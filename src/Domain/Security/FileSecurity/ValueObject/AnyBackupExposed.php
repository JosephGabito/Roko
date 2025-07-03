<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

final class AnyBackupExposed {

	use SharedFileSecurityDescriptionTrait;

	private bool $exposed;

	public function __construct(
		bool $exposed
	) {
		$this->exposed = $exposed;
	}

	public function isExposed(): bool {
		return $this->exposed;
	}

	public function value(): bool {
		return $this->exposed;
	}
}
