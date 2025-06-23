<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\Entity;

use DateTimeImmutable;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\KeyPair;

final class SecurityKeys {

	public function __construct(
		private KeyPair $auth,
		private KeyPair $secureAuth,
		private DateTimeImmutable $rotatedAt,
	) {}

	public function rotatedAt(): DateTimeImmutable {
		return $this->rotatedAt;
	}

	public function needsRotation( int $ttlDays = 90 ): bool {
		return $this->rotatedAt->modify( "+{$ttlDays} days" ) < new DateTimeImmutable();
	}

	/** Convenience accessors */
	public function auth(): KeyPair {
		return $this->auth; }
	public function secureAuth(): KeyPair {
		return $this->secureAuth; }
}
