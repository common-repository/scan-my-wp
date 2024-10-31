<?php
/**
 * Enqueue the scripts and styles
 *
 * @package Scan My WP
 */

add_action( 'admin_enqueue_scripts', 'smwp_load_admin_scripts' );

/**
 * Enqueue admin scripts and style
 *
 * @since 1.0
 *
 * @param str $hook Page slug in admin side.
 */
function smwp_load_admin_scripts( $hook ) {
	if ( 'toplevel_page_scan-my-wp' !== $hook ) {
		return;
	}

	wp_register_style( 'scan-my-wp-fa', plugins_url( '../css/font-awesome.min.css', __FILE__ ), array(), '1.0.0' );
	wp_register_style( 'scan-my-wp-bulma', plugins_url( '../css/bulma.min.css', __FILE__ ), array(), '1.0.0' );
	wp_register_style( 'scan-my-wp-admin', plugins_url( '../css/admin.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_style( 'scan-my-wp-admin' );
	wp_enqueue_style( 'scan-my-wp-bulma' );
	wp_enqueue_style( 'scan-my-wp-fa' );
	wp_enqueue_script( 'scan-my-wp-admin', plugins_url( '../js/admin.js', __FILE__ ), array( 'jquery' ), '1.0.5', false );
}
