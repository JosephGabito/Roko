<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security\SecurityKeys\Entity;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;

/**
 * Provides the current set of keys/salts in a way that's storage-agnostic.
 */
interface SecurityKeysProviderInterface
{
    public function snapshot(): SecurityKeys;
}
