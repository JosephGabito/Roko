<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\Entity;

use JosephG\Roko\Domain\Security\UserSecurity\ValueObject\Username;
use JosephG\Roko\Domain\Security\UserSecurity\ValueObject\FailedLoginCount;

final class UserSecurityProfile {

	public function __construct(
		public Username $adminUsername,
		public FailedLoginCount $failedLoginCount,
	) {}

	public function isDefaultAdmin(): bool {
		return $this->adminUsername->isDefaultAdmin();
	}
}
