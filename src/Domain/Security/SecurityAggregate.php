<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security;

use JsonException;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermissionInterface;
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
		private SecurityKeysProviderInterface $securityKeysProvider,
		private FilePermissionInterface $filePermissionProvider,
		private UserSecurityRepositoryInterface $userRepo,
		private NetworkSecurityRepositoryInterface $netRepo,
		private FileIntegrityRepositoryInterface $integrityRepo,
		private VulnerabilityRepositoryInterface $vulnRepo,
	) {}

	/**
	 * Returns a plain PHP array describing the current security posture.
	 */
	public function snapshot(): array {

		$keys          = $this->securityKeysProvider->snapshot();
		$fileSecurity  = $this->filePermissionProvider->snapshot();
		$userProfile   = $this->userRepo->currentProfile();
		$networkState  = $this->netRepo->currentState();
		$integrityScan = $this->integrityRepo->latestScan();
		$vulns         = $this->vulnRepo->latestKnown();

		$securityKeys = array_map(
			function ( $value, $key ) {
				// Get the array key.
				return array(
					'key'         => $key,
					'strength'    => $value->strength(),
					'description' => $value->description(),
				);
			},
			$keys->toArray(),
			array_keys( $keys->toArray() )
		);

		$fileSecurity = $fileSecurity->toArray();

		return array(
			'timestamp'            => ( new \DateTimeImmutable() )->format( \DateTimeInterface::ATOM ),
			'securityKeys'         => array(
				'summary'      => $keys->getSectionSummary(),
				'securityKeys' => $securityKeys,
			),
			'fileSecurity'         => array(
				'directoryListingIsOn'       => $fileSecurity['directoryListingIsOn']->value(),
				'wpDebugOn'                  => $fileSecurity['wpDebugOn']->value(),
				'editorOn'                   => $fileSecurity['editorOn']->value(),
				'dashboardInstallsOn'        => $fileSecurity['dashboardInstallsOn']->value(),
				'phpExecutionInUploadsDirOn' => $fileSecurity['phpExecutionInUploadsDirOn']->value(),
				'doesSensitiveFilesExists'   => $fileSecurity['doesSensitiveFilesExists']->value(),
				'xmlrpcOn'                   => $fileSecurity['xmlrpcOn']->value(),
				'wpConfigPermission644'      => $fileSecurity['wpConfigPermission644']->value(),
				'htAccessPermission644'      => $fileSecurity['htAccessPermission644']->value(),
				'anyBackupExposed'           => $fileSecurity['anyBackupExposed']->value(),
				'logFilesExposed'            => $fileSecurity['logFilesExposed']->value(),
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
