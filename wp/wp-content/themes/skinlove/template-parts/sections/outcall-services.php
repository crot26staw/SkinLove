<?php
/**
 * Секция «Выездные: каталог услуг» — тот же каталог, что «Программы»
 * (data-section="programs", components/programs.js), но из выездных услуг:
 * три колонки, без вкладки «Все» и цен, подпись под фильтром у каждой
 * категории. Первая вкладка открыта сразу, поэтому услуги категорий без
 * вкладки на текущем языке не выводятся — до них нельзя было бы дойти.
 * Без услуг не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section    = $args['section'];
$skinlove_categories = skinlove_program_categories( 'sl_outcall_category' );
$skinlove_services   = skinlove_programs( $skinlove_categories, 'sl_outcall_service', 'sl_outcall_category' );
$skinlove_used       = array_unique( array_column( $skinlove_services, 'category' ) );
$skinlove_tabs       = array_filter(
	$skinlove_categories,
	fn( $category, $id ) => '' !== $category['name'] && in_array( $id, $skinlove_used, true ),
	ARRAY_FILTER_USE_BOTH
);

if ( $skinlove_tabs ) {
	$skinlove_services = array_values( array_filter( $skinlove_services, fn( $service ) => isset( $skinlove_tabs[ $service['category'] ] ) ) );
}

if ( ! $skinlove_services ) {
	return;
}

$skinlove_title  = skinlove_entry( $skinlove_section, 'title' );
$skinlove_text   = skinlove_entry( $skinlove_section, 'text' );
$skinlove_button = skinlove_entry( $skinlove_section, 'button' );
$skinlove_limit  = max( 0, (int) ( $skinlove_section['limit'] ?? 0 ) );
$skinlove_id     = skinlove_section_uid( 'services' );
$skinlove_grid   = $skinlove_id . '-grid';
$skinlove_first  = array_key_first( $skinlove_tabs );
$skinlove_heart  = skinlove_icon( 'heart-filter', 'filter-tab__icon' );
?>
<section class="section programs programs--padded" id="<?php echo esc_attr( $skinlove_id ); ?>" data-section="programs" data-programs data-programs-columns="3"<?php echo $skinlove_limit ? ' data-programs-limit="' . (int) $skinlove_limit . '"' : ''; ?>>
	<div class="programs__intro">
		<?php if ( $skinlove_title ) : ?>
			<div class="programs__head">
				<h2 class="heading heading--h2-sm programs__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			</div>
		<?php endif; ?>

		<?php if ( $skinlove_text ) : ?>
			<p class="programs__text"><?php echo esc_html( $skinlove_text ); ?></p>
		<?php endif; ?>

		<?php if ( $skinlove_tabs ) : ?>
			<div class="programs__tabs" data-programs-tabs>
				<div class="programs__filters" role="group" aria-label="<?php echo esc_attr( skinlove_t( 'directions' ) ); ?>" data-programs-filters>
					<?php foreach ( $skinlove_tabs as $skinlove_key => $skinlove_category ) : ?>
						<button class="filter-tab filter-tab--beige" type="button" aria-pressed="<?php echo $skinlove_key === $skinlove_first ? 'true' : 'false'; ?>" data-programs-filter="<?php echo esc_attr( $skinlove_category['slug'] ); ?>">
							<?php echo $skinlove_heart; // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<span class="filter-tab__label" data-label="<?php echo esc_attr( $skinlove_category['name'] ); ?>"><span class="filter-tab__text"><?php echo esc_html( $skinlove_category['name'] ); ?></span></span>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<?php foreach ( $skinlove_tabs as $skinlove_key => $skinlove_category ) : ?>
				<?php if ( $skinlove_category['caption'] ) : ?>
					<p class="programs__caption" data-programs-caption="<?php echo esc_attr( $skinlove_category['slug'] ); ?>"<?php echo $skinlove_key === $skinlove_first ? '' : ' hidden'; ?>><?php echo esc_html( $skinlove_category['caption'] ); ?></p>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<ul class="programs__grid"<?php echo $skinlove_limit ? ' id="' . esc_attr( $skinlove_grid ) . '"' : ''; ?> data-programs-grid>
		<?php
		foreach ( $skinlove_services as $skinlove_service ) {
			get_template_part(
				'template-parts/cards/program-card',
				null,
				array(
					'program'  => $skinlove_service,
					'category' => $skinlove_categories[ $skinlove_service['category'] ]['slug'] ?? '',
					'tag'      => '',
					'button'   => $skinlove_button,
					'pill'     => true,
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
