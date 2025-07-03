<?php
namespace JosephG\Roko\Domain\Security\Checks;

use JosephG\Roko\Domain\Security\FileIntegrity\Entity\IntegrityScan;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Check;
use JosephG\Roko\Domain\Security\Checks\ValueObject\CheckStatus;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Severity;

/**
 * Domain Service: Transforms IntegrityScan entity into security checks.
 *
 * Emits business codes for recommendations - Application layer handles translation.
 */
final class FileIntegrityChecks {

	/** @var Check[] */
	private $checks;

	public function __construct( array $checks ) {
		$this->checks = $checks;
	}

	/**
	 * Create FileIntegrityChecks from IntegrityScan domain entity.
	 *
	 * @param IntegrityScan $integrityScan Domain entity with file integrity checks.
	 */
	public static function fromIntegrityScan( IntegrityScan $integrityScan ) {
		$checks = array();

		// Core Checksum Check
		$coreCheck = self::createCoreChecksumCheck( $integrityScan->coreChecksumMismatch );
		if ( $coreCheck ) {
			$checks[] = $coreCheck;
		}

		// Executable Files in Uploads Check
		$executableCheck = self::createExecutableUploadsCheck( $integrityScan->executableInUploadsFound );
		if ( $executableCheck ) {
			$checks[] = $executableCheck;
		}

		// Dot Files Check
		$dotFilesCheck = self::createDotFilesCheck( $integrityScan->dotFilesPresent );
		if ( $dotFilesCheck ) {
			$checks[] = $dotFilesCheck;
		}

		// Oversized Files Check
		$oversizedCheck = self::createOversizedFilesCheck( $integrityScan->oversizedFilesFound );
		if ( $oversizedCheck ) {
			$checks[] = $oversizedCheck;
		}

		// Backup Folders Check
		$backupCheck = self::createBackupFoldersCheck( $integrityScan->backupFoldersFound );
		if ( $backupCheck ) {
			$checks[] = $backupCheck;
		}

		// Recent File Changes Check
		$recentChangesCheck = self::createRecentChangesCheck( $integrityScan->recentFileChanges );
		if ( $recentChangesCheck ) {
			$checks[] = $recentChangesCheck;
		}

		// Malware Patterns Check
		$malwareCheck = self::createMalwarePatternsCheck( $integrityScan->malwarePatternsFound );
		if ( $malwareCheck ) {
			$checks[] = $malwareCheck;
		}

		return new self( $checks );
	}

	/**
	 * Create core checksum check from value object.
	 */
	private static function createCoreChecksumCheck( $coreChecksum ) {
		$hasIssues = $coreChecksum->hasMismatches();
		$isAsync   = $coreChecksum->isAsync();

		// Skip if async (we can't determine status yet)
		if ( $isAsync ) {
			return null;
		}

		$businessCode = $hasIssues ? 'core_checksum_mismatch' : 'core_checksum_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::high() : Severity::low();

		return new Check(
			'core_checksum',
			'Core Files Integrity',
			$status,
			$severity,
			$coreChecksum->getDescription(),
			array(
				'has_mismatches' => $hasIssues,
				'is_async'       => $isAsync,
				'data'           => $coreChecksum->getData(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create executable uploads check from value object.
	 */
	private static function createExecutableUploadsCheck( $executableUploads ) {
		$hasIssues    = $executableUploads->hasExecutables();
		$businessCode = $hasIssues ? 'executable_uploads_found' : 'executable_uploads_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::high() : Severity::low();

		return new Check(
			'executable_uploads',
			'Scripts in Uploads Directory',
			$status,
			$severity,
			$executableUploads->getDescription(),
			array(
				'has_executables' => $hasIssues,
				'count'           => $executableUploads->getCount(),
				'files'           => $executableUploads->getFiles(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create dot files check from value object.
	 */
	private static function createDotFilesCheck( $dotFiles ) {
		$hasIssues    = $dotFiles->hasDotFiles();
		$businessCode = $hasIssues ? 'dot_files_found' : 'dot_files_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::medium() : Severity::low();

		return new Check(
			'dot_files',
			'Hidden Files Present',
			$status,
			$severity,
			$dotFiles->getDescription(),
			array(
				'has_dot_files' => $hasIssues,
				'count'         => $dotFiles->getCount(),
				'files'         => $dotFiles->getFiles(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create oversized files check from value object.
	 */
	private static function createOversizedFilesCheck( $oversizedFiles ) {
		$hasIssues    = $oversizedFiles->hasOversizedFiles();
		$businessCode = $hasIssues ? 'oversized_files_found' : 'oversized_files_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::medium() : Severity::low();

		return new Check(
			'oversized_files',
			'Large Files Detected',
			$status,
			$severity,
			$oversizedFiles->getDescription(),
			array(
				'has_oversized' => $hasIssues,
				'count'         => $oversizedFiles->getCount(),
				'files'         => $oversizedFiles->getFiles(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create backup folders check from value object.
	 */
	private static function createBackupFoldersCheck( $backupFolders ) {
		$hasIssues    = $backupFolders->hasBackupFolders();
		$businessCode = $hasIssues ? 'backup_folders_found' : 'backup_folders_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::medium() : Severity::low();

		return new Check(
			'backup_folders',
			'Backup Folders Exposed',
			$status,
			$severity,
			$backupFolders->getDescription(),
			array(
				'has_backups' => $hasIssues,
				'count'       => $backupFolders->getCount(),
				'folders'     => $backupFolders->getFolders(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create recent changes check from value object.
	 */
	private static function createRecentChangesCheck( $recentChanges ) {
		$hasIssues    = $recentChanges->hasRecentChanges();
		$businessCode = $hasIssues ? 'recent_changes_detected' : 'recent_changes_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::medium() : Severity::low();

		return new Check(
			'recent_changes',
			'Recent File Modifications',
			$status,
			$severity,
			$recentChanges->getDescription(),
			array(
				'has_recent_changes' => $hasIssues,
				'count'              => $recentChanges->getCount(),
				'files'              => $recentChanges->getFiles(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Create malware patterns check from value object.
	 */
	private static function createMalwarePatternsCheck( $malwarePatterns ) {
		$hasIssues    = $malwarePatterns->hasMalwarePatterns();
		$businessCode = $hasIssues ? 'malware_patterns_found' : 'malware_patterns_clean';
		$status       = $hasIssues ? CheckStatus::fail() : CheckStatus::pass();
		$severity     = $hasIssues ? Severity::high() : Severity::low();

		return new Check(
			'malware_patterns',
			'Malware Signatures',
			$status,
			$severity,
			$malwarePatterns->getDescription(),
			array(
				'has_malware' => $hasIssues,
				'count'       => $malwarePatterns->getCount(),
				'files'       => $malwarePatterns->getFiles(),
			),
			$businessCode,
			'roko'
		);
	}

	/**
	 * Convert checks to array format.
	 */
	public function toArray() {
		return array_map(
			function ( Check $check ) {
				return $check->toArray();
			},
			$this->checks
		);
	}

	/**
	 * Get all Check objects.
	 *
	 * @return Check[]
	 */
	public function getChecks(): array {
		return $this->checks;
	}
}
