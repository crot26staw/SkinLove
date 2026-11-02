<?php
/**
 * Секция «Рамка с фото» (frame--inset): фото фоном карточки, заголовок,
 * вводный абзац и текст; без текстов не выводится. Пара к frame-reel — оба layout'а выводят
 * data-section="frame" (решение 16).
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead    = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );

if ( ! $skinlove_title && ! $skinlove_lead && ! $skinlove_text ) {
	return;
}
?>
<section class="section section--light frame frame--inset" data-section="frame">
	<div class="frame__card">
		<div class="frame__decor" aria-hidden="true">
			<?php
			if ( $skinlove_image ) {
				echo wp_get_attachment_image(
					$skinlove_image,
					'full',
					false,
					array(
						'class'    => 'frame__image',
						'alt'      => '',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
			}
			?>
		</div>

		<div class="frame__lines" aria-hidden="true">
			<?php echo skinlove_icon( 'plus', 'frame__marker', 'data-rule-marker' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>

		<?php if ( $skinlove_title ) : ?>
			<h2 class="heading heading--h2 frame__title" data-heading-fill>
				<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h2>
		<?php endif; ?>

		<div class="frame__foot">
			<?php if ( $skinlove_lead || $skinlove_text ) : ?>
				<div class="frame__texts">
					<?php if ( $skinlove_lead ) : ?>
						<p class="frame__lead"><?php echo esc_html( $skinlove_lead ); ?></p>
					<?php endif; ?>
					<?php if ( $skinlove_text ) : ?>
						<div class="frame__text content">
							<?php echo skinlove_paragraphs( $skinlove_text ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/frame-hearts' ); ?>
		</div>
	</div>
</section>
