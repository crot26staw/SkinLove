<?php
/**
 * Меню: навигация в открывающемся меню, карта сайта в подвале и разделы
 * лендинга «Выездные услуги» в его шапке, у каждого по локации на язык. Направления сюда не входят — они
 * собираются из страниц (inc/directions.php).
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Локации меню.
 */
function skinlove_register_menus() {
	register_nav_menus(
		array(
			'nav_ru'     => 'Навигация в меню (RU)',
			'nav_en'     => 'Навигация в меню (EN)',
			'sitemap_ru' => 'Карта сайта в подвале (RU)',
			'sitemap_en' => 'Карта сайта в подвале (EN)',
			'outcall_ru' => 'Шапка «Выездных услуг» (RU)',
			'outcall_en' => 'Шапка «Выездных услуг» (EN)',
		)
	);
}
add_action( 'after_setup_theme', 'skinlove_register_menus' );

/**
 * Выводит меню на текущем языке. Пока меню не назначено, не выводит ничего.
 *
 * Разметка — как у wp_nav_menu в вёрстке: ul.menu без обёртки и без id,
 * пункты li.menu-item с классами WordPress.
 *
 * @param string $location   Локация без языка: nav, sitemap, outcall.
 * @param string $class      Классы ul.
 * @param string $link_class Класс ссылок пунктов, в шапке Outcall — header__link.
 */
function skinlove_nav_menu( $location, $class, $link_class = '' ) {
	$location .= '_' . skinlove_lang();

	if ( ! has_nav_menu( $location ) ) {
		return;
	}

	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'menu_class'     => $class,
			'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
			'depth'          => 1,
			'fallback_cb'    => false,
			'link_class'     => $link_class,
		)
	);
}

/**
 * Класс ссылок пунктов из аргумента link_class skinlove_nav_menu().
 *
 * @param array    $atts Атрибуты ссылки.
 * @param WP_Post  $item Пункт меню.
 * @param stdClass $args Аргументы wp_nav_menu.
 * @return array
 */
function skinlove_nav_menu_link_class( $atts, $item, $args ) {
	if ( ! empty( $args->link_class ) ) {
		$atts['class'] = trim( ( $atts['class'] ?? '' ) . ' ' . $args->link_class );
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'skinlove_nav_menu_link_class', 10, 3 );
