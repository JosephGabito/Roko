<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * FileSecurityChecks recommendation strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class FileSecurityChecksI18n {

	/**
	 * Get the recommendation for a given file security business code.
	 *
	 * @param string $businessCode Business code from domain (e.g., 'directory_listing_vulnerable').
	 * @return string The recommendation for the given business code.
	 */
	public static function recommendation( $businessCode ) {
		$recommendations = self::recommendationsCollection();

		if ( isset( $recommendations[ $businessCode ] ) ) {
			return $recommendations[ $businessCode ];
		}

		// Fallback for unknown codes
		return __( 'Review file security configuration and apply appropriate hardening measures.', 'roko' );
	}

	/**
	 * Get the recommendations collection for file security business codes.
	 *
	 * @return array The recommendations collection.
	 */
	private static function recommendationsCollection() {
		return array(
			// Directory Listing
			'directory_listing_vulnerable'  => __( 'Add "Options -Indexes" to your .htaccess file to prevent directory browsing.', 'roko' ),
			'directory_listing_secure'      => __( 'Directory listing is properly disabled. Good security practice!', 'roko' ),

			// Debug Mode
			'wp_debug_vulnerable'           => __( 'Disable WP_DEBUG in production by setting it to false in wp-config.php.', 'roko' ),
			'wp_debug_secure'               => __( 'Debug mode is disabled. Excellent for production security.', 'roko' ),

			// File Editor
			'file_editor_vulnerable'        => __( 'Disable the file editor by adding "define(\'DISALLOW_FILE_EDIT\', true);" to wp-config.php.', 'roko' ),
			'file_editor_secure'            => __( 'File editor is disabled. This prevents unauthorized code changes.', 'roko' ),

			// Dashboard Installs
			'dashboard_installs_vulnerable' => __( 'Disable dashboard installations by adding "define(\'DISALLOW_FILE_MODS\', true);" to wp-config.php.', 'roko' ),
			'dashboard_installs_secure'     => __( 'Dashboard installs are restricted. This prevents unauthorized plugin/theme installations.', 'roko' ),

			// PHP Execution in Uploads
			'php_exec_uploads_vulnerable'   => __( 'Block PHP execution in uploads directory by adding .htaccess rules or server configuration.', 'roko' ),
			'php_exec_uploads_secure'       => __( 'PHP execution is blocked in uploads directory. Great security practice!', 'roko' ),

			// Sensitive Files
			'sensitive_files_vulnerable'    => __( 'Move wp-config.php outside web root or add .htaccess protection for sensitive files.', 'roko' ),
			'sensitive_files_secure'        => __( 'Sensitive files are properly protected from web access.', 'roko' ),

			// XML-RPC
			'xmlrpc_vulnerable'             => __( 'Consider disabling XML-RPC if not needed, or use a security plugin to limit access.', 'roko' ),
			'xmlrpc_secure'                 => __( 'XML-RPC is disabled, reducing potential attack vectors.', 'roko' ),

			// wp-config.php Permissions
			'wp_config_perms_vulnerable'    => __( 'Set wp-config.php permissions to 644 or 600 for better security.', 'roko' ),
			'wp_config_perms_secure'        => __( 'wp-config.php has secure file permissions.', 'roko' ),

			// .htaccess Permissions
			'htaccess_perms_vulnerable'     => __( 'Set .htaccess permissions to 644 to prevent unauthorized modifications.', 'roko' ),
			'htaccess_perms_secure'         => __( '.htaccess has appropriate file permissions.', 'roko' ),

			// Backup Files
			'backup_files_vulnerable'       => __( 'Remove exposed backup files (.zip, .sql, .bak) from web root immediately.', 'roko' ),
			'backup_files_secure'           => __( 'No backup files found in web-accessible areas.', 'roko' ),

			// Log Files
			'log_files_vulnerable'          => __( 'Move or remove exposed log files from web root to prevent information disclosure.', 'roko' ),
			'log_files_secure'              => __( 'No log files exposed in web-accessible areas.', 'roko' ),
		);
	}
}
