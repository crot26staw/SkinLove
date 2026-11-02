<?php
/**
 * Общие хелперы сидов. Подключается из каждого сид-файла:
 *
 *   require __DIR__ . '/_lib.php';
 *
 * Запуск: docker compose run --rm wpcli wp eval-file /seed/{файл}.php
 *
 * Сиды идемпотентны: запись ищется по slug, картинка — по имени исходного
 * файла, повторный запуск перезаписывает поля, а не плодит дубли.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

/** Папка public/ вёрстки внутри контейнера wpcli. */
const SKINLOVE_SEED_ASSETS = '/seed-assets';

/**
 * ID вложения для картинки из public/img вёрстки. Загружает файл
 * в медиабиблиотеку при первом вызове, дальше находит по имени файла.
 *
 * @param string $file Имя файла в public/img, например 'adv-1.jpg'.
 * @param string $alt  alt на русском (решение 4: один на оба языка).
 * @return int
 */
function skinlove_seed_image( $file, $alt = '' ) {
	$found = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_skinlove_seed_file',
			'meta_value'     => $file,
		)
	);

	if ( $found ) {
		$id = (int) $found[0];
	} else {
		$source = SKINLOVE_SEED_ASSETS . '/img/' . $file;

		if ( ! file_exists( $source ) ) {
			WP_CLI::error( "Нет картинки {$source}" );
		}

		// media_handle_sideload перемещает файл, поэтому отдаём ему копию.
		$tmp = wp_tempnam( $file );
		copy( $source, $tmp );

		$id = media_handle_sideload(
			array(
				'name'     => $file,
				'tmp_name' => $tmp,
			)
		);

		if ( is_wp_error( $id ) ) {
			WP_CLI::error( "{$file}: " . $id->get_error_message() );
		}

		update_post_meta( $id, '_skinlove_seed_file', $file );
		WP_CLI::log( "Загружена картинка {$file}" );
	}

	if ( '' !== $alt ) {
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	}

	return $id;
}

/**
 * Страница по slug: создаёт, если её нет, иначе обновляет заголовок и порядок.
 * Без menu_order в $args порядок существующей страницы сохраняется:
 * wp_insert_post не сливает поля с записью и сбросил бы его в 0.
 *
 * @param string $slug  Slug, один на оба языка.
 * @param string $title Заголовок в админке (русский).
 * @param array  $args  Дополнительные поля wp_insert_post: menu_order, page_template…
 * @return int ID страницы.
 */
function skinlove_seed_page( $slug, $title, $args = array() ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );

	$id = wp_insert_post(
		array_merge(
			array(
				'ID'          => $page ? $page->ID : 0,
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_name'   => $slug,
				'post_title'  => $title,
				'menu_order'  => $page ? $page->menu_order : 0,
			),
			$args
		),
		true
	);

	if ( is_wp_error( $id ) ) {
		WP_CLI::error( "{$slug}: " . $id->get_error_message() );
	}

	WP_CLI::log( ( $page ? 'Обновлена' : 'Создана' ) . " страница {$slug} (#{$id})" );

	return $id;
}

/**
 * Записывает двуязычное поле записи: sl_{$name}_ru и sl_{$name}_en.
 *
 * @param int    $post_id ID записи.
 * @param string $name    Имя без префикса и языка.
 * @param string $ru      Текст на русском.
 * @param string $en      Текст на английском.
 */
function skinlove_seed_pair( $post_id, $name, $ru, $en ) {
	carbon_set_post_meta( $post_id, "sl_{$name}_ru", $ru );
	carbon_set_post_meta( $post_id, "sl_{$name}_en", $en );
}

/**
 * Записывает двуязычную настройку сайта: sl_{$name}_ru и sl_{$name}_en.
 *
 * @param string $name Имя без префикса и языка.
 * @param string $ru   Текст на русском.
 * @param string $en   Текст на английском.
 */
function skinlove_seed_option_pair( $name, $ru, $en ) {
	carbon_set_theme_option( "sl_{$name}_ru", $ru );
	carbon_set_theme_option( "sl_{$name}_en", $en );
}

/**
 * Меню с пунктами, назначенное на локацию. Пункты пересоздаются при
 * каждом запуске, само меню ищется по названию.
 *
 * @param string $name     Название меню в админке.
 * @param string $location Локация темы.
 * @param array  $items    Пункты: [ 'page' => slug, 'title' => … ] или [ 'url' => …, 'title' => … ].
 */
