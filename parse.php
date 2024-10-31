<?php
/**
 * Parse functions
 *
 * @package Scan My WP
 */

/**
 * Check the array count to two
 *
 * @since 1.0.0
 *
 * @param arr $match Array to match the count.
 * @param str $default Any default value.
 */
function smwp_is_match( $match, $default = '' ) {
	if ( count( $match ) === 2 ) {
		return $match[1];
	}
	return $default;
}

/**
 * Read and add smwp log
 *
 * @since 2.7.0
 */
function smwp_parse_log() {
	// wpscan parser.
	$wpscan = file_get_contents( dirname( __FILE__ ) . '/smwp.log.txt' );

	$out = array();

	if ( preg_match( '/WordPress version can not be detected/', $wpscan ) ) {
		$out['wp_version'] = false;
	} else {
		preg_match( '/WordPress version (.*?) identified/s', $wpscan, $match );
		$out['wp_version'] = smwp_is_match( $match, false );
		// core analysis.
		preg_match( '/([0-9]+) vulnerabilities identified from the version number/', $wpscan, $match );
		$out['core']              = array();
		$out['core']['num_vulns'] = smwp_is_match( $match, 0 );
		$out['core']['vulns']     = array();
		preg_match( '/from the version number(.*)\[\+\] WordPress theme/s', $wpscan, $match );
		$core_vulns = smwp_is_match( $match, false );
		if ( $core_vulns !== false ) {
			$core_vulns_exp = explode( "\n", trim( $core_vulns ) );
			$new            = false;
			$vuln           = array();
			foreach ( $core_vulns_exp as $cv ) {
				preg_match( '/Title: (.*)/', $cv, $match );

				if ( ( $title = smwp_is_match( $match, false ) ) && ! $new ) {
					$vuln['title'] = $title;
					$vuln['refs']  = array();
					$vuln['fixed'] = '';
					$new           = true;

				} elseif ( ( $title = smwp_is_match( $match, false ) ) && $new ) {

					array_push( $out['core']['vulns'], $vuln );
					$vuln['title'] = $title;
					$vuln['refs']  = array();
					$vuln['fixed'] = '';

					$new = false;

				} else {

					preg_match( '/Reference: (.*)/', $cv, $match );
					if ( $ref = smwp_is_match( $match, false ) ) {
						array_push( $vuln['refs'], $ref );
					}

					preg_match( '/Fixed in: (.*)/', $cv, $match );
					if ( $fixed = smwp_is_match( $match, false ) ) {
						$vuln['fixed'] = $fixed;
					}
				}
			}
		}

		// theme analysis.
		preg_match( '/WordPress theme in use: (.*)/', $wpscan, $match );

		$out['theme'] = array();

		$out['theme']['name'] = smwp_is_match( $match );

		preg_match( '/WordPress theme in use: .*?\n(.*?)\[\+\] Enumerating plugins/s', $wpscan, $match );

		$out['theme']['details'] = smwp_is_match( $match );

		if ( $out['theme']['details'] !== '' ) {
			$out['theme']['details'] = preg_replace( '/\[\+\] Name.*/', '', $out['theme']['details'] );
			$out['theme']['details'] = preg_replace( '/\|  /', '', $out['theme']['details'] );
			$out['theme']['details'] = preg_replace( '/(.*?): /', '<strong>$1</strong>: ', $out['theme']['details'] );
			$out['theme']['details'] = preg_replace( '/(\[!\].*)/', '<span style="color: red">$1</span>', $out['theme']['details'] );

			$out['theme']['details'] = nl2br( trim( $out['theme']['details'] ) );
		}
	}

	return $out;

}
