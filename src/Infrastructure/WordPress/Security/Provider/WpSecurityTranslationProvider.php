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
	 * Get recommendation text for security key based on strength and source.
	 *
	 * @param string $strength Key strength (none, weak, strong).
	 * @param string $source   Key source (constant, db, roko, filter).
	 * @return string Localized recommendation text.
	 */
	public function getSecurityKeyRecommendation( $strength, $source ) {
		return SecurityKeysChecksI18n::recommendation( $strength, $source );
	}
}
