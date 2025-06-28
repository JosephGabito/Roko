<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Entity;

interface FilePermissionInterface {

	public function snapshot(): FilePermission;
}
