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

final class WpFileSecurityProvider implements FilePermissionInterface {

	private function isWpDebugOn(): bool {
		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}

	private function isEditorOn(): bool {
		return defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT;
	}

	private function isDashboardInstallsOn(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function isPHPExecutionInUploadsDirOn(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function isDirectoryListingOn(): bool {
		return is_dir( ABSPATH ) && ! is_link( ABSPATH );
	}

	private function doesSensitiveFilesExists(): bool {
		return file_exists( ABSPATH . 'wp-config.php' ) || file_exists( ABSPATH . '.htaccess' );
	}

	private function isXMLRPCOn(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function isWpConfigPermission644(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function isHtAccessPermission644(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function anyBackupExposed(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	private function logFilesExposed(): bool {
		return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
	}

	public function snapshot(): FilePermission {

		$directoryListingIsOff = new IsDirectoryListingOn( $this->isDirectoryListingOn() );

		$wpDebugOn                  = new IsWpDebugOn( $this->isWpDebugOn() );
		$editorOn                   = new IsEditorOn( $this->isEditorOn() );
		$dashboardInstallsOn        = new IsDashboardInstallsOn( $this->isDashboardInstallsOn() );
		$phpExecutionInUploadsDirOn = new IsPHPExecutionInUploadsDirOn( $this->isPHPExecutionInUploadsDirOn() );
		$doesSensitiveFilesExists   = new DoesSensitiveFilesExists( $this->doesSensitiveFilesExists() );
		$xmlrpcOn                   = new IsXMLRPCOn( $this->isXMLRPCOn() );
		$wpConfigPermission644      = new IsWpConfigPermission644( $this->isWpConfigPermission644() );
		$htAccessPermission644      = new IsHtAccessPermission644( $this->isHtAccessPermission644() );
		$anyBackupExposed           = new AnyBackupExposed( $this->anyBackupExposed() );
		$logFilesExposed            = new LogFilesExposed( $this->logFilesExposed() );

		// In each Value Object class, add the proper descriptions:
		$directoryListingIsOff->setDescription(
			__(
				'Your server shows folder contents to visitors, making it easy for hackers to find sensitive files.',
				'roko'
			)
		);

		$wpDebugOn->setDescription(
			__(
				'Debug mode is enabled, which can expose sensitive information about your site to attackers.',
				'roko'
			)
		);

		$editorOn->setDescription(
			__(
				'WordPress file editor is enabled, allowing hackers to edit your files through the dashboard.',
				'roko'
			)
		);

		$dashboardInstallsOn->setDescription(
			__(
				'Plugin/theme installation from dashboard is enabled, allowing unauthorized software installs.',
				'roko'
			)
		);

		$phpExecutionInUploadsDirOn->setDescription(
			__(
				'PHP execution is allowed in uploads folder, letting hackers run malicious code.',
				'roko'
			)
		);

		$doesSensitiveFilesExists->setDescription(
			__(
				'Configuration files like wp-config.php are exposed, giving hackers access to your database passwords.',
				'roko'
			)
		);

		$xmlrpcOn->setDescription(
			__(
				'XML-RPC is enabled and vulnerable to brute force attacks and unauthorized remote access.',
				'roko'
			)
		);

		$wpConfigPermission644->setDescription(
			__(
				'Your wp-config.php file has wrong permissions, potentially exposing your database credentials.',
				'roko'
			)
		);

		$htAccessPermission644->setDescription(
			__(
				'Your .htaccess file has wrong permissions, allowing hackers to modify your site\'s security rules.',
				'roko'
			)
		);

		$anyBackupExposed->setDescription(
			__(
				'Your backup files are publicly accessible and can be downloaded by hackers.',
				'roko'
			)
		);

		$logFilesExposed->setDescription(
			__(
				'Your error logs are publicly accessible and can reveal sensitive information to hackers.',
				'roko'
			)
		);

		$filePermission = new FilePermission(
			$directoryListingIsOff,
			$wpDebugOn,
			$editorOn,
			$dashboardInstallsOn,
			$phpExecutionInUploadsDirOn,
			$doesSensitiveFilesExists,
			$xmlrpcOn,
			$wpConfigPermission644,
			$htAccessPermission644,
			$anyBackupExposed,
			$logFilesExposed
		);

		$filePermission->setSectionSummary(
			__( 'File & System Protection', 'roko' ),
			__( 'Your server has security settings that act like locks on your doors. When these are misconfigured, hackers can steal your data, break your site, or plant malicious code. These checks make sure your digital doors are properly locked.', 'roko' )
		);

		return $filePermission;
	}
}
