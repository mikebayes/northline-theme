<?php
/**
 * Block pattern categories.
 *
 * The patterns themselves live as individual files in /patterns and are
 * registered automatically by WordPress 6.0+ from their file headers, which
 * keeps each pattern readable and translatable.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the pattern categories used by the theme's patterns.
 *
 * @return void
 */
function northline_register_pattern_categories() {
	if ( ! function_exists( 'register_block_pattern_category' ) ) {
		return;
	}

	$categories = array(
		'northline-sections' => array(
			'label'       => __( 'Northline: Sections', 'northline' ),
			'description' => __( 'Full-width page sections built from core blocks.', 'northline' ),
		),
		'northline-pages'    => array(
			'label'       => __( 'Northline: Full Pages', 'northline' ),
			'description' => __( 'Complete starting layouts for the main site pages.', 'northline' ),
		),
	);

	foreach ( $categories as $slug => $args ) {
		register_block_pattern_category( $slug, $args );
	}
}
add_action( 'init', 'northline_register_pattern_categories', 9 );

/**
 * Render one of the theme's block patterns to a string.
 *
 * Pattern files are plain PHP + block markup, so including them with output
 * buffering returns exactly the markup an editor would get from the inserter.
 * Provisioning and starter content both use this, which is what stops the
 * seeded pages and the pattern library from drifting apart.
 *
 * Note that this is a one-way copy: once the markup has been written to a post,
 * that post is ordinary editorial content and is never re-read from here.
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
