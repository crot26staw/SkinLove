<?php
/**
 * Конструктор секций страницы.
 *
 * Поле sl_sections — complex, где каждый layout это секция вёрстки, а его
 * имя совпадает с data-section и с файлом template-parts/sections/{имя}.php.
 * В админке каждая секция — своя вкладка; в подписи вкладки — название
 * секции и её заголовок, если он есть.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Зарегистрированные секции: имя => подпись и поля.
 * Поля отдаются функцией, чтобы классы Carbon не трогались до его загрузки.
 *
 * @return array<string,array{label:string,fields:callable}>
 */
function skinlove_section_layouts() {
	return array(
		'hero'       => array(
			'label'  => 'Первый экран',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_sub_pair( 'textarea', 'lead', 'Текст', 'skinlove_short_text' ),
				skinlove_button_fields()
			),
		),
		'about'      => array(
			'label'  => 'О клинике',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'text', 'label', 'Надзаголовок' ),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_sub_pair( 'textarea', 'lead', 'Вводный абзац', 'skinlove_short_text' ),
				skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_paragraphs_help' ),
				skinlove_button_fields()
			),
		),
		'services'   => array(
			'label'  => 'Направления',
			'fields' => fn() => array(
				Field::make( 'html', 'services_note' )
					->set_html( '<p>Карточки собираются из страниц с флажком «Показывать в направлениях»: название, текст карточки и изображение записи — в боковой колонке страницы.</p>' ),
			),
		),
		'atmosphere' => array(
			'label'  => 'Атмосфера',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'text', 'label', 'Надзаголовок' ),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_sub_pair( 'textarea', 'lead', 'Вводный абзац', 'skinlove_short_text' ),
				skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_short_text' ),
				array(
					Field::make( 'image', 'image', 'Фото' )->set_help_text( 'Вертикальное, 9 : 16.' ),
					skinlove_items_field( 'features', 'Особенности', 'особенность', 'особенности' )
						->set_max( 3 )
						->set_help_text( 'Подписи поверх фото: больше трёх в кадр не помещаются.' ),
				)
			),
		),
		'advantages' => array(
			'label'  => 'Преимущества',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				array(
					skinlove_items_field(
						'items',
						'Карточки',
						'карточку',
						'карточки',
						array( Field::make( 'image', 'image', 'Фото' )->set_help_text( 'Горизонтальное, примерно 2 : 1.' ) )
					),
				)
			),
		),
		'reviews'    => array(
			'label'  => 'Отзывы',
			'fields' => fn() => array_merge(
				array(
					Field::make( 'html', 'reviews_note' )
						->set_html( '<p>Сами отзывы общие для всего сайта — раздел «Отзывы» в меню админки. Без отзывов секция не выводится.</p>' ),
				),
				skinlove_sub_pair( 'text', 'label', 'Надзаголовок' ),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_button_fields()
			),
		),
		'cta'        => array(
			'label'  => 'Призыв к записи',
			'fields' => fn() => array_merge(
				array(
					Field::make( 'set', 'variant', 'Вид' )
						->set_options(
							array(
								'cover'      => 'Горизонтальное фото',
								'stacked'    => 'Заголовок в две строки, друг под другом',
								'deep-shade' => 'Плотное затемнение фото',
							)
						)
						->set_help_text( 'Без отметок — вертикальное фото (в кадр берётся нижняя треть) и заголовок, слова которого разъезжаются по ширине.' ),
					Field::make( 'image', 'image', 'Фото' ),
				),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_short_text' ),
				skinlove_sub_pair( 'text', 'button', 'Кнопка', 'skinlove_cta_button_help' )
			),
		),
		'page-hero'   => array(
			'label'  => 'Первый экран с логотипом',
			'fields' => fn() => array_merge(
				array(
					Field::make( 'html', 'page_hero_note' )
						->set_html( '<p>Видео и логотип вместо заголовка — из темы, в админке не меняются.</p>' ),
				),
				skinlove_sub_pair( 'textarea', 'lead', 'Крупный текст', 'skinlove_short_text' ),
				skinlove_sub_pair( 'textarea', 'note', 'Текст', 'skinlove_short_text' )
			),
		),
		'philosophy'  => array(
			'label'  => 'Философия',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'text', 'label', 'Надзаголовок' ),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				array( Field::make( 'image', 'image', 'Фото под заголовком' )->set_help_text( 'Горизонтальное, примерно 5 : 3.' ) ),
				skinlove_sub_pair( 'textarea', 'quote', 'Цитата', 'skinlove_short_text' ),
				array(
					Field::make( 'image', 'image_left', 'Фото слева' )->set_width( 50 )->set_help_text( 'Квадратное.' ),
					Field::make( 'image', 'image_right', 'Фото справа' )->set_width( 50 )->set_help_text( 'Горизонтальное, примерно 3 : 2.' ),
				),
				skinlove_sub_pair( 'textarea', 'text', 'Текст под фото справа', 'skinlove_paragraphs_help' )
			),
		),
		'frame-inset' => array(
			'label'  => 'Рамка с фото',
			'fields' => fn() => array_merge(
				array( Field::make( 'image', 'image', 'Фото фоном' )->set_help_text( 'Горизонтальное, 3 : 2.' ) ),
				skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
				skinlove_sub_pair( 'textarea', 'lead', 'Вводный абзац', 'skinlove_short_text' ),
				skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_paragraphs_help' )
			),
		),
		'frame-reel'  => array(
			'label'  => 'Рамка с видео',
			'fields' => fn() => array(
				Field::make( 'html', 'frame_reel_note' )
					->set_html( '<p>Видео — из темы, идёт от прокрутки. Тексты сменяют друг друга по ходу ролика, первый виден сразу.</p>' ),
				Field::make( 'complex', 'layers', 'Тексты' )
					->setup_labels(
						array(
							'plural_name'   => 'тексты',
							'singular_name' => 'текст',
						)
					)
					->set_collapsed( true )
					->add_fields(
						array_merge(
							skinlove_sub_pair( 'textarea', 'title', 'Заголовок', 'skinlove_heading_help' ),
							skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_paragraphs_help' )
						)
					)
					->set_header_template( "<%- title_ru.replace(/\\*/g, '') %>" ),
			),
		),
		'founder'     => array(
			'label'  => 'Основатель',
			'fields' => fn() => array_merge(
				skinlove_sub_pair( 'text', 'title', 'Заголовок' ),
				skinlove_sub_pair( 'textarea', 'lead', 'Вводный абзац', 'skinlove_short_text' ),
				array(
					Field::make( 'image', 'image', 'Портрет' )->set_width( 50 )->set_help_text( 'PNG без фона, вертикальный.' ),
					Field::make( 'image', 'quote_image', 'Фото у цитаты' )->set_width( 50 )->set_help_text( 'Вертикальное, 9 : 16.' ),
				),
				skinlove_sub_pair( 'text', 'since', 'Подпись слева от цитаты' ),
				skinlove_sub_pair( 'textarea', 'quote', 'Цитата', 'skinlove_short_text' ),
				skinlove_sub_pair( 'text', 'name', 'Имя' )
			),
		),
	);
}

