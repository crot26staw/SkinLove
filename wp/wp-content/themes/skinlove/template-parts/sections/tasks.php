<?php
/**
 * Секция «Задачи»: заголовок и карточки задач с тремя сердцами.
 * Без карточек не выводится.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;

$skinlove_section = $args['section'];
$skinlove_label   = skinlove_entry( $skinlove_section, 'label' );
$skinlove_title   = skinlove_entry( $skinlove_section, 'title' );
$skinlove_items   = array();

foreach ( (array) ( $skinlove_section['items'] ?? array() ) as $skinlove_item ) {
	$skinlove_item_title = skinlove_entry( $skinlove_item, 'title' );

	if ( $skinlove_item_title ) {
		$skinlove_items[] = array(
			'title' => $skinlove_item_title,
			'text'  => skinlove_entry( $skinlove_item, 'text' ),
		);
	}
}

if ( ! $skinlove_items ) {
	return;
}

$skinlove_hearts = skinlove_icon( 'heart', 'task-card__heart task-card__heart--muted' )
	. skinlove_icon( 'heart', 'task-card__heart' )
	. skinlove_icon( 'heart', 'task-card__heart task-card__heart--muted' );
?>
<section class="section section--light tasks" data-section="tasks" data-tasks>
	<?php if ( $skinlove_label || $skinlove_title ) : ?>
		<div class="tasks__head">
			<?php if ( $skinlove_label ) : ?>
				<p class="descriptor"><?php echo esc_html( $skinlove_label ); ?></p>
			<?php endif; ?>

			<?php if ( $skinlove_title ) : ?>
				<h2 class="heading heading--h2 tasks__title" data-heading-fill>
					<?php echo skinlove_heading( $skinlove_title ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</h2>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<ul class="tasks__list" data-tasks-list>
		<?php foreach ( $skinlove_items as $skinlove_item ) : ?>
			<li class="task-card" data-task>
				<span class="task-card__hearts" aria-hidden="true"><?php echo $skinlove_hearts; // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<h3 class="task-card__title"><?php echo esc_html( $skinlove_item['title'] ); ?></h3>
				<?php if ( $skinlove_item['text'] ) : ?>
					<p class="task-card__text"><?php echo esc_html( $skinlove_item['text'] ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
