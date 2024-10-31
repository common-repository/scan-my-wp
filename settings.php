<?php
/**
 * Display plugin admin interface
 *

 * @package Scan My WP
 */

add_action( 'admin_menu', 'smwp_create_admin_menu' );

/**
 * Create admin menu
 *
 * @since 1.0
 */

function smwp_create_admin_menu() {
	add_menu_page( 'Scan My WP', 'Scan my WP', 'manage_options', 'scan-my-wp', 'smwp_menu_output' );
}

/**
 * Admin menu output
 *
 * @since 1.0
 */

function smwp_menu_output() {
	$vuln_count = $disc_count = 0;
	require 'includes/functions.php';
	?>
	<div class="titan-framework-panel-wrap">
		<div class="options-container">            
			<?php
			$msgbox = array(
				'msg'  => false,
				'type' => 'info',
			);

			$is_message_warning = '';


			if ( ( isset( $_POST['smwp_scan_nonce_field'] ) && wp_verify_nonce( $_POST['smwp_scan_nonce_field'], 'smwp_scan_action' ) && isset( $_POST['scanNow'] ) ) || isset( $_GET['check_results'] ) ) {
				$args = [];

				$scan_launch = true;

				$args['site']    = site_url();
				$args['type']    = isset( $_GET['check_results'] ) ? 'check' : 'launch';
				$args['scan_id'] = get_option( 'smwp_last_scan_id' );

				if ( isset( $_POST['scanNow'] ) ) {
					$args['type'] = 'launch';
				}

				if ( ( isset( $_GET['check_results'] ) && ! isset( $_POST['scanNow'] ) ) && ! $args['scan_id'] ) {
					$scan_launch = false;
				}

				if ( $scan_launch ) {

					$query = http_build_query( $args );

					$response = wp_remote_get( 'https://api.scanmywp.com/api.php?' . $query );

					if ( is_array( $response ) ) {
						$header = $response['headers']; // array of http header lines.
						$json   = json_decode( $response['body'] ); // use the content.

						if ( $json ) {

							if ( isset( $json->error ) && $json->error ) {
								$msgbox['msg']      = $json->errorMsg;
								$is_message_warning = 'is-warning';
							} else {
								switch ( $json->status ) {
									case 'new':
										$msgbox['msg'] = 'Scan has started successfully. We will keep checking the results in background, please keep this browser window open';

										update_option( 'smwp_last_scan_id', $json->results );

										if ( 'launch' === $args['type'] ) {
											smwp_db_insert_scan( $json->results, 'new' );
										}

										break;

									case 'progress':
										$msgbox['msg'] = 'The status of the scan is ' . $json->status;

										update_option( 'smwp_last_scan_id', $json->results );

										smwp_db_update_scan( $json->results, false, $json->status );

										break;
									case 'completed':
										update_option( 'smwp_last_scan_results', serialize( $json->results ) );

										smwp_db_update_scan( $args['scan_id'], $json->results, $json->status );

										update_option( 'smwp_last_scan_id', false );

										$results = $json->results;

										break;

									case 'failed':
										$msgbox['msg'] = 'Scan has failed to start';
										break;
									default:
										// code...
										break;
								}
							}
						}
					}
				}
			}

			$scan_data = smwp_db_get_latest_scan();

			$last_scanned = 'never';

			if ( ( $results = $scan_data->results ) || $scan_data->updated ) {

				$last_scanned = ScanMyWPTools::time_ago( $scan_data->updated );

				$results = unserialize( $results );
				if ( is_array( $results ) && count( $results ) > 0 ) {
					$vuln_table = '';
					$disc_table = '';

					foreach ( $results as $res ) {

						switch ( $res->type ) {
							case 'vulnerability':
								$vuln_table .= ScanMyWPTable::card( $res );
								$vuln_count++;
								break;

							case 'discovery':

								$disc_table .= ScanMyWPTable::card( $res );
								$disc_count++;
								break;

							default:
								// code...
								break;
						}
					}
				}
			}

			$smwp_last_scan_id = get_option( 'smwp_last_scan_id' );
			$smwp_last_scan_id = $smwp_last_scan_id ? $smwp_last_scan_id : 'false';

			$last_five_scans = smwp_db_get_last_5();

			include 'templates/scanmywp.tpl.php';
			echo $html;
			?>

		</div>
	</div>
	<?php
}
