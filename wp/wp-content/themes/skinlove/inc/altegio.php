<?php
/**
 * Отправка заявок в CRM Altegio.
 *
 * Лидов в Altegio нет, поэтому заявка становится клиентом: ищем его по
 * телефону, нет — создаём, и пишем в карточку комментарий с данными заявки.
 * Если задан тег, он вешается на нового клиента.
 *
 * Ключи — константы в wp-config.php, в репозитории их нет:
 * SKINLOVE_ALTEGIO_PARTNER_TOKEN, SKINLOVE_ALTEGIO_USER_TOKEN,
 * SKINLOVE_ALTEGIO_LOCATION_ID и необязательная SKINLOVE_ALTEGIO_TAG_ID.
 * Без них заявки только сохраняются в админке.
 *
 * Отправка идёт после ответа посетителю: ждать трёх запросов к чужому API
 * ему незачем. Не получилось — повтор по wp-cron, всего SKINLOVE_ALTEGIO_TRIES
 * попыток.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

const SKINLOVE_ALTEGIO_API         = 'https://api.alteg.io/api/v1';
const SKINLOVE_ALTEGIO_TRIES       = 5;
const SKINLOVE_ALTEGIO_RETRY_HOOK  = 'skinlove_altegio_retry';
const SKINLOVE_ALTEGIO_TIMEOUT     = 10;
const SKINLOVE_ALTEGIO_COMMENT_MAX = 255;

/**
 * Заданы ли ключи Altegio.
 *
 * @return bool
 */
function skinlove_altegio_enabled() {
	return defined( 'SKINLOVE_ALTEGIO_PARTNER_TOKEN' ) && SKINLOVE_ALTEGIO_PARTNER_TOKEN
		&& defined( 'SKINLOVE_ALTEGIO_USER_TOKEN' ) && SKINLOVE_ALTEGIO_USER_TOKEN
		&& defined( 'SKINLOVE_ALTEGIO_LOCATION_ID' ) && (int) SKINLOVE_ALTEGIO_LOCATION_ID;
}

/**
 * Ставит заявку в очередь: отправка после того, как посетитель получил ответ.
 *
 * @param int $lead_id ID заявки.
 */
function skinlove_altegio_queue( $lead_id ) {
	if ( ! skinlove_altegio_enabled() ) {
		return;
	}

	update_post_meta( $lead_id, 'crm_status', 'pending' );

	add_action(
		'shutdown',
		function () use ( $lead_id ) {
			if ( function_exists( 'litespeed_finish_request' ) ) {
				litespeed_finish_request();
			} elseif ( function_exists( 'fastcgi_finish_request' ) ) {
				fastcgi_finish_request();
			}

			skinlove_altegio_push( $lead_id );
		}
	);
}

/**
 * Отправляет заявку и записывает итог в мету: crm_status (sent, pending,
 * error), crm_client_id, crm_error, crm_tries.
 *
 * @param int $lead_id ID заявки.
 */
function skinlove_altegio_push( $lead_id ) {
	$lead_id = (int) $lead_id;

	if ( ! skinlove_altegio_enabled() || 'sent' === get_post_meta( $lead_id, 'crm_status', true ) ) {
		return;
	}

	$tries = (int) get_post_meta( $lead_id, 'crm_tries', true ) + 1;
	update_post_meta( $lead_id, 'crm_tries', $tries );

	$client_id = skinlove_altegio_deliver( $lead_id );

	if ( ! is_wp_error( $client_id ) ) {
		update_post_meta( $lead_id, 'crm_status', 'sent' );
		update_post_meta( $lead_id, 'crm_client_id', $client_id );
		delete_post_meta( $lead_id, 'crm_error' );
		return;
	}

	update_post_meta( $lead_id, 'crm_error', mb_substr( $client_id->get_error_message(), 0, 300 ) );

	if ( $tries >= SKINLOVE_ALTEGIO_TRIES ) {
		update_post_meta( $lead_id, 'crm_status', 'error' );
		return;
	}

	update_post_meta( $lead_id, 'crm_status', 'pending' );
	// 5, 10, 20, 40 минут.
	wp_schedule_single_event( time() + 5 * MINUTE_IN_SECONDS * 2 ** ( $tries - 1 ), SKINLOVE_ALTEGIO_RETRY_HOOK, array( $lead_id ) );
}
add_action( SKINLOVE_ALTEGIO_RETRY_HOOK, 'skinlove_altegio_push' );

