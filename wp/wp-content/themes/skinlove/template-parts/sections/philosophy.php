<?php
/**
 * Секция «Философия»: заголовок, широкое фото с цитатой и две колонки
 * с фото, в правой — текст. Колонки выводятся всегда, даже без фото:
 * они держат раскладку ряда. Совсем без контента секция не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_quote   = skinlove_entry( $skinlove_section, 'quote' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_images  = array(
	'wide'      => (int) ( $skinlove_section['image'] ?? 0 ),
	'square'    => (int) ( $skinlove_section['image_left'] ?? 0 ),
	'landscape' => (int) ( $skinlove_section['image_right'] ?? 0 ),
);

if ( ! array_filter( $skinlove_images ) && ! $skinlove_label && ! $skinlove_title && ! $skinlove_quote && ! $skinlove_text ) {
	return;
}

/**
 * Фото в обёртке с пропорциями из вёрстки.
 *
 * @param int    $id      ID вложения.
 * @param string $variant Модификатор обёртки.
 */
$skinlove_figure = static function ( $id, $variant ) {
	if ( ! $id ) {
		return;
	}

	printf(
		'<div class="philosophy__figure philosophy__figure--%s">%s</div>',
		esc_attr( $variant ),
		wp_get_attachment_image(
			$id,
			'full',
			false,
			array(
				'class'    => 'philosophy__image',
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		)
	);
};
?>
<section class="section section--light philosophy" data-section="philosophy">
	<?php if ( $skinlove_label || $skinlove_title ) : ?>
		<div class="philosophy__head">
			<?php if ( $skinlove_label ) : ?>
				<p class="descriptor philosophy__label"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 heading--row philosophy__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_images['wide'] || $skinlove_quote ) : ?>
		<div class="philosophy__statement">
			<?php $skinlove_figure( $skinlove_images['wide'], 'wide' ); ?>

			<?php if ( $skinlove_quote ) : ?>
				<p class="philosophy__quote"><?php echo esc_html( $skinlove_quote ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_images['square'] || $skinlove_images['landscape'] || $skinlove_text ) : ?>
		<div class="philosophy__row">
			<div class="philosophy__column philosophy__column--offset">
				<?php $skinlove_figure( $skinlove_images['square'], 'square' ); ?>
			</div>

			<div class="philosophy__column">
				<?php $skinlove_figure( $skinlove_images['landscape'], 'landscape' ); ?>

				<?php if ( $skinlove_text ) : ?>
					<div class="philosophy__text content">
						<?php echo skinlove_paragraphs( $skinlove_text ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
