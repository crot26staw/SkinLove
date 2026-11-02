<?php
/**
 * Карточка программы в каталоге «Программы». Кнопка открывает модалку
 * заявки с названием программы в источнике.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_program = $args['program'];
$skinlove_tag     = $args['tag'];
?>
<li class="program-card" data-program data-category="<?php echo esc_attr( $args['category'] ); ?>">
	<div class="program-card__body">
		<div class="program-card__head">
			<h3 class="program-card__title"><?php echo esc_html( $skinlove_program['title'] ); ?></h3>
			<?php if ( $skinlove_tag ) : ?>
				<p class="program-card__tag"><?php echo esc_html( $skinlove_tag ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $skinlove_program['text'] ) : ?>
			<p class="program-card__text"><?php echo esc_html( $skinlove_program['text'] ); ?></p>
		<?php endif; ?>
	</div>

	<div class="program-card__footer">
		<?php foreach ( $skinlove_program['prices'] as $skinlove_price ) : ?>
			<p class="program-card__price">
				<?php if ( $skinlove_price['amount'] ) : ?>
					<span class="program-card__amount"><?php echo esc_html( $skinlove_price['amount'] ); ?></span>
				<?php endif; ?>
				<span class="program-card__value"><?php echo esc_html( $skinlove_price['price'] ); ?></span>
			</p>
		<?php endforeach; ?>

		<?php echo skinlove_button( $args['button'], '#request-modal', '', sprintf( 'data-modal-open="request-modal" data-lead-item="%s"', esc_attr( $skinlove_program['title'] ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</li>
