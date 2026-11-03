<?php
/**
 * Подвал сайта, плавающие кнопки связи и модалка заявки.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_directions = skinlove_directions();
$skinlove_preview    = skinlove_current_direction( $skinlove_directions );
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

			<?php get_template_part( 'template-parts/footer-contacts' ); ?>

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

				<?php get_template_part( 'template-parts/footer-credits' ); ?>
			</div>
		</div>
	</footer>

	<?php get_template_part( 'template-parts/document-end' ); ?>
