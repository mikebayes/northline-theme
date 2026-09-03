<?php
/**
 * Title: Page - Home
 * Slug: northline/page-home
 * Categories: northline-pages
 * Block Types: core/post-content
 * Post Types: page
 * Description: The complete Northline home page: hero, services, about, process, testimonials, service areas, latest Insights and a closing call to action.
 * Keywords: home, front page, landing, full page
 * Viewport width: 1400
 *
 * @package Northline
 *
 * Composed from the individual section patterns so the two never drift apart -
 * edit a section pattern and this page picks the change up.
 */

if ( ! function_exists( 'northline_get_pattern_content' ) ) {
	return;
}

$northline_sections = array(
	'hero-dark',
	'services-grid',
	'about-split',
	'process-steps',
	'testimonials',
	'service-areas',
	'insights-teaser',
	'cta-band',
);

foreach ( $northline_sections as $northline_section ) {
	echo northline_get_pattern_content( $northline_section ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
