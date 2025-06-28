<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermission;
use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermissionInterface;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDirectoryListingOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpDebugOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsEditorOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDashboardInstallsOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsPHPExecutionInUploadsDirOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\DoesSensitiveFilesExists;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsXMLRPCOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpConfigPermission644;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsHtAccessPermission644;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\AnyBackupExposed;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\LogFilesExposed;

/**
 * WpFileSecurityProvider
 *
 * Inspects WordPress file and directory settings to detect insecure
 * configurations (directory listings, debug mode, file editor, upload execution, etc.).
 */
final class WpFileSecurityProvider implements FilePermissionInterface {

	/**
	 * Read .htaccess contents or return empty string if missing.
	 *
	 * @param string $path Full path to the file.
	 * @return string
	 */
	private function getHtaccessContents( string $path ): string {
		return file_exists( $path ) ? (string) file_get_contents( $path ) : '';
	}

	/**
	 * Check if a file exists and has exact permissions.
	 *
	 * @param string $file Full path to the file.
	 * @param int    $mode Expected permission mode (octal).
	 * @return bool
	 */
	private function hasFilePermission( string $file, int $mode ): bool {
		return file_exists( $file )
			&& ( ( fileperms( $file ) & 0x1FF ) === $mode );
	}

	/**
	 * Detect if .htaccess is enabled at the WordPress root.
	 *
	 * @return bool True if .htaccess file exists at ABSPATH.
	 */
	private function isHtAccessEnabled(): bool {
		return file_exists( ABSPATH . '.htaccess' );
	}

	/**
	 * Determine if WP_DEBUG is enabled.
	 *
	 * @return bool True if debug mode is on.
	 */
	private function isWpDebugOn(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG === true;
	}

