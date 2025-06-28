<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

/**
 * Value Object representing core file checksum mismatches.
 * Joe says: "Your core files should exactly match WordPress's originals.
 * Any tiny drift—extra code, removed lines, even whitespace—could be a backdoor."
 */
final class CoreChecksumMismatch {

	/**
	 * @var array|string[] Async data or actual mismatch results
	 */
	private array $data;

	/**
	 * @var string Human-readable description of this check
	 */
	private string $description = '';

	/**
	 * @param array|string[] $data Either async data ['async' => '<wpurl>'] or actual results
	 */
	public function __construct( array $data ) {
		$this->data = $data;
	}

	/**
	 * Get the core checksum data.
	 *
	 * @return array
	 */
	public function getData(): array {
		return $this->data;
	}

	/**
	 * Whether this is async data that needs to be fetched.
	 *
	 * @return bool
	 */
	public function isAsync(): bool {
		return isset( $this->data['async'] );
	}

	/**
	 * Whether any core files have mismatched checksums.
	 *
	 * @return bool
	 */
	public function hasMismatches(): bool {
		if ( $this->isAsync() ) {
			return false; // Unknown until async check completes
		}
		return ! empty( $this->data );
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
