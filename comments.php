<?php
/**
 * Comments area for Insights articles.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-area__title">
			<?php
			$northline_comment_count = get_comments_number();

			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s comment', '%s comments', (int) $northline_comment_count, 'northline' ) ),
				esc_html( number_format_i18n( $northline_comment_count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => esc_html__( 'Older comments', 'northline' ),
				'next_text' => esc_html__( 'Newer comments', 'northline' ),
			)
		);
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'northline' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>

</section>
