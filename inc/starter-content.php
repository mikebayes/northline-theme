<?php
/**
 * Native WordPress starter content.
 *
 * On a brand-new site this seeds the five pages, the menus and the front-page
 * settings so the theme has something to render immediately. Everything it
 * creates lands in the database as ordinary Pages and menu items — it is a
 * starting point for editors, not a substitute for the CMS.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render one of the theme's block patterns to a string.
 *
 * Pattern files are plain PHP + block markup, so including them with output
 * buffering returns exactly the markup an editor would get from the inserter.
 * This keeps starter content and the pattern library from drifting apart.
 *
 * @param string $slug Pattern file name without extension, e.g. 'page-about'.
 * @return string Block markup, or an empty string when the file is missing.
 */
function northline_get_pattern_content( $slug ) {
	$file = NORTHLINE_DIR . '/patterns/' . sanitize_file_name( $slug ) . '.php';

	if ( ! is_readable( $file ) ) {
		return '';
	}

	ob_start();
	require $file;

	return trim( (string) ob_get_clean() );
}

/**
 * Register starter content for fresh installs.
 *
 * @return void
 */
function northline_starter_content() {
	$pages = array(
		'home'     => array(
			'title'   => _x( 'Home', 'starter page title', 'northline' ),
			'pattern' => 'page-home',
		),
		'about'    => array(
			'title'   => _x( 'About', 'starter page title', 'northline' ),
			'pattern' => 'page-about',
		),
		'services' => array(
			'title'   => _x( 'Services', 'starter page title', 'northline' ),
			'pattern' => 'page-services',
		),
		'insights' => array(
			'title'   => _x( 'Insights', 'starter page title', 'northline' ),
			'pattern' => '',
		),
		'contact'  => array(
			'title'   => _x( 'Contact', 'starter page title', 'northline' ),
			'pattern' => 'page-contact',
		),
	);

	$posts = array();

	foreach ( $pages as $slug => $page ) {
		$posts[ $slug ] = array(
			'post_type'    => 'page',
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_content' => $page['pattern'] ? northline_get_pattern_content( $page['pattern'] ) : '',
		);
	}

	$menu_items = array(
		'page_home'     => array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{home}}',
		),
		'page_about'    => array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{about}}',
		),
		'page_services' => array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{services}}',
		),
		'page_insights' => array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{insights}}',
		),
		'page_contact'  => array(
			'type'      => 'post_type',
			'object'    => 'page',
			'object_id' => '{{contact}}',
		),
	);

	add_theme_support(
		'starter-content',
		array(
			'posts'     => $posts,
			'options'   => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{insights}}',
				'blogname'       => _x( 'Northline Electrical', 'starter site title', 'northline' ),
				'blogdescription' => _x( 'Licensed electrical contractors in Winnipeg', 'starter tagline', 'northline' ),
			),
			'nav_menus' => array(
				'primary' => array(
					'name'  => __( 'Primary Menu', 'northline' ),
					'items' => $menu_items,
				),
				'footer'  => array(
					'name'  => __( 'Footer Menu', 'northline' ),
					'items' => $menu_items,
				),
			),
		)
	);
}
add_action( 'after_setup_theme', 'northline_starter_content', 20 );
