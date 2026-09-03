<?php
/**
 * One-time site provisioning.
 *
 * WordPress's native starter content only runs on a genuinely fresh site
 * (`is_fresh_site()`), so it is skipped on any install that already has
 * content — which is every real deployment target. This file provides the same
 * outcome as an explicit, idempotent, rerunnable routine, exposed as the
 * `wp northline provision` command in inc/cli.php.
 *
 * Everything it writes lands in the database as ordinary Pages, menu items and
 * options. After provisioning, page content is normal editorial content: it is
 * edited in the block editor and is never re-read from the theme's pattern
 * files again unless someone explicitly asks for that with --force-content.
 *
 * Nothing here deletes or overwrites content it did not create.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post meta key marking a page as provisioned, storing its manifest key.
 */
if ( ! defined( 'NORTHLINE_PAGE_META' ) ) {
	define( 'NORTHLINE_PAGE_META', '_northline_page' );
}

/**
 * Option holding the provisioning record (ids, menus, what has been applied).
 */
if ( ! defined( 'NORTHLINE_PROVISION_OPTION' ) ) {
	define( 'NORTHLINE_PROVISION_OPTION', 'northline_provisioning' );
}

/**
 * Schema version of the provisioning record.
 */
if ( ! defined( 'NORTHLINE_PROVISION_VERSION' ) ) {
	define( 'NORTHLINE_PROVISION_VERSION', 1 );
}

/**
 * The single definition of what a provisioned Northline site contains.
 *
 * Both the WP-CLI command and the native starter-content fallback read this,
 * so the two can never describe different sites.
 *
 * @return array{pages:array,front_page:string,posts_page:string,menus:array,settings:array}
 */
function northline_provisioning_manifest() {
	$manifest = array(
		'pages'      => array(
			'home'     => array(
				'title'        => _x( 'Home', 'provisioned page title', 'northline' ),
				'pattern'      => 'page-home',
				'excerpt'      => '',
				'menu_classes' => '',
			),
			'about'    => array(
				'title'        => _x( 'About', 'provisioned page title', 'northline' ),
				'pattern'      => 'page-about',
				'excerpt'      => __( 'A small Winnipeg crew that answers its own phone, pulls its own permits, and prices the job before it starts.', 'northline' ),
				'menu_classes' => '',
			),
			'services' => array(
				'title'        => _x( 'Services', 'provisioned page title', 'northline' ),
				'pattern'      => 'page-services',
				'excerpt'      => __( 'Residential, commercial and standby electrical work across Winnipeg and the Capital Region, quoted in writing and closed with an inspection.', 'northline' ),
				'menu_classes' => '',
			),
			'insights' => array(
				'title'        => _x( 'Insights', 'provisioned page title', 'northline' ),
				'pattern'      => 'page-insights',
				'excerpt'      => '',
				'menu_classes' => '',
			),
			'contact'  => array(
				'title'        => _x( 'Contact', 'provisioned page title', 'northline' ),
				'pattern'      => 'page-contact',
				'excerpt'      => __( 'Phone, email and the shop address, the hours we answer, and what counts as an after-hours emergency.', 'northline' ),
				'menu_classes' => 'nl-menu-cta',
			),
		),
		'front_page' => 'home',
		'posts_page' => 'insights',
		'menus'      => array(
			'primary' => array(
				'name'  => __( 'Northline Primary', 'northline' ),
				'items' => array( 'home', 'about', 'services', 'insights', 'contact' ),
			),
			'footer'  => array(
				'name'  => __( 'Northline Footer', 'northline' ),
				'items' => array( 'home', 'about', 'services', 'insights', 'contact' ),
			),
		),
		'settings'   => array(
			'blogname'        => _x( 'Northline Electrical', 'provisioned site title', 'northline' ),
			'blogdescription' => _x( 'Licensed electrical contractors in Winnipeg', 'provisioned tagline', 'northline' ),
		),
	);

	/**
	 * Filters the provisioning manifest.
	 *
	 * @param array $manifest Pages, menus and settings to provision.
	 */
	return apply_filters( 'northline_provisioning_manifest', $manifest );
}

