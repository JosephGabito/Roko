<?php
namespace JosephG\Roko\Infrastructure\WordPress\Database;

use wpdb;

/**
 * Handles versioned migrations via separate files in the Migrations folder.
 */
class Delta {

	/** @var wpdb */
	private $wpdb;
	/** @var string */
	private $charset;
	/** @var string */
	private $migrations_path;

	public function __construct() {

		global $wpdb;

		$this->wpdb            = $wpdb;
		$this->charset         = $wpdb->get_charset_collate();
		$this->migrations_path = __DIR__ . '/Migrations';
	}

	/**
	 * Run all pending migrations (versioned PHP files) in chronological order.
	 */
	public function migrate(): void {

		$migrations = $this->getMigrations();

		foreach ( $migrations as $version => $file ) {
			$this->runMigration( $version, $file );
		}
	}

	/**
	 * Scan Migrations directory for PHP files named by version and sort them.
	 *
	 * @return array<string,string> map version => filepath
	 */
	private function getMigrations(): array {

		$files = glob( $this->migrations_path . '/*.php' );

		usort(
			$files,
			function ( $a, $b ) {
				return version_compare( basename( $a, '.php' ), basename( $b, '.php' ) );
			}
		);

		$map = array();

		foreach ( $files as $file ) {
			$version         = basename( $file, '.php' );
			$map[ $version ] = $file;
		}

		return $map;
	}

	/**
	 * Execute a single migration file if its version is newer than the stored one.
	 *
	 * @param string $version Migration version (filename)
	 * @param string $file    Path to migration file
	 */
	private function runMigration( string $version, string $file ): void {

		$applied = get_option( 'roko_db_version', '0.0.0' );

		if ( ! version_compare( $applied, $version, '<' ) || ! file_exists( $file ) ) {
			return;
		}

		/** @noinspection PhpIncludeInspection */
		$file = require_once $file;

		if ( ! is_array( $file ) ) {
			return;
		}

		$file[ $version ]();

		update_option( 'roko_db_version', $version );
	}
}
