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