function skinlove_seed_menu( $name, $location, $items ) {
	$menu    = wp_get_nav_menu_object( $name );
	$menu_id = $menu ? $menu->term_id : wp_create_nav_menu( $name );

	if ( is_wp_error( $menu_id ) ) {
		WP_CLI::error( "{$name}: " . $menu_id->get_error_message() );
	}

	foreach ( (array) wp_get_nav_menu_items( $menu_id ) as $old ) {
		wp_delete_post( $old->ID, true );
	}

	foreach ( $items as $position => $item ) {
		$data = array(
			'menu-item-title'    => $item['title'],
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position + 1,
		);

		if ( isset( $item['page'] ) ) {
			$page = get_page_by_path( $item['page'], OBJECT, 'page' );

			if ( ! $page ) {
				WP_CLI::error( "Нет страницы {$item['page']} для меню {$name}" );
			}

			$data += array(
				'menu-item-type'      => 'post_type',
				'menu-item-object'    => 'page',
				'menu-item-object-id' => $page->ID,
			);
		} else {
			$data += array(
				'menu-item-type' => 'custom',
				'menu-item-url'  => $item['url'],
			);
		}

		wp_update_nav_menu_item( $menu_id, 0, $data );
	}

	$locations              = get_theme_mod( 'nav_menu_locations', array() );
	$locations[ $location ] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );

	WP_CLI::log( "Меню «{$name}» → {$location}, пунктов: " . count( $items ) );
}

/**
 * Запись по заголовку: создаёт, если её нет, иначе обновляет.
 * Для записей без slug — отзывов, программ.
 *
 * @param string $type  Тип записи.
 * @param string $title Заголовок (русский).
 * @param array  $args  Дополнительные поля wp_insert_post: menu_order…
 * @return int ID записи.
 */
function skinlove_seed_post( $type, $title, $args = array() ) {
	$found = get_posts(
		array(
			'post_type'      => $type,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'title'          => $title,
		)
	);

	$id = wp_insert_post(
		array_merge(
			array(
				'ID'          => $found ? $found[0] : 0,
				'post_type'   => $type,
				'post_status' => 'publish',
				'post_title'  => $title,
			),
			$args
		),
		true
	);

	if ( is_wp_error( $id ) ) {
		WP_CLI::error( "{$title}: " . $id->get_error_message() );
	}

	WP_CLI::log( ( $found ? 'Обновлена' : 'Создана' ) . " запись {$type} «{$title}» (#{$id})" );

	return $id;
}

/**
 * Секции страницы целиком: конструктор перезаписывается при каждом запуске.
 *
 * @param int   $post_id  ID страницы.
 * @param array $sections Элементы sl_sections, у каждого '_type' => layout.
 */
function skinlove_seed_sections( $post_id, $sections ) {
	carbon_set_post_meta( $post_id, 'sl_sections', $sections );
	WP_CLI::log( 'Секций: ' . count( $sections ) );
}

/**
 * Элемент complex из пар: [ 'title' => [ ru, en ] ] → title_ru, title_en.
 *
 * @param array $pairs Пары по именам полей.
 * @param array $extra Поля без языка: картинки, ссылки, варианты.
 * @return array
 */
function skinlove_seed_entry( $pairs, $extra = array() ) {
	$entry = $extra;

	foreach ( $pairs as $name => $pair ) {
		$entry[ "{$name}_ru" ] = $pair[0];
		$entry[ "{$name}_en" ] = $pair[1];
	}

	return $entry;
}

/**
 * Карточки «Преимуществ» главной и страниц услуг: тексты у них общие,
 * фото у каждой страницы свои.
 *
 * @return array Пары для skinlove_seed_entry().
 */
