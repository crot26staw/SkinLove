<?php
/**
 * Колонка контактов подвала: телефоны и почта, адрес и часы, соцсети.
 * Группа без значений не выводится.
 *
 * Аргументы:
 * - socials — выводить ссылки на соцсети; в подвале Outcall только
 *   WhatsApp, как в макете.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_phones   = skinlove_phones();
$skinlove_email    = skinlove_option_raw( 'email' );
$skinlove_address  = skinlove_option( 'address' );
$skinlove_hours    = skinlove_option( 'hours' );
$skinlove_socials  = ( $args['socials'] ?? true ) ? skinlove_socials() : array();
$skinlove_whatsapp = skinlove_whatsapp_url();
?>
	<div class="footer__column footer__column--contacts">
		<?php if ( $skinlove_phones || $skinlove_email ) : ?>
			<div class="footer__group footer__group--contacts">
				<p class="footer__label" id="footer-contacts"><?php echo esc_html( skinlove_t( 'contacts' ) ); ?></p>

				<ul class="menu footer__menu" aria-labelledby="footer-contacts">
					<?php foreach ( $skinlove_phones as $skinlove_phone ) : ?>
						<li class="menu-item"><a href="<?php echo esc_url( $skinlove_phone['href'] ); ?>"><?php echo esc_html( $skinlove_phone['label'] ); ?></a></li>
					<?php endforeach; ?>
					<?php if ( $skinlove_email ) : ?>
						<li class="menu-item"><a href="<?php echo esc_url( 'mailto:' . $skinlove_email ); ?>"><?php echo esc_html( $skinlove_email ); ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_address || $skinlove_hours ) : ?>
			<div class="footer__group footer__group--address">
				<p class="footer__label" id="footer-address"><?php echo esc_html( skinlove_t( 'address' ) ); ?></p>

				<ul class="menu footer__menu" aria-labelledby="footer-address">
					<?php if ( $skinlove_address ) : ?>
						<li class="menu-item"><a href="<?php echo esc_url( skinlove_address_url( $skinlove_address ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $skinlove_address ); ?></a></li>
					<?php endif; ?>
					<?php if ( $skinlove_hours ) : ?>
						<li class="menu-item"><span class="footer__menu-text"><?php echo esc_html( $skinlove_hours ); ?></span></li>
					<?php endif; ?>
				</ul>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_socials || $skinlove_whatsapp ) : ?>
			<div class="footer__group footer__group--socials">
				<p class="footer__label" id="footer-socials"><?php echo esc_html( skinlove_t( 'socials' ) ); ?></p>

				<div class="footer__socials" aria-labelledby="footer-socials" role="group">
					<?php foreach ( $skinlove_socials as $skinlove_key => $skinlove_url ) : ?>
						<a class="social-button" href="<?php echo esc_url( $skinlove_url ); ?>" aria-label="<?php echo esc_attr( skinlove_t( $skinlove_key ) ); ?>" target="_blank" rel="noopener"><?php echo skinlove_icon( 'social', 'social-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endforeach; ?>
					<?php if ( $skinlove_whatsapp ) : ?>
						<a class="social-button social-button--filled" href="<?php echo esc_url( $skinlove_whatsapp ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'whatsapp' ) ); ?>" target="_blank" rel="noopener"><?php echo skinlove_icon( 'whatsapp', 'social-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
