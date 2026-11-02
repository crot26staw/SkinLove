<?php
/**
 * Секция «Выездные: когда удобно»: видео из темы, заголовок, текст,
 * ситуации и кнопка заявки. Отметки у ситуаций рисует components/reasons.js.
 * Без заголовка и ситуаций не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_button  = skinlove_entry( $skinlove_section, 'button' );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title' => $skinlove_item_title,
			'text'  => skinlove_entry( $skinlove_item, 'text' ),
		);
	}
}

if ( ! $skinlove_title && ! $skinlove_items ) {
	return;
}
?>
<section class="section section--light reasons" data-section="reasons" data-reasons>
	<div class="reasons__media">
		<video class="reasons__image" width="1440" height="1440" poster="<?php skinlove_asset_url( 'img/outcall-reasons-poster.jpg' ); ?>" autoplay muted loop playsinline preload="metadata" disablepictureinpicture tabindex="-1" aria-hidden="true" data-autoplay-video>
			<source src="<?php skinlove_asset_url( 'video/outcall-reasons.mp4' ); ?>" type="video/mp4">
		</video>
	</div>

	<div class="reasons__body">
		<?php if ( $skinlove_title || $skinlove_text ) : ?>
			<div class="reasons__head">
				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2-sm reasons__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>

				<?php if ( $skinlove_text ) : ?>
					<p class="reasons__text"><?php echo esc_html( $skinlove_text ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_items ) : ?>
			<ul class="reasons__list">
				<?php foreach ( $skinlove_items as $skinlove_item ) : ?>
					<li class="reason" data-reason>
						<span class="reason__marks" aria-hidden="true" data-reason-marks></span>
						<h3 class="reason__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
						<?php if ( $skinlove_item['text'] ) : ?>
							<p class="reason__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php echo skinlove_pill_button( $skinlove_button, '#request-modal', 'reasons__button', 'data-modal-open="request-modal"' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
