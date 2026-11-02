<?php
/**
 * Сид страницы «Wellness и диагностика»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/wellness.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$wellness = skinlove_seed_page( 'wellness', 'Wellness' );

skinlove_seed_pair(
	$wellness,
	'seo_title',
	'Wellness и диагностика — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Wellness and diagnostics — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$wellness,
	'seo_description',
	'Wellness и диагностика в клинике SkinLove: консультации врача, лабораторная диагностика, check-up программы и персональные программы восстановления в Дубае.',
	'Wellness and diagnostics at SkinLove: doctor consultations, laboratory tests, check-up programmes and personalised recovery programmes in Dubai.'
);

$tasks = array(
	array(
		'title' => array( 'Поддержание здоровья', 'Staying healthy' ),
		'text'  => array( 'Формируем индивидуальные программы профилактики и долгосрочного контроля состояния организма.', 'We create personal programmes for prevention and long-term health monitoring.' ),
	),
	array(
		'title' => array( 'Восстановление после перелётов и стресса', 'Recovery after flights and stress' ),
		'text'  => array( 'Помогаем быстрее адаптироваться к смене часовых поясов, интенсивному графику и эмоциональным нагрузкам.', 'We help you adapt faster to time-zone changes, a busy schedule and emotional strain.' ),
	),
	array(
		'title' => array( 'Хроническая усталость', 'Chronic fatigue' ),
		'text'  => array( 'Помогаем восстановить уровень энергии и улучшить общее самочувствие.', 'We help restore your energy levels and improve your overall well-being.' ),
	),
	array(
		'title' => array( 'Дефициты витаминов и микроэлементов', 'Vitamin and mineral deficiencies' ),
		'text'  => array( 'Определяем возможные недостатки и подбираем программу восполнения.', 'We identify possible deficiencies and select a programme to replenish them.' ),
	),
	array(
		'title' => array( 'Снижение иммунитета', 'Weakened immunity' ),
		'text'  => array( 'Поддерживаем организм в периоды повышенных нагрузок и сезонных изменений.', 'We support your body through periods of high demand and seasonal change.' ),
	),
);

$procedures = array(
	array(
		'pairs' => array(
			'tab'   => array( 'Консультация врача', 'Doctor consultation' ),
			'title' => array( 'Профессиональная оценка здоровья и индивидуальный подход', 'A professional health assessment and a personal approach' ),
			'text'  => array(
				'Консультация врача помогает оценить состояние организма, разобраться с жалобами и определить необходимые обследования и дальнейшие рекомендации.',
				'A doctor consultation helps assess your overall health, get to the bottom of your concerns and decide on the tests and next steps you need.',
			),
		),
		'image' => array( 'wellness-consultation.jpg' ),
	),
	array(
		'pairs' => array(
			'tab'   => array( 'Лабораторная диагностика', 'Laboratory tests' ),
			'title' => array( 'Точная диагностика для персонального подхода к здоровью', 'Accurate diagnostics for a personal approach to your health' ),
			'text'  => array(
				'Лабораторные исследования помогают оценить ключевые показатели организма, выявить возможные отклонения и подобрать индивидуальные рекомендации для поддержания здоровья.',
				'Laboratory tests help assess your body’s key markers, identify possible abnormalities and shape personal recommendations for staying healthy.',
			),
		),
		'image' => array( 'outcall-tests.jpg', 'Центрифуга с пробирками' ),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Хиджама', 'Hijama' ),
			'title' => array( 'Восстановление баланса и улучшение общего самочувствия', 'Restoring balance and improving overall well-being' ),
			'text'  => array(
				'Традиционная процедура с использованием вакуумных банок, направленная на локальное воздействие на ткани, расслабление и уменьшение мышечного напряжения.',
				'A traditional treatment using vacuum cups that works locally on the tissues, promoting relaxation and easing muscle tension.',
			),
		),
		'image'  => array( 'wellness-hijama.jpg', 'Хиджама: вакуумные банки на спине' ),
		'prices' => array(
			skinlove_seed_price( 'Спина', '700 AED', 'Back' ),
			skinlove_seed_price( 'Лицо', '900 AED', 'Face' ),
			skinlove_seed_price( 'Спина, лицо, живот', '900 AED', 'Back, face, abdomen' ),
		),
	),
	array(
		'pairs'      => array(
			'tab'   => array( 'IV Therapy', 'IV Therapy' ),
			'title' => array( 'Индивидуальные программы витаминной поддержки и восстановления организма', 'Personalised programmes for vitamin support and recovery' ),
			'text'  => array(
				'Инфузионная терапия с индивидуальным подбором составов для восполнения дефицитов, поддержки организма и восстановления после стресса и нагрузок.',
				'Infusion therapy with individually selected formulas to replenish deficiencies, support your body and help you recover from stress and exertion.',
			),
		),
		'image'      => array( 'svc-1-iv.jpg' ),
		'prices'     => array(
			skinlove_seed_price( 'Витаминные капельницы', 'от 650 AED', 'Vitamin IV drips' ),
			skinlove_seed_price( 'Восстановительные капельницы', 'от 650 AED', 'Recovery IV drips' ),
			skinlove_seed_price( 'Beauty и Anti-age капельницы', 'от 650 AED', 'Beauty and anti-ageing IV drips' ),
			skinlove_seed_price( 'Антистресс и энергия', 'от 650 AED', 'Anti-stress and energy' ),
			skinlove_seed_price( 'Специализированные капельницы', 'от 650 AED', 'Specialised IV drips' ),
			skinlove_seed_price( 'Курсы капельниц', 'от 2900 AED', 'IV drip courses' ),
		),
		'button'     => array( 'Подробнее об услуге', 'Learn more' ),
		'button_url' => '/iv-therapy/',
	),
);

$steps = array(
	array(
		'title' => array( 'Консультация', 'Consultation' ),
		'text'  => array(
			'Врач подробно изучает ваше самочувствие, образ жизни, жалобы и цели.',
			'The doctor takes a detailed look at your well-being, lifestyle, concerns and goals.',
		),
		'image' => array( 'outcall-step-1.jpg' ),
	),
	array(
		'title' => array( 'Диагностика', 'Diagnostics' ),
		'text'  => array(
			'При необходимости назначаются анализы и дополнительные исследования для оценки состояния организма.',
			'If needed, tests and further examinations are ordered to assess your health.',
		),
		'image' => array( 'outcall-tests.jpg' ),
	),
	array(
		'title' => array( 'Персональный план', 'A personal plan' ),
		'text'  => array(
			'На основе результатов врач составляет индивидуальную программу восстановления и поддержки.',
			'Based on the results, the doctor draws up a personal programme of recovery and support.',
		),
		'image' => array( 'wellness-consultation.jpg' ),
	),
	array(
		'title' => array( 'Сопровождение', 'Ongoing support' ),
		'text'  => array(
			'Мы отслеживаем динамику, корректируем программу при необходимости и сопровождаем вас на каждом этапе.',
			'We track your progress, adjust the programme when needed and support you at every stage.',
		),
		'image' => array( 'iv-step-4.jpg' ),
	),
);

$faq = array(
	array(
		'question' => array( 'Нужно ли сдавать анализы перед началом программы?', 'Do I need tests before starting a programme?' ),
		'answer'   => array(
			'Не всегда. Врач определит необходимость обследований после консультации.',
			'Not always. The doctor will decide whether any tests are needed after the consultation.',
		),
	),
	array(
		'question' => array( 'Можно ли пройти только диагностику?', 'Can I have diagnostics only?' ),
		'answer'   => array(
			'Да. Вы можете сдать анализы или пройти check-up без дальнейшей программы, а врач разберёт результаты и даст рекомендации.',
			'Yes. You can take tests or have a check-up without a follow-on programme, and the doctor will go through the results and give recommendations.',
		),
	),
	array(
		'question' => array( 'Сколько времени занимает программа восстановления?', 'How long does a recovery programme take?' ),
		'answer'   => array(
			'Зависит от задач и состояния организма: от нескольких недель до нескольких месяцев. Сроки врач обозначит после консультации и диагностики.',
			'It depends on your goals and health: from a few weeks to several months. The doctor will outline the timeframe after the consultation and diagnostics.',
		),
	),
	array(
		'question' => array( 'Что входит в Check-up?', 'What does a check-up include?' ),
		'answer'   => array(
			'Консультация врача, набор лабораторных исследований под ваши задачи и разбор результатов с рекомендациями. Состав программы подбирается индивидуально.',
			'A doctor consultation, a set of laboratory tests tailored to your goals and a review of the results with recommendations. The programme is put together individually.',
		),
	),
	array(
		'question' => array( 'Подходит ли Wellness-программа, если ничего не беспокоит?', 'Is a wellness programme worthwhile if nothing is bothering me?' ),
		'answer'   => array(
			'Да. Профилактическая программа помогает вовремя заметить изменения, восполнить дефициты и сохранить хорошее самочувствие надолго.',
			'Yes. A preventive programme helps you spot changes early, replenish deficiencies and stay well for longer.',
		),
	),
);

skinlove_seed_sections(
	$wellness,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Wellness\n*и диагностика*", "Wellness\n*and diagnostics*" ),
				'lead'   => array( 'Забота о здоровье начинается с понимания своего организма', 'Caring for your health starts with understanding your body' ),
				'text'   => array(
					'Wellness-программы и современная диагностика помогают выявить причины изменений самочувствия, восполнить дефициты и подобрать персональный план восстановления. Мы рассматриваем организм комплексно, чтобы вы чувствовали себя лучше не только сегодня, но и в долгосрочной перспективе.',
					'Wellness programmes and modern diagnostics help uncover why you feel the way you do, replenish deficiencies and build a personal recovery plan. We look at the body as a whole, so you feel better not just today but in the long term.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type' => 'service-hero',
				'image' => skinlove_seed_image( 'wellness-hero.jpg' ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Задачи', 'Your goals' ),
				'title' => array( "Когда организму\n*нужна поддержка*", "When your body\n*needs support*" ),
			),
			array(
				'_type' => 'tasks',
				'items' => array_map( 'skinlove_seed_entry', $tasks ),
			)
		),
		array(
			'_type'        => 'procedures',
			'prices_limit' => 11,
			'items'        => skinlove_seed_procedures( $procedures, array( 'Записаться на процедуру', 'Book a treatment' ) ),
		),
		...skinlove_seed_service_sections(
			array(
				'promos'           => array( 'svc-1-iv.jpg', 'outcall-vitamin.jpg', 'iv-form.jpg', 'outcall-tests.jpg' ),
				'steps_title'      => array( "Комфортный уход\n*с индивидуальным подходом*", "Comfortable care\n*with a personal approach*" ),
				'steps'            => $steps,
				'request'          => array(
					'title'  => array( "Начните с понимания\n*своего организма*", "Start by understanding\n*your body*" ),
					'text'   => array(
						'Оставьте контакты — администратор SkinLove свяжется с вами, уточнит ваш запрос и поможет записаться на консультацию, подобрать необходимую диагностику и удобное время для визита.',
						'Leave your details and a SkinLove administrator will get in touch, clarify your request and help you book a consultation, choose the right tests and find a convenient time for your visit.',
					),
					'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
					'image'  => array( 'wellness-form.jpg', 'Пробирка с анализом крови в руке специалиста' ),
				),
				'advantages'       => 'wellness',
				'advantages_count' => 5,
				'faq'              => $faq,
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Начните с главного —\n*понимания своего организма*", "Start with what matters most —\n*understanding your body*" ),
				'text'   => array(
					'Запишитесь на консультацию, чтобы пройти диагностику, получить персональные рекомендации и подобрать программу, которая поможет чувствовать себя лучше каждый день.',
					'Book a consultation to have diagnostics, get personal recommendations and find a programme that helps you feel better every day.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover', 'stacked', 'deep-shade' ),
				'image'   => skinlove_seed_image( 'wellness-hero.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «Wellness» заполнена' );
