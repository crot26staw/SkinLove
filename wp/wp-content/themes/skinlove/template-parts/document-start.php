<?php
/**
 * Начало документа для обеих шапок: <head>, открытие <body> и ссылка
 * «Перейти к содержимому».
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;
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
