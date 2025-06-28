<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\Entity;

use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\CoreChecksumMismatch;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\ExecutableInUploadsFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\DotFilesPresent;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\OversizedFilesFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\BackupFoldersFound;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\RecentFileChanges;
use JosephG\Roko\Domain\Security\FileIntegrity\ValueObject\MalwarePatternsFound;

/**
 * Entity representing a complete file integrity scan.
 */
final class IntegrityScan {

	public function __construct(
		public bool $coreIntact,
		public int $suspiciousCount,
		public \DateTimeImmutable $scannedAt,
		public CoreChecksumMismatch $coreChecksumMismatch,
		public ExecutableInUploadsFound $executableInUploadsFound,
		public DotFilesPresent $dotFilesPresent,
		public OversizedFilesFound $oversizedFilesFound,
		public BackupFoldersFound $backupFoldersFound,
		public RecentFileChanges $recentFileChanges,
		public MalwarePatternsFound $malwarePatternsFound,
	) {}

	/**
	 * Get comprehensive file integrity data as array.
	 *
	 * @return array
	 */
	public function toArray(): array {
		return array(
			'coreIntact'           => $this->coreIntact,
			'suspiciousCount'      => $this->suspiciousCount,
			'scannedAt'            => $this->scannedAt->format( \DateTimeInterface::ATOM ),
			'coreChecksumMismatch' => array(
				'data'        => $this->coreChecksumMismatch->getData(),
				'isAsync'     => $this->coreChecksumMismatch->isAsync(),
				'hasMismatch' => $this->coreChecksumMismatch->hasMismatches(),
				'description' => $this->coreChecksumMismatch->getDescription(),
			),
			'executableInUploads'  => array(
				'files'       => $this->executableInUploadsFound->getFiles(),
				'count'       => $this->executableInUploadsFound->getCount(),
				'hasIssue'    => $this->executableInUploadsFound->hasExecutables(),
				'description' => $this->executableInUploadsFound->getDescription(),
			),
			'dotFilesPresent'      => array(
				'files'       => $this->dotFilesPresent->getFiles(),
				'count'       => $this->dotFilesPresent->getCount(),
				'hasIssue'    => $this->dotFilesPresent->hasDotFiles(),
				'description' => $this->dotFilesPresent->getDescription(),
			),
			'oversizedFilesFound'  => array(
				'files'       => $this->oversizedFilesFound->getFiles(),
				'count'       => $this->oversizedFilesFound->getCount(),
				'hasIssue'    => $this->oversizedFilesFound->hasOversizedFiles(),
				'description' => $this->oversizedFilesFound->getDescription(),
			),
			'backupFoldersFound'   => array(
				'folders'     => $this->backupFoldersFound->getFolders(),
				'count'       => $this->backupFoldersFound->getCount(),
				'hasIssue'    => $this->backupFoldersFound->hasBackupFolders(),
				'description' => $this->backupFoldersFound->getDescription(),
			),
			'recentFileChanges'    => array(
				'files'       => $this->recentFileChanges->getFiles(),
				'count'       => $this->recentFileChanges->getCount(),
				'hasIssue'    => $this->recentFileChanges->hasRecentChanges(),
				'description' => $this->recentFileChanges->getDescription(),
			),
			'malwarePatternsFound' => array(
				'files'       => $this->malwarePatternsFound->getFiles(),
				'count'       => $this->malwarePatternsFound->getCount(),
				'hasIssue'    => $this->malwarePatternsFound->hasMalwarePatterns(),
				'description' => $this->malwarePatternsFound->getDescription(),
			),
		);
	}
}
