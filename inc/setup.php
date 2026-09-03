<?php
/**
 * Theme supports, menus, image sizes and other one-time setup.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 *
 * @return void
 */
function northline_setup() {
	load_theme_textdomain( 'northline', NORTHLINE_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Site Identity: logo, title and tagline are all editor-controlled.
	add_theme_support(
		'custom-logo',
		array(
			'height'               => 96,
			'width'                => 360,
			'flex-height'          => true,
			'flex-width'           => true,
			'unlink-homepage-logo' => false,
		)
	);

	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );

	// Navigation menus are managed at Appearance → Menus.
	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu (header)', 'northline' ),
			'footer'  => __( 'Footer Menu', 'northline' ),
			'legal'   => __( 'Legal Menu (footer bottom bar)', 'northline' ),
		)
	);

	// Featured image crops used by cards and page headers.
	add_image_size( 'northline-card', 800, 560, true );
	add_image_size( 'northline-wide', 1600, 800, true );
}
add_action( 'after_setup_theme', 'northline_setup' );

/**
 * Set the legacy content width used by oEmbeds and unsized media.
 *
 * @return void
 */
function northline_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'northline_content_width', 736 );
}
add_action( 'after_setup_theme', 'northline_content_width', 0 );

/**
 * Register widget areas.
 *
 * The footer columns are widget areas so the address block, hours and service
 * area list stay editable from Appearance → Widgets rather than living in PHP.
 *
 * @return void
 */
function northline_widgets_init() {
	$defaults = array(
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget__title">',
		'after_title'   => '</h2>',
	);

	for ( $i = 1; $i <= 3; $i++ ) {
		register_sidebar(
			array_merge(
				$defaults,
				array(
					'name'        => sprintf(
						/* translators: %d: footer column number. */
						__( 'Footer Column %d', 'northline' ),
						$i
					),
					'id'          => 'footer-' . $i,
					'description' => __( 'Shown in the site footer. Use a Text or Block widget for contact details, hours or a service-area list.', 'northline' ),
				)
			)
		);
	}

	register_sidebar(
		array_merge(
			$defaults,
			array(
				'name'        => __( 'Insights Sidebar', 'northline' ),
				'id'          => 'sidebar-insights',
				'description' => __( 'Optional sidebar shown alongside single Insights articles.', 'northline' ),
			)
		)
	);
}
add_action( 'widgets_init', 'northline_widgets_init' );

/**
 * Add helpful classes to the <body> element.
 *
 * @param string[] $classes Existing body classes.
 * @return string[]
 */
function northline_body_classes( $classes ) {
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'is-front-page';
	}

	if ( ! has_custom_logo() ) {
		$classes[] = 'has-text-branding';
	}

	if ( is_singular() && has_post_thumbnail() ) {
		$classes[] = 'has-featured-image';
	}

	return $classes;
}
add_filter( 'body_class', 'northline_body_classes' );

/**
 * Give the excerpt a typographic ellipsis instead of the default bracketed one.
 *
 * @param string $more Default "more" string.
 * @return string
 */
function northline_excerpt_more( $more ) {
	return is_admin() ? $more : '&hellip;';
}
add_filter( 'excerpt_more', 'northline_excerpt_more' );

/**
 * Shorten excerpts slightly so Insights cards stay balanced.
 *
 * @param int $length Default excerpt length in words.
 * @return int
 */
function northline_excerpt_length( $length ) {
	return is_admin() ? $length : 28;
}
add_filter( 'excerpt_length', 'northline_excerpt_length' );

/**
 * Add a skip-link target-friendly landmark id to the archive title markup.
 *
 * @param string $title Archive title.
 * @return string
 */
function northline_archive_title( $title ) {
	if ( is_category() || is_tag() || is_tax() ) {
		$title = single_term_title( '', false );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'northline_archive_title' );
