<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

final class LogFilesExposed {

	use SharedFileSecurityDescriptionTrait;

	private Async $async;

	public function __construct(
		Async $async
	) {
		$this->async = $async;
	}

	public function value(): Async {
		return $this->async;
	}

	public function async(): Async {
		return $this->async;
	}
}
