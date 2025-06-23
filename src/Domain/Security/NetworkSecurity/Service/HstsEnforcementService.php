<?php
namespace JosephG\Roko\Domain\Security\NetworkSecurity\Service;

use JosephG\Roko\Domain\Security\NetworkSecurity\Repository\NetworkSecurityRepositoryInterface;

final readonly class HstsEnforcementService {

	public function __construct( private NetworkSecurityRepositoryInterface $repo ) {}

	public function isHstsEnabled(): bool {
		$state = $this->repo->currentState();
		return $state->hsts ?? false;
	}
}
