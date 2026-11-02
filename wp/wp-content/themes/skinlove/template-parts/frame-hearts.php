<?php
/**
 * Три сердца в углу карточки frame — общие для frame-inset и frame-reel.
 *
 * @package SkinLove
 */

defined( 'ABSPATH' ) || exit;
?>
<span class="frame__hearts" aria-hidden="true"><?php echo skinlove_icon( 'heart', 'frame__heart frame__heart--muted' ) . skinlove_icon( 'heart', 'frame__heart' ) . skinlove_icon( 'heart', 'frame__heart frame__heart--muted' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
