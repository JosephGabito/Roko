<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

final class SaltVault {

	/** Option name that holds the encrypted JSON blob */
	private const OPTION = 'roko_secure_salts';

	/** Derive a 32-byte secret-box key from AUTH_KEY */
	private static function key(): string {
		$master = defined( 'AUTH_KEY' ) ? AUTH_KEY : wp_generate_password( 64, true, true );
		// HKDF-SHA256 → 32 bytes                                                ↑ falls back for unit tests
		return hash_hkdf( 'sha256', $master, 32, 'roko-salt-vault' );
	}

	/** Store new salts (expects ['auth'=>[key,salt], …]) */
	public static function put( array $payload ): void {
		$json = wp_json_encode( $payload );
		$key  = self::key();

		if ( function_exists( 'sodium_crypto_secretbox' ) ) {                       // libsodium path
			$nonce  = random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
			$cipher = $nonce . sodium_crypto_secretbox( $json, $nonce, $key );
		} else {                                                                    // openssl fallback
			$iv     = random_bytes( 16 );                                           // AES-256-CTR
			$cipher = $iv . openssl_encrypt( $json, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv );
		}

		update_option( self::OPTION, base64_encode( $cipher ), false );
	}

	/** Retrieve + decrypt; returns [] if option missing/invalid */
	public static function get(): array {
		$raw = base64_decode( get_option( self::OPTION, '' ), true );
		if ( ! $raw ) {
			return array(); }

		$key = self::key();

		if ( function_exists( 'sodium_crypto_secretbox_open' ) ) {
			$nonce  = substr( $raw, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
			$cipher = substr( $raw, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
			$plain  = sodium_crypto_secretbox_open( $cipher, $nonce, $key );
		} else {
			$iv     = substr( $raw, 0, 16 );
			$cipher = substr( $raw, 16 );
			$plain  = openssl_decrypt( $cipher, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv );
		}

		return $plain ? json_decode( $plain, true ) : array();
	}

	/** Clear all stored salts */
	public static function clear(): void {
		delete_option( self::OPTION );
	}
}
