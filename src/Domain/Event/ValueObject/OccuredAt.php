<?php
namespace JosephG\Roko\Domain\Event\ValueObject;

use DateTimeImmutable;

class OccuredAt {
	public function __construct(
		public DateTimeImmutable $value
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
