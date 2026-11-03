<?php
/**
 * Язык по стране посетителя: из России и Беларуси — русская версия,
 * из остальных стран — английская.
 *
 * Срабатывает один раз, на первом заходе: решение запоминается в cookie,
 * и дальше сайт посетителя не перебрасывает — ручной выбор в переключателе
 * языка остаётся за ним. Ботов не трогаем: поисковики обходят сайт с
 * американских адресов, и без исключения русская версия выпала бы из индекса.
 * Если страну определить не удалось, посетитель остаётся на открытой странице.
 *
 * Страну ищем по IP в базе DB-IP IP to Country Lite (формат MMDB, читает
 * maxmind-db/reader из vendor/). GeoLite2 от MaxMind из России не выдают,
 * а DB-IP скачивается без регистрации и ключей. Лицензия CC BY 4.0
 * требует ссылку на DB-IP — она в подвале (template-parts/footer-geo-credit.php).
 * База лежит в uploads: тема перезаписывается при выкладке. Новая база
 * выходит раз в месяц, wp-cron проверяет раз в неделю.
 *
 * Без базы редиректа нет, администратору показывается уведомление.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

/** Страны, которым показываем русскую версию (ISO 3166-1 alpha-2). */
const SKINLOVE_GEO_RU_COUNTRIES = array( 'RU', 'BY' );

/** Cookie с решением: 'ru' | 'en'. Год — дальше решает переключатель. */
const SKINLOVE_GEO_COOKIE = 'skinlove_lang';

/** Хук обновления базы в wp-cron. */
const SKINLOVE_GEO_UPDATE_HOOK = 'skinlove_geoip_update';

/** Опция с месяцем скачанной базы, 'YYYY-MM'. */
const SKINLOVE_GEO_MONTH_OPTION = 'skinlove_geoip_month';

/**
 * Путь к файлу базы.
 *
 * @return string
 */
function skinlove_geo_db_path() {
	return wp_upload_dir( null, false )['basedir'] . '/skinlove-geoip/dbip-country-lite.mmdb';
}

/**
 * Код страны по IP или '' — если базы нет, адрес не найден или не разобран.
 *
 * @param string $ip IPv4 или IPv6.
 * @return string
 */
function skinlove_geo_country( $ip ) {
	$file = skinlove_geo_db_path();

	if ( ! class_exists( '\MaxMind\Db\Reader' ) || ! is_readable( $file ) || ! filter_var( $ip, FILTER_VALIDATE_IP ) ) {
		return '';
	}

	try {
		$reader = new \MaxMind\Db\Reader( $file );
		$record = $reader->get( $ip );
		$reader->close();
	} catch ( \Exception $e ) {
		return '';
	}

	return strtoupper( (string) ( $record['country']['iso_code'] ?? '' ) );
}

/**
 * Поисковые и прочие роботы, а также запросы без User-Agent.
 *
 * @return bool
 */
