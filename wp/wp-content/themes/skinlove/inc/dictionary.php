<?php
/**
 * Словарь интерфейсных строк: подписи кнопок, aria-label, служебные тексты.
 *
 * Контент страниц сюда не попадает — он в полях админки. Здесь только то,
 * что редактору менять не нужно, но что различается между языками.
 * Строки добавляются по мере натяжки шаблонов.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Все строки словаря.
 *
 * @return array<string,array{ru:string,en:string}>
 */
function skinlove_dictionary() {
	return array(
		'skip_link'   => array(
			'ru' => 'Перейти к содержимому',
			'en' => 'Skip to content',
		),
		'logo_home'   => array(
			'ru' => 'SkinLove, на главную',
			'en' => 'SkinLove, home page',
		),
		'lang_ru'     => array(
			'ru' => 'Язык сайта: русский',
			'en' => 'Site language: Russian',
		),
		'lang_en'     => array(
			'ru' => 'Язык сайта: английский',
			'en' => 'Site language: English',
		),
		'badge_alfa'  => array(
			'ru' => 'Разработано в alfa',
			'en' => 'Designed by alfa',
		),
		'lang_label_ru'     => array(
			'ru' => 'Ru',
			'en' => 'Ru',
		),
		'lang_label_en'     => array(
			'ru' => 'En',
			'en' => 'En',
		),
		'menu_open'         => array(
			'ru' => 'Меню',
			'en' => 'Menu',
		),
		'menu_close'        => array(
			'ru' => 'Закрыть',
			'en' => 'Close',
		),
		'menu_nav'          => array(
			'ru' => 'Основная навигация',
			'en' => 'Main navigation',
		),
		'menu_nav_label'    => array(
			'ru' => 'Навигация',
			'en' => 'Navigation',
		),
		'directions'        => array(
			'ru' => 'Направления',
			'en' => 'Services',
		),
		'contacts'          => array(
			'ru' => 'Контакты',
			'en' => 'Contacts',
		),
		'address'           => array(
			'ru' => 'Адрес',
			'en' => 'Address',
		),
		'socials'           => array(
			'ru' => 'Социальные сети',
			'en' => 'Social media',
		),
		'sitemap'           => array(
			'ru' => 'Карта сайта',
			'en' => 'Site map',
		),
		'social_instagram'  => array(
			'ru' => 'SkinLove в Instagram',
			'en' => 'SkinLove on Instagram',
		),
		'social_telegram'   => array(
			'ru' => 'SkinLove в Telegram',
			'en' => 'SkinLove on Telegram',
		),
		'whatsapp'          => array(
			'ru' => 'Написать в WhatsApp',
			'en' => 'Message us on WhatsApp',
		),
		'call'              => array(
			'ru' => 'Позвонить',
			'en' => 'Call us',
		),
		'dock_toggle'       => array(
			'ru' => 'Связаться с нами',
			'en' => 'Contact us',
		),
		'book'              => array(
			'ru' => 'Записаться на консультацию',
			'en' => 'Book a consultation',
		),
		'close'             => array(
			'ru' => 'Закрыть',
			'en' => 'Close',
		),
		'form_name'         => array(
			'ru' => 'Ваше имя',
			'en' => 'Your name',
		),
		'form_phone'        => array(
			'ru' => 'Номер телефона',
			'en' => 'Phone number',
		),
		'form_required'     => array(
			'ru' => 'Данное поле обязательно для заполнения',
			'en' => 'This field is required',
		),
		'form_submit'       => array(
			'ru' => 'Оставить заявку',
			'en' => 'Send request',
		),
		'form_success'      => array(
			'ru' => 'Спасибо, заявка отправлена. Администратор SkinLove свяжется с вами в ближайшее время.',
			'en' => 'Thank you, your request has been sent. A SkinLove administrator will be in touch shortly.',
		),
		'form_failure'      => array(
			'ru' => 'Не удалось отправить заявку. Попробуйте ещё раз или позвоните нам.',
			'en' => 'We couldn’t send your request. Please try again or give us a call.',
		),
		'lead_bad_name'     => array(
			'ru' => 'Укажите имя.',
			'en' => 'Please enter your name.',
		),
		'lead_bad_phone'    => array(
			'ru' => 'Проверьте номер телефона.',
			'en' => 'Please check your phone number.',
		),
		'lead_rate_limited' => array(
			'ru' => 'Слишком много заявок. Попробуйте позже или позвоните нам.',
			'en' => 'Too many requests. Please try again later or give us a call.',
		),
		'services_more'     => array(
			'ru' => 'Узнать подробнее',
			'en' => 'Learn more',
		),
		'reviews_guests'    => array(
			'ru' => 'Гости клиники',
			'en' => 'Clinic guests',
		),
		'reviews_prev'      => array(
			'ru' => 'Предыдущий отзыв',
			'en' => 'Previous review',
		),
		'reviews_next'      => array(
			'ru' => 'Следующий отзыв',
			'en' => 'Next review',
		),
		'contacts_address'  => array(
			'ru' => 'Адрес:',
			'en' => 'Address:',
		),
		'contacts_phone'    => array(
			'ru' => 'Телефон для связи:',
			'en' => 'Phone:',
		),
		'contacts_email'    => array(
			'ru' => 'Почта:',
			'en' => 'Email:',
		),
		'contacts_socials'  => array(
			'ru' => 'Социальные сети:',
			'en' => 'Social media:',
		),
		'map_title'         => array(
			'ru' => 'Клиника SkinLove на карте',
			'en' => 'SkinLove clinic on the map',
		),
		'procedures_tabs'   => array(
			'ru' => 'Процедуры',
			'en' => 'Treatments',
		),
		'prices_title'      => array(
			'ru' => 'Стоимость процедур',
			'en' => 'Prices',
		),
		'price_from'        => array(
			'ru' => 'от',
			'en' => 'from',
		),
		'prices_variants'   => array(
			'ru' => 'Вид прайса',
			'en' => 'Price list type',
		),
		'prices_more'       => array(
			'ru' => 'Развернуть полный прайс',
			'en' => 'Show full price list',
		),
		'prices_less'       => array(
			'ru' => 'Свернуть прайс',
			'en' => 'Collapse price list',
		),
		'promos_prev'       => array(
			'ru' => 'Предыдущие акции',
			'en' => 'Previous offers',
		),
		'promos_next'       => array(
			'ru' => 'Следующие акции',
			'en' => 'Next offers',
		),
		'results_prev'      => array(
			'ru' => 'Предыдущие результаты',
			'en' => 'Previous results',
		),
		'results_next'      => array(
			'ru' => 'Следующие результаты',
			'en' => 'Next results',
		),
	);
}
