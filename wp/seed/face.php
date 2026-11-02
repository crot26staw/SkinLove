<?php
/**
 * Сид страницы «Эстетическая косметология лица»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/face.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$face = skinlove_seed_page( 'face', 'Эстетическая косметология лица' );

skinlove_seed_pair(
	$face,
	'seo_title',
	'Эстетическая косметология лица — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Aesthetic facial treatments — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$face,
	'seo_description',
	'Эстетическая косметология лица в клинике SkinLove: чистки лица, пилинги, удаление папиллом и бородавок, оксигенотерапия и микротоки в Дубае.',
	'Aesthetic facial treatments at SkinLove: facial cleansing, peels, papilloma and wart removal, oxygen therapy and microcurrent therapy in Dubai.'
);

$tasks = array(
	array(
		'title' => array( 'Выровнять тон и текстуру', 'Even out tone and texture' ),
		'text'  => array( 'Сделать кожу более гладкой, ровной и ухоженной.', 'Make your skin smoother, more even and well cared for.' ),
	),
	array(
		'title' => array( 'Очистить кожу', 'Cleanse your skin' ),
		'text'  => array( 'Избавиться от загрязнений, излишков себума и несовершенств.', 'Clear away impurities, excess sebum and blemishes.' ),
	),
	array(
		'title' => array( 'Вернуть коже сияние', 'Bring back radiance' ),
		'text'  => array( 'Когда лицо выглядит уставшим и тусклым.', 'For when your face looks tired and dull.' ),
	),
	array(
		'title' => array( 'Глубоко увлажнить', 'Deeply hydrate' ),
		'text'  => array( 'Восстановить комфорт, мягкость и естественное сияние кожи.', 'Restore comfort, softness and your skin’s natural glow.' ),
	),
	array(
		'title' => array( 'Уменьшить воспаления', 'Calm inflammation' ),
		'text'  => array( 'Комплексный уход за проблемной и чувствительной кожей.', 'Comprehensive care for problem-prone and sensitive skin.' ),
	),
);

$procedures = array(
	array(
		'pairs'  => array(
			'tab'   => array( 'Чистки лица', 'Facial cleansing' ),
			'title' => array( 'Глубокое очищение и обновление кожи для свежего и ровного тона', 'Deep cleansing and renewal for a fresh, even complexion' ),
			'text'  => array(
				'Профессиональное очищение помогает удалить загрязнения и излишки себума, уменьшить выраженность несовершенств и улучшить текстуру кожи, возвращая ей гладкость, свежесть и ухоженный вид.',
				'Professional cleansing removes impurities and excess sebum, reduces the appearance of blemishes and improves skin texture, leaving your skin smooth, fresh and well cared for.',
			),
		),
		'image'  => array( 'face-clean.jpg', 'Косметолог проводит ультразвуковую чистку лица' ),
		'prices' => array(
			skinlove_seed_price( 'Zo Skin Obagi', '700 AED' ),
			skinlove_seed_price( 'iS Clinical', '750 AED' ),
			skinlove_seed_price( 'Чистка лица', '750 AED', 'Facial cleansing' ),
			skinlove_seed_price( 'Чистка спины', '750 AED', 'Back cleansing' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Пилинги', 'Peels' ),
			'title' => array( 'Бережное обновление кожи для ровного тона и гладкой текстуры', 'Gentle skin renewal for an even tone and smooth texture' ),
			'text'  => array(
				'Профессиональные пилинги помогают удалить ороговевшие клетки, улучшить рельеф и тон кожи, уменьшить выраженность пигментации и несовершенств. Состав и интенсивность воздействия подбираются индивидуально с учётом особенностей кожи.',
				'Professional peels remove dead skin cells, refine skin texture and tone, and reduce the appearance of pigmentation and blemishes. The formula and intensity are chosen individually to suit your skin.',
			),
		),
		'image'  => array( 'face-peel.jpg', 'Косметолог наносит пилинг кистью' ),
		'prices' => array(
			skinlove_seed_price( 'BioRePeel лицо / тело', '450 / 600 AED', 'BioRePeel face / body' ),
			skinlove_seed_price( 'PRX-T33', '450 AED' ),
			skinlove_seed_price( 'Джесснера', '600 AED', 'Jessner' ),
			skinlove_seed_price( 'TCA', '550 AED' ),
			skinlove_seed_price( 'Summer Peel', '450 AED' ),
			skinlove_seed_price( 'Салициловый лицо / тело', '450 / 550 AED', 'Salicylic face / body' ),
			skinlove_seed_price( 'Азелаиновый', '450 AED', 'Azelaic' ),
			skinlove_seed_price( 'Миндальный', '450 AED', 'Mandelic' ),
			skinlove_seed_price( 'Гликолевый', '450 AED', 'Glycolic' ),
			skinlove_seed_price( 'Феруловый', '450 AED', 'Ferulic' ),
			skinlove_seed_price( 'Yellow Peel', '550 AED' ),
			skinlove_seed_price( 'Ретиноловый', '550 AED', 'Retinol' ),
			skinlove_seed_price( 'Интимный пилинг', '600 AED', 'Intimate area peel' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Удаление папиллом и бородавок', 'Papilloma and wart removal' ),
			'title' => array( 'Бережное удаление кожных образований с заботой об эстетике кожи', 'Gentle removal of skin growths with care for how your skin looks' ),
			'text'  => array(
				'Процедура направлена на удаление папиллом и бородавок с минимальным воздействием на окружающие ткани. Метод удаления подбирается индивидуально после оценки образования специалистом.',
				'The treatment removes papillomas and warts with minimal impact on the surrounding tissue. The removal method is chosen individually once a specialist has assessed the growth.',
			),
		),
		'image'  => array( 'face-papilloma.jpg', 'Специалист удаляет папиллому аппаратом' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура', 'от 200 AED', '1 session' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Оксигенотерапия', 'Oxygen therapy' ),
			'title' => array( 'Кислородное насыщение для свежести, увлажнения и сияния кожи', 'Oxygen infusion for fresh, hydrated, radiant skin' ),
			'text'  => array(
				'Процедура сочетает воздействие кислорода и активных сывороток, помогая увлажнить кожу, улучшить её тонус, цвет и общее состояние, вернуть ощущение свежести и ухоженный вид.',
				'The treatment combines oxygen with active serums to hydrate the skin, improve its tone, colour and overall condition, and bring back a fresh, well-cared-for look.',
			),
		),
		'image'  => array( 'face-oxygen.jpg', 'Оксигенотерапия: распыление кислорода над лицом пациентки' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура', '150 AED', '1 session' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Микротоки', 'Microcurrent therapy' ),
			'title' => array( 'Деликатный лифтинг и восстановление тонуса кожи', 'Gentle lifting and renewed skin firmness' ),
			'text'  => array(
				'Микротоковая терапия стимулирует естественные процессы регенерации, помогает улучшить микроциркуляцию, уменьшить отёчность и повысить упругость кожи, делая контуры лица более чёткими.',
				'Microcurrent therapy stimulates natural regeneration, helps improve microcirculation, reduce puffiness and increase skin firmness, making facial contours more defined.',
			),
		),
		'image'  => array( 'face-microcurrent.jpg', 'Косметолог проводит микротоковую терапию лица' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура', '350 AED', '1 session' ),
		),
	),
);

$steps = array(
	array(
		'title' => array( 'Консультация', 'Consultation' ),
		'text'  => array(
			'Косметолог оценивает состояние кожи, выявляет её потребности и подбирает подходящую программу.',
			'The cosmetologist assesses your skin, identifies its needs and selects the right programme.',
		),
		'image' => array( 'outcall-step-1.jpg', 'Поднос с чаем и закусками после процедуры' ),
	),
	array(
		'title' => array( 'Подготовка', 'Preparation' ),
		'text'  => array(
			'Очищение кожи и подготовка к основному этапу процедуры.',
			'Your skin is cleansed and prepared for the main stage of the treatment.',
		),
		'image' => array( 'face-peel.jpg' ),
	),
	array(
		'title' => array( 'Основной уход', 'The treatment' ),
		'text'  => array(
			'Проведение выбранной процедуры с использованием профессиональной косметики и современных методик.',
			'The chosen treatment is performed with professional skincare products and modern techniques.',
		),
		'image' => array( 'face-hero.jpg', 'Косметолог проводит уходовую процедуру' ),
	),
	array(
		'title' => array( 'Домашние рекомендации', 'Home care advice' ),
		'text'  => array(
			'После процедуры специалист расскажет, как поддерживать результат и правильно ухаживать за кожей дома.',
			'After the treatment, the specialist will explain how to maintain the result and care for your skin at home.',
		),
		'image' => array( 'iv-step-4.jpg' ),
	),
);

// В вёрстке и макете ответы на первый и последний вопрос стоят так — перенесено как есть.
$faq = array(
	array(
		'question' => array( 'Когда будет заметен эффект?', 'When will I see the effect?' ),
		'answer'   => array(
			'Во время консультации косметолог оценит состояние кожи и подберёт наиболее эффективную программу ухода.',
			'During the consultation, the cosmetologist will assess your skin and select the most effective care programme.',
		),
	),
	array(
		'question' => array( 'Как часто рекомендуется делать профессиональный уход?', 'How often should I have professional skincare treatments?' ),
		'answer'   => array(
			'Это зависит от состояния кожи и выбранной процедуры. Специалист составит индивидуальный график посещений.',
			'It depends on your skin and the chosen treatment. The specialist will put together a personal schedule of visits.',
		),
	),
	array(
		'question' => array( 'Есть ли восстановительный период?', 'Is there any downtime?' ),
		'answer'   => array(
			'Большинство уходовых процедур не требуют длительной реабилитации, поэтому вы сможете быстро вернуться к привычному ритму жизни.',
			'Most skincare treatments need no lengthy recovery, so you can quickly get back to your usual routine.',
		),
	),
	array(
		'question' => array( 'Можно ли сочетать уходовые процедуры с инъекциями?', 'Can skincare treatments be combined with injectables?' ),
		'answer'   => array(
			'Да. Многие процедуры прекрасно дополняют друг друга и помогают получить более выраженный результат.',
			'Yes. Many treatments complement each other well and help achieve a more noticeable result.',
		),
	),
	array(
		'question' => array( 'Как понять, какая процедура подойдёт именно мне?', 'How do I know which treatment is right for me?' ),
		'answer'   => array(
			'После большинства процедур кожа выглядит более свежей и ухоженной уже после первого посещения, а регулярный уход позволяет сохранить результат надолго.',
			'After most treatments, your skin looks fresher and more cared for from the very first visit, and regular care helps the result last.',
		),
	),
);

skinlove_seed_sections(
	$face,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Эстетическая\n*косметология лица*", "Aesthetic\n*facial treatments*" ),
				'lead'   => array( 'Здоровая кожа начинается с правильного ухода', 'Healthy skin starts with the right care' ),
				'text'   => array(
					'Профессиональные уходовые процедуры помогают сохранить здоровье кожи, вернуть ей свежесть, сияние и комфорт. Мы подбираем программы индивидуально, учитывая особенности вашей кожи, её состояние и образ жизни.',
					'Professional skincare treatments help keep your skin healthy and bring back its freshness, radiance and comfort. Every programme is tailored to your skin, its condition and your lifestyle.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type' => 'service-hero',
				'image' => skinlove_seed_image( 'face-hero.jpg' ),
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
			'prices_limit' => 13,
			'items'        => skinlove_seed_procedures( $procedures, array( 'Записаться на процедуру', 'Book a treatment' ) ),
		),
		...skinlove_seed_service_sections(
			array(
				'promos'      => array( 'svc-2-inject.jpg', 'inject-step-3.jpg', 'svc-3-face.jpg', 'wellness-consultation.jpg' ),
				'steps_title' => array( "Комфортный уход\n*с индивидуальным подходом*", "Comfortable care\n*with a personal approach*" ),
				'steps'       => $steps,
				'request'     => array(
					'title'  => array( "Подберём уход\n*для вашей кожи*", "We’ll find the right care\n*for your skin*" ),
					'text'   => array(
						'Оставьте контакты — администратор SkinLove уточнит ваш запрос и поможет записаться к косметологу. Специалист оценит состояние кожи и подберёт процедуру с учётом её потребностей и ваших целей.',
						'Leave your details and a SkinLove administrator will clarify your request and help you book with a cosmetologist. The specialist will assess your skin and select a treatment based on its needs and your goals.',
					),
					'button' => array( 'Подобрать процедуру', 'Find my treatment' ),
					'image'  => array( 'face-microcurrent.jpg' ),
				),
				'advantages'  => 'face',
				'faq'         => $faq,
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Доверьте заботу\n*о своей коже*\nпрофессионалам", "Entrust\n*your skin*\nto the experts" ),
				'text'   => array(
					'Запишитесь на консультацию, и мы подберём программу ухода, которая поможет сохранить здоровье, свежесть и естественное сияние вашей кожи.',
					'Book a consultation and we’ll select a care programme to keep your skin healthy, fresh and naturally radiant.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover' ),
				'image'   => skinlove_seed_image( 'face-hero.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «Эстетическая косметология лица» заполнена' );
