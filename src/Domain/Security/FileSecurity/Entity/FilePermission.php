<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Entity;

use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDirectoryListingOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpDebugOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsEditorOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDashboardInstallsOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsPHPExecutionInUploadsDirOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsXMLRPCOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpConfigPermission644;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\AnyBackupExposed;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\LogFilesExposed;

final class FilePermission {

	private $directoryListingIsOn;
	private $wpDebugOn;
	private $editorOn;
	private $dashboardInstallsOn;
	private $phpExecutionInUploadsDirOn;
	private $xmlrpcOn;
	private $wpConfigPermission644;
	private $anyBackupExposed;
	private $logFilesExposed;

	private $title;
	private $description;

	public function __construct(
		IsDirectoryListingOn $directoryListingIsOn,
		IsWpDebugOn $wpDebugOn,
		IsEditorOn $editorOn,
		IsDashboardInstallsOn $dashboardInstallsOn,
		IsPHPExecutionInUploadsDirOn $phpExecutionInUploadsDirOn,
		IsXMLRPCOn $xmlrpcOn,
		IsWpConfigPermission644 $wpConfigPermission644,
		AnyBackupExposed $anyBackupExposed,
		LogFilesExposed $logFilesExposed
	) {
		$this->directoryListingIsOn       = $directoryListingIsOn;
		$this->wpDebugOn                  = $wpDebugOn;
		$this->editorOn                   = $editorOn;
		$this->dashboardInstallsOn        = $dashboardInstallsOn;
		$this->phpExecutionInUploadsDirOn = $phpExecutionInUploadsDirOn;
		$this->xmlrpcOn                   = $xmlrpcOn;
		$this->wpConfigPermission644      = $wpConfigPermission644;
		$this->anyBackupExposed           = $anyBackupExposed;
		$this->logFilesExposed            = $logFilesExposed;
	}

	public function setSectionSummary( $title, $description ) {
		$this->title       = $title;
		$this->description = $description;
	}

	public function getSectionSummary(): array {
		return array(
			'title'       => $this->title,
			'description' => $this->description,
		);
	}

	/**
	 * Exposes the file permission as an array.
	 *
	 * @return array{
	 *  directoryListingIsOn: IsDirectoryListingOn,
	 *  wpDebugOn: IsWpDebugOn,
	 *  editorOn: IsEditorOn,
	 *  dashboardInstallsOn: IsDashboardInstallsOn,
	 *  phpExecutionInUploadsDirOn: IsPHPExecutionInUploadsDirOn,
	 *  xmlrpcOn: IsXMLRPCOn,
	 *  wpConfigPermission644: IsWpConfigPermission644,
	 *  anyBackupExposed: AnyBackupExposed,
	 *  logFilesExposed: LogFilesExposed,
	 * }
	 */
	public function toArray(): array {
		return array(
			'directoryListingIsOn'       => $this->directoryListingIsOn,
			'wpDebugOn'                  => $this->wpDebugOn,
			'editorOn'                   => $this->editorOn,
			'dashboardInstallsOn'        => $this->dashboardInstallsOn,
			'phpExecutionInUploadsDirOn' => $this->phpExecutionInUploadsDirOn,
			'xmlrpcOn'                   => $this->xmlrpcOn,
			'wpConfigPermission644'      => $this->wpConfigPermission644,
			'anyBackupExposed'           => $this->anyBackupExposed,
			'logFilesExposed'            => $this->logFilesExposed,
		);
	}
}
