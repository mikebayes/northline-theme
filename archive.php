<?php
/**
 * Category, tag, author and date archives.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-header">
	<div class="page-header__inner">
		<p class="page-header__eyebrow"><?php esc_html_e( 'Insights', 'northline' ); ?></p>
		<h1 class="page-header__title"><?php the_archive_title(); ?></h1>
		<?php
		$northline_archive_description = get_the_archive_description();

		if ( $northline_archive_description ) :
			?>
			<div class="page-header__standfirst"><?php echo wp_kses_post( $northline_archive_description ); ?></div>
		<?php endif; ?>
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
