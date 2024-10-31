<?php
/**
 * Plugin Name: Scan My WP
 * Plugin URI: http://scan-my-wp.wpwave.com/
 * Description: Scan security vulnerabilities on your WordPress site before the bad guys do!
 * Author: wpWave
 * Author URI: http://wpwave.com
 * Version: 1.0.0
 * Text Domain: scan-my-wp
 * Domain Path: /
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Network: True
 */

require_once 'load.php'; // load Titan Framework.

require_once 'includes/db.php';

require_once 'includes/scripts.php';

require_once 'settings.php';

register_activation_hook( __FILE__, 'smwp_install' );