/**
 * Read the stored provisioning record, normalised.
 *
 * @return array
 */
function northline_provisioning_state() {
	$state = get_option( NORTHLINE_PROVISION_OPTION, array() );

	if ( ! is_array( $state ) ) {
		$state = array();
	}

	return wp_parse_args(
		$state,
		array(
			'version'          => 0,
			'provisioned_at'   => '',
			'pages'            => array(),
			'menus'            => array(),
			'settings_applied' => false,
		)
	);
}

/**
 * Find a page previously provisioned under a manifest key.
 *
 * The meta marker is authoritative: it survives the page being renamed, moved
 * to a new slug or re-saved, which a slug lookup would not.
 *
 * @param string $key Manifest key, e.g. 'about'.
 * @return int Post ID, or 0 when not found.
 */
function northline_find_provisioned_page( $key ) {
	$found = get_posts(
		array(
			'post_type'              => 'page',
			'post_status'            => 'any',
			'numberposts'            => 1,
			'fields'                 => 'ids',
			'orderby'                => 'ID',
			'order'                  => 'ASC',
			'meta_key'               => NORTHLINE_PAGE_META, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'             => $key,                // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
		)
	);

	return empty( $found ) ? 0 : (int) $found[0];
}

/**
 * Pick an author for provisioned pages.
 *
 * Uses the user WP-CLI was invoked as when one was given, otherwise the
 * lowest-numbered administrator, so pages never end up authored by user 0.
 *
 * @return int
 */
function northline_provisioning_author() {
	$current = get_current_user_id();

	if ( $current ) {
		return (int) $current;
	}

	$admins = get_users(
		array(
			'role'    => 'administrator',
			'number'  => 1,
			'orderby' => 'ID',
			'order'   => 'ASC',
			'fields'  => 'ID',
		)
	);

	return empty( $admins ) ? 0 : (int) $admins[0];
}

/**
 * Format a content length for reporting.
 *
 * size_format() returns false for 0, which would print nothing at all.
 *
 * @param string $content Block markup.
 * @return string
 */
function northline_provisioning_size( $content ) {
	$bytes = strlen( (string) $content );

	if ( $bytes < 1 ) {
		return '0 B';
	}

	return (string) size_format( $bytes );
}

/**
 * Append a line to the provisioning report.
 *
 * @param array  $report Report, passed by reference.
 * @param string $area   Area of the site: page, menu, setting.
 * @param string $item   Item within that area.
 * @param string $action One of: created, adopted, updated, skipped, assigned, planned.
 * @param string $detail Human-readable explanation.
 * @param int    $id     Related object ID, when there is one.
 * @return void
 */
function northline_provisioning_step( array &$report, $area, $item, $action, $detail, $id = 0 ) {
	$report['steps'][] = array(
		'area'   => $area,
		'item'   => $item,
		'action' => $action,
		'detail' => $detail,
		'id'     => (int) $id,
	);
}

/**
 * Provision the site.
 *
 * Safe to run repeatedly. Existing provisioned pages are left exactly as the
 * editor left them; only genuinely missing things are created.
 *
 * @param array $options {
 *     Optional. Provisioning options.
 *
 *     @type bool $dry_run        Report what would happen without writing. Default false.
 *     @type bool $force_content  Overwrite page content with the theme patterns. Default false.
 *     @type bool $force_settings Re-apply site title and tagline. Default false.
 * }
 * @return array{dry_run:bool,steps:array,warnings:array,pages:array}
 */
