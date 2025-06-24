<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Entity;

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

final readonly class FilePermission {

	private IsDirectoryListingOn $directoryListingIsOn;
	private IsWpDebugOn $wpDebugOn;
	private IsEditorOn $editorOn;
	private IsDashboardInstallsOn $dashboardInstallsOn;
	private IsPHPExecutionInUploadsDirOn $phpExecutionInUploadsDirOn;
	private DoesSensitiveFilesExists $doesSensitiveFilesExists;
	private IsXMLRPCOn $xmlrpcOn;
	private IsWpConfigPermission644 $wpConfigPermission644;
	private IsHtAccessPermission644 $htAccessPermission644;
	private AnyBackupExposed $anyBackupExposed;
	private LogFilesExposed $logFilesExposed;

	public function __construct(
		IsDirectoryListingOn $directoryListingIsOn,
		IsWpDebugOn $wpDebugOn,
		IsEditorOn $editorOn,
		IsDashboardInstallsOn $dashboardInstallsOn,
		IsPHPExecutionInUploadsDirOn $phpExecutionInUploadsDirOn,
		DoesSensitiveFilesExists $doesSensitiveFilesExists,
		IsXMLRPCOn $xmlrpcOn,
		IsWpConfigPermission644 $wpConfigPermission644,
		IsHtAccessPermission644 $htAccessPermission644,
		AnyBackupExposed $anyBackupExposed,
		LogFilesExposed $logFilesExposed
	) {
		$this->directoryListingIsOn = $directoryListingIsOn;
		$this->wpDebugOn = $wpDebugOn;
		$this->editorOn = $editorOn;
		$this->dashboardInstallsOn = $dashboardInstallsOn;
		$this->phpExecutionInUploadsDirOn = $phpExecutionInUploadsDirOn;
		$this->doesSensitiveFilesExists = $doesSensitiveFilesExists;
		$this->xmlrpcOn = $xmlrpcOn;
		$this->wpConfigPermission644 = $wpConfigPermission644;
		$this->htAccessPermission644 = $htAccessPermission644;
		$this->anyBackupExposed = $anyBackupExposed;
		$this->logFilesExposed = $logFilesExposed;
	}

	public function toArray(): array {
		return [
			'directoryListingIsOn' => $this->directoryListingIsOn,
			'wpDebugOn' => $this->wpDebugOn,
			'editorOn' => $this->editorOn,
			'dashboardInstallsOn' => $this->dashboardInstallsOn,
			'phpExecutionInUploadsDirOn' => $this->phpExecutionInUploadsDirOn,
			'doesSensitiveFilesExists' => $this->doesSensitiveFilesExists,
			'xmlrpcOn' => $this->xmlrpcOn,
			'wpConfigPermission644' => $this->wpConfigPermission644,
			'htAccessPermission644' => $this->htAccessPermission644,
			'anyBackupExposed' => $this->anyBackupExposed,
			'logFilesExposed' => $this->logFilesExposed,
		];
	}
}
