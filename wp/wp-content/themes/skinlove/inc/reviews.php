<?php
/**
 * Отзывы: общие на весь сайт, без публичных страниц. Секция «Отзывы»
 * на любой странице выводит их все по порядку.
 *
 * Заголовок записи в админке — русское имя, он подставляется сам.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Тип записи «Отзыв».
 */
function skinlove_register_reviews() {
	register_post_type(
		'sl_review',
		array(
			'labels'        => array(
				'name'          => 'Отзывы',
				'singular_name' => 'Отзыв',
				'add_new'       => 'Добавить отзыв',
				'add_new_item'  => 'Новый отзыв',
				'edit_item'     => 'Редактировать отзыв',
				'all_items'     => 'Все отзывы',
				'not_found'     => 'Отзывов нет',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-format-quote',
			'menu_position' => 21,
			'supports'      => array( 'page-attributes' ),
		)
	);
}
add_action( 'init', 'skinlove_register_reviews' );

/**
 * Поля отзыва.
 */
function skinlove_register_review_fields() {
	Container::make( 'post_meta', 'Отзыв' )
		->where( 'post_type', '=', 'sl_review' )
		->add_fields(
			array_merge(
				skinlove_pair( 'text', 'name', 'Имя' ),
				skinlove_pair( 'textarea', 'text', 'Текст' ),
				array(
					Field::make( 'image', 'sl_photo', 'Фото' )
						->set_help_text( 'Квадратное. Без фото — силуэт-заглушка.' ),
				)
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_review_fields' );

/**
 * Заголовок записи = русское имя, чтобы отзыв находился в списке.
 *
 * @param int $post_id ID записи.
 */
function skinlove_review_title( $post_id ) {
	if ( 'sl_review' !== get_post_type( $post_id ) ) {
		return;
	}

	$name = skinlove_clean( carbon_get_post_meta( $post_id, 'sl_name_ru' ) );

	if ( '' === $name || get_the_title( $post_id ) === $name ) {
		return;
	}

	wp_update_post(
		array(
			'ID'         => $post_id,
			'post_title' => $name,
		)
	);
}
add_action( 'carbon_fields_post_meta_container_saved', 'skinlove_review_title' );

/**
 * В списке отзывов в админке — тот же порядок, что на сайте.
 *
 * @param WP_Query $query Запрос.
 */
function skinlove_reviews_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'sl_review' !== $query->get( 'post_type' ) || $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', 'menu_order title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'skinlove_reviews_admin_order' );

/**
 * Отзывы на текущем языке. Без имени или текста отзыв пропускается.
 *
 * @return array<int,array{name:string,text:string,photo:int}>
 */
function skinlove_reviews() {
	$reviews = array();

	if ( ! skinlove_carbon_ready() ) {
		return $reviews;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'sl_review',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			),
		)
	);

	foreach ( $ids as $id ) {
		$name = skinlove_field( 'name', $id );
		$text = skinlove_field( 'text', $id );

		if ( '' === $name || '' === $text ) {
			continue;
		}

		$reviews[] = array(
			'name'  => $name,
			'text'  => $text,
			'photo' => (int) carbon_get_post_meta( $id, 'sl_photo' ),
		);
	}

	return $reviews;
}
