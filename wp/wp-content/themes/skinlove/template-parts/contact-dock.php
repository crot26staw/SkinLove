<?php
/**
 * Плавающие кнопки связи: WhatsApp и звонок на первый номер.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_whatsapp = skinlove_whatsapp_url();
$skinlove_phone    = skinlove_phones()[0] ?? null;

if ( ! $skinlove_whatsapp && ! $skinlove_phone ) {
	return;
}
?>
	<div class="contact-dock" data-contact-dock>
		<ul class="contact-dock__list" id="contact-dock-list" data-contact-dock-list hidden>
			<?php if ( $skinlove_whatsapp ) : ?>
				<li><a class="contact-dock__button" href="<?php echo esc_url( $skinlove_whatsapp ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( skinlove_t( 'whatsapp' ) ); ?>" data-contact-dock-item><?php echo skinlove_icon( 'dock-whatsapp', 'contact-dock__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
			<?php endif; ?>
			<?php if ( $skinlove_phone ) : ?>
				<li><a class="contact-dock__button" href="<?php echo esc_url( $skinlove_phone['href'] ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'call' ) ); ?>" data-contact-dock-item><?php echo skinlove_icon( 'phone', 'contact-dock__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a></li>
			<?php endif; ?>
		</ul>
		<button class="contact-dock__button contact-dock__toggle" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'dock_toggle' ) ); ?>" aria-expanded="false" aria-controls="contact-dock-list" data-contact-dock-toggle>
			<?php echo skinlove_icon( 'phone', 'contact-dock__icon contact-dock__icon--phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<?php echo skinlove_icon( 'close', 'contact-dock__icon contact-dock__icon--close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
	</div>
