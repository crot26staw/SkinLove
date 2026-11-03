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
			'title'             => array( 'Не знаете, какая процедура вам подойдёт?', 'Not sure which treatment is right for you?' ),
			'text'              => array(
				'Расскажите о своём самочувствии — мы поможем определить подходящее направление и уточним возможность выезда специалиста. Всего несколько вопросов, чтобы начать заботиться о себе.',
				'Tell us how you feel — we will help you find the right direction and check whether a specialist can visit you. Just a few questions to start taking care of yourself.',
			),
			'label'             => array( 'SkinLove / персональный подбор', 'SkinLove / tailored selection' ),
			'concern_title'     => array( 'Что вас беспокоит?', 'What would you like help with?' ),
			'concern_text'      => array(
				'Выберите один или несколько вариантов. Необязательно знать название процедуры — мы поможем разобраться в вашем запросе.',
				'Choose one or more options. You don’t need to know the name of the treatment — we will help you work out what you need.',
			),
			'other_label'       => array( 'Другая ситуация', 'Something else' ),
			'other_placeholder' => array( 'Расскажите своими словами, что вас беспокоит', 'Tell us in your own words what is bothering you' ),
			'place_title'       => array( 'Где вам удобнее получить услугу?', 'Where would you prefer to receive the service?' ),
			'place_text'        => array(
				'Выберите подходящее место — мы уточним возможность выезда по вашему адресу.',
				'Choose a suitable place — we will check whether we can visit your address.',
			),
			'date_title'        => array( 'Когда вам было бы удобно?', 'When would suit you?' ),
			'date_text'         => array(
				'Укажите желаемое время, а мы уточним доступность специалиста.',
				'Choose a preferred time and we will check the specialist’s availability.',
			),
			'address_title'     => array( 'Укажите адрес выезда', 'Enter the visit address' ),
			'address_text'      => array(
				'Напишите адрес, по которому вам будет удобно принять специалиста SkinLove. Мы уточним возможность выезда по выбранной локации.',
				'Write the address where you would like to welcome a SkinLove specialist. We will check whether a visit to this location is possible.',
			),
			'contacts_title'    => array( 'Осталось только оставить контакты', 'Just leave your contact details' ),
			'contacts_text'     => array(
				'Администратор SkinLove свяжется с вами, уточнит детали и предложит подходящую услугу.',
				'A SkinLove administrator will contact you, clarify the details and suggest a suitable service.',
			),
		),
		array(
			'_type'           => 'quiz',
			'times'           => '10:00, 12:00, 13:00, 14:00, 15:00, 16:00, 17:00, 18:00, 19:00',
			'concern_options' => array_map(
				fn( $label ) => skinlove_seed_entry( array( 'label' => $label ) ),
				array(
					array( 'Чувствую усталость и недостаток энергии', 'I feel tired and low on energy' ),
					array( 'Испытываю стресс и переутомление', 'I am stressed and overworked' ),
					array( 'Плохо себя чувствую после вечеринки', 'I feel unwell after a party' ),
					array( 'Хочу восстановиться после перелёта', 'I want to recover after a flight' ),
					array( 'Беспокоит самочувствие или симптомы', 'I am concerned about my wellbeing or symptoms' ),
					array( 'Хочу проверить здоровье и сдать анализы', 'I want a health check and lab tests' ),
					array( 'Хочу улучшить состояние кожи и внешний вид', 'I want to improve my skin and appearance' ),
				)
			),
			'place_options'   => array_map(
				fn( $label ) => skinlove_seed_entry( array( 'label' => $label ) ),
				array(
					array( 'Дома', 'At home' ),
					array( 'В отеле', 'At a hotel' ),
					array( 'В офисе', 'At the office' ),
					array( 'На яхте', 'On a yacht' ),
					array( 'В другом месте', 'Somewhere else' ),
					array( 'Пока не определились', 'Not decided yet' ),
				)
			),
		)
	),
	skinlove_seed_entry(
		array(
			'title' => array( 'Профессиональная забота в каждой детали', 'Professional care in every detail' ),
			'text'  => array(
				'Выездной сервис SkinLove объединяет медицинскую экспертизу, индивидуальный подход и внимание к вашему комфорту. Мы заботимся не только о качестве процедуры, но и о том, как вы себя чувствуете на каждом этапе.',
				'SkinLove’s at-home service brings together medical expertise, an individual approach and attention to your comfort. We care not only about the quality of the treatment, but also about how you feel at every stage.',
			),
		),
		array(
			'_type' => 'care',
			'image' => skinlove_seed_image( 'outcall-care.jpg', 'Мужчина отдыхает на диване во время капельницы, за окном — Дубай Марина' ),
			'items' => array_map(
				fn( $item ) => skinlove_seed_entry( $item ),
				array(
					array(
						'title' => array( 'Индивидуальный подбор', 'Tailored to you' ),
						'text'  => array(
							'Никаких универсальных решений. Учитываем ваши пожелания, состояние здоровья и медицинские показания, чтобы предложить подходящую процедуру.',
							'No one-size-fits-all solutions. We take into account your wishes, health and medical indications to suggest the right treatment.',
						),
					),
					array(
						'title' => array( 'Всё необходимое привезём с собой', 'We bring everything with us' ),
						'text'  => array(
							'Специалист приезжает с необходимыми препаратами, оборудованием и расходными материалами для согласованной процедуры.',
							'The specialist arrives with the products, equipment and supplies needed for the agreed treatment.',
						),
					),
					array(
						'title' => array( 'Опытные специалисты', 'Experienced specialists' ),
						'text'  => array(
							'Ваше здоровье — в руках квалифицированных медицинских специалистов, которые уделяют внимание безопасности и каждой детали процедуры.',
							'Your health is in the hands of qualified medical specialists who pay attention to safety and every detail of the treatment.',
						),
					),
					array(
						'title' => array( 'Забота после процедуры', 'Aftercare' ),
						'text'  => array(
							'Не оставляем вас с вопросами. Объясняем особенности восстановления, даём рекомендации и остаёмся на связи при необходимости.',
							'We won’t leave you with unanswered questions. We explain what to expect during recovery, give recommendations and stay in touch if needed.',
						),
					),
				)
			),
		)
	),
	skinlove_seed_entry(
		array(
			'title'  => array( 'Вы выбираете услугу. Мы организуем остальное.', 'You choose the service. We arrange the rest.' ),
			'text'   => array(
				'Забудьте о поездках, ожидании и необходимости подстраивать свой день под запись в клинику. Мы позаботимся об организации визита, чтобы вы могли уделить время себе там, где вам действительно удобно.',
				'Forget about travel, waiting and fitting your day around a clinic appointment. We will take care of organising the visit so you can make time for yourself wherever suits you best.',
			),
			'button' => array( 'Организовать выезд', 'Arrange a visit' ),
		),
		array(
			'_type' => 'process',
			'items' => array(
				skinlove_seed_entry(
					array(
						'title'  => array( 'Расскажите, что вам нужно', 'Tell us what you need' ),
						'text'   => array(
							'Выберите процедуру или просто опишите свой запрос. Необязательно разбираться в медицинских программах — мы поможем определить, с чего начать.',
							'Choose a treatment or simply describe your request. You don’t need to know your way around medical programmes — we will help you decide where to start.',
						),
						'button' => array( 'Рассказать о своей ситуации', 'Tell us about your situation' ),
					),
					array( 'icon' => 'pulse' )
				),
				skinlove_seed_entry(
					array(
						'title'  => array( 'Подберём решение под вас', 'We find the right solution for you' ),
						'text'   => array(
							'Специалист уточнит ваши потребности и состояние здоровья, определит возможность проведения процедуры и согласует удобное время и место выезда.',
							'A specialist will clarify your needs and health, confirm whether the treatment is possible and agree on a convenient time and place for the visit.',
						),
						'button' => array( '', '' ),
					),
					array( 'icon' => 'list' )
				),
				skinlove_seed_entry(
					array(
						'title'  => array( 'Приедем со всем необходимым', 'We arrive with everything needed' ),
						'text'   => array(
							'Вам не придётся заботиться об оборудовании и подготовке материалов. Специалист SkinLove привезёт всё необходимое для согласованной процедуры.',
							'You won’t need to worry about equipment or preparing supplies. A SkinLove specialist will bring everything needed for the agreed treatment.',
						),
						'button' => array( '', '' ),
					),
					array( 'icon' => 'person' )
				),
				skinlove_seed_entry(
					array(
						'title'  => array( 'Останемся на связи', 'We stay in touch' ),
						'text'   => array(
							'Забота не заканчивается после процедуры. Вы получите персональные рекомендации и при необходимости сможете обсудить дальнейшее сопровождение.',
							'Care doesn’t end with the treatment. You will receive personal recommendations and, if needed, can discuss ongoing support.',
						),
						'button' => array( '', '' ),
					),
					array( 'icon' => 'care' )
				),
			),
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
		array( 'title' => array( 'Сервис, к которому хочется возвращаться', 'A service you’ll want to come back to' ) ),
		array( '_type' => 'feedback' )
	),
	skinlove_seed_entry(
		array(
			'label' => array( '', '' ),
			'title' => array( 'Частые вопросы', 'Frequently asked questions' ),
		),
		array(
			'_type'   => 'faq',
			'variant' => 'outcall',
			'items'   => array_map(
				'skinlove_seed_entry',
				array(
					array(
						'question' => array( 'В какие районы Дубая возможен выезд?', 'Which areas of Dubai do you cover?' ),
						'answer'   => array(
							'Мы стараемся организовать визит там, где вам удобно: дома, в отеле, офисе или другой локации. Сообщите ваш адрес, и администратор уточнит возможность выезда и доступное время.',
							'We try to arrange the visit wherever suits you: at home, in a hotel, at the office or another location. Share your address and an administrator will confirm whether a visit is possible and what times are available.',
						),
						'button'   => array( 'Узнать доступность выезда на мой адрес', 'Check availability at my address' ),
					),
					array(
						'question' => array( 'Как быстро может приехать специалист?', 'How quickly can a specialist arrive?' ),
						'answer'   => array(
							'Мы понимаем, что помощь иногда нужна в ближайшее время. Оставьте заявку, и администратор проверит доступность специалистов и предложит ближайшее возможное время визита.',
							'We understand that sometimes help is needed soon. Send a request and an administrator will check specialists’ availability and offer the earliest possible visit time.',
						),
						'button'   => array( 'Оставить заявку', 'Send request' ),
					),
					array(
						'question' => array( 'Какие процедуры можно заказать с выездом?', 'Which treatments are available as a home visit?' ),
						'answer'   => array(
							'Доступность зависит от выбранной услуги и медицинских требований. Расскажите, какая процедура вас интересует, — мы уточним возможность её проведения вне клиники и предложим подходящий формат.',
							'Availability depends on the chosen service and medical requirements. Tell us which treatment you are interested in — we will check whether it can be performed outside the clinic and suggest a suitable format.',
						),
						'button'   => array( '', '' ),
					),
					array(
						'question' => array( 'Можно ли заказать капельницу после вечеринки?', 'Can I book an IV drip after a party?' ),
						'answer'   => array(
							'Если после употребления алкоголя вас беспокоят головная боль, слабость или тошнота, обратитесь за консультацией. Врач оценит симптомы и определит необходимую помощь. Инфузионная терапия проводится только при наличии показаний.',
							'If you have a headache, weakness or nausea after drinking alcohol, ask for a consultation. A doctor will assess your symptoms and decide what help you need. IV therapy is given only when medically indicated.',
						),
						'button'   => array( '', '' ),
					),
					array(
						'question' => array( 'Можно ли заказать выезд сразу для нескольких человек?', 'Can I book a visit for several people at once?' ),
						'answer'   => array(
							'Укажите количество человек и интересующие услуги при оформлении заявки. Мы уточним возможность организации совместного визита с учётом индивидуальных потребностей каждого.',
							'Include the number of people and the services you are interested in when sending your request. We will check whether a joint visit can be arranged, taking everyone’s individual needs into account.',
						),
						'button'   => array( '', '' ),
					),
					array(
						'question' => array( 'Сколько стоит выезд специалиста?', 'How much does a home visit cost?' ),
						'answer'   => array(
							'Стоимость зависит от выбранной процедуры и условий выезда. Администратор заранее уточнит детали и сообщит полную стоимость до подтверждения записи.',
							'The cost depends on the chosen treatment and the visit conditions. An administrator will clarify the details in advance and tell you the full price before confirming the booking.',
						),
						'button'   => array( '', '' ),
					),
				)
			),
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
