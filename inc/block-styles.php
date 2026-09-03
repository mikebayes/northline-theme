<?php
/**
 * Custom block style variations.
 *
 * These are the main mechanism that lets an editor build a custom-looking page
 * out of ordinary core blocks: pick a Group, choose "Dark Section" in the
 * sidebar, and it renders as a designed band of the site. Every variation is
 * implemented in assets/css/blocks.css against an .is-style-* class, so the
 * same styling applies on the front end and inside the editor canvas.
 *
 * @package Northline
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Northline's block style variations.
 *
 * @return void
 */
function northline_register_block_styles() {
	$styles = array(
		'core/group'      => array(
			array(
				'name'  => 'nl-section-dark',
				'label' => __( 'Section — Dark', 'northline' ),
			),
			array(
				'name'  => 'nl-section-surface',
				'label' => __( 'Section — Surface', 'northline' ),
			),
			array(
				'name'  => 'nl-intro',
				'label' => __( 'Section Intro', 'northline' ),
			),
			array(
				'name'  => 'nl-card',
				'label' => __( 'Card', 'northline' ),
			),
			array(
				'name'  => 'nl-panel-outline',
				'label' => __( 'Outlined Panel', 'northline' ),
			),
			array(
				'name'  => 'nl-rule-top',
				'label' => __( 'Accent Rule (top)', 'northline' ),
			),
		),
		'core/columns'    => array(
			array(
				'name'  => 'nl-cards',
				'label' => __( 'Card Grid', 'northline' ),
			),
			array(
				'name'  => 'nl-divided',
				'label' => __( 'Divided Columns', 'northline' ),
			),
		),
		'core/column'     => array(
			array(
				'name'  => 'nl-card',
				'label' => __( 'Card', 'northline' ),
			),
		),
		'core/heading'    => array(
			array(
				'name'  => 'nl-eyebrow',
				'label' => __( 'Eyebrow', 'northline' ),
			),
			array(
				'name'  => 'nl-underline',
				'label' => __( 'Accent Underline', 'northline' ),
			),
		),
		'core/paragraph'  => array(
			array(
				'name'  => 'nl-lead',
				'label' => __( 'Lead Paragraph', 'northline' ),
			),
			array(
				'name'  => 'nl-eyebrow',
				'label' => __( 'Eyebrow', 'northline' ),
			),
		),
		'core/list'       => array(
			array(
				'name'  => 'nl-checklist',
				'label' => __( 'Checklist', 'northline' ),
			),
			array(
				'name'  => 'nl-specs',
				'label' => __( 'Spec List', 'northline' ),
			),
		),
		'core/image'      => array(
			array(
				'name'  => 'nl-framed',
				'label' => __( 'Framed', 'northline' ),
			),
			array(
				'name'  => 'nl-soft',
				'label' => __( 'Soft Shadow', 'northline' ),
			),
		),
		'core/quote'      => array(
			array(
				'name'  => 'nl-testimonial',
				'label' => __( 'Testimonial', 'northline' ),
			),
		),
		'core/separator'  => array(
			array(
				'name'  => 'nl-accent',
				'label' => __( 'Accent Bar', 'northline' ),
			),
		),
		'core/cover'      => array(
			array(
				'name'  => 'nl-scrim',
				'label' => __( 'Editorial Scrim', 'northline' ),
			),
		),
		'core/media-text' => array(
			array(
				'name'  => 'nl-panel',
				'label' => __( 'Panelled', 'northline' ),
			),
		),
		'core/table'      => array(
			array(
				'name'  => 'nl-clean',
				'label' => __( 'Clean Rows', 'northline' ),
			),
		),
		'core/buttons'    => array(
			array(
				'name'  => 'nl-stacked-mobile',
				'label' => __( 'Full Width on Mobile', 'northline' ),
			),
		),
	);

	foreach ( $styles as $block => $variations ) {
		foreach ( $variations as $variation ) {
			register_block_style( $block, $variation );
		}
	}
}
add_action( 'init', 'northline_register_block_styles' );
