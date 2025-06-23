<?php
namespace JosephG\Roko\Domain\Security\KnownVulnerabilities\ValueObject;

final readonly class CvE {

	public function __construct( public string $id ) {}
}
