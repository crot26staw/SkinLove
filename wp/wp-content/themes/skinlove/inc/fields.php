<?php
/**
 * Хелперы полей админки и разметки из них.
 *
 * У каждого текстового поля пара RU / EN, рядом по половине ширины.
 * Поля записей и настроек получают префикс sl_, поля внутри complex — нет.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

use Carbon_Fields\Field;

/**
 * Пара полей RU / EN для записи или настроек: sl_{$name}_ru, sl_{$name}_en.
 *
 * @param string        $type  Тип поля Carbon: text, textarea, rich_text…
 * @param string        $name  Имя без префикса и языка.
 * @param string        $label Подпись без языка.
 * @param callable|null $setup Донастройка каждого из двух полей (подсказка, высота).
 * @return Field[]
 */
function skinlove_pair( $type, $name, $label, $setup = null ) {
	return skinlove_make_pair( $type, "sl_{$name}", $label, $setup );
}

/**
 * Пара полей RU / EN внутри complex: {$name}_ru, {$name}_en.
 *
 * @param string        $type  Тип поля Carbon.
 * @param string        $name  Имя без языка.
 * @param string        $label Подпись без языка.
 * @param callable|null $setup Донастройка каждого из двух полей.
 * @return Field[]
 */
function skinlove_sub_pair( $type, $name, $label, $setup = null ) {
	return skinlove_make_pair( $type, $name, $label, $setup );
}

/**
 * Общая часть skinlove_pair() и skinlove_sub_pair().
 *
 * @param string        $type  Тип поля Carbon.
 * @param string        $name  Имя без языка.
 * @param string        $label Подпись без языка.
 * @param callable|null $setup Донастройка поля.
 * @return Field[]
 */
function skinlove_make_pair( $type, $name, $label, $setup ) {
	$fields = array();

	foreach ( array( 'ru' => 'RU', 'en' => 'EN' ) as $lang => $suffix ) {
		$field = Field::make( $type, "{$name}_{$lang}", "{$label} ({$suffix})" )->set_width( 50 );

		if ( $setup ) {
			$setup( $field );
		}

		$fields[] = $field;
	}

	return $fields;
}

/**
 * Подсказка к полю заголовка с акцентом.
 *
 * @param Field $field Поле.
 */
function skinlove_heading_help( $field ) {
	$field->set_rows( 2 )->set_help_text( 'Акцентная строка — в *звёздочках*, перенос строки — новая строка.' );
}

/**
 * В боковой колонке поля пары идут друг под другом.
 *
 * @param Field $field Поле.
 */
function skinlove_full_width( $field ) {
	$field->set_width( 100 );
}

/**
 * Короткий абзац: textarea в три строки.
 *
 * @param Field $field Поле.
 */
function skinlove_short_text( $field ) {
	$field->set_rows( 3 );
}

/**
 * Разметка заголовка с акцентом из поля админки.
 *
 * Каждая строка — span.heading__line, фрагмент в *звёздочках* — отдельная
 * строка с модификатором --accent. Пример: «Начните *с заботы* о себе» —
 * три строки, средняя акцентная. Текст экранируется до разбора.
 *
 * @param string          $text Значение поля.
 * @param string|string[] $attr Атрибут для каждой строки (например, data-hero-intro) — только из шаблона.
 *                              Список атрибутов раздаётся строкам по кругу: левая, правая, левая…
 * @return string Готовая разметка или пустая строка.
 */
function skinlove_heading( $text, $attr = '' ) {
	$lines = array();
	$attrs = array_map( fn( $item ) => $item ? ' ' . $item : '', (array) $attr );

	foreach ( preg_split( '/\R/u', esc_html( $text ) ) as $row ) {
		foreach ( preg_split( '/(\*[^*]+\*)/u', $row, -1, PREG_SPLIT_DELIM_CAPTURE ) as $part ) {
			$accent = str_starts_with( $part, '*' ) && str_ends_with( $part, '*' ) && strlen( $part ) > 2;
			$part   = trim( $accent ? substr( $part, 1, -1 ) : $part );

			if ( '' === $part ) {
				continue;
			}

			$class   = $accent ? 'heading__line heading__line--accent' : 'heading__line';
			$attr    = $attrs[ count( $lines ) % count( $attrs ) ];
			$lines[] = "<span class=\"{$class}\"{$attr}>{$part}</span>";
		}
	}

	return implode( "\n", $lines );
}

