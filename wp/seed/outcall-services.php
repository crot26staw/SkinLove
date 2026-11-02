<?php
/**
 * Сид выездных услуг: категории и услуги из каталога outcall.html.
 * Категории ищутся по slug, услуги — по русскому названию.
 *
 * Подпись кнопки у услуги записывается, только если она отличается от
 * подписи по умолчанию в секции каталога («Узнать программу», сид outcall.php).
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/outcall-services.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

/* Категории по порядку вкладок: slug => название, подпись под фильтром. */
$categories = array(
	'iv'       => array(
		'name'    => array( 'IV Therapy', 'IV Therapy' ),
		'caption' => array(
			'Инфузионные программы с индивидуальным подбором по медицинским показаниям.',
			'Infusion programmes individually selected according to medical indications.',
		),
	),
	'doctor'   => array(
		'name'    => array( 'Врач и анализы', 'Doctor & tests' ),
		'caption' => array(
			'Медицинские вопросы, которые можно обсудить со специалистом без лишней поездки в клинику.',
			'Medical questions you can discuss with a specialist without an extra trip to the clinic.',
		),
	),
	'wellness' => array(
		'name'    => array( 'Wellness', 'Wellness' ),
		'caption' => array(
			'Персональные программы, которые учитывают ваш образ жизни и индивидуальные потребности.',
			'Personal programmes that take your lifestyle and individual needs into account.',
		),
	),
	'beauty'   => array(
		'name'    => array( 'Косметология', 'Aesthetics' ),
		'caption' => array(
			'Уточните возможность проведения эстетических процедур вне клиники после консультации специалиста.',
			'Check whether an aesthetic treatment can be performed outside the clinic after a consultation with a specialist.',
		),
	),
);

