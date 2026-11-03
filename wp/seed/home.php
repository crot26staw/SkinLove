<?php
/**
 * Сид главной: SEO и секции. Карточки «Направлений» — из pages.php,
 * отзывы — из reviews.php.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/home.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$home = skinlove_seed_page( 'home', 'Главная' );

skinlove_seed_pair(
	$home,
	'seo_title',
	'SkinLove — клиника эстетической медицины и wellness в Дубае',
	'SkinLove — aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$home,
	'seo_description',
	'SkinLove — клиника эстетической медицины и wellness. Персональные решения для красоты, здоровья и восстановления: в клинике или с выездом в удобное для вас место.',
	'SkinLove is an aesthetic medicine and wellness clinic. Personalised solutions for beauty, health and recovery — at the clinic or wherever suits you best.'
);

$features = array(
	array(
		'title' => array( 'Любимый напиток', 'Your favourite drink' ),
		'text'  => array(
			'Мы запоминаем предпочтения наших гостей и встречаем их так, как любят именно они.',
			'We remember our guests’ preferences and welcome each of them just the way they like.',
		),
	),
	array(
		'title' => array( 'Спокойная атмосфера', 'A calm atmosphere' ),
		'text'  => array(
			'Интерьер создан так, чтобы вы могли расслабиться и переключиться с повседневных забот.',
			'The interior is designed to help you relax and leave everyday worries behind.',
		),
	),
	array(
		'title' => array( 'Внимание к деталям', 'Attention to detail' ),
		'text'  => array(
			'Каждый элемент пространства помогает почувствовать, что здесь вас действительно ждали.',
			'Every element of the space lets you feel that you were truly expected here.',
		),
	),
);

$advantages = skinlove_seed_advantages();

foreach ( $advantages as $index => $advantage ) {
	// Фото «Естественного результата» у главной своё, «О клинике» — с прежним.
	$file = 5 === $index ? 'home-adv-6.jpg' : 'adv-' . ( $index + 1 ) . '.jpg';

	$advantages[ $index ] = skinlove_seed_entry(
		$advantage,
		array( 'image' => skinlove_seed_image( $file, $advantage['title'][0] ) )
	);
}

skinlove_seed_sections(
	$home,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Клиника эстетической\n*медицины и wellness*", "Aesthetic medicine\n*and wellness clinic*" ),
				'lead'   => array(
					'Персональные решения для красоты, здоровья и восстановления — в клинике или с выездом в удобное для вас место.',
					'Personalised solutions for beauty, health and recovery — at the clinic or wherever suits you best.',
				),
				'button' => array( 'Выбрать направление', 'Choose a service' ),
			),
			array(
				'_type'      => 'hero',
				'button_url' => '#services',
			)
		),
		skinlove_seed_entry(
			array(
				'label'  => array( 'о клинике', 'about the clinic' ),
				'title'  => array( "SkinLove — не просто клиника.\n*Это место, куда хочется возвращаться.*", "SkinLove is more than a clinic.\n*It’s a place you’ll want to return to.*" ),
				'lead'   => array(
					'В SkinLove мы верим, что качественная эстетическая медицина начинается с доверия.',
					'At SkinLove, we believe that good aesthetic medicine begins with trust.',
				),
				'text'   => array(
					"Поэтому для нас важны не только современные методики и опыт специалистов, но и атмосфера, в которой вы чувствуете себя спокойно, уверенно и окружёнными заботой.\n\nМы стремимся к тому, чтобы каждый визит был комфортным с первой минуты — от тёплой встречи и внимательного отношения до результата, который выглядит естественно и соответствует именно вашим ожиданиям.",
					"That is why we care not only about modern techniques and experienced specialists, but also about an atmosphere in which you feel calm, confident and looked after.\n\nWe want every visit to be comfortable from the very first minute — from a warm welcome and attentive care to a result that looks natural and meets your own expectations.",
				),
				'button' => array( 'Подробнее о клинике', 'More about the clinic' ),
			),
			array(
				'_type'      => 'about',
				'button_url' => '/about/',
			)
		),
		array( '_type' => 'services' ),
		skinlove_seed_entry(
			array(
				'label' => array( 'атмосфера клиники', 'the clinic’s atmosphere' ),
				'title' => array( "Здесь забота ощущается\n*ещё до процедуры*", "Here, care begins\n*before the treatment*" ),
				'lead'  => array(
					'Для нас важен не только результат, но и то, как вы себя чувствуете на каждом этапе.',
					'What matters to us is not only the result, but how you feel at every step.',
				),
				'text'  => array(
					'Пока вы знакомитесь со специалистом и обсуждаете план лечения, мы уже готовим ваш любимый напиток, создаём комфортную атмосферу и заботимся о том, чтобы каждый визит оставлял приятные впечатления',
					'While you meet your specialist and discuss your treatment plan, we are already preparing your favourite drink, creating a comfortable atmosphere and making sure every visit leaves a pleasant impression',
				),
			),
			array(
				'_type'    => 'atmosphere',
				'image'    => skinlove_seed_image( 'atmosphere.jpg', 'Гостья клиники SkinLove у витрины с уходовыми средствами' ),
				'features' => array_map( 'skinlove_seed_entry', $features ),
			)
		),
		skinlove_seed_entry(
			array( 'title' => array( "Почему выбирают\n*SkinLove*", "Why clients choose\n*SkinLove*" ) ),
			array(
				'_type' => 'advantages',
				'items' => $advantages,
			)
		),
		skinlove_seed_entry(
			array(
				'label'  => array( 'Отзывы', 'Reviews' ),
				'title'  => array( "Нам доверяют\n*самое ценное — здоровье*", "Trusted with\n*what matters most — your health*" ),
				'button' => array( 'Больше отзывов', 'More reviews' ),
			),
			array(
				'_type'      => 'reviews',
				'button_url' => 'https://maps.app.goo.gl/Nn7DLKbUKJPvBk4n6',
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( 'Начните *с заботы* о себе', 'Start *by caring* for yourself' ),
				'text'   => array(
					'Оставьте заявку — администратор SkinLove свяжется с вами, уточнит запрос и поможет подобрать направление, специалиста и удобное время для визита.',
					'Leave a request and a SkinLove administrator will get in touch to discuss what you need and help you choose a service, a specialist and a convenient time for your visit.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array(),
				'image'   => skinlove_seed_image( 'cta.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Главная заполнена' );
