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
	 * Get all security key recommendations keyed by strength_source pattern.
	 *
	 * @return array Array of recommendations keyed by 'strength_source' (e.g., 'none_constant', 'strong_roko').
	 */
	public function getAllSecurityKeyRecommendations();
}
