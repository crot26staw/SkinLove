<?php
/**
 * Секция «Выездные: подбор услуги» — анкета по шагам (components/quiz.js)
 * с заявкой в конце. Подписи вариантов на языке страницы уходят в заявку
 * ответами (решение 26): concern[], concern_other, place, date, date_unknown,
 * address, address_unknown — подписи ключей в inc/leads.php.
 *
 * Шаг без вопроса пропускается, как и шаг с вариантами без вариантов и шаг
 * даты без времени; счётчик считает только выведенные. Шаг контактов есть
 * всегда — без остальных секция становится простой формой заявки, а без
 * них и без заголовка не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text    = skinlove_entry( $skinlove_section, 'text' );
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );

$skinlove_options = static function ( $name ) use ( $skinlove_section ) {
	$labels = array();

	foreach ( (array) ( $skinlove_section[ $name ] ?? array() ) as $option ) {
		$label = skinlove_entry( $option, 'label' );

		if ( $label ) {
			$labels[] = $label;
		}
	}

	return $labels;
};

$skinlove_steps = array();

foreach ( array( 'concern', 'place', 'date', 'address' ) as $skinlove_kind ) {
	$skinlove_step = array(
		'kind'  => $skinlove_kind,
		'title' => skinlove_entry( $skinlove_section, "{$skinlove_kind}_title" ),
		'text'  => skinlove_entry( $skinlove_section, "{$skinlove_kind}_text" ),
	);

	if ( 'concern' === $skinlove_kind ) {
		$skinlove_step['options']     = $skinlove_options( 'concern_options' );
		$skinlove_step['other']       = skinlove_entry( $skinlove_section, 'other_label' );
		$skinlove_step['placeholder'] = skinlove_entry( $skinlove_section, 'other_placeholder' );
		$skinlove_ready               = $skinlove_step['options'] || $skinlove_step['other'];
	} elseif ( 'place' === $skinlove_kind ) {
		$skinlove_step['options'] = $skinlove_options( 'place_options' );
		$skinlove_ready           = (bool) $skinlove_step['options'];
	} elseif ( 'date' === $skinlove_kind ) {
		$skinlove_step['times'] = skinlove_clean( $skinlove_section['times'] ?? '' );
		$skinlove_ready         = '' !== $skinlove_step['times'];
	} else {
		$skinlove_ready = true;
	}

	if ( $skinlove_step['title'] && $skinlove_ready ) {
		$skinlove_steps[] = $skinlove_step;
	}
}

if ( ! $skinlove_title && ! $skinlove_steps ) {
	return;
}

$skinlove_steps[] = array(
	'kind'  => 'contacts',
	'title' => skinlove_entry( $skinlove_section, 'contacts_title' ),
	'text'  => skinlove_entry( $skinlove_section, 'contacts_text' ),
);

$skinlove_total = str_pad( (string) count( $skinlove_steps ), 2, '0', STR_PAD_LEFT );
$skinlove_heart = skinlove_icon( 'heart-filter', 'button__icon' );
?>
<section class="section section--beige quiz" data-section="quiz">
	<?php if ( $skinlove_title || $skinlove_text ) : ?>
		<div class="quiz__intro">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2-sm quiz__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_text ) : ?>
				<p class="quiz__text"><?php echo esc_html( $skinlove_text ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<form class="form quiz__form" action="<?php skinlove_lead_action(); ?>" method="post" novalidate data-form data-quiz>
		<?php skinlove_lead_fields( 'quiz', 'quiz' ); ?>

		<div class="quiz__top">
			<?php if ( $skinlove_label ) : ?>
				<p class="quiz__label"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>
			<p class="quiz__counter" data-quiz-counter>01/<?php echo esc_html( $skinlove_total ); ?></p>
		</div>

		<div class="quiz__progress" aria-hidden="true">
			<span class="quiz__progress-bar" data-quiz-progress></span>
		</div>

		<?php foreach ( $skinlove_steps as $skinlove_index => $skinlove_step ) : ?>
			<div class="quiz__step" role="group"<?php echo $skinlove_step['title'] ? ' aria-label="' . esc_attr( $skinlove_step['title'] ) . '"' : ''; ?> data-quiz-step<?php echo $skinlove_index ? ' hidden' : ''; ?>>
				<?php if ( $skinlove_step['title'] || $skinlove_step['text'] ) : ?>
					<div class="quiz__step-head">
						<?php if ( $skinlove_step['title'] ) : ?>
							<h3 class="quiz__step-title" tabindex="-1"><?php echo esc_html( $skinlove_step['title'] ); ?></h3>
						<?php endif; ?>
						<?php if ( $skinlove_step['text'] ) : ?>
							<p class="quiz__step-text"><?php echo esc_html( $skinlove_step['text'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( 'concern' === $skinlove_step['kind'] ) : ?>
					<div class="quiz__options">
						<?php foreach ( $skinlove_step['options'] as $skinlove_option ) : ?>
							<label class="quiz__option">
								<input class="quiz__control" type="checkbox" name="concern[]" value="<?php echo esc_attr( $skinlove_option ); ?>">
								<span class="quiz__option-label"><?php echo esc_html( $skinlove_option ); ?></span>
							</label>
						<?php endforeach; ?>
						<?php if ( $skinlove_step['other'] ) : ?>
							<div class="quiz__option quiz__option--other">
								<input class="quiz__control" type="checkbox" name="concern[]" value="<?php echo esc_attr( $skinlove_step['other'] ); ?>" aria-label="<?php echo esc_attr( $skinlove_step['other'] ); ?>" data-quiz-other>
								<span class="quiz__option-label" aria-hidden="true"><?php echo esc_html( $skinlove_step['other'] ); ?></span>
								<input class="quiz__option-field" type="text" name="concern_other"<?php echo $skinlove_step['placeholder'] ? ' placeholder="' . esc_attr( $skinlove_step['placeholder'] ) . '"' : ''; ?> aria-label="<?php echo esc_attr( rtrim( $skinlove_step['title'], '?' ) ); ?>" data-quiz-other-field>
							</div>
						<?php endif; ?>
					</div>

					<p class="quiz__error" data-quiz-error hidden><?php echo esc_html( skinlove_t( 'quiz_error_many' ) ); ?></p>
				<?php elseif ( 'place' === $skinlove_step['kind'] ) : ?>
					<div class="quiz__options">
						<?php foreach ( $skinlove_step['options'] as $skinlove_option ) : ?>
							<label class="quiz__option quiz__option--round">
								<input class="quiz__control" type="radio" name="place" value="<?php echo esc_attr( $skinlove_option ); ?>">
								<span class="quiz__option-label"><?php echo esc_html( $skinlove_option ); ?></span>
							</label>
						<?php endforeach; ?>
					</div>

					<p class="quiz__error" data-quiz-error hidden><?php echo esc_html( skinlove_t( 'quiz_error_one' ) ); ?></p>
				<?php elseif ( 'date' === $skinlove_step['kind'] ) : ?>
					<div class="quiz__options quiz__options--stack">
						<div
							class="date-picker"
							data-date-picker
							data-times="<?php echo esc_attr( $skinlove_step['times'] ); ?>"
							data-locale="<?php echo esc_attr( skinlove_t( 'date_locale' ) ); ?>"
							data-label-prev-month="<?php echo esc_attr( skinlove_t( 'date_prev_month' ) ); ?>"
							data-label-next-month="<?php echo esc_attr( skinlove_t( 'date_next_month' ) ); ?>"
							data-label-prev-day="<?php echo esc_attr( skinlove_t( 'date_prev_day' ) ); ?>"
							data-label-next-day="<?php echo esc_attr( skinlove_t( 'date_next_day' ) ); ?>"
							data-label-time="<?php echo esc_attr( skinlove_t( 'date_time' ) ); ?>"
							data-label-hint="<?php echo esc_attr( skinlove_t( 'date_hint' ) ); ?>">
							<button class="quiz__option date-picker__toggle" type="button" aria-expanded="false" data-date-picker-toggle>
								<?php echo skinlove_icon( 'calendar', 'quiz__option-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
								<span class="quiz__option-label" data-date-picker-value><?php echo esc_html( skinlove_t( 'date_placeholder' ) ); ?></span>
							</button>

							<input type="hidden" name="date" data-date-picker-input data-quiz-value>

							<div class="date-picker__popup" role="dialog" aria-label="<?php echo esc_attr( skinlove_t( 'date_dialog' ) ); ?>" data-date-picker-popup hidden></div>
						</div>

						<label class="quiz__option quiz__option--round">
							<input class="quiz__control" type="checkbox" name="date_unknown" value="<?php echo esc_attr( skinlove_t( 'quiz_unknown' ) ); ?>" data-quiz-unknown>
							<span class="quiz__option-label"><?php echo esc_html( skinlove_t( 'quiz_unknown' ) ); ?></span>
						</label>
					</div>

					<p class="quiz__error" data-quiz-error hidden><?php echo esc_html( skinlove_t( 'quiz_error_date' ) ); ?></p>
				<?php elseif ( 'address' === $skinlove_step['kind'] ) : ?>
					<div class="quiz__options quiz__options--stack">
						<label class="quiz__option quiz__option--input">
							<?php echo skinlove_icon( 'pin', 'quiz__option-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<input class="quiz__option-field" type="text" name="address" placeholder="<?php echo esc_attr( skinlove_t( 'quiz_address' ) ); ?>" autocomplete="street-address" data-quiz-value>
						</label>

						<label class="quiz__option quiz__option--round">
							<input class="quiz__control" type="checkbox" name="address_unknown" value="<?php echo esc_attr( skinlove_t( 'quiz_unknown' ) ); ?>" data-quiz-unknown>
							<span class="quiz__option-label"><?php echo esc_html( skinlove_t( 'quiz_unknown' ) ); ?></span>
						</label>
					</div>

					<p class="quiz__error" data-quiz-error hidden><?php echo esc_html( skinlove_t( 'quiz_error_address' ) ); ?></p>
				<?php else : ?>
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

					<button class="button button--pill form__submit" type="submit">
						<?php echo $skinlove_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?>
						<span class="button__label"><?php echo esc_html( skinlove_t( 'quiz_submit' ) ); ?></span>
					</button>

					<?php skinlove_form_failure(); ?>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>

		<p class="form__status" data-form-status tabindex="-1" hidden><?php echo esc_html( skinlove_t( 'form_success' ) ); ?></p>

		<div class="quiz__nav">
			<button class="button button--pill button--pill-beige" type="button" data-quiz-back hidden>
				<span class="button__label"><?php echo esc_html( skinlove_t( 'quiz_back' ) ); ?></span>
			</button>

			<button class="button button--pill" type="button" data-quiz-next>
				<?php echo $skinlove_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="button__label"><?php echo esc_html( skinlove_t( 'quiz_next' ) ); ?></span>
			</button>
		</div>
	</form>
</section>
