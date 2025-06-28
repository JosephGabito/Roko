<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object for recent file changes detected.
 * Joe says: "A file dropped 5 minutes ago—did you put that there?"
 */
final class RecentFileChanges {

	/**
	 * @var array List of recently changed files
	 */
	private array $files;

	/**
	 * @var string Human-readable description
	 */
	private string $description = '';

	/**
	 * @param array $files List of recently changed files with timestamps
	 */
	public function __construct( array $files ) {
		$this->files = $files;
	}

	/**
	 * Get the list of recently changed files.
	 *
	 * @return array
	 */
	public function getFiles(): array {
		return $this->files;
	}

	/**
	 * Whether any recent changes were detected.
	 *
	 * @return bool
	 */
	public function hasRecentChanges(): bool {
		return ! empty( $this->files );
	}

	/**
	 * Get count of recently changed files.
	 *
	 * @return int
	 */
	public function getCount(): int {
		return count( $this->files );
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
