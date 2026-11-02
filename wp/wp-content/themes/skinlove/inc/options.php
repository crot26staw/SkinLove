<?php
/**
 * «Настройки сайта»: то, что одинаково на всех страницах, — контакты,
 * соцсети, карта, модалка заявки.
 *
 * Телефоны, почта, WhatsApp и ссылки одни на оба языка, тексты — парами.
 * Пустое поле скрывает свой элемент в шапке, меню и подвале.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Регистрация страницы настроек.
 */
function skinlove_register_options() {
	Container::make( 'theme_options', 'Настройки сайта' )
		->set_page_file( 'skinlove-options' )
		->set_icon( 'dashicons-admin-settings' )
		->set_page_menu_position( 3 )
		->add_tab(
			'Контакты',
			array_merge(
				array(
					Field::make( 'complex', 'sl_phones', 'Телефоны' )
						->setup_labels(
							array(
								'plural_name'   => 'телефоны',
								'singular_name' => 'телефон',
							)
						)
						->add_fields(
							array(
								Field::make( 'text', 'number', 'Номер' )
									->set_help_text( 'Как показывать на сайте: +971 56 497 4681. Первый номер — для кнопки «Позвонить».' ),
							)
						)
						->set_header_template( '<%- number %>' ),
					Field::make( 'text', 'sl_email', 'Почта' )->set_width( 50 ),
					Field::make( 'text', 'sl_whatsapp', 'WhatsApp' )
						->set_width( 50 )
						->set_help_text( 'Номер в любом формате, ссылка wa.me соберётся сама.' ),
				),
				skinlove_pair( 'text', 'address', 'Адрес' ),
				array(
					Field::make( 'text', 'sl_address_url', 'Ссылка на адрес' )
						->set_help_text( 'Например, на точку в Google Maps. Пусто — ссылка на поиск адреса в Google Maps.' ),
				),
				skinlove_pair( 'text', 'hours', 'Часы работы' )
			)
		)
		->add_tab(
			'Соцсети',
			array(
				Field::make( 'text', 'sl_instagram', 'Instagram' )->set_width( 50 ),
				Field::make( 'text', 'sl_telegram', 'Telegram' )->set_width( 50 ),
			)
		)
		->add_tab(
			'Карта',
			array(
				Field::make( 'textarea', 'sl_map_code', 'Код карты' )
					->set_rows( 4 )
					->set_help_text( 'Google Maps: «Поделиться» → «Встраивание карт» → скопировать iframe. Яндекс: «Конструктор карт» → «Получить код» → iframe.' ),
			)
		)
		->add_tab(
			'Модалка заявки',
			array_merge(
				skinlove_pair( 'text', 'modal_title', 'Заголовок' ),
				skinlove_pair( 'textarea', 'modal_text', 'Текст', 'skinlove_short_text' ),
				array( Field::make( 'image', 'sl_modal_image', 'Картинка' ) ),
				skinlove_pair( 'textarea', 'modal_success', 'Сообщение после отправки', 'skinlove_short_text' )
			)
		);
}
add_action( 'carbon_fields_register_fields', 'skinlove_register_options' );

/**
 * Телефоны из настроек: подпись и номер для tel:.
 *
 * @return array<int,array{label:string,href:string}>
 */
function skinlove_phones() {
	$phones = array();

	foreach ( (array) skinlove_option_raw( 'phones' ) as $entry ) {
		$label  = skinlove_clean( $entry['number'] ?? '' );
		$digits = preg_replace( '/\D/', '', $label );

		if ( '' !== $digits ) {
			$phones[] = array(
				'label' => $label,
				'href'  => 'tel:+' . $digits,
			);
		}
	}

	return $phones;
}

/**
 * Ссылка на WhatsApp или пустая строка.
 *
 * @return string
 */
function skinlove_whatsapp_url() {
	$digits = preg_replace( '/\D/', '', (string) skinlove_option_raw( 'whatsapp' ) );

	return '' === $digits ? '' : 'https://wa.me/' . $digits;
}

/**
 * Соцсети с непустыми ссылками: ключ строки словаря => URL.
 *
 * @return array<string,string>
 */
function skinlove_socials() {
	$socials = array(
		'social_instagram' => skinlove_option_raw( 'instagram' ),
		'social_telegram'  => skinlove_option_raw( 'telegram' ),
	);

	return array_filter( $socials );
}

/**
 * Ссылка на адрес: из настроек или поиск адреса в Google Maps.
 *
 * @param string $address Адрес на текущем языке.
 * @return string
 */
function skinlove_address_url( $address ) {
	$url = (string) skinlove_option_raw( 'address_url' );

	return '' !== $url ? $url : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $address );
}
