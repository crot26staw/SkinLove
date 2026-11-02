<?php
/**
 * Сид страницы «Выездные услуги»: шаблон, SEO, секции и меню шапки
 * лендинга. Услуги каталога — отдельный сид outcall-services.php.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/outcall.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$outcall = skinlove_seed_page( 'outcall', 'Выездные услуги', array( 'page_template' => 'page-outcall.php' ) );

skinlove_seed_pair(
	$outcall,
	'seo_title',
	'Выездные услуги — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'At-home services — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$outcall,
	'seo_description',
	'Выездные услуги SkinLove: процедуры эстетической медицины и wellness у вас дома, в отеле или офисе в Дубае.',
	'SkinLove at-home services: aesthetic medicine and wellness treatments at your home, hotel or office in Dubai.'
);

$book = array( 'Заказать выезд специалиста', 'Book a home visit' );

$sections = array(
	skinlove_seed_entry(
		array(
			'title'      => array( "Медицинские и\u{a0}косметологические услуги с выездом по Дубаю", 'Medical and aesthetic services with home visits across Dubai' ),
			'text'       => array(
				'Получайте медицинские, косметологические и wellness-услуги дома, в отеле, офисе или на яхте — с привычным комфортом и вниманием специалистов SkinLove.',
				'Receive medical, aesthetic and wellness services at home, in your hotel, at the office or on a yacht — with the comfort you are used to and the attention of SkinLove specialists.',
			),
			'card_title' => array( 'Организуем выезд специально для вас', 'We will arrange a visit just for you' ),
			'card_text'  => array(
				'Оставьте контакты — мы уточним ваш запрос, поможем выбрать услугу и согласуем удобное время и место.',
				'Leave your details — we will clarify your request, help you choose a service and agree on a convenient time and place.',
			),
			'button'     => $book,
		),
		array(
			'_type' => 'outcall-hero',
			'image' => skinlove_seed_image( 'svc-6-outcall.jpg', 'Девушка отдыхает в шезлонге у воды во время капельницы' ),
			'tags'  => array_map(
				fn( $label ) => skinlove_seed_entry( array( 'label' => $label ) ),
				array(
					array( 'Квалифицированные специалисты', 'Qualified specialists' ),
					array( 'В удобное для вас время', 'At a time that suits you' ),
					array( 'Выезд по всему Дубаю', 'Visits across Dubai' ),
				)
			),
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'Не меняйте свои планы ради визита в клинику', 'Don’t change your plans for a clinic visit' ),
			'text'   => array(
				'Работа, путешествия, личные дела — ваш день уже расписан. Доверьте организацию визита SkinLove: мы согласуем удобное время и место, чтобы вы могли позаботиться о себе без лишних поездок и изменений в расписании.',
				'Work, travel, personal commitments — your day is already planned. Let SkinLove arrange the visit: we will agree on a convenient time and place so you can take care of yourself without extra trips or changes to your schedule.',
			),
			'button' => array( 'Вызвать специалиста', 'Request a specialist' ),
		),
		array(
			'_type' => 'reasons',
			'items' => array_map(
				fn( $item ) => skinlove_seed_entry( $item ),
				array(
					array(
						'title' => array( 'При плотном графике', 'With a busy schedule' ),
						'text'  => array(
							'Не тратьте время на дорогу и ожидание. Получайте необходимые услуги, не отвлекаясь надолго от важных дел.',
							'Don’t waste time on travel and waiting. Get the services you need without stepping away from important matters for long.',
						),
					),
					array(
						'title' => array( 'Во время путешествия', 'While travelling' ),
						'text'  => array(
							'Наслаждайтесь отдыхом или сосредоточьтесь на делах в Дубае. Мы поможем организовать визит специалиста прямо в вашем отеле.',
							'Enjoy your holiday or focus on business in Dubai. We will help arrange a specialist’s visit right at your hotel.',
						),
					),
					array(
						'title' => array( 'В привычной обстановке', 'In familiar surroundings' ),
						'text'  => array(
							'Выбирайте комфорт и приватность собственного пространства. Получайте профессиональную заботу там, где вам спокойно и удобно.',
							'Choose the comfort and privacy of your own space. Receive professional care where you feel calm and at ease.',
						),
					),
				)
			),
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'В ваших планах всегда должно быть место для себя', 'Your plans should always have room for you' ),
			'text'   => array(
				'Каким бы насыщенным ни был ваш день, не откладывайте заботу о здоровье и самочувствии. SkinLove поможет организовать визит специалиста там и тогда, где вам удобно.',
				'However busy your day, don’t put off caring for your health and wellbeing. SkinLove will arrange a specialist’s visit wherever and whenever suits you.',
			),
			'button' => array( 'Позаботиться о себе', 'Take care of yourself' ),
		),
		array(
			'_type'   => 'program-request',
			'variant' => 'outcall',
			'inset'   => 'inset-sm',
			'options' => array(),
			'image'   => skinlove_seed_image( 'outcall-request.jpg', 'Пациентка в кресле во время капельницы' ),
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'Выберите подходящую услугу', 'Choose the right service' ),
			'text'   => array(
				'От витаминных капельниц до консультации врача и персональных wellness-программ. Выберите направление — мы уточним ваш запрос и поможем организовать визит специалиста.',
				'From vitamin drips to a doctor’s consultation and personal wellness programmes. Choose a category — we will clarify your request and help arrange a specialist’s visit.',
			),
			'button' => array( 'Узнать программу', 'Ask about the programme' ),
		),
		array(
			'_type' => 'outcall-services',
			'limit' => '6',
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'Получите необходимую помощь без поездки в клинику', 'Get the care you need without a trip to the clinic' ),
			'text'   => array(
				'Расскажите о своём запросе — мы поможем подобрать подходящую услугу, согласуем детали и организуем выезд специалиста SkinLove по вашему адресу.',
				'Tell us what you need — we will help you choose the right service, agree on the details and arrange for a SkinLove specialist to visit your address.',
			),
			'button' => array( 'Организовать выезд', 'Arrange a visit' ),
		),
		array(
			'_type'   => 'program-request',
			'variant' => 'outcall',
			'inset'   => 'inset-sm',
			'options' => array( 'reverse', 'flush-end' ),
			'image'   => skinlove_seed_image( 'outcall-help.jpg', 'Три девушки отдыхают на террасе во время капельниц' ),
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'Позаботьтесь о себе, не меняя своих планов', 'Take care of yourself without changing your plans' ),
			'text'   => array(
				'Оставьте заявку — мы поможем подобрать подходящую услугу, согласуем удобное время и место и возьмём организацию выезда на себя.',
				'Send a request — we will help you choose the right service, agree on a convenient time and place, and take care of organising the visit.',
			),
			'button' => $book,
		),
		array(
			'_type'   => 'program-request',
			'variant' => 'outcall',
			'inset'   => 'inset',
			'options' => array( 'image-low' ),
			'image'   => skinlove_seed_image( 'outcall-final.jpg', 'Массаж на открытом воздухе среди камней' ),
		)
	),
);

skinlove_seed_sections( $outcall, $sections );

skinlove_seed_menu(
	'Шапка «Выездных услуг» (RU)',
	'outcall_ru',
	array(
		array( 'url' => '#services', 'title' => 'Услуги' ),
		array( 'url' => '#process', 'title' => 'Этапы' ),
		array( 'url' => '#reviews', 'title' => 'Отзывы' ),
		array( 'url' => '#faq', 'title' => 'Вопросы' ),
		array( 'page' => 'contacts', 'title' => 'Контакты' ),
	)
);

skinlove_seed_menu(
	'Шапка «Выездных услуг» (EN)',
	'outcall_en',
	array(
		array( 'url' => '#services', 'title' => 'Services' ),
		array( 'url' => '#process', 'title' => 'Steps' ),
		array( 'url' => '#reviews', 'title' => 'Reviews' ),
		array( 'url' => '#faq', 'title' => 'FAQ' ),
		array( 'page' => 'contacts', 'title' => 'Contacts' ),
	)
);

WP_CLI::success( 'Выездные услуги заполнены' );
