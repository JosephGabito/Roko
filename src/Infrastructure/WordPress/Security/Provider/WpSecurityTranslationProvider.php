<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\Provider;

use JosephG\Roko\Application\Security\Provider\SecurityTranslationProviderInterface;
use JosephG\Roko\Infrastructure\WordPress\Security\I18n\SecurityKeysChecksI18n;

/**
 * WordPress implementation of SecurityTranslationProvider.
 *
 * Provides WordPress-specific translations for security recommendations.
 */
final class WpSecurityTranslationProvider implements SecurityTranslationProviderInterface {

	/**
	 * Get all security key recommendations keyed by strength_source pattern.
	 *
	 * @return array Array of recommendations keyed by 'strength_source' (e.g., 'none_constant', 'strong_roko').
	 */
	public function getAllSecurityKeyRecommendations() {
		$recommendations = array();
		$strengths       = array( 'none', 'weak', 'strong' );
		$sources         = array( 'constant', 'db', 'roko', 'filter' );

		// Generate all possible combinations
		foreach ( $strengths as $strength ) {
			foreach ( $sources as $source ) {
				$key                     = $strength . '_' . $source;
				$recommendations[ $key ] = SecurityKeysChecksI18n::recommendation( $strength, $source );
			}
		}

		return $recommendations;
	}
}
