<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object for executable files found in uploads directory.
 * Joe says: "Your media folder is for pics and docs. If we find scripts there, they're begging to run."
 */
final class ExecutableInUploadsFound {

	/**
	 * @var array List of executable files found
	 */
	private array $files;

	/**
	 * @var string Human-readable description
	 */
	private string $description = '';

	/**
	 * @param array $files List of found executable files
	 */
	public function __construct( array $files ) {
		$this->files = $files;
	}

	/**
	 * Get the list of executable files found.
	 *
	 * @return array
	 */
	public function getFiles(): array {
		return $this->files;
	}

	/**
	 * Whether any executable files were found.
	 *
	 * @return bool
	 */
	public function hasExecutables(): bool {
		return ! empty( $this->files );
	}

	/**
	 * Get count of executable files found.
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
