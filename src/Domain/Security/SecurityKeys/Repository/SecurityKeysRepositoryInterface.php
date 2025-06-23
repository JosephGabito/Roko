<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\Repository;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;

interface SecurityKeysRepositoryInterface {

	public function current(): SecurityKeys;

	/** Persist a fresh set of keys in whatever storage (DB/options/file) */
	public function store( SecurityKeys $keys ): void;

	/** Helper to build a fresh random key set */
	public function generateNew(): SecurityKeys;
}
