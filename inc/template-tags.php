<?php
/**
 * Template helper functions.
 *
 * These output structural chrome only — dates, authors, pagination, read-more
 * affordances. Editorial copy always comes from the database.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Print the published date for the current post.
 *
 * @return void
 */
function northline_posted_on() {
	printf(
		'<time class="entry-meta__date" datetime="%1$s">%2$s</time>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Print the author byline for the current post.
 *
 * @return void
 */
function northline_posted_by() {
	printf(
		'<span class="entry-meta__author">%1$s <a href="%2$s" rel="author">%3$s</a></span>',
		esc_html_x( 'By', 'post author byline', 'northline' ),
		esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
		esc_html( get_the_author() )
	);
}

/**
 * Print the primary category of the current post as a small label.
 *
 * @return void
 */
function northline_posted_in() {
	$categories = get_the_category();

	if ( empty( $categories ) ) {
		return;
	}

	$primary = $categories[0];

	printf(
		'<a class="entry-meta__category" href="%1$s">%2$s</a>',
		esc_url( (string) get_category_link( $primary->term_id ) ),
		esc_html( $primary->name )
	);
}

/**
 * Print the tag list for the current post.
 *
 * @return void
 */
function northline_entry_tags() {
	$tags = get_the_tag_list( '', '' );

	if ( ! $tags || is_wp_error( $tags ) ) {
		return;
	}

	printf(
		'<div class="entry-tags"><span class="screen-reader-text">%1$s</span>%2$s</div>',
		esc_html__( 'Tagged:', 'northline' ),
		wp_kses_post( $tags )
	);
}

/**
 * Output the featured image for the current post, linked when in a loop.
 *
 * @param string $size          Registered image size to render.
 * @param bool   $force_link    Whether to always wrap the image in a permalink.
 * @return void
 */
function northline_post_thumbnail( $size = 'northline-card', $force_link = false ) {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	$link = $force_link || ! is_singular();

	echo '<figure class="entry-thumbnail">';

	if ( $link ) {
		printf( '<a class="entry-thumbnail__link" href="%s" aria-hidden="true" tabindex="-1">', esc_url( (string) get_permalink() ) );
	}

	// No alt override: the attachment's own alt text is the accessible one, and
	// the wrapping link is hidden from assistive tech because the card title
	// already links to the same post.
	the_post_thumbnail(
		$size,
		array( 'loading' => is_singular() ? 'eager' : 'lazy' )
	);

	if ( $link ) {
		echo '</a>';
	}

	echo '</figure>';
}

/**
 * Output accessible previous/next pagination for archives.
 *
 * @return void
 */
function northline_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => esc_html__( 'Previous', 'northline' ),
			'next_text'          => esc_html__( 'Next', 'northline' ),
			'screen_reader_text' => esc_html__( 'Insights navigation', 'northline' ),
			'aria_label'         => esc_html__( 'Insights', 'northline' ),
			'class'              => 'pagination',
		)
	);
}

/**
 * Determine whether the current view should show a page header block.
 *
 * The front page is excluded: its hero is authored in the block editor so the
 * template must not print a competing title.
 *
 * @return bool
 */
function northline_show_page_header() {
	if ( is_front_page() ) {
		return false;
	}

	return (bool) apply_filters( 'northline_show_page_header', true );
}

/**
 * Return the ID of the page assigned as the Posts page, if any.
 *
 * @return int
 */
function northline_posts_page_id() {
	if ( 'page' !== get_option( 'show_on_front' ) ) {
		return 0;
	}

	return (int) get_option( 'page_for_posts' );
}
