<?php
/**
 * A single Insights article.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<header class="page-header page-header--article <?php echo has_post_thumbnail() ? 'page-header--has-media' : ''; ?>">
			<div class="page-header__inner">
				<div class="entry-meta entry-meta--header">
					<?php
					northline_posted_in();
					northline_posted_on();
					?>
				</div>

				<h1 class="page-header__title"><?php the_title(); ?></h1>

				<?php if ( has_excerpt() ) : ?>
					<p class="page-header__standfirst"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="entry-meta entry-meta--byline"><?php northline_posted_by(); ?></div>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="page-header__media">
					<?php the_post_thumbnail( 'northline-wide', array( 'loading' => 'eager' ) ); ?>
					<?php
					$northline_caption = get_the_post_thumbnail_caption();

					if ( $northline_caption ) :
						?>
						<figcaption><?php echo wp_kses_post( $northline_caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endif; ?>
		</header>

		<div class="entry-content">
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

		<footer class="entry-footer">
			<?php northline_entry_tags(); ?>
		</footer>

	</article>

	<nav class="post-navigation" aria-label="<?php esc_attr_e( 'Article', 'northline' ); ?>">
		<?php
		the_post_navigation(
			array(
				'prev_text' => '<span class="post-navigation__label">' . esc_html__( 'Previous article', 'northline' ) . '</span><span class="post-navigation__title">%title</span>',
				'next_text' => '<span class="post-navigation__label">' . esc_html__( 'Next article', 'northline' ) . '</span><span class="post-navigation__title">%title</span>',
			)
		);
		?>
	</nav>

	<?php
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}

endwhile;

get_footer();
