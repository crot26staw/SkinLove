<?php
/**
 * Инициализация Carbon Fields.
 *
 * Библиотека ставится Composer'ом в vendor/ и в репозиторий не попадает,
 * поэтому её отсутствие — штатная ситуация на свежем сервере. Вместо
 * фатальной ошибки показываем администратору, что нужно сделать.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/**
 * Загружает Carbon Fields, если библиотека установлена.
 */
function skinlove_boot_carbon_fields() {
	$autoload = get_theme_file_path( 'vendor/autoload.php' );

	if ( ! file_exists( $autoload ) ) {
		add_action( 'admin_notices', 'skinlove_carbon_missing_notice' );
		return;
	}

	require_once $autoload;
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', 'skinlove_boot_carbon_fields' );

/**
 * Сообщение в админке, если зависимости не установлены.
 */
function skinlove_carbon_missing_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<div class="notice notice-error"><p><strong>SkinLove:</strong> не установлены зависимости темы. ';
	echo 'Выполните <code>composer install --no-dev</code> в папке темы — без этого поля контента недоступны, ';
	echo 'а секции страниц не выводятся.</p></div>';
}

/**
 * Готова ли библиотека к работе.
 *
 * @return bool
 */
function skinlove_carbon_ready() {
	return function_exists( 'carbon_get_post_meta' );
}
