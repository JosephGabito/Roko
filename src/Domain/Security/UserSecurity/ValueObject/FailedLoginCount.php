<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\ValueObject;

final class FailedLoginCount {

	public function __construct( public int $value ) {}
}
