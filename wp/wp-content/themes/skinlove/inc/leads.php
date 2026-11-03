<?php
/**
 * Приём заявок со всех форм сайта.
 *
 * Заявка сначала сохраняется в CPT sl_lead и только потом уходит в CRM:
 * если CRM недоступна, лид остаётся в админке. Вместе с именем и телефоном
 * приходит источник — страница, секция, элемент (программа, акция,
 * процедура) и тип формы, у квиза — ответы.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

const SKINLOVE_LEAD_POST_TYPE = 'sl_lead';
const SKINLOVE_RATE_LIMIT     = 5;
const SKINLOVE_RATE_WINDOW    = HOUR_IN_SECONDS;
const SKINLOVE_ANSWERS_MAX    = 30;
const SKINLOVE_ANSWER_ITEMS   = 20;
const SKINLOVE_LEAD_BODY_MAX  = 16 * KB_IN_BYTES;

/**
 * Типы форм: значение поля form => подпись в админке.
 *
 * @return array<string,string>
 */
function skinlove_lead_forms() {
	return array(
		'modal'           => 'Модалка',
		'cta'             => 'Блок «Записаться»',
		'contacts'        => 'Контакты',
		'program-request' => 'Заявка на программу',
		'outcall-hero'    => 'Outcall: первый экран',
		'quiz'            => 'Квиз',
	);
}

/**
 * Служебные поля заявки — всё остальное из формы считается ответами.
 *
 * @return string[]
 */
function skinlove_lead_known_fields() {
	return array( 'name', 'phone', 'lang', 'form', 'page_id', 'section', 'item', 'website' );
}

/**
 * Подписи ответов квиза в карточке заявки: ключ поля формы => вопрос.
 * Пара «значение — не определился» делит одну подпись.
 *
 * @return array<string,string>
 */
function skinlove_lead_answer_labels() {
	return array(
		'concern'         => 'Что беспокоит',
		'concern_other'   => 'Своими словами',
		'place'           => 'Где удобно',
		'date'            => 'Когда удобно',
		'date_unknown'    => 'Когда удобно',
		'address'         => 'Адрес выезда',
		'address_unknown' => 'Адрес выезда',
	);
}

/**
 * Тип записи «Заявки» — только для админки. Права как у страниц: заявки
 * с именами и телефонами видят редактор и администратор, но не автор
 * и не участник, у которых есть права на записи.
 */
function skinlove_register_lead_post_type() {
	register_post_type(
		SKINLOVE_LEAD_POST_TYPE,
		array(
			'labels'          => array(
				'name'          => 'Заявки',
				'singular_name' => 'Заявка',
				'menu_name'     => 'Заявки',
				'edit_item'     => 'Заявка',
				'search_items'  => 'Найти заявку',
				'not_found'     => 'Заявок пока нет',
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-email-alt',
			'menu_position'   => 4,
			'supports'        => array( 'title' ),
			'capability_type' => 'page',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
			'rewrite'         => false,
			'query_var'       => false,
			'show_in_rest'    => false,
		)
	);
}
add_action( 'init', 'skinlove_register_lead_post_type' );

/**
 * Источник заявки одной строкой: «Страница · секция · элемент».
 *
 * @param int $lead_id ID заявки.
 * @return string
 */
function skinlove_lead_source( $lead_id ) {
	$parts = array(
		get_post_meta( $lead_id, 'page_title', true ),
		get_post_meta( $lead_id, 'section', true ),
		get_post_meta( $lead_id, 'item', true ),
	);

	return implode( ' · ', array_filter( $parts ) );
}

/**
 * Колонки списка заявок.
 *
 * @param array $columns Колонки по умолчанию.
 * @return array
 */
function skinlove_lead_columns( $columns ) {
	return array(
		'cb'        => $columns['cb'] ?? '',
		'title'     => 'Имя',
		'sl_phone'  => 'Телефон',
		'sl_form'   => 'Форма',
		'sl_source' => 'Источник',
		'sl_lang'   => 'Язык',
		'date'      => 'Получена',
	);
}
add_filter( 'manage_' . SKINLOVE_LEAD_POST_TYPE . '_posts_columns', 'skinlove_lead_columns' );

/**
 * Содержимое колонок списка заявок.
 *
 * @param string $column  Имя колонки.
 * @param int    $post_id ID заявки.
 */
function skinlove_lead_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'sl_phone':
			$phone = get_post_meta( $post_id, 'phone', true );
			printf( '<a href="tel:+%s">%s</a>', esc_attr( preg_replace( '/\D/', '', $phone ) ), esc_html( $phone ) );
			break;

		case 'sl_form':
			$form = get_post_meta( $post_id, 'form', true );
			echo esc_html( skinlove_lead_forms()[ $form ] ?? $form );
			break;

		case 'sl_source':
			echo esc_html( skinlove_lead_source( $post_id ) );
			break;

		case 'sl_lang':
			echo esc_html( strtoupper( get_post_meta( $post_id, 'lang', true ) ) );
			break;
	}
}
add_action( 'manage_' . SKINLOVE_LEAD_POST_TYPE . '_posts_custom_column', 'skinlove_lead_column_content', 10, 2 );

