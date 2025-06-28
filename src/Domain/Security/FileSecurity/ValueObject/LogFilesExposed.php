<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final class LogFilesExposed {

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
