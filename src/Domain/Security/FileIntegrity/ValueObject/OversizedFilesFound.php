<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object for oversized files found in site directories.
 * Joe says: "Huge files blow out your backups and might be data exfiltration."
 */
final class OversizedFilesFound {

	/**
	 * @var array List of oversized files found
	 */
	private array $files;

	/**
	 * @var string Human-readable description
	 */
	private string $description = '';

	/**
	 * @param array $files List of oversized files with sizes
	 */
	public function __construct( array $files ) {
		$this->files = $files;
	}

	/**
	 * Get the list of oversized files found.
	 *
	 * @return array
	 */
	public function getFiles(): array {
		return $this->files;
	}

	/**
	 * Whether any oversized files were found.
	 *
	 * @return bool
	 */
	public function hasOversizedFiles(): bool {
		return ! empty( $this->files );
	}

	/**
	 * Get count of oversized files found.
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