function skinlove_seed_advantages() {
	return array(
		array(
			'title' => array( 'Индивидуальный подход', 'A personal approach' ),
			'text'  => array(
				'Мы внимательно изучаем ваш запрос, образ жизни и состояние здоровья, чтобы подобрать решение, которое подойдёт именно вам.',
				'We take time to understand your goals, lifestyle and health so we can find the solution that is right for you.',
			),
		),
		array(
			'title' => array( 'Комплексная забота', 'Comprehensive care' ),
			'text'  => array(
				'Эстетическая медицина, IV Therapy и wellness работают вместе, помогая сохранять не только красоту, но и хорошее самочувствие.',
				'Aesthetic medicine, IV Therapy and wellness work together to help you look after not only your appearance but also how you feel.',
			),
		),
		array(
			'title' => array( 'Премиальный сервис', 'Premium service' ),
			'text'  => array(
				'Внимание к деталям делает каждый визит комфортным — от тёплой встречи до сопровождения после процедуры.',
				'Attention to detail makes every visit comfortable — from a warm welcome to follow-up care after your treatment.',
			),
		),
		array(
			'title' => array( 'Медицинская экспертиза', 'Medical expertise' ),
			'text'  => array(
				'Современные методики, сертифицированные препараты и специалисты, для которых безопасность пациента всегда остаётся главным приоритетом.',
				'Modern techniques, certified products and specialists for whom patient safety always comes first.',
			),
		),
		array(
			'title' => array( 'Выездные услуги', 'At-home services' ),
			'text'  => array(
				'Если приехать в клинику неудобно, многие процедуры доступны с выездом домой, в отель, офис или другое удобное место.',
				'If visiting the clinic isn’t convenient, many treatments are available at your home, hotel, office or another place that suits you.',
			),
		),
		array(
			'title' => array( 'Естественный результат', 'Natural-looking results' ),
			'text'  => array(
				'Мы не меняем внешность, а деликатно подчёркиваем вашу индивидуальность и сохраняем естественную красоту.',
				'We don’t change how you look — we gently enhance your individuality and preserve your natural beauty.',
			),
		),
	);
}

/**
 * Строка прайса процедуры. Названия препаратов международные, поэтому
 * без EN строка получает русское название как есть. «от» в начале цены
 * становится флажком «Цена „от“» — слово выводится на языке страницы.
 *
 * @param string      $ru    Название.
 * @param string      $price Цена: 1750 AED, 2550 / 4050 AED, от 650 AED.
 * @param string|null $en    Название на английском, если отличается.
 * @return array
 */
function skinlove_seed_price( $ru, $price, $en = null ) {
	$from = str_starts_with( $price, 'от ' );

	return array(
		'_type'      => 'row',
		'name_ru'    => $ru,
		'name_en'    => $en ?? $ru,
		'price'      => $from ? substr( $price, strlen( 'от ' ) ) : $price,
		'price_from' => $from ? 'yes' : '',
	);
}

/**
 * Заголовок группы в прайсе процедуры.
 *
 * @param string $ru Заголовок.
 * @param string $en Заголовок на английском.
 * @return array
 */
function skinlove_seed_price_group( $ru, $en ) {
	return array(
		'_type'   => 'group',
		'name_ru' => $ru,
		'name_en' => $en,
	);
}

/**
 * Процедуры секции «Процедуры и цены».
 *
 * @param array $procedures Процедуры: pairs (tab, title, text, при втором
 *                          прайсе variant, variant_alt), image [файл, alt],
 *                          prices, prices_alt, button [ru, en], button_url.
 * @param array $button     Подпись кнопки по умолчанию [ru, en].
 * @return array
 */
function skinlove_seed_procedures( $procedures, $button ) {
	return array_map(
		fn( $procedure ) => skinlove_seed_entry(
			$procedure['pairs'] + array( 'button' => $procedure['button'] ?? $button ),
			array(
				'image'      => skinlove_seed_image( ...$procedure['image'] ),
				'prices'     => $procedure['prices'] ?? array(),
				'prices_alt' => $procedure['prices_alt'] ?? array(),
				'button_url' => $procedure['button_url'] ?? '',
			)
		),
		$procedures
	);
}

/**
 * Элементы с фото — этапы: пары и image [файл, alt].
 *
 * @param array $items Элементы.
 * @return array
 */
function skinlove_seed_image_items( $items ) {
	return array_map(
		fn( $item ) => skinlove_seed_entry(
			array_diff_key( $item, array( 'image' => true ) ),
			array( 'image' => skinlove_seed_image( ...$item['image'] ) )
		),
		$items
	);
}

/**
 * Карточки акций: в вёрстке страниц услуг это заглушки с одним текстом,
 * отличаются только фото.
 *
 * @param array $files Фото карточек.
 * @return array
 */
