<?php
namespace JosephG\Roko\Domain\Security\UserSecurity\Repository;

use JosephG\Roko\Domain\Security\UserSecurity\Entity\UserSecurityProfile;

interface UserSecurityRepositoryInterface {

	public function currentProfile(): UserSecurityProfile;
}
