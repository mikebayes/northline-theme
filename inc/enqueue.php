<?php
/**
 * Front-end and editor asset loading.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return a filemtime-based version string for a theme asset.
 *
 * Falls back to the theme version when the file is missing so a broken path
 * never produces a fatal error.
 *
 * @param string $relative_path Path relative to the theme root, e.g. 'assets/css/theme.css'.
 * @return string
 */
function northline_asset_version( $relative_path ) {
	$absolute = NORTHLINE_DIR . '/' . ltrim( $relative_path, '/' );

	if ( file_exists( $absolute ) ) {
		return (string) filemtime( $absolute );
	}

	return NORTHLINE_VERSION;
}

/**
 * Enqueue front-end styles and scripts.
 *
 * @return void
 */
function northline_enqueue_assets() {
	// The theme header lives in style.css; the actual styling is in assets/css.
	wp_enqueue_style(
		'northline-style',
		get_stylesheet_uri(),
		array(),
		northline_asset_version( 'style.css' )
	);

	wp_enqueue_style(
		'northline-theme',
		NORTHLINE_URI . '/assets/css/theme.css',
		array( 'northline-style' ),
		northline_asset_version( 'assets/css/theme.css' )
	);

	wp_enqueue_style(
		'northline-blocks',
		NORTHLINE_URI . '/assets/css/blocks.css',
		array( 'northline-theme' ),
		northline_asset_version( 'assets/css/blocks.css' )
	);

	wp_enqueue_script(
		'northline-navigation',
		NORTHLINE_URI . '/assets/js/navigation.js',
		array(),
		northline_asset_version( 'assets/js/navigation.js' ),
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'northline_enqueue_assets' );

/**
 * Load the shared block stylesheet inside the editor so the canvas matches
 * the front end.
 *
 * @return void
 */
function northline_editor_styles() {
	add_editor_style(
		array(
			'assets/css/blocks.css',
			'assets/css/editor.css',
		)
	);
}
add_action( 'after_setup_theme', 'northline_editor_styles' );
