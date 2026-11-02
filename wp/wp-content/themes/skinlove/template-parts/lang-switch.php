<?php
/**
 * Переключатель языка: ведёт на ту же страницу на другом языке.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_other = 'en' === skinlove_lang() ? 'ru' : 'en';
?>
	<div class="lang-switch" data-lang-switch>
		<button class="lang-switch__toggle" type="button" aria-label="<?php echo esc_attr( skinlove_t( 'lang_' . skinlove_lang() ) ); ?>" aria-expanded="false" aria-controls="lang-switch-list" data-lang-switch-toggle>
			<span class="lang-switch__label"><?php echo esc_html( skinlove_t( 'lang_label_' . skinlove_lang() ) ); ?></span>
			<?php echo skinlove_icon( 'triangle', 'lang-switch__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</button>
		<ul class="lang-switch__list" id="lang-switch-list" data-lang-switch-list hidden>
			<li class="lang-switch__item" data-lang-switch-item><a class="lang-switch__link" href="<?php echo esc_url( skinlove_lang_url( $skinlove_other ) ); ?>" lang="<?php echo esc_attr( $skinlove_other ); ?>" hreflang="<?php echo esc_attr( $skinlove_other ); ?>"><?php echo esc_html( skinlove_t( 'lang_label_' . $skinlove_other ) ); ?></a></li>
		</ul>
	</div>
