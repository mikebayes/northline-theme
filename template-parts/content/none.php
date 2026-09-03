<?php
/**
 * Shown when a loop returns no results.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="no-results">
	<h2 class="no-results__title"><?php esc_html_e( 'Nothing found', 'northline' ); ?></h2>

	<?php if ( is_search() ) : ?>
		<p><?php esc_html_e( 'No results matched that search. Try a different term or browse the latest articles.', 'northline' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'There is nothing published here yet. Please check back soon.', 'northline' ); ?></p>
	<?php endif; ?>
</div>
