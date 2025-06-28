<?php
namespace JosephG\Roko\Domain\Security\SecurityKeys\Entity;

use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;

final class SecurityKeys {

	private SecurityKey $AuthKey;
	private SecurityKey $SecureAuthKey;
	private SecurityKey $LoggedInKey;
	private SecurityKey $NonceKey;
	private SecurityKey $AuthSalt;
	private SecurityKey $SecureAuthSalt;
	private SecurityKey $LoggedInSalt;
	private SecurityKey $NonceSalt;

	private $title       = '';
	private $description = '';

	public function __construct(
		SecurityKey $AuthKey,
		SecurityKey $SecureAuthKey,
		SecurityKey $LoggedInKey,
		SecurityKey $NonceKey,
		SecurityKey $AuthSalt,
		SecurityKey $SecureAuthSalt,
		SecurityKey $LoggedInSalt,
		SecurityKey $NonceSalt
	) {
		$this->AuthKey        = $AuthKey;
		$this->SecureAuthKey  = $SecureAuthKey;
		$this->LoggedInKey    = $LoggedInKey;
		$this->NonceKey       = $NonceKey;
		$this->AuthSalt       = $AuthSalt;
		$this->SecureAuthSalt = $SecureAuthSalt;
		$this->LoggedInSalt   = $LoggedInSalt;
		$this->NonceSalt      = $NonceSalt;
	}

	public function setSectionSummary( string $title, string $description ) {
		$this->title       = $title;
		$this->description = $description;
	}

	public function getSectionSummary(): array {
		return array(
			'title'       => $this->title,
			'description' => $this->description,
		);
	}

	public function toArray(): array {
		return array(
			'Login Security'                => $this->AuthKey,
			'HTTPS Login Security'          => $this->SecureAuthKey,
			'Remember Me Security'          => $this->LoggedInKey,
			'Form Protection'               => $this->NonceKey,
			'Login Cookie Protection'       => $this->AuthSalt,
			'HTTPS Cookie Protection'       => $this->SecureAuthSalt,
			'Remember Me Cookie Protection' => $this->LoggedInSalt,
			'Form Cookie Protection'        => $this->NonceSalt,
		);
	}
}
