<?php
/**
 * Секция «Этапы»: счётчик и фото этапа слева, список этапов справа.
 * Счётчик, фото и этапы — три прохода по одному списку, скрипт связывает
 * их по индексу, поэтому кадр выводится и у этапа без фото.
 * Без этапов не выводится.
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
			'title' => $skinlove_item_title,
			'text'  => skinlove_entry( $skinlove_item, 'text' ),
			'image' => (int) ( $skinlove_item['image'] ?? 0 ),
		);
	}
}

if ( ! $skinlove_items ) {
	return;
}

$skinlove_total = sprintf( '%02d', count( $skinlove_items ) );
?>
<section class="section <?php echo esc_attr( skinlove_background_class( $skinlove_section, 'beige' ) ); ?> steps" data-section="steps" data-steps>
	<?php if ( $skinlove_label || $skinlove_title ) : ?>
		<div class="steps__head">
			<?php if ( $skinlove_label ) : ?>
				<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 steps__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="steps__row" data-steps-track>
		<div class="steps__visual">
			<p class="steps__counter" aria-hidden="true">
				<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
					<span class="steps__count" data-steps-counter<?php echo $skinlove_index ? ' hidden' : ''; ?>><span class="steps__number"><?php printf( '%02d', $skinlove_index + 1 ); ?></span><span class="steps__total">/<?php echo esc_html( $skinlove_total ); ?></span></span>
				<?php endforeach; ?>
			</p>

			<ul class="steps__frames">
				<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
					<li class="steps__frame" data-steps-frame<?php echo $skinlove_index ? ' hidden' : ''; ?>>
						<?php
						if ( $skinlove_item['image'] ) {
							echo wp_get_attachment_image(
								$skinlove_item['image'],
								'full',
								false,
								array(
									'class'    => 'steps__image',
									'loading'  => false,
									'decoding' => 'async',
								)
							);
						}
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="steps__body">
			<?php if ( $skinlove_label ) : ?>
				<p class="steps__label"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>

			<div class="steps__viewport">
				<ol class="steps__list" data-steps-list>
					<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
						<li class="step" data-steps-item<?php echo $skinlove_index ? '' : ' aria-current="step"'; ?>>
							<h3 class="step__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
							<?php if ( $skinlove_item['text'] ) : ?>
								<p class="step__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</section>
