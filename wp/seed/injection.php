<?php
/**
 * Сид страницы «Инъекционная косметология»: SEO и секции.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/injection.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

$injection = skinlove_seed_page( 'injection', 'Инъекционная косметология' );

skinlove_seed_pair(
	$injection,
	'seo_title',
	'Инъекционная косметология — SkinLove, клиника эстетической медицины и wellness в Дубае',
	'Injectable aesthetics — SkinLove, aesthetic medicine and wellness clinic in Dubai'
);
skinlove_seed_pair(
	$injection,
	'seo_description',
	'Инъекционная косметология в клинике SkinLove: контурная пластика, биоревитализация, мезотерапия, ботулинотерапия, PRP-терапия и липолитики в Дубае.',
	'Injectable aesthetics at SkinLove: dermal fillers, biorevitalisation, mesotherapy, anti-wrinkle injections, PRP therapy and lipolytic injections in Dubai.'
);

$tasks = array(
	array(
		'title' => array( 'Скорректировать отдельные зоны', 'Refine specific areas' ),
		'text'  => array( 'Губы, подбородок, скулы, носогубные складки и другие области.', 'Lips, chin, cheekbones, nasolabial folds and other areas.' ),
	),
	array(
		'title' => array( 'Восстановить объёмы', 'Restore volume' ),
		'text'  => array( 'Деликатно подчеркнуть контуры лица и восстановить утраченный объём.', 'Subtly define facial contours and restore lost volume.' ),
	),
	array(
		'title' => array( 'Разгладить мимические морщины', 'Smooth expression lines' ),
		'text'  => array( 'Сделать лицо более свежим и расслабленным, сохранив естественную мимику.', 'Give your face a fresher, more relaxed look while keeping your natural expressions.' ),
	),
	array(
		'title' => array( 'Улучшить качество кожи', 'Improve skin quality' ),
		'text'  => array( 'Вернуть коже увлажнённость, плотность и здоровое сияние.', 'Restore hydration, firmness and a healthy glow to your skin.' ),
	),
	array(
		'title' => array( 'Предупредить возрастные изменения', 'Prevent signs of ageing' ),
		'text'  => array( 'Поддерживать молодость кожи с помощью современных профилактических методик.', 'Keep your skin youthful with modern preventive techniques.' ),
	),
);

$procedure_button = array( 'Записаться на процедуру', 'Book a treatment' );

$procedures = array(
	array(
		'pairs'  => array(
			'tab'   => array( 'Контурная пластика', 'Dermal fillers' ),
			'title' => array( 'Деликатная коррекция объёмов и гармонизация черт лица', 'Delicate volume correction and facial harmonisation' ),
			'text'  => array(
				'Восстановление утраченных объёмов, коррекция контуров и естественное преображение с сохранением индивидуальности.',
				'Restoring lost volume and refining contours for a natural transformation that preserves your individuality.',
			),
		),
		'image'  => array( 'inject-hero.jpg', 'Специалист проводит контурную пластику губ' ),
		'prices' => array(
			skinlove_seed_price( 'Juvederm Ultra Smile (0,55 ml)', '1750 AED', 'Juvederm Ultra Smile (0.55 ml)' ),
			skinlove_seed_price( 'Juvederm VOLUMA (1 ml)', '1750 AED' ),
			skinlove_seed_price( 'Juvederm VOLIFT (1 ml)', '1750 AED' ),
			skinlove_seed_price( 'Juvederm VOLBELLA (1 ml)', '1750 AED' ),
			skinlove_seed_price( 'Juvederm ULTRA 4 (1 ml)', '1750 AED' ),
			skinlove_seed_price( 'Radiesse (1,5 ml / 3 ml)', '2550 / 4050 AED', 'Radiesse (1.5 ml / 3 ml)' ),
			skinlove_seed_price( 'Stylage S / M / L / XL (1 ml)', '1700 AED' ),
			skinlove_seed_price( 'Pure 2 / 4', '1100 AED' ),
			skinlove_seed_price( 'Половые губы (1 ml)', '1300 AED', 'Labia (1 ml)' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Коллагенотерапия', 'Collagen stimulators' ),
			'title' => array( 'Восстановление упругости, плотности и естественного качества кожи', 'Restoring firmness, density and natural skin quality' ),
			'text'  => array(
				'Процедура направлена на стимуляцию выработки собственного коллагена, улучшение структуры кожи и коррекцию возрастных изменений.',
				'The treatment stimulates your own collagen production, improves skin structure and addresses signs of ageing.',
			),
		),
		'image'  => array( 'inject-collagen.jpg', 'Инъекция коллагенового препарата в область скулы' ),
		'prices' => array(
			skinlove_seed_price( 'Karisma Face (2 ml)', '2550 AED' ),
			skinlove_seed_price( 'SCULPTRA', '3050 AED' ),
			skinlove_seed_price( 'Urtol', '2000 AED' ),
			skinlove_seed_price( 'Aestefill', '3000 AED' ),
			skinlove_seed_price( 'Nithya', '2050 AED' ),
			skinlove_seed_price( 'Revacoll (5 ml)', '1900 AED' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Биоревитализация', 'Biorevitalisation' ),
			'title' => array( 'Глубокое увлажнение, восстановление упругости и улучшение качества кожи', 'Deep hydration, renewed firmness and better skin quality' ),
			'text'  => array(
				'Процедура помогает восполнить недостаток увлажнённости, улучшить тонус и эластичность кожи, уменьшить выраженность мелких морщин и вернуть лицу свежий, ухоженный вид.',
				'The treatment helps replenish moisture, improve skin tone and elasticity, soften fine lines and restore a fresh, well-cared-for look.',
			),
		),
		'image'  => array( 'inject-bio.jpg', 'Инъекция препарата для биоревитализации' ),
		'prices' => array(
			skinlove_seed_price( 'Novacutan YBIO / SBIO (2 ml)', '1700 AED' ),
			skinlove_seed_price( 'Novacutan (2 ml)', '1700 AED' ),
			skinlove_seed_price( 'Profhilo (2 ml)', '1700 AED' ),
			skinlove_seed_price( 'Prinest / Prinest Fast (2 ml)', '1700 AED' ),
			skinlove_seed_price( 'Neopeptide MTD (2 ml)', '1300 AED' ),
			skinlove_seed_price( 'Molecule Aqua (2 ml)', '1400 AED' ),
			skinlove_seed_price( 'Hyal White MTD (2 ml)', '1300 AED' ),
			skinlove_seed_price( 'EXOSOME (2 ml)', '1200 AED' ),
			skinlove_seed_price( 'Monaco', '1700 AED' ),
			skinlove_seed_price( 'Jalupro', '1700 AED' ),
			skinlove_seed_price( 'Meso Wharton P-199 / Meso Xanthin F-199', '1700 AED' ),
			skinlove_seed_price( 'PDRN', '1300 AED' ),
			skinlove_seed_price( 'TWAC / TWAC 3.0 / TWAC Eyes', '1700 / 1700 / 1300 AED' ),
			skinlove_seed_price( 'Revi Eye', '1300 AED' ),
			skinlove_seed_price( 'NCTF 135', '1100 AED' ),
			skinlove_seed_price( 'Juvederm VOLIFT', '1900 AED' ),
			skinlove_seed_price( 'Mesoten PuRe (3 ml) / AquaLight (2,5 ml) / Eyes (2 ml)', '1600 / 1600 / 1300 AED', 'Mesoten PuRe (3 ml) / AquaLight (2.5 ml) / Eyes (2 ml)' ),
			skinlove_seed_price( 'Lennea Booster (3 ml)', '1700 AED' ),
			skinlove_seed_price( 'Rejuran S (1 ml)', '1700 AED' ),
			skinlove_seed_price( 'GOLDFILL (3 ml)', '1700 AED' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Мезотерапия', 'Mesotherapy' ),
			'title' => array( 'Прицельное восстановление и улучшение качества кожи', 'Targeted renewal and improved skin quality' ),
			'text'  => array(
				'Процедура помогает работать с различными эстетическими задачами: от обезвоженности и несовершенств кожи до тёмных кругов под глазами и ослабленных волос. Препараты подбираются индивидуально с учётом состояния кожи и желаемого результата.',
				'The treatment addresses a range of aesthetic concerns, from dehydration and skin imperfections to dark circles under the eyes and weakened hair. Products are selected individually, based on your skin and the result you want.',
			),
		),
		'image'  => array( 'inject-meso.jpg', 'Мезотерапия: инъекция в область щеки' ),
		'prices' => array(
			skinlove_seed_price( 'MTD Hair', '700 AED' ),
			skinlove_seed_price( 'Dermaheal', '1000 AED' ),
			skinlove_seed_price( 'Meso Anti Acne', '1000 AED' ),
			skinlove_seed_price( 'Meso Eye Lifting', '1000 AED' ),
			skinlove_seed_price( 'Dr. Cyj Hair Filler', '900 AED' ),
			skinlove_seed_price( 'Anti-Dark Solution', '900 AED' ),
			skinlove_seed_price( 'Melsmon', '900 AED' ),
			skinlove_seed_price( 'Demax (Япония)', '1100 AED', 'Demax (Japan)' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Ботулинотерапия', 'Anti-wrinkle injections' ),
			'title' => array( 'Коррекция мимических морщин с сохранением естественной выразительности', 'Softening expression lines while keeping your natural expressiveness' ),
			'text'  => array(
				'Ботулинотерапия помогает разгладить мимические морщины, снизить избыточную активность отдельных мышц и предупредить углубление заломов, сохраняя естественность мимики.',
				'Botulinum toxin injections help smooth expression lines, reduce excessive activity in specific muscles and keep creases from deepening, while your expressions stay natural.',
			),
		),
		'image'  => array( 'inject-botox.jpg', 'Специалист готовит препарат для ботулинотерапии' ),
		'prices' => array(
			skinlove_seed_price( 'Ботокс (1 ед.)', '45 AED', 'Botox (1 unit)' ),
			skinlove_seed_price( 'Диспорт (1 ед.)', '35 AED', 'Dysport (1 unit)' ),
			skinlove_seed_price( 'Лечение гипергидроза 100 ед. / 300 ед.', '1500 / 2800 AED', 'Hyperhidrosis treatment, 100 / 300 units' ),
			skinlove_seed_price( 'Верхняя треть лица', '1500 AED', 'Upper face' ),
			skinlove_seed_price( 'Full face + шея', '2300 AED', 'Full face + neck' ),
			skinlove_seed_price( 'Traptox (шея Барби)', '1800 AED', 'Traptox (Barbie neck)' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'PRP-терапия / плазмолифтинг', 'PRP therapy / plasma lifting' ),
			'title' => array( 'Естественное обновление и восстановление качества кожи', 'Natural renewal and restored skin quality' ),
			'text'  => array(
				'PRP-терапия использует собственную плазму крови пациента, обогащённую тромбоцитами, для стимуляции восстановительных процессов, улучшения тонуса и текстуры кожи, повышения её упругости и эластичности.',
				'PRP therapy uses your own platelet-rich plasma to stimulate the skin’s repair processes, improve its tone and texture, and increase firmness and elasticity.',
			),
		),
		'image'  => array( 'inject-prp.jpg', 'PRP-терапия: инъекция в область под глазами' ),
		'prices' => array(
			skinlove_seed_price( '1 процедура', '900 AED', '1 session' ),
		),
	),
	array(
		'pairs'  => array(
			'tab'   => array( 'Липолитики', 'Lipolytic injections' ),
			'title' => array( 'Деликатная коррекция локальных жировых отложений и моделирование контуров', 'Delicate reduction of localised fat and contour sculpting' ),
			'text'  => array(
				'Инъекционная процедура помогает уменьшить локальные жировые отложения, скорректировать проблемные зоны и сделать контуры лица и тела более чёткими и гармоничными.',
				'This injectable treatment helps reduce localised fat deposits, refine problem areas and make the contours of the face and body more defined and balanced.',
			),
		),
		'image'  => array( 'inject-lipo.jpg', 'Пациентка в кресле во время инъекционной процедуры' ),
		'prices' => array(
			skinlove_seed_price( 'DR LIPO+ / DR LIPO+V', '800 AED' ),
			skinlove_seed_price( 'PI', '800 AED' ),
			skinlove_seed_price( 'LEMON BOTTLE', '850 AED' ),
			skinlove_seed_price( 'PB Serum TRIADA', '950 AED' ),
			skinlove_seed_price( 'MesoSculpt C71 (1 ml)', '1100 AED' ),
			skinlove_seed_price( 'Light fit', '850 AED' ),
			skinlove_seed_price( 'Lipolab', '750 AED' ),
			skinlove_seed_price( 'Курс Lipolab, 5 процедур', '2500 AED', 'Lipolab course, 5 sessions' ),
		),
	),
);

$steps = array(
	array(
		'title' => array( 'Диагностика', 'Assessment' ),
		'text'  => array(
			'Врач внимательно изучает особенности лица, выслушивает ваши пожелания и отвечает на вопросы.',
			'The doctor carefully assesses your facial features, listens to your wishes and answers your questions.',
		),
		'image' => array( 'inject-step-1.jpg', 'Пациентка берёт напиток с подноса перед консультацией' ),
	),
	array(
		'title' => array( 'Индивидуальный план', 'A personal plan' ),
		'text'  => array(
			'Мы подбираем процедуру, препарат и объём коррекции с учётом ваших целей.',
			'We select the treatment, the product and the extent of correction to match your goals.',
		),
		'image' => array( 'wellness-consultation.jpg', 'Врач обсуждает с пациенткой план процедур' ),
	),
	array(
		'title' => array( 'Проведение процедуры', 'The treatment' ),
		'text'  => array(
			'Инъекционная процедура выполняется максимально комфортно с соблюдением медицинских стандартов.',
			'The injectable treatment is performed as comfortably as possible, in line with medical standards.',
		),
		'image' => array( 'inject-step-3.jpg', 'Специалист проводит инъекционную процедуру' ),
	),
	array(
		'title' => array( 'Рекомендации', 'Aftercare' ),
		'text'  => array(
			'После процедуры врач расскажет об уходе, восстановлении и при необходимости составит дальнейший план.',
			'After the treatment, the doctor will explain aftercare and recovery and, if needed, put together a plan for the next steps.',
		),
		'image' => array( 'iv-step-4.jpg', 'Специалист подбирает уход для пациентки' ),
	),
);

$faq = array(
	array(
		'question' => array( 'Будет ли результат выглядеть естественно?', 'Will the result look natural?' ),
		'answer'   => array(
			'Да. Мы придерживаемся принципа деликатной коррекции, которая сохраняет индивидуальные черты лица.',
			'Yes. We follow the principle of subtle correction that preserves your individual features.',
		),
	),
	array(
		'question' => array( 'Болезненны ли процедуры?', 'Are the treatments painful?' ),
		'answer'   => array(
			'Дискомфорт минимальный: при необходимости используется местная анестезия, а многие препараты уже содержат обезболивающий компонент.',
			'Discomfort is minimal: local anaesthesia is used where needed, and many products already contain a numbing agent.',
		),
	),
	array(
		'question' => array( 'Когда будет заметён результат?', 'When will I see the result?' ),
		'answer'   => array(
			'Зависит от процедуры: эффект контурной пластики виден сразу, ботулинотерапии — через 7–14 дней, биоревитализации и коллагенотерапии — после курса.',
			'It depends on the treatment: dermal fillers show their effect straight away, anti-wrinkle injections after 7–14 days, and biorevitalisation and collagen stimulators after a course.',
		),
	),
	array(
		'question' => array( 'Нужен ли период восстановления?', 'Is there any downtime?' ),
		'answer'   => array(
			'Как правило, нет. Возможны небольшие покраснения или отёк в зоне инъекций, которые проходят за 1–3 дня. Врач даст рекомендации по уходу.',
			'As a rule, no. There may be slight redness or swelling at the injection sites, which settles within 1–3 days. The doctor will give you aftercare advice.',
		),
	),
	array(
		'question' => array( 'Как понять, какая процедура подойдёт именно мне?', 'How do I know which treatment is right for me?' ),
		'answer'   => array(
			'Запишитесь на консультацию: врач оценит состояние кожи и особенности лица, обсудит ваши пожелания и подберёт процедуру и препарат.',
			'Book a consultation: the doctor will assess your skin and facial features, discuss your wishes and select the right treatment and product.',
		),
	),
);

skinlove_seed_sections(
	$injection,
	array(
		skinlove_seed_entry(
			array(
				'title'  => array( "Инъекционная\n*косметология*", "Injectable\n*aesthetics*" ),
				'lead'   => array( 'Современные методики для естественного преображения', 'Modern techniques for a natural transformation' ),
				'text'   => array(
					'Инъекционные процедуры помогают деликатно скорректировать возрастные изменения, улучшить качество кожи и подчеркнуть естественную красоту. Мы подбираем решения индивидуально, сохраняя гармонию черт лица и вашу индивидуальность.',
					'Injectable treatments gently address signs of ageing, improve skin quality and enhance your natural beauty. Every plan is tailored to you, preserving the harmony of your features and your individuality.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type' => 'service-hero',
				'image' => skinlove_seed_image( 'inject-hero.jpg' ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Задачи', 'Your goals' ),
				'title' => array( "Решения, которые подбираются\n*именно под ваш запрос*", "Solutions chosen\n*around your needs*" ),
			),
			array(
				'_type' => 'tasks',
				'items' => array_map( 'skinlove_seed_entry', $tasks ),
			)
		),
		array(
			'_type'        => 'procedures',
			'prices_limit' => 11,
			'items'        => skinlove_seed_procedures( $procedures, $procedure_button ),
		),
		...skinlove_seed_service_sections(
			array(
				'promos'      => array( 'svc-2-inject.jpg', 'inject-step-3.jpg', 'svc-3-face.jpg', 'wellness-consultation.jpg' ),
				'steps_title' => array( "Комфортно. Безопасно\n*Под наблюдением специалиста*", "Comfortable. Safe\n*Under expert care*" ),
				'steps'       => $steps,
				'request'     => array(
					'title'  => array( "Естественный\nрезультат начинается\n*с индивидуального подхода*", "Natural results\nbegin with\n*a personal approach*" ),
					'text'   => array(
						'Оставьте контакты — администратор SkinLove поможет записаться к специалисту. На консультации врач оценит особенности лица, обсудит ваши пожелания и предложит деликатный план коррекции с сохранением естественных черт.',
						'Leave your details and a SkinLove administrator will help you book with a specialist. At the consultation, the doctor will assess your facial features, discuss your wishes and suggest a delicate correction plan that keeps your natural features.',
					),
					'button' => array( 'Подобрать процедуру', 'Find my treatment' ),
					'image'  => array( 'inject-botox.jpg' ),
				),
				'advantages'  => 'inject',
				'faq'         => $faq,
			)
		),
		skinlove_seed_entry(
			array(
				'title'  => array( "Запишитесь\n*на консультацию*", "Book\n*a consultation*" ),
				'text'   => array(
					'Врач оценит особенности лица, обсудит ваши пожелания и предложит деликатный план коррекции с сохранением естественных черт.',
					'The doctor will assess your facial features, discuss your wishes and suggest a delicate correction plan that keeps your natural features.',
				),
				'button' => array( 'Записаться на консультацию', 'Book a consultation' ),
			),
			array(
				'_type'   => 'cta',
				'variant' => array( 'cover' ),
				'image'   => skinlove_seed_image( 'inject-hero.jpg' ),
			)
		),
	)
);

WP_CLI::success( 'Страница «Инъекционная косметология» заполнена' );
