<?php
/**
 * Секция «Отзывы»: имена-вкладки и текст выбранного отзыва. Отзывы общие
 * на весь сайт (inc/reviews.php), без них секция не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_reviews = skinlove_reviews();

if ( ! $skinlove_reviews ) {
	return;
}

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_uid     = skinlove_section_uid( 'review' );
?>
<section class="section section--beige reviews" data-section="reviews" data-reviews>
	<?php if ( ! empty( $skinlove_section['blinds'] ) ) : ?>
		<div class="blinds" aria-hidden="true">
			<?php echo str_repeat( '<span class="blinds__bar" data-blind></span>', 5 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>

	<?php endif; ?>
	<span class="reviews__line rule" data-rule aria-hidden="true"></span>
	<span class="reviews__divider rule rule--vertical" data-rule aria-hidden="true"></span>
	<?php echo skinlove_icon( 'plus', 'reviews__marker', 'data-rule-marker' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

	<div class="reviews__inner">
		<?php if ( $skinlove_label || $skinlove_title ) : ?>
			<header class="reviews__head">
				<?php if ( $skinlove_label ) : ?>
					<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
				<?php endif; ?>
				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2 reviews__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="reviews__body">
			<div class="reviews__aside">
				<ul class="reviews__names" role="tablist" aria-orientation="vertical" aria-label="<?php echo esc_attr( skinlove_t( 'reviews_guests' ) ); ?>" data-review-tablist>
					<?php foreach ( $skinlove_reviews as $skinlove_index => $skinlove_review ) : ?>
						<?php $skinlove_id = $skinlove_uid . '-' . ( $skinlove_index + 1 ); ?>
						<li role="presentation">
							<button
								class="tab"
								type="button"
								role="tab"
								id="<?php echo esc_attr( $skinlove_id ); ?>-tab"
								aria-controls="<?php echo esc_attr( $skinlove_id ); ?>-panel"
								aria-selected="<?php echo 0 === $skinlove_index ? 'true' : 'false'; ?>"
								tabindex="<?php echo 0 === $skinlove_index ? '0' : '-1'; ?>"
								data-review-tab>
								<span class="tab__label"><?php echo esc_html( $skinlove_review['name'] ); ?></span>
								<?php echo skinlove_icon( 'heart', 'tab__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							</button>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="reviews__nav">
					<button class="arrow-button" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'reviews_prev' ) ); ?>" data-review-prev>
						<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
					<button class="arrow-button arrow-button--next" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'reviews_next' ) ); ?>" data-review-next>
						<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</button>
				</div>
			</div>

			<div class="reviews__main">
				<?php foreach ( $skinlove_reviews as $skinlove_index => $skinlove_review ) : ?>
					<?php
					get_template_part(
						'template-parts/cards/review',
						null,
						array(
							'review' => $skinlove_review,
							'id'     => $skinlove_uid . '-' . ( $skinlove_index + 1 ),
							'hidden' => 0 !== $skinlove_index,
						)
					);
					?>
				<?php endforeach; ?>
			</div>
		</div>

		<?php echo skinlove_entry_button( $skinlove_section ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
