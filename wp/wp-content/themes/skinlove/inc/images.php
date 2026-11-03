<?php
/**
 * Атрибут sizes у картинок из медиатеки.
 *
 * WordPress ставит sizes «на всю ширину до ширины файла», и браузер качает
 * для карточки на треть экрана файл под весь экран. Здесь — реальная ширина
 * картинки на каждом брейкпоинте вёрстки, по классу, который картинке даёт
 * шаблон. Значения сняты замером в браузере на всех страницах на ширинах
 * 320–2560 и высотах окна 640–1536 и округлены вверх: sizes не меньше той
 * ширины, что картинка занимает (при object-fit: cover — вместе с обрезкой).
 * Поменялась раскладка секции — значение надо переснять.
 *
 * Картинок на весь экран (hero, кадр, атмосфера, cta, забота) здесь нет:
 * им и так нужен файл почти максимального размера, стандартный sizes им
 * подходит.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Класс картинки => sizes.
 *
 * @return array<string,string>
 */
function skinlove_image_sizes() {
	return array(
		'service-card__image'       => '(min-width: 1920px) 711px, (min-width: 1024px) calc(23vw + 239px), (min-width: 768px) 498px, calc(81vw + 96px)',
		'advantage__image'          => '(min-width: 1920px) 769px, (min-width: 1024px) calc(25vw + 257px), (min-width: 768px) 480px, calc(60vw + 288px)',
		'promo-card__image'         => '(min-width: 1920px) 688px, (min-width: 1024px) calc(22vw + 233px), (min-width: 768px) 400px, 345px',
		'steps__image'              => '(min-width: 1920px) 888px, (min-width: 1024px) calc(29vw + 296px), 533px',
		'result-card__image'        => '(min-width: 1920px) 445px, (min-width: 1024px) calc(15vw + 144px), (min-width: 768px) 349px, 269px',
		/* На десктопе портрет обрезается по высоте секции: в высоком окне
		   ему нужно больше, чем по ширине. */
		'founder__portrait-image'   => '(min-width: 1024px) and (max-aspect-ratio: 3/2) 63vh, (min-width: 1024px) 41vw, (min-width: 768px) calc(51vw - 22px), calc(92vw + 46px)',
		'founder__quote-image'      => '(min-width: 1920px) 177px, (min-width: 1024px) calc(6vw + 57px), (min-width: 768px) calc(51vw - 32px), calc(92vw + 36px)',
		'philosophy__image'         => '(min-width: 1920px) 1413px, (min-width: 1024px) calc(50vw + 398px), (min-width: 768px) calc(50vw + 99px), calc(178vw - 36px)',
		'program-request__image'    => '(min-width: 1920px) 1599px, (min-width: 1024px) calc(51vw + 544px), (min-width: 768px) calc(53vw + 481px), calc(53vw + 364px)',
		'outcall-hero__image'       => '(min-width: 1920px) calc(50vw - 50px), (min-width: 1024px) calc(49vw - 20px), calc(100vw - 20px)',
		'atmosphere__preview-image' => '(min-width: 1920px) 455px, (min-width: 1024px) calc(15vw + 150px), (min-width: 768px) 256px, calc(100vw - 20px)',
	);
}

/**
 * Подставляет sizes по классу картинки и снимает приставку «auto,», которую
 * WordPress ставит ленивым картинкам до этого фильтра. По ней браузер берёт
 * ширину рамки картинки и не учитывает обрезку object-fit: cover —
 * обрезанным картинкам (философия, заявка, процедуры) доставался файл
 * меньше нужного. Без неё работает явный sizes, а он не меньше рамки.
 *
 * @param array $attr Атрибуты картинки.
 * @return array
 */
function skinlove_image_sizes_attr( $attr ) {
	if ( empty( $attr['sizes'] ) ) {
		return $attr;
	}

	$attr['sizes'] = preg_replace( '/^auto,\s*/', '', $attr['sizes'] );

	if ( empty( $attr['class'] ) ) {
		return $attr;
	}

	$sizes = skinlove_image_sizes();

	foreach ( explode( ' ', $attr['class'] ) as $class ) {
		if ( isset( $sizes[ $class ] ) ) {
			$attr['sizes'] = $sizes[ $class ];
			break;
		}
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'skinlove_image_sizes_attr' );

/**
 * Нарезки PNG-загрузок — в WebP. Фото в PNG (портрет основательницы с
 * прозрачностью) WordPress режет без сжатия: нарезка 768 весила 540 КБ,
 * в WebP — 80 КБ. JPEG остаются JPEG: на зернистых фото WebP при том же
 * качестве не легче.
 *
 * @param array $formats Исходный MIME => MIME нарезок.
 * @return array
 */
function skinlove_image_output_format( $formats ) {
	$formats['image/png'] = 'image/webp';

	return $formats;
}
add_filter( 'image_editor_output_format', 'skinlove_image_output_format' );

/**
 * Качество WebP. При 94 нарезка портрета поверх фона секции по VMAF
 * не хуже JPEG-нарезок сайта (качество 82); стандартные 86 — заметно ниже.
 *
 * @param int    $quality   Качество.
 * @param string $mime_type MIME нарезки.
 * @return int
 */
function skinlove_webp_quality( $quality, $mime_type ) {
	return 'image/webp' === $mime_type ? 94 : $quality;
}
add_filter( 'wp_editor_set_quality', 'skinlove_webp_quality', 10, 2 );

/**
 * ID картинок в секциях страницы: поля image, image_left, image_right,
 * quote_image на любой глубине (картинки бывают и в элементах complex).
 *
 * @param array $sections Секции из carbon_get_post_meta( …, 'sl_sections' ).
 * @return int[]
 */
function skinlove_image_ids( $sections ) {
	$ids = array();

	array_walk_recursive(
		$sections,
		function ( $value, $key ) use ( &$ids ) {
			if ( in_array( $key, array( 'image', 'image_left', 'image_right', 'quote_image' ), true ) && (int) $value ) {
				$ids[] = (int) $value;
			}
		}
	);

	return $ids;
}

/**
 * Загружает записи и мету вложений одним запросом на всё: без этого
 * wp_get_attachment_image() ходит в базу за каждой картинкой отдельно.
 *
 * @param int[] $ids ID вложений.
 */
function skinlove_prime_attachments( $ids ) {
	$ids = array_unique( array_filter( array_map( 'intval', $ids ) ) );

	if ( $ids ) {
		_prime_post_caches( $ids, false, true );
	}
}
