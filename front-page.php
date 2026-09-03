<?php
/**
 * The front page.
 *
 * Deliberately minimal: the entire home page is composed in the block editor
 * on the Page assigned under Settings → Reading. The template's only job is to
 * render that content edge to edge so full-width sections (hero, services,
 * stats, CTA) can bleed to the viewport.
 *
 * If the site is configured to show the blog on the front page, this hands off
 * to the standard index template instead.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

if ( 'page' !== get_option( 'show_on_front' ) ) {
	get_template_part( 'index' );
	return;
}

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'front-page' ); ?>>
		<div class="entry-content entry-content--canvas">
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<nav class="page-links">',
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</article>

	<?php
endwhile;

get_footer();
