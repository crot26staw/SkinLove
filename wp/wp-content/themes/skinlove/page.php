<?php
/**
 * Все страницы, включая главную: секции из конструктора по порядку.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main">
	<?php skinlove_render_sections(); ?>
</main>

<?php
get_footer();
