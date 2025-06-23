<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\Service;

use JosephG\Roko\Domain\Security\SecurityKeys\Repository\SecurityKeysRepositoryInterface;

final readonly class KeyRotationService {

	public function __construct( private SecurityKeysRepositoryInterface $repo ) {}

	public function rotateIfNeeded(): bool {
		$keys = $this->repo->current();
		if ( ! $keys->needsRotation() ) {
			return false; // nothing done
		}
		$this->repo->store( $this->repo->generateNew() );
		return true;
	}
}
