<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;
use JosephG\Roko\Infrastructure\WordPress\Security\I18n\SecurityKeysI18n;

final class WpSecurityKeysProvider implements SecurityKeysProviderInterface {

	public function snapshot(): SecurityKeys {

		$wpConfigAuthKey        = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
		$wpConfigSecureAuthKey  = defined( 'SECURE_AUTH_KEY' ) ? SECURE_AUTH_KEY : '';
		$wpConfigLoggedInKey    = defined( 'LOGGED_IN_KEY' ) ? LOGGED_IN_KEY : '';
		$wpConfigNonceKey       = defined( 'NONCE_KEY' ) ? NONCE_KEY : '';
		$wpConfigAuthSalt       = defined( 'AUTH_SALT' ) ? AUTH_SALT : '';
		$wpConfigSecureAuthSalt = defined( 'SECURE_AUTH_SALT' ) ? SECURE_AUTH_SALT : '';
		$wpConfigLoggedInSalt   = defined( 'LOGGED_IN_SALT' ) ? LOGGED_IN_SALT : '';
		$wpConfigNonceSalt      = defined( 'NONCE_SALT' ) ? NONCE_SALT : '';

		$authKey        = new SecurityKey( $wpConfigAuthKey, SecurityKeysI18n::description( 'authKey' ) );
		$secureAuthKey  = new SecurityKey( $wpConfigSecureAuthKey, SecurityKeysI18n::description( 'secureAuthKey' ) );
		$loggedInKey    = new SecurityKey( $wpConfigLoggedInKey, SecurityKeysI18n::description( 'loggedInKey' ) );
		$nonceKey       = new SecurityKey( $wpConfigNonceKey, SecurityKeysI18n::description( 'nonceKey' ) );
		$authSalt       = new SecurityKey( $wpConfigAuthSalt, SecurityKeysI18n::description( 'authSalt' ) );
		$secureAuthSalt = new SecurityKey( $wpConfigSecureAuthSalt, SecurityKeysI18n::description( 'secureAuthSalt' ) );
		$loggedInSalt   = new SecurityKey( $wpConfigLoggedInSalt, SecurityKeysI18n::description( 'loggedInSalt' ) );
		$nonceSalt      = new SecurityKey( $wpConfigNonceSalt, SecurityKeysI18n::description( 'nonceSalt' ) );

		$securityKeys = new SecurityKeys(
			$authKey,
			$secureAuthKey,
			$loggedInKey,
			$nonceKey,
			$authSalt,
			$secureAuthSalt,
			$loggedInSalt,
			$nonceSalt
		);

		$securityKeys->setSectionSummary(
			esc_html__( 'Security Keys', 'roko' ),
			esc_html__( 'Secret keys in your WordPress settings help protect logins and sessions. Roko checks for weak or missing keys and points out anything that needs fixing.', 'roko' )
		);

		return $securityKeys;
	}
}
