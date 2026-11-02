<?php
/**
 * Секция «Заявка с формой»: заголовок, текст, форма «имя + телефон» и фото.
 * Форма выводится всегда — секция без текстов это просто форма заявки.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );
?>
<section class="section section--beige program-request" data-section="program-request">
	<div class="program-request__body">
		<div class="program-request__inner">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 program-request__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<p class="program-request__text"><?php echo esc_html( $skinlove_text ); ?></p>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/lead-form',
				null,
				array(
					'form'    => 'program-request',
					'section' => 'program-request',
					'class'   => 'program-request__form',
					'button'  => skinlove_entry( $skinlove_section, 'button' ),
				)
			);
			?>
		</div>
	</div>

	<?php if ( $skinlove_image ) : ?>
		<div class="program-request__media">
			<?php
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => 'program-request__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>
</section>
