<?php
/**
 * Plugin Name: Roko AI Butler
 * Plugin URI:  https://example.com/roko
 * Description: AI-powered WordPress butler that advises on performance, security, and more.
 * Version:     0.1.0
 * Author:      Joseph Gabito
 * Author URI:  https://josephwp.com
 * Text Domain: roko
 * Requires PHP: 7.4
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package Roko
 * @author  Joseph Gabito
 * @license GPL-2.0+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ROKO_PLUGIN_FILE', __FILE__ );
define( 'ROKO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'ROKO_PLUGIN_DIR', __DIR__ );
define( 'ROKO_PLUGIN_VERSION', '0.1.0' );

require_once __DIR__ . '/vendor/autoload.php';

( new JosephG\Roko\Infrastructure\WordPress\Plugin() )->init();
