<?php
/**
 * Секция «Выездные: как проходит»: заголовок, текст, кнопка заявки и
 * нумерованные этапы. Номера считаются по порядку, иконки — из темы,
 * кнопка у этапа необязательна. Без заголовка и этапов не выводится.
 * Анимация — animations/process.js.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_button  = skinlove_entry( $skinlove_section, 'button' );
$skinlove_icons   = skinlove_process_icons();
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title'  => $skinlove_item_title,
			'text'   => skinlove_entry( $skinlove_item, 'text' ),
			'button' => skinlove_entry( $skinlove_item, 'button' ),
			'icon'   => $skinlove_icons[ $skinlove_item['icon'] ?? '' ]['file'] ?? '',
		);
	}
}

if ( ! $skinlove_title && ! $skinlove_items ) {
	return;
}

$skinlove_modal = 'data-modal-open="request-modal"';
?>
<section class="section section--light process" id="<?php echo esc_attr( skinlove_section_uid( 'process' ) ); ?>" data-section="process" data-process>
	<?php if ( $skinlove_title || $skinlove_text || $skinlove_button ) : ?>
		<div class="process__head">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2-sm process__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<p class="process__text"><?php echo esc_html( $skinlove_text ); ?></p>
			<?php endif; ?>

			<?php echo skinlove_pill_button( $skinlove_button, '#request-modal', '', $skinlove_modal ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	<?php endif; ?>

	<?php if ( $skinlove_items ) : ?>
		<ol class="process__list">
			<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
				<li class="process__step" data-process-step>
					<span class="process__number" data-process-number><span class="process__index"><?php echo esc_html( str_pad( (string) ( $skinlove_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span></span>

					<div class="process__card">
						<?php if ( $skinlove_item['icon'] ) : ?>
							<img class="process__icon" src="<?php skinlove_asset_url( $skinlove_item['icon'] ); ?>" width="25" height="25" alt="" loading="lazy" decoding="async">
						<?php endif; ?>

						<div class="process__body">
							<h3 class="process__step-title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
							<?php if ( $skinlove_item['text'] ) : ?>
								<p class="process__step-text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
							<?php endif; ?>

							<?php echo skinlove_pill_button( $skinlove_item['button'], '#request-modal', '', $skinlove_modal ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>
</section>