function skinlove_seed_promos( $files ) {
	$promo = array(
		'title'  => array( 'Выходные для здоровья', 'Wellness weekends' ),
		'text'   => array(
			'Приём врача по выгодной цене — без пробок, очередей и спешки. Забота о себе, которая вписывается в ваше расписание. Субботнее и воскресное утро. Никуда не нужно спешить. Самое время для того, что постоянно откладываете, — для консультации с врачом.',
			'A doctor’s appointment at a special price — no traffic, no queues, no rush. Self-care that fits into your schedule. Saturday and Sunday mornings, with nowhere to hurry. The perfect time for what you keep putting off: a consultation with a doctor.',
		),
		'button' => array( 'Подобрать процедуру', 'Find my treatment' ),
	);

	return array_map(
		fn( $file ) => skinlove_seed_entry( $promo, array( 'image' => skinlove_seed_image( $file ) ) ),
		$files
	);
}

/**
 * Карточки «Почему выбирают» страницы услуги: общие тексты, свои фото
 * {$prefix}-adv-N.jpg (alt — заголовок карточки).
 *
 * @param string $prefix Префикс фото: inject, face…
 * @param int    $count  Сколько карточек: у wellness и iv-therapy их 5.
 * @return array
 */
function skinlove_seed_service_advantages( $prefix, $count = 6 ) {
	$advantages = array_slice( skinlove_seed_advantages(), 0, $count );

	foreach ( $advantages as $index => $advantage ) {
		$advantages[ $index ] = skinlove_seed_entry(
			$advantage,
			array( 'image' => skinlove_seed_image( "{$prefix}-adv-" . ( $index + 1 ) . '.jpg', $advantage['title'][0] ) )
		);
	}

	return $advantages;
}

/**
 * Секции, одинаковые на всех страницах услуг: акции, этапы, заявка,
 * преимущества, результаты, отзывы, FAQ — в порядке вёрстки, между
 * процедурами и финальным CTA.
 *
 * @param array $args promos (файлы фото), steps_title [ru, en], steps,
 *                    request (пары title, text, button + image), advantages
 *                    (префикс фото), advantages_count, faq.
 * @return array
 */
function skinlove_seed_service_sections( $args ) {
	return array(
		skinlove_seed_entry(
			array(
				'label' => array( 'Акции', 'Offers' ),
				'title' => array( "Акции\n*и спецпредложения*", "Offers\n*and promotions*" ),
			),
			array(
				'_type'      => 'promos',
				'background' => 'beige',
				'items'      => skinlove_seed_promos( $args['promos'] ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Этапы', 'Steps' ),
				'title' => $args['steps_title'],
			),
			array(
				'_type'      => 'steps',
				'background' => 'beige',
				'items'      => skinlove_seed_image_items( $args['steps'] ),
			)
		),
		skinlove_seed_entry(
			array_diff_key( $args['request'], array( 'image' => true ) ),
			array(
				'_type' => 'program-request',
				'image' => skinlove_seed_image( ...$args['request']['image'] ),
			)
		),
		skinlove_seed_entry(
			array( 'title' => array( "Почему выбирают\n*SkinLove*", "Why clients choose\n*SkinLove*" ) ),
			array(
				'_type' => 'advantages',
				'items' => skinlove_seed_service_advantages( $args['advantages'], $args['advantages_count'] ?? 6 ),
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'Результаты', 'Results' ),
				'title' => array( "Результаты наших\n*пациентов*", "Our patients’\n*results*" ),
			),
			array( '_type' => 'results' )
		),
		skinlove_seed_entry(
			array(
				'label'  => array( 'Отзывы', 'Reviews' ),
				'title'  => array( "Нам доверяют\n*самое ценное — здоровье*", "Trusted with\n*what matters most — your health*" ),
				'button' => array( 'Больше отзывов', 'More reviews' ),
			),
			array(
				'_type'      => 'reviews',
				'blinds'     => true,
				'button_url' => '#',
			)
		),
		skinlove_seed_entry(
			array(
				'label' => array( 'FAQ', 'FAQ' ),
				'title' => array( 'Частые вопросы', 'Frequently asked questions' ),
			),
			array(
				'_type' => 'faq',
				'items' => array_map( 'skinlove_seed_entry', $args['faq'] ),
			)
		),
	);
}
