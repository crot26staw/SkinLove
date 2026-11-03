<?php
/**
 * Секция «Выездные: забота в деталях»: фото, заголовок, текст и карточки.
 * Без заголовка и карточек не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title' => $skinlove_item_title,
			'text'  => skinlove_entry( $skinlove_item, 'text' ),
		);
	}
}

if ( ! $skinlove_title && ! $skinlove_items ) {
	return;
}
?>
<section class="section care" data-section="care">
	<?php if ( $skinlove_image ) : ?>
		<div class="care__media" aria-hidden="true">
			<?php
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => 'care__image',
					'alt'      => '',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_title || $skinlove_text ) : ?>
		<div class="care__head">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2-sm care__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<p class="care__text"><?php echo esc_html( $skinlove_text ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_items ) : ?>
		<ul class="care__list">
			<?php foreach ( $skinlove_items as $skinlove_item ) : ?>
				<li class="care__card">
					<h3 class="care__card-title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
					<?php if ( $skinlove_item['text'] ) : ?>
						<p class="care__card-text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
