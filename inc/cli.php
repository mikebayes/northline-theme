<?php
/**
 * WP-CLI commands for the Northline theme.
 *
 * A thin presentation layer only: every decision lives in inc/provisioning.php
 * so the provisioning routine stays plain WordPress code that can be called
 * from anywhere.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	return;
}

/**
 * Provisions and inspects the Northline site.
 */
class Northline_CLI_Command {

	/**
	 * Creates the Northline pages, menus and Reading settings.
	 *
	 * Populates Home, About, Services, Insights and Contact with the block
	 * content built from the theme's patterns, sets the static front page and
	 * posts page, builds the primary and footer menus, and sets the site title
	 * and tagline.
	 *
	 * Safe to run more than once. Pages that already exist are left exactly as
	 * the editor left them, menu items are matched by the page they point at so
	 * nothing is duplicated, and nothing is ever deleted — existing content such
	 * as "Hello world!" and "Sample Page" is untouched.
	 *
	 * After provisioning, page content lives in the database and is edited in
	 * the block editor. It is not re-read from the theme unless you explicitly
	 * pass --force-content.
	 *
	 * ## OPTIONS
	 *
	 * [--dry-run]
	 * : Report exactly what would change without writing anything.
	 *
	 * [--force-content]
	 * : Replace existing page content with the theme's patterns. Destructive:
	 * this discards edits made in the block editor.
	 *
	 * [--force-settings]
	 * : Re-apply the site title and tagline. By default these are set on the
	 * first run only, so a later rename in WordPress survives a rerun.
	 *
	 * ## EXAMPLES
	 *
	 *     # See the plan without touching the database.
	 *     $ wp northline provision --dry-run
	 *
	 *     # Provision the site.
	 *     $ wp northline provision
	 *
	 *     # Reset the five pages back to the designed layouts (destructive).
	 *     $ wp northline provision --force-content
	 *
	 * @param array $args       Positional arguments (unused).
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public function provision( $args, $assoc_args ) {
		unset( $args );

		$options = array(
			'dry_run'        => (bool) \WP_CLI\Utils\get_flag_value( $assoc_args, 'dry-run', false ),
			'force_content'  => (bool) \WP_CLI\Utils\get_flag_value( $assoc_args, 'force-content', false ),
			'force_settings' => (bool) \WP_CLI\Utils\get_flag_value( $assoc_args, 'force-settings', false ),
		);

		if ( $options['dry_run'] ) {
			WP_CLI::log( WP_CLI::colorize( '%YDry run:%n nothing will be written.' ) );
		}

		if ( $options['force_content'] && ! $options['dry_run'] ) {
			WP_CLI::confirm( 'This will overwrite the content of the five Northline pages, discarding any edits made in the block editor. Continue?', $assoc_args );
		}

		$report = northline_provision_site( $options );

		if ( empty( $report['steps'] ) ) {
			WP_CLI::warning( 'Nothing to report — the manifest produced no steps.' );
			return;
		}

		\WP_CLI\Utils\format_items(
			'table',
			$report['steps'],
			array( 'area', 'item', 'action', 'detail' )
		);

		foreach ( $report['warnings'] as $warning ) {
			WP_CLI::warning( $warning );
		}

		if ( $report['dry_run'] ) {
			WP_CLI::success( 'Dry run complete. Re-run without --dry-run to apply.' );
			return;
		}

		$counts = array_count_values( wp_list_pluck( $report['steps'], 'action' ) );
		$made   = isset( $counts['created'] ) ? (int) $counts['created'] : 0;
		$change = ( isset( $counts['updated'] ) ? (int) $counts['updated'] : 0 ) + ( isset( $counts['assigned'] ) ? (int) $counts['assigned'] : 0 );
		$kept   = isset( $counts['skipped'] ) ? (int) $counts['skipped'] : 0;

		WP_CLI::success(
			sprintf(
				'Provisioning complete: %d created, %d updated, %d already in place. Front page: %s',
				$made,
				$change,
				$kept,
				home_url( '/' )
			)
		);
	}

	/**
	 * Reports what has and has not been provisioned.
	 *
	 * Read-only. Writes nothing.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render output in a particular format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - csv
	 *   - json
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     $ wp northline status
	 *
	 * @param array $args       Positional arguments (unused).
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 */
	public function status( $args, $assoc_args ) {
		unset( $args );

		$rows   = northline_provisioning_status();
		$format = \WP_CLI\Utils\get_flag_value( $assoc_args, 'format', 'table' );

		\WP_CLI\Utils\format_items( $format, $rows, array( 'area', 'item', 'status', 'detail' ) );

		$missing = 0;

		foreach ( $rows as $row ) {
			if ( 'missing' === $row['status'] ) {
				++$missing;
			}
		}

		if ( $missing ) {
			WP_CLI::warning( sprintf( '%d item(s) not provisioned. Run: wp northline provision', $missing ) );
			return;
		}

		WP_CLI::success( 'Northline is fully provisioned.' );
	}
}

WP_CLI::add_command( 'northline', 'Northline_CLI_Command' );