/**
 * Повторяемые элементы секции: заголовок и текст парами, плюс свои поля.
 *
 * @param string  $name     Имя поля.
 * @param string  $label    Подпись.
 * @param string  $singular «Добавить …».
 * @param string  $plural   Множественное число для подписей Carbon.
 * @param Field[] $before   Поля перед заголовком (например, фото).
 * @return Field
 */
function skinlove_items_field( $name, $label, $singular, $plural, $before = array() ) {
	return Field::make( 'complex', $name, $label )
		->setup_labels(
			array(
				'plural_name'   => $plural,
				'singular_name' => $singular,
			)
		)
		->set_collapsed( true )
		->add_fields(
			array_merge(
				$before,
				skinlove_sub_pair( 'text', 'title', 'Заголовок' ),
				skinlove_sub_pair( 'textarea', 'text', 'Текст', 'skinlove_short_text' )
			)
		)
		->set_header_template( '<%- title_ru %>' );
}

/**
 * Подсказка к тексту из нескольких абзацев.
 *
 * @param Field $field Поле.
 */
function skinlove_paragraphs_help( $field ) {
	$field->set_rows( 6 )->set_help_text( 'Абзацы разделяются пустой строкой.' );
}

/**
 * Кнопка призыва открывает модалку заявки, ссылка ей не нужна.
 *
 * @param Field $field Поле.
 */