/**
 * Находит или создаёт клиента и пишет комментарий с заявкой.
 * Клиент сохраняется в мету сразу: при повторе после ошибки на комментарии
 * второй клиент не создастся.
 *
 * @param int $lead_id ID заявки.
 * @return int|WP_Error ID клиента в Altegio.
 */
function skinlove_altegio_deliver( $lead_id ) {
	$name   = get_the_title( $lead_id );
	$phone  = '+' . preg_replace( '/\D/', '', get_post_meta( $lead_id, 'phone', true ) );
	$client = (int) get_post_meta( $lead_id, 'crm_client_id', true );

	if ( ! $client ) {
		$client = skinlove_altegio_find_client( $phone );
	}

	if ( ! is_wp_error( $client ) && ! $client ) {
		$client = skinlove_altegio_create_client( $name, $phone );
	}

	if ( is_wp_error( $client ) ) {
		return $client;
	}

	update_post_meta( $lead_id, 'crm_client_id', $client );

	$sent = (int) get_post_meta( $lead_id, 'crm_comments_sent', true );
	$path = sprintf( '/locations/%d/clients/%d/comments', SKINLOVE_ALTEGIO_LOCATION_ID, $client );

	foreach ( array_slice( skinlove_altegio_comments( $lead_id ), $sent ) as $text ) {
		$result = skinlove_altegio_request( 'POST', $path, array( 'text' => $text ) );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		update_post_meta( $lead_id, 'crm_comments_sent', ++$sent );
	}

	return $client;
}

/**
 * ID клиента локации по телефону, 0 — не найден.
 *
 * @param string $phone Телефон в виде +цифры.
 * @return int|WP_Error
 */
function skinlove_altegio_find_client( $phone ) {
	$result = skinlove_altegio_request(
		'POST',
		sprintf( '/locations/%d/clients/search', SKINLOVE_ALTEGIO_LOCATION_ID ),
		array(
			'page'      => 1,
			'page_size' => 10,
			'fields'    => array( 'id', 'phone' ),
			'filters'   => array(
				array(
					'type'  => 'quick_search',
					'state' => array( 'value' => ltrim( $phone, '+' ) ),
				),
			),
		)
	);

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	// Быстрый поиск ищет и по подстроке — берём только точное совпадение.
	foreach ( (array) ( $result['data'] ?? array() ) as $row ) {
		if ( preg_replace( '/\D/', '', (string) ( $row['phone'] ?? '' ) ) === ltrim( $phone, '+' ) ) {
			return (int) $row['id'];
		}
	}

	return 0;
}

/**
 * Создаёт клиента и возвращает его ID.
 *
 * @param string $name  Имя.
 * @param string $phone Телефон в виде +цифры.
 * @return int|WP_Error
 */
function skinlove_altegio_create_client( $name, $phone ) {
	$body = array(
		'name'  => $name,
		'phone' => $phone,
	);

	if ( defined( 'SKINLOVE_ALTEGIO_TAG_ID' ) && (int) SKINLOVE_ALTEGIO_TAG_ID ) {
		$body['categories'] = array( (int) SKINLOVE_ALTEGIO_TAG_ID );
	}

	$result = skinlove_altegio_request( 'POST', sprintf( '/clients/%d', SKINLOVE_ALTEGIO_LOCATION_ID ), $body );

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	$id = (int) ( $result['data']['id'] ?? $result['id'] ?? 0 );

	return $id ? $id : new WP_Error( 'skinlove_altegio', 'Altegio не вернул ID клиента' );
}

