<?php
/**
 * SEO без плагина: title и description на каждом языке у каждой страницы.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;

/**
 * Вкладка «SEO» у страниц.
 */
function skinlove_register_seo_fields() {
	Container::make( 'post_meta', 'SEO' )
		->where( 'post_type', '=', 'page' )
		->set_context( 'side' )
		->add_fields(
			array_merge(
				skinlove_pair( 'text', 'seo_title', 'Title', 'skinlove_full_width' ),
				skinlove_pair( 'textarea', 'seo_description', 'Description', 'skinlove_full_width' )
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_seo_fields' );

/**
 * Title страницы из SEO-поля. Если поле пустое: в RU — заголовок WordPress,
 * в EN — название сайта, чтобы не показывать русский заголовок.
 *
 * @param string $title Title, собранный раньше.
 * @return string
 */
function skinlove_document_title( $title ) {
	if ( ! is_singular() ) {
		return $title;
	}

	$seo_title = skinlove_field( 'seo_title', get_queried_object_id() );

	if ( '' !== $seo_title ) {
		return $seo_title;
	}

	return 'en' === skinlove_lang() ? get_bloginfo( 'name' ) : $title;
}
add_filter( 'pre_get_document_title', 'skinlove_document_title' );

/**
 * Description и Open Graph.
 */
function skinlove_meta_tags() {
	if ( ! is_singular() ) {
		return;
	}

	$id          = get_queried_object_id();
	$title       = skinlove_field( 'seo_title', $id );
	$description = skinlove_field( 'seo_description', $id );

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	if ( '' !== $title ) {
		printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	}

	if ( '' !== $description ) {
		printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	}

	echo '<meta property="og:type" content="website">' . "\n";

	$locales = array(
		'ru' => 'ru_RU',
		'en' => 'en_GB',
	);
	$lang    = skinlove_lang();

	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( skinlove_permalink_in( $lang, $id ) ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( $locales[ $lang ] ) );
	printf( '<meta property="og:locale:alternate" content="%s">' . "\n", esc_attr( $locales[ 'en' === $lang ? 'ru' : 'en' ] ) );

	$image = get_the_post_thumbnail_url( $id, 'large' );
	if ( $image ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	}
}
add_action( 'wp_head', 'skinlove_meta_tags', 1 );

/**
 * Альтернативные языковые версии страницы. x-default — основной язык
 * (решение 2). Canonical выводит сам WordPress: он собирается через
 * home_url и уже указывает на текущий язык.
 */
function skinlove_hreflang() {
	if ( ! is_singular() ) {
		return;
	}

	$id   = get_queried_object_id();
	$urls = array(
		'ru'        => skinlove_permalink_in( 'ru', $id ),
		'en'        => skinlove_permalink_in( 'en', $id ),
		'x-default' => skinlove_permalink_in( 'ru', $id ),
	);

	foreach ( $urls as $hreflang => $url ) {
		printf( '<link rel="alternate" hreflang="%s" href="%s">' . "\n", esc_attr( $hreflang ), esc_url( $url ) );
	}
}
add_action( 'wp_head', 'skinlove_hreflang', 2 );

/**
 * В карте сайта — только страницы: записей и рубрик на сайте нет, а карта
 * авторов раскрывает логины. Английские адреса поисковик находит по hreflang.
 *
 * @param array  $provider Провайдер карты.
 * @param string $name     Его имя.
 * @return array|false
 */
function skinlove_sitemap_providers( $provider, $name ) {
	return in_array( $name, array( 'users', 'taxonomies' ), true ) ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'skinlove_sitemap_providers', 10, 2 );

/**
 * Из типов записей в карте сайта оставляет только страницы.
 *
 * @param array $post_types Типы записей.
 * @return array
 */
function skinlove_sitemap_post_types( $post_types ) {
	return array_intersect_key( $post_types, array( 'page' => true ) );
}
add_filter( 'wp_sitemaps_post_types', 'skinlove_sitemap_post_types' );

/**
 * Для адресов карты сайта WordPress выполняет обычный запрос записей, а их
 * на сайте нет: статус становится 404 раньше, чем карта отрисуется, и
 * поисковик её отбрасывает. Пустую карту ядро и так отдаёт с 404 само,
 * отключённые провайдеры (авторы, рубрики) остаются за обычной обработкой.
 *
 * @param bool $preempt Пропустить ли обработку 404.
 * @return bool
 */
function skinlove_sitemap_skip_404( $preempt ) {
	$sitemap = get_query_var( 'sitemap' );

	if ( $preempt || get_query_var( 'sitemap-stylesheet' ) || 'index' === $sitemap ) {
		return true;
	}

	return $sitemap && wp_sitemaps_get_server()->registry->get_provider( $sitemap );
}
add_filter( 'pre_handle_404', 'skinlove_sitemap_skip_404' );
