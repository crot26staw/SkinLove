<?php
/**
 * Секция «Рамка с видео» (frame--reverse, сцена reel): видео из темы
 * идёт от прокрутки (решение 14), тексты сменяют друг друга по ходу
 * ролика. Номер в data-reel-layer — порядковый номер текста, заголовок
 * и абзац с одним номером показываются вместе. Без текстов не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_layers = array();

foreach ( (array) ( $args['section']['layers'] ?? array() ) as $skinlove_layer ) {
	$skinlove_layer = array(
		'title' => skinlove_entry( $skinlove_layer, 'title' ),
		'text'  => skinlove_entry( $skinlove_layer, 'text' ),
	);

	if ( $skinlove_layer['title'] || $skinlove_layer['text'] ) {
		$skinlove_layers[] = $skinlove_layer;
	}
}

if ( ! $skinlove_layers ) {
	return;
}

$skinlove_has_titles = (bool) array_filter( array_column( $skinlove_layers, 'title' ) );
$skinlove_has_texts  = (bool) array_filter( array_column( $skinlove_layers, 'text' ) );
?>
<section class="section section--light frame frame--reverse" data-section="frame" data-reel>
	<div class="frame__card" data-reel-card>
		<div class="frame__decor" aria-hidden="true">
			<video class="frame__image" data-reel-video width="1920" height="1080" poster="<?php skinlove_asset_url( 'img/frame-video-poster.jpg' ); ?>" muted playsinline preload="auto" disablepictureinpicture tabindex="-1">
				<source src="<?php skinlove_asset_url( 'video/frame-video-4k.mp4' ); ?>" media="(min-width: 1024px)" type="video/mp4">
				<source src="<?php skinlove_asset_url( 'video/frame-video.mp4' ); ?>" type="video/mp4">
			</video>
		</div>

		<?php if ( $skinlove_has_titles ) : ?>
			<div class="frame__stack">
				<?php foreach ( $skinlove_layers as $skinlove_index => $skinlove_layer ) : ?>
					<?php if ( $skinlove_layer['title'] ) : ?>
						<h2 class="heading heading--h2 frame__title"<?php echo 0 === $skinlove_index ? ' data-heading-fill' : ''; ?> data-reel-layer="<?php echo (int) $skinlove_index; ?>"<?php echo 0 === $skinlove_index ? '' : ' hidden'; ?>>
							<?php echo skinlove_heading( $skinlove_layer['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
						</h2>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<div class="frame__foot">
			<?php if ( $skinlove_has_texts ) : ?>
				<div class="frame__texts">
					<div class="frame__stack">
						<?php foreach ( $skinlove_layers as $skinlove_index => $skinlove_layer ) : ?>
							<?php if ( $skinlove_layer['text'] ) : ?>
								<div class="frame__text content" data-reel-layer="<?php echo (int) $skinlove_index; ?>"<?php echo 0 === $skinlove_index ? '' : ' hidden'; ?>>
									<?php echo skinlove_paragraphs( $skinlove_layer['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/frame-hearts' ); ?>
		</div>
	</div>
</section>
