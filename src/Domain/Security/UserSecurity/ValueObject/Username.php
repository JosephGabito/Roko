<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\ValueObject;

final readonly class Username {

	public function __construct( public string $value ) {}

	public function isDefaultAdmin(): bool {
		return strtolower( $this->value ) === 'admin';
	}
}
