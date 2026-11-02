<?php
/**
 * Секция «Процедуры и цены»: вкладки процедур, фото, описание, прайс
 * со сворачиванием и кнопка заявки с названием процедуры в источнике.
 *
 * Фото, панели и подписи — три прохода по одному списку, скрипт связывает
 * их по индексу, поэтому кадр выводится и у процедуры без фото.
 * Процедура без названия вкладки или без содержимого (заголовка, описания,
 * прайса) пропускается, без процедур секции нет.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_tab = skinlove_entry( $skinlove_item, 'tab' );

	if ( ! $skinlove_tab ) {
		continue;
	}

	$skinlove_rows = array_filter(
		(array) ( $skinlove_item['prices'] ?? array() ),
		fn( $row ) => '' !== skinlove_entry( $row, 'name' )
	);

	$skinlove_item = array(
		'tab'    => $skinlove_tab,
		'image'  => (int) ( $skinlove_item['image'] ?? 0 ),
		'title'  => skinlove_entry( $skinlove_item, 'title' ),
		'text'   => skinlove_entry( $skinlove_item, 'text' ),
		'rows'   => $skinlove_rows,
		'button' => skinlove_entry( $skinlove_item, 'button' ),
	);

	if ( $skinlove_item['title'] || $skinlove_item['text'] || $skinlove_item['rows'] ) {
		$skinlove_items[] = $skinlove_item;
	}
}

if ( ! $skinlove_items ) {
	return;
}

$skinlove_limit = (int) ( $skinlove_section['prices_limit'] ?? 0 );
$skinlove_limit = $skinlove_limit > 0 ? $skinlove_limit : SKINLOVE_PRICES_LIMIT;
?>
<section class="section section--beige procedures" data-section="procedures" data-procedures>
	<div class="blinds" aria-hidden="true">
		<?php echo str_repeat( '<span class="blinds__bar" data-blind></span>', 5 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>

	<div class="procedures__inner" data-procedures-track>
		<div class="procedures__media">
			<ul class="procedures__frames">
				<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
					<li class="procedures__frame" data-procedures-frame<?php echo $skinlove_index ? ' hidden' : ''; ?>>
						<?php
						if ( $skinlove_item['image'] ) {
							echo wp_get_attachment_image(
								$skinlove_item['image'],
								'full',
								false,
								array(
									'class'    => 'procedures__image',
									'loading'  => false,
									'decoding' => 'async',
								)
							);
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>

			<ul class="procedures__tabs" role="tablist" aria-orientation="vertical" aria-label="<?php echo esc_attr( skinlove_t( 'procedures_tabs' ) ); ?>" data-procedures-tablist>
				<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
					<li role="presentation">
						<button class="tab tab--light" type="button" role="tab" aria-selected="<?php echo $skinlove_index ? 'false' : 'true'; ?>" tabindex="<?php echo $skinlove_index ? '-1' : '0'; ?>" data-procedures-tab>
							<span class="tab__label"><?php echo esc_html( $skinlove_item['tab'] ); ?></span>
							<?php echo skinlove_icon( 'heart', 'tab__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="procedures__body">
			<ul class="procedures__panels">
				<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
					<li class="procedure-panel" role="tabpanel" data-procedures-panel<?php echo $skinlove_index ? ' hidden' : ''; ?>>
						<?php if ( $skinlove_item['title'] || $skinlove_item['text'] ) : ?>
							<div class="procedure-panel__intro">
								<?php if ( $skinlove_item['title'] ) : ?>
									<h3 class="procedure-panel__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( $skinlove_item['text'] ) : ?>
									<p class="procedure-panel__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ( $skinlove_item['rows'] ) : ?>
							<div class="procedure-panel__prices" data-prices data-prices-limit="<?php echo (int) $skinlove_limit; ?>" data-prices-limit-compact="<?php echo (int) SKINLOVE_PRICES_LIMIT_COMPACT; ?>">
								<p class="procedure-panel__prices-title"><?php echo esc_html( skinlove_t( 'prices_title' ) ); ?></p>

								<ul class="procedure-panel__price-list" data-prices-list>
									<?php
									foreach ( $skinlove_item['rows'] as $skinlove_row ) {
										get_template_part( 'template-parts/cards/price-row', null, array( 'row' => $skinlove_row ) );
									}
									?>
								</ul>

								<button class="procedure-panel__more" type="button" aria-expanded="false" data-prices-toggle hidden>
									<span class="procedure-panel__more-label procedure-panel__more-label--open"><?php echo esc_html( skinlove_t( 'prices_more' ) ); ?></span>
									<span class="procedure-panel__more-label procedure-panel__more-label--close"><?php echo esc_html( skinlove_t( 'prices_less' ) ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<?php echo skinlove_button( $skinlove_item['button'], '#request-modal', 'procedure-panel__button', sprintf( 'data-modal-open="request-modal" data-lead-item="%s"', esc_attr( $skinlove_item['tab'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<ul class="procedures__captions" aria-hidden="true">
			<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
				<li class="procedures__caption" data-procedures-caption<?php echo $skinlove_index ? ' hidden' : ''; ?>><?php echo esc_html( $skinlove_item['tab'] ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
