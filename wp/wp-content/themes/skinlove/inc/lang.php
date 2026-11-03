<?php
/**
 * Два языка без плагина: русский на /, английский на /en/.
 *
 * Префикс /en/ снимается с REQUEST_URI до разбора запроса, и WordPress
 * находит страницу по обычному slug. После разбора адрес возвращается
 * как был, чтобы redirect_canonical сравнивал его с уже локализованными
 * ссылками и не уводил в цикл. В EN-режиме home_url получает /en/, так что
 * все ссылки сайта остаются в выбранном языке. Админку, AJAX, cron, REST
 * и WP-CLI это не трогает.
 *
 * Пустое значение поля — пустая строка, фолбэка на русский нет:
 * шаблон в этом случае элемент не выводит.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Текущий язык.
 *
 * @return string 'ru' | 'en'
 */
function skinlove_lang() {
	return $GLOBALS['skinlove_lang'] ?? 'ru';
}

/**
 * Путь домашнего адреса без завершающего слэша: '' или '/subdir'.
 *
 * @return string
 */
function skinlove_home_path() {
	return untrailingslashit( (string) wp_parse_url( get_option( 'home' ), PHP_URL_PATH ) );
}

/**
 * Определяет язык по префиксу и снимает его с адреса запроса.
 * Заодно запоминает путь страницы без префикса — для переключателя языка.
 */
function skinlove_detect_lang() {
	if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'WP_CLI' ) && WP_CLI ) || empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	$uri    = $_SERVER['REQUEST_URI']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$base   = skinlove_home_path();
	$prefix = $base . '/en';
	$is_en  = (bool) preg_match( '#^' . preg_quote( $prefix, '#' ) . '(?=/|\?|$)#', $uri );

	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$path = substr( $path, strlen( $is_en ? $prefix : $base ) );

	$GLOBALS['skinlove_path'] = '/' . ltrim( $path, '/' );

	if ( ! $is_en ) {
		return;
	}

	$rest = substr( $uri, strlen( $prefix ) );
	if ( '' === $rest || '?' === $rest[0] ) {
		$rest = '/' . $rest;
	}

	$GLOBALS['skinlove_lang']        = 'en';
	$GLOBALS['skinlove_request_uri'] = $uri;
	$_SERVER['REQUEST_URI']          = $base . $rest;
}
add_action( 'after_setup_theme', 'skinlove_detect_lang', 0 );

/**
 * Возвращает исходный адрес запроса после разбора.
 */
function skinlove_restore_request_uri() {
	if ( isset( $GLOBALS['skinlove_request_uri'] ) ) {
		$_SERVER['REQUEST_URI'] = $GLOBALS['skinlove_request_uri'];
	}
}
add_action( 'parse_request', 'skinlove_restore_request_uri' );

/**
 * В EN-режиме добавляет /en к ссылкам сайта. REST не трогаем: эндпоинты
 * одни на оба языка, язык заявки приходит полем формы.
 *
 * @param string      $url  Готовый адрес.
 * @param string|null $path Путь, переданный в home_url().
 * @return string
 */
function skinlove_localize_home_url( $url, $path ) {
	if ( 'en' !== skinlove_lang() ) {
		return $url;
	}

	if ( str_starts_with( ltrim( (string) $path, '/' ), rest_get_url_prefix() ) ) {
		return $url;
	}

	$home = untrailingslashit( set_url_scheme( get_option( 'home' ), wp_parse_url( $url, PHP_URL_SCHEME ) ) );

	if ( ! str_starts_with( $url, $home ) ) {
		return $url;
	}

	return $home . '/en' . substr( $url, strlen( $home ) );
}
add_filter( 'home_url', 'skinlove_localize_home_url', 10, 2 );

/**
 * Адрес текущей страницы на другом языке.
 *
 * @param string $lang 'ru' | 'en'.
 * @return string
 */
