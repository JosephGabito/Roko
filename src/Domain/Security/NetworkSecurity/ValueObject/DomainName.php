<?php
namespace JosephG\Roko\Domain\Security\NetworkSecurity\ValueObject;

final readonly class DomainName {

	public function __construct( public string $value ) {}
}
