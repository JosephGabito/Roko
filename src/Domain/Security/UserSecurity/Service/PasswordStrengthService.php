<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\Service;

use JosephG\Roko\Domain\Security\UserSecurity\Repository\UserSecurityRepositoryInterface;

final class PasswordStrengthService {

	public function __construct( private UserSecurityRepositoryInterface $repo ) {}

	public function weakPasswordCount(): int {
		$profile = $this->repo->currentProfile();
		// placeholder – real logic in repository
		return $profile->failedLoginCount->value > 10 ? 1 : 0;
	}
}
