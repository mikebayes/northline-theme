<?php
/**
 * Title: Contact - Details, Hours and Emergency
 * Slug: northline/contact-details
 * Categories: northline-sections
 * Description: Three cards covering how to reach the office, where it is, and what counts as an emergency. Drop a form plugin's block into the first card if you add one later.
 * Keywords: contact, phone, address, hours, emergency
 * Viewport width: 1400
 *
 * @package Northline
 */

?>
<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","contentSize":"78rem"}} -->
<div class="wp-block-group alignwide">

	<!-- wp:columns {"className":"is-style-nl-cards"} -->
	<div class="wp-block-columns is-style-nl-cards">

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"is-style-nl-eyebrow"} -->
			<p class="is-style-nl-eyebrow">Talk to us</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size">Phone and email</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p><strong><a href="tel:+12045550142">(204) 555-0142</a></strong><br>Mon&ndash;Fri, 7:00am&ndash;5:00pm</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph -->
			<p><a href="mailto:quotes@example.com">quotes@example.com</a><br>Quotes answered the same business day.</p>
			<!-- /wp:paragraph -->

			<!-- wp:paragraph {"fontSize":"x-small"} -->
			<p class="has-x-small-font-size">Sending photos of the panel, the meter and the work area gets you a faster and more accurate number.</p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"is-style-nl-eyebrow"} -->
			<p class="is-style-nl-eyebrow">Find us</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size">Shop and office</h3>
			<!-- /wp:heading -->

			<!-- wp:paragraph -->
			<p>Unit 4 &ndash; 1187 Fife Street<br>Winnipeg, MB&nbsp; R2X 2N6</p>
			<!-- /wp:paragraph -->

			<!-- wp:list {"className":"is-style-nl-specs","fontSize":"small"} -->
			<ul class="wp-block-list is-style-nl-specs has-small-font-size">
				<!-- wp:list-item -->
				<li><strong>Office</strong> Mon&ndash;Fri, 7am&ndash;5pm</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><strong>Pickups</strong> By appointment</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li><strong>Parking</strong> Free, off Dufferin</li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column -->
		<div class="wp-block-column">
			<!-- wp:paragraph {"className":"is-style-nl-eyebrow"} -->
			<p class="is-style-nl-eyebrow">After hours</p>
			<!-- /wp:paragraph -->

			<!-- wp:heading {"level":3,"fontSize":"medium"} -->
			<h3 class="wp-block-heading has-medium-font-size">What counts as an emergency</h3>
			<!-- /wp:heading -->

			<!-- wp:list {"className":"is-style-nl-checklist","fontSize":"small"} -->
			<ul class="wp-block-list is-style-nl-checklist has-small-font-size">
				<!-- wp:list-item -->
				<li>Burning smell, smoke or scorch marks</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Water reaching a panel or receptacle</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Total loss of power with the utility reporting none</li>
				<!-- /wp:list-item -->
				<!-- wp:list-item -->
				<li>Loss of heat, sump or life-safety systems</li>
				<!-- /wp:list-item -->
			</ul>
			<!-- /wp:list -->

			<!-- wp:buttons -->
			<div class="wp-block-buttons">
				<!-- wp:button -->
				<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="tel:+12045550142">Call the 24h line</a></div>
				<!-- /wp:button -->
			</div>
			<!-- /wp:buttons -->
		</div>
		<!-- /wp:column -->

	</div>
	<!-- /wp:columns -->

</div>
<!-- /wp:group -->
