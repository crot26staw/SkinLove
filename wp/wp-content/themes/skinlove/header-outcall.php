<?php
/**
 * Шапка лендинга «Выездные услуги» (решение 24): логотип ведёт на эту же страницу,
 * телефон и WhatsApp, разделы страницы, язык и кнопка заявки. Вверху
 * страницы RU и кнопка скрыты, после прокрутки шапка становится полной
 * строкой (components/header.js, data-header-stuck). Меню сайта нет.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_phone    = skinlove_phones()[0] ?? null;
$skinlove_whatsapp = skinlove_whatsapp_url();

get_template_part( 'template-parts/document-start' );
?>

	<header class="header header--bar" data-header data-header-stuck>
		<div class="header__start">
			<a class="header__logo" href="<?php echo esc_url( get_permalink( get_queried_object_id() ) ); ?>">
				<img class="header__logo-image" src="<?php skinlove_asset_url( 'img/logo-white.webp' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
			</a>

			<?php if ( $skinlove_phone ) : ?>
				<a class="header__link header__phone" href="<?php echo esc_url( $skinlove_phone['href'] ); ?>">
					<?php echo skinlove_icon( 'phone', 'header__phone-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<span><?php echo esc_html( $skinlove_phone['label'] ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $skinlove_whatsapp ) : ?>
				<a class="header__link header__messenger" href="<?php echo esc_url( $skinlove_whatsapp ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( skinlove_t( 'whatsapp' ) ); ?>">
					<?php echo skinlove_icon( 'dock-whatsapp', 'header__messenger-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( has_nav_menu( 'outcall_' . skinlove_lang() ) ) : ?>
			<nav class="header__nav" aria-label="<?php echo esc_attr( skinlove_t( 'outcall_nav' ) ); ?>">
				<?php skinlove_nav_menu( 'outcall', 'menu header__menu', 'header__link' ); ?>
			</nav>
		<?php endif; ?>

		<div class="header__end">
			<?php get_template_part( 'template-parts/lang-switch' ); ?>

			<?php echo skinlove_pill_button( skinlove_t( 'outcall_book' ), '#request-modal', 'header__cta', 'data-modal-open="request-modal" data-lead-section="header"' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</header>
