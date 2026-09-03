<?php
/**
 * Title: Insights - Latest Articles
 * Slug: northline/insights-teaser
 * Categories: northline-sections
 * Description: A section heading plus the three most recent Insights posts, pulled live from the Posts list by the core Latest Posts block.
 * Keywords: insights, blog, posts, articles, latest, news
 * Viewport width: 1400
 *
 * @package Northline
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","contentSize":"78rem"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)">

	<!-- wp:group {"className":"is-style-nl-intro","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
	<div class="wp-block-group is-style-nl-intro">
		<!-- wp:paragraph {"className":"is-style-nl-eyebrow"} -->
		<p class="is-style-nl-eyebrow">Insights</p>
		<!-- /wp:paragraph -->

		<!-- wp:heading -->
		<h2 class="wp-block-heading">Plain answers to the questions we get asked on site</h2>
		<!-- /wp:heading -->
	</div>
	<!-- /wp:group -->

	<!-- wp:latest-posts {"postsToShow":3,"displayPostContent":true,"excerptLength":24,"displayPostDate":true,"postLayout":"grid","columns":3,"displayFeaturedImage":true,"featuredImageSizeSlug":"northline-card","addLinkToFeaturedImage":true} /-->

</div>
<!-- /wp:group -->
