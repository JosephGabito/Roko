<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Service;

use JosephG\Roko\Domain\Security\FileSecurity\Repository\FileSecurityRepositoryInterface;

final readonly class PermissionAuditService {

	public function __construct( private FileSecurityRepositoryInterface $repo ) {}

	public function wpConfigSecure(): bool {
		$perms = $this->repo->currentPermissions();
		return $perms->wpConfig->isSecure( array( '600', '644' ) );
	}
}
