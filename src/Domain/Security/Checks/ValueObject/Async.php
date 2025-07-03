<?php
namespace JosephG\Roko\Domain\Security\Checks\ValueObject;

final class Async {

	private bool $isAsync;
	private string $endpoint;

	public function __construct( bool $isAsync = false, string $endpoint = '' ) {
		$this->isAsync  = $isAsync;
		$this->endpoint = $endpoint;
	}

	public function endpoint(): string {
		return $this->endpoint;
	}

	public function isAsync(): bool {
		return $this->isAsync;
	}

	public static function nope(): self {
		return new self( false, '' );
	}

	public static function yes( string $endpoint ): self {
		return new self( true, $endpoint );
	}

	public function toArray(): array {
		return array(
			'isAsync'  => $this->isAsync,
			'endpoint' => $this->endpoint,
		);
	}
}
