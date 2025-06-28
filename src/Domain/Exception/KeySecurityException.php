<?php
namespace JosephG\Roko\Domain\Exception;

use Exception;

class KeySecurityException extends Exception {

	public function __construct( $message ) {
		return $message;
	}
}
