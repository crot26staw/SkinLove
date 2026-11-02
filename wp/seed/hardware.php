<?php
/**
 * Сид страницы «Аппаратная косметология»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/hardware.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$hardware = skinlove_seed_page( 'hardware', 'Аппаратная косметология' );

skinlove_seed_pair(
	$hardware,
	'seo_title',
	'Аппаратная косметология — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Device-based aesthetics — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$hardware,
	'seo_description',
	'Аппаратная косметология в клинике SkinLove: INDIBA, фотоомоложение BBL, лазерная эпиляция, эпиляция Genesis, удаление татуировок и татуажа в Дубае.',
	'Device-based aesthetics at SkinLove: INDIBA, BBL photorejuvenation, laser hair removal, Genesis hair removal, and tattoo and permanent make-up removal in Dubai.'
);

$tasks = array(
	array(
		'title' => array( 'Расширенные поры', 'Enlarged pores' ),
		'text'  => array( 'Работаем над текстурой кожи и визуальным уменьшением пор.', 'We work on skin texture and make pores look smaller.' ),
	),
	array(
		'title' => array( 'Постакне и неровный рельеф', 'Acne marks and uneven texture' ),
		'text'  => array( 'Помогаем уменьшить выраженность следов постакне и сделать поверхность кожи более гладкой.', 'We help fade acne marks and make the surface of your skin smoother.' ),
	),
	array(
		'title' => array( 'Пигментация и неровный тон', 'Pigmentation and uneven tone' ),
		'text'  => array( 'Помогаем сделать тон кожи более ровным и уменьшить выраженность пигментных пятен.', 'We help even out your skin tone and reduce the appearance of dark spots.' ),
	),
	array(
		'title' => array( 'Сосудистые проявления', 'Visible blood vessels' ),
		'text'  => array( 'Работаем с видимыми сосудами, покраснениями и сосудистыми звёздочками.', 'We treat visible vessels, redness and spider veins.' ),
	),
	array(
		'title' => array( 'Возрастные изменения', 'Signs of ageing' ),
		'text'  => array( 'Поддерживаем плотность, упругость и естественное обновление кожи.', 'We support your skin’s density, firmness and natural renewal.' ),
	),
);

$sessions = array(
	'1 процедура'  => '1 session',
	'4 процедуры'  => '4 sessions',
	'5 процедур'   => '5 sessions',
	'10 процедур'  => '10 sessions',
);
$session  = fn( $ru, $price ) => skinlove_seed_price( $ru, $price, $sessions[ $ru ] );

$procedures = array(
	array(
		'pairs'  => array(
			'tab'   => array( 'INDIBA для лица и тела', 'INDIBA for face and body' ),
			'title' => array( 'Восстановление упругости, лифтинг и улучшение качества кожи', 'Restored firmness, lifting and better skin quality' ),
			'text'  => array(
				'Аппаратная процедура INDIBA помогает стимулировать естественные процессы восстановления тканей, улучшить микроциркуляцию, повысить упругость кожи и уменьшить отёчность. Подходит для комплексного ухода за лицом и телом, а также коррекции контуров.',
				'INDIBA helps stimulate natural tissue repair, improve microcirculation, increase skin firmness and reduce puffiness. It is suitable for comprehensive face and body care as well as contouring.',
			),
		),
		'image'  => array( 'hardware-indiba.jpg', 'Косметолог проводит процедуру INDIBA на лице пациентки' ),
		'prices' => array(
			skinlove_seed_price( 'INDIBA для тела 1 зона / 3 зоны', '600 / 1000 AED', 'INDIBA body, 1 area / 3 areas' ),
			skinlove_seed_price( 'INDIBA для лица 1 процедура', '450 AED', 'INDIBA face, 1 session' ),
			skinlove_seed_price_group( 'Комплекс INDIBA + RSL', 'INDIBA + RSL package' ),
			$session( '1 процедура', '950 AED' ),
			$session( '5 процедур', '4000 AED' ),
			$session( '10 процедур', '6500 AED' ),
			skinlove_seed_price_group( 'INDIBA + RSL + прессотерапия + обёртывание', 'INDIBA + RSL + pressotherapy + body wrap' ),
			$session( '5 процедур', '4500 AED' ),
			$session( '10 процедур', '7500 AED' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'BBL', 'BBL' ),
			'title' => array( 'Фотоомоложение и комплексное улучшение качества кожи', 'Photorejuvenation and an all-round boost in skin quality' ),
			'text'  => array(
				'Аппаратная процедура BBL направлена на коррекцию пигментации, неровного тона и возрастных изменений кожи. Световое воздействие помогает улучшить текстуру кожи, сделать её тон более ровным и вернуть свежий, ухоженный вид.',
				'BBL targets pigmentation, uneven tone and signs of skin ageing. Light energy helps improve skin texture, even out its tone and bring back a fresh, well-cared-for look.',
			),
		),
		'image'  => array( 'hardware-hero.jpg', 'Косметолог проводит процедуру BBL у аппарата VIVACE' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура (3 в 1)', '1700 AED', '1 session (3 in 1)' ),
			$session( '4 процедуры', '4500 AED' ),
		),
	),
	array(
		'pairs'      => array(
			'tab'         => array( 'Лазерная эпиляция', 'Laser hair removal' ),
			'title'       => array( 'Гладкая кожа без нежелательных волос и ежедневного бритья', 'Smooth skin without unwanted hair or daily shaving' ),
			'text'        => array(
				'Лазерная эпиляция помогает надолго уменьшить рост нежелательных волос, сохраняя гладкость и ухоженный вид кожи. Процедура обеспечивает точечное воздействие на волосяные фолликулы и подходит для женщин и мужчин.',
				'Laser hair removal reduces unwanted hair growth for the long term, keeping your skin smooth and well groomed. The treatment precisely targets hair follicles and is suitable for both women and men.',
			),
			'variant'     => array( 'Женская', 'Women' ),
			'variant_alt' => array( 'Мужская', 'Men' ),
		),
		'image'      => array( 'hardware-epilation.jpg', 'Пациентка в халате SkinLove во время лазерной эпиляции' ),
		'prices'     => array(
			skinlove_seed_price( 'Подмышки', '150 AED', 'Underarms' ),
			skinlove_seed_price( 'Руки до локтя', '150 AED', 'Lower arms' ),
			skinlove_seed_price( 'Руки полностью', '200 AED', 'Full arms' ),
			skinlove_seed_price( 'Ноги голени', '200 AED', 'Lower legs' ),
			skinlove_seed_price( 'Ноги полностью', '300 AED', 'Full legs' ),
			skinlove_seed_price( 'Верхняя губа', '50 AED', 'Upper lip' ),
			skinlove_seed_price( 'Лицо полностью', '200 AED', 'Full face' ),
			skinlove_seed_price( 'Глубокое бикини', '250 AED', 'Full bikini' ),
			skinlove_seed_price( 'Ареолы', '50 AED', 'Areolas' ),
			skinlove_seed_price( 'Полоска живота', '50 AED', 'Navel line' ),
			skinlove_seed_price( 'Живот полностью', '100 AED', 'Full abdomen' ),
			skinlove_seed_price( 'Спина', '200 AED', 'Back' ),
			skinlove_seed_price( 'Ягодицы', '150 AED', 'Buttocks' ),
			skinlove_seed_price( 'Бикини + подмышки', '350 AED', 'Bikini + underarms' ),
			skinlove_seed_price( 'Бикини + подмышки + голени + 2 мини-зоны', '550 AED', 'Bikini + underarms + lower legs + 2 small areas' ),
			skinlove_seed_price( 'FULL BODY', '700 AED' ),
		),
		'prices_alt' => array(
			skinlove_seed_price( 'Подмышки', '150 AED', 'Underarms' ),
			skinlove_seed_price( 'Руки до локтя', '250 AED', 'Lower arms' ),
			skinlove_seed_price( 'Руки полностью', '300 AED', 'Full arms' ),
			skinlove_seed_price( 'Ноги голени', '250 AED', 'Lower legs' ),
			skinlove_seed_price( 'Ноги полностью', '350 AED', 'Full legs' ),
			skinlove_seed_price( 'Лицо полностью', '300 AED', 'Full face' ),
			skinlove_seed_price( 'Глубокое бикини', '500 AED', 'Full bikini' ),
			skinlove_seed_price( 'Грудь', '250 AED', 'Chest' ),
			skinlove_seed_price( 'Живот', '150 AED', 'Abdomen' ),
			skinlove_seed_price( 'Спина', '250 AED', 'Back' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Лазерная эпиляция Genesis', 'Genesis laser hair removal' ),
			'title' => array( 'Гладкая кожа и длительное уменьшение роста нежелательных волос', 'Smooth skin and a long-lasting reduction in unwanted hair' ),
			'text'  => array(
				'Лазерная эпиляция Genesis помогает сократить рост нежелательных волос, обеспечивая точное воздействие на волосяные фолликулы. Процедура направлена на достижение длительной гладкости кожи и уменьшение необходимости частого бритья.',
				'Genesis laser hair removal reduces unwanted hair growth by precisely targeting hair follicles. The treatment aims for long-lasting smoothness and less need for frequent shaving.',
			),
		),
		'image'  => array( 'hardware-genesis.jpg', 'Специалист проводит лазерную эпиляцию ног аппаратом Genesis' ),
		'prices' => array(
			skinlove_seed_price( 'Лицо полностью', '250 AED', 'Full face' ),
			skinlove_seed_price( 'Верхняя губа', '120 AED', 'Upper lip' ),
			skinlove_seed_price( 'Подмышки', '150 AED', 'Underarms' ),
			skinlove_seed_price( 'Руки полностью', '220 AED', 'Full arms' ),
			skinlove_seed_price( 'Руки до локтя', '170 AED', 'Lower arms' ),
			skinlove_seed_price( 'Ареолы', '100 AED', 'Areolas' ),
			skinlove_seed_price( 'Живот', '150 AED', 'Abdomen' ),
			skinlove_seed_price( 'Глубокое бикини', '350 AED', 'Full bikini' ),
			skinlove_seed_price( 'Ноги голени', '220 AED', 'Lower legs' ),
			skinlove_seed_price( 'Ягодицы', '150 AED', 'Buttocks' ),
			skinlove_seed_price( 'Спина', '300 AED', 'Back' ),
			skinlove_seed_price( 'Бикини + подмышки', '450 AED', 'Bikini + underarms' ),
			skinlove_seed_price( 'Бикини + подмышки + голени + 2 мини-зоны', '650 AED', 'Bikini + underarms + lower legs + 2 small areas' ),
			skinlove_seed_price( 'FULL BODY (за исключением спины)', '800 AED', 'FULL BODY (excluding back)' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Удаление татуировок и татуажа', 'Tattoo and permanent make-up removal' ),
			'title' => array( 'Деликатное удаление пигмента с сохранением естественной красоты кожи', 'Gentle pigment removal that preserves your skin’s natural beauty' ),
			'text'  => array(
				'Лазерная процедура помогает постепенно осветлить и удалить нежелательные татуировки и перманентный макияж. Точечное воздействие на пигмент позволяет скорректировать или удалить прежний рисунок, минимизируя воздействие на окружающие ткани.',
				'The laser treatment gradually lightens and removes unwanted tattoos and permanent make-up. By precisely targeting the pigment, it can correct or remove an old design with minimal impact on the surrounding tissue.',
			),
		),
		'image'  => array( 'hardware-tattoo.jpg', 'Лазерное удаление татуировки на голени' ),
		'prices' => array(
			skinlove_seed_price( 'Татуаж бровей', '700 AED', 'Eyebrow permanent make-up' ),
			skinlove_seed_price( 'Татуаж губ', '700 AED', 'Lip permanent make-up' ),
			skinlove_seed_price( 'Татуаж стрелки / межреснички', '1200 AED', 'Eyeliner / lash line permanent make-up' ),
			skinlove_seed_price( 'Татуировка', 'от 300 AED', 'Tattoo' ),
		),
	),
);

$steps = array(
	array(
		'title' => array( 'Консультация', 'Consultation' ),
		'text'  => array(
			'Специалист оценивает состояние кожи, уточняет ваш запрос и исключает возможные противопоказания.',
			'The specialist assesses your skin, clarifies your goals and rules out any contraindications.',
		),
		'image' => array( 'outcall-step-1.jpg' ),
	),
	array(
		'title' => array( 'Подбор параметров', 'Choosing the settings' ),
		'text'  => array(
			'Тип воздействия и настройки оборудования подбираются с учётом зоны, фототипа кожи и желаемого результата.',
			'The type of treatment and the device settings are chosen for the area, your skin phototype and the result you want.',
		),
		'image' => array( 'hardware-step-2.jpg', 'Пациентка у аппарата VIVACE в кабинете клиники' ),
	),
	array(
		'title' => array( 'Проведение процедуры', 'The treatment' ),
		'text'  => array(
			'Специалист обрабатывает выбранную зону, контролируя интенсивность и реакцию кожи на каждом этапе.',
			'The specialist treats the chosen area, monitoring the intensity and your skin’s response at every stage.',
		),
		'image' => array( 'hardware-step-3.jpg', 'Специалист проводит процедуру на аппарате VIVACE' ),
	),
	array(
		'title' => array( 'Восстановление', 'Recovery' ),
		'text'  => array(
			'После процедуры вы получаете рекомендации по уходу, защите кожи и дальнейшему курсу.',
			'After the treatment, you receive advice on aftercare, skin protection and the rest of your course.',
		),
		'image' => array( 'iv-step-4.jpg' ),
	),
);

$faq = array(
	array(
		'question' => array( 'Как понять, какая лазерная процедура мне подойдёт?', 'How do I know which laser treatment is right for me?' ),
		'answer'   => array(
			'Процедура подбирается после консультации и диагностики кожи. Специалист учитывает ваш запрос, фототип, состояние кожи и возможные противопоказания.',
			'The treatment is chosen after a consultation and skin assessment. The specialist takes into account your goals, phototype, skin condition and any contraindications.',
		),
	),
	array(
		'question' => array( 'Болезненны ли лазерные процедуры?', 'Are laser treatments painful?' ),
		'answer'   => array(
			'Ощущения зависят от типа процедуры и индивидуальной чувствительности. При необходимости используется местное обезболивание или система охлаждения.',
			'How it feels depends on the type of treatment and your individual sensitivity. Where needed, a local anaesthetic or a cooling system is used.',
		),
	),
	array(
		'question' => array( 'Сколько процедур потребуется?', 'How many sessions will I need?' ),
		'answer'   => array(
			'Количество зависит от выбранной методики, выраженности проблемы и реакции кожи. Некоторым пациентам достаточно одной процедуры, для других рекомендуется курс.',
			'It depends on the technique, the extent of the concern and how your skin responds. For some patients one session is enough; for others a course is recommended.',
		),
	),
	array(
		'question' => array( 'Нужен ли восстановительный период?', 'Is there any downtime?' ),
		'answer'   => array(
			'Он зависит от интенсивности воздействия. После некоторых процедур возможно небольшое покраснение, после более глубоких методик коже потребуется несколько дней на восстановление.',
			'It depends on the intensity of the treatment. Some treatments may leave slight redness; after deeper techniques, your skin will need a few days to recover.',
		),
	),
	array(
		'question' => array( 'Можно ли проводить лазерные процедуры летом?', 'Can I have laser treatments in summer?' ),
		'answer'   => array(
			'Некоторые методики имеют сезонные ограничения. Специалист оценит состояние кожи, уровень солнечной активности и подберёт безопасный период для процедуры.',
			'Some techniques have seasonal restrictions. The specialist will assess your skin and sun exposure and choose a safe time for the treatment.',
		),
	),
	array(
		'question' => array( 'Когда будет заметен результат?', 'When will I see the result?' ),
		'answer'   => array(
			'Часть изменений может быть заметна после восстановления кожи. Окончательный эффект некоторых процедур формируется постепенно в течение нескольких недель.',
			'Some changes may be visible once your skin has recovered. The final effect of certain treatments develops gradually over several weeks.',
		),
	),
);

skinlove_seed_sections(
	$hardware,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Аппаратная\n*косметология*", "Device-based\n*aesthetics*" ),
				'lead'   => array( 'Точное воздействие для заметного обновления кожи', 'Precise treatments for visibly renewed skin' ),
				'text'   => array(
					'Современные лазерные методики помогают работать с пигментацией, сосудистыми проявлениями, неровным рельефом и возрастными изменениями. Параметры процедуры подбираются индивидуально — с учётом состояния кожи, фототипа и желаемого результата.',
					'Modern laser techniques address pigmentation, visible blood vessels, uneven texture and signs of ageing. Treatment settings are tailored to you, taking into account your skin’s condition, phototype and the result you want.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type' => 'service-hero',
				'image' => skinlove_seed_image( 'hardware-hero.jpg' ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Задачи', 'Your goals' ),
				'title' => array( "Уход, который работает\n*именно для вашей кожи*", "Care that works\n*for your skin*" ),
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
					'title'  => array( "Начните с диагностики\n*и консультации специалиста*", "Start with an assessment\n*and a specialist consultation*" ),
					'text'   => array(
						'Оставьте контакты — администратор SkinLove поможет записаться на консультацию. Специалист оценит состояние кожи и подберёт методику с учётом вашего запроса, фототипа и желаемого результата.',
						'Leave your details and a SkinLove administrator will help you book a consultation. The specialist will assess your skin and choose a technique based on your goals, phototype and the result you want.',
					),
					'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
					'image'  => array( 'hardware-epilation.jpg' ),
				),
				'advantages'  => 'hardware',
				'faq'         => $faq,
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Подберём\n*лазерную процедуру*\nдля вашей кожи", "We’ll find\n*the right laser treatment*\nfor your skin" ),
				'text'   => array(
					'Запишитесь на консультацию, чтобы определить причину беспокоящих изменений, подобрать подходящую методику и составить индивидуальный план процедур.',
					'Book a consultation to find the cause of the changes that concern you, choose the right technique and put together a personal treatment plan.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover' ),
				'image'   => skinlove_seed_image( 'hardware-step-3.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «Аппаратная косметология» заполнена' );
