<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

/**
 * Loads salts from the database if they exist.
 *
 * @see https://github.com/WordPress/WordPress/blob/master/wp-includes/pluggable.php#L1032
 */
final class DynamicSaltLoader {

	public static function boot(): void {
		$salts = SaltVault::get();
		if ( ! $salts ) {
			return; }          // nothing stored yet

		// Mark that Roko is providing salts
		add_filter( 'roko_is_providing_salts', '__return_true' );

		add_filter(
			'salt',
			static function ( $value, $scheme ) use ( $salts ) {
				if ( isset( $salts[ $scheme ] ) ) {
					return $salts[ $scheme ][0] . $salts[ $scheme ][1]; // key + salt
				}
				return $value;
			},
			10,
			2
		);
	}
}
