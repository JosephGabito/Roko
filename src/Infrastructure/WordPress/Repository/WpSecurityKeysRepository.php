<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use DateTimeImmutable;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\KeyPair;
use JosephG\Roko\Domain\Security\SecurityKeys\Repository\SecurityKeysRepositoryInterface;

final class WpSecurityKeysRepository implements SecurityKeysRepositoryInterface {

	private const OPTION = 'roko_security_keys';

	public function current(): SecurityKeys {
		$stored = get_option( self::OPTION, array() );
		return new SecurityKeys(
			new KeyPair( $stored['auth_key'] ?? $this->getAuthKey(), $stored['auth_salt'] ?? $this->getAuthSalt() ),
			new KeyPair( $stored['secure_auth_key'] ?? $this->getSecureAuthKey(), $stored['secure_auth_salt'] ?? $this->getSecureAuthSalt() ),
			isset( $stored['rotated_at'] ) ? new DateTimeImmutable( $stored['rotated_at'] ) : new DateTimeImmutable( '@0' )
		);
	}

	private function getAuthKey() {
		return defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
	}

	private function getSecureAuthKey() {
		return defined( 'SECURE_AUTH_KEY' ) ? SECURE_AUTH_KEY : '';
	}

	private function getAuthSalt() {
		return defined( 'AUTH_SALT' ) ? AUTH_SALT : '';
	}

	private function getSecureAuthSalt() {
		return defined( 'SECURE_AUTH_SALT' ) ? SECURE_AUTH_SALT : '';
	}

	public function store( SecurityKeys $keys ): void {
		update_option(
			self::OPTION,
			array(
				'auth_key'         => $keys->auth()->key,
				'auth_salt'        => $keys->auth()->salt,
				'secure_auth_key'  => $keys->secureAuth()->key,
				'secure_auth_salt' => $keys->secureAuth()->salt,
				'rotated_at'       => $keys->rotatedAt()->format( DateTimeImmutable::ATOM ),
			),
			false
		);
	}

	public function generateNew(): SecurityKeys {
		$randomKey  = wp_generate_password( 64, true, true );
		$randomSalt = wp_generate_password( 64, true, true );
		return new SecurityKeys(
			new KeyPair( $randomKey, $randomSalt ),
			new KeyPair( wp_generate_password( 64, true, true ), wp_generate_password( 64, true, true ) ),
			new DateTimeImmutable()
		);
	}
}
