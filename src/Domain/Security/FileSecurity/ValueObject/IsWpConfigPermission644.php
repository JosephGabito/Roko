<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final readonly class IsWpConfigPermission644 {

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