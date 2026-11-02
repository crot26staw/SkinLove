<?php
/**
 * Меню: навигация в открывающемся меню и карта сайта в подвале,
 * у каждого по локации на язык. Направления сюда не входят — они
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
 * @param string $location Локация без языка: nav, sitemap.
 * @param string $class    Классы ul.
 */
function skinlove_nav_menu( $location, $class ) {
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
		)
	);
}
