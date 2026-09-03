<?php
/**
 * Page header: eyebrow, title, standfirst and featured image.
 *
 * The title comes from the Page title, the standfirst from the Excerpt field
 * (Page sidebar → Excerpt) and the image from the Featured Image — all native
 * WordPress fields, so none of it is fixed in the template.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

if ( ! northline_show_page_header() ) {
	return;
}

$northline_standfirst = has_excerpt() ? get_the_excerpt() : '';
$northline_parent     = wp_get_post_parent_id( get_the_ID() );
?>
<header class="page-header <?php echo has_post_thumbnail() ? 'page-header--has-media' : ''; ?>">
	<div class="page-header__inner">

		<?php if ( $northline_parent ) : ?>
			<p class="page-header__eyebrow">
				<a href="<?php echo esc_url( (string) get_permalink( $northline_parent ) ); ?>">
					<?php echo esc_html( (string) get_the_title( $northline_parent ) ); ?>
				</a>
			</p>
		<?php endif; ?>

		<h1 class="page-header__title"><?php the_title(); ?></h1>

		<?php if ( $northline_standfirst ) : ?>
			<p class="page-header__standfirst"><?php echo esc_html( $northline_standfirst ); ?></p>
		<?php endif; ?>

	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<figure class="page-header__media">
			<?php the_post_thumbnail( 'northline-wide', array( 'loading' => 'eager' ) ); ?>
		</figure>
	<?php endif; ?>
</header>
