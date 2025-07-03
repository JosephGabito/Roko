<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

final class DoesSensitiveFilesExists {

	use SharedFileSecurityDescriptionTrait;

	private bool $exists;

	public function __construct(
		bool $exists,
	) {
		$this->exists = $exists;
	}

	public function exists(): bool {
		return $this->exists;
	}

	public function value(): bool {
		return $this->exists;
	}
}
