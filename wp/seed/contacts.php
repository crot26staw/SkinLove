<?php
/**
 * Сид страницы «Контакты»: SEO и секция. Сами контакты и карта —
 * в «Настройках сайта» (seed/options.php).
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/contacts.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$contacts = skinlove_seed_page( 'contacts', 'Контакты' );

skinlove_seed_pair(
	$contacts,
	'seo_title',
	'Контакты — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Contacts — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$contacts,
	'seo_description',
	'Контакты клиники SkinLove в Дубае: адрес, телефоны для записи и связи в WhatsApp, почта и социальные сети.',
	'SkinLove clinic contacts in Dubai: address, phone numbers for bookings and WhatsApp, email and social media.'
);

skinlove_seed_sections(
	$contacts,
	array(
		skinlove_seed_entry(
			array(
				'title' => array( "Контактная\n*информация*", "Contact\n*information*" ),
			),
			array( '_type' => 'contacts' )
		),
	)
);

WP_CLI::success( 'Страница «Контакты» заполнена' );
