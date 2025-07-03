<?php
declare(strict_types=1);

namespace JosephG\Roko\Application\Security;

use JosephG\Roko\Domain\Security\SecurityAggregate;
use JosephG\Roko\Application\Security\Provider\SecurityTranslationProviderInterface;

/**
 * Application Service for Security operations.
 *
 * Orchestrates domain operations and coordinates with infrastructure providers.
 * This is where cross-cutting concerns like translations are handled.
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
	 */
	public function getSecuritySnapshot() {
		// Get pure domain data
		$domainSnapshot = $this->securityAggregate->snapshot();

		// Enhance with translations from infrastructure
		return $this->enhanceWithTranslations( $domainSnapshot );
	}

	/**
	 * Get security snapshot as JSON.
	 */
	public function getSecuritySnapshotJson( $options = 0 ) {
		return json_encode( $this->getSecuritySnapshot(), JSON_THROW_ON_ERROR | $options );
	}

	/**
	 * Enhance domain snapshot with translation-specific data.
	 */
	private function enhanceWithTranslations( array $domainSnapshot ) {
		// Transform SecurityKeys section with proper recommendations
		foreach ( $domainSnapshot['sections'] as &$section ) {
			if ( $section['id'] === 'security_keys' ) {
				$section['checks'] = $this->enhanceSecurityKeysChecks( $section['checks'] );
			}
		}

		return $domainSnapshot;
	}

	/**
	 * Enhance security keys checks with contextual recommendations.
	 */
	private function enhanceSecurityKeysChecks( array $checks ) {
		foreach ( $checks as &$check ) {
			// Extract strength and source from check data to get proper recommendation
			$strength = $this->extractStrengthFromCheck( $check );
			$source   = $this->extractSourceFromCheck( $check );

			$check['recommendation'] = $this->translationProvider->getSecurityKeyRecommendation( $strength, $source );
		}

		return $checks;
	}

	/**
	 * Extract strength from check evidence.
	 */
	private function extractStrengthFromCheck( array $check ) {
		return isset( $check['evidence']['strength'] ) ? $check['evidence']['strength'] : 'unknown';
	}

	/**
	 * Extract source from check evidence.
	 */
	private function extractSourceFromCheck( array $check ) {
		return isset( $check['evidence']['source'] ) ? $check['evidence']['source'] : 'unknown';
	}
}