	/**
	 * Check if the built-in file editor is available.
	 *
	 * @return bool True if DISALLOW_FILE_EDIT is not true.
	 */
	private function isEditorOn(): bool {
		return ! ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT === true );
	}

	/**
	 * Check if dashboard installs and updates are allowed.
	 *
	 * @return bool True if DISALLOW_FILE_MODS is not true.
	 */
	private function isDashboardInstallsOn(): bool {
		return ! ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS === true );
	}

	/**
	 * Detect if PHP execution is permitted inside the uploads directory.
	 *
	 * Uses .htaccess rules when enabled. If .htaccess isn't used, defaults
	 * to assuming execution is allowed.
	 *
	 * @return bool True if PHP execution is allowed or cannot be verified.
	 */
	private function isPHPExecutionInUploadsDirOn(): bool {
		$dir    = wp_upload_dir()['basedir'] ?? '';
		$htFile = $dir . '/.htaccess';

		if ( $this->isHtAccessEnabled() ) {
			$rules = $this->getHtaccessContents( $htFile );
			return stripos( $rules, 'php_flag engine off' ) === false
				&& stripos( $rules, '<FilesMatch' ) === false;
		}

		// No .htaccess support => execution likely allowed
		return true;
	}

	/**
	 * Determine if directory listing is exposed at site root.
	 *
	 * @return bool True if directory listings are allowed.
	 */
	private function isDirectoryListingOn(): bool {
		$rootHt   = ABSPATH . '.htaccess';
		$contents = $this->getHtaccessContents( $rootHt );
		return stripos( $contents, 'Options -Indexes' ) === false;
	}

	/**
	 * Check for existence of sensitive core files in web root.
	 *
	 * @return bool True if wp-config.php or .htaccess exist.
	 */
	private function doesSensitiveFilesExists(): bool {
		return file_exists( ABSPATH . 'wp-config.php' )
			|| file_exists( ABSPATH . '.htaccess' );
	}

	/**
	 * Detect if XML-RPC is enabled and present.
	 *
	 * @return bool True if xmlrpc.php exists and filter returns true.
	 */
	private function isXMLRPCOn(): bool {
		return file_exists( ABSPATH . 'xmlrpc.php' )
			&& apply_filters( 'xmlrpc_enabled', true ) === true;
	}

	/**
	 * Verify wp-config.php file permissions are 0644.
	 *
	 * @return bool True if permissions are exactly 0644.
	 */
	private function isWpConfigPermission644(): bool {
		return $this->hasFilePermission( ABSPATH . 'wp-config.php', 0644 );
	}

	/**
	 * Verify .htaccess file permissions are 0644.
	 *
	 * @return bool True if permissions are exactly 0644.
	 */
	private function isHtAccessPermission644(): bool {
		return $this->hasFilePermission( ABSPATH . '.htaccess', 0644 );
	}

	/**
	 * Scan for exposed backup files (zip, tar.gz, sql, bak, old).
	 *
	 * @return bool True if any backups found.
	 */
	private function anyBackupExposed(): bool {
		$patterns = array( '*.zip', '*.tar.gz', '*.sql', '*.bak', '*.old' );
		foreach ( $patterns as $pat ) {
			if ( glob( ABSPATH . $pat, GLOB_BRACE ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Scan for exposed log files at site root.
	 *
	 * @return bool True if any .log files exist.
	 */
	private function logFilesExposed(): bool {
		return (bool) glob( ABSPATH . '*.log', GLOB_BRACE );
	}

	/**
	 * Build a FilePermission snapshot with all checks and descriptions.
	 *
	 * @return FilePermission
	 */
	public function snapshot(): FilePermission {
		$directoryListing = new IsDirectoryListingOn( $this->isDirectoryListingOn() );
		$directoryListing->setDescription(
			__( 'Your server allows directory listings—hackers can browse your folders for juicy files.', 'roko' )
		);

		$wpDebug = new IsWpDebugOn( $this->isWpDebugOn() );
		$wpDebug->setDescription(
			__( 'WP_DEBUG is on, potentially exposing SQL queries, filesystem paths, and credentials.', 'roko' )
		);

		$editor = new IsEditorOn( $this->isEditorOn() );
		$editor->setDescription(
			__( 'The built-in file editor is enabled, letting anyone with dashboard access edit your PHP files.', 'roko' )
		);

		$dashboardInstalls = new IsDashboardInstallsOn( $this->isDashboardInstallsOn() );
		$dashboardInstalls->setDescription(
			__( 'Dashboard installs are enabled, opening a door to unauthorized plugin/theme uploads.', 'roko' )
		);

		$phpExecUploads = new IsPHPExecutionInUploadsDirOn( $this->isPHPExecutionInUploadsDirOn() );
		$phpExecUploads->setDescription(
			__( 'Uploads folder allows PHP execution—malicious scripts can run if uploaded.', 'roko' )
		);

		$sensitiveFiles = new DoesSensitiveFilesExists( $this->doesSensitiveFilesExists() );
		$sensitiveFiles->setDescription(
			__( 'Core files like wp-config.php or .htaccess exist at web-root without extra protection.', 'roko' )
		);

		$xmlrpc = new IsXMLRPCOn( $this->isXMLRPCOn() );
		$xmlrpc->setDescription(
			__( 'XML-RPC is enabled, which is often abused for brute-force and DDoS attacks.', 'roko' )
		);

		$wpConfigPerm = new IsWpConfigPermission644( $this->isWpConfigPermission644() );
		$wpConfigPerm->setDescription(
			__( 'wp-config.php permissions are misconfigured; should be 0644 to lock down credentials.', 'roko' )
		);

		$htAccessPerm = new IsHtAccessPermission644( $this->isHtAccessPermission644() );
		$htAccessPerm->setDescription(
			__( 'Your .htaccess file isn\'t locked to 0644, so attackers might modify rewrite/security rules.', 'roko' )
		);

		$backupExposed = new AnyBackupExposed( $this->anyBackupExposed() );
		$backupExposed->setDescription(
			__( 'Backup files (zip/sql/etc.) are publicly searchable and downloadable.', 'roko' )
		);

		$logsExposed = new LogFilesExposed( $this->logFilesExposed() );
		$logsExposed->setDescription(
			__( 'Log files are in web-root and can reveal error messages or stack traces.', 'roko' )
		);

		$filePermission = new FilePermission(
			$directoryListing,
			$wpDebug,
			$editor,
			$dashboardInstalls,
			$phpExecUploads,
			$sensitiveFiles,
			$xmlrpc,
			$wpConfigPerm,
			$htAccessPerm,
			$backupExposed,
			$logsExposed
		);

		$filePermission->setSectionSummary(
			__( 'File & System Protection', 'roko' ),
			__( 'These checks verify your WP root and uploads directory are properly locked down—no public indexing, no exposed configs or logs, and no unwanted execution.', 'roko' )
		);

		return $filePermission;
	}
}
