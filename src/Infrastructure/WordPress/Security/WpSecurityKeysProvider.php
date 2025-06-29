<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;
use JosephG\Roko\Infrastructure\WordPress\Security\I18n\SecurityKeys as I18nSecurityKeys;

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

		$authKey        = new SecurityKey( $wpConfigAuthKey, I18nSecurityKeys::description( 'authKey' ) );
		$secureAuthKey  = new SecurityKey( $wpConfigSecureAuthKey, I18nSecurityKeys::description( 'secureAuthKey' ) );
		$loggedInKey    = new SecurityKey( $wpConfigLoggedInKey, I18nSecurityKeys::description( 'loggedInKey' ) );
		$nonceKey       = new SecurityKey( $wpConfigNonceKey, I18nSecurityKeys::description( 'nonceKey' ) );
		$authSalt       = new SecurityKey( $wpConfigAuthSalt, I18nSecurityKeys::description( 'authSalt' ) );
		$secureAuthSalt = new SecurityKey( $wpConfigSecureAuthSalt, I18nSecurityKeys::description( 'secureAuthSalt' ) );
		$loggedInSalt   = new SecurityKey( $wpConfigLoggedInSalt, I18nSecurityKeys::description( 'loggedInSalt' ) );
		$nonceSalt      = new SecurityKey( $wpConfigNonceSalt, I18nSecurityKeys::description( 'nonceSalt' ) );

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
