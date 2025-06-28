<?php
namespace JosephG\Roko\Domain\Security\FileSecurity\ValueObject;

trait SharedFileSecurityDescriptionTrait {

	private $description;

	public function setDescription( $description ) {
		if ( ! is_scalar( $description ) ) {
			throw new \Exception( 'Description must be of scalar value.' );
		}
		$this->description = $description;
	}

	public function description() {
		return $this->description;
	}
}
