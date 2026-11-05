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
				<a class="footer__logo" href="<?php echo esc_url( get_permalink( get_queried_object_id() ) ); ?>">
					<img class="footer__logo-image" src="<?php skinlove_asset_url( 'img/logo-black.webp' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
				</a>
			</div>

			<?php get_template_part( 'template-parts/footer-contacts', null, array( 'socials' => false ) ); ?>

			<div class="footer__column footer__column--end">
				<?php echo skinlove_pill_button( skinlove_t( 'outcall_book' ), '#request-modal', '', 'data-modal-open="request-modal" data-lead-section="footer"' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

				<?php get_template_part( 'template-parts/footer-credits' ); ?>
			</div>
		</div>
	</footer>

	<?php get_template_part( 'template-parts/document-end' ); ?>
