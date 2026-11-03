<?php
/**
 * Кнопки соцсетей и WhatsApp — общие для подвала, меню и «Контактов».
 * Обёртку выводит шаблон, который подключает партиал.
 *
 * Аргументы:
 * - socials — выводить соцсети; в подвале Outcall только WhatsApp.
 * - filled  — WhatsApp залит постоянно, как в подвале Outcall; иначе
 *   заливка только при наведении, как у остальных кнопок.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_socials  = ( $args['socials'] ?? true ) ? skinlove_socials() : array();
$skinlove_whatsapp = skinlove_whatsapp_url();
$skinlove_filled   = $args['filled'] ?? false;
?>
<?php foreach ( $skinlove_socials as $skinlove_key => $skinlove_url ) : ?>
	<a class="social-button" href="<?php echo esc_url( $skinlove_url ); ?>" aria-label="<?php echo esc_attr( skinlove_t( $skinlove_key ) ); ?>" target="_blank" rel="noopener"><?php echo skinlove_icon( 'social', 'social-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
<?php endforeach; ?>
<?php if ( $skinlove_whatsapp ) : ?>
	<a class="social-button<?php echo $skinlove_filled ? ' social-button--filled' : ''; ?>" href="<?php echo esc_url( $skinlove_whatsapp ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'whatsapp' ) ); ?>" target="_blank" rel="noopener"><?php echo skinlove_icon( 'whatsapp', 'social-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
<?php endif; ?>
