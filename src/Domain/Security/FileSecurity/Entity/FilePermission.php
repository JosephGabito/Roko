<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\Entity;

use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\Path;

final readonly class FilePermission {

	public function __construct(
		public Path $file,
		public string $permOctal, // e.g. "644"
	) {}

	public function isSecure( array $allowed ): bool {
		return in_array( $this->permOctal, $allowed, true );
	}
}
