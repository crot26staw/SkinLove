<?php
/**
 * Секция «Заявка с формой»: заголовок, текст, форма «имя + телефон» и фото.
 * Форма выводится всегда — секция без текстов это просто форма заявки.
 *
 * Вид «Выездные услуги» — свои отступы, кнопка-пилюля и прямые контакты
 * под формой. Таких форм на странице несколько (решение 28), поэтому в
 * источник заявки уходит заголовок секции.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image   = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_outcall = 'outcall' === ( $skinlove_section['variant'] ?? '' );
$skinlove_options = $skinlove_outcall ? (array) ( $skinlove_section['options'] ?? array() ) : array();
$skinlove_classes = array( 'section', 'section--beige', 'program-request' );

if ( $skinlove_outcall ) {
	$skinlove_classes[] = 'program-request--' . ( 'inset' === ( $skinlove_section['inset'] ?? '' ) ? 'inset' : 'inset-sm' );

	if ( in_array( 'reverse', $skinlove_options, true ) ) {
		$skinlove_classes[] = 'program-request--reverse';
	}

	$skinlove_classes[] = 'program-request--media-first';

	if ( in_array( 'flush-end', $skinlove_options, true ) ) {
		$skinlove_classes[] = 'program-request--flush-end';
	}
}

$skinlove_form = array(
	'form'    => 'program-request',
	'section' => 'program-request',
	'class'   => 'program-request__form',
	'button'  => skinlove_entry( $skinlove_section, 'button' ),
);

if ( $skinlove_outcall ) {
	$skinlove_form['pill'] = true;
	$skinlove_form['item'] = trim( preg_replace( '/\s+/u', ' ', str_replace( '*', '', $skinlove_title ) ) );
}
?>
<section class="<?php echo esc_attr( implode( ' ', $skinlove_classes ) ); ?>" data-section="program-request">
	<div class="program-request__body">
		<div class="program-request__inner">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading <?php echo $skinlove_outcall ? 'heading--h2-sm' : 'heading--h2'; ?> program-request__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<p class="program-request__text"><?php echo esc_html( $skinlove_text ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_outcall ) : ?>
				<div class="program-request__actions">
					<?php get_template_part( 'template-parts/lead-form', null, $skinlove_form ); ?>

					<?php get_template_part( 'template-parts/contact-links' ); ?>
				</div>
			<?php else : ?>
				<?php get_template_part( 'template-parts/lead-form', null, $skinlove_form ); ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $skinlove_image ) : ?>
		<div class="program-request__media">
			<?php
			echo wp_get_attachment_image(
				$skinlove_image,
				'full',
				false,
				array(
					'class'    => in_array( 'image-low', $skinlove_options, true ) ? 'program-request__image program-request__image--low' : 'program-request__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
				)
			);
			?>
		</div>
	<?php endif; ?>
</section>