function northline_provision_site( $options = array() ) {
	$options = wp_parse_args(
		$options,
		array(
			'dry_run'        => false,
			'force_content'  => false,
			'force_settings' => false,
		)
	);

	$dry      = (bool) $options['dry_run'];
	$manifest = northline_provisioning_manifest();
	$state    = northline_provisioning_state();
	$author   = northline_provisioning_author();

	$report = array(
		'dry_run'  => $dry,
		'steps'    => array(),
		'warnings' => array(),
		'pages'    => array(),
	);

	if ( ! $author ) {
		$report['warnings'][] = __( 'No administrator account was found; provisioned pages will have no author. Run with --user=<id|login> to set one.', 'northline' );
	}

	/* ---------------------------------------------------------------------
	 * Pages
	 * ------------------------------------------------------------------ */

	$page_ids   = array();
	$menu_order = 0;

	foreach ( $manifest['pages'] as $key => $page ) {
		$menu_order += 10;

		$content     = $page['pattern'] ? northline_get_pattern_content( $page['pattern'] ) : '';
		$existing_id = northline_find_provisioned_page( $key );
		$adopted     = false;

		if ( ! $existing_id ) {
			$by_path = get_page_by_path( $key, OBJECT, 'page' );

			if ( $by_path instanceof WP_Post ) {
				$existing_id = (int) $by_path->ID;
				$adopted     = true;
			}
		}

		// Nothing exists: create the page with its pattern content.
		if ( ! $existing_id ) {
			if ( $dry ) {
				northline_provisioning_step(
					$report,
					'page',
					$key,
					'planned',
					sprintf(
						/* translators: 1: page title, 2: content size in bytes. */
						__( 'would create "%1$s" with %2$s of block content', 'northline' ),
						$page['title'],
						northline_provisioning_size( $content )
					)
				);
				continue;
			}

			$new_id = wp_insert_post(
				wp_slash(
					array(
						'post_type'      => 'page',
						'post_title'     => $page['title'],
						'post_name'      => $key,
						'post_status'    => 'publish',
						'post_content'   => $content,
						'post_excerpt'   => $page['excerpt'],
						'post_author'    => $author,
						'menu_order'     => $menu_order,
						'comment_status' => 'closed',
						'ping_status'    => 'closed',
					)
				),
				true
			);

			if ( is_wp_error( $new_id ) ) {
				$report['warnings'][] = sprintf(
					/* translators: 1: manifest key, 2: error message. */
					__( 'Could not create the %1$s page: %2$s', 'northline' ),
					$key,
					$new_id->get_error_message()
				);
				continue;
			}

			update_post_meta( $new_id, NORTHLINE_PAGE_META, $key );
			$page_ids[ $key ] = (int) $new_id;

			northline_provisioning_step(
				$report,
				'page',
				$key,
				'created',
				sprintf(
					/* translators: 1: page title, 2: content size. */
					__( 'created "%1$s" with %2$s of block content', 'northline' ),
					$page['title'],
					northline_provisioning_size( $content )
				),
				$new_id
			);

			continue;
		}

		// Something exists: adopt it, but never trample editorial changes.
		$page_ids[ $key ] = $existing_id;
		$post             = get_post( $existing_id );
		$has_content      = $post && '' !== trim( (string) $post->post_content );

		if ( $adopted ) {
			$report['warnings'][] = sprintf(
				/* translators: 1: page title, 2: post ID. */
				__( 'Adopted the existing page "%1$s" (ID %2$d) because its slug matched. Its content was left as-is.', 'northline' ),
				get_the_title( $existing_id ),
				$existing_id
			);
		}

		$update        = array();
		$notes         = array();
		$write_content = $content && ( $options['force_content'] || ! $has_content );

		if ( $write_content ) {
			$update['post_content'] = $content;
			$notes[]                = $has_content
				? __( 'content replaced from the theme pattern', 'northline' )
				: __( 'empty content filled from the theme pattern', 'northline' );
		}

		if ( $page['excerpt'] && $post && '' === trim( (string) $post->post_excerpt ) ) {
			$update['post_excerpt'] = $page['excerpt'];
			$notes[]                = __( 'standfirst added', 'northline' );
		}

		if ( $post && in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ), true ) ) {
			$update['post_status'] = 'publish';
			$notes[]               = __( 'published (was not public, which would have broken the menu)', 'northline' );
		}

		if ( empty( $update ) ) {
			if ( ! $dry ) {
				update_post_meta( $existing_id, NORTHLINE_PAGE_META, $key );
			}

			northline_provisioning_step(
				$report,
				'page',
				$key,
				'skipped',
				sprintf(
					/* translators: %s: page title. */
					__( '"%s" already exists and was left untouched', 'northline' ),
					get_the_title( $existing_id )
				),
				$existing_id
			);

			continue;
		}

		if ( $dry ) {
			northline_provisioning_step(
				$report,
				'page',
				$key,
				'planned',
				implode( '; ', $notes ),
				$existing_id
			);

			continue;
		}

		$update['ID'] = $existing_id;
		$updated      = wp_update_post( wp_slash( $update ), true );

		if ( is_wp_error( $updated ) ) {
			$report['warnings'][] = sprintf(
				/* translators: 1: manifest key, 2: error message. */
				__( 'Could not update the %1$s page: %2$s', 'northline' ),
				$key,
				$updated->get_error_message()
			);
			continue;
		}

		update_post_meta( $existing_id, NORTHLINE_PAGE_META, $key );

		northline_provisioning_step(
			$report,
			'page',
			$key,
			'updated',
			implode( '; ', $notes ),
			$existing_id
		);
	}

	$report['pages'] = $page_ids;

	/* ---------------------------------------------------------------------
	 * Menus
	 * ------------------------------------------------------------------ */

	$menu_ids = isset( $state['menus'] ) ? (array) $state['menus'] : array();

	// Clear any half-written menu records from an interrupted earlier run so
	// this one starts from a consistent state.
	northline_prune_orphan_menu_items( $page_ids, $dry, $report );

	foreach ( $manifest['menus'] as $location => $menu_config ) {
		$menu_id = northline_provision_menu( $location, $menu_config, $manifest['pages'], $page_ids, $state, $options, $report );

		if ( $menu_id ) {
			$menu_ids[ $location ] = $menu_id;
		}
	}

	/* ---------------------------------------------------------------------
	 * Reading settings
	 * ------------------------------------------------------------------ */

	$front_id = isset( $page_ids[ $manifest['front_page'] ] ) ? (int) $page_ids[ $manifest['front_page'] ] : 0;
	$posts_id = isset( $page_ids[ $manifest['posts_page'] ] ) ? (int) $page_ids[ $manifest['posts_page'] ] : 0;

	if ( $front_id && $posts_id && $front_id === $posts_id ) {
		$report['warnings'][] = __( 'The front page and posts page resolved to the same page; Reading settings were left alone.', 'northline' );
	} elseif ( $front_id ) {
		$show_on_front  = get_option( 'show_on_front' );
		$page_on_front  = (int) get_option( 'page_on_front' );
		$page_for_posts = (int) get_option( 'page_for_posts' );

		$reading_ok = ( 'page' === $show_on_front )
			&& ( $page_on_front === $front_id )
			&& ( ! $posts_id || $page_for_posts === $posts_id );

		if ( $reading_ok ) {
			northline_provisioning_step( $report, 'setting', 'reading', 'skipped', __( 'front page and posts page already set correctly', 'northline' ) );
		} else {
			$detail = sprintf(
				/* translators: 1: front page ID, 2: posts page ID. */
				__( 'static front page = %1$d, posts page = %2$d', 'northline' ),
				$front_id,
				$posts_id
			);

			if ( $dry ) {
				northline_provisioning_step( $report, 'setting', 'reading', 'planned', $detail );
			} else {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $front_id );

				if ( $posts_id ) {
					update_option( 'page_for_posts', $posts_id );
				}

				northline_provisioning_step( $report, 'setting', 'reading', 'updated', $detail );
			}
		}
	}

	/* ---------------------------------------------------------------------
	 * Site identity
	 *
	 * Applied on the first provision only. After that the site title and
	 * tagline belong to whoever edits them in WordPress, and a rerun must not
	 * quietly undo their change.
	 * ------------------------------------------------------------------ */

	$identity_applied = false;

	if ( $options['force_settings'] || empty( $state['settings_applied'] ) ) {
		$detail = sprintf(
			/* translators: 1: site title, 2: tagline. */
			__( 'title "%1$s", tagline "%2$s"', 'northline' ),
			$manifest['settings']['blogname'],
			$manifest['settings']['blogdescription']
		);

		if ( $dry ) {
			northline_provisioning_step( $report, 'setting', 'identity', 'planned', $detail );
		} else {
			update_option( 'blogname', $manifest['settings']['blogname'] );
			update_option( 'blogdescription', $manifest['settings']['blogdescription'] );
			northline_provisioning_step( $report, 'setting', 'identity', 'updated', $detail );
		}

		$identity_applied = true;
	} else {
		northline_provisioning_step(
			$report,
			'setting',
			'identity',
			'skipped',
			__( 'already applied on a previous run; use --force-settings to re-apply', 'northline' )
		);
	}

	/* ---------------------------------------------------------------------
	 * Record what happened
	 * ------------------------------------------------------------------ */

	if ( ! $dry ) {
		$state['version']          = NORTHLINE_PROVISION_VERSION;
		$state['provisioned_at']   = gmdate( 'c' );
		$state['pages']            = $page_ids;
		$state['menus']            = $menu_ids;
		$state['settings_applied'] = ! empty( $state['settings_applied'] ) || $identity_applied;

		update_option( NORTHLINE_PROVISION_OPTION, $state, false );
	}

	return $report;
}

