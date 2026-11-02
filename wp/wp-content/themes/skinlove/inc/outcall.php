<?php
/**
 * Выездные услуги: каталог лендинга Outcall, отдельный от программ IV
 * (решение 25). Без детальных страниц; вкладки фильтра — категории,
 * у каждой подпись под фильтром. Выборка и порядок — общие с программами
 * (inc/programs.php).
 *
 * Заголовок записи в админке — русское название, он подставляется сам.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Тип записи «Выездная услуга» и таксономия «Категория».
 */
function skinlove_register_outcall_services() {
	register_post_type(
		'sl_outcall_service',
		array(
			'labels'        => array(
				'name'          => 'Выездные услуги',
				'singular_name' => 'Выездная услуга',
				'add_new'       => 'Добавить услугу',
				'add_new_item'  => 'Новая услуга',
				'edit_item'     => 'Редактировать услугу',
				'all_items'     => 'Все услуги',
				'not_found'     => 'Услуг нет',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-car',
			'menu_position' => 23,
			'supports'      => array( 'page-attributes' ),
		)
	);

	register_taxonomy(
		'sl_outcall_category',
		'sl_outcall_service',
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
add_action( 'init', 'skinlove_register_outcall_services' );

/**
 * Поля услуги и категории.
 */
function skinlove_register_outcall_fields() {
	Container::make( 'post_meta', 'Выездная услуга' )
		->where( 'post_type', '=', 'sl_outcall_service' )
		->add_fields(
			array_merge(
				array(
					Field::make( 'html', 'sl_outcall_note' )
						->set_html( '<p>Категория — в правой колонке, она же вкладка фильтра. Если отмечено несколько, услуга попадёт в первую по порядку категорий. Порядок услуг — поле «Порядок» там же.</p>' ),
				),
				skinlove_pair( 'text', 'title', 'Название' ),
				skinlove_pair( 'textarea', 'text', 'Описание', 'skinlove_short_text' ),
				skinlove_pair( 'text', 'button', 'Кнопка', 'skinlove_outcall_button_help' )
			)
		);

	Container::make( 'term_meta', 'Категория' )
		->where( 'term_taxonomy', '=', 'sl_outcall_category' )
		->add_fields(
			array(
				Field::make( 'text', 'sl_name_en', 'Название (EN)' )
					->set_help_text( 'Русское название — поле «Название» выше. Без английского вкладка и её услуги в EN-версии не выводятся.' ),
				Field::make( 'textarea', 'sl_caption_ru', 'Подпись под фильтром (RU)' )
					->set_rows( 2 )
					->set_width( 50 ),
				Field::make( 'textarea', 'sl_caption_en', 'Подпись под фильтром (EN)' )
					->set_rows( 2 )
					->set_width( 50 ),
				Field::make( 'text', 'sl_order', 'Порядок' )
					->set_attribute( 'type', 'number' )
					->set_help_text( 'Порядок вкладок: меньше — левее. Первая вкладка открыта сразу.' ),
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_outcall_fields' );

/**
 * Подпись кнопки услуги необязательна.
 *
 * @param Field $field Поле.
 */
function skinlove_outcall_button_help( $field ) {
	$field->set_help_text( 'Открывает окно заявки. Пусто — подпись из секции каталога.' );
}
