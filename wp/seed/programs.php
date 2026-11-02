<?php
/**
 * Сид программ IV Therapy: категории и программы из каталога вёрстки.
 * Категории ищутся по slug, программы — по русскому названию.
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/programs.php
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require __DIR__ . '/_lib.php';

/*
 * Категории по порядку вкладок: slug => название, метка на карточке.
 * В вёрстке у двух карточек своя метка (Anti-age у Laennec, Антистресс),
 * в теме метка одна на категорию.
 */
$categories = array(
	'vitamin'  => array(
		'name' => array( 'Витаминные', 'Vitamin' ),
		'tag'  => array( 'Витамины', 'Vitamins' ),
	),
	'recovery' => array(
		'name' => array( 'Восстановительные', 'Recovery' ),
		'tag'  => array( 'Восстановление', 'Recovery' ),
	),
	'beauty'   => array(
		'name' => array( 'Beauty и Anti-age', 'Beauty & anti-ageing' ),
		'tag'  => array( 'Beauty', 'Beauty' ),
	),
	'energy'   => array(
		'name' => array( 'Энергетические', 'Energy' ),
		'tag'  => array( 'Энергия', 'Energy' ),
	),
	'health'   => array(
		'name' => array( 'Оздоровительные', 'Health' ),
		'tag'  => array( 'Здоровье', 'Health' ),
	),
	'course'   => array(
		'name' => array( 'Курсы', 'Courses' ),
		'tag'  => array( 'Курс', 'Course' ),
	),
);

$single = array( '1 процедура', '1 session' );

