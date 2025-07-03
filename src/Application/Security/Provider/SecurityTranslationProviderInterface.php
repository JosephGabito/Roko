<?php
declare(strict_types=1);

namespace JosephG\Roko\Application\Security\Provider;

/**
 * Interface for security translation providers.
 *
 * Abstracts translation concerns from the application layer.
 * Infrastructure layer will implement this to provide WordPress-specific translations.
 */
interface SecurityTranslationProviderInterface {

	/**
	 * Get recommendation text for security key based on strength and source.
	 *
	 * @param string $strength Key strength (none, weak, strong).
	 * @param string $source   Key source (constant, db, roko, filter).
	 * @return string Localized recommendation text.
	 */
	public function getSecurityKeyRecommendation( $strength, $source );
}
