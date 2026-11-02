<?php
/**
 * Секция «Выездные: первый экран»: преимущества, заголовок, текст и
 * карточка с формой заявки слева, фото справа. Форма выводится всегда.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section    = $args['section'];
$skinlove_title      = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text       = skinlove_entry( $skinlove_section, 'text' );
$skinlove_card_title = skinlove_entry( $skinlove_section, 'card_title' );
$skinlove_card_text  = skinlove_entry( $skinlove_section, 'card_text' );
$skinlove_image      = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_tags       = array_filter( array_map( fn( $tag ) => skinlove_entry( $tag, 'label' ), (array) ( $skinlove_section['tags'] ?? array() ) ) );
?>
<section class="section outcall-hero" data-section="outcall-hero">
	<div class="outcall-hero__panel">
		<?php if ( $skinlove_tags || $skinlove_title ) : ?>
			<div class="outcall-hero__head">
				<?php if ( $skinlove_tags ) : ?>
					<ul class="outcall-hero__tags">
						<?php foreach ( $skinlove_tags as $skinlove_tag ) : ?>
							<li class="outcall-hero__tag">
								<?php echo skinlove_icon( 'heart-filter', 'outcall-hero__tag-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span class="outcall-hero__tag-label"><?php echo esc_html( $skinlove_tag ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<?php if ( $skinlove_title ) : ?>
					<h1 class="heading heading--h2-sm outcall-hero__title">
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h1>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_text ) : ?>
			<p class="outcall-hero__text"><?php echo esc_html( $skinlove_text ); ?></p>
		<?php endif; ?>

		<div class="outcall-hero__card">
			<?php if ( $skinlove_card_title ) : ?>
				<h2 class="outcall-hero__card-title"><?php echo esc_html( $skinlove_card_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $skinlove_card_text ) : ?>
				<p class="outcall-hero__card-text"><?php echo esc_html( $skinlove_card_text ); ?></p>
			<?php endif; ?>

			<?php
			get_template_part(
				'template-parts/lead-form',
				null,
				array(
					'form'    => 'outcall-hero',
					'section' => 'outcall-hero',
					'button'  => skinlove_entry( $skinlove_section, 'button' ),
					'pill'    => true,
				)
			);
			?>
		</div>
	</div>

	<?php if ( $skinlove_image ) : ?>
		<div class="outcall-hero__media">
			<?php
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => 'outcall-hero__image',
					'loading'  => false,
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>
</section>
