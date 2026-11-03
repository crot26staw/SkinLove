<?php
/**
 * Секция «Первый экран с логотипом» («О клинике»): видео из темы
 * (решение 14), вместо заголовка — wordmark, один на оба языка (решение 15).
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_lead    = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_note    = skinlove_entry( $skinlove_section, 'note' );
?>
<section class="page-hero" data-section="page-hero" data-spread data-tour>
	<div class="page-hero__decor" aria-hidden="true">
		<video class="page-hero__decor-media" data-tour-video width="1920" height="1080" poster="<?php skinlove_asset_url( 'img/about-video-poster.jpg' ); ?>" muted playsinline preload="auto" disablepictureinpicture tabindex="-1">
			<source src="<?php skinlove_asset_url( 'video/about-video-4k.mp4' ); ?>" media="(min-width: 1921px), (min-width: 1024px) and (min-resolution: 2dppx)" type="video/mp4">
			<source src="<?php skinlove_asset_url( 'video/about-video-hd.mp4' ); ?>" media="(min-width: 1024px)" type="video/mp4">
			<source src="<?php skinlove_asset_url( 'video/about-video.mp4' ); ?>" type="video/mp4">
		</video>
	</div>

	<div class="page-hero__veil" data-spread-veil aria-hidden="true"></div>

	<div class="page-hero__inner">
		<h1 class="page-hero__title" data-spread-left>
			<img class="page-hero__wordmark" src="<?php skinlove_asset_url( 'img/wordmark-light.svg' ); ?>" width="910" height="228" alt="SkinLove" decoding="async">
		</h1>

		<?php if ( $skinlove_lead || $skinlove_note ) : ?>
			<div class="page-hero__text" data-spread-right>
				<?php if ( $skinlove_lead ) : ?>
					<p class="page-hero__lead"><?php echo esc_html( $skinlove_lead ); ?></p>
				<?php endif; ?>
				<?php if ( $skinlove_note ) : ?>
					<p class="page-hero__note"><?php echo esc_html( $skinlove_note ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
