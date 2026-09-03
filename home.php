<?php
/**
 * The Insights index — the page assigned as the Posts page.
 *
 * The page title, standfirst (Excerpt) and any block content added to that Page
 * are rendered above the article grid, so the intro to Insights stays editable
 * in the block editor.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();

$northline_posts_page = northline_posts_page_id();
?>

<?php if ( $northline_posts_page ) : ?>
	<?php
	$northline_intro = get_post( $northline_posts_page );

	if ( $northline_intro instanceof WP_Post ) :
		$northline_standfirst = $northline_intro->post_excerpt;
		?>
		<header class="page-header">
			<div class="page-header__inner">
				<h1 class="page-header__title"><?php echo esc_html( get_the_title( $northline_posts_page ) ); ?></h1>

				<?php if ( $northline_standfirst ) : ?>
					<p class="page-header__standfirst"><?php echo esc_html( $northline_standfirst ); ?></p>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( trim( $northline_intro->post_content ) ) : ?>
			<div class="entry-content entry-content--intro">
				<?php echo apply_filters( 'the_content', $northline_intro->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
		<?php
	endif;
	?>
<?php else : ?>
	<header class="page-header">
		<div class="page-header__inner">
			<h1 class="page-header__title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
		</div>
	</header>
<?php endif; ?>

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
