<?php
/**
 * Секция «О клинике» на главной: надзаголовок, заголовок, вводный абзац,
 * текст и кнопка.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead    = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
?>
<section class="section section--light about" data-section="about">
	<div class="about__inner">
		<?php if ( $skinlove_label ) : ?>
			<p class="descriptor about__label"><?php echo esc_html( $skinlove_label ); ?></p>
		<?php endif; ?>

		<?php if ( $skinlove_title ) : ?>
			<h2 class="heading heading--h2 about__title" data-heading-fill>
				<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h2>
		<?php endif; ?>

		<div class="about__body rule" data-rule>
			<?php echo skinlove_icon( 'plus', 'about__marker', 'data-rule-marker' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

			<?php if ( $skinlove_lead ) : ?>
				<p class="about__lead"><?php echo esc_html( $skinlove_lead ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<div class="about__text content">
					<?php echo skinlove_paragraphs( $skinlove_text ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</div>
			<?php endif; ?>

			<?php echo skinlove_entry_button( $skinlove_section, 'about__button' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
