<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\Service;

use JosephG\Roko\Domain\Security\FileIntegrity\Repository\FileIntegrityRepositoryInterface;

final readonly class IntegrityScanner {

	public function __construct( private FileIntegrityRepositoryInterface $repo ) {}

	public function rescan(): IntegrityScan {
		// delegate heavy scan to repository / infrastructure
		return $this->repo->latestScan();
	}
}
