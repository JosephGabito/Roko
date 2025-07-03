<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

/**
 * Value object for check status values as defined in JSON schema.
 * PHP 7.0 compatible alternative to enum.
 */
final class CheckStatus {
	public const PASS    = 'pass';
	public const FAIL    = 'fail';
	public const NOTICE  = 'notice';
	public const PENDING = 'pending';

	private string $value;

	private function __construct( string $value ) {
		$this->value = $value;
	}

	public static function pass(): self {
		return new self( self::PASS );
	}

	public static function fail(): self {
		return new self( self::FAIL );
	}

	public static function notice(): self {
		return new self( self::NOTICE );
	}

	public static function pending(): self {
		return new self( self::PENDING );
	}

	public function value(): string {
		return $this->value;
	}

	public function equals( CheckStatus $other ): bool {
		return $this->value === $other->value;
	}
}