function skinlove_geo_is_bot() {
	$agent = (string) ( $_SERVER['HTTP_USER_AGENT'] ?? '' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	return '' === $agent || (bool) preg_match( '/bot|crawl|spider|slurp|mediapartners|facebookexternalhit|preview|lighthouse|headless|curl|wget/i', $agent );
}

/**
 * Первый заход: определяет язык по стране и, если открыта страница
 * на другом языке, уводит на ту же страницу на нужном.
 */
function skinlove_geo_redirect() {
	if ( isset( $_COOKIE[ SKINLOVE_GEO_COOKIE ] ) || is_preview() || is_feed() || is_robots() || get_query_var( 'sitemap' ) ) {
		return;
	}

	if ( ! in_array( $_SERVER['REQUEST_METHOD'] ?? 'GET', array( 'GET', 'HEAD' ), true ) || skinlove_geo_is_bot() ) {
		return;
	}

	$country = skinlove_geo_country( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$current = skinlove_lang();
	$lang    = '' === $country ? $current : ( in_array( $country, SKINLOVE_GEO_RU_COUNTRIES, true ) ? 'ru' : 'en' );

	setcookie(
		SKINLOVE_GEO_COOKIE,
		$lang,
		array(
			'expires'  => time() + YEAR_IN_SECONDS,
			'path'     => COOKIEPATH ? COOKIEPATH : '/',
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);

	if ( $lang === $current ) {
		return;
	}

	$query = (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_QUERY ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput

	nocache_headers();
	wp_safe_redirect( skinlove_lang_url( $lang ) . ( '' === $query ? '' : '?' . $query ), 302, 'SkinLove' );
	exit;
}
add_action( 'template_redirect', 'skinlove_geo_redirect', 1 );

/**
 * Ставит еженедельное обновление базы; если базы ещё нет — разовое сразу.
 */
function skinlove_geo_schedule() {
	if ( ! wp_next_scheduled( SKINLOVE_GEO_UPDATE_HOOK ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'weekly', SKINLOVE_GEO_UPDATE_HOOK );
	}

	if ( ! file_exists( skinlove_geo_db_path() ) && ! wp_next_scheduled( SKINLOVE_GEO_UPDATE_HOOK . '_now' ) ) {
		wp_schedule_single_event( time(), SKINLOVE_GEO_UPDATE_HOOK . '_now' );
	}
}
add_action( 'init', 'skinlove_geo_schedule' );

/**
 * Скачивает базу текущего месяца и подменяет файл. В первые дни месяца
 * новой базы может ещё не быть — тогда берём прошлый месяц. Если база
 * этого месяца уже лежит, ничего не делаем.
 *
 * @return true|WP_Error
 */
function skinlove_geo_update() {
	$file   = skinlove_geo_db_path();
	$months = array( gmdate( 'Y-m' ), gmdate( 'Y-m', strtotime( 'first day of last month' ) ) );

	if ( file_exists( $file ) && get_option( SKINLOVE_GEO_MONTH_OPTION ) === $months[0] ) {
		return true;
	}

	// wp_tempnam() — из админки: WP-CLI её подгружает, wp-cron.php нет.
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$archive = wp_tempnam( 'dbip-country-lite' );
	$month   = null;

	foreach ( $months as $candidate ) {
		$download = wp_remote_get(
			"https://download.db-ip.com/free/dbip-country-lite-{$candidate}.mmdb.gz",
			array(
				'timeout'  => 120,
				'stream'   => true,
				'filename' => $archive,
			)
		);

		if ( ! is_wp_error( $download ) && 200 === wp_remote_retrieve_response_code( $download ) ) {
			$month = $candidate;
			break;
		}
	}

	if ( ! $month ) {
		wp_delete_file( $archive );
		return new WP_Error( 'skinlove_geo_download', 'Не удалось скачать базу DB-IP.' );
	}

	if ( $month === get_option( SKINLOVE_GEO_MONTH_OPTION ) && file_exists( $file ) ) {
		wp_delete_file( $archive );
		return true;
	}

	wp_mkdir_p( dirname( $file ) );

	/* Распаковываем во временный файл рядом, проверяем, что база читается,
	   и только потом rename: открытый в этот момент запрос не прочитает
	   базу наполовину. */
	$partial = $file . '.part';
	$result  = true;

	try {
		$in  = gzopen( $archive, 'rb' );
		$out = fopen( $partial, 'wb' ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		if ( ! $in || ! $out ) {
			throw new Exception( 'Не удалось открыть архив или ' . $partial );
		}

		while ( ! gzeof( $in ) ) {
			fwrite( $out, gzread( $in, 1 << 20 ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		}

		gzclose( $in );
		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		( new \MaxMind\Db\Reader( $partial ) )->close();

		if ( ! rename( $partial, $file ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions
			throw new Exception( 'Не удалось записать базу в ' . $file );
		}

		update_option( SKINLOVE_GEO_MONTH_OPTION, $month, false );
	} catch ( \Exception $e ) {
		wp_delete_file( $partial );
		$result = new WP_Error( 'skinlove_geo_extract', $e->getMessage() );
	}

	wp_delete_file( $archive );

	return $result;
}
add_action( SKINLOVE_GEO_UPDATE_HOOK, 'skinlove_geo_update' );
add_action( SKINLOVE_GEO_UPDATE_HOOK . '_now', 'skinlove_geo_update' );

/**
 * Уведомление администратору, пока базы нет: без неё язык по стране
 * не выбирается.
 */
function skinlove_geo_missing_notice() {
	if ( ! current_user_can( 'manage_options' ) || file_exists( skinlove_geo_db_path() ) ) {
		return;
	}

	echo '<div class="notice notice-warning"><p><strong>SkinLove:</strong> база стран DB-IP ещё не скачана — язык по стране посетителя не выбирается. ';
	echo 'Она скачается при ближайшем запуске wp-cron; если уведомление не уходит, проверьте, что сервер может обращаться к download.db-ip.com.</p></div>';
}
add_action( 'admin_notices', 'skinlove_geo_missing_notice' );