/**
 * Remove menu-item records that belong to no menu at all.
 *
 * wp_update_nav_menu_item() inserts the nav_menu_item post before it finishes
 * writing that item's meta, so a run that dies partway through — as an earlier
 * version of this file did on WordPress 7.1 — can leave a nav_menu_item post
 * that was never attached to a menu.
 *
 * A nav_menu_item with no `nav_menu` term is not part of any menu and is
 * rendered nowhere, so removing one cannot change what a visitor or an editor
 * sees. The candidates are further narrowed to items pointing at pages this
 * command provisioned, which is what makes them attributable to an interrupted
 * run of this command rather than to anything a person did.
 *
 * @param array $page_ids Provisioned page IDs keyed by manifest key.
 * @param bool  $dry      Whether this is a dry run.
 * @param array $report   Report, passed by reference.
 * @return void
 */
function northline_prune_orphan_menu_items( $page_ids, $dry, array &$report ) {
	$page_ids = array_filter( array_map( 'intval', (array) $page_ids ) );

	if ( empty( $page_ids ) ) {
		return;
	}

	$candidates = get_posts(
		array(
			'post_type'              => 'nav_menu_item',
			'post_status'            => 'any',
			'numberposts'            => 100,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
			'suppress_filters'       => false,
			'meta_query'             => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_menu_item_object_id',
					'value'   => array_map( 'strval', array_values( $page_ids ) ),
					'compare' => 'IN',
				),
			),
		)
	);

	$removed = 0;

	foreach ( $candidates as $candidate_id ) {
		$terms = wp_get_object_terms( $candidate_id, 'nav_menu', array( 'fields' => 'ids' ) );

		// Attached to a menu: it is a real menu item, leave it alone.
		if ( is_wp_error( $terms ) || ! empty( $terms ) ) {
			continue;
		}

		if ( ! $dry ) {
			wp_delete_post( $candidate_id, true );
		}

		++$removed;
	}

	if ( ! $removed ) {
		return;
	}

	northline_provisioning_step(
		$report,
		'menu',
		'orphan cleanup',
		$dry ? 'planned' : 'updated',
		sprintf(
			/* translators: %d: number of orphaned menu item records. */
			_n(
				'removed %d menu-item record left behind by an interrupted run',
				'removed %d menu-item records left behind by an interrupted run',
				$removed,
				'northline'
			),
			$removed
		)
	);
}

