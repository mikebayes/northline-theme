<?php
/**
 * Primary navigation.
 *
 * Managed at Appearance → Menus. Add the CSS class "nl-menu-cta" to any menu
 * item (Screen Options → CSS Classes) to render it as a button.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

if ( ! has_nav_menu( 'primary' ) ) {
	return;
}
?>
<nav
	id="primary-navigation"
	class="primary-navigation"
	aria-label="<?php esc_attr_e( 'Primary', 'northline' ); ?>"
	data-nav-panel
>
	<?php
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'menu_class'     => 'primary-menu',
			'container'      => false,
			'depth'          => 2,
			'fallback_cb'    => false,
		)
	);
	?>
</nav>
