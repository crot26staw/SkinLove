<?php
/**
 * Прямые контакты под формой заявки на Outcall: WhatsApp и звонок на
 * номера из настроек. Номер, совпадающий с WhatsApp, второй раз не
 * выводится — он уже на кнопке WhatsApp. Без контактов блок не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_whatsapp = skinlove_whatsapp_url();
$skinlove_number   = skinlove_clean( skinlove_option_raw( 'whatsapp' ) );
$skinlove_digits   = preg_replace( '/\D/', '', $skinlove_number );
$skinlove_phones   = array_filter(
	skinlove_phones(),
	fn( $phone ) => 'tel:+' . $skinlove_digits !== $phone['href']
);

if ( ! $skinlove_whatsapp && ! $skinlove_phones ) {
	return;
}
?>
<div class="contact-links">
	<p class="contact-links__title"><?php echo esc_html( skinlove_t( 'contact_links' ) ); ?></p>

	<ul class="contact-links__list">
		<?php if ( $skinlove_whatsapp ) : ?>
			<li><a class="button button--pill button--pill-light" href="<?php echo esc_url( $skinlove_whatsapp ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( skinlove_t( 'whatsapp' ) . ': ' . $skinlove_number ); ?>">
				<?php echo skinlove_icon( 'dock-whatsapp', 'button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="button__label"><?php echo esc_html( $skinlove_number ); ?></span>
			</a></li>
		<?php endif; ?>
		<?php foreach ( $skinlove_phones as $skinlove_phone ) : ?>
			<li><a class="button button--pill button--pill-light" href="<?php echo esc_url( $skinlove_phone['href'] ); ?>" aria-label="<?php echo esc_attr( skinlove_t( 'call' ) . ': ' . $skinlove_phone['label'] ); ?>">
				<?php echo skinlove_icon( 'phone', 'button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="button__label"><?php echo esc_html( $skinlove_phone['label'] ); ?></span>
			</a></li>
		<?php endforeach; ?>
	</ul>
</div>
