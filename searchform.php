<?php
/**
 * Search form.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

$northline_search_id = wp_unique_id( 'search-field-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="<?php echo esc_attr( $northline_search_id ); ?>">
		<span class="screen-reader-text"><?php esc_html_e( 'Search this site', 'northline' ); ?></span>
	</label>
	<input
		type="search"
		id="<?php echo esc_attr( $northline_search_id ); ?>"
		class="search-form__field"
		placeholder="<?php esc_attr_e( 'Search&hellip;', 'northline' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	>
	<button type="submit" class="search-form__submit">
		<?php esc_html_e( 'Search', 'northline' ); ?>
	</button>
</form>
