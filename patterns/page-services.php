<?php
/**
 * Title: Page - Services
 * Slug: northline/page-services
 * Categories: northline-pages
 * Block Types: core/post-content
 * Post Types: page
 * Description: A Services page: the six-card service grid, how a job runs, and a closing call to action.
 * Keywords: services, capabilities, full page
 * Viewport width: 1400
 *
 * @package Northline
 */

if ( ! function_exists( 'northline_get_pattern_content' ) ) {
	return;
}

$northline_sections = array(
	'services-grid',
	'process-steps',
	'cta-band',
);

foreach ( $northline_sections as $northline_section ) {
	echo northline_get_pattern_content( $northline_section ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
