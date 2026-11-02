<?php
/**
 * Template Name: Выездные услуги
 *
 * Лендинг Outcall: тот же конструктор секций, свои шапка и подвал (решение 24).
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

get_header( 'outcall' );
?>

<main id="main">
	<?php skinlove_render_sections(); ?>
</main>

<?php
get_footer( 'outcall' );
