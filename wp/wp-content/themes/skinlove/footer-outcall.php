<?php
/**
 * Подвал лендинга «Выездные услуги» (решение 24): логотип без ссылки,
 * контакты и WhatsApp, кнопка заявки и бейдж. Без жалюзи, карты сайта
 * и направлений.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;
?>
	<footer class="footer footer--outcall" data-footer>
		<div class="footer__columns">
			<div class="footer__column footer__column--logo">
				<span class="footer__logo">
					<img class="footer__logo-image" src="<?php skinlove_asset_url( 'img/logo-black.png' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
				</span>
			</div>

			<?php get_template_part( 'template-parts/footer-contacts', null, array( 'socials' => false ) ); ?>

			<div class="footer__column footer__column--end">
				<?php echo skinlove_pill_button( skinlove_t( 'outcall_book' ), '#request-modal', '', 'data-modal-open="request-modal" data-lead-section="footer"' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

				<img class="footer__badge" src="<?php skinlove_asset_url( 'img/alfa.svg' ); ?>" width="149" height="20" alt="<?php echo esc_attr( skinlove_t( 'badge_alfa' ) ); ?>" decoding="async">
			</div>
		</div>
	</footer>

	<?php get_template_part( 'template-parts/document-end' ); ?>
