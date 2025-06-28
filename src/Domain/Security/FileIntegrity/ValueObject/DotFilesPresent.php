<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object for dot files found in site directories.
 * Joe says: "Hidden files often leak passwords or reveal your repo history."
 */
final class DotFilesPresent {

	/**
	 * @var array List of dot files found
	 */
	private array $files;

	/**
	 * @var string Human-readable description
	 */
	private string $description = '';

	/**
	 * @param array $files List of found dot files
	 */
	public function __construct( array $files ) {
		$this->files = $files;
	}

	/**
	 * Get the list of dot files found.
	 *
	 * @return array
	 */
	public function getFiles(): array {
		return $this->files;
	}

	/**
	 * Whether any dot files were found.
	 *
	 * @return bool
	 */
	public function hasDotFiles(): bool {
		return ! empty( $this->files );
	}

	/**
	 * Get count of dot files found.
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
