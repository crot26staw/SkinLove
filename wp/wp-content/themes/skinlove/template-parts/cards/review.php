<?php
/**
 * Отзыв — панель вкладки в секции «Отзывы».
 *
 * Аргументы: review — элемент skinlove_reviews(), id — префикс id вкладки
 * и панели, hidden — панель скрыта до выбора.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_review = $args['review'];
?>
<figure
	class="review"
	id="<?php echo esc_attr( $args['id'] ); ?>-panel"
	role="tabpanel"
	aria-labelledby="<?php echo esc_attr( $args['id'] ); ?>-tab"
	data-review-panel<?php echo $args['hidden'] ? ' hidden' : ''; ?>>
	<blockquote class="review__quote"><?php echo esc_html( $skinlove_review['text'] ); ?></blockquote>
	<figcaption class="review__author">
		<span class="review__avatar">
			<?php
			if ( $skinlove_review['photo'] ) {
				echo wp_get_attachment_image(
					$skinlove_review['photo'],
					'medium',
					false,
					array(
						'class'    => 'review__image',
						'alt'      => '',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
			} else {
				echo skinlove_icon( 'avatar', 'review__image' ); // phpcs:ignore WordPress.Security.EscapeOutput
			}
			?>
		</span>
		<span class="review__name"><?php echo esc_html( $skinlove_review['name'] ); ?></span>
	</figcaption>
</figure>
