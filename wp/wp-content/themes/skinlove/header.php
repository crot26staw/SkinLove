<?php
/**
 * Шапка сайта: логотип, переключатель языка, кнопка меню и само меню.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

get_template_part( 'template-parts/document-start' );
?>

	<header class="header" data-header>
		<a class="header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'logo_home' ) ); ?>">
			<img class="header__logo-image" src="<?php skinlove_asset_url( 'img/logo-white.webp' ); ?>" width="301" height="102" alt="SkinLove" decoding="async">
		</a>

		<?php get_template_part( 'template-parts/lang-switch' ); ?>

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
