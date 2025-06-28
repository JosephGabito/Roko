<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final class IsHtAccessPermission644 {

	use SharedFileSecurityDescriptionTrait;

	private bool $is644;

	public function __construct(
		bool $is644
	) {
		$this->is644 = $is644;
	}

	public function is644(): bool {
		return $this->is644;
	}

	public function value(): bool {
		return $this->is644;
	}
}
