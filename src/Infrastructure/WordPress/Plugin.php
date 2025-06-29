<?php
namespace JosephG\Roko\Infrastructure\WordPress;

use JosephG\Roko\Infrastructure\WordPress\Database\Delta;
use JosephG\Roko\Infrastructure\WordPress\Integration\QueryMonitor\Bridge as QueryMonitorBridge;
use JosephG\Roko\Infrastructure\WordPress\Admin\AdminPage;

// Security.
use JosephG\Roko\Infrastructure\WordPress\Security\SecurityJsonService;
use JosephG\Roko\Domain\Security\SecurityAggregate;
use JosephG\Roko\Infrastructure\WordPress\Security\WpSecurityKeysProvider;
use JosephG\Roko\Infrastructure\WordPress\Security\WpFileSecurityProvider;
use JosephG\Roko\Infrastructure\WordPress\Repository\WpUserSecurityRepository;
use JosephG\Roko\Infrastructure\WordPress\Repository\WpNetworkSecurityRepository;
use JosephG\Roko\Infrastructure\WordPress\Repository\WpFileIntegrityRepository;
use JosephG\Roko\Infrastructure\WordPress\Repository\WpVulnerabilityRepository;

class Plugin {

	/** Bootstrap the plugin (called from roko.php) */
	public static function init(): void {
		// Run migrations when the plugin is activated
		register_activation_hook(
			ROKO_PLUGIN_FILE,
			array( __CLASS__, 'runMigrations' )
		);

		QueryMonitorBridge::init();

		$aggregate = new SecurityAggregate(
			new WpSecurityKeysProvider(),
			new WpFileSecurityProvider(),
			new WpFileIntegrityRepository(),
			new WpVulnerabilityRepository(),
		);

		new SecurityJsonService( $aggregate );

		// Also run on every init to catch upgrades without reactivating
		add_action( 'init', array( __CLASS__, 'runMigrations' ) );
		add_action( 'admin_menu', array( new AdminPage(), 'add_admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( new AdminPage(), 'enqueue_admin_assets' ) );
	}

	/**
	 * Execute any pending migrations via the Delta runner.
	 */
	public static function runMigrations(): void {
		$delta = new Delta();
		$delta->migrate();
	}
}
