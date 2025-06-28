<?php
namespace JosephG\Roko\Domain\Security\KnownVulnerabilities\ValueObject;

final class CvE {

	public function __construct( public string $id ) {}
}
