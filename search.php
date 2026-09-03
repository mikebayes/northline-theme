<?php
/**
 * Search results.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-header">
	<div class="page-header__inner">
		<p class="page-header__eyebrow"><?php esc_html_e( 'Search results', 'northline' ); ?></p>
		<h1 class="page-header__title">
			<?php echo "&ldquo;" . esc_html( get_search_query() ) . "&rdquo;"; ?>
		</h1>
		<div class="page-header__search"><?php get_search_form(); ?></div>
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
