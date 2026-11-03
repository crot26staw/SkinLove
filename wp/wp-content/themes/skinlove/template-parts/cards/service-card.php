<?php
/**
 * Карточка направления в ленте «Направления».
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_direction = $args['direction'];
?>
<article class="service-card" data-services-card>
	<?php if ( $skinlove_direction['image'] ) : ?>
		<div class="service-card__media">
			<?php
			echo wp_get_attachment_image(
				$skinlove_direction['image'],
				'full',
				false,
				array(
					'class'    => 'service-card__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>
	<div class="service-card__body">
		<h3 class="service-card__title"><?php echo esc_html( $skinlove_direction['title'] ); ?></h3>
		<?php if ( $skinlove_direction['text'] ) : ?>
			<p class="service-card__text"><?php echo esc_html( $skinlove_direction['text'] ); ?></p>
		<?php endif; ?>
		<?php echo skinlove_button( skinlove_t( 'services_more' ), $skinlove_direction['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</article>
