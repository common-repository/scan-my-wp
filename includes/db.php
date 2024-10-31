<?php
/**
 * Installation of required tables in database
 *
 * @package Scan My WP
 */

global $smwp_db_version;
$smwp_db_version = '1.0';

/**
 * Create table smwp_scans
 *
 * @since 1.0
 */
function smwp_install() {
	global $wpdb;
	global $smwp_db_version;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		scan_id int(10) NOT NULL,
		results text NULL,
		status varchar(25) NULL,
		created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		updated timestamp,
		PRIMARY KEY  (scan_id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	dbDelta( $sql );

	add_option( 'smwp_db_version', $smwp_db_version );

}

/**
 * Insert data into smwp_scans
 *
 * @since 1.0
 *
 * @param int $scan_id global scan id.
 * @param str $status status of scan.
 */
function smwp_db_insert_scan( $scan_id, $status ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$wpdb->insert(
		$table_name,
		array(
			'scan_id' => $scan_id,
			'status'  => $status,
			'created' => date( 'Y-m-d H:i:s' ),
			'updated' => date( 'Y-m-d H:i:s' ),
		)
	); // db call ok.

	if ( $wpdb->last_result ) {
		die( $wpdb->last_result );
	}
}

/**
 * Update table smwp_scans
 *
 * @since 1.0
 *
 * @param int $scan_id Global scan id.
 * @param arr $results Array of result.
 * @param str $status Status of scan.
 */
function smwp_db_update_scan( $scan_id, $results, $status ) {

	global $wpdb;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$update_data = array( 'updated' => date( 'Y-m-d H:i:s' ) );

	if ( $results !== false ) {
		$update_data['results'] = serialize( $results );
	}

	if ( $status !== false ) {
		$update_data['status'] = $status;
	}

	if ( count( $update_data ) === 0 ) {
		return;
	}

	$wpdb->update(
		$table_name,
		$update_data,
		array(
			'scan_id' => $scan_id,
		)
	); // db call ok.
}

/**
 * Get data from smwp_scans
 *
 * @since 1.0
 *
 * @param int $scan_id Global scan id.
 */
function smwp_db_get_scan( $scan_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$results = $wpdb->get_row( "SELECT * FROM $table_name WHERE scan_id = $scan_id" );

	return $results;
}

/**
 * Get latest scan from table smwp_scans
 *
 * @since 1.0
 */
function smwp_db_get_latest_scan() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$results = $wpdb->get_row( "SELECT * FROM $table_name WHERE status = 'completed' ORDER BY created DESC LIMIT 1" );

	return $results;
}

/**
 * get last five scan from table smwp_scans
 *
 * @since 1.0
 */
function smwp_db_get_last_5() {
	global $wpdb;

	$table_name = $wpdb->prefix . 'smwp_scans';

	$results = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY created DESC LIMIT 5" );

	return $results;
}
