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
final class SecurityAggregate {

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

		$securityKeys = array();
		$keysArray    = $keys->toArray();

		if ( is_array( $keysArray ) && ! empty( $keysArray ) ) {
			$securityKeys = array_map(
				function ( $value, $key ) {
					return array(
						'key'         => $key,
						'strength'    => $value->strength(),
						'description' => $value->description(),
					);
				},
				$keysArray,
				array_keys( $keysArray )
			);
		}

		$fileSecurityArray = $fileSecurity->toArray();

		$fileSecurityArrayDump = array(
			'directoryListingIsOn'       => array(
				'description' => $fileSecurityArray['directoryListingIsOn']->description(),
				'value'       => $fileSecurityArray['directoryListingIsOn']->value(),
			),
			'wpDebugOn'                  => array(
				'description' => $fileSecurityArray['wpDebugOn']->description(),
				'value'       => $fileSecurityArray['wpDebugOn']->value(),
			),
			'editorOn'                   => array(
				'description' => $fileSecurityArray['editorOn']->description(),
				'value'       => $fileSecurityArray['editorOn']->value(),
			),
			'dashboardInstallsOn'        => array(
				'description' => $fileSecurityArray['dashboardInstallsOn']->description(),
				'value'       => $fileSecurityArray['dashboardInstallsOn']->value(),
			),
			'phpExecutionInUploadsDirOn' => array(
				'description' => $fileSecurityArray['phpExecutionInUploadsDirOn']->description(),
				'value'       => $fileSecurityArray['phpExecutionInUploadsDirOn']->value(),
			),
			'doesSensitiveFilesExists'   => array(
				'description' => $fileSecurityArray['doesSensitiveFilesExists']->description(),
				'value'       => $fileSecurityArray['doesSensitiveFilesExists']->value(),
			),
			'xmlrpcOn'                   => array(
				'description' => $fileSecurityArray['xmlrpcOn']->description(),
				'value'       => $fileSecurityArray['xmlrpcOn']->value(),
			),
			'wpConfigPermission644'      => array(
				'description' => $fileSecurityArray['wpConfigPermission644']->description(),
				'value'       => $fileSecurityArray['wpConfigPermission644']->value(),
			),
			'htAccessPermission644'      => array(
				'description' => $fileSecurityArray['htAccessPermission644']->description(),
				'value'       => $fileSecurityArray['htAccessPermission644']->value(),
			),
			'anyBackupExposed'           => array(
				'description' => $fileSecurityArray['anyBackupExposed']->description(),
				'value'       => $fileSecurityArray['anyBackupExposed']->value(),
			),
			'logFilesExposed'            => array(
				'description' => $fileSecurityArray['logFilesExposed']->description(),
				'value'       => $fileSecurityArray['logFilesExposed']->value(),
			),
		);

		return array(
			'timestamp'            => ( new \DateTimeImmutable() )->format( \DateTimeInterface::ATOM ),
			'securityKeys'         => $keys ? array(
				'summary'      => $keys->getSectionSummary(),
				'securityKeys' => $securityKeys,
			) : array(),
			'fileSecurity'         => array(
				'summary'      => $fileSecurity->getSectionSummary(),
				'fileSecurity' => $fileSecurityArrayDump,
			),
			'userSecurity'         => $userProfile ? array(
				'adminUsernameRisk' => $userProfile->isDefaultAdmin(),
				'failedLogins24h'   => $userProfile->failedLoginCount,
			) : array(),
			'networkSecurity'      => $networkState ? array(
				'httpsEnforced' => $networkState->httpsEnforced,
				'sslValid'      => $networkState->sslValid,
				'headersScore'  => $networkState->securityHeadersCount,
			) : array(),
			'fileIntegrity'        => $integrityScan ? array_merge(
				array(
					'coreModified'    => ! $integrityScan->coreIntact,
					'suspiciousFiles' => $integrityScan->suspiciousCount,
					'scannedAt'       => $integrityScan->scannedAt->format( \DateTimeInterface::ATOM ),
				),
				$integrityScan->toArray()
			) : array(),
			'knownVulnerabilities' => $vulns ? array_map(
				static fn ( $v ) => array(
					'plugin'    => $v->pluginSlug,
					'cve'       => $v->cve,
					'severity'  => $v->severity,
					'published' => $v->publishedAt->format( \DateTimeInterface::ATOM ),
				),
				$vulns
			) : array(),
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
