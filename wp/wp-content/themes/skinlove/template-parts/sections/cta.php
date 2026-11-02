<?php
/**
 * Секция «Призыв к записи»: карточка с фото, заголовком, текстом и кнопкой,
 * которая открывает модалку заявки. Вид — модификаторы cta--*.
 * Без текстов не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_button  = skinlove_entry( $skinlove_section, 'button' );
$skinlove_class   = 'section section--beige cta';

if ( ! $skinlove_title && ! $skinlove_text && ! $skinlove_button ) {
	return;
}

foreach ( array_intersect( (array) ( $skinlove_section['variant'] ?? array() ), array( 'cover', 'stacked', 'deep-shade' ) ) as $skinlove_variant ) {
	$skinlove_class .= " cta--{$skinlove_variant}";
}
?>
<section class="<?php echo esc_attr( $skinlove_class ); ?>" data-section="cta">
	<div class="cta__card" data-cta-card>
		<div class="cta__media" data-cta-media>
			<?php
			if ( $skinlove_image ) {
				echo wp_get_attachment_image(
					$skinlove_image,
					'full',
					false,
					array(
						'class'          => 'cta__media-image',
						'alt'            => '',
						'aria-hidden'    => 'true',
						'loading'        => 'lazy',
						'decoding'       => 'async',
						'data-cta-image' => '',
					)
				);
			}
			?>
			<span class="cta__shade" data-cta-shade aria-hidden="true"></span>
		</div>

		<?php if ( $skinlove_title ) : ?>
			<h2 class="heading heading--h2 cta__title" data-heading-fill data-cta-title>
				<?php echo skinlove_heading( $skinlove_title, 'data-cta-word' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</h2>
		<?php endif; ?>

		<?php if ( $skinlove_text ) : ?>
			<p class="cta__text" data-cta-aside><?php echo esc_html( $skinlove_text ); ?></p>
		<?php endif; ?>

		<?php echo skinlove_button( $skinlove_button, '#request-modal', 'button--light cta__button', 'data-modal-open="request-modal" data-cta-aside' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
