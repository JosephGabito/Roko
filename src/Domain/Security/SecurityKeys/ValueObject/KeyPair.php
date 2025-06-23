<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\ValueObject;

final readonly class KeyPair {

	public function __construct(
		public string $key,
		public string $salt,
	) {}
}
