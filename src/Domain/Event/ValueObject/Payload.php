<?php
namespace JosephG\Roko\Domain\Event\ValueObject;

class Payload {
	public function __construct(
		public array $value
	) {
		$this->value = $value;
	}

	public function __toString() {
		return json_encode( $this->value );
	}

	public function value() {
		return $this->value;
	}
}