$consult  = array( 'Получить консультацию', 'Get a consultation' );
$check    = array( 'Уточнить доступность', 'Check availability' );
$pick     = array( 'Подобрать программу', 'Find my programme' );
$services = array(
	array(
		'category' => 'iv',
		'title'    => array( 'После мероприятий и вечеринок', 'After events and parties' ),
		'text'     => array(
			'Головная боль, слабость или плохое самочувствие после насыщенного вечера? Врач оценит ваше состояние и поможет определить необходимую помощь.',
			'Headache, fatigue or feeling unwell after a busy evening? A doctor will assess how you feel and help decide what care you need.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница Майерса', 'Myers’ cocktail' ),
		'text'     => array(
			'Витаминно-минеральный комплекс для восполнения индивидуальных потребностей организма по рекомендации специалиста.',
			'A vitamin and mineral complex to meet your body’s individual needs, as recommended by a specialist.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Антистресс»', 'Anti-stress drip' ),
		'text'     => array(
			'Напряжённый график, постоянная усталость и стресс? Обсудите со специалистом программу поддержки с учётом вашего состояния.',
			'A demanding schedule, constant fatigue and stress? Discuss a support programme tailored to your condition with a specialist.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Мультивитамин»', 'Multivitamin drip' ),
		'text'     => array(
			'Комплекс витаминов и микроэлементов, который подбирается с учётом состояния организма и выявленных потребностей.',
			'A complex of vitamins and trace elements selected according to your body’s condition and identified needs.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Золушка»', 'Cinderella drip' ),
		'text'     => array(
			'Антиоксидантная Beauty-программа с индивидуальным подбором состава после оценки состояния здоровья.',
			'An antioxidant beauty programme with an individually selected formula, following a health assessment.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница с NAD+', 'NAD+ drip' ),
		'text'     => array(
			'Инфузионная программа с NAD+ для индивидуальной терапии. Возможность применения и ожидаемый эффект обсуждаются с врачом.',
			'An NAD+ infusion programme for individual therapy. Whether it suits you and what to expect are discussed with a doctor.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Витамин D»', 'Vitamin D drip' ),
		'text'     => array(
			'При подтверждённом дефиците витамина D врач подберёт подходящий способ его восполнения и оценит необходимость инфузии.',
			'If a vitamin D deficiency is confirmed, a doctor will choose a suitable way to replenish it and assess whether an infusion is needed.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Железо»', 'Iron drip' ),
		'text'     => array(
			'Внутривенное восполнение железа при подтверждённом дефиците и наличии медицинских показаний.',
			'Intravenous iron replenishment for a confirmed deficiency where there are medical indications.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница Laennec', 'Laennec drip' ),
		'text'     => array(
			'Инфузионная программа на основе препарата Laennec. Показания, ограничения и возможность применения определяются врачом.',
			'An infusion programme based on Laennec. Indications, contraindications and suitability are determined by a doctor.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Для концентрации внимания', 'For focus and concentration' ),
		'text'     => array(
			'Высокие интеллектуальные нагрузки и сложности с концентрацией? Специалист поможет разобраться в причинах и подобрать подходящее решение.',
			'A heavy mental workload and difficulty concentrating? A specialist will help identify the causes and find a suitable solution.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Простуда и недомогания', 'Colds and feeling unwell' ),
		'text'     => array(
			'Чувствуете слабость и признаки простуды? Получите консультацию врача, чтобы определить необходимое лечение и восстановительный режим.',
			'Feeling weak or noticing signs of a cold? Consult a doctor to determine the treatment and recovery routine you need.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Супер Слим»', 'Super Slim drip' ),
		'text'     => array(
			'Программа с альфа-липоевой кислотой, которую врач может рассмотреть в рамках индивидуального плана контроля веса.',
			'A programme with alpha-lipoic acid that a doctor may consider as part of an individual weight management plan.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Помощь при интоксикации', 'Help with intoxication' ),
		'text'     => array(
			'При подозрении на отравление важно своевременно оценить состояние. Врач определит необходимую помощь и подходящий формат лечения.',
			'If poisoning is suspected, it is important to assess your condition promptly. A doctor will determine the care you need and the right form of treatment.',
		),
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Очищающая»', 'Cleansing drip' ),
		'text'     => array(
			'Индивидуальная инфузионная программа из каталога SkinLove. Состав и целесообразность процедуры определяет специалист.',
			'An individual infusion programme from the SkinLove catalogue. The formula and whether the treatment is appropriate are determined by a specialist.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'iv',
		'title'    => array( 'Капельница «Здоровая печень»', 'Healthy Liver drip' ),
		'text'     => array(
			'Программа с препаратом Гептрал, возможность назначения которой определяется врачом с учётом состояния печени и результатов обследований.',
			'A programme with Heptral, prescribed at a doctor’s discretion based on the condition of your liver and your test results.',
		),
	),
	array(
		'category' => 'doctor',
		'title'    => array( 'Консультация врача', 'Doctor’s consultation' ),
		'text'     => array(
			'Обсудите симптомы и получите профессиональные рекомендации по дальнейшим действиям.',
			'Discuss your symptoms and receive professional advice on the next steps.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'doctor',
		'title'    => array( 'Забор анализов', 'Sample collection' ),
		'text'     => array(
			'Согласуйте возможность сдачи необходимых анализов по удобному адресу.',
			'Arrange for the tests you need to be taken at an address that suits you.',
		),
		'button'   => $check,
	),
	array(
		'category' => 'doctor',
		'title'    => array( 'Разбор результатов анализов', 'Test results review' ),
		'text'     => array(
			'Получите объяснение результатов и рекомендации по дальнейшему обследованию.',
			'Have your results explained and receive recommendations for further tests.',
		),
		'button'   => $consult,
	),
	array(
		'category' => 'doctor',
		'title'    => array( 'Помощь при алкогольной интоксикации', 'Help with alcohol intoxication' ),
		'text'     => array(
			'Врачебная оценка состояния и определение необходимой помощи. При опасных симптомах нужна экстренная медицинская помощь.',
			'A doctor’s assessment of your condition and the care you need. Dangerous symptoms require emergency medical help.',
		),
		'button'   => array( 'Связаться с врачом', 'Contact a doctor' ),
	),
	array(
		'category' => 'wellness',
		'title'    => array( 'После перелётов', 'After flights' ),
		'text'     => array(
			'Обсудите со специалистом самочувствие после дороги и смены часовых поясов.',
			'Talk to a specialist about how you feel after travel and a change of time zones.',
		),
		'button'   => $pick,
	),
	array(
		'category' => 'wellness',
		'title'    => array( 'При высоких нагрузках', 'Under heavy workloads' ),
		'text'     => array(
			'Подберите программу поддержки с учётом интенсивного рабочего графика и состояния здоровья.',
			'Find a support programme that takes your demanding schedule and health into account.',
		),
		'button'   => $pick,
	),
	array(
		'category' => 'wellness',
		'title'    => array( 'Спорт и восстановление', 'Sport and recovery' ),
		'text'     => array(
			'Индивидуальный подход к восстановлению после физических нагрузок.',
			'An individual approach to recovery after physical exercise.',
		),
		'button'   => $pick,
	),
	array(
		'category' => 'wellness',
		'title'    => array( 'Персональная wellness-программа', 'Personal wellness programme' ),
		'text'     => array(
			'Комплексный план с учётом ваших целей, результатов обследований и рекомендаций специалистов.',
			'A comprehensive plan based on your goals, test results and specialists’ recommendations.',
		),
		'button'   => array( 'Обсудить программу', 'Discuss a programme' ),
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'Ботулинотерапия', 'Anti-wrinkle injections' ),
		'text'     => array(
			'Коррекция мимических морщин с сохранением естественной выразительности лица.',
			'Softening expression lines while keeping your face naturally expressive.',
		),
		'button'   => $check,
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'Биоревитализация', 'Biorevitalisation' ),
		'text'     => array(
			'Инъекционные процедуры для улучшения увлажнённости и качества кожи.',
			'Injectable treatments to improve skin hydration and quality.',
		),
		'button'   => $check,
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'Мезотерапия', 'Mesotherapy' ),
		'text'     => array(
			'Индивидуально подобранные процедуры для ухода за кожей лица или волосистой частью головы.',
			'Individually selected treatments to care for the skin of your face or scalp.',
		),
		'button'   => $check,
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'PRP-терапия', 'PRP therapy' ),
		'text'     => array(
			'Процедура с использованием собственной плазмы пациента по показаниям специалиста.',
			'A treatment using the patient’s own plasma, as indicated by a specialist.',
		),
		'button'   => $check,
	),
);

