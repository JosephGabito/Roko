<?php
namespace JosephG\Roko\Domain\Event\ValueObject;

class Integration {
	public function __construct(
		public string $value
	) {
		$this->value = $value;
	}

	public function __toString() {
		return $this->value;
	}

	public function value() {
		return $this->value;
	}
}
