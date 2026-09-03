<?php
/**
 * Title: About - Split with Credentials Panel
 * Slug: northline/about-split
 * Categories: northline-sections
 * Description: A two-column section: story copy on the left, an outlined panel of licence and coverage details on the right. Swap the panel for an Image block to lead with a photo instead.
 * Keywords: about, story, credentials, licence, two columns
 * Viewport width: 1400
 *
 * @package Northline
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"}}},"layout":{"type":"constrained","contentSize":"78rem"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|70"}}}} -->
	<div class="wp-block-columns">

		<!-- wp:column {"width":"56%"} -->
		<div class="wp-block-column" style="flex-basis:56%">

			<!-- wp:paragraph {"className":"is-style-nl-eyebrow"} -->
			<p class="is-style-nl-eyebrow">Who you are hiring</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading -->
			<h2 class="wp-block-heading">A small crew that answers its own phone</h2>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Northline started in a garage off Fife Street with one van and a licence. We still run lean on purpose: the person who quotes your job is the person who shows up to do it, and you get their cell number rather than a dispatch queue.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph -->
			<p>Most of our work comes from homeowners in River Heights, St. Vital and Transcona, plus a steady list of property managers who need someone reliable when a panel starts tripping at seven on a Sunday morning. We turn down more work than we take so the jobs we accept get finished properly.</p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"is-style-nl-checklist"} -->
			<ul class="wp-block-list is-style-nl-checklist">
				<!-- wp:list-item -->
				<li>Written quotes with a fixed price, not a range</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Permits pulled and inspections booked by us</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Boots off, drop sheets down, debris hauled out</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Two-year workmanship warranty on every install</li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

		</div>
		<!-- /wp:column -->

		<!-- wp:column {"width":"44%"} -->
		<div class="wp-block-column" style="flex-basis:44%">

			<!-- wp:group {"className":"is-style-nl-panel-outline","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-nl-panel-outline">

				<!-- wp:heading {"level":3,"fontSize":"medium"} -->
				<h3 class="wp-block-heading has-medium-font-size">The paperwork, up front</h3>
				<!-- /wp:heading -->

				<!-- wp:list {"className":"is-style-nl-specs"} -->
				<ul class="wp-block-list is-style-nl-specs">
					<!-- wp:list-item -->
					<li><strong>Contractor licence</strong> MB&nbsp;E-0000&nbsp;(placeholder)</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><strong>Liability</strong> $5,000,000</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><strong>WCB Manitoba</strong> Clearance on request</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><strong>Service area</strong> Winnipeg + 60&nbsp;km</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><strong>Regular hours</strong> Mon&ndash;Fri, 7am&ndash;5pm</li>
					<!-- /wp:list-item -->
					<!-- wp:list-item -->
					<li><strong>Emergency</strong> 24 hours, every day</li>
					<!-- /wp:list-item -->
				</ul>
				<!-- /wp:list -->

				<!-- wp:paragraph {"fontSize":"x-small"} -->
				<p class="has-x-small-font-size">Certificates of insurance and clearance letters are sent with every commercial quote.</p>
				<!-- /wp:paragraph -->

			</div>
			<!-- /wp:group -->

		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
