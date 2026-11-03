<?php
/**
 * Тема SkinLove: настройка и подключение ассетов.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

define( 'SKINLOVE_VERSION', '0.1.0' );

require_once get_theme_file_path( 'inc/carbon.php' );
require_once get_theme_file_path( 'inc/dictionary.php' );
require_once get_theme_file_path( 'inc/lang.php' );
require_once get_theme_file_path( 'inc/geo.php' );
require_once get_theme_file_path( 'inc/security.php' );
require_once get_theme_file_path( 'inc/fields.php' );
require_once get_theme_file_path( 'inc/sections.php' );
require_once get_theme_file_path( 'inc/section-previews.php' );
require_once get_theme_file_path( 'inc/seo.php' );
require_once get_theme_file_path( 'inc/icons.php' );
require_once get_theme_file_path( 'inc/options.php' );
require_once get_theme_file_path( 'inc/directions.php' );
require_once get_theme_file_path( 'inc/menus.php' );
require_once get_theme_file_path( 'inc/leads.php' );
require_once get_theme_file_path( 'inc/reviews.php' );
require_once get_theme_file_path( 'inc/programs.php' );
require_once get_theme_file_path( 'inc/outcall.php' );

/**
 * Возвращает URL файла из assets/ и его версию по времени изменения.
 * Версия по filemtime снимает вопрос с кэшем браузера при правках вёрстки.
 *
 * @param string $path Путь относительно папки assets, например 'css/main.css'.
 * @return array{0:string,1:string}|null URL и версия, либо null если файла нет.
 */
function skinlove_asset( $path ) {
	$file = get_theme_file_path( "assets/{$path}" );

	if ( ! file_exists( $file ) ) {
		return null;
	}

	return array( get_theme_file_uri( "assets/{$path}" ), (string) filemtime( $file ) );
}

/**
 * Печатает URL декоративной графики или видео из assets/, экранированный
 * для атрибута.
 *
 * @param string $path Путь относительно папки assets, например 'img/alfa.svg'.
 */
function skinlove_asset_url( $path ) {
	echo esc_url( get_theme_file_uri( "assets/{$path}" ) );
}

/**
 * Поддержка возможностей WordPress.
 */
function skinlove_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'skinlove_setup' );

/**
 * Убирает из <head> то, что сайту без редактора блоков, записей и
 * встраивания не нужно: эмодзи, oEmbed, RSD, shortlink, версию WordPress.
 */
function skinlove_cleanup_head() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
	remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'wp_generator' );
}
add_action( 'init', 'skinlove_cleanup_head' );

/**
 * Стили блоков: блоков на фронте нет, а их CSS грузится на каждой странице.
 * Стили theme.json (global-styles) сняты в skinlove_cleanup_head().
 */
function skinlove_dequeue_block_styles() {
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'skinlove_dequeue_block_styles', 100 );

/**
 * Подключение стилей и скриптов темы.
 */
function skinlove_assets() {
	wp_enqueue_style(
		'skinlove-fonts',
		'https://fonts.googleapis.com/css2?family=IBM+Plex+Serif:ital,wght@1,400&family=Inter+Tight:wght@400;500&display=swap',
		array(),
		null
	);

	$main_css = skinlove_asset( 'css/main.css' );
	if ( $main_css ) {
		wp_enqueue_style( 'skinlove-main', $main_css[0], array( 'skinlove-fonts' ), $main_css[1] );
	}

	$main_js = skinlove_asset( 'js/main.js' );
	if ( $main_js ) {
		wp_enqueue_script( 'skinlove-main', $main_js[0], array(), $main_js[1], true );
	}
}
add_action( 'wp_enqueue_scripts', 'skinlove_assets' );

/**
 * Сборка Vite отдаёт ES-модуль, поэтому скрипту нужен type="module".
 *
 * @param string $tag    Готовый тег script.
 * @param string $handle Идентификатор скрипта.
 * @return string
 */
function skinlove_script_as_module( $tag, $handle ) {
	if ( 'skinlove-main' !== $handle ) {
		return $tag;
	}

	return str_replace( '<script ', '<script type="module" ', $tag );
}
add_filter( 'script_loader_tag', 'skinlove_script_as_module', 10, 2 );

/**
 * Google Fonts быстрее отдаёт шрифты, если заранее открыть соединение.
 *
 * @param array  $urls          Уже собранные URL.
 * @param string $relation_type Тип связи.
 * @return array
 */
function skinlove_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' !== $relation_type ) {
		return $urls;
	}

	$urls[] = 'https://fonts.googleapis.com';
	$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );

	return $urls;
}
add_filter( 'wp_resource_hints', 'skinlove_resource_hints', 10, 2 );
