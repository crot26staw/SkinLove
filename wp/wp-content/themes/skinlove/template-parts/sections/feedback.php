<?php
/**
 * Секция «Выездные: отзывы» — карусель карточек со стрелками
 * (components/carousel.js). Отзывы общие на весь сайт (inc/reviews.php,
 * решение 27), без них секция не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_reviews = skinlove_reviews();

if ( ! $skinlove_reviews ) {
	return;
}

$skinlove_title = skinlove_entry( $args['section'], 'title' );
?>
<section class="section section--beige feedback" id="<?php echo esc_attr( skinlove_section_uid( 'reviews' ) ); ?>" data-section="feedback" data-carousel>
	<div class="feedback__head">
		<?php if ( $skinlove_title ) : ?>
			<h2 class="heading heading--h2-sm feedback__title" data-heading-fill>
				<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h2>
		<?php endif; ?>

		<div class="feedback__nav">
			<button class="arrow-button arrow-button--filled" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'feedback_prev' ) ); ?>" data-carousel-prev>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<button class="arrow-button arrow-button--filled arrow-button--next" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'feedback_next' ) ); ?>" data-carousel-next>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>

	<div class="feedback__viewport" data-carousel-viewport>
		<ul class="feedback__track">
			<?php foreach ( $skinlove_reviews as $skinlove_review ) : ?>
				<?php get_template_part( 'template-parts/cards/review-card', null, array( 'review' => $skinlove_review ) ); ?>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
