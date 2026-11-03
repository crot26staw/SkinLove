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
		'social_instagram_medical' => array(
			'ru' => 'SkinLove Medical Clinic в Instagram',
			'en' => 'SkinLove Medical Clinic on Instagram',
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
		'programs_all'      => array(
			'ru' => 'Все виды',
			'en' => 'All',
		),
		'programs_filters'  => array(
			'ru' => 'Виды программ',
			'en' => 'Programme types',
		),
		'programs_more'     => array(
			'ru' => 'Показать остальные программы',
			'en' => 'Show more programmes',
		),
		'programs_less'     => array(
			'ru' => 'Скрыть программы',
			'en' => 'Show fewer programmes',
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
		'outcall_nav'       => array(
			'ru' => 'Разделы страницы',
			'en' => 'Page sections',
		),
		'outcall_book'      => array(
			'ru' => 'Заказать выезд специалиста',
			'en' => 'Book a home visit',
		),
		'contact_links'     => array(
			'ru' => 'Или свяжитесь с нами напрямую и расскажите, что вам необходимо',
			'en' => 'Or contact us directly and tell us what you need',
		),
		'quiz_back'         => array(
			'ru' => 'Назад',
			'en' => 'Back',
		),
		'quiz_next'         => array(
			'ru' => 'Продолжить',
			'en' => 'Continue',
		),
		'quiz_unknown'      => array(
			'ru' => 'Не определился',
			'en' => 'Not sure yet',
		),
		'quiz_address'      => array(
			'ru' => 'Адрес выезда',
			'en' => 'Visit address',
		),
		'quiz_error_many'   => array(
			'ru' => 'Выберите хотя бы один вариант',
			'en' => 'Choose at least one option',
		),
		'quiz_error_one'    => array(
			'ru' => 'Выберите один из вариантов',
			'en' => 'Choose one of the options',
		),
		'quiz_error_date'   => array(
			'ru' => 'Выберите дату или отметьте, что ещё не определились',
			'en' => 'Choose a date or tick that you are not sure yet',
		),
		'quiz_error_address' => array(
			'ru' => 'Напишите адрес или отметьте, что ещё не определились',
			'en' => 'Enter an address or tick that you are not sure yet',
		),
		'quiz_submit'       => array(
			'ru' => 'Отправить заявку',
			'en' => 'Send request',
		),
		'date_placeholder'  => array(
			'ru' => 'ДД.ММ.ГГ',
			'en' => 'DD/MM/YY',
		),
		'date_locale'       => array(
			'ru' => 'ru',
			'en' => 'en-GB',
		),
		'date_dialog'       => array(
			'ru' => 'Выбор даты и времени',
			'en' => 'Choose date and time',
		),
		'date_prev_month'   => array(
			'ru' => 'Предыдущий месяц',
			'en' => 'Previous month',
		),
		'date_next_month'   => array(
			'ru' => 'Следующий месяц',
			'en' => 'Next month',
		),
		'date_prev_day'     => array(
			'ru' => 'Предыдущий день',
			'en' => 'Previous day',
		),
		'date_next_day'     => array(
			'ru' => 'Следующий день',
			'en' => 'Next day',
		),
		'date_time'         => array(
			'ru' => 'Время',
			'en' => 'Time',
		),
		'date_hint'         => array(
			'ru' => 'Сначала выберите день',
			'en' => 'Choose a day first',
		),
		'feedback_prev'     => array(
			'ru' => 'Предыдущие отзывы',
			'en' => 'Previous reviews',
		),
		'feedback_next'     => array(
			'ru' => 'Следующие отзывы',
			'en' => 'Next reviews',
		),
	);
}
