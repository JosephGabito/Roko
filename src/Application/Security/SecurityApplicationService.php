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
	 * Single-purpose use case following DDD patterns.
	 */
	public function getSecuritySnapshot() {
		// Get recommendations from infrastructure
		$recommendations = $this->translationProvider->getAllSecurityKeyRecommendations();

		// Let domain handle its own serialization with translations
		return $this->securityAggregate->snapshot( $recommendations );
	}

	/**
	 * Get security snapshot as JSON.
	 */
	public function getSecuritySnapshotJson( $options = 0 ) {
		return json_encode( $this->getSecuritySnapshot(), JSON_THROW_ON_ERROR | $options );
	}
}
