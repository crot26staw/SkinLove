<?php
/**
 * Секция «Вопросы и ответы»: аккордеон на <details>, первый вопрос открыт.
 * Вопрос без ответа (или наоборот) пропускается, без вопросов секции нет.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_question = skinlove_entry( $skinlove_item, 'question' );
	$skinlove_answer   = skinlove_entry( $skinlove_item, 'answer' );

	if ( $skinlove_question && $skinlove_answer ) {
		$skinlove_items[] = array(
			'question'   => $skinlove_question,
			'paragraphs' => preg_split( '/\R\s*\R/u', $skinlove_answer ),
		);
	}
}

if ( ! $skinlove_items ) {
	return;
}
?>
<section class="section section--beige faq" data-section="faq">
	<?php if ( $skinlove_label || $skinlove_title ) : ?>
		<div class="faq__head">
			<?php if ( $skinlove_label ) : ?>
				<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 faq__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="faq__list">
		<?php foreach ( $skinlove_items as $skinlove_index => $skinlove_item ) : ?>
			<details class="faq-item" data-faq-item<?php echo $skinlove_index ? '' : ' open'; ?>>
				<summary class="faq-item__summary">
					<span class="faq-item__question"><?php echo esc_html( $skinlove_item['question'] ); ?></span>
					<span class="faq-item__toggle" aria-hidden="true">
						<?php echo skinlove_icon( 'plus-faq', 'faq-item__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</span>
				</summary>
				<div class="faq-item__body" data-faq-body>
					<div class="faq-item__answer">
						<?php foreach ( $skinlove_item['paragraphs'] as $skinlove_paragraph ) : ?>
							<p class="faq-item__text"><?php echo nl2br( esc_html( trim( $skinlove_paragraph ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></p>
						<?php endforeach; ?>
					</div>
				</div>
			</details>
		<?php endforeach; ?>
	</div>
</section>
