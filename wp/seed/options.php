<?php
/**
 * Сид «Настроек сайта»: контакты, карта, модалка заявки.
 * Соцсети пустые — ссылки проставят позже (решение 11), блок скрыт.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/options.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

carbon_set_theme_option(
	'sl_phones',
	array(
		array(
			'number'  => '+971 56 497 4681',
			'note_ru' => '(what’s app и звонки)',
			'note_en' => '(WhatsApp and calls)',
		),
		array(
			'number'  => '+971 50 689 4801',
			'note_ru' => '(только звонки)',
			'note_en' => '(calls only)',
		),
	)
);
carbon_set_theme_option( 'sl_email', 'Medicalclinicskin@gmail.com' );
carbon_set_theme_option( 'sl_whatsapp', '+971 56 497 4681' );

skinlove_seed_option_pair( 'address', 'Dubai, Damac Smart Height', 'Dubai, Damac Smart Height' );
carbon_set_theme_option( 'sl_address_url', '' );
skinlove_seed_option_pair( 'hours', 'Каждый день 09:00 – 21:00', 'Daily, 09:00 – 21:00' );

carbon_set_theme_option(
	'sl_map_code',
	'<iframe src="https://www.google.com/maps?q=Damac+Smart+Heights,+Barsha+Heights,+Dubai&output=embed" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'
);

carbon_set_theme_option( 'sl_instagram', '' );
carbon_set_theme_option( 'sl_telegram', '' );

skinlove_seed_option_pair( 'modal_title', 'Оставьте свои данные', 'Leave your details' );
skinlove_seed_option_pair(
	'modal_text',
	'Администратор SkinLove свяжется с вами, уточнит запрос и поможет подобрать направление, специалиста и удобное время для визита.',
	'A SkinLove administrator will get in touch to learn more about your request and help you choose the right treatment, specialist and a convenient time for your visit.'
);
carbon_set_theme_option( 'sl_modal_image', skinlove_seed_image( 'svc-3-face.jpg' ) );
skinlove_seed_option_pair(
	'modal_success',
	'Спасибо, заявка отправлена. Администратор SkinLove свяжется с вами в ближайшее время.',
	'Thank you, your request has been sent. A SkinLove administrator will be in touch shortly.'
);

WP_CLI::success( 'Настройки сайта заполнены' );
