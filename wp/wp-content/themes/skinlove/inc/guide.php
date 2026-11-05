<?php
/**
 * «Инструкция»: как управлять сайтом — скриншоты с описанием, по вкладкам.
 *
 * Страница живёт в админке, поэтому открывается только после входа.
 * Ссылки на неё — первым пунктом меню админки, виджетом в начале консоли
 * и в админ-баре. Индексация запрещена заголовком и meta robots, картинки
 * закрыты от индексации в admin/guide/.htaccess.
 *
 * Тексты и список скриншотов — inc/guide-content.php, картинки —
 * admin/guide/{имя}.jpg.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

const SKINLOVE_GUIDE_SLUG = 'skinlove-guide';

/**
 * Кто видит инструкцию: все, кто может редактировать страницы.
 */
const SKINLOVE_GUIDE_CAP = 'edit_pages';

/**
 * Скриншоты сняты с плотностью 2× — в инструкции они показываются
 * в половину своего размера, чтобы узкие кадры не растягивались.
 */
const SKINLOVE_GUIDE_SHOT_SCALE = 2;

/**
 * Адрес страницы инструкции, при необходимости — сразу на вкладке.
 *
 * @param string $tab Ключ вкладки из skinlove_guide_tabs().
 * @return string
 */
function skinlove_guide_url( $tab = '' ) {
	$url = admin_url( 'admin.php?page=' . SKINLOVE_GUIDE_SLUG );

	return '' === $tab ? $url : $url . '#' . $tab;
}

/**
 * Пункт меню — самым первым, над «Консолью».
 */
function skinlove_guide_menu() {
	$hook = add_menu_page(
		'Инструкция по управлению сайтом',
		'Инструкция',
		SKINLOVE_GUIDE_CAP,
		SKINLOVE_GUIDE_SLUG,
		'skinlove_guide_render',
		'dashicons-book-alt',
		1
	);

	add_action( "load-{$hook}", 'skinlove_guide_noindex_header' );
}
add_action( 'admin_menu', 'skinlove_guide_menu' );

/**
 * Запрет индексации заголовком ответа.
 */
function skinlove_guide_noindex_header() {
	header( 'X-Robots-Tag: noindex, nofollow, noarchive', true );
}

/**
 * Запрет индексации в <head> — на случай, если заголовок срежет прокси.
 */
function skinlove_guide_noindex_meta() {
	if ( skinlove_is_guide_screen() ) {
		echo '<meta name="robots" content="noindex, nofollow, noarchive">' . "\n";
	}
}
add_action( 'admin_head', 'skinlove_guide_noindex_meta' );

/**
 * Открыт ли сейчас экран инструкции.
 *
 * @return bool
 */
function skinlove_is_guide_screen() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	return $screen && 'toplevel_page_' . SKINLOVE_GUIDE_SLUG === $screen->id;
}

/**
 * Стили и скрипт: на экране инструкции — всё, на остальных — только
 * выделение пункта меню, чтобы его было видно.
 *
 * @param string $hook Текущий экран админки.
 */
function skinlove_guide_assets( $hook ) {
	wp_add_inline_style(
		'admin-menu',
		'#adminmenu .toplevel_page_' . SKINLOVE_GUIDE_SLUG . ':not(.current) > a { background: #9c6b5a; color: #fff; }'
		. '#adminmenu .toplevel_page_' . SKINLOVE_GUIDE_SLUG . ':not(.current) > a .wp-menu-image::before { color: #fff; }'
		. '#adminmenu .toplevel_page_' . SKINLOVE_GUIDE_SLUG . ' { margin-bottom: 6px; }'
	);

	if ( 'toplevel_page_' . SKINLOVE_GUIDE_SLUG !== $hook ) {
		return;
	}

	$css = get_theme_file_path( 'admin/guide.css' );
	$js  = get_theme_file_path( 'admin/guide.js' );

	wp_enqueue_style( 'skinlove-guide', get_theme_file_uri( 'admin/guide.css' ), array(), (string) filemtime( $css ) );
	wp_enqueue_script( 'skinlove-guide', get_theme_file_uri( 'admin/guide.js' ), array(), (string) filemtime( $js ), true );
}
add_action( 'admin_enqueue_scripts', 'skinlove_guide_assets' );