$programs = array(
	array(
		'category' => 'vitamin',
		'title'    => array( 'Капельница «Витамин D»', 'Vitamin D drip' ),
		'text'     => array(
			'Программа для восполнения дефицита витамина D при подтверждённой необходимости.',
			'A programme to replenish vitamin D when a deficiency has been confirmed.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'vitamin',
		'title'    => array( 'Капельница «Мультивитамин»', 'Multivitamin drip' ),
		'text'     => array(
			'Комплекс микроэлементов для восполнения индивидуальных потребностей организма и нутритивной поддержки.',
			'A blend of micronutrients to meet your body’s individual needs and provide nutritional support.',
		),
		'prices'   => array( array( $single, '750 AED' ) ),
	),
	array(
		'category' => 'vitamin',
		'title'    => array( 'Капельница «Железо»', 'Iron drip' ),
		'text'     => array(
			'Инфузионная терапия для восполнения дефицита железа по результатам анализов и назначению врача.',
			'Infusion therapy to replenish iron deficiency based on test results and a doctor’s prescription.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'recovery',
		'title'    => array( 'Миланский коктейль', 'Milan cocktail' ),
		'text'     => array(
			'Инфузионный комплекс с глутатионом, актовегином и витаминами группы B, представленный в прайсе для восстановления после нагрузок.',
			'An infusion blend with glutathione, Actovegin and B vitamins, offered for recovery after physical exertion.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'recovery',
		'title'    => array( 'Капельница Майерса', 'Myers’ cocktail' ),
		'text'     => array(
			'Витаминно-минеральный комплекс с витаминами C, группы B, магнием и кальцием для нутритивной поддержки.',
			'A vitamin and mineral blend with vitamin C, B vitamins, magnesium and calcium for nutritional support.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'Капельница «Золушка»', 'Cinderella drip' ),
		'text'     => array(
			'Антиоксидантный комплекс с глутатионом, альфа-липоевой кислотой и витамином C, представленный как программа поддержки качества кожи.',
			'An antioxidant blend with glutathione, alpha-lipoic acid and vitamin C, offered as a programme to support skin quality.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'beauty',
		'title'    => array( 'Капельница Laennec', 'Laennec drip' ),
		'text'     => array(
			'Инфузионная терапия на основе плацентарного препарата, представленная в прайсе как программа поддержки восстановления тканей.',
			'Infusion therapy based on a placental preparation, offered as a programme to support tissue repair.',
		),
		'prices'   => array( array( $single, '750 AED' ) ),
	),
	array(
		'category' => 'energy',
		'title'    => array( 'Капельница «Антистресс»', 'Anti-stress drip' ),
		'text'     => array(
			'Инфузионная программа, представленная в прайсе для поддержки организма в периоды эмоциональных нагрузок и переутомления.',
			'An infusion programme offered to support your body through periods of emotional strain and exhaustion.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'energy',
		'title'    => array( 'Капельница с NAD+', 'NAD+ drip' ),
		'text'     => array(
			'Инфузия с NAD+, позиционируемая как программа поддержки клеточных энергетических процессов.',
			'An NAD+ infusion positioned as a programme to support cellular energy processes.',
		),
		'prices'   => array( array( $single, '750 AED' ) ),
	),
	array(
		'category' => 'energy',
		'title'    => array( 'Капельница для улучшения работы головного мозга', 'Brain performance drip' ),
		'text'     => array(
			'Программа, представленная в прайсе для поддержки концентрации внимания и когнитивных функций.',
			'A programme offered to support concentration and cognitive function.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'health',
		'title'    => array( 'Капельница против простуды и гриппа', 'Cold and flu drip' ),
		'text'     => array(
			'Комплекс с витамином C, Реамберином и полиоксидонием, указанный в прайсе для применения при простудных состояниях.',
			'A blend with vitamin C, Reamberin and Polyoxidonium, offered for use when you have a cold.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'health',
		'title'    => array( 'Капельница «Супер Слим»', 'Super Slim drip' ),
		'text'     => array(
			'Инфузионная программа с альфа-липоевой кислотой, представленная в прайсе как дополнение к программам контроля веса.',
			'An infusion programme with alpha-lipoic acid, offered as a complement to weight-management programmes.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'health',
		'title'    => array( 'Капельница от отравлений', 'Intoxication recovery drip' ),
		'text'     => array(
			'Инфузионная программа, указанная в прайсе для восстановительной поддержки при интоксикациях.',
			'An infusion programme offered for restorative support after intoxication.',
		),
		'prices'   => array( array( $single, '750 AED' ) ),
	),
	array(
		'category' => 'health',
		'title'    => array( 'Капельница «Очищающая»', 'Cleansing drip' ),
		'text'     => array(
			'Программа, представленная в прайсе для поддержки обменных процессов и общего самочувствия.',
			'A programme offered to support your metabolism and overall well-being.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'health',
		'title'    => array( 'Капельница «Здоровая печень»', 'Healthy Liver drip' ),
		'text'     => array(
			'Инфузионная программа с Гептралом, представленная в прайсе для поддержки функции печени.',
			'An infusion programme with Heptral, offered to support liver function.',
		),
		'prices'   => array( array( $single, '650 AED' ) ),
	),
	array(
		'category' => 'course',
		'title'    => array( 'Курс капельниц — 6 процедур', 'IV drip course — 6 sessions' ),
		'text'     => array(
			'Комплекс инфузионных процедур с подбором состава и программы по индивидуальным показаниям.',
			'A series of infusion treatments, with the formula and programme chosen according to your individual needs.',
		),
		'prices'   => array( array( array( '6 процедур', '6 sessions' ), '2900 AED' ) ),
	),
	array(
		'category' => 'course',
		'title'    => array( 'Курс капельниц — 10 процедур', 'IV drip course — 10 sessions' ),
		'text'     => array(
			'Расширенный курс инфузионной терапии с индивидуальным подбором процедур.',
			'An extended course of infusion therapy with individually selected treatments.',
		),
		'prices'   => array( array( array( '10 процедур', '10 sessions' ), '4500 AED' ) ),
	),
);

$order = 0;

foreach ( $categories as $slug => $category ) {
	$term = get_term_by( 'slug', $slug, 'sl_program_category' );

	if ( $term ) {
		wp_update_term( $term->term_id, 'sl_program_category', array( 'name' => $category['name'][0] ) );
		$term_id = $term->term_id;
	} else {
		$created = wp_insert_term( $category['name'][0], 'sl_program_category', array( 'slug' => $slug ) );

		if ( is_wp_error( $created ) ) {
			WP_CLI::error( "{$slug}: " . $created->get_error_message() );
		}

		$term_id = $created['term_id'];
	}

	carbon_set_term_meta( $term_id, 'sl_name_en', $category['name'][1] );
	carbon_set_term_meta( $term_id, 'sl_tag_ru', $category['tag'][0] );
	carbon_set_term_meta( $term_id, 'sl_tag_en', $category['tag'][1] );
	carbon_set_term_meta( $term_id, 'sl_order', ( ++$order ) * 10 );

	WP_CLI::log( ( $term ? 'Обновлена' : 'Создана' ) . " категория {$slug} (#{$term_id})" );
}

foreach ( $programs as $index => $program ) {
	$id = skinlove_seed_post( 'sl_program', $program['title'][0], array( 'menu_order' => ( $index + 1 ) * 10 ) );

	skinlove_seed_pair( $id, 'title', ...$program['title'] );
	skinlove_seed_pair( $id, 'text', ...$program['text'] );
	carbon_set_post_meta(
		$id,
		'sl_prices',
		array_map(
			fn( $row ) => skinlove_seed_entry( array( 'amount' => $row[0] ), array( 'price' => $row[1] ) ),
			$program['prices']
		)
	);
	wp_set_object_terms( $id, $program['category'], 'sl_program_category' );
}

WP_CLI::success( 'Программ: ' . count( $programs ) . ', категорий: ' . count( $categories ) );
