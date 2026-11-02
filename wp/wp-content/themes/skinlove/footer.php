<?php
/**
 * Подвал сайта, плавающие кнопки связи и модалка заявки.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_directions = skinlove_directions();
$skinlove_preview    = skinlove_current_direction( $skinlove_directions );
$skinlove_phones     = skinlove_phones();
$skinlove_email      = skinlove_option_raw( 'email' );
$skinlove_address    = skinlove_option( 'address' );
$skinlove_hours      = skinlove_option( 'hours' );
$skinlove_socials    = skinlove_socials();
$skinlove_whatsapp   = skinlove_whatsapp_url();
?>
	<footer class="footer" data-footer>
		<div class="blinds" aria-hidden="true">
			<span class="blinds__bar" data-blind></span>
			<span class="blinds__bar" data-blind></span>
			<span class="blinds__bar" data-blind></span>
			<span class="blinds__bar" data-blind></span>
			<span class="blinds__bar" data-blind></span>
		</div>

		<div class="footer__columns">
			<nav class="footer__column footer__column--sitemap" aria-labelledby="footer-sitemap">
				<p class="footer__label" id="footer-sitemap"><?php echo esc_html( skinlove_t( 'sitemap' ) ); ?></p>

				<?php skinlove_nav_menu( 'sitemap', 'menu footer__menu' ); ?>
			</nav>

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

			<?php if ( $skinlove_directions ) : ?>
				<nav class="footer__column footer__column--directions" aria-labelledby="footer-directions">
					<p class="footer__label" id="footer-directions"><?php echo esc_html( skinlove_t( 'directions' ) ); ?></p>

					<div class="footer__preview">
						<img class="footer__preview-image" data-footer-preview src="<?php echo esc_url( skinlove_direction_image_url( $skinlove_preview ) ); ?>" width="720" height="1280" alt="" loading="lazy" decoding="async">
					</div>

					<ul class="menu footer__menu footer__menu--directions" data-footer-directions>
						<?php foreach ( $skinlove_directions as $skinlove_direction ) : ?>
							<li class="menu-item<?php echo $skinlove_direction['current'] ? ' current-menu-item' : ''; ?>"><a href="<?php echo esc_url( $skinlove_direction['url'] ); ?>" data-footer-direction data-image="<?php echo esc_url( skinlove_direction_image_url( $skinlove_direction ) ); ?>"<?php echo $skinlove_direction['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $skinlove_direction['title'] ); ?></a></li>
						<?php endforeach; ?>
					</ul>

					<div class="footer__hearts" aria-hidden="true">
						<?php foreach ( $skinlove_directions as $skinlove_direction ) : ?>
							<?php echo skinlove_icon( 'heart', $skinlove_direction === $skinlove_preview ? 'footer__heart footer__heart--current' : 'footer__heart', 'data-footer-heart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<?php endforeach; ?>
					</div>
				</nav>
			<?php endif; ?>

			<a class="footer__cta" href="#request-modal" data-modal-open="request-modal" data-lead-section="footer">
				<span class="footer__cta-hearts" aria-hidden="true">
					<?php echo skinlove_icon( 'heart', 'footer__cta-heart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php echo skinlove_icon( 'heart', 'footer__cta-heart footer__cta-heart--main' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php echo skinlove_icon( 'heart', 'footer__cta-heart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</span>
				<span class="footer__cta-label"><?php echo esc_html( skinlove_t( 'book' ) ); ?></span>
			</a>
		</div>

		<div class="footer__bottom">
			<div class="footer__brand">
				<a class="footer__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'logo_home' ) ); ?>">
					<img class="footer__logo-image" src="<?php skinlove_asset_url( 'img/logo-black.png' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
				</a>

				<img class="footer__badge" src="<?php skinlove_asset_url( 'img/alfa.svg' ); ?>" width="149" height="20" alt="<?php echo esc_attr( skinlove_t( 'badge_alfa' ) ); ?>" decoding="async">
			</div>
		</div>
	</footer>

	<?php get_template_part( 'template-parts/contact-dock' ); ?>
	<?php get_template_part( 'template-parts/request-modal' ); ?>

<?php wp_footer(); ?>
</body>
</html>
