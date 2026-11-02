<?php
/**
 * Строка прайса процедуры: «название + цена» или заголовок группы.
 *
 * Аргументы: row — элемент complex prices (_type row | group).
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_row  = $args['row'];
$skinlove_name = skinlove_entry( $skinlove_row, 'name' );

if ( ! $skinlove_name ) {
	return;
}

if ( 'group' === ( $skinlove_row['_type'] ?? '' ) ) : ?>
	<li class="procedure-panel__price-group"><?php echo esc_html( $skinlove_name ); ?></li>
<?php else : ?>
	<li class="procedure-panel__price">
		<span class="procedure-panel__price-name"><?php echo esc_html( $skinlove_name ); ?></span>
		<span class="procedure-panel__price-value"><?php echo esc_html( skinlove_clean( $skinlove_row['price'] ?? '' ) ); ?></span>
	</li>
	<?php
endif;
