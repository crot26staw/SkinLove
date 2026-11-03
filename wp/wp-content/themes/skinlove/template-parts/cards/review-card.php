<?php
/**
 * Отзыв — карточка карусели «Выездные: отзывы».
 *
 * Аргументы: review — элемент skinlove_reviews().
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_review = $args['review'];
?>
<li class="review-card">
	<div class="review-card__author">
		<span class="review-card__photo">
			<?php
			if ( $skinlove_review['photo'] ) {
				echo wp_get_attachment_image(
					$skinlove_review['photo'],
					'medium',
					false,
					array(
						'class'    => 'review-card__image',
						'alt'      => '',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
			} else {
				echo skinlove_icon( 'avatar', 'review-card__image' ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</span>
		<p class="review-card__name"><?php echo esc_html( $skinlove_review['name'] ); ?></p>
	</div>

	<blockquote class="review-card__text"><?php echo esc_html( $skinlove_review['text'] ); ?></blockquote>
</li>
