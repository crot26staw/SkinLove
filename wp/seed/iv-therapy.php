<?php
/**
 * Сид страницы «IV Therapy»: SEO и секции. Программы каталога — отдельный
 * сид programs.php.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/iv-therapy.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$iv = skinlove_seed_page( 'iv-therapy', 'IV Therapy' );

skinlove_seed_pair(
	$iv,
	'seo_title',
	'IV Therapy — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'IV Therapy — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$iv,
	'seo_description',
	'IV Therapy в клинике SkinLove: внутривенные капельницы для восстановления, энергии и иммунитета. В клинике или с выездом в удобное для вас место.',
	'IV Therapy at SkinLove: intravenous drips for recovery, energy and immunity. At the clinic or at a location that suits you.'
);

$tasks = array(
	array(
		'title' => array( 'При интенсивных тренировках', 'With intense training' ),
		'text'  => array( 'Восстановить силы и поддержать организм после спортивных нагрузок.', 'Regain your strength and support your body after workouts.' ),
	),
	array(
		'title' => array( 'При высоком уровне стресса', 'Under high stress' ),
		'text'  => array( 'Поддержать организм в период интенсивной работы и эмоциональных нагрузок.', 'Support your body through demanding work and emotional strain.' ),
	),
	array(
		'title' => array( 'После перелётов', 'After flights' ),
		'text'  => array( 'Быстрее адаптироваться к смене часовых поясов и восстановить силы.', 'Adapt to time-zone changes faster and regain your strength.' ),
	),
	array(
		'title' => array( 'После мероприятий', 'After events' ),
		'text'  => array( 'Помочь организму быстрее вернуться к привычному состоянию.', 'Help your body get back to its usual state sooner.' ),
	),
	array(
		'title' => array( 'При дефицитах витаминов', 'With vitamin deficiencies' ),
		'text'  => array( 'Поддержать организм по результатам анализов и рекомендациям специалиста.', 'Support your body based on test results and your specialist’s recommendations.' ),
	),
);

$steps = array(
	array(
		'title' => array( 'Консультация', 'Consultation' ),
		'text'  => array(
			'Специалист уточняет ваш запрос, собирает анамнез и при необходимости рекомендует анализы.',
			'The specialist clarifies your request, takes your medical history and recommends tests if needed.',
		),
		'image' => array( 'outcall-step-1.jpg' ),
	),
	array(
		'title' => array( 'Подбор состава', 'Choosing the formula' ),
		'text'  => array(
			'Программа составляется индивидуально с учётом ваших целей и состояния организма.',
			'Your programme is tailored to your goals and your body’s condition.',
		),
		'image' => array( 'iv-step-2.jpg', 'Флаконы для капельницы на стойке' ),
	),
	array(
		'title' => array( 'Проведение процедуры', 'The treatment' ),
		'text'  => array(
			'Капельница проходит в комфортной атмосфере клиники или с выездом в удобное для вас место.',
			'Your drip takes place in the comfort of the clinic or at a location that suits you.',
		),
		'image' => array( 'iv-step-3.jpg', 'Пациентка в кресле во время капельницы' ),
	),
	array(
		'title' => array( 'Рекомендации', 'Recommendations' ),
		'text'  => array(
			'После процедуры вы получите рекомендации и дальнейший план поддержки организма.',
			'After the treatment you’ll receive recommendations and a plan for ongoing support.',
		),
		'image' => array( 'iv-step-4.jpg' ),
	),
);

$faq = array(
	array(
		'question' => array( 'Кому подходит IV Therapy?', 'Who is IV Therapy suitable for?' ),
		'answer'   => array(
			'После консультации специалист поможет подобрать программу, которая соответствует вашим задачам и состоянию здоровья.',
			'After a consultation, the specialist will help you choose a programme that matches your goals and health.',
		),
	),
	array(
		'question' => array( 'Нужно ли сдавать анализы?', 'Do I need any tests?' ),
		'answer'   => array(
			'Не всегда. Для базовых программ достаточно консультации, а при подборе персонального состава специалист может рекомендовать анализы — их можно сдать в клинике или с выездом.',
			'Not always. A consultation is enough for basic programmes; for a personalised formula the specialist may recommend tests, which you can take at the clinic or at home.',
		),
	),
	array(
		'question' => array( 'Сколько длится процедура?', 'How long does the treatment take?' ),
		'answer'   => array(
			'В среднем от 30 до 60 минут в зависимости от состава. Всё это время вы отдыхаете в комфортном кресле под наблюдением специалиста.',
			'On average 30 to 60 minutes, depending on the formula. Throughout, you relax in a comfortable chair under a specialist’s supervision.',
		),
	),
	array(
		'question' => array( 'Можно ли вызвать специалиста домой?', 'Can a specialist come to my home?' ),
		'answer'   => array(
			'Да. Капельницы доступны с выездом домой, в отель или офис: специалист привезёт всё необходимое и проведёт процедуру по тем же стандартам, что и в клинике.',
			'Yes. Drips are available at your home, hotel or office: the specialist brings everything needed and carries out the treatment to the same standards as at the clinic.',
		),
	),
	array(
		'question' => array( 'Есть ли противопоказания?', 'Are there any contraindications?' ),
		'answer'   => array(
			'Да, как у любой медицинской процедуры. Поэтому перед первой капельницей специалист собирает анамнез и при необходимости рекомендует анализы, чтобы подобрать безопасный состав.',
			'Yes, as with any medical procedure. That’s why before your first drip the specialist takes your medical history and recommends tests if needed, to choose a safe formula.',
		),
	),
);

// Общие секции страниц услуг; на IV порядок другой и «Результатов» нет.
$shared = array_column(
	skinlove_seed_service_sections(
		array(
			'background'       => 'light',
			'promos'           => array( 'svc-1-iv.jpg', 'outcall-vitamin.jpg', 'iv-form.jpg', 'outcall-tests.jpg' ),
			'promos_blinds'    => true,
			'steps_title'      => array( "Комфортно. Безопасно\n*Под наблюдением специалиста*", "Comfortable. Safe\n*Under a specialist’s supervision*" ),
			'steps'            => $steps,
			'request'          => array(
				'title'  => array( "Подберём программу\n*под ваши задачи*", "We’ll find a programme\n*to suit your goals*" ),
				'text'   => array(
					'Оставьте контакты — администратор SkinLove свяжется с вами, уточнит запрос и поможет выбрать подходящую программу IV Therapy и удобный формат процедуры.',
					'Leave your details and a SkinLove administrator will get in touch, clarify your request and help you choose the right IV Therapy programme and a convenient treatment format.',
				),
				'button' => array( 'Подобрать программу', 'Find my programme' ),
				'image'  => array( 'iv-form.jpg', 'Пациентка в кресле во время процедуры' ),
			),
			'advantages'       => 'iv',
			'advantages_count' => 5,
			'faq'              => $faq,
		)
	),
	null,
	'_type'
);

skinlove_seed_sections(
	$iv,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( '*IV Therapy*', '*IV Therapy*' ),
				'lead'   => array( 'Восстановление начинается изнутри', 'Recovery starts from within' ),
				'text'   => array(
					'Персонально подобранные витаминные капельницы помогают восполнить дефициты, поддержать организм, восстановиться после нагрузок и сохранить высокий уровень энергии. Каждая программа составляется индивидуально с учётом ваших целей, состояния здоровья и рекомендаций специалиста.',
					'Personally tailored vitamin drips help replenish deficiencies, support your body, recover after exertion and keep your energy high. Every programme is put together individually, taking into account your goals, health and your specialist’s recommendations.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'        => 'service-hero',
				'image'        => skinlove_seed_image( 'svc-1-iv.jpg' ),
				'header_plain' => true,
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Задачи', 'Your goals' ),
				'title' => array(
					"Организм каждый день сталкивается с нагрузками.\n*Иногда ему просто нужна дополнительная поддержка*",
					"Your body faces demands every day.\n*Sometimes it just needs extra support*",
				),
			),
			array(
				'_type' => 'tasks',
				'items' => array_map( 'skinlove_seed_entry', $tasks ),
			)
		),
		skinlove_seed_entry(
			array(
				'label'  => array( 'Виды', 'Programmes' ),
				'title'  => array( "Более 60 программ\n*для различных задач*", "Over 60 programmes\n*for every goal*" ),
				'text'   => array(
					'От быстрого восстановления после перелёта или напряжённой недели до комплексной поддержки энергии, иммунитета, сна и общего самочувствия.',
					'From quick recovery after a flight or a demanding week to all-round support for energy, immunity, sleep and overall well-being.',
				),
				'button' => array( 'Записаться на процедуру', 'Book a treatment' ),
			),
			array(
				'_type' => 'programs',
				'limit' => '',
			)
		),
		$shared['program-request'],
		$shared['promos'],
		$shared['steps'],
		$shared['advantages'],
		$shared['reviews'],
		$shared['faq'],
		skinlove_seed_entry(
			array(
				'title'  => array( "Позаботьтесь о своём\n*организме уже сегодня*", "Take care of your body\n*starting today*" ),
				'text'   => array(
					'Оставьте заявку — администратор SkinLove свяжется с вами, поможет подобрать программу IV Therapy и удобное время для процедуры.',
					'Send a request and a SkinLove administrator will get in touch to help you choose an IV Therapy programme and a convenient time for your treatment.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array(),
				'image'   => skinlove_seed_image( 'iv-form.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «IV Therapy» заполнена' );
