<?php
/**
 * Превью секций в меню «Добавить секцию» конструктора.
 *
 * Пункты меню Carbon Fields — голые <li> с подписью, без имени layout'а,
 * поэтому скрипт сопоставляет их с секциями по подписи. Переименованная
 * секция просто останется без превью. Картинки — admin/previews/{имя}.jpg,
 * снимки секций на ширине 1920.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Группы в меню: подпись группы => первая секция группы. Секции идут
 * в порядке skinlove_section_layouts(), группа тянется до следующей.
 *
 * @return array<string,string>
 */
function skinlove_section_groups() {
	return array(
		'Главная'              => 'hero',
		'О клинике и Контакты' => 'page-hero',
		'Услуги'               => 'service-hero',
		'Выездные'             => 'outcall-hero',
	);
}

/**
 * Подключает превью на экране редактирования страницы.
 *
 * @param string $hook Текущий экран админки.
 */
function skinlove_section_previews_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) || 'page' !== get_current_screen()->post_type ) {
		return;
	}

	$groups   = array_flip( skinlove_section_groups() );
	$sections = array();

	foreach ( skinlove_section_layouts() as $name => $layout ) {
		$file = "admin/previews/{$name}.jpg";

		$sections[ $layout['label'] ] = array(
			'preview' => file_exists( get_theme_file_path( $file ) ) ? get_theme_file_uri( $file ) : '',
			'group'   => $groups[ $name ] ?? '',
		);
	}

	$css = get_theme_file_path( 'admin/section-previews.css' );
	$js  = get_theme_file_path( 'admin/section-previews.js' );

	wp_enqueue_style( 'skinlove-section-previews', get_theme_file_uri( 'admin/section-previews.css' ), array(), (string) filemtime( $css ) );
	wp_enqueue_script( 'skinlove-section-previews', get_theme_file_uri( 'admin/section-previews.js' ), array(), (string) filemtime( $js ), true );
	wp_add_inline_script( 'skinlove-section-previews', 'window.skinloveSections = ' . wp_json_encode( $sections ) . ';', 'before' );
}
add_action( 'admin_enqueue_scripts', 'skinlove_section_previews_assets' );
