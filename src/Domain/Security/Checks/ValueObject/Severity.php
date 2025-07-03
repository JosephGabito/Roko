<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

/**
 * Value object for severity levels as defined in JSON schema.
 * PHP 7.0 compatible alternative to enum.
 */
final class Severity {
	public const LOW      = 'low';
	public const MEDIUM   = 'medium';
	public const HIGH     = 'high';
	public const CRITICAL = 'critical';

	private string $value;

	private function __construct( string $value ) {
		$this->value = $value;
	}

	public static function low(): self {
		return new self( self::LOW );
	}

	public static function medium(): self {
		return new self( self::MEDIUM );
	}

	public static function high(): self {
		return new self( self::HIGH );
	}

	public static function critical(): self {
		return new self( self::CRITICAL );
	}

	public function value(): string {
		return $this->value;
	}

	public function equals( Severity $other ): bool {
		return $this->value === $other->value;
	}
}
