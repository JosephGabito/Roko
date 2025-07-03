<?php
declare(strict_types=1);

namespace JosephG\Roko\Application\Security;

use JosephG\Roko\Domain\Security\SecurityAggregate;
use JosephG\Roko\Application\Security\Provider\SecurityTranslationProviderInterface;

/**
 * Application Service for getting security snapshots.
 *
 * Orchestrates domain operations and coordinates with infrastructure providers.
 * Follows single-responsibility principle - focused on one use case.
 */
final class SecurityApplicationService {

	/** @var SecurityAggregate */
	private $securityAggregate;

	/** @var SecurityTranslationProviderInterface */
	private $translationProvider;

	public function __construct(
		SecurityAggregate $securityAggregate,
		SecurityTranslationProviderInterface $translationProvider
	) {
		$this->securityAggregate   = $securityAggregate;
		$this->translationProvider = $translationProvider;
	}

	/**
	 * Get security snapshot with proper translations.
	 * 
	 * Domain emits business codes - Application translates them to human text.
	 */
	public function getSecuritySnapshot() {
		// Get pure domain snapshot with business codes
		$domainSnapshot = $this->securityAggregate->snapshot();
		
		// Application layer responsibility: translate business codes to human text
		return $this->translateBusinessCodes( $domainSnapshot );
	}

	/**
	 * Get security snapshot as JSON.
	 */
	public function getSecuritySnapshotJson( $options = 0 ) {
		return json_encode( $this->getSecuritySnapshot(), JSON_THROW_ON_ERROR | $options );
	}

	/**
	 * Translate business codes emitted by Domain into human-readable text.
	 * 
	 * This keeps i18n concerns in Application layer, not Domain.
	 */
	private function translateBusinessCodes( array $domainSnapshot ) {
		// Get all translations from infrastructure
		$translations = $this->translationProvider->getAllSecurityKeyRecommendations();
		
		// Transform security keys section
		foreach ( $domainSnapshot['sections'] as &$section ) {
			if ( $section['id'] === 'security_keys' ) {
				foreach ( $section['checks'] as &$check ) {
					// Domain emitted business code - Application translates it
					$businessCode = $check['recommendation'];
					$check['recommendation'] = isset( $translations[ $businessCode ] ) 
						? $translations[ $businessCode ] 
						: 'Review configuration and strengthen security.';
				}
			}
		}
		
		return $domainSnapshot;
	}
}