$order = 0;

foreach ( $categories as $slug => $category ) {
	$term = get_term_by( 'slug', $slug, 'sl_outcall_category' );

	if ( $term ) {
		wp_update_term( $term->term_id, 'sl_outcall_category', array( 'name' => $category['name'][0] ) );
		$term_id = $term->term_id;
	} else {
		$created = wp_insert_term( $category['name'][0], 'sl_outcall_category', array( 'slug' => $slug ) );

		if ( is_wp_error( $created ) ) {
			WP_CLI::error( "{$slug}: " . $created->get_error_message() );
		}

		$term_id = $created['term_id'];
	}

	carbon_set_term_meta( $term_id, 'sl_name_en', $category['name'][1] );
	carbon_set_term_meta( $term_id, 'sl_caption_ru', $category['caption'][0] );
	carbon_set_term_meta( $term_id, 'sl_caption_en', $category['caption'][1] );
	carbon_set_term_meta( $term_id, 'sl_order', ( ++$order ) * 10 );

	WP_CLI::log( ( $term ? 'Обновлена' : 'Создана' ) . " категория {$slug} (#{$term_id})" );
}

foreach ( $services as $index => $service ) {
	$id = skinlove_seed_post( 'sl_outcall_service', $service['title'][0], array( 'menu_order' => ( $index + 1 ) * 10 ) );

	skinlove_seed_pair( $id, 'title', ...$service['title'] );
	skinlove_seed_pair( $id, 'text', ...$service['text'] );
	skinlove_seed_pair( $id, 'button', ...( $service['button'] ?? array( '', '' ) ) );
	wp_set_object_terms( $id, $service['category'], 'sl_outcall_category' );
}

WP_CLI::success( 'Услуг: ' . count( $services ) . ', категорий: ' . count( $categories ) );
