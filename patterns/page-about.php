<?php
/**
 * Title: Page - About
 * Slug: northline/page-about
 * Categories: northline-pages
 * Block Types: core/post-content
 * Post Types: page
 * Description: An About page: the story and credentials panel, service areas, client quotes and a closing call to action.
 * Keywords: about, company, team, full page
 * Viewport width: 1400
 *
 * @package Northline
 */

if ( ! function_exists( 'northline_get_pattern_content' ) ) {
	return;
}

$northline_sections = array(
	'about-split',
	'service-areas',
	'testimonials',
	'cta-band',
);

foreach ( $northline_sections as $northline_section ) {
	echo northline_get_pattern_content( $northline_section ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
