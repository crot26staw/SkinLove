<?php
/**
 * Секция «Первый экран» главной: видео из темы (решение 14), заголовок,
 * текст и кнопка.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead    = skinlove_entry( $skinlove_section, 'lead' );
?>
<section class="hero hero--intro" data-section="hero">
	<div class="hero__decor" data-hero-video aria-hidden="true">
		<div class="hero__decor-frame">
			<video class="hero__decor-media" width="1918" height="1080" poster="<?php skinlove_asset_url( 'img/hero-video-poster.jpg' ); ?>" muted playsinline preload="auto" disablepictureinpicture tabindex="-1">
				<source src="<?php skinlove_asset_url( 'video/hero-video-4k.mp4' ); ?>" media="(min-width: 1921px), (min-width: 1024px) and (min-resolution: 2dppx)" type="video/mp4">
				<source src="<?php skinlove_asset_url( 'video/hero-video.mp4' ); ?>" type="video/mp4">
			</video>
		</div>
	</div>

	<div class="hero__veil" data-hero-veil aria-hidden="true"></div>

	<div class="hero__inner">
		<?php if ( $skinlove_title ) : ?>
			<h1 class="heading heading--h1 hero__title">
				<?php echo skinlove_heading( $skinlove_title, 'data-hero-intro' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h1>
		<?php endif; ?>

		<?php if ( $skinlove_lead ) : ?>
			<p class="hero__lead" data-hero-intro><?php echo esc_html( $skinlove_lead ); ?></p>
		<?php endif; ?>

		<?php echo skinlove_entry_button( $skinlove_section, 'hero__button', 'data-hero-intro' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
