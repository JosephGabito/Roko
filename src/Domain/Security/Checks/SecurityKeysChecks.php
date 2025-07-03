<?php
namespace JosephG\Roko\Domain\Security\Checks;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Check;
use JosephG\Roko\Domain\Security\Checks\ValueObject\CheckStatus;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Severity;

/**
 * Aggregate that transforms SecurityKeys domain objects into Check value objects.
 *
 * Handles mapping of strength to status/severity and generates contextual recommendations.
 */
final class SecurityKeysChecks {

	/** @var Check[] */
	private $checks;

	private function __construct( array $checks ) {
		$this->checks = $checks;
	}

	/**
	 * Create SecurityKeysChecks from SecurityKeys domain entity.
	 *
	 * @param SecurityKeys $securityKeys Domain entity with security keys.
	 */
	public static function fromSecurityKeys( SecurityKeys $securityKeys ) {
		$keyMappings = self::getKeyMappings();
		$checks      = array();

		foreach ( $securityKeys->toArray() as $displayName => $securityKey ) {
			if ( ! isset( $keyMappings[ $displayName ] ) ) {
				continue; // Skip unknown keys
			}

			$keyId              = $keyMappings[ $displayName ];
			$strength           = $securityKey->strength();
			$source             = $securityKey->source();
			$recommendationCode = $strength . '_' . $source; // Business code, not translated text

			$checks[] = new Check(
				$keyId,
				$displayName,
				self::mapStrengthToStatus( $strength ),
				self::calculateSeverity( $strength, $keyId ),
				$securityKey->description(),
				self::buildEvidence( $securityKey, $securityKeys->getLastRotated() ),
				$recommendationCode, // Domain emits business codes
				'roko'
			);
		}

		return new self( $checks );
	}

	/**
	 * Get all checks as array.
	 *
	 * @return array
	 */
	public function toArray() {
		return array_map(
			function ( Check $check ) {
				return $check->toArray();
			},
			$this->checks
		);
	}

	/**
	 * Get check by key ID.
	 */
	public function getByKey( string $keyId ): ?Check {
		foreach ( $this->checks as $check ) {
			if ( $check->getId() === $keyId ) {
				return $check;
			}
		}
		return null;
	}

	/**
	 * Count total checks.
	 */
	public function count() {
		return count( $this->checks );
	}

	/**
	 * Get all Check objects.
	 *
	 * @return Check[]
	 */
	public function getChecks() {
		return $this->checks;
	}

	/**
	 * Map display names to snake_case IDs.
	 */
	private static function getKeyMappings() {
		return array(
			'Login Security'                => 'auth_key',
			'HTTPS Login Security'          => 'secure_auth_key',
			'Remember Me Security'          => 'logged_in_key',
			'Form Protection'               => 'nonce_key',
			'Login Cookie Protection'       => 'auth_salt',
			'HTTPS Cookie Protection'       => 'secure_auth_salt',
			'Remember Me Cookie Protection' => 'logged_in_salt',
			'Form Cookie Protection'        => 'nonce_salt',
		);
	}

	/**
	 * Map SecurityKey strength to CheckStatus.
	 */
	private static function mapStrengthToStatus( string $strength ) {
		switch ( $strength ) {
			case 'strong':
				return CheckStatus::pass();
			case 'weak':
			case 'none':
				return CheckStatus::fail();
			default:
				return CheckStatus::notice();
		}
	}

	/**
	 * Calculate severity based on strength and key importance.
	 */
	private static function calculateSeverity( string $strength, string $keyId ) {
		if ( $strength === 'strong' ) {
			return Severity::low(); // Good but mention rotation
		}

		// Critical authentication keys
		if ( in_array( $keyId, array( 'auth_key', 'secure_auth_key' ), true ) ) {
			return Severity::critical();
		}

		// Important keys (salts and other keys)
		return Severity::high();
	}

	/**
	 * Build evidence object with key details.
	 */
	private static function buildEvidence( $securityKey, $lastRotated ) {
		return array(
			'strength'      => $securityKey->strength(),
			'source'        => $securityKey->source(),
			'keyLength'     => strlen( $securityKey->value() ),
			'isRokoManaged' => $securityKey->source() === 'roko',
			'lastRotated'   => $lastRotated,
		);
	}
}
