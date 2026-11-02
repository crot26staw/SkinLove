<?php
/**
 * Модалка заявки. Открывается кнопками с data-modal-open="request-modal";
 * у кнопки может быть data-lead-item — название программы, акции,
 * процедуры, — оно уходит в заявку вместе с секцией, где стоит кнопка.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_title   = skinlove_option( 'modal_title' );
$skinlove_text    = skinlove_option( 'modal_text' );
$skinlove_success = skinlove_option( 'modal_success' );
$skinlove_image   = (int) skinlove_option_raw( 'modal_image' );
?>
	<div class="modal" id="request-modal" data-modal data-lenis-prevent hidden>
		<div class="modal__backdrop" data-modal-backdrop data-modal-close></div>

		<button class="modal__close" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'close' ) ); ?>" data-modal-close>
			<?php echo skinlove_icon( 'modal-close', 'modal__close-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>

		<div class="modal__dialog" role="dialog" aria-modal="true"<?php echo $skinlove_title ? ' aria-labelledby="request-modal-title"' : ''; ?> data-modal-dialog>
			<?php if ( $skinlove_image ) : ?>
				<div class="modal__media">
					<?php
					echo wp_get_attachment_image(
						$skinlove_image,
						'large',
						false,
						array(
							'class'    => 'modal__media-image',
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
					?>
				</div>
			<?php endif; ?>

			<div class="modal__body">
				<img class="modal__logo" src="<?php skinlove_asset_url( 'img/logo-black.png' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">

				<div class="modal__content">
					<?php if ( $skinlove_title || $skinlove_text ) : ?>
						<div class="modal__head">
							<?php if ( $skinlove_title ) : ?>
								<h2 class="modal__title" id="request-modal-title"><?php echo esc_html( $skinlove_title ); ?></h2>
							<?php endif; ?>
							<?php if ( $skinlove_text ) : ?>
								<p class="modal__text"><?php echo esc_html( $skinlove_text ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/lead-form', null, array( 'form' => 'modal', 'success' => $skinlove_success ) ); ?>
				</div>
			</div>
		</div>
	</div>
