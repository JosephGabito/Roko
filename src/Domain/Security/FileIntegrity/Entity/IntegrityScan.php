<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\Entity;

final class IntegrityScan {

	public function __construct(
		public bool $coreIntact,
		public int $suspiciousCount,
		public \DateTimeImmutable $scannedAt,
	) {}
}
