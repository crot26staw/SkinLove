<?php
/**
 * Секция «Акции»: заголовок со стрелками и карусель карточек. Кнопка
 * карточки открывает модалку заявки с названием акции в источнике.
 * Без акций не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title'  => $skinlove_item_title,
			'text'   => skinlove_entry( $skinlove_item, 'text' ),
			'button' => skinlove_entry( $skinlove_item, 'button' ),
			'image'  => (int) ( $skinlove_item['image'] ?? 0 ),
		);
	}
}

if ( ! $skinlove_items ) {
	return;
}
?>
<section class="section <?php echo esc_attr( skinlove_background_class( $skinlove_section, 'beige' ) ); ?> promos" data-section="promos" data-promos data-carousel>
	<div class="promos__head">
		<?php if ( $skinlove_label || $skinlove_title ) : ?>
			<div class="promos__heading">
				<?php if ( $skinlove_label ) : ?>
					<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
				<?php endif; ?>

				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2 promos__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="promos__nav">
			<button class="arrow-button" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'promos_prev' ) ); ?>" data-carousel-prev>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<button class="arrow-button arrow-button--next" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'promos_next' ) ); ?>" data-carousel-next>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>

	<div class="promos__viewport" data-carousel-viewport>
		<ul class="promos__track">
			<?php foreach ( $skinlove_items as $skinlove_item ) : ?>
				<li class="promo-card">
					<?php if ( $skinlove_item['image'] ) : ?>
						<div class="promo-card__media">
							<?php
							echo wp_get_attachment_image(
								$skinlove_item['image'],
								'full',
								false,
								array(
									'class'    => 'promo-card__image',
									'alt'      => '',
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						</div>
					<?php endif; ?>

					<h3 class="promo-card__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>

					<?php if ( $skinlove_item['text'] ) : ?>
						<p class="promo-card__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
					<?php endif; ?>

					<?php echo skinlove_button( $skinlove_item['button'], '#request-modal', '', sprintf( 'data-modal-open="request-modal" data-lead-item="%s"', esc_attr( $skinlove_item['title'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
