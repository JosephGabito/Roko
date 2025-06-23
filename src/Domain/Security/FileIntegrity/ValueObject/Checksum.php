<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

final readonly class Checksum {

	public function __construct( public string $value ) {}
}
