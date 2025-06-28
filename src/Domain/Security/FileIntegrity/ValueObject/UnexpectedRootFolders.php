<?php
/**
 * Value Object representing unexpected folders found at the WordPress root.
 */
final class UnexpectedRootFolders {

	/**
	 * @var string[] List of unexpected folder names
	 */
	private array $folders;

	/**
	 * @var string Human-readable description of this check
	 */
	private string $description = '';

	/**
	 * @param string[] $folders Array of folder names that don’t belong in the root
	 */
	public function __construct( array $folders ) {
		$this->folders = $folders;
	}

	/**
	 * Get the list of unexpected folders.
	 *
	 * @return string[]
	 */
	public function getFolders(): array {
		return $this->folders;
	}

	/**
	 * Whether any unexpected folders were found.
	 *
	 * @return bool
	 */
	public function hasFolders(): bool {
		return ! empty( $this->folders );
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