/**
 * Ссылка в админ-баре — видна и в админке, и на сайте под логином.
 *
 * @param WP_Admin_Bar $bar Админ-бар.
 */
function skinlove_guide_admin_bar( $bar ) {
	if ( ! current_user_can( SKINLOVE_GUIDE_CAP ) ) {
		return;
	}

	$bar->add_node(
		array(
			'id'    => SKINLOVE_GUIDE_SLUG,
			'title' => '<span class="ab-icon dashicons dashicons-book-alt" aria-hidden="true"></span><span class="ab-label">Инструкция</span>',
			'href'  => skinlove_guide_url(),
			'meta'  => array( 'title' => 'Инструкция по управлению сайтом' ),
		)
	);
}
add_action( 'admin_bar_menu', 'skinlove_guide_admin_bar', 25 );

/**
 * Виджет в консоли — первым в левой колонке.
 */
function skinlove_guide_dashboard_widget() {
	if ( ! current_user_can( SKINLOVE_GUIDE_CAP ) ) {
		return;
	}

	wp_add_dashboard_widget( 'skinlove_guide', 'Инструкция по управлению сайтом', 'skinlove_guide_render_widget' );

	global $wp_meta_boxes;

	$normal = $wp_meta_boxes['dashboard']['normal']['core'] ?? array();

	if ( isset( $normal['skinlove_guide'] ) ) {
		$wp_meta_boxes['dashboard']['normal']['core'] = array( 'skinlove_guide' => $normal['skinlove_guide'] ) + $normal; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
add_action( 'wp_dashboard_setup', 'skinlove_guide_dashboard_widget' );

/**
 * Содержимое виджета: кнопка и ссылки на вкладки.
 */
function skinlove_guide_render_widget() {
	?>
	<p>Как поменять тексты, фото, цены, контакты и где смотреть заявки — по шагам, со скриншотами.</p>
	<p><a class="button button-primary" href="<?php echo esc_url( skinlove_guide_url() ); ?>">Открыть инструкцию</a></p>
	<ul style="columns: 2; margin: 0;">
		<?php foreach ( skinlove_guide_tabs() as $key => $tab ) : ?>
			<li><a href="<?php echo esc_url( skinlove_guide_url( $key ) ); ?>"><?php echo esc_html( $tab['title'] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
	<?php
}

/**
 * Вкладки инструкции.
 *
 * @return array<string,array{title:string,icon:string,intro:string,steps:array}>
 */
function skinlove_guide_tabs() {
	static $tabs = null;

	if ( null === $tabs ) {
		$tabs = require get_theme_file_path( 'inc/guide-content.php' );
	}

	return $tabs;
}

/**
 * Разметка, допустимая в текстах инструкции.
 *
 * @return array
 */
function skinlove_guide_allowed_html() {
	return array(
		'p'      => array(),
		'ul'     => array(),
		'ol'     => array(),
		'li'     => array(),
		'strong' => array(),
		'em'     => array(),
		'code'   => array(),
		'kbd'    => array(),
		'br'     => array(),
		'a'      => array(
			'href'   => true,
			'target' => true,
			'rel'    => true,
		),
		'span'   => array( 'class' => true ),
	);
}

/**
 * Текст шага: {admin:путь} → адрес в админке, {tab:ключ} → ссылка на вкладку.
 *
 * @param string $html Текст шага.
 * @return string
 */
function skinlove_guide_text( $html ) {
	$html = preg_replace_callback(
		'/\{admin:([^}]*)\}/',
		fn( $m ) => esc_url( admin_url( $m[1] ) ),
		$html
	);

	$html = preg_replace_callback(
		'/\{tab:([a-z-]+)\}/',
		fn( $m ) => esc_url( '#' . $m[1] ),
		$html
	);

	return wp_kses( $html, skinlove_guide_allowed_html() );
}

/**
 * Страница инструкции.
 */
function skinlove_guide_render() {
	$tabs  = skinlove_guide_tabs();
	$first = array_key_first( $tabs );
	?>
	<div class="wrap sl-guide" data-sl-guide>
		<h1 class="sl-guide__title">Инструкция по управлению сайтом</h1>
		<p class="sl-guide__lead">Выберите раздел. Цифры в тексте совпадают с метками на скриншоте, скриншот открывается в полном размере по клику.</p>

		<div class="sl-guide__layout">
			<nav class="sl-guide__nav" aria-label="Разделы инструкции">
				<div class="sl-guide__tabs" role="tablist" aria-orientation="vertical">
					<?php foreach ( $tabs as $key => $tab ) : ?>
						<a
							class="sl-guide__tab"
							href="#<?php echo esc_attr( $key ); ?>"
							id="sl-guide-tab-<?php echo esc_attr( $key ); ?>"
							role="tab"
							aria-controls="sl-guide-panel-<?php echo esc_attr( $key ); ?>"
							aria-selected="<?php echo $key === $first ? 'true' : 'false'; ?>"
							data-sl-guide-tab="<?php echo esc_attr( $key ); ?>"
						>
							<span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>" aria-hidden="true"></span>
							<span><?php echo esc_html( $tab['title'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			</nav>

			<div class="sl-guide__panels">
				<?php foreach ( $tabs as $key => $tab ) : ?>
					<section
						class="sl-guide__panel"
						id="sl-guide-panel-<?php echo esc_attr( $key ); ?>"
						role="tabpanel"
						aria-labelledby="sl-guide-tab-<?php echo esc_attr( $key ); ?>"
						tabindex="-1"
						data-sl-guide-panel="<?php echo esc_attr( $key ); ?>"
					>
						<h2 class="sl-guide__panel-title"><?php echo esc_html( $tab['title'] ); ?></h2>

						<?php if ( ! empty( $tab['intro'] ) ) : ?>
							<div class="sl-guide__intro"><?php echo skinlove_guide_text( $tab['intro'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<?php endif; ?>

						<ol class="sl-guide__steps">
							<?php foreach ( $tab['steps'] as $step ) : ?>
								<?php skinlove_guide_render_step( $step ); ?>
							<?php endforeach; ?>
						</ol>
					</section>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * Шаг: заголовок, описание, ссылка на раздел и скриншот.
 *
 * @param array $step Шаг из skinlove_guide_tabs().
 */
function skinlove_guide_render_step( $step ) {
	$file  = empty( $step['image'] ) ? '' : "admin/guide/{$step['image']}.jpg";
	$image = '' !== $file && file_exists( get_theme_file_path( $file ) ) ? $file : '';
	?>
	<li class="sl-guide__step">
		<div class="sl-guide__text">
			<h3 class="sl-guide__step-title"><?php echo esc_html( $step['title'] ); ?></h3>
			<?php echo skinlove_guide_text( $step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<?php if ( ! empty( $step['link'] ) ) : ?>
				<p><a class="button" href="<?php echo esc_url( admin_url( $step['link'][0] ) ); ?>"><?php echo esc_html( $step['link'][1] ); ?></a></p>
			<?php endif; ?>
		</div>

		<?php if ( $image ) : ?>
			<?php $size = wp_getimagesize( get_theme_file_path( $image ) ); ?>
			<figure class="sl-guide__shot">
				<a href="<?php echo esc_url( get_theme_file_uri( $image ) ); ?>" target="_blank" rel="noopener">
					<img
						src="<?php echo esc_url( get_theme_file_uri( $image ) ); ?>"
						alt="<?php echo esc_attr( 'Скриншот: ' . $step['title'] ); ?>"
						<?php if ( $size ) : ?>
							width="<?php echo (int) round( $size[0] / SKINLOVE_GUIDE_SHOT_SCALE ); ?>"
							height="<?php echo (int) round( $size[1] / SKINLOVE_GUIDE_SHOT_SCALE ); ?>"
						<?php endif; ?>
						loading="lazy"
						decoding="async"
					>
				</a>
			</figure>
		<?php endif; ?>
	</li>
	<?php
}
