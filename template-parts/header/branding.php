<?php
/**
 * Site branding: custom logo, or site title and tagline.
 *
 * All three are edited under Appearance → Customize → Site Identity.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

$northline_description = get_bloginfo( 'description', 'display' );
?>
<div class="site-branding">

	<?php if ( has_custom_logo() ) : ?>
		<div class="site-branding__logo"><?php the_custom_logo(); ?></div>
	<?php else : ?>
		<?php $northline_title_tag = ( is_front_page() && ! is_paged() ) ? 'h1' : 'p'; ?>
		<<?php echo esc_attr( $northline_title_tag ); ?> class="site-title">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="site-title__mark" aria-hidden="true"></span>
				<span class="site-title__text"><?php bloginfo( 'name' ); ?></span>
			</a>
		</<?php echo esc_attr( $northline_title_tag ); ?>>
	<?php endif; ?>

	<?php if ( $northline_description ) : ?>
		<p class="site-description"><?php echo esc_html( $northline_description ); ?></p>
	<?php endif; ?>

</div>
