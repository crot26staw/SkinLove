<?php
/**
 * Программы IV Therapy: каталог без детальных страниц (решение 21).
 * Секция «Программы» выводит их все, вкладки фильтра — категории.
 *
 * Заголовок записи в админке — русское название, он подставляется сам.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Тип записи «Программа» и таксономия «Категория».
 */
function skinlove_register_programs() {
	register_post_type(
		'sl_program',
		array(
			'labels'        => array(
				'name'          => 'Программы IV',
				'singular_name' => 'Программа',
				'add_new'       => 'Добавить программу',
				'add_new_item'  => 'Новая программа',
				'edit_item'     => 'Редактировать программу',
				'all_items'     => 'Все программы',
				'not_found'     => 'Программ нет',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-heart',
			'menu_position' => 22,
			'supports'      => array( 'page-attributes' ),
		)
	);

	register_taxonomy(
		'sl_program_category',
		'sl_program',
		array(
			'labels'            => array(
				'name'          => 'Категории',
				'singular_name' => 'Категория',
				'add_new_item'  => 'Новая категория',
				'edit_item'     => 'Редактировать категорию',
				'all_items'     => 'Все категории',
				'not_found'     => 'Категорий нет',
			),
			'public'            => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'skinlove_register_programs' );

/**
 * Поля программы и категории.
 */
function skinlove_register_program_fields() {
	Container::make( 'post_meta', 'Программа' )
		->where( 'post_type', '=', 'sl_program' )
		->add_fields(
			array_merge(
				array(
					Field::make( 'html', 'sl_program_note' )
						->set_html( '<p>Категория — в правой колонке; она же вкладка фильтра и метка на карточке. Если отмечено несколько, программа попадёт в первую по порядку категорий. Порядок программ — поле «Порядок» там же.</p>' ),
				),
				skinlove_pair( 'text', 'title', 'Название' ),
				skinlove_pair( 'textarea', 'text', 'Описание', 'skinlove_short_text' ),
				array( skinlove_program_prices_field() )
			)
		);

	Container::make( 'term_meta', 'Категория' )
		->where( 'term_taxonomy', '=', 'sl_program_category' )
		->add_fields(
			array(
				Field::make( 'text', 'sl_name_en', 'Название (EN)' )
					->set_help_text( 'Русское название — поле «Название» выше. Без английского вкладка в EN-версии не выводится.' ),
				Field::make( 'text', 'sl_tag_ru', 'Метка на карточке (RU)' )
					->set_width( 50 )
					->set_help_text( 'Короче названия, в одну строку: «Витамины» для «Витаминных». Пусто — название категории.' ),
				Field::make( 'text', 'sl_tag_en', 'Метка на карточке (EN)' )
					->set_width( 50 ),
				Field::make( 'text', 'sl_order', 'Порядок' )
					->set_attribute( 'type', 'number' )
					->set_help_text( 'Порядок вкладок: меньше — левее.' ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_program_fields' );

/**
 * Цены программы: строки «объём + цена» (решение 23).
 *
 * @return Field
 */
function skinlove_program_prices_field() {
	return Field::make( 'complex', 'sl_prices', 'Цены' )
		->setup_labels(
			array(
				'plural_name'   => 'цены',
				'singular_name' => 'цену',
			)
		)
		->set_collapsed( true )
		->add_fields(
			array_merge(
				skinlove_sub_pair( 'text', 'amount', 'Объём' ),
				array(
					Field::make( 'text', 'price', 'Цена' )
						->set_help_text( 'Как показывать: 650 AED. Одна на оба языка. Без цены строка не выводится.' ),
				)
			)
		)
		->set_header_template( '<%- amount_ru %> — <%- price %>' );
}

/**
 * Заголовок записи = русское название, чтобы программа находилась в списке.
 *
 * @param int $post_id ID записи.
 */
function skinlove_program_title( $post_id ) {
	if ( 'sl_program' !== get_post_type( $post_id ) ) {
		return;
	}

	$title = skinlove_clean( carbon_get_post_meta( $post_id, 'sl_title_ru' ) );

	if ( '' === $title || get_the_title( $post_id ) === $title ) {
		return;
	}

	wp_update_post(
		array(
			'ID'         => $post_id,
			'post_title' => $title,
		)
	);
}
add_action( 'carbon_fields_post_meta_container_saved', 'skinlove_program_title' );

/**
 * В списке программ в админке — тот же порядок, что на сайте.
 *
 * @param WP_Query $query Запрос.
 */
function skinlove_programs_admin_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || 'sl_program' !== $query->get( 'post_type' ) || $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', 'menu_order title' );
	$query->set( 'order', 'ASC' );
}
add_action( 'pre_get_posts', 'skinlove_programs_admin_order' );

/**
 * Категории по порядку: slug, название и метка на текущем языке.
 * Категория без названия на языке остаётся в списке — у её программ
 * есть метка, но вкладки нет.
 *
 * @return array<int,array{slug:string,name:string,tag:string}> По ID термина.
 */
function skinlove_program_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'sl_program_category',
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $terms ) ) {
		return array();
	}

	$order = array();

	foreach ( $terms as $term ) {
		$order[ $term->term_id ] = (int) carbon_get_term_meta( $term->term_id, 'sl_order' );
	}

	usort(
		$terms,
		fn( $a, $b ) => array( $order[ $a->term_id ], $a->name ) <=> array( $order[ $b->term_id ], $b->name )
	);

	$lang       = skinlove_lang();
	$categories = array();

	foreach ( $terms as $term ) {
		$name = 'ru' === $lang ? skinlove_clean( $term->name ) : skinlove_clean( carbon_get_term_meta( $term->term_id, 'sl_name_en' ) );
		$tag  = skinlove_clean( carbon_get_term_meta( $term->term_id, "sl_tag_{$lang}" ) );

		$categories[ $term->term_id ] = array(
			'slug' => $term->slug,
			'name' => $name,
			'tag'  => '' === $tag ? $name : $tag,
		);
	}

	return $categories;
}

/**
 * Программы на текущем языке по порядку. Без названия программа
 * пропускается; строка цены без цены — тоже.
 *
 * @param array $categories Результат skinlove_program_categories().
 * @return array<int,array{title:string,text:string,category:int,prices:array}>
 */
function skinlove_programs( $categories ) {
	$programs = array();

	if ( ! skinlove_carbon_ready() ) {
		return $programs;
	}

	$posts = get_posts(
		array(
			'post_type'      => 'sl_program',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			),
		)
	);

	foreach ( $posts as $post ) {
		$title = skinlove_field( 'title', $post->ID );

		if ( '' === $title ) {
			continue;
		}

		$terms    = wp_list_pluck( get_the_terms( $post, 'sl_program_category' ) ?: array(), 'term_id' );
		$category = array_values( array_intersect( array_keys( $categories ), $terms ) );
		$prices   = array();

		foreach ( (array) carbon_get_post_meta( $post->ID, 'sl_prices' ) as $row ) {
			$price = skinlove_clean( $row['price'] ?? '' );

			if ( '' !== $price ) {
				$prices[] = array(
					'amount' => skinlove_entry( $row, 'amount' ),
					'price'  => $price,
				);
			}
		}

		$programs[] = array(
			'title'    => $title,
			'text'     => skinlove_field( 'text', $post->ID ),
			'category' => $category[0] ?? 0,
			'prices'   => $prices,
		);
	}

	return $programs;
}
