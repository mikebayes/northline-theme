<?php
/**
 * Northline theme bootstrap.
 *
 * Northline is a hybrid classic theme. Templates in this theme supply structure
 * and styling only — all editorial content is authored in the WordPress admin
 * through Pages, Posts, the block editor, featured images, menus and Site
 * Identity. Nothing that an editor would reasonably want to change should be
 * hard-coded here or in any template file.
 *
 * @package Northline
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme version, used for cache-busting front-end assets.
 */
define( 'NORTHLINE_VERSION', '1.0.0' );

/**
 * Absolute path to the theme directory, without a trailing slash.
 */
define( 'NORTHLINE_DIR', get_template_directory() );

/**
 * URI of the theme directory, without a trailing slash.
 */
define( 'NORTHLINE_URI', get_template_directory_uri() );

require_once NORTHLINE_DIR . '/inc/setup.php';
require_once NORTHLINE_DIR . '/inc/enqueue.php';
require_once NORTHLINE_DIR . '/inc/block-styles.php';
require_once NORTHLINE_DIR . '/inc/patterns.php';
require_once NORTHLINE_DIR . '/inc/template-tags.php';
require_once NORTHLINE_DIR . '/inc/starter-content.php';
