<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;
use JosephG\Roko\Infrastructure\WordPress\Security\I18n\SecurityKeysI18n;

/**
 * Returns the eight effective secrets that WordPress uses
 * at run-time, tagging each one with its origin:
 *  - 'constant'  value defined in wp-config.php
 *  - 'db'        value auto-generated & stored in wp_options
 *  - 'roko'      value generated and managed by Roko's secure vault
 *  - 'filter'    value supplied via the `salt` filter by another plugin
 */
final class WpSecurityKeysProvider implements SecurityKeysProviderInterface {

	public function snapshot(): SecurityKeys {

		$schemes = array(
			'auth'        => array( 'authKey', 'authSalt' ),
			'secure_auth' => array( 'secureAuthKey', 'secureAuthSalt' ),
			'logged_in'   => array( 'loggedInKey', 'loggedInSalt' ),
			'nonce'       => array( 'nonceKey', 'nonceSalt' ),
		);

		$objects = array();

		foreach ( $schemes as $scheme => $labels ) {

			$info = $this->getKeySaltInfo( $scheme );

			$objects[ $labels[0] ] = new SecurityKey(
				$info['key'],
				SecurityKeysI18n::description( $labels[0] ),
				$info['keySrc']
			);
			$objects[ $labels[1] ] = new SecurityKey(
				$info['salt'],
				SecurityKeysI18n::description( $labels[1] ),
				$info['saltSrc']
			);
		}

		$securityKeys = new SecurityKeys(
			$objects['authKey'],
			$objects['secureAuthKey'],
			$objects['loggedInKey'],
			$objects['nonceKey'],
			$objects['authSalt'],
			$objects['secureAuthSalt'],
			$objects['loggedInSalt'],
			$objects['nonceSalt']
		);

		$securityKeys->setSectionSummary(
			esc_html__( 'Security Keys', 'roko' ),
			esc_html__(
				'Roko inspects the secrets WordPress actually uses at run-time, so weak or missing values never slip through.',
				'roko'
			)
		);

		// Add last rotated timestamp if available
		$lastRotated = get_option( 'roko_salts_last_rotated' );
		if ( $lastRotated ) {
			$securityKeys->setLastRotated( $lastRotated );
		}

		return $securityKeys;
	}

	/**
	 * @return array{key:string,keySrc:string,salt:string,saltSrc:string}
	 */
	private function getKeySaltInfo( string $scheme ): array {

		$combined = wp_salt( $scheme );          // 128-char string
		$key      = substr( $combined, 0, 64 );
		$salt     = substr( $combined, 64 );

		$constKey  = strtoupper( $scheme ) . '_KEY';
		$constSalt = strtoupper( $scheme ) . '_SALT';

		// Check if Roko is providing the salts (check this FIRST)
		$isRokoProvided = apply_filters( 'roko_is_providing_salts', false );

		// If Roko is providing salts, that takes priority over everything else
		if ( $isRokoProvided ) {
			$keySrc  = 'roko';
			$saltSrc = 'roko';
		} else {
			// Fall back to normal detection
			$keySrc = defined( $constKey ) && strpos( constant( $constKey ), 'put your unique' ) === false
						? 'constant'
						: ( get_option( "{$scheme}_salt" ) ? 'db fallback' : 'filter' );

			$saltSrc = defined( $constSalt ) && strpos( constant( $constSalt ), 'put your unique' ) === false
						? 'constant'
						: ( get_option( "{$scheme}_salt" ) ? 'db fallback' : 'filter' );
		}

		return array(
			'key'     => $key,
			'keySrc'  => $keySrc,
			'salt'    => $salt,
			'saltSrc' => $saltSrc,
		);
	}
}
