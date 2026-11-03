<?php
/**
 * Секция «Направления»: горизонтальная лента карточек направлений
 * (inc/directions.php). Без направлений не выводится.
 *
 * id="services" — цель якоря «Направления» из меню и кнопки первого экрана.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_directions = skinlove_directions();

if ( ! $skinlove_directions ) {
	return;
}
?>
<section class="section section--beige services" id="services" data-section="services" data-warm-images>
	<div class="services__viewport">
		<img class="services__aside" src="<?php skinlove_asset_url( 'img/services-aside.webp' ); ?>" width="1024" height="1536" alt="" aria-hidden="true" decoding="async">

		<ul class="services__track" data-services-track>
			<?php foreach ( $skinlove_directions as $skinlove_direction ) : ?>
				<li class="services__item" data-services-item>
					<?php get_template_part( 'template-parts/cards/service-card', null, array( 'direction' => $skinlove_direction ) ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<div class="blinds" aria-hidden="true">
		<span class="blinds__bar" data-blind></span>
		<span class="blinds__bar" data-blind></span>
		<span class="blinds__bar" data-blind></span>
		<span class="blinds__bar" data-blind></span>
		<span class="blinds__bar" data-blind></span>
	</div>
</section>
