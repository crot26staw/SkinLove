<?php
/**
 * Сид страницы «Коррекция фигуры и массаж»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/body.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$body = skinlove_seed_page( 'body', 'Коррекция фигуры и массаж' );

skinlove_seed_pair(
	$body,
	'seo_title',
	'Коррекция фигуры и массаж — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Body contouring and massage — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$body,
	'seo_description',
	'Коррекция фигуры и массаж в клинике SkinLove: RSL-скульптурирование, прессотерапия, обёртывания, ручной массаж и ручная липосакция в Дубае.',
	'Body contouring and massage at SkinLove: RSL sculpting, pressotherapy, body wraps, manual massage and manual lipo-sculpting in Dubai.'
);

$tasks = array(
	array(
		'title' => array( 'Расслабление мышц', 'Muscle relaxation' ),
		'text'  => array( 'Снимаем напряжение после работы, тренировок и длительных перелётов.', 'We release tension after work, training and long flights.' ),
	),
	array(
		'title' => array( 'Улучшение качества кожи', 'Better skin quality' ),
		'text'  => array( 'Повышаем тонус, упругость и гладкость кожи.', 'We improve skin tone, firmness and smoothness.' ),
	),
	array(
		'title' => array( 'Коррекция силуэта', 'Silhouette refinement' ),
		'text'  => array( 'Помогаем подчеркнуть естественные контуры тела и сделать их более гармоничными.', 'We help enhance your body’s natural contours and bring them into balance.' ),
	),
	array(
		'title' => array( 'Уменьшение отёчности', 'Less puffiness' ),
		'text'  => array( 'Стимулируем лимфоток и помогаем избавиться от ощущения тяжести.', 'We stimulate lymphatic flow and help relieve a feeling of heaviness.' ),
	),
	array(
		'title' => array( 'Работа с целлюлитом', 'Cellulite care' ),
		'text'  => array( 'Комплексный подход для улучшения текстуры кожи и локальных изменений.', 'A comprehensive approach to improving skin texture and localised changes.' ),
	),
);

$procedures = array(
	array(
		'pairs'  => array(
			'tab'   => array( 'RSL-скульптурирование', 'RSL sculpting' ),
			'title' => array( 'Моделирование силуэта и улучшение качества кожи тела', 'Body shaping and better skin quality' ),
			'text'  => array(
				'Аппаратная процедура RSL-скульптурирования помогает уменьшить выраженность целлюлита, улучшить микроциркуляцию и лимфоотток, повысить упругость кожи и сделать контуры тела более ровными и подтянутыми.',
				'RSL sculpting is a device-based treatment that helps reduce the appearance of cellulite, improve microcirculation and lymphatic drainage, increase skin firmness and make body contours smoother and more toned.',
			),
		),
		'image'  => array( 'body-rsl.jpg', 'Специалист проводит RSL-скульптурирование живота' ),
		'prices' => array(
			skinlove_seed_price_group( 'RSL-скульптурирование', 'RSL sculpting' ),
			skinlove_seed_price( '30 минут', '300 AED', '30 minutes' ),
			skinlove_seed_price( '40 минут', '350 AED', '40 minutes' ),
			skinlove_seed_price( '60 минут', '450 AED', '60 minutes' ),
			skinlove_seed_price( 'Курс 40 минут × 5 процедур', '1700 AED', 'Course: 40 minutes × 5 sessions' ),
			skinlove_seed_price( 'Курс 40 минут × 10 процедур', '3000 AED', 'Course: 40 minutes × 10 sessions' ),
			skinlove_seed_price( 'Курс 60 минут × 5 процедур', '2100 AED', 'Course: 60 minutes × 5 sessions' ),
			skinlove_seed_price( 'Курс 60 минут × 10 процедур', '3800 AED', 'Course: 60 minutes × 10 sessions' ),
			skinlove_seed_price_group( 'Комплексы RSL + прессотерапия', 'RSL + pressotherapy packages' ),
			skinlove_seed_price( '40 минут RSL + 60 минут прессотерапии × 5 процедур', '1700 AED', '40 minutes RSL + 60 minutes pressotherapy × 5 sessions' ),
			skinlove_seed_price( '40 минут RSL + 60 минут прессотерапии × 10 процедур', '3000 AED', '40 minutes RSL + 60 minutes pressotherapy × 10 sessions' ),
			skinlove_seed_price( '60 минут RSL + 60 минут прессотерапии × 5 процедур', '2100 AED', '60 minutes RSL + 60 minutes pressotherapy × 5 sessions' ),
			skinlove_seed_price( '60 минут RSL + 60 минут прессотерапии × 10 процедур', '3800 AED', '60 minutes RSL + 60 minutes pressotherapy × 10 sessions' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Прессотерапия', 'Pressotherapy' ),
			'title' => array( 'Лёгкость в теле, уменьшение отёчности и восстановление тонуса', 'A lighter feeling, less puffiness and restored tone' ),
			'text'  => array(
				'Прессотерапия помогает улучшить лимфоотток и кровообращение, уменьшить ощущение тяжести и отёчность, способствуя расслаблению и восстановлению после физических нагрузок.',
				'Pressotherapy helps improve lymphatic drainage and circulation and ease heaviness and puffiness, supporting relaxation and recovery after exercise.',
			),
		),
		'image'  => array( 'body-press.jpg', 'Пациентка в костюме для прессотерапии' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура', '279 AED', '1 session' ),
			skinlove_seed_price( '5 процедур', '1200 AED', '5 sessions' ),
			skinlove_seed_price( '10 процедур', '2000 AED', '10 sessions' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Обёртывания', 'Body wraps' ),
			'title' => array( 'Уход за телом для гладкости, упругости и улучшения тонуса кожи', 'Body care for smooth, firm, toned skin' ),
			'text'  => array(
				'Профессиональные обёртывания помогают увлажнить и смягчить кожу, улучшить её текстуру, уменьшить ощущение отёчности и повысить тонус. Процедуры подбираются индивидуально для комплексного ухода и коррекции силуэта.',
				'Professional body wraps hydrate and soften the skin, improve its texture, ease puffiness and increase tone. Treatments are selected individually for comprehensive care and silhouette refinement.',
			),
		),
		'image'  => array( 'body-wrap.jpg', 'Специалист наносит состав для обёртывания на спину пациентки' ),
		'prices' => array(
			skinlove_seed_price( 'Баночное обёртывание горячее', '250 AED', 'Hot body wrap' ),
			skinlove_seed_price( 'Баночное обёртывание холодное', '250 AED', 'Cold body wrap' ),
			skinlove_seed_price( 'T-Shock (бандажное обёртывание)', '550 AED', 'T-Shock (bandage wrap)' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Ручной массаж', 'Manual massage' ),
			'title' => array( 'Снятие напряжения, восстановление и забота о теле', 'Tension relief, recovery and body care' ),
			'text'  => array(
				'Ручной массаж помогает расслабить мышцы, улучшить кровообращение и уменьшить ощущение усталости. Индивидуально подобранные техники способствуют восстановлению после нагрузок, улучшению самочувствия и поддержанию тонуса тканей.',
				'Manual massage helps relax muscles, improve circulation and ease fatigue. Individually chosen techniques support recovery after exertion, improve well-being and help maintain tissue tone.',
			),
		),
		'image'  => array( 'body-massage.jpg', 'Массажист прорабатывает мышцы ноги' ),
		'prices' => array(
			skinlove_seed_price( 'Пробная, 60 минут', '350 AED', 'Trial, 60 minutes' ),
			skinlove_seed_price( 'Пробная, 30 минут', '175 AED', 'Trial, 30 minutes' ),
			skinlove_seed_price( 'Разовая, 60 минут', '450 AED', 'Single session, 60 minutes' ),
			skinlove_seed_price( 'Разовая, 30 минут', '230 AED', 'Single session, 30 minutes' ),
			skinlove_seed_price( 'Курс 5, 60 минут', '2000 AED', 'Course of 5, 60 minutes' ),
			skinlove_seed_price( 'Курс 5, 30 минут', '1000 AED', 'Course of 5, 30 minutes' ),
			skinlove_seed_price( 'Курс 10, 60 минут', '3500 AED', 'Course of 10, 60 minutes' ),
			skinlove_seed_price( 'Курс 10, 30 минут', '1700 AED', 'Course of 10, 30 minutes' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Ручная липосакция', 'Manual lipo-sculpting' ),
			'title' => array( 'Моделирование силуэта и коррекция локальных объёмов', 'Body shaping and refinement of localised volume' ),
			'text'  => array(
				'Мануальная процедура направлена на проработку проблемных зон, улучшение микроциркуляции и уменьшение выраженности неровностей кожи.',
				'This hands-on treatment works on problem areas, improves microcirculation and reduces the appearance of uneven skin.',
			),
		),
		'image'  => array( 'body-lipo.jpg', 'Специалист выполняет ручную липосакцию руки' ),
		'prices' => array(
			skinlove_seed_price( 'Живот', '550 AED', 'Abdomen' ),
			skinlove_seed_price( 'Колено', '550 AED', 'Knee' ),
			skinlove_seed_price( 'Задняя поверхность бедра', '550 AED', 'Back of thigh' ),
			skinlove_seed_price( 'Нога', '550 AED', 'Leg' ),
			skinlove_seed_price( 'Рука', '550 AED', 'Arm' ),
			skinlove_seed_price( 'Бок', '550 AED', 'Flank' ),
			skinlove_seed_price( 'Спина (одна сторона)', '550 AED', 'Back (one side)' ),
		),
	),
);

$steps = array(
	array(
		'title' => array( 'Консультация', 'Consultation' ),
		'text'  => array(
			'Специалист определяет ваши цели, оценивает особенности тела и составляет программу процедур.',
			'The specialist defines your goals, assesses your body’s needs and draws up a treatment programme.',
		),
		'image' => array( 'outcall-step-1.jpg' ),
	),
	array(
		'title' => array( 'Подбор методики', 'Choosing the technique' ),
		'text'  => array(
			'Мы выбираем наиболее эффективное сочетание аппаратных технологий и массажных техник.',
			'We choose the most effective combination of device-based technologies and massage techniques.',
		),
		'image' => array( 'body-massage.jpg' ),
	),
	array(
		'title' => array( 'Проведение процедуры', 'The treatment' ),
		'text'  => array(
			'Процедура проходит в спокойной атмосфере с максимальным комфортом для пациента.',
			'The treatment takes place in a calm atmosphere, with your comfort as the priority.',
		),
		'image' => array( 'body-rsl.jpg' ),
	),
	array(
		'title' => array( 'План поддержки', 'Maintenance plan' ),
		'text'  => array(
			'После сеанса специалист даёт рекомендации по образу жизни, домашнему уходу и дальнейшему курсу процедур.',
			'After the session, the specialist gives advice on lifestyle, home care and the rest of your course.',
		),
		'image' => array( 'iv-step-4.jpg' ),
	),
);

$faq = array(
	array(
		'question' => array( 'Как понять, какая процедура подойдёт именно мне?', 'How do I know which treatment is right for me?' ),
		'answer'   => array(
			'Во время консультации специалист оценит ваши цели, особенности организма и предложит оптимальную программу.',
			'During the consultation, the specialist will assess your goals and your body’s needs and suggest the best programme.',
		),
	),
	array(
		'question' => array( 'Сколько процедур потребуется?', 'How many sessions will I need?' ),
		'answer'   => array(
			'Количество зависит от исходного состояния и желаемого результата. Для достижения выраженного эффекта чаще всего рекомендуется курс.',
			'It depends on your starting point and the result you want. For a noticeable effect, a course is usually recommended.',
		),
	),
	array(
		'question' => array( 'Помогают ли процедуры снизить вес?', 'Do the treatments help with weight loss?' ),
		'answer'   => array(
			'Нет. Основная задача процедур — улучшение контуров тела, качества кожи и уменьшение локальных эстетических изменений. Для снижения веса важен комплексный подход, включающий питание и физическую активность.',
			'No. The treatments are designed to improve body contours and skin quality and reduce localised aesthetic concerns. Weight loss calls for a comprehensive approach that includes nutrition and physical activity.',
		),
	),
	array(
		'question' => array( 'Можно ли сочетать аппаратные процедуры и массаж?', 'Can device-based treatments be combined with massage?' ),
		'answer'   => array(
			'Да. Во многих случаях именно комплексный подход позволяет получить наиболее заметный и стойкий результат.',
			'Yes. In many cases, a combined approach gives the most noticeable and lasting result.',
		),
	),
	array(
		'question' => array( 'Есть ли противопоказания?', 'Are there any contraindications?' ),
		'answer'   => array(
			'Перед началом курса специалист обязательно проводит консультацию и исключает возможные противопоказания.',
			'Before starting a course, the specialist always holds a consultation and rules out any contraindications.',
		),
	),
);

skinlove_seed_sections(
	$body,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Коррекция\n*фигуры и массаж*", "Body contouring\n*and massage*" ),
				'lead'   => array( 'Гармония тела начинается с заботы о себе', 'A body in balance starts with self-care' ),
				'text'   => array(
					'Современные аппаратные методики и профессиональный массаж помогают скорректировать контуры тела, уменьшить отёчность, улучшить качество кожи и снять накопившееся напряжение. Программы подбираются индивидуально с учётом ваших целей и особенностей организма.',
					'Modern device-based techniques and professional massage help refine body contours, reduce puffiness, improve skin quality and release built-up tension. Programmes are tailored to your goals and your body’s needs.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type' => 'service-hero',
				'image' => skinlove_seed_image( 'body-hero.jpg' ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Задачи', 'Your goals' ),
				'title' => array( "Решения для красоты тела\n*и хорошего самочувствия*", "Solutions for a beautiful body\n*and well-being*" ),
			),
			array(
				'_type' => 'tasks',
				'items' => array_map( 'skinlove_seed_entry', $tasks ),
			)
		),
		array(
			'_type'        => 'procedures',
			'prices_limit' => 16,
			'items'        => skinlove_seed_procedures( $procedures, array( 'Записаться на процедуру', 'Book a treatment' ) ),
		),
		...skinlove_seed_service_sections(
			array(
				'promos'      => array( 'svc-2-inject.jpg', 'inject-step-3.jpg', 'svc-3-face.jpg', 'wellness-consultation.jpg' ),
				'steps_title' => array( "Комфортный уход\n*с индивидуальным подходом*", "Comfortable care\n*with a personal approach*" ),
				'steps'       => $steps,
				'request'     => array(
					'title'  => array( "Подберём решение\n*под ваши цели*", "We’ll find a solution\n*for your goals*" ),
					'text'   => array(
						'Оставьте контакты — администратор SkinLove уточнит ваш запрос и поможет подобрать подходящую процедуру, специалиста и удобное время для визита.',
						'Leave your details and a SkinLove administrator will clarify your request and help you choose the right treatment, specialist and a convenient time for your visit.',
					),
					'button' => array( 'Подобрать процедуру', 'Find my treatment' ),
					'image'  => array( 'body-form.jpg', 'Пациентка во время прессотерапии' ),
				),
				'advantages'  => 'body',
				'faq'         => $faq,
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Сделайте первый шаг к телу,\n*в котором вам комфортно*", "Take the first step towards a body\n*you feel at home in*" ),
				'text'   => array(
					'Запишитесь на консультацию, и мы подберём программу, которая поможет улучшить контуры тела, качество кожи и общее самочувствие.',
					'Book a consultation and we’ll select a programme to help improve your body contours, skin quality and overall well-being.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover', 'stacked' ),
				'image'   => skinlove_seed_image( 'body-hero.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «Коррекция фигуры и массаж» заполнена' );
