<?php
/**
 * The fallback template.
 *
 * WordPress falls back here when no more specific template matches. It mirrors
 * the Insights index layout.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-header">
	<div class="page-header__inner">
		<h1 class="page-header__title">
			<?php
			if ( is_home() && ! is_front_page() ) {
				echo esc_html( get_the_title( (int) get_option( 'page_for_posts' ) ) );
			} else {
				esc_html_e( 'Latest', 'northline' );
			}
			?>
		</h1>
	</div>
</header>

<div class="content-area">
	<?php if ( have_posts() ) : ?>

		<div class="post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content/card' );
			endwhile;
			?>
		</div>

		<?php northline_pagination(); ?>

	<?php else : ?>
		<?php get_template_part( 'template-parts/content/none' ); ?>
	<?php endif; ?>
</div>

<?php
get_footer();
