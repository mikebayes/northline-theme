<?php
/**
 * Title: Page - Contact
 * Slug: northline/page-contact
 * Categories: northline-pages
 * Block Types: core/post-content
 * Post Types: page
 * Description: A Contact page: phone, email and shop details, what counts as an emergency, and the areas served.
 * Keywords: contact, phone, address, full page
 * Viewport width: 1400
 *
 * @package Northline
 */

if ( ! function_exists( 'northline_get_pattern_content' ) ) {
	return;
}

$northline_sections = array(
	'contact-details',
	'service-areas',
);

foreach ( $northline_sections as $northline_section ) {
	echo northline_get_pattern_content( $northline_section ) . "\n\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
