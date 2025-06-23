<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\Repository;

use JosephG\Roko\Domain\Security\FileIntegrity\Entity\IntegrityScan;

interface FileIntegrityRepositoryInterface {

	public function latestScan(): IntegrityScan;
}
