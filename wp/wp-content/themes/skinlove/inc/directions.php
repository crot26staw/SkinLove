<?php
/**
 * Направления: страницы услуг, которые попадают в меню, подвал и на главную.
 *
 * Страница отмечается флажком, порядок — поле «Порядок» в атрибутах
 * страницы, картинка — миниатюра. Список собирается сам, отдельного меню
 * для направлений нет.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Поля направления в боковой колонке страницы.
 */
function skinlove_register_direction_fields() {
	Container::make( 'post_meta', 'Направление' )
		->where( 'post_type', '=', 'page' )
		->set_context( 'side' )
		->add_fields(
			array_merge(
				array(
					Field::make( 'checkbox', 'sl_direction', 'Показывать в направлениях' )
						->set_help_text( 'Меню, подвал и карточки на главной. Порядок — «Атрибуты страницы → Порядок», картинка — «Изображение записи».' ),
				),
				skinlove_pair( 'text', 'direction_title', 'Название в меню', 'skinlove_direction_field' ),
				skinlove_pair( 'textarea', 'direction_text', 'Текст карточки на главной', 'skinlove_direction_text_field' )
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_direction_fields' );

/**
 * Поле направления видно, только когда стоит флажок.
 *
 * @param \Carbon_Fields\Field\Field $field Поле.
 */
function skinlove_direction_field( $field ) {
	$field->set_width( 100 )->set_conditional_logic(
		array(
			array(
				'field' => 'sl_direction',
				'value' => true,
			),
		)
	);
}

/**
 * Текст карточки: как skinlove_direction_field(), в три строки.
 *
 * @param \Carbon_Fields\Field\Field $field Поле.
 */
function skinlove_direction_text_field( $field ) {
	skinlove_direction_field( $field );
	skinlove_short_text( $field );
}

/**
 * Направления по порядку. Пункт без названия на текущем языке пропускается.
 *
 * @return array<int,array{id:int,title:string,text:string,url:string,image:int,current:bool}>
 */
function skinlove_directions() {
	static $directions = null;

	if ( null !== $directions ) {
		return $directions;
	}

	$directions = array();

	if ( ! skinlove_carbon_ready() ) {
		return $directions;
	}

	$ids = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'meta_key'       => '_sl_direction',
			'meta_value'     => 'yes',
		)
	);

	_prime_post_caches( $ids );

	foreach ( $ids as $id ) {
		$title = skinlove_field( 'direction_title', $id );

		if ( '' === $title ) {
			continue;
		}

		$directions[] = array(
			'id'      => $id,
			'title'   => $title,
			'text'    => skinlove_field( 'direction_text', $id ),
			'url'     => get_permalink( $id ),
			'image'   => (int) get_post_thumbnail_id( $id ),
			'current' => is_page( $id ),
		);
	}

	skinlove_prime_attachments( array_column( $directions, 'image' ) );

	return $directions;
}

/**
 * Направление, картинка которого показывается в превью сразу: текущая
 * страница, иначе первое.
 *
 * @param array $directions Результат skinlove_directions().
 * @return array|null
 */
function skinlove_current_direction( $directions ) {
	foreach ( $directions as $direction ) {
		if ( $direction['current'] ) {
			return $direction;
		}
	}

	return $directions[0] ?? null;
}

/**
 * URL картинки направления для превью меню и подвала.
 *
 * Превью — один img, которому скрипт подменяет src, поэтому без srcset:
 * он перебил бы подменённый src.
 *
 * @param array $direction Элемент skinlove_directions().
 * @return string
 */
function skinlove_direction_image_url( $direction ) {
	return $direction['image'] ? (string) wp_get_attachment_image_url( $direction['image'], 'large' ) : '';
}
