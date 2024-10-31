<?php
/**
 * When using the embedded framework, use it only if the framework
 * plugin isn't activated.
 *
 * @package Scan My WP
 */

// Don't do anything when we're activating a plugin to prevent errors.
// on redeclaring Titan classes.
if ( ! empty( $_GET['action'] ) && ! empty( $_GET['plugin'] ) ) {
	if ( $_GET['action'] === 'activate' ) {
		return;
	}
}
