<?php
/**
 * Секция «Результаты»: заголовок со стрелками и лента фото «до / после»
 * из настроек сайта (общие для всех страниц). Без фото не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_images  = skinlove_results();

if ( ! $skinlove_images ) {
	return;
}
?>
<section class="section section--light results" data-section="results" data-warm-images data-results>
	<div class="results__aside">
		<?php if ( $skinlove_label || $skinlove_title ) : ?>
			<div class="results__head">
				<?php if ( $skinlove_label ) : ?>
					<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
				<?php endif; ?>

				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2 results__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="results__main">
		<div class="results__viewport" data-results-viewport>
			<ul class="results__track" data-results-track>
				<?php foreach ( $skinlove_images as $skinlove_image ) : ?>
					<li class="result-card">
						<?php
						echo wp_get_attachment_image(
							$skinlove_image,
							'full',
							false,
							array(
								'class'    => 'result-card__image',
								'loading'  => 'lazy',
								'decoding' => 'async',
							)
						);
						?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="results__nav">
			<button class="arrow-button arrow-button--filled" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'results_prev' ) ); ?>" data-results-prev>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
			<button class="arrow-button arrow-button--filled arrow-button--next" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'results_next' ) ); ?>" data-results-next>
				<?php echo skinlove_icon( 'arrow', 'arrow-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</button>
		</div>
	</div>
</section>