/**
 * Provision a single navigation menu and assign it to a theme location.
 *
 * Reuses whatever menu is already assigned to the location, adds only the
 * items that are missing, and matches existing items by the page they point
 * at, so reruns never duplicate anything.
 *
 * @param string $location    Theme location slug.
 * @param array  $menu_config Manifest entry for this menu.
 * @param array  $pages       Manifest page definitions.
 * @param array  $page_ids    Resolved page IDs keyed by manifest key.
 * @param array  $state       Stored provisioning record.
 * @param array  $options     Provisioning options.
 * @param array  $report      Report, passed by reference.
 * @return int Menu term ID, or 0 when none could be resolved.
 */
function northline_provision_menu( $location, $menu_config, $pages, $page_ids, $state, $options, array &$report ) {
	$dry = ! empty( $options['dry_run'] );

	if ( ! array_key_exists( $location, get_registered_nav_menus() ) ) {
		$report['warnings'][] = sprintf(
			/* translators: %s: menu location slug. */
			__( 'The theme does not register a "%s" menu location; skipped.', 'northline' ),
			$location
		);

		return 0;
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();
	$menu      = false;

	// 1. Whatever is already assigned to this location wins.
	if ( ! empty( $locations[ $location ] ) ) {
		$menu = wp_get_nav_menu_object( (int) $locations[ $location ] );
	}

	// 2. Then a menu we created on a previous run.
	if ( ! $menu && ! empty( $state['menus'][ $location ] ) ) {
		$menu = wp_get_nav_menu_object( (int) $state['menus'][ $location ] );
	}

	// 3. Then a menu with the manifest name.
	if ( ! $menu ) {
		$menu = wp_get_nav_menu_object( $menu_config['name'] );
	}

	if ( ! $menu && $dry ) {
		// Report the whole plan rather than stopping at the missing menu — the
		// first run is exactly when the operator most needs to see all of it.
		northline_provisioning_step(
			$report,
			'menu',
			$location,
			'planned',
			sprintf(
				/* translators: %s: menu name. */
				__( 'would create the menu "%s"', 'northline' ),
				$menu_config['name']
			)
		);

		$planned = array();

		foreach ( $menu_config['items'] as $key ) {
			if ( ! empty( $page_ids[ $key ] ) || ! empty( $pages[ $key ] ) ) {
				$planned[] = $key;
			}
		}

		northline_provisioning_step(
			$report,
			'menu',
			$location . ' items',
			'planned',
			sprintf(
				/* translators: %s: comma-separated list of page keys. */
				__( 'items: %s', 'northline' ),
				implode( ', ', $planned )
			)
		);

		northline_provisioning_step(
			$report,
			'menu',
			$location . ' location',
			'planned',
			__( 'would assign the new menu to this location', 'northline' )
		);

		return 0;
	}

	if ( ! $menu ) {
		$new_menu_id = wp_create_nav_menu( $menu_config['name'] );

		if ( is_wp_error( $new_menu_id ) ) {
			$report['warnings'][] = sprintf(
				/* translators: 1: menu name, 2: error message. */
				__( 'Could not create the menu "%1$s": %2$s', 'northline' ),
				$menu_config['name'],
				$new_menu_id->get_error_message()
			);

			return 0;
		}

		$menu = wp_get_nav_menu_object( $new_menu_id );

		northline_provisioning_step(
			$report,
			'menu',
			$location,
			'created',
			sprintf(
				/* translators: %s: menu name. */
				__( 'created the menu "%s"', 'northline' ),
				$menu_config['name']
			),
			$menu ? $menu->term_id : 0
		);
	} else {
		northline_provisioning_step(
			$report,
			'menu',
			$location,
			'skipped',
			sprintf(
				/* translators: %s: menu name. */
				__( 'reusing the existing menu "%s"', 'northline' ),
				$menu->name
			),
			$menu->term_id
		);
	}

	if ( ! $menu ) {
		return 0;
	}

	$menu_id = (int) $menu->term_id;

	// Which pages are already in this menu?
	$existing = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'publish,draft' ) );
	$existing = is_array( $existing ) ? $existing : array();
	$linked   = array();

	foreach ( $existing as $item ) {
		if ( 'post_type' === $item->type && 'page' === $item->object ) {
			$linked[ (int) $item->object_id ] = true;
		}
	}

	$position = count( $existing );
	$added    = array();

	foreach ( $menu_config['items'] as $key ) {
		if ( empty( $page_ids[ $key ] ) ) {
			continue;
		}

		$page_id = (int) $page_ids[ $key ];

		if ( isset( $linked[ $page_id ] ) ) {
			continue;
		}

		if ( $dry ) {
			$added[] = $key;
			continue;
		}

		++$position;

		/*
		 * menu-item-classes must be a SPACE-SEPARATED STRING, not an array.
		 * wp_update_nav_menu_item() normalises it itself with
		 * array_map( 'sanitize_html_class', explode( ' ', ... ) ) and stores the
		 * resulting array in _menu_item_classes, so passing an array makes core
		 * call explode() on an array — a TypeError on modern PHP. This matches
		 * what the Menus admin screen submits.
		 */
		$classes = isset( $pages[ $key ]['menu_classes'] ) ? trim( (string) $pages[ $key ]['menu_classes'] ) : '';

		// The menu item title is deliberately left empty so the item follows
		// the page title, including any later rename in the admin.
		$item_id = wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-object-id' => $page_id,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-position'  => $position,
				'menu-item-classes'   => $classes,
			)
		);

		if ( is_wp_error( $item_id ) ) {
			$report['warnings'][] = sprintf(
				/* translators: 1: manifest key, 2: error message. */
				__( 'Could not add "%1$s" to the menu: %2$s', 'northline' ),
				$key,
				$item_id->get_error_message()
			);
			continue;
		}

		// No _menu_item_classes write here: wp_update_nav_menu_item() has
		// already stored the sanitised array form for us.
		$linked[ $page_id ] = true;
		$added[]            = $key;
	}

	if ( $added ) {
		northline_provisioning_step(
			$report,
			'menu',
			$location . ' items',
			$dry ? 'planned' : 'created',
			sprintf(
				/* translators: %s: comma-separated list of page keys. */
				__( 'items: %s', 'northline' ),
				implode( ', ', $added )
			),
			$menu_id
		);
	} else {
		northline_provisioning_step(
			$report,
			'menu',
			$location . ' items',
			'skipped',
			__( 'every page is already in this menu', 'northline' ),
			$menu_id
		);
	}

	// Assign the menu to the theme location.
	if ( ! empty( $locations[ $location ] ) && (int) $locations[ $location ] === $menu_id ) {
		northline_provisioning_step( $report, 'menu', $location . ' location', 'skipped', __( 'already assigned', 'northline' ), $menu_id );
	} elseif ( $dry ) {
		northline_provisioning_step( $report, 'menu', $location . ' location', 'planned', __( 'would assign the menu to this location', 'northline' ), $menu_id );
	} else {
		$locations[ $location ] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
		northline_provisioning_step( $report, 'menu', $location . ' location', 'assigned', __( 'menu assigned to this location', 'northline' ), $menu_id );
	}

	return $menu_id;
}

