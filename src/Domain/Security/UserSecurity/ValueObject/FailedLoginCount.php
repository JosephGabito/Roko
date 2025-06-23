<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\ValueObject;

final readonly class FailedLoginCount {

	public function __construct( public int $value ) {}
}
