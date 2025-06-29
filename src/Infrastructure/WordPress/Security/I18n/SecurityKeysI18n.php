<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * SecurityKeys strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class SecurityKeysI18n {

	/**
	 * Get the description for a given key.
	 *
	 * @param string $key The key to get the description for.
	 * @return string The description for the given key.
	 * @throws \InvalidArgumentException If the key is not found.
	 */
	public static function description( string $key ): string {
		$strings = self::stringsCollection();
		if ( ! isset( $strings[ $key ] ) ) {
			throw new \InvalidArgumentException( sprintf( 'Invalid key: %s', $key ) );
		}
		return $strings[ $key ];
	}

	/**
	 * Get the strings collection.
	 *
	 * @return array The strings collection.
	 */
	public static function stringsCollection(): array {

		return array(
			'authKey'        => __( 'Prevents unauthorized access to user accounts through cookie manipulation.', 'roko' ),
			'secureAuthKey'  => __( 'Adds extra protection when users access your site via secure (SSL) connections.', 'roko' ),
			'loggedInKey'    => __( 'Prevents hijacking of long-term login sessions when users stay logged in.', 'roko' ),
			'nonceKey'       => __( 'Secures form submissions and AJAX requests against CSRF attacks.', 'roko' ),
			'authSalt'       => __( 'Adds randomness to authentication cookies making them impossible to reverse-engineer.', 'roko' ),
			'secureAuthSalt' => __( 'Enhances security of encrypted authentication on SSL/HTTPS connections.', 'roko' ),
			'loggedInSalt'   => __( 'Makes persistent login sessions more secure against session hijacking attacks.', 'roko' ),
			'nonceSalt'      => __( 'Adds extra randomness to nonce generation for stronger CSRF protection.', 'roko' ),
		);
	}
}
