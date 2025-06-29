<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * FileSecurity strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class FileSecurityI18n {

	public static function description( string $key ): string {
		return self::stringsCollection()[ $key ];
	}

	public static function stringsCollection(): array {
		return array(
			'directoryListing'  => __( 'Visitors can see all your site folders and files—bad actors might find secrets.', 'roko' ),
			'wpDebug'           => __( 'Your site is showing technical details and errors that could help hackers.', 'roko' ),
			'editor'            => __( 'Anyone with dashboard access could edit your site code directly.', 'roko' ),
			'dashboardInstalls' => __( 'Logged-in users can install or update plugins and themes without your permission.', 'roko' ),
			'phpExecUploads'    => __( 'If someone uploads a PHP file, it may run and harm your site.', 'roko' ),
			'sensitiveFiles'    => __( 'Important files like wp-config.php or .htaccess are sitting where anyone can access them.', 'roko' ),
			'xmlrpc'            => __( 'An old API (XML-RPC) is active—hackers use it to guess passwords and overload sites.', 'roko' ),
			'wpConfigPerm'      => __( 'Your main settings file is not locked down properly; info could leak.', 'roko' ),
			'htAccessPerm'      => __( 'Your security rules file can be changed by others—protections might be turned off.', 'roko' ),
			'backupExposed'     => __( 'Backup copies of your site are out in the open for anyone to download.', 'roko' ),
			'logsExposed'       => __( 'Your site error logs are exposed and could show private details.', 'roko' ),
		);
	}
}
