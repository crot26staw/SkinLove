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
}
add_action( 'wp_head', 'skinlove_meta_tags', 1 );
