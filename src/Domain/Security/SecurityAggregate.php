<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security;

use JsonException;
use JosephG\Roko\Domain\Security\SecurityKeys\Repository\SecurityKeysRepositoryInterface;
use JosephG\Roko\Domain\Security\FileSecurity\Repository\FileSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\UserSecurity\Repository\UserSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\NetworkSecurity\Repository\NetworkSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\FileIntegrity\Repository\FileIntegrityRepositoryInterface;
use JosephG\Roko\Domain\Security\KnownVulnerabilities\Repository\VulnerabilityRepositoryInterface;

/**
 * Aggregate Root: combines all security sub-domain snapshots.
 *
 * Keep this class free of WordPress calls—inject repositories instead.
 */
final readonly class SecurityAggregate {

	public function __construct(
		private SecurityKeysRepositoryInterface $keysRepo,
		private FileSecurityRepositoryInterface $fileRepo,
		private UserSecurityRepositoryInterface $userRepo,
		private NetworkSecurityRepositoryInterface $netRepo,
		private FileIntegrityRepositoryInterface $integrityRepo,
		private VulnerabilityRepositoryInterface $vulnRepo,
	) {}

	/**
	 * Returns a plain PHP array describing the current security posture.
	 */
	public function snapshot(): array {
		$keys          = $this->keysRepo->current();
		$filePerms     = $this->fileRepo->currentPermissions();
		$userProfile   = $this->userRepo->currentProfile();
		$networkState  = $this->netRepo->currentState();
		$integrityScan = $this->integrityRepo->latestScan();
		$vulns         = $this->vulnRepo->latestKnown();

		return array(
			'timestamp'            => ( new \DateTimeImmutable() )->format( \DateTimeInterface::ATOM ),
			'securityKeys'         => array(
				'needsRotation' => $keys->needsRotation(),
				'rotatedAt'     => $keys->rotatedAt()->format( \DateTimeInterface::ATOM ),
			),
			'fileSecurity'         => array(
				'wpConfigPerm'  => $filePerms->wpConfig,
				'htaccessPerm'  => $filePerms->htaccess,
				'dirListingOff' => $filePerms->directoryListingDisabled,
				'wpDebug'       => $filePerms->wpDebug,
			),
			'userSecurity'         => array(
				'adminUsernameRisk' => $userProfile->isDefaultAdmin(),
				'failedLogins24h'   => $userProfile->failedLoginCount,
			),
			'networkSecurity'      => array(
				'httpsEnforced' => $networkState->httpsEnforced,
				'sslValid'      => $networkState->sslValid,
				'headersScore'  => $networkState->securityHeadersCount,
			),
			'fileIntegrity'        => array(
				'coreModified'    => ! $integrityScan->coreIntact,
				'suspiciousFiles' => $integrityScan->suspiciousCount,
				'scannedAt'       => $integrityScan->scannedAt->format( \DateTimeInterface::ATOM ),
			),
			'knownVulnerabilities' => array_map(
				static fn ( $v ) => array(
					'plugin'    => $v->pluginSlug,
					'cve'       => $v->cve,
					'severity'  => $v->severity,
					'published' => $v->publishedAt->format( \DateTimeInterface::ATOM ),
				),
				$vulns
			),
		);
	}

	/**
	 * JSON representation—throws JsonException on encoding failure.
	 *
	 * @throws JsonException
	 */
	public function toJson( int $options = 0 ): string {
		return json_encode( $this->snapshot(), JSON_THROW_ON_ERROR | $options );
	}
}
