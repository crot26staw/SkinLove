<?php
/**
 * Форма заявки «имя + телефон»: модалка, контакты, заявки в секциях.
 *
 * Аргументы:
 * - form    — тип из skinlove_lead_forms();
 * - section — data-section секции с формой (у модалки подставит скрипт);
 * - class   — дополнительный класс формы, например program-request__form;
 * - button  — подпись кнопки, по умолчанию «Оставить заявку»;
 * - success — сообщение после отправки, по умолчанию из словаря.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_args = wp_parse_args(
	$args,
	array(
		'form'    => 'modal',
		'section' => '',
		'class'   => '',
		'button'  => '',
		'success' => '',
	)
);

$skinlove_button  = $skinlove_args['button'] ? $skinlove_args['button'] : skinlove_t( 'form_submit' );
$skinlove_success = $skinlove_args['success'] ? $skinlove_args['success'] : skinlove_t( 'form_success' );
?>
<form class="<?php echo esc_attr( trim( 'form ' . $skinlove_args['class'] ) ); ?>" action="<?php skinlove_lead_action(); ?>" method="post" novalidate data-form>
	<?php skinlove_lead_fields( $skinlove_args['form'], $skinlove_args['section'] ); ?>

	<div class="form__fields">
		<label class="form__field">
			<input class="form__input" type="text" name="name" placeholder="<?php echo esc_attr( skinlove_t( 'form_name' ) ); ?>" autocomplete="name" required data-mask="name">
			<span class="form__error" data-form-error hidden><?php echo esc_html( skinlove_t( 'form_required' ) ); ?></span>
		</label>

		<label class="form__field">
			<input class="form__input" type="tel" name="phone" placeholder="<?php echo esc_attr( skinlove_t( 'form_phone' ) ); ?>" autocomplete="tel" required>
			<span class="form__error" data-form-error hidden><?php echo esc_html( skinlove_t( 'form_required' ) ); ?></span>
		</label>
	</div>

	<button class="button form__submit" type="submit" data-button>
		<span class="button__label"><?php echo esc_html( $skinlove_button ); ?></span>
		<?php echo skinlove_icon( 'heart', 'button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</button>

	<?php skinlove_form_failure(); ?>

	<p class="form__status" data-form-status tabindex="-1" hidden><?php echo esc_html( $skinlove_success ); ?></p>
</form>