function skinlove_lang_url( $lang ) {
	$home = untrailingslashit( get_option( 'home' ) );

	return $home . ( 'en' === $lang ? '/en' : '' ) . ( $GLOBALS['skinlove_path'] ?? '/' );
}

/**
 * Постоянная ссылка записи на заданном языке — для hreflang. Язык
 * подменяется на время вызова, и ссылку собирает тот же фильтр home_url.
 *
 * @param string $lang    'ru' | 'en'.
 * @param int    $post_id ID записи.
 * @return string
 */
function skinlove_permalink_in( $lang, $post_id ) {
	$current                  = skinlove_lang();
	$GLOBALS['skinlove_lang'] = $lang;
	$url                      = (string) get_permalink( $post_id );
	$GLOBALS['skinlove_lang'] = $current;

	return $url;
}

/**
 * Атрибут lang у <html> — по текущему языку, а не по локали сайта.
 * По нему же календарь выбирает локаль.
 *
 * @param string $output Атрибуты, собранные WordPress.
 * @return string
 */
function skinlove_language_attributes( $output ) {
	if ( is_admin() ) {
		return $output;
	}

	return preg_replace( '/lang="[^"]*"/', 'lang="' . skinlove_lang() . '"', $output );
}
add_filter( 'language_attributes', 'skinlove_language_attributes' );

/**
 * Строка из словаря на текущем языке.
 *
 * @param string $key Ключ из skinlove_dictionary().
 * @return string
 */
function skinlove_t( $key ) {
	static $dictionary = null;
	$dictionary ??= skinlove_dictionary();

	if ( ! isset( $dictionary[ $key ] ) ) {
		_doing_it_wrong( __FUNCTION__, esc_html( "Нет строки «{$key}» в словаре." ), SKINLOVE_VERSION );
		return '';
	}

	return $dictionary[ $key ][ skinlove_lang() ];
}

/**
 * Приводит значение поля к строке без пробелов по краям.
 *
 * @param mixed $value Значение из Carbon Fields.
 * @return string
 */
function skinlove_clean( $value ) {
	return is_string( $value ) ? trim( $value ) : '';
}

/**
 * Двуязычное поле записи: sl_{$name}_{$lang}.
 *
 * @param string   $name    Имя поля без префикса и языка.
 * @param int|null $post_id ID записи, по умолчанию текущая.
 * @return string
 */
function skinlove_field( $name, $post_id = null ) {
	if ( ! skinlove_carbon_ready() ) {
		return '';
	}

	$lang = skinlove_lang();

	return skinlove_clean( carbon_get_post_meta( $post_id ?? get_the_ID(), "sl_{$name}_{$lang}" ) );
}

/**
 * Двуязычное поле элемента complex: {$name}_{$lang}.
 *
 * @param array  $entry Элемент complex.
 * @param string $name  Имя поля без языка.
 * @return string
 */
function skinlove_entry( $entry, $name ) {
	return skinlove_clean( $entry[ $name . '_' . skinlove_lang() ] ?? '' );
}

/**
 * Двуязычная настройка сайта: sl_{$name}_{$lang}.
 *
 * @param string $name Имя поля без префикса и языка.
 * @return string
 */
function skinlove_option( $name ) {
	if ( ! skinlove_carbon_ready() ) {
		return '';
	}

	$lang = skinlove_lang();

	return skinlove_clean( carbon_get_theme_option( "sl_{$name}_{$lang}" ) );
}

/**
 * Настройка сайта, одна на оба языка: телефоны, почта, код карты.
 *
 * @param string $name Имя поля без префикса.
 * @return mixed Строка обрезается по краям, остальное — как есть.
 */
function skinlove_option_raw( $name ) {
	if ( ! skinlove_carbon_ready() ) {
		return '';
	}

	$value = carbon_get_theme_option( "sl_{$name}" );

	return is_string( $value ) ? trim( $value ) : $value;
}
