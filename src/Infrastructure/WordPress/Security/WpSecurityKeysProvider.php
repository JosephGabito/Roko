<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;

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

		$authKey = new SecurityKey(
			$wpConfigAuthKey,
			esc_html__( 'Prevents unauthorized access to user accounts through cookie manipulation.', 'roko' )
		);

		$secureAuthKey = new SecurityKey(
			$wpConfigSecureAuthKey,
			esc_html__( 'Adds extra protection when users access your site via secure (SSL) connections.', 'roko' )
		);

		$loggedInKey = new SecurityKey(
			$wpConfigLoggedInKey,
			esc_html__( 'Prevents hijacking of long-term login sessions when users stay logged in.', 'roko' )
		);

		$nonceKey = new SecurityKey(
			$wpConfigNonceKey,
			esc_html__( 'Secures form submissions and AJAX requests against CSRF attacks.', 'roko' )
		);

		$authSalt = new SecurityKey(
			$wpConfigAuthSalt,
			esc_html__( 'Adds randomness to authentication cookies making them impossible to reverse-engineer.', 'roko' )
		);

		$secureAuthSalt = new SecurityKey(
			$wpConfigSecureAuthSalt,
			esc_html__( 'Enhances security of encrypted authentication on SSL/HTTPS connections.', 'roko' )
		);

		$loggedInSalt = new SecurityKey(
			$wpConfigLoggedInSalt,
			esc_html__( 'Makes persistent login sessions more secure against session hijacking attacks.', 'roko' )
		);

		$nonceSalt = new SecurityKey(
			$wpConfigNonceSalt,
			esc_html__( 'Adds extra randomness to nonce generation for stronger CSRF protection.', 'roko' )
		);

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
			esc_html__( 'Site Security Foundation', 'roko' ),
			esc_html__( 'WordPress uses secret keys in wp-config.php to encrypt user sessions and prevent hackers from stealing accounts. When these are weak or missing, your entire site becomes vulnerable.', 'roko' )
		);

		return $securityKeys;
	}
}
