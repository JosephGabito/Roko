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

	/** @var bool */
	public $coreIntact;

	/** @var int */
	public $suspiciousCount;

	/** @var \DateTimeImmutable */
	public $scannedAt;

	/** @var CoreChecksumMismatch */
	public $coreChecksumMismatch;

	/** @var ExecutableInUploadsFound */
	public $executableInUploadsFound;

	/** @var DotFilesPresent */
	public $dotFilesPresent;

	/** @var OversizedFilesFound */
	public $oversizedFilesFound;

	/** @var BackupFoldersFound */
	public $backupFoldersFound;

	/** @var RecentFileChanges */
	public $recentFileChanges;

	/** @var MalwarePatternsFound */
	public $malwarePatternsFound;

	private $title       = '';
	private $description = '';

	public function __construct(
		$coreIntact,
		$suspiciousCount,
		\DateTimeImmutable $scannedAt,
		CoreChecksumMismatch $coreChecksumMismatch,
		ExecutableInUploadsFound $executableInUploadsFound,
		DotFilesPresent $dotFilesPresent,
		OversizedFilesFound $oversizedFilesFound,
		BackupFoldersFound $backupFoldersFound,
		RecentFileChanges $recentFileChanges,
		MalwarePatternsFound $malwarePatternsFound
	) {
		$this->coreIntact                = $coreIntact;
		$this->suspiciousCount           = $suspiciousCount;
		$this->scannedAt                 = $scannedAt;
		$this->coreChecksumMismatch      = $coreChecksumMismatch;
		$this->executableInUploadsFound  = $executableInUploadsFound;
		$this->dotFilesPresent           = $dotFilesPresent;
		$this->oversizedFilesFound       = $oversizedFilesFound;
		$this->backupFoldersFound        = $backupFoldersFound;
		$this->recentFileChanges         = $recentFileChanges;
		$this->malwarePatternsFound      = $malwarePatternsFound;
	}

	public function setSectionSummary( $title, $description ) {
		$this->title       = $title;
		$this->description = $description;
	}

	public function getSectionSummary() {
		return array(
			'title'       => $this->title,
			'description' => $this->description,
		);
	}

	/**
	 * Get comprehensive file integrity data as array.
	 *
	 * @return array
	 */
	public function toArray() {
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
