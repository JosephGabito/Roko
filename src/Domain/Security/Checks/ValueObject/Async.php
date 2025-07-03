<?php
namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

final class Async {

	private $isAsync;
	private $endpoint;

	public function __construct( $isAsync = false, $endpoint = '' ) {
		$this->isAsync  = $isAsync;
		$this->endpoint = $endpoint;
	}

	public function endpoint() {
		return $this->endpoint;
	}

	public function isAsync() {
		return $this->isAsync;
	}

	public static function nope() {
		return new self( false, '' );
	}

	public static function yes( $endpoint ) {
		return new self( true, $endpoint );
	}

	public function toArray() {
		return array(
			'isAsync'  => $this->isAsync,
			'endpoint' => $this->endpoint,
		);
	}
}
