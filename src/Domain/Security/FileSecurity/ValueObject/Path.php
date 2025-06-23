<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final readonly class Path {

	public function __construct( public string $path ) {}
}
