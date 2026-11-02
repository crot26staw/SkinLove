<?php
/**
 * Секция «Первый экран услуги»: фото фоном, крупный текст, текст с кнопкой
 * заявки и заголовок, строки которого разъезжаются в стороны (сцена spread).
 * Заголовок из одной строки стоит по центру. Без заголовка не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead    = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_button  = skinlove_entry( $skinlove_section, 'button' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );

if ( ! $skinlove_title ) {
	return;
}

$skinlove_heading = skinlove_heading( $skinlove_title, array( 'data-spread-left', 'data-spread-right' ) );
$skinlove_single  = 1 === substr_count( $skinlove_heading, '<span' );

if ( $skinlove_single ) {
	$skinlove_heading = skinlove_heading( $skinlove_title );
}
?>
<section class="service-hero" data-section="service-hero" data-spread<?php echo empty( $skinlove_section['header_plain'] ) ? '' : ' data-header-plain'; ?>>
	<div class="service-hero__decor" aria-hidden="true">
		<?php
		if ( $skinlove_image ) {
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => 'service-hero__decor-media',
					'alt'      => '',
					'loading'  => false,
					'decoding' => 'async',
				)
			);
		}
		?>
	</div>

	<div class="service-hero__veil" data-spread-veil aria-hidden="true"></div>

	<div class="service-hero__inner">
		<?php if ( $skinlove_lead || $skinlove_text || $skinlove_button ) : ?>
			<div class="service-hero__row">
				<?php if ( $skinlove_lead ) : ?>
					<p class="service-hero__lead" data-spread-left><?php echo esc_html( $skinlove_lead ); ?></p>
				<?php endif; ?>

				<?php if ( $skinlove_text || $skinlove_button ) : ?>
					<div class="service-hero__aside" data-spread-right>
						<?php if ( $skinlove_text ) : ?>
							<p class="service-hero__text"><?php echo esc_html( $skinlove_text ); ?></p>
						<?php endif; ?>

						<?php echo skinlove_button( $skinlove_button, '#request-modal', 'button--light service-hero__button', 'data-modal-open="request-modal"' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<h1 class="heading heading--h1 heading--row service-hero__title<?php echo $skinlove_single ? ' service-hero__title--center' : ''; ?>">
			<?php echo $skinlove_heading; // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</h1>
	</div>
</section>
