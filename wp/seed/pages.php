<?php
/**
 * Сид структуры сайта: страницы, главная, направления (флажок, названия,
 * тексты карточек, миниатюры) и меню на двух языках. Секции страниц
 * заполняют сиды этапов — {страница}.php.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/pages.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$pages = array(
	'home'     => 'Главная',
	'about'    => 'О клинике',
	'contacts' => 'Контакты',
);

foreach ( $pages as $slug => $title ) {
	skinlove_seed_page( $slug, $title );
}

update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', get_page_by_path( 'home', OBJECT, 'page' )->ID );

// Порядок — как в карточках на главной.
$directions = array(
	'iv-therapy' => array(
		'title' => array( 'IV Therapy', 'IV Therapy' ),
		'text'  => array(
			'Персонально подобранные витаминные капельницы для восстановления после перелётов, стресса, физических нагрузок и дефицитных состояний.',
			'Personalised vitamin drips to help you recover from flights, stress, physical exertion and nutrient deficiencies.',
		),
		'image' => 'svc-1-iv.jpg',
	),
	'injection'  => array(
		'title' => array( 'Инъекционная косметология', 'Injectable aesthetics' ),
		'text'  => array(
			'Современные методики для естественной коррекции возрастных изменений и улучшения качества кожи.',
			'Modern techniques for natural-looking correction of age-related changes and better skin quality.',
		),
		'image' => 'svc-2-inject-patient.jpg',
	),
	'face'       => array(
		'title' => array( 'Эстетическая косметология лица', 'Aesthetic facial treatments' ),
		'text'  => array(
			'Профессиональный уход, который помогает поддерживать здоровье, чистоту и сияние кожи.',
			'Professional care that helps keep your skin healthy, clear and radiant.',
		),
		'image' => 'svc-3-face.jpg',
	),
	'hardware'   => array(
		'title' => array( 'Аппаратная косметология', 'Device-based aesthetics' ),
		'text'  => array(
			'Технологичные процедуры для омоложения кожи и коррекции эстетических несовершенств.',
			'Advanced device-based treatments for skin rejuvenation and the correction of aesthetic concerns.',
		),
		'image' => 'svc-4-laser.png',
	),
	'body'       => array(
		'title' => array( 'Коррекция фигуры и массаж', 'Body contouring and massage' ),
		'text'  => array(
			'Ручные и аппаратные методики для моделирования силуэта, восстановления и улучшения качества кожи.',
			'Manual and device-based techniques to sculpt your silhouette, support recovery and improve skin quality.',
		),
		'image' => 'svc-5-body.png',
	),
	'outcall'    => array(
		'title' => array( 'Выездные услуги', 'At-home services' ),
		'text'  => array(
			'Профессиональная медицинская помощь там, где вам удобно — дома, в отеле, офисе или на яхте.',
			'Professional medical care wherever suits you — at home, in your hotel, at the office or on a yacht.',
		),
		'image' => 'svc-6-outcall-wide.jpg',
	),
	'wellness'   => array(
		'title' => array( 'Wellness', 'Wellness' ),
		'text'  => array(
			'Комплексные программы для поддержания энергии, здоровья и хорошего самочувствия.',
			'Comprehensive programmes to support your energy, health and overall wellbeing.',
		),
		'image' => 'svc-7-wellness.jpg',
	),
);

$order = 0;

foreach ( $directions as $slug => $direction ) {
	$id = skinlove_seed_page( $slug, $direction['title'][0], array( 'menu_order' => ++$order ) );

	carbon_set_post_meta( $id, 'sl_direction', true );
	skinlove_seed_pair( $id, 'direction_title', ...$direction['title'] );
	skinlove_seed_pair( $id, 'direction_text', ...$direction['text'] );
	set_post_thumbnail( $id, skinlove_seed_image( $direction['image'], $direction['title'][0] ) );
}

$services = skinlove_home_path() . '/#services';

skinlove_seed_menu(
	'Навигация (RU)',
	'nav_ru',
	array(
		array( 'page' => 'about', 'title' => 'О клинике' ),
		array( 'page' => 'contacts', 'title' => 'Контакты' ),
		array( 'url' => $services, 'title' => 'Направления' ),
	)
);

skinlove_seed_menu(
	'Навигация (EN)',
	'nav_en',
	array(
		array( 'page' => 'about', 'title' => 'About the clinic' ),
		array( 'page' => 'contacts', 'title' => 'Contacts' ),
		array( 'url' => skinlove_home_path() . '/en/#services', 'title' => 'Services' ),
	)
);

skinlove_seed_menu(
	'Карта сайта (RU)',
	'sitemap_ru',
	array(
		array( 'page' => 'home', 'title' => 'Главная' ),
		array( 'page' => 'about', 'title' => 'О клинике' ),
		array( 'url' => $services, 'title' => 'Направления' ),
		array( 'page' => 'contacts', 'title' => 'Контакты' ),
	)
);

skinlove_seed_menu(
	'Карта сайта (EN)',
	'sitemap_en',
	array(
		array( 'page' => 'home', 'title' => 'Home' ),
		array( 'page' => 'about', 'title' => 'About the clinic' ),
		array( 'url' => skinlove_home_path() . '/en/#services', 'title' => 'Services' ),
		array( 'page' => 'contacts', 'title' => 'Contacts' ),
	)
);

WP_CLI::success( 'Страницы, направления и меню заполнены' );
