<?php
declare(strict_types=1);

namespace JosephG\Roko\Application\Security;

use JosephG\Roko\Domain\Security\SecurityAggregate;
use JosephG\Roko\Application\Security\Provider\SecurityTranslationProviderInterface;
use JosephG\Roko\Application\Security\Fix\SecurityFixMapper;

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
	 * Also populate fix data for failed checks.
	 *
	 * This keeps i18n concerns in Application layer, not Domain.
	 */
	private function translateBusinessCodes( array $domainSnapshot ) {
		// Get all translations from infrastructure
		$securityKeyTranslations = $this->translationProvider->getAllSecurityKeyRecommendations();

		// Transform each section
		foreach ( $domainSnapshot['sections'] as &$section ) {
			if ( $section['id'] === 'security_keys' ) {
				foreach ( $section['checks'] as &$check ) {
					// Domain emitted business code - Application translates it
					$businessCode            = $check['recommendation'];
					$check['recommendation'] = isset( $securityKeyTranslations[ $businessCode ] )
						? $securityKeyTranslations[ $businessCode ]
						: 'Review configuration and strengthen security.';

					// Add fix data for failed checks
					$this->addFixDataIfAvailable( $check, $businessCode );
				}
			} elseif ( $section['id'] === 'file_security' ) {
				foreach ( $section['checks'] as &$check ) {
					// Domain emitted business code - Application translates it
					$businessCode            = $check['recommendation'];
					$check['recommendation'] = $this->translationProvider->getFileSecurityRecommendation( $businessCode );

					// Add fix data for failed checks
					$this->addFixDataIfAvailable( $check, $businessCode );
				}
			} elseif ( $section['id'] === 'file_integrity' ) {
				foreach ( $section['checks'] as &$check ) {
					// Domain emitted business code - Application translates it
					$businessCode            = $check['recommendation'];
					$check['recommendation'] = $this->translationProvider->getFileIntegrityRecommendation( $businessCode );

					// Add fix data for failed checks
					$this->addFixDataIfAvailable( $check, $businessCode );
				}
			} elseif ( $section['id'] === 'known_vulnerabilities' ) {
				foreach ( $section['checks'] as &$check ) {
					// Domain emitted business code - Application translates it
					$businessCode            = $check['recommendation'];
					$check['recommendation'] = $this->translationProvider->getVulnerabilityRecommendation( $businessCode );

					// Add fix data for failed checks
					$this->addFixDataIfAvailable( $check, $businessCode );
				}
			}
		}

		return $domainSnapshot;
	}

	/**
	 * Add fix data to a check if it's failed and fixable.
	 *
	 * @param array &$check The check array to modify
	 * @param string $businessCode The business code emitted by domain
	 */
	private function addFixDataIfAvailable( array &$check, $businessCode ) {
		// Only add fix data for failed checks
		if ( $check['status'] !== 'fail' ) {
			return;
		}

		// Check if this business code has an available fix
		$fixData = SecurityFixMapper::getFixForBusinessCode( $businessCode );
		if ( $fixData ) {
			$check['fix'] = $fixData;
		}
	}
}