/**
 * Текст заявки для карточки клиента, порезанный на комментарии по 255
 * символов — длиннее Altegio не принимает. Режем по строкам, а строку
 * длиннее лимита — по словам.
 *
 * @param int $lead_id ID заявки.
 * @return string[]
 */
function skinlove_altegio_comments( $lead_id ) {
	$form  = get_post_meta( $lead_id, 'form', true );
	$lines = array(
		'Заявка с сайта: ' . ( skinlove_lead_forms()[ $form ] ?? $form ),
		'Источник: ' . skinlove_lead_source( $lead_id ),
		'Язык: ' . strtoupper( get_post_meta( $lead_id, 'lang', true ) ),
	);

	foreach ( skinlove_lead_answer_rows( $lead_id ) as $question => $answer ) {
		$lines[] = "{$question}: {$answer}";
	}

	$lines = array_filter( $lines, fn( $line ) => ! preg_match( '/:\s*$/u', $line ) );

	$chunks  = array();
	$current = '';

	foreach ( $lines as $line ) {
		foreach ( skinlove_altegio_split( $line ) as $piece ) {
			$joined = '' === $current ? $piece : "{$current}\n{$piece}";

			if ( mb_strlen( $joined ) > SKINLOVE_ALTEGIO_COMMENT_MAX ) {
				$chunks[] = $current;
				$joined   = $piece;
			}

			$current = $joined;
		}
	}

	$chunks[] = $current;

	return array_values( array_filter( $chunks, 'strlen' ) );
}

/**
 * Строка, порезанная по словам на куски не длиннее лимита комментария.
 *
 * @param string $line Строка.
 * @return string[]
 */
function skinlove_altegio_split( $line ) {
	$pieces  = array();
	$current = '';

	foreach ( preg_split( '/\s+/u', $line ) as $word ) {
		$joined = '' === $current ? $word : "{$current} {$word}";

		if ( mb_strlen( $joined ) > SKINLOVE_ALTEGIO_COMMENT_MAX ) {
			$pieces[] = $current;
			$joined   = mb_substr( $word, 0, SKINLOVE_ALTEGIO_COMMENT_MAX );
		}

		$current = $joined;
	}

	$pieces[] = $current;

	return array_filter( $pieces, 'strlen' );
}

/**
 * Запрос к API Altegio.
 *
 * @param string $method Метод.
 * @param string $path   Путь от /api/v1.
 * @param array  $body   Тело запроса.
 * @return array|WP_Error Разобранный ответ.
 */
function skinlove_altegio_request( $method, $path, $body ) {
	$response = wp_remote_request(
		SKINLOVE_ALTEGIO_API . $path,
		array(
			'method'  => $method,
			'timeout' => SKINLOVE_ALTEGIO_TIMEOUT,
			'headers' => array(
				'Accept'        => 'application/vnd.api.v2+json',
				'Content-Type'  => 'application/json',
				'Authorization' => sprintf( 'Bearer %s, User %s', SKINLOVE_ALTEGIO_PARTNER_TOKEN, SKINLOVE_ALTEGIO_USER_TOKEN ),
			),
			'body'    => wp_json_encode( $body ),
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = (int) wp_remote_retrieve_response_code( $response );
	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( $code < 200 || $code >= 300 || ! is_array( $data ) || false === ( $data['success'] ?? true ) ) {
		$message = is_array( $data ) ? ( $data['meta']['message'] ?? '' ) : '';
		return new WP_Error( 'skinlove_altegio', trim( "HTTP {$code} {$path} {$message}" ) );
	}

	return $data;
}

/**
 * Статус отправки в CRM для админки.
 *
 * @param int $lead_id ID заявки.
 * @return string
 */
function skinlove_altegio_status_label( $lead_id ) {
	$status = get_post_meta( $lead_id, 'crm_status', true );
	$error  = get_post_meta( $lead_id, 'crm_error', true );

	switch ( $status ) {
		case 'sent':
			return 'Отправлена';
		case 'pending':
			return $error ? "Повтор: {$error}" : 'В очереди';
		case 'error':
			return "Не отправлена: {$error}";
		default:
			return 'Не отправлялась';
	}
}
