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
							array_merge(
								array(
									Field::make( 'text', 'number', 'Номер' )
										->set_help_text( 'Как показывать на сайте: +971 56 497 4681. Первый номер — для кнопки «Позвонить».' ),
								),
								skinlove_sub_pair( 'text', 'note', 'Пометка', 'skinlove_phone_note_help' )
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
			'Результаты',
			array(
				Field::make( 'media_gallery', 'sl_results', 'Фото «до / после»' )
					->set_type( array( 'image' ) )
					->set_help_text( 'Общие для всех страниц услуг, выводятся в секции «Результаты» в этом порядке. Вертикальные, примерно 6 : 7; подпись — alt в медиабиблиотеке.' ),
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
 * Пометка у телефона видна только на странице «Контакты».
 *
 * @param Field $field Поле.
 */
function skinlove_phone_note_help( $field ) {
	$field->set_help_text( 'Над номером на странице «Контакты», например «(только звонки)».' );
}

/**
 * Телефоны из настроек: подпись, номер для tel: и пометка на текущем языке.
 *
 * @return array<int,array{label:string,href:string,note:string}>
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
				'note'  => skinlove_entry( $entry, 'note' ),
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

/**
 * Карта из настроек: из вставленного кода берётся только адрес первого
 * iframe, и только с google.com или yandex.ru (с поддоменами). Сам iframe
 * собирается заново — чужие атрибуты и скрипты в вывод не попадают.
 *
 * @param string $class Класс iframe из вёрстки.
 * @return string HTML iframe или пустая строка.
 */
function skinlove_map_embed( $class ) {
	$tags = new WP_HTML_Tag_Processor( (string) skinlove_option_raw( 'map_code' ) );

	if ( ! $tags->next_tag( 'iframe' ) ) {
		return '';
	}

	$src    = html_entity_decode( (string) $tags->get_attribute( 'src' ) );
	$scheme = wp_parse_url( $src, PHP_URL_SCHEME );
	$host   = strtolower( (string) wp_parse_url( $src, PHP_URL_HOST ) );

	if ( 'https' !== $scheme || ! preg_match( '/(^|\.)(google\.com|yandex\.ru)$/', $host ) ) {
		return '';
	}

	return sprintf(
		'<iframe class="%1$s" src="%2$s" title="%3$s" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>',
		esc_attr( $class ),
		esc_url( $src ),
		esc_attr( skinlove_t( 'map_title' ) )
	);
}

/**
 * Фото «до / после» для секции «Результаты».
 *
 * @return int[] ID вложений.
 */
function skinlove_results() {
	return array_values( array_filter( array_map( 'intval', (array) carbon_get_theme_option( 'sl_results' ) ) ) );
}
