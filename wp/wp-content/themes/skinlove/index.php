<?php
/**
 * Обязательный шаблон WordPress. Блога на сайте нет, все страницы
 * выводит page.php; сюда попадают только служебные запросы.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main"></main>

<?php
get_footer();
