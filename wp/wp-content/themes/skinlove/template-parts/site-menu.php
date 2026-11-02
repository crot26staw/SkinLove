<?php
/**
 * Открывающееся меню: навигация, направления с превью, контакты.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_directions = skinlove_directions();
$skinlove_preview    = skinlove_current_direction( $skinlove_directions );
$skinlove_phones     = skinlove_phones();
$skinlove_email      = skinlove_option_raw( 'email' );
$skinlove_address    = skinlove_option( 'address' );
$skinlove_socials    = skinlove_socials();
?>
	<div class="site-menu" id="site-menu" data-menu data-lenis-prevent hidden>
		<div class="site-menu__inner">
			<nav class="site-menu__nav" aria-label="<?php echo esc_attr( skinlove_t( 'menu_nav' ) ); ?>">
				<p class="site-menu__label"><?php echo esc_html( skinlove_t( 'menu_nav_label' ) ); ?></p>

				<?php skinlove_nav_menu( 'nav', 'menu site-menu__pages' ); ?>
			</nav>

			<div class="site-menu__aside">
				<?php if ( $skinlove_directions ) : ?>
					<nav class="site-menu__directions" aria-label="<?php echo esc_attr( skinlove_t( 'directions' ) ); ?>" data-menu-directions>
						<p class="site-menu__label"><?php echo esc_html( skinlove_t( 'directions' ) ); ?></p>

						<div class="site-menu__preview">
							<img class="site-menu__preview-image" data-menu-preview src="<?php echo esc_url( skinlove_direction_image_url( $skinlove_preview ) ); ?>" width="720" height="1280" alt="" decoding="async">
						</div>

						<ul class="menu site-menu__list">
							<?php foreach ( $skinlove_directions as $skinlove_direction ) : ?>
								<li class="menu-item<?php echo $skinlove_direction['current'] ? ' current-menu-item' : ''; ?>"><a href="<?php echo esc_url( $skinlove_direction['url'] ); ?>" data-menu-direction data-image="<?php echo esc_url( skinlove_direction_image_url( $skinlove_direction ) ); ?>"<?php echo $skinlove_direction['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $skinlove_direction['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>

						<div class="site-menu__hearts" aria-hidden="true">
							<?php foreach ( $skinlove_directions as $skinlove_direction ) : ?>
								<?php echo skinlove_icon( 'heart', 'site-menu__heart', 'data-menu-heart' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<?php endforeach; ?>
						</div>
					</nav>
				<?php endif; ?>

				<div class="site-menu__contacts">
					<?php if ( $skinlove_phones || $skinlove_email ) : ?>
						<div class="site-menu__group">
							<p class="site-menu__label"><?php echo esc_html( skinlove_t( 'contacts' ) ); ?></p>

							<ul class="menu site-menu__list">
								<?php foreach ( $skinlove_phones as $skinlove_phone ) : ?>
									<li class="menu-item"><a href="<?php echo esc_url( $skinlove_phone['href'] ); ?>"><?php echo esc_html( $skinlove_phone['label'] ); ?></a></li>
								<?php endforeach; ?>
								<?php if ( $skinlove_email ) : ?>
									<li class="menu-item"><a href="<?php echo esc_url( 'mailto:' . $skinlove_email ); ?>"><?php echo esc_html( $skinlove_email ); ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $skinlove_address ) : ?>
						<div class="site-menu__group">
							<p class="site-menu__label"><?php echo esc_html( skinlove_t( 'address' ) ); ?></p>

							<ul class="menu site-menu__list">
								<li class="menu-item"><a href="<?php echo esc_url( skinlove_address_url( $skinlove_address ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $skinlove_address ); ?></a></li>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $skinlove_socials ) : ?>
						<div class="site-menu__group">
							<p class="site-menu__label"><?php echo esc_html( skinlove_t( 'socials' ) ); ?></p>

							<div class="site-menu__socials">
								<?php foreach ( $skinlove_socials as $skinlove_key => $skinlove_url ) : ?>
									<a class="social-button" href="<?php echo esc_url( $skinlove_url ); ?>" aria-label="<?php echo esc_attr( skinlove_t( $skinlove_key ) ); ?>" target="_blank" rel="noopener"><?php echo skinlove_icon( 'social', 'social-button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
