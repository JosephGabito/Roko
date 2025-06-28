<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object representing unexpected files found at the WordPress root.
 */
final class UnexpectedRootFiles {

	/**
	 * @var string[] List of unexpected file names
	 */
	private array $files;

	/**
	 * @var string Human-readable description of this check
	 */
	private string $description = '';

	/**
	 * @param string[] $files Array of file names that don’t belong in the root
	 */
	public function __construct( array $files ) {
		$this->files = $files;
	}

	/**
	 * Get the list of unexpected files.
	 *
	 * @return string[]
	 */
	public function getFiles(): array {
		return $this->files;
	}

	/**
	 * Whether any unexpected files were found.
	 *
	 * @return bool
	 */
	public function hasFiles(): bool {
		return ! empty( $this->files );
	}

	/**
	 * Set the description for this check, used in UI feedback.
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
