<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

final readonly class IsXMLRPCOn {

	private bool $isOn;

	public function __construct(
		bool $isOn
	) {
		$this->isOn = $isOn;
	}

	public function isOn(): bool {
		return $this->isOn;
	}

	public function value(): bool {
		return $this->isOn;
	}
}
