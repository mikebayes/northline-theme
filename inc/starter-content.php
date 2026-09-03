<?php
/**
 * Native WordPress starter content.
 *
 * WordPress only offers starter content on a genuinely fresh site — the moment
 * anything has been published or edited, `is_fresh_site()` is false and this is
 * silently skipped. That covers the ideal case (a brand-new install) and
 * nothing else, which is why the theme also ships an explicit, idempotent
 * provisioning command: see inc/provisioning.php and `wp northline provision`.
 *
 * Both paths read the same manifest, so they always describe the same site.
 * Everything either one creates lands in the database as ordinary Pages and
 * menu items — a starting point for editors, not a substitute for the CMS.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register starter content for fresh installs.
 *
 * @return void
 */
function northline_starter_content() {
	$manifest = northline_provisioning_manifest();

	$posts      = array();
	$menu_items = array();

	foreach ( $manifest['pages'] as $slug => $page ) {
		$posts[ $slug ] = array(
			'post_type'    => 'page',
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_excerpt' => $page['excerpt'],
			'post_content' => $page['pattern'] ? northline_get_pattern_content( $page['pattern'] ) : '',
		);

		$menu_items[ 'page_' . $slug ] = array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{' . $slug . '}}',
			'classes'   => $page['menu_classes'],
		);
	}

	$nav_menus = array();

	foreach ( $manifest['menus'] as $location => $menu ) {
		$nav_menus[ $location ] = array(
			'name'  => $menu['name'],
			'items' => $menu_items,
		);
	}

	add_theme_support(
		'starter-content',
		array(
			'posts'     => $posts,
			'options'   => array(
				'show_on_front'   => 'page',
				'page_on_front'   => '{{' . $manifest['front_page'] . '}}',
				'page_for_posts'  => '{{' . $manifest['posts_page'] . '}}',
				'blogname'        => $manifest['settings']['blogname'],
				'blogdescription' => $manifest['settings']['blogdescription'],
			),
			'nav_menus' => $nav_menus,
		)
	);
}
add_action( 'after_setup_theme', 'northline_starter_content', 20 );
