<?php
/**
 * Служебные подписи подвала: плашка разработчика и ссылка на DB-IP —
 * по базе DB-IP выбирается язык по стране (inc/geo.php), лицензия
 * CC BY 4.0 требует указать источник. Название сервиса не переводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="footer__credits">
	<img class="footer__badge" src="<?php skinlove_asset_url( 'img/alfa.svg' ); ?>" width="149" height="20" alt="<?php echo esc_attr( skinlove_t( 'badge_alfa' ) ); ?>" decoding="async">
	<a class="footer__credit" href="https://db-ip.com" target="_blank" rel="noopener">IP Geolocation by DB-IP</a>
</div>
