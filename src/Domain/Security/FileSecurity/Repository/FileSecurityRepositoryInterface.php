<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Repository;

use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermission;

interface FileSecurityRepositoryInterface {

	public function currentPermissions(): object; // return DTO with multiple perms
}
