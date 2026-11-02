<?php
/**
 * Шапка сайта: логотип, переключатель языка, кнопка меню и само меню.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_other = 'en' === skinlove_lang() ? 'ru' : 'en';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php skinlove_asset_url( 'favicon.svg' ); ?>" type="image/svg+xml">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
	<a class="skip-link" href="#main"><?php echo esc_html( skinlove_t( 'skip_link' ) ); ?></a>

	<header class="header" data-header>
		<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'logo_home' ) ); ?>">
			<img class="header__logo-image" src="<?php skinlove_asset_url( 'img/logo-white.png' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
		</a>

		<div class="lang-switch" data-lang-switch>
			<button class="lang-switch__toggle" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'lang_' . skinlove_lang() ) ); ?>" aria-expanded="false" aria-controls="lang-switch-list" data-lang-switch-toggle>
				<span class="lang-switch__label"><?php echo esc_html( skinlove_t( 'lang_label_' . skinlove_lang() ) ); ?></span>
				<?php echo skinlove_icon( 'triangle', 'lang-switch__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<ul class="lang-switch__list" id="lang-switch-list" data-lang-switch-list hidden>
				<li class="lang-switch__item" data-lang-switch-item><a class="lang-switch__link" href="<?php echo esc_url( skinlove_lang_url( $skinlove_other ) ); ?>" lang="<?php echo esc_attr( $skinlove_other ); ?>" hreflang="<?php echo esc_attr( $skinlove_other ); ?>"><?php echo esc_html( skinlove_t( 'lang_label_' . $skinlove_other ) ); ?></a></li>
			</ul>
		</div>

		<button
			class="menu-toggle"
			type="button"
			data-menu-toggle
			aria-expanded="false"
			aria-controls="site-menu">
			<span class="menu-toggle__label menu-toggle__label--open"><?php echo esc_html( skinlove_t( 'menu_open' ) ); ?></span>
			<span class="menu-toggle__label menu-toggle__label--close"><?php echo esc_html( skinlove_t( 'menu_close' ) ); ?></span>
			<span class="menu-toggle__icon" aria-hidden="true">
				<span class="menu-toggle__line menu-toggle__line--top"></span>
				<span class="menu-toggle__line menu-toggle__line--middle"></span>
				<span class="menu-toggle__line menu-toggle__line--bottom"></span>
			</span>
		</button>
	</header>

	<?php get_template_part( 'template-parts/site-menu' ); ?>