function skinlove_cta_button_help( $field ) {
	$field->set_help_text( 'Открывает окно заявки.' );
}

/**
 * Поля конструктора у страниц.
 */
function skinlove_register_sections() {
	$sections = Field::make( 'complex', 'sl_sections', 'Секции' )
		->set_layout( 'tabbed-vertical' )
		->setup_labels(
			array(
				'plural_name'   => 'секции',
				'singular_name' => 'секцию',
			)
		);

	foreach ( skinlove_section_layouts() as $name => $layout ) {
		$sections->add_fields( $name, $layout['label'], ( $layout['fields'] )() );
		$sections->set_header_template( skinlove_section_header( $layout['label'] ) );
	}

	Container::make( 'post_meta', 'Секции страницы' )
		->where( 'post_type', '=', 'page' )
		->add_fields( array( $sections ) );
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_sections' );

/**
 * Контент страниц целиком в конструкторе: редактор блоков и поле
 * the_content у них не нужны и только сдвигают секции вниз экрана.
 */
function skinlove_pages_without_editor() {
	remove_post_type_support( 'page', 'editor' );
}
add_action( 'init', 'skinlove_pages_without_editor' );

/**
 * Страницы редактируются в классическом экране — там конструктор сразу
 * под заголовком.
 *
 * @param bool   $use       Использовать ли редактор блоков.
 * @param string $post_type Тип записи.
 * @return bool
 */
function skinlove_classic_editor_for_pages( $use, $post_type ) {
	return 'page' === $post_type ? false : $use;
}
add_filter( 'use_block_editor_for_post_type', 'skinlove_classic_editor_for_pages', 10, 2 );

/**
 * Шаблон подписи вкладки: «Отзывы: Нам доверяют» — без звёздочек акцента.
 *
 * @param string $label Название секции.
 * @return string Шаблон underscore.js для Carbon.
 */
function skinlove_section_header( $label ) {
	return esc_html( $label ) . "<% if (typeof title_ru !== 'undefined' && title_ru) { %>: <%- title_ru.replace(/\\*/g, '') %><% } %>";
}

/**
 * Выводит секции страницы по порядку из админки.
 * Секция неизвестного типа (layout удалён из темы) пропускается.
 *
 * @param int|null $post_id ID страницы, по умолчанию текущая.
 */
function skinlove_render_sections( $post_id = null ) {
	if ( ! skinlove_carbon_ready() ) {
		return;
	}

	$layouts  = skinlove_section_layouts();
	$sections = carbon_get_post_meta( $post_id ?? get_the_ID(), 'sl_sections' );

	foreach ( (array) $sections as $section ) {
		$type = $section['_type'] ?? '';

		if ( ! isset( $layouts[ $type ] ) ) {
			continue;
		}

		get_template_part( 'template-parts/sections/' . $type, null, array( 'section' => $section ) );
	}
}

/**
 * Префикс id для секции, которая может встретиться на странице дважды:
 * reviews, reviews-2, reviews-3…
 *
 * @param string $name Имя секции.
 * @return string
 */
function skinlove_section_uid( $name ) {
	static $counts = array();

	$counts[ $name ] = ( $counts[ $name ] ?? 0 ) + 1;

	return 1 === $counts[ $name ] ? $name : "{$name}-{$counts[ $name ]}";
}
