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
		$this->AuthKey = $AuthKey;
		$this->SecureAuthKey = $SecureAuthKey;
		$this->LoggedInKey = $LoggedInKey;
		$this->NonceKey = $NonceKey;
		$this->AuthSalt = $AuthSalt;
		$this->SecureAuthSalt = $SecureAuthSalt;
		$this->LoggedInSalt = $LoggedInSalt;
		$this->NonceSalt = $NonceSalt;
	}

	public function toArray(): array {
		return [
			'AuthKey' => $this->AuthKey,
			'SecureAuthKey' => $this->SecureAuthKey,
			'LoggedInKey' => $this->LoggedInKey,
			'NonceKey' => $this->NonceKey,
			'AuthSalt' => $this->AuthSalt,
			'SecureAuthSalt' => $this->SecureAuthSalt,
			'LoggedInSalt' => $this->LoggedInSalt,
			'NonceSalt' => $this->NonceSalt,
		];
	}
}
