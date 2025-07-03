<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use JosephG\Roko\Domain\Security\FileIntegrity\Entity\IntegrityScan;
use JosephG\Roko\Domain\Security\FileIntegrity\Repository\FileIntegrityRepositoryInterface;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\CoreChecksumMismatch;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\ExecutableInUploadsFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\DotFilesPresent;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\OversizedFilesFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\BackupFoldersFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\RecentFileChanges;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\MalwarePatternsFound;

/**
 * WordPress implementation of file integrity repository.
 * Currently returns static demo data as per requirements.
 */
final class WpFileIntegrityRepository implements FileIntegrityRepositoryInterface {

	public function latestScan(): IntegrityScan {

		// Core checksum - async pattern as requested
		$coreChecksumMismatch = new CoreChecksumMismatch( array( 'async' => home_url() ) );
		$coreChecksumMismatch->setDescription( 'Verify core files match WordPress.org checksums. Any modifications could indicate malware injection.' );

		// Executables in uploads - static demo data
		$executableInUploads = new ExecutableInUploadsFound( array() ); // Clean for demo
		$executableInUploads->setDescription( 'Your media folder is for pics and docs. If we find scripts there, they\'re begging to run.' );

		// Dot files - static demo with some findings
		$dotFiles = new DotFilesPresent(
			array(
				array(
					'file' => '.DS_Store',
					'path' => '/wp-content/uploads/.DS_Store',
					'size' => '6KB',
				),
				array(
					'file' => '.htaccess_backup',
					'path' => '/wp-content/.htaccess_backup',
					'size' => '2KB',
				),
			)
		);
		$dotFiles->setDescription( 'Hidden files often leak passwords or reveal your repo history.' );

		// Oversized files - static demo
		$oversizedFiles = new OversizedFilesFound(
			array(
				array(
					'file' => 'debug.log',
					'path' => '/wp-content/debug.log',
					'size' => '87MB',
				),
			)
		);
		$oversizedFiles->setDescription( 'Huge files blow out your backups and might be data exfiltration.' );

		// Backup folders - static demo
		$backupFolders = new BackupFoldersFound( array() ); // Clean for demo
		$backupFolders->setDescription( 'Those plugin backups belong in secure storage, not your web root.' );

		// Recent file changes - static demo
		$recentFileChanges = new RecentFileChanges(
			array(
				array(
					'file'     => 'wp-config.php',
					'modified' => '2 minutes ago',
					'change'   => 'Modified',
				),
			)
		);
		$recentFileChanges->setDescription( 'A file dropped 5 minutes ago—did you put that there?' );

		// Malware patterns - static demo (clean for demo)
		$malwarePatterns = new MalwarePatternsFound( array() ); // Clean for demo
		$malwarePatterns->setDescription( 'These code snippets are classic malware fingerprints.' );

		$scan = new IntegrityScan(
			true, // coreIntact: Legacy field - will be replaced by detailed checks
			3, // suspiciousCount: Count from detailed findings
			new \DateTimeImmutable(),
			$coreChecksumMismatch,
			$executableInUploads,
			$dotFiles,
			$oversizedFiles,
			$backupFolders,
			$recentFileChanges,
			$malwarePatterns
		);

		$scan->setSectionSummary(
			__( 'File Integrity', 'roko' ),
			__( 'We scan your WordPress core files, uploads directory, and system files for unauthorized changes, malware patterns, and suspicious activity that could indicate compromise.', 'roko' )
		);

		return $scan;
	}
}
