<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\ValueObject;

/**
 * Immutable value object that represents a single WordPress key or salt.
 *
 *  • $value   – the 64-character secret itself
 *  • $source  – where it came from: 'constant', 'db', or 'filter'
 *
 * Strength rules (unchanged):
 *  - NONE  : constant missing or empty
 *  - WEAK  : too short OR low entropy OR lacks char-class variety
 *  - STRONG: ≥ 48 chars, includes upper+lower+digit+symbol, entropy ≥ 200 bits
 */
final class SecurityKey {

	/* ---------- policy thresholds ---------- */
	private const MIN_LEN_STRONG = 48;
	private const ENTROPY_STRONG = 200.0;   // Shannon bits

	/* ---------- core properties ---------- */
	private string $key;          // 64-char secret
	private string $description;  // human-readable label
	private string $source;       // constant | db | filter

	public function __construct(
		string $key,
		string $description,
		string $source = 'constant'
	) {
		$this->key         = $key;
		$this->description = $description;
		$this->source      = $source;
	}

	/* ---------- public API ---------- */

	public function description(): string {
		return $this->description;
	}

	public function value(): string {
		return $this->key;
	}

	public function source(): string {
		return $this->source;
	}

	public function __toString(): string {
		return $this->key;
	}

	/**
	 * Returns one of: 'none', 'weak', 'strong'.
	 */
	public function strength(): string {
		if ( $this->isEmpty() ) {
			return 'none';
		}

		return $this->passesStrongRules() ? 'strong' : 'weak';
	}

	public function isStrong(): bool {
		return $this->passesStrongRules();
	}

	public function isWeak(): bool {
		return ! $this->isEmpty() && ! $this->passesStrongRules();
	}

	public function isEmpty(): bool {
		return $this->key === '';
	}

	public function toArray(): array {
		return array(
			'value'       => $this->key,
			'description' => $this->description,
			'source'      => $this->source,
			'strength'    => $this->strength(),
		);
	}

	/* ---------- internal helpers ---------- */

	private function passesStrongRules(): bool {
		return \strlen( $this->key ) >= self::MIN_LEN_STRONG
			&& $this->hasAllCharClasses()
			&& $this->entropyBits() >= self::ENTROPY_STRONG;
	}

	private function hasAllCharClasses(): bool {
		return (bool) (
			\preg_match( '/[A-Z]/', $this->key ) &&
			\preg_match( '/[a-z]/', $this->key ) &&
			\preg_match( '/[0-9]/', $this->key ) &&
			\preg_match( '/[^A-Za-z0-9]/', $this->key )
		);
	}

	/**
	 * Conservative Shannon-entropy estimate (bits).
	 */
	private function entropyBits(): float {
		$len = \strlen( $this->key );
		if ( $len === 0 ) {
			return 0.0;
		}

		$freq           = \array_count_values( \str_split( $this->key ) );
		$entropyPerChar = 0.0;

		foreach ( $freq as $count ) {
			$p               = $count / $len;
			$entropyPerChar -= $p * \log( $p, 2 );
		}

		return $entropyPerChar * $len;
	}

	/* ---------- utility ---------- */

	public static function generate(): self {
		$raw = \function_exists( 'wp_generate_password' )
			? wp_generate_password( self::MIN_LEN_STRONG, true, true )
			: \bin2hex( \random_bytes( self::MIN_LEN_STRONG / 2 ) );

		return new self( $raw, 'WordPress Auth Key', 'generated' );
	}
}
