<?php
namespace JosephG\Roko\Application\Security\Fix;

/**
 * Application Service: Maps business codes to WordPress fix routes.
 *
 * Translates domain business codes into actionable WordPress REST API endpoints.
 */
final class SecurityFixMapper {

	/**
	 * Mapping of business codes to fix routes and confirmation requirements.
	 */
	const FIX_MAPPINGS = array(
		// File Security fixes
		'debug_on'                => array(
			'route'             => '/wp-json/roko/v1/fix/disable-wp-debug',
			'needsConfirmation' => false,
		),
		'editor_on'               => array(
			'route'             => '/wp-json/roko/v1/fix/disable-file-editor',
			'needsConfirmation' => false,
		),
		'listing_on'              => array(
			'route'             => '/wp-json/roko/v1/fix/disable-directory-listing',
			'needsConfirmation' => false,
		),
		'xmlrpc_on'               => array(
			'route'             => '/wp-json/roko/v1/fix/disable-xmlrpc',
			'needsConfirmation' => false,
		),
		'dashboard_installs_on'   => array(
			'route'             => '/wp-json/roko/v1/fix/disable-dashboard-installs',
			'needsConfirmation' => false,
		),
		'php_exec_uploads_on'     => array(
			'route'             => '/wp-json/roko/v1/fix/disable-php-execution-uploads',
			'needsConfirmation' => true,
		),

		// File permission fixes (potentially disruptive)
		'wp_config_perms_insecure' => array(
			'route'             => '/wp-json/roko/v1/fix/secure-wp-config-permissions',
			'needsConfirmation' => true,
		),
		'htaccess_perms_insecure'  => array(
			'route'             => '/wp-json/roko/v1/fix/secure-htaccess-permissions',
			'needsConfirmation' => true,
		),

		// File cleanup (potentially destructive)
		'sensitive_files_exposed'  => array(
			'route'             => '/wp-json/roko/v1/fix/remove-sensitive-files',
			'needsConfirmation' => true,
		),
		'backup_files_exposed'     => array(
			'route'             => '/wp-json/roko/v1/fix/remove-backup-files',
			'needsConfirmation' => true,
		),
		'log_files_exposed'        => array(
			'route'             => '/wp-json/roko/v1/fix/remove-log-files',
			'needsConfirmation' => true,
		),

		// Security Keys (already implemented, high confirmation)
		'weak_constant'            => array(
			'route'             => '/wp-json/roko/v1/fix/rotate-security-keys',
			'needsConfirmation' => true,
		),
		'weak_roko'                => array(
			'route'             => '/wp-json/roko/v1/fix/rotate-security-keys',
			'needsConfirmation' => true,
		),
	);

	/**
	 * Get fix data for a business code.
	 *
	 * @param string $businessCode The business code emitted by domain
	 * @return array|null Fix data with route and needsConfirmation, or null if not fixable
	 */
	public static function getFixForBusinessCode( $businessCode ) {
		return self::FIX_MAPPINGS[ $businessCode ] ?? null;
	}

	/**
	 * Check if a business code has an available fix.
	 *
	 * @param string $businessCode The business code to check
	 * @return bool True if fix is available
	 */
	public static function hasFixForBusinessCode( $businessCode ) {
		return isset( self::FIX_MAPPINGS[ $businessCode ] );
	}

	/**
	 * Get all available fix routes.
	 *
	 * @return array Array of all fix routes
	 */
	public static function getAllFixRoutes() {
		return array_column( self::FIX_MAPPINGS, 'route' );
	}
} 