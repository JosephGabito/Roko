<?php
namespace JosephG\Roko\Domain\Security\FileIntegrity\ValueObject;

final class Checksum {

	public function __construct( public string $value ) {}
}
