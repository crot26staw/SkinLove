<?php
/**
 * Секция «Программы»: каталог программ IV с фильтром по категориям.
 * Вкладка выводится у категории, в которой есть программа на текущем
 * языке; фильтрует components/programs.js. Без программ не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section    = $args['section'];
$skinlove_categories = skinlove_program_categories();
$skinlove_programs   = skinlove_programs( $skinlove_categories );

if ( ! $skinlove_programs ) {
	return;
}

$skinlove_label  = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title  = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text   = skinlove_entry( $skinlove_section, 'text' );
$skinlove_button = skinlove_entry( $skinlove_section, 'button' );
$skinlove_limit  = max( 0, (int) ( $skinlove_section['limit'] ?? 0 ) );
$skinlove_grid   = skinlove_section_uid( 'programs' ) . '-grid';
$skinlove_used   = array_unique( array_column( $skinlove_programs, 'category' ) );
$skinlove_tabs   = array_filter(
	$skinlove_categories,
	fn( $category, $id ) => '' !== $category['name'] && in_array( $id, $skinlove_used, true ),
	ARRAY_FILTER_USE_BOTH
);
$skinlove_heart  = skinlove_icon( 'heart-filter', 'filter-tab__icon' );
?>
<section class="section programs" data-section="programs" data-programs<?php echo $skinlove_limit ? ' data-programs-limit="' . (int) $skinlove_limit . '"' : ''; ?>>
	<div class="programs__intro">
		<?php if ( $skinlove_label || $skinlove_title ) : ?>
			<div class="programs__head">
				<?php if ( $skinlove_label ) : ?>
					<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
				<?php endif; ?>

				<?php if ( $skinlove_title ) : ?>
					<h2 class="heading heading--h2 programs__title" data-heading-fill>
						<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</h2>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_text ) : ?>
			<p class="programs__text"><?php echo esc_html( $skinlove_text ); ?></p>
		<?php endif; ?>

		<?php if ( $skinlove_tabs ) : ?>
			<div class="programs__tabs" data-programs-tabs>
				<div class="programs__filters" role="group" aria-label="<?php echo esc_attr( skinlove_t( 'programs_filters' ) ); ?>" data-programs-filters>
					<?php
					$skinlove_filters = array( 'all' => skinlove_t( 'programs_all' ) );

					foreach ( $skinlove_tabs as $skinlove_category ) {
						$skinlove_filters[ $skinlove_category['slug'] ] = $skinlove_category['name'];
					}

					foreach ( $skinlove_filters as $skinlove_value => $skinlove_name ) :
						?>
						<button class="filter-tab" type="button" aria-pressed="<?php echo 'all' === $skinlove_value ? 'true' : 'false'; ?>" data-programs-filter="<?php echo esc_attr( $skinlove_value ); ?>">
							<?php echo $skinlove_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="filter-tab__label" data-label="<?php echo esc_attr( $skinlove_name ); ?>"><span class="filter-tab__text"><?php echo esc_html( $skinlove_name ); ?></span></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<ul class="programs__grid"<?php echo $skinlove_limit ? ' id="' . esc_attr( $skinlove_grid ) . '"' : ''; ?> data-programs-grid>
		<?php
		foreach ( $skinlove_programs as $skinlove_program ) {
			$skinlove_category = $skinlove_categories[ $skinlove_program['category'] ] ?? null;

			get_template_part(
				'template-parts/cards/program-card',
				null,
				array(
					'program'  => $skinlove_program,
					'category' => $skinlove_category['slug'] ?? '',
					'tag'      => $skinlove_category['tag'] ?? '',
					'button'   => $skinlove_button,
				)
			);
		}
		?>
	</ul>

	<?php if ( $skinlove_limit ) : ?>
		<button class="button button--pill programs__more" type="button" aria-controls="<?php echo esc_attr( $skinlove_grid ); ?>" aria-expanded="false" data-programs-more data-label-more="<?php echo esc_attr( skinlove_t( 'programs_more' ) ); ?>" data-label-less="<?php echo esc_attr( skinlove_t( 'programs_less' ) ); ?>" hidden>
			<?php echo skinlove_icon( 'heart-filter', 'button__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="button__label"><?php echo esc_html( skinlove_t( 'programs_more' ) ); ?></span>
		</button>
	<?php endif; ?>
</section>
