<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final class LogFilesExposed {

	use SharedFileSecurityDescriptionTrait;

	private $exposed;

	public function __construct(
		$exposed
	) {
		$this->exposed = $exposed;
	}

	public function isExposed() {
		return $this->exposed;
	}

	public function value() {
		return $this->exposed;
	}
}
