<?php
namespace JosephG\Roko\Domain\Security\NetworkSecurity\Repository;

use JosephG\Roko\Domain\Security\NetworkSecurity\Entity\SslCertificate;

interface NetworkSecurityRepositoryInterface {

	public function currentState(): object; // DTO: httpsEnforced, securityHeadersCount, SslCertificate etc.
}
