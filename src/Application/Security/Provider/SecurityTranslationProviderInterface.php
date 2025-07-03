<?php
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

	/**
	 * Get file security recommendation for a specific business code.
	 *
	 * @param string $businessCode Business code from domain (e.g., 'directory_listing_vulnerable').
	 * @return string Localized recommendation text.
	 */
	public function getFileSecurityRecommendation( $businessCode );

	/**
	 * Get file integrity recommendation for a specific business code.
	 *
	 * @param string $businessCode Business code from domain (e.g., 'core_checksum_mismatch').
	 * @return string Localized recommendation text.
	 */
	public function getFileIntegrityRecommendation( $businessCode );

	/**
	 * Get vulnerability recommendation for a specific business code.
	 *
	 * @param string $businessCode Business code from domain (e.g., 'vulnerabilities_found').
	 * @return string Localized recommendation text.
	 */
	public function getVulnerabilityRecommendation( $businessCode );
}