/**
 * Поле ссылки кнопки: подпись парой, адрес один на оба языка.
 *
 * @param string $name  Имя без языка, например button → button_ru, button_en, button_url.
 * @param string $label Подпись кнопки в админке.
 * @return Field[]
 */
function skinlove_button_fields( $name = 'button', $label = 'Кнопка' ) {
	return array_merge(
		skinlove_sub_pair( 'text', $name, $label ),
		array(
			Field::make( 'text', "{$name}_url", "{$label}: ссылка" )
				->set_help_text( 'Страница сайта — путь от корня: /about/ (в английской версии станет /en/about/). Якорь — #services. Внешняя — полный адрес.' ),
		)
	);
}

/**
 * Адрес из поля ссылки. Путь от корня получает адрес сайта и префикс
 * языка, якорь и полный адрес остаются как есть.
 *
 * @param string $value Значение поля.
 * @return string
 */
function skinlove_url( $value ) {
	$value = skinlove_clean( $value );

	if ( str_starts_with( $value, '/' ) && ! str_starts_with( $value, '//' ) ) {
		return home_url( $value );
	}

	return $value;
}

/**
 * Кнопка с сердцем. Без подписи или адреса не выводится.
 *
 * @param string $label Подпись.
 * @param string $url   Адрес из skinlove_url().
 * @param string $class Классы после button.
 * @param string $attr  Дополнительные атрибуты — только из шаблона.
 * @return string
 */
function skinlove_button( $label, $url, $class = '', $attr = '' ) {
	if ( '' === $label || '' === $url ) {
		return '';
	}

	return sprintf(
		'<a class="%s" data-button%s href="%s"><span class="button__label">%s</span>%s</a>',
		esc_attr( trim( 'button ' . $class ) ),
		$attr ? ' ' . $attr : '',
		esc_url( $url ),
		esc_html( $label ),
		skinlove_icon( 'heart', 'button__icon' )
	);
}

/**
 * Кнопка-пилюля: сердце перед подписью (Outcall, «Показать ещё»).
 * Без подписи или адреса — пустая строка.
 *
 * @param string $label Подпись.
 * @param string $url   Адрес.
 * @param string $class Классы после button button--pill.
 * @param string $attr  Дополнительные атрибуты — только из шаблона.
 * @return string
 */
function skinlove_pill_button( $label, $url, $class = '', $attr = '' ) {
	if ( '' === $label || '' === $url ) {
		return '';
	}

	return sprintf(
		'<a class="%s" href="%s"%s>%s<span class="button__label">%s</span></a>',
		esc_attr( trim( 'button button--pill ' . $class ) ),
		esc_url( $url ),
		$attr ? ' ' . $attr : '',
		skinlove_icon( 'heart-filter', 'button__icon' ),
		esc_html( $label )
	);
}

/**
 * Кнопка из полей skinlove_button_fields() элемента complex.
 *
 * @param array  $entry Элемент complex.
 * @param string $class Классы после button.
 * @param string $attr  Дополнительные атрибуты — только из шаблона.
 * @param string $name  Имя поля.
 * @return string
 */
function skinlove_entry_button( $entry, $class = '', $attr = '', $name = 'button' ) {
	return skinlove_button( skinlove_entry( $entry, $name ), skinlove_url( $entry[ "{$name}_url" ] ?? '' ), $class, $attr );
}

/**
 * Абзацы из textarea: пустая строка между ними — новый абзац,
 * одиночный перенос — <br>.
 *
 * @param string $text Значение поля.
 * @return string
 */
function skinlove_paragraphs( $text ) {
	return '' === $text ? '' : wpautop( esc_html( $text ) );
}
