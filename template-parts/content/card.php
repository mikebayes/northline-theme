<?php
/**
 * An Insights card used in grids.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>

	<?php northline_post_thumbnail( 'northline-card' ); ?>

	<div class="post-card__body">
		<div class="entry-meta">
			<?php
			northline_posted_in();
			northline_posted_on();
			?>
		</div>

		<h2 class="post-card__title">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>

		<?php if ( has_excerpt() || get_the_content() ) : ?>
			<p class="post-card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php endif; ?>

		<span class="post-card__more" aria-hidden="true"><?php esc_html_e( 'Read article', 'northline' ); ?></span>
	</div>

</article>
