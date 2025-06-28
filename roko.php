<?php
/**
 * Plugin Name:     Roko - The WordPress Butler
 * Plugin URI:      https://rokosaurus.com
 * Description:     Roko quietly manages everything behind the scenes, spots stuff you didn't see, and offers one-click fixes.
 * Version:         0.1.0
 * Author:          Roko
 * Author URI:      https://rokosaurus.com
 * Text Domain:     roko
 * Domain Path:     /languages
 * Requires PHP:    7.4
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package         Roko
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ROKO_PLUGIN_FILE',   __FILE__ );
define( 'ROKO_PLUGIN_URL',    plugin_dir_url( __FILE__ ) );
define( 'ROKO_PLUGIN_DIR',    __DIR__ );
define( 'ROKO_PLUGIN_VERSION', '0.1.0' );

require_once __DIR__ . '/vendor/autoload.php';

$plugin = new JosephG\Roko\Infrastructure\WordPress\Plugin();
$plugin->init();
