<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

trait SharedFileSecurityDescriptionTrait {

	private $description;

	public function setDescription( $description ) {
		if ( ! is_scalar( $description ) ) {
			throw new \Exception( 'Description must be of scalar value.' );
		}
		$this->description = $description;
	}

	public function async(): Async {
		return Async::nope();
	}

	public function description() {
		return $this->description;
	}
}
