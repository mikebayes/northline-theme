<?php
/**
 * The site footer, closing the main content landmark and the document.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;
?>
	</main><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-footer__inner">

			<?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
				<div class="site-footer__columns">
					<?php for ( $northline_col = 1; $northline_col <= 3; $northline_col++ ) : ?>
						<?php if ( is_active_sidebar( 'footer-' . $northline_col ) ) : ?>
							<div class="site-footer__column">
								<?php dynamic_sidebar( 'footer-' . $northline_col ); ?>
							</div>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer', 'northline' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer',
							'menu_class'     => 'footer-menu',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => false,
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<div class="site-footer__bar">
				<p class="site-footer__copyright">
					<?php
					printf(
						/* translators: 1: current year, 2: site name. */
						esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'northline' ),
						esc_html( wp_date( 'Y' ) ),
						esc_html( get_bloginfo( 'name' ) )
					);
					?>
				</p>

				<?php if ( has_nav_menu( 'legal' ) ) : ?>
					<nav class="legal-navigation" aria-label="<?php esc_attr_e( 'Legal', 'northline' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'legal',
								'menu_class'     => 'legal-menu',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>

		</div>
	</footer>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
