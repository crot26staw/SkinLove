<?php
/**
 * Секция «Контакты»: заголовок и строки «подпись — значение». Значения
 * из «Настроек сайта»; строка без значения не выводится, адрес без карты
 * остаётся одной ссылкой, карта без адреса — строкой с одной картой.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_address = skinlove_option( 'address' );
$skinlove_map     = skinlove_map_embed( 'contacts__map-frame' );
$skinlove_phones  = skinlove_phones();
$skinlove_email   = skinlove_option_raw( 'email' );
$skinlove_buttons = skinlove_socials() || skinlove_whatsapp_url();
?>
<section class="section section--light contacts" data-section="contacts">
	<?php if ( $skinlove_title ) : ?>
		<h1 class="heading heading--h2 contacts__title">
			<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</h1>
	<?php endif; ?>

	<div class="contacts__list">
		<?php if ( $skinlove_address || $skinlove_map ) : ?>
			<div class="contacts__row">
				<p class="contacts__label"><?php echo esc_html( skinlove_t( 'contacts_address' ) ); ?></p>

				<div class="contacts__body">
					<?php if ( $skinlove_address ) : ?>
						<a class="contacts__value" href="<?php echo esc_url( skinlove_address_url( $skinlove_address ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $skinlove_address ); ?></a>
					<?php endif; ?>

					<?php if ( $skinlove_map ) : ?>
						<div class="contacts__map">
							<?php echo $skinlove_map; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_phones ) : ?>
			<div class="contacts__row">
				<p class="contacts__label"><?php echo esc_html( skinlove_t( 'contacts_phone' ) ); ?></p>

				<div class="contacts__body">
					<?php foreach ( $skinlove_phones as $skinlove_phone ) : ?>
						<div class="contacts__item">
							<?php if ( $skinlove_phone['note'] ) : ?>
								<p class="contacts__note"><?php echo esc_html( $skinlove_phone['note'] ); ?></p>
							<?php endif; ?>
							<a class="contacts__value" href="<?php echo esc_url( $skinlove_phone['href'] ); ?>"><?php echo esc_html( $skinlove_phone['label'] ); ?></a>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_email ) : ?>
			<div class="contacts__row">
				<p class="contacts__label"><?php echo esc_html( skinlove_t( 'contacts_email' ) ); ?></p>

				<div class="contacts__body">
					<a class="contacts__value" href="<?php echo esc_url( 'mailto:' . $skinlove_email ); ?>"><?php echo esc_html( $skinlove_email ); ?></a>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_buttons ) : ?>
			<div class="contacts__row">
				<p class="contacts__label"><?php echo esc_html( skinlove_t( 'contacts_socials' ) ); ?></p>

				<div class="contacts__body">
					<div class="contacts__socials">
						<?php get_template_part( 'template-parts/social-buttons' ); ?>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
