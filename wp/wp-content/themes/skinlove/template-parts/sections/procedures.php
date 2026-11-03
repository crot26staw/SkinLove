<?php
/**
 * Секция «Процедуры и цены»: вкладки процедур, фото, описание, прайс
 * со сворачиванием и кнопка заявки с названием процедуры в источнике.
 *
 * Фото, панели и подписи — три прохода по одному списку, скрипт связывает
 * их по индексу, поэтому кадр выводится и у процедуры без фото.
 * Процедура без названия вкладки или без содержимого (заголовка, описания,
 * прайса) пропускается, без процедур секции нет. Переключатель прайсов —
 * когда заполнены оба прайса и обе подписи, иначе выводится первый
 * непустой прайс. Кнопка без ссылки открывает окно заявки.
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

	$skinlove_lists = array();

	foreach ( array( 'prices' => 'variant', 'prices_alt' => 'variant_alt' ) as $skinlove_field => $skinlove_variant ) {
		$skinlove_rows = array_filter(
			(array) ( $skinlove_item[ $skinlove_field ] ?? array() ),
			fn( $row ) => '' !== skinlove_entry( $row, 'name' )
		);

		if ( $skinlove_rows ) {
			$skinlove_lists[] = array(
				'label' => skinlove_entry( $skinlove_item, $skinlove_variant ),
				'rows'  => $skinlove_rows,
			);
		}
	}

	if ( count( $skinlove_lists ) > 1 && ( '' === $skinlove_lists[0]['label'] || '' === $skinlove_lists[1]['label'] ) ) {
		$skinlove_lists = array_slice( $skinlove_lists, 0, 1 );
	}

	$skinlove_item = array(
		'tab'        => $skinlove_tab,
		'image'      => (int) ( $skinlove_item['image'] ?? 0 ),
		'title'      => skinlove_entry( $skinlove_item, 'title' ),
		'text'       => skinlove_entry( $skinlove_item, 'text' ),
		'lists'      => $skinlove_lists,
		'button'     => skinlove_entry( $skinlove_item, 'button' ),
		'button_url' => skinlove_url( $skinlove_item['button_url'] ?? '' ),
	);

	if ( $skinlove_item['title'] || $skinlove_item['text'] || $skinlove_item['lists'] ) {
		$skinlove_items[] = $skinlove_item;
	}
}

if ( ! $skinlove_items ) {
	return;
}

$skinlove_limit = (int) ( $skinlove_section['prices_limit'] ?? 0 );
$skinlove_limit = $skinlove_limit > 0 ? $skinlove_limit : SKINLOVE_PRICES_LIMIT;
?>
<section class="section section--beige procedures" data-section="procedures" data-warm-images data-procedures>
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
									'loading'  => 'lazy',
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

						<?php if ( $skinlove_item['lists'] ) : ?>
							<?php $skinlove_variants = count( $skinlove_item['lists'] ) > 1; ?>
							<div class="procedure-panel__prices" data-prices data-prices-limit="<?php echo (int) $skinlove_limit; ?>" data-prices-limit-compact="<?php echo (int) SKINLOVE_PRICES_LIMIT_COMPACT; ?>">
								<p class="procedure-panel__prices-title"><?php echo esc_html( skinlove_t( 'prices_title' ) ); ?></p>

								<?php if ( $skinlove_variants ) : ?>
									<div class="procedure-panel__variants" role="group" aria-label="<?php echo esc_attr( skinlove_t( 'prices_variants' ) ); ?>" data-price-variants>
										<?php foreach ( $skinlove_item['lists'] as $skinlove_list_index => $skinlove_list ) : ?>
											<button class="filter-tab" type="button" aria-pressed="<?php echo $skinlove_list_index ? 'false' : 'true'; ?>" data-price-variant="<?php echo (int) $skinlove_list_index; ?>">
												<?php echo skinlove_icon( 'heart-filter', 'filter-tab__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
												<span class="filter-tab__label" data-label="<?php echo esc_attr( $skinlove_list['label'] ); ?>"><span class="filter-tab__text"><?php echo esc_html( $skinlove_list['label'] ); ?></span></span>
											</button>
										<?php endforeach; ?>
									</div>
								<?php endif; ?>

								<?php foreach ( $skinlove_item['lists'] as $skinlove_list_index => $skinlove_list ) : ?>
									<ul class="procedure-panel__price-list" data-prices-list<?php echo $skinlove_variants ? ' data-price-variant-list="' . (int) $skinlove_list_index . '"' : ''; ?><?php echo $skinlove_list_index ? ' hidden' : ''; ?>>
										<?php
										foreach ( $skinlove_list['rows'] as $skinlove_row ) {
											get_template_part( 'template-parts/cards/price-row', null, array( 'row' => $skinlove_row ) );
										}
										?>
									</ul>
								<?php endforeach; ?>

								<button class="procedure-panel__more" type="button" aria-expanded="false" data-prices-toggle hidden>
									<span class="procedure-panel__more-label procedure-panel__more-label--open"><?php echo esc_html( skinlove_t( 'prices_more' ) ); ?></span>
									<span class="procedure-panel__more-label procedure-panel__more-label--close"><?php echo esc_html( skinlove_t( 'prices_less' ) ); ?></span>
								</button>
							</div>
						<?php endif; ?>

						<?php
						if ( $skinlove_item['button_url'] ) {
							echo skinlove_button( $skinlove_item['button'], $skinlove_item['button_url'], 'procedure-panel__button' ); // phpcs:ignore WordPress.Security.EscapeOutput
						} else {
							echo skinlove_button( $skinlove_item['button'], '#request-modal', 'procedure-panel__button', sprintf( 'data-modal-open="request-modal" data-lead-item="%s"', esc_attr( $skinlove_item['tab'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput
						}
						?>
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