/**
 * Поля заявки на экране редактирования.
 */
function skinlove_lead_meta_box() {
	add_meta_box(
		'skinlove_lead_details',
		'Данные заявки',
		'skinlove_render_lead_meta_box',
		SKINLOVE_LEAD_POST_TYPE,
		'normal'
	);
}
add_action( 'add_meta_boxes', 'skinlove_lead_meta_box' );

/**
 * Таблица с данными заявки.
 *
 * @param WP_Post $post Заявка.
 */
function skinlove_render_lead_meta_box( $post ) {
	$form = get_post_meta( $post->ID, 'form', true );
	$page = (int) get_post_meta( $post->ID, 'page_id', true );

	$rows = array(
		'Телефон'  => get_post_meta( $post->ID, 'phone', true ),
		'Язык'     => strtoupper( get_post_meta( $post->ID, 'lang', true ) ),
		'Форма'    => skinlove_lead_forms()[ $form ] ?? $form,
		'Страница' => get_post_meta( $post->ID, 'page_title', true ),
		'Секция'   => get_post_meta( $post->ID, 'section', true ),
		'Элемент'  => get_post_meta( $post->ID, 'item', true ),
	);

	foreach ( (array) get_post_meta( $post->ID, 'answers', true ) as $key => $answer ) {
		$question          = skinlove_lead_answer_labels()[ $key ] ?? $key;
		$answer            = is_array( $answer ) ? implode( ', ', $answer ) : $answer;
		$rows[ $question ] = isset( $rows[ $question ] ) ? "{$rows[ $question ]}, {$answer}" : $answer;
	}

	echo '<table class="widefat striped"><tbody>';

	foreach ( array_filter( $rows, 'strlen' ) as $label => $value ) {
		printf( '<tr><th style="width:160px">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( $value ) );
	}

	echo '</tbody></table>';

	if ( $page && get_permalink( $page ) ) {
		printf( '<p><a href="%s" target="_blank">Открыть страницу</a></p>', esc_url( get_permalink( $page ) ) );
	}
}

/**
 * Эндпоинт POST /wp-json/skinlove/v1/lead.
 *
 * Публичный по назначению: его вызывают анонимные посетители. Nonce не
 * проверяется — под кэшем он протухает и ломает отправку живым людям,
 * ничего не давая против ботов. От автоматических отправок защищают
 * ловушка-honeypot и лимит на IP.
 */
function skinlove_register_lead_route() {
	register_rest_route(
		'skinlove/v1',
		'/lead',
		array(
			'methods'             => 'POST',
			'callback'            => 'skinlove_handle_lead',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'skinlove_register_lead_route' );

/**
 * Не превышен ли лимит заявок с одного адреса. IP не сохраняется —
 * в ключ идёт его хэш, и тот живёт час.
 *
 * @return bool
 */
function skinlove_rate_limit_ok() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	if ( '' === $ip ) {
		return true;
	}

	$key   = 'sl_lead_' . md5( $ip . wp_salt() );
	$count = (int) get_transient( $key );

	if ( $count >= SKINLOVE_RATE_LIMIT ) {
		return false;
	}

	set_transient( $key, $count + 1, SKINLOVE_RATE_WINDOW );

	return true;
}

/**
 * Ошибка заявки с текстом на языке формы.
 *
 * @param string $key    Ключ словаря.
 * @param int    $status HTTP-статус.
 * @return WP_Error
 */
function skinlove_lead_error( $key, $status ) {
	return new WP_Error( "skinlove_{$key}", skinlove_t( $key ), array( 'status' => $status ) );
}

/**
 * Ответы из формы, кроме служебных полей: строки или списки строк.
 *
 * @param array $params Параметры запроса.
 * @return array<string,string|string[]>
 */
function skinlove_lead_answers( $params ) {
	$answers = array();

	foreach ( $params as $key => $value ) {
		if ( count( $answers ) >= SKINLOVE_ANSWERS_MAX || in_array( $key, skinlove_lead_known_fields(), true ) ) {
			continue;
		}

		$key   = sanitize_key( $key );
		$value = is_array( $value )
			? array_slice( array_values( array_filter( array_map( 'skinlove_lead_text', $value ), 'strlen' ) ), 0, SKINLOVE_ANSWER_ITEMS )
			: skinlove_lead_text( $value );

		if ( '' !== $key && array() !== $value && '' !== $value ) {
			$answers[ $key ] = $value;
		}
	}

	return $answers;
}

/**
 * Строка из формы: без тегов и не длиннее 500 символов.
 *
 * @param mixed $value Значение.
 * @return string
 */
function skinlove_lead_text( $value ) {
	return is_scalar( $value ) ? mb_substr( sanitize_text_field( (string) $value ), 0, 500 ) : '';
}

/**
 * Обработка заявки.
 *
 * @param WP_REST_Request $request Запрос.
 * @return WP_REST_Response|WP_Error
 */
function skinlove_handle_lead( WP_REST_Request $request ) {
	$params = $request->get_json_params() ?: $request->get_body_params();
	$params = is_array( $params ) ? $params : array();

	$lang                     = 'en' === ( $params['lang'] ?? '' ) ? 'en' : 'ru';
	$GLOBALS['skinlove_lang'] = $lang;

	// Настоящая заявка с квизом укладывается в пару килобайт, а без
	// ограничения один запрос кладёт в базу всё, что пропустит PHP.
	if ( strlen( $request->get_body() ) > SKINLOVE_LEAD_BODY_MAX ) {
		return skinlove_lead_error( 'form_failure', 413 );
	}

	// Ловушка для ботов: человек это поле не видит. Отвечаем как при успехе,
	// чтобы не подсказывать, что заявка отброшена.
	if ( '' !== skinlove_lead_text( $params['website'] ?? '' ) ) {
		return new WP_REST_Response( array( 'success' => true ), 200 );
	}

	$name   = skinlove_lead_text( $params['name'] ?? '' );
	$phone  = skinlove_lead_text( $params['phone'] ?? '' );
	$digits = preg_replace( '/\D/', '', $phone );

	if ( mb_strlen( preg_replace( '/[\s-]/u', '', $name ) ) < 2 ) {
		return skinlove_lead_error( 'lead_bad_name', 400 );
	}

	if ( strlen( $digits ) < 7 || strlen( $digits ) > 15 ) {
		return skinlove_lead_error( 'lead_bad_phone', 400 );
	}

	// Лимит считается только по принятым заявкам: иначе человек, несколько
	// раз ошибившийся в телефоне, окажется заблокирован на час.
	if ( ! skinlove_rate_limit_ok() ) {
		return skinlove_lead_error( 'lead_rate_limited', 429 );
	}

	$form    = (string) ( $params['form'] ?? '' );
	$form    = isset( skinlove_lead_forms()[ $form ] ) ? $form : '';
	$page_id = absint( $params['page_id'] ?? 0 );
	$page_id = $page_id && 'publish' === get_post_status( $page_id ) ? $page_id : 0;

	$lead = array(
		'name'       => $name,
		'phone'      => $phone,
		'lang'       => $lang,
		'form'       => $form,
		'page_id'    => $page_id,
		'page_title' => $page_id ? get_the_title( $page_id ) : '',
		'section'    => sanitize_key( $params['section'] ?? '' ),
		'item'       => skinlove_lead_text( $params['item'] ?? '' ),
		'answers'    => skinlove_lead_answers( $params ),
	);

	$lead_id = wp_insert_post(
		array(
			'post_type'   => SKINLOVE_LEAD_POST_TYPE,
			'post_title'  => $name,
			'post_status' => 'publish',
			'meta_input'  => array_diff_key( $lead, array( 'name' => true ) ),
		),
		true
	);

	if ( is_wp_error( $lead_id ) ) {
		return skinlove_lead_error( 'form_failure', 500 );
	}

	skinlove_send_lead_to_crm( $lead_id, $lead );

	return new WP_REST_Response( array( 'success' => true ), 200 );
}

/**
 * Отправка заявки в CRM. Подключается отдельной задачей (решение 31):
 * пока заявки только сохраняются в админке. Заявка к этому моменту уже
 * в базе, поэтому ошибка отправки не должна ронять ответ посетителю.
 *
 * @param int   $lead_id ID сохранённой заявки.
 * @param array $lead    Данные заявки.
 */
function skinlove_send_lead_to_crm( $lead_id, $lead ) {
}

/**
 * Скрытые поля формы заявки: тип, страница, язык, источник и ловушка.
 * Секцию и элемент у модалки подставляет скрипт по кнопке, которая её открыла.
 *
 * @param string $form    Тип формы из skinlove_lead_forms().
 * @param string $section data-section секции, в которой стоит форма.
 * @param string $item    Элемент источника, если он известен заранее.
 */
function skinlove_lead_fields( $form, $section = '', $item = '' ) {
	printf( '<input type="hidden" name="form" value="%s">', esc_attr( $form ) );
	printf( '<input type="hidden" name="page_id" value="%d">', (int) get_queried_object_id() );
	printf( '<input type="hidden" name="lang" value="%s">', esc_attr( skinlove_lang() ) );
	printf( '<input type="hidden" name="section" value="%s" data-lead-section>', esc_attr( $section ) );
	printf( '<input type="hidden" name="item" value="%s" data-lead-item>', esc_attr( $item ) );
	echo '<label class="form__trap" aria-hidden="true">Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>';
}

/**
 * Сообщение об ошибке отправки. Скрипт подставляет в него текст ответа
 * сервера, а если ответа нет — оставляет этот.
 */
function skinlove_form_failure() {
	printf( '<p class="form__failure" data-form-failure role="alert" hidden>%s</p>', esc_html( skinlove_t( 'form_failure' ) ) );
}

/**
 * Атрибут action формы заявки.
 */
function skinlove_lead_action() {
	echo esc_url( rest_url( 'skinlove/v1/lead' ) );
}
