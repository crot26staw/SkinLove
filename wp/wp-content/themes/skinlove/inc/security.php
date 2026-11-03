<?php
/**
 * Закрывает то, чем сайт не пользуется, а боты пользуются: комментарии,
 * XML-RPC, перечисление пользователей. Плюс защитные заголовки ответа.
 *
 * HSTS здесь не ставится: его включают на сервере, когда HTTPS на домене
 * и поддоменах точно останется навсегда.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/*
 * Комментариев на сайте нет, но у вложений они открыты по умолчанию,
 * и wp-comments-post.php принимает спам от кого угодно. Закрытые
 * комментарии ядро отклоняет и там, и в REST.
 */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/*
 * XML-RPC: system.multicall перебирает сотни паролей за один запрос,
 * pingback.ping годится для DDoS чужих сайтов. Пустой список методов
 * отключает и то и другое, заголовок X-Pingback больше не нужен.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );
add_filter( 'xmlrpc_methods', '__return_empty_array' );

/**
 * Убирает заголовок X-Pingback.
 *
 * @param array $headers Заголовки ответа.
 * @return array
 */
function skinlove_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );

	return $headers;
}
add_filter( 'wp_headers', 'skinlove_remove_pingback_header' );

/**
 * Список пользователей в REST — только для вошедших: гостю он раскрывает
 * логины. Админке маршрут нужен, поэтому снимаем его лишь у гостей.
 *
 * @param array $endpoints Маршруты REST.
 * @return array
 */
function skinlove_hide_users_endpoint( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}

	return array_filter(
		$endpoints,
		static fn( $route ) => ! str_starts_with( $route, '/wp/v2/users' ),
		ARRAY_FILTER_USE_KEY
	);
}
add_filter( 'rest_endpoints', 'skinlove_hide_users_endpoint' );

/**
 * Архивы авторов отдают 404: блога нет, а ?author=1 уводит на
 * /author/{логин}/.
 *
 * @param array $query_vars Переменные запроса.
 * @return array
 */
function skinlove_disable_author_archives( $query_vars ) {
	if ( is_admin() || ( ! isset( $query_vars['author'] ) && ! isset( $query_vars['author_name'] ) ) ) {
		return $query_vars;
	}

	return array( 'error' => '404' );
}
add_filter( 'request', 'skinlove_disable_author_archives' );

/**
 * oEmbed-ответ без автора страницы — там тоже имя и ссылка на архив автора.
 *
 * @param array $data Данные ответа.
 * @return array
 */
function skinlove_oembed_without_author( $data ) {
	unset( $data['author_name'], $data['author_url'] );

	return $data;
}
add_filter( 'oembed_response_data', 'skinlove_oembed_without_author' );

/**
 * Защитные заголовки и без версии PHP.
 */
function skinlove_security_headers() {
	header_remove( 'X-Powered-By' );
	header( 'X-Content-Type-Options: nosniff' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
}
add_action( 'send_headers', 'skinlove_security_headers' );
