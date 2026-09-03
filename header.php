<?php
/**
 * The site header, opening the document and the main content landmark.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'northline' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-header__inner">

			<?php get_template_part( 'template-parts/header/branding' ); ?>

			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<button
					class="nav-toggle"
					type="button"
					aria-expanded="false"
					aria-controls="primary-navigation"
					data-nav-toggle
				>
					<span class="nav-toggle__bars" aria-hidden="true"></span>
					<span class="nav-toggle__label"><?php esc_html_e( 'Menu', 'northline' ); ?></span>
				</button>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/header/navigation' ); ?>

		</div>
	</header>

	<main id="content" class="site-main" role="main">
