<?php
/**
 * Сид страницы «О клинике»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/about.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$about = skinlove_seed_page( 'about', 'О клинике' );

skinlove_seed_pair(
	$about,
	'seo_title',
	'О клинике — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'About the clinic — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$about,
	'seo_description',
	'О клинике SkinLove: команда, подход и философия заботы о красоте и здоровье. Эстетическая медицина и wellness в Дубае.',
	'About SkinLove: our team, our approach and our philosophy of caring for beauty and health. Aesthetic medicine and wellness in Dubai.'
);

$advantages = array(
	array(
		'title' => array( 'Персональный подход', 'A personal approach' ),
		'text'  => array(
			'Каждая программа строится с учётом вашего образа жизни, состояния здоровья и индивидуальных особенностей.',
			'Every programme is built around your lifestyle, your health and your individual needs.',
		),
	),
	array(
		'title' => array( 'Комплексная медицина', 'Integrated medicine' ),
		'text'  => array(
			'Мы объединяем эстетическую косметологию, IV Therapy, wellness и диагностику, чтобы работать не только с внешними изменениями, но и с внутренним состоянием организма.',
			'We bring together aesthetic treatments, IV Therapy, wellness and diagnostics to care not only for how you look, but for how your body feels from within.',
		),
	),
	array(
		'title' => array( 'Долгосрочные отношения', 'Long-term relationships' ),
		'text'  => array(
			'Мы стремимся стать вашим постоянным партнёром в вопросах красоты, здоровья и профилактики, а не местом для разового визита.',
			'We aim to be your long-term partner in beauty, health and prevention — not a place for a one-off visit.',
		),
	),
	array(
		'title' => array( 'Медицинская безопасность', 'Medical safety' ),
		'text'  => array(
			'Используем сертифицированные препараты, современное оборудование и работаем по международным медицинским стандартам.',
			'We use certified products and modern equipment, and work to international medical standards.',
		),
	),
	array(
		'title' => array( 'Выездной сервис', 'At-home service' ),
		'text'  => array(
			'Если приехать в клинику неудобно, мы организуем профессиональный выезд домой, в отель, офис или на яхту.',
			'If visiting the clinic isn’t convenient, our specialists will come to your home, hotel, office or yacht.',
		),
	),
	array(
		'title' => array( 'Естественная красота', 'Natural beauty' ),
		'text'  => array(
			'Мы не меняем внешность, а подчёркиваем индивидуальность и сохраняем гармоничные черты лица.',
			'We don’t change how you look — we enhance your individuality and preserve the natural harmony of your features.',
		),
	),
);

// Фото те же, что на главной: alt не передаём, чтобы не перезаписать его.
foreach ( $advantages as $index => $advantage ) {
	$advantages[ $index ] = skinlove_seed_entry(
		$advantage,
		array( 'image' => skinlove_seed_image( 'adv-' . ( $index + 1 ) . '.jpg' ) )
	);
}

$layers = array(
	array(
		'title' => array( "Пространство, в котором\n*легко расслабиться*", "A space where\n*it’s easy to unwind*" ),
		'text'  => array(
			'Каждая деталь SkinLove создана для того, чтобы вы могли переключиться с повседневных забот и почувствовать спокойствие уже с первых минут.',
			'Every detail at SkinLove is designed to help you leave everyday worries behind and feel at ease from the very first minutes.',
		),
	),
	array(
		'title' => array( "Время, которое\n*принадлежит только вам*", "Time that\n*belongs to you alone*" ),
		'text'  => array(
			'Продуманная обстановка, мягкий свет и внимание к деталям создают атмосферу, в которой можно замедлиться и посвятить время себе.',
			'Thoughtful surroundings, soft light and attention to detail create an atmosphere where you can slow down and devote time to yourself.',
		),
	),
);

skinlove_seed_sections(
	$about,
	array(
		skinlove_seed_entry(
			array(
				'lead' => array( 'Место, где эстетическая медицина становится заботой', 'A place where aesthetic medicine becomes care' ),
				'note' => array(
					'Здесь не стремятся изменить внешность — здесь помогают сохранить здоровье, подчеркнуть естественную красоту и сделать заботу о себе приятной частью жизни.',
					'We don’t set out to change how you look — we help you stay healthy, bring out your natural beauty and make self-care an enjoyable part of life.',
				),
			),
			array( '_type' => 'page-hero' )
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'философия', 'philosophy' ),
				'title' => array( '*Love —* буквально', '*Love —* literally' ),
				'quote' => array(
					'Для нас эстетическая медицина — это не про шаблонные стандарты красоты. Это про уважение к индивидуальности каждого человека.',
					'For us, aesthetic medicine isn’t about one-size-fits-all beauty standards. It’s about respecting each person’s individuality.',
				),
				'text'  => array(
					"Мы внимательно слушаем, не навязываем лишнего и всегда начинаем с понимания ваших целей. Именно поэтому наши программы строятся не вокруг процедур, а вокруг человека.\n\nМы убеждены, что лучший результат — тот, который выглядит естественно, помогает чувствовать себя увереннее и остаётся незаметным для окружающих.",
					"We listen carefully, never push anything unnecessary and always begin by understanding your goals. That is why our programmes are built not around treatments, but around the person.\n\nWe believe the best result is one that looks natural, helps you feel more confident and goes unnoticed by others.",
				),
			),
			array(
				'_type'       => 'philosophy',
				'image'       => skinlove_seed_image( 'philosophy-1.jpg', 'Косметолог проводит аппаратную процедуру' ),
				'image_left'  => skinlove_seed_image( 'philosophy-2.jpg', 'Специалист клиники с детьми' ),
				'image_right' => skinlove_seed_image( 'philosophy-3.jpg', 'Гостья клиники в зоне ожидания' ),
			)
		),
		skinlove_seed_entry(
			array(
				'title' => array( "SkinLove появилась из желания изменить\n*отношение к эстетической медицине*", "SkinLove was born from a wish to change\n*the way people see aesthetic medicine*" ),
				'lead'  => array(
					'Мы мечтали создать пространство, где профессиональная медицина сочетается с искренним сервисом, спокойной атмосферой и персональным подходом.',
					'We dreamed of a space where professional medicine meets genuine service, a calm atmosphere and a personal approach.',
				),
				'text'  => array(
					"Место, куда приходят не потому, что нужно что‑то исправить, а потому что здесь приятно заботиться о себе.\n\nСегодня SkinLove объединяет эстетическую медицину, wellness и современные технологии восстановления, помогая нашим гостям чувствовать себя лучше каждый день.",
					"A place people come to not because something needs fixing, but because it feels good to take care of themselves here.\n\nToday SkinLove brings together aesthetic medicine, wellness and modern recovery technologies, helping our guests feel better every day.",
				),
			),
			array(
				'_type' => 'frame-inset',
				'image' => skinlove_seed_image( 'about-reception.jpg' ),
			)
		),
		skinlove_seed_entry(
			array( 'title' => array( "Не просто процедуры,\n*а комплексная забота*", "Not just treatments,\n*but comprehensive care*" ) ),
			array(
				'_type' => 'advantages',
				'items' => $advantages,
			)
		),
		array(
			'_type'  => 'frame-reel',
			'layers' => array_map( 'skinlove_seed_entry', $layers ),
		),
		skinlove_seed_entry(
			array(
				'title' => array( 'Основательница SkinLove', 'The founder of SkinLove' ),
				'lead'  => array(
					'SkinLove появилась благодаря желанию объединить современную эстетическую медицину, заботу о здоровье и искренний сервис в одном пространстве.',
					'SkinLove grew out of a wish to bring modern aesthetic medicine, health care and genuine service together in one space.',
				),
				'since' => array( 'medical expertise', 'medical expertise' ),
				'quote' => array(
					'Мне хотелось создать клинику, в которой пациенты будут чувствовать себя спокойно, уверенно и по‑настоящему услышанными. Где красота начинается не с процедуры, а с заботы',
					'I wanted to create a clinic where patients feel calm, confident and truly heard. Where beauty begins not with a treatment, but with care',
				),
				'name'  => array( 'Анастасия Прилатова', 'Anastasia Prilatova' ),
			),
			array(
				'_type'       => 'founder',
				'image'       => skinlove_seed_image( 'founder.png', 'Анастасия Прилатова, основательница SkinLove' ),
				'quote_image' => skinlove_seed_image( 'founder-quote.jpg' ),
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Познакомьтесь\n*со SkinLove*\nлично", "Meet\n*SkinLove*\nin person" ),
				'text'   => array(
					'Будем рады встретить вас в клинике или организовать выезд специалиста в удобное для вас место.',
					'We’ll be happy to welcome you at the clinic or arrange for a specialist to visit you wherever suits you best.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover' ),
				'image'   => skinlove_seed_image( 'about-hero.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «О клинике» заполнена' );
