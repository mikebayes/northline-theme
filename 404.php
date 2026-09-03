<?php
/**
 * The 404 template.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-header">
	<div class="page-header__inner">
		<p class="page-header__eyebrow"><?php esc_html_e( 'Error 404', 'northline' ); ?></p>
		<h1 class="page-header__title"><?php esc_html_e( 'That page is off the grid', 'northline' ); ?></h1>
		<p class="page-header__standfirst"><?php esc_html_e( 'The page you were looking for has been moved or no longer exists. Try a search, or head back to the home page.', 'northline' ); ?></p>
		<div class="page-header__search"><?php get_search_form(); ?></div>
	</div>
</header>

<div class="content-area">
	<p class="content-area__actions">
		<a class="nl-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Back to home', 'northline' ); ?>
		</a>
	</p>
</div>

<?php
get_footer();
