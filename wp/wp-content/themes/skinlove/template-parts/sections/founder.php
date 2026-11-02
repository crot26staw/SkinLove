<?php
/**
 * Секция «Основатель»: заголовок и вводный абзац, портрет, подпись,
 * цитата с фото и имя. Совсем без контента не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section     = $args['section'];
$skinlove_title       = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead        = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_since       = skinlove_entry( $skinlove_section, 'since' );
$skinlove_quote       = skinlove_entry( $skinlove_section, 'quote' );
$skinlove_name        = skinlove_entry( $skinlove_section, 'name' );
$skinlove_image       = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_quote_image = (int) ( $skinlove_section['quote_image'] ?? 0 );

if ( ! $skinlove_title && ! $skinlove_lead && ! $skinlove_since && ! $skinlove_quote && ! $skinlove_name && ! $skinlove_image ) {
	return;
}
?>
<section class="section section--beige founder" data-section="founder">
	<span class="founder__line rule" data-rule aria-hidden="true"></span>

	<?php if ( $skinlove_title || $skinlove_lead ) : ?>
		<div class="founder__head">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="founder__title"><?php echo esc_html( $skinlove_title ); ?></h2>
			<?php endif; ?>
			<?php if ( $skinlove_lead ) : ?>
				<p class="founder__lead"><?php echo esc_html( $skinlove_lead ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_image ) : ?>
		<div class="founder__portrait">
			<?php
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => 'founder__portrait-image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>

	<div class="founder__foot">
		<?php if ( $skinlove_since || $skinlove_quote ) : ?>
			<div class="founder__row">
				<?php if ( $skinlove_since ) : ?>
					<p class="founder__since"><?php echo esc_html( $skinlove_since ); ?></p>
				<?php endif; ?>

				<?php if ( $skinlove_quote ) : ?>
					<figure class="founder__quote">
						<?php if ( $skinlove_quote_image ) : ?>
							<div class="founder__quote-media">
								<?php
								echo wp_get_attachment_image(
									$skinlove_quote_image,
									'full',
									false,
									array(
										'class'    => 'founder__quote-image',
										'alt'      => '',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							</div>
						<?php endif; ?>
						<blockquote class="founder__quote-text">
							<p><?php echo esc_html( $skinlove_quote ); ?></p>
						</blockquote>
					</figure>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_name ) : ?>
			<p class="founder__name"><?php echo esc_html( $skinlove_name ); ?></p>
		<?php endif; ?>
	</div>
</section>
