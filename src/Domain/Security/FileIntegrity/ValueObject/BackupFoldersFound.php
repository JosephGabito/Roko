<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object for backup folders found in web root.
 * Joe says: "Those plugin backups belong in secure storage, not your web root."
 */
final class BackupFoldersFound {

	/**
	 * @var array List of backup folders found
	 */
	private array $folders;

	/**
	 * @var string Human-readable description
	 */
	private string $description = '';

	/**
	 * @param array $folders List of backup folders found
	 */
	public function __construct( array $folders ) {
		$this->folders = $folders;
	}

	/**
	 * Get the list of backup folders found.
	 *
	 * @return array
	 */
	public function getFolders(): array {
		return $this->folders;
	}

	/**
	 * Whether any backup folders were found.
	 *
	 * @return bool
	 */
	public function hasBackupFolders(): bool {
		return ! empty( $this->folders );
	}

	/**
	 * Get count of backup folders found.
	 *
	 * @return int
	 */
	public function getCount(): int {
		return count( $this->folders );
	}

	/**
	 * Set the description for this check.
	 *
	 * @param string $description
	 */
	public function setDescription( string $description ): void {
		$this->description = $description;
	}

	/**
	 * Get the description for this check.
	 *
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
}
