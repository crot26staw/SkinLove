<?php
/**
 * Секция «Атмосфера»: вступление и фото, на котором проявляются
 * особенности. Сердца у особенности — её номер из общего числа.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section  = $args['section'];
$skinlove_label    = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title    = skinlove_entry( $skinlove_section, 'title' );
$skinlove_lead     = skinlove_entry( $skinlove_section, 'lead' );
$skinlove_text     = skinlove_entry( $skinlove_section, 'text' );
$skinlove_image    = (int) ( $skinlove_section['image'] ?? 0 );
$skinlove_features = array();

foreach ( (array) ( $skinlove_section['features'] ?? array() ) as $skinlove_feature ) {
	$skinlove_feature_title = skinlove_entry( $skinlove_feature, 'title' );

	if ( $skinlove_feature_title ) {
		$skinlove_features[] = array(
			'title' => $skinlove_feature_title,
			'text'  => skinlove_entry( $skinlove_feature, 'text' ),
		);
	}
}
?>
<section class="section section--light atmosphere" data-section="atmosphere">
	<div class="atmosphere__head rule" data-rule data-atmosphere-head>
		<?php echo skinlove_icon( 'plus', 'atmosphere__marker', 'data-rule-marker' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>

		<?php if ( $skinlove_label ) : ?>
			<p class="descriptor atmosphere__label"><?php echo esc_html( $skinlove_label ); ?></p>
		<?php endif; ?>

		<div class="atmosphere__intro">
			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 atmosphere__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>

			<?php if ( $skinlove_lead || $skinlove_text ) : ?>
				<div class="atmosphere__texts rule" data-rule>
					<?php if ( $skinlove_lead ) : ?>
						<p class="atmosphere__lead"><?php echo esc_html( $skinlove_lead ); ?></p>
					<?php endif; ?>
					<?php if ( $skinlove_text ) : ?>
						<p class="atmosphere__text"><?php echo esc_html( $skinlove_text ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $skinlove_image ) : ?>
		<div class="atmosphere__media" data-atmosphere-media>
			<div class="atmosphere__preview" data-atmosphere-preview>
				<?php
				echo wp_get_attachment_image(
					$skinlove_image,
					'full',
					false,
					array(
						'class'    => 'atmosphere__preview-image',
						'loading'  => 'lazy',
						'decoding' => 'async',
					)
				);
				?>
			</div>

			<div class="atmosphere__scene" data-atmosphere-scene>
				<?php
				echo wp_get_attachment_image(
					$skinlove_image,
					'full',
					false,
					array(
						'class'                       => 'atmosphere__scene-image',
						'alt'                         => '',
						'aria-hidden'                 => 'true',
						'loading'                     => 'lazy',
						'decoding'                    => 'async',
						'data-atmosphere-scene-image' => '',
					)
				);
				?>

				<?php if ( $skinlove_features ) : ?>
					<ul class="atmosphere__features" data-atmosphere-features>
						<?php foreach ( $skinlove_features as $skinlove_index => $skinlove_feature ) : ?>
							<li class="feature">
								<span class="feature__steps" aria-hidden="true">
									<?php
									foreach ( $skinlove_features as $skinlove_step => $skinlove_unused ) {
										echo skinlove_icon( 'heart', $skinlove_step <= $skinlove_index ? 'feature__heart' : 'feature__heart feature__heart--muted' ); // phpcs:ignore WordPress.Security.EscapeOutput
									}
									?>
								</span>
								<h3 class="feature__title"><?php echo esc_html( $skinlove_feature['title'] ); ?></h3>
								<?php if ( $skinlove_feature['text'] ) : ?>
									<p class="feature__text"><?php echo esc_html( $skinlove_feature['text'] ); ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</section>
