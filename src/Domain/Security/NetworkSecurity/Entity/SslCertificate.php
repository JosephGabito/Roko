<?php
namespace JosephG\Roko\Domain\Security\NetworkSecurity\Entity;

use JosephG\Roko\Domain\Security\NetworkSecurity\ValueObject\DomainName;

final readonly class SslCertificate {

	public function __construct(
		public DomainName $domain,
		public bool $valid,
		public \DateTimeImmutable $expiresAt,
	) {}

	public function isExpired(): bool {
		return $this->expiresAt < new \DateTimeImmutable();
	}
}
