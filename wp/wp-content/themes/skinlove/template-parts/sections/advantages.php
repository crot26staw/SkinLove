<?php
/**
 * Секция «Преимущества»: горизонтальная лента — вступление с логотипом
 * и карточки. Без карточек не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title' => $skinlove_item_title,
			'text'  => skinlove_entry( $skinlove_item, 'text' ),
			'image' => (int) ( $skinlove_item['image'] ?? 0 ),
		);
	}
}

if ( ! $skinlove_items ) {
	return;
}
?>
<section class="section section--light advantages" data-section="advantages">
	<div class="advantages__viewport">
		<ul class="advantages__track" data-advantages-track>
			<li class="advantages__intro">
				<img class="advantages__logo" src="<?php skinlove_asset_url( 'img/logo-black.png' ); ?>" width="2402" height="808" alt="SkinLove" decoding="async">

				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2 advantages__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>

				<span class="advantages__steps" aria-hidden="true"><?php echo skinlove_icon( 'heart', 'advantages__heart advantages__heart--muted' ) . skinlove_icon( 'heart', 'advantages__heart' ) . skinlove_icon( 'heart', 'advantages__heart advantages__heart--muted' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>

				<span class="advantages__marks" aria-hidden="true">
					<?php
					foreach ( array( 'top-left', 'top-right', 'bottom-left', 'bottom-right' ) as $skinlove_corner ) {
						echo skinlove_icon( 'plus-small', "advantages__mark advantages__mark--{$skinlove_corner}" ); // phpcs:ignore WordPress.Security.EscapeOutput
					}
					?>
				</span>
			</li>

			<?php foreach ( $skinlove_items as $skinlove_item ) : ?>
				<li class="advantage">
					<div class="advantage__body" data-advantage-body>
						<?php if ( $skinlove_item['image'] ) : ?>
							<div class="advantage__media">
								<?php
								echo wp_get_attachment_image(
									$skinlove_item['image'],
									'full',
									false,
									array(
										'class'    => 'advantage__image',
										'loading'  => false,
										'decoding' => 'async',
									)
								);
								?>
							</div>
						<?php endif; ?>
						<h3 class="advantage__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
						<?php if ( $skinlove_item['text'] ) : ?>
							<p class="advantage__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<span class="advantages__line rule" data-rule aria-hidden="true"></span>
		<?php echo skinlove_icon( 'plus', 'advantages__marker', 'data-rule-marker' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
	</div>
</section>