/**
 * Summarise the current provisioning state for reporting.
 *
 * @return array List of rows: area, item, status, detail.
 */
function northline_provisioning_status() {
	$manifest = northline_provisioning_manifest();
	$state    = northline_provisioning_state();
	$rows     = array();

	foreach ( $manifest['pages'] as $key => $page ) {
		$id = northline_find_provisioned_page( $key );

		if ( ! $id ) {
			$rows[] = array(
				'area'   => 'page',
				'item'   => $key,
				'status' => 'missing',
				'detail' => __( 'not provisioned', 'northline' ),
			);
			continue;
		}

		$post = get_post( $id );

		$rows[] = array(
			'area'   => 'page',
			'item'   => $key,
			'status' => 'ok',
			'detail' => sprintf(
				/* translators: 1: post ID, 2: post status, 3: content size. */
				__( 'ID %1$d, %2$s, %3$s of content', 'northline' ),
				$id,
				$post ? $post->post_status : 'unknown',
				$post ? northline_provisioning_size( $post->post_content ) : '0 B'
			),
		);
	}

	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();

	foreach ( $manifest['menus'] as $location => $menu_config ) {
		$menu = empty( $locations[ $location ] ) ? false : wp_get_nav_menu_object( (int) $locations[ $location ] );

		if ( ! $menu ) {
			$rows[] = array(
				'area'   => 'menu',
				'item'   => $location,
				'status' => 'missing',
				'detail' => __( 'no menu assigned to this location', 'northline' ),
			);
			continue;
		}

		$items = wp_get_nav_menu_items( $menu->term_id );

		$rows[] = array(
			'area'   => 'menu',
			'item'   => $location,
			'status' => 'ok',
			'detail' => sprintf(
				/* translators: 1: menu name, 2: number of items. */
				__( '"%1$s", %2$d items', 'northline' ),
				$menu->name,
				is_array( $items ) ? count( $items ) : 0
			),
		);
	}

	$front_id = isset( $state['pages'][ $manifest['front_page'] ] ) ? (int) $state['pages'][ $manifest['front_page'] ] : northline_find_provisioned_page( $manifest['front_page'] );
	$posts_id = isset( $state['pages'][ $manifest['posts_page'] ] ) ? (int) $state['pages'][ $manifest['posts_page'] ] : northline_find_provisioned_page( $manifest['posts_page'] );

	$rows[] = array(
		'area'   => 'setting',
		'item'   => 'front page',
		'status' => ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === $front_id && $front_id ) ? 'ok' : 'missing',
		'detail' => sprintf(
			/* translators: 1: show_on_front value, 2: page_on_front ID. */
			__( 'show_on_front=%1$s, page_on_front=%2$d', 'northline' ),
			(string) get_option( 'show_on_front' ),
			(int) get_option( 'page_on_front' )
		),
	);

	$rows[] = array(
		'area'   => 'setting',
		'item'   => 'posts page',
		'status' => ( (int) get_option( 'page_for_posts' ) === $posts_id && $posts_id ) ? 'ok' : 'missing',
		'detail' => sprintf(
			/* translators: %d: page_for_posts ID. */
			__( 'page_for_posts=%d', 'northline' ),
			(int) get_option( 'page_for_posts' )
		),
	);

	$rows[] = array(
		'area'   => 'setting',
		'item'   => 'identity',
		'status' => ( get_option( 'blogname' ) === $manifest['settings']['blogname'] ) ? 'ok' : 'custom',
		'detail' => sprintf(
			/* translators: %s: current site title. */
			__( 'site title is "%s"', 'northline' ),
			(string) get_option( 'blogname' )
		),
	);

	$rows[] = array(
		'area'   => 'record',
		'item'   => 'last run',
		'status' => $state['provisioned_at'] ? 'ok' : 'missing',
		'detail' => $state['provisioned_at'] ? $state['provisioned_at'] : __( 'never provisioned', 'northline' ),
	);

	return $rows;
}
