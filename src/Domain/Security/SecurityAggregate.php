<?php
declare(strict_types=1);

namespace JosephG\Roko\Domain\Security;

use JsonException;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermissionInterface;
use JosephG\Roko\Domain\Security\FileIntegrity\Repository\FileIntegrityRepositoryInterface;
use JosephG\Roko\Domain\Security\KnownVulnerabilities\Repository\VulnerabilityRepositoryInterface;
use JosephG\Roko\Domain\Security\Checks\SecurityKeysChecks;
use JosephG\Roko\Domain\Security\Checks\FileSecurityChecks;

/**
 * Aggregate Root: combines all security sub-domain snapshots.
 *
 * Keep this class free of WordPress calls—inject repositories instead.
 */
final class SecurityAggregate {

	/** @var SecurityKeysProviderInterface */
	private $securityKeysProvider;

	/** @var FilePermissionInterface */
	private $filePermissionProvider;

	/** @var FileIntegrityRepositoryInterface */
	private $integrityRepo;

	/** @var VulnerabilityRepositoryInterface */
	private $vulnRepo;

	public function __construct(
		SecurityKeysProviderInterface $securityKeysProvider,
		FilePermissionInterface $filePermissionProvider,
		FileIntegrityRepositoryInterface $integrityRepo,
		VulnerabilityRepositoryInterface $vulnRepo
	) {
		$this->securityKeysProvider   = $securityKeysProvider;
		$this->filePermissionProvider = $filePermissionProvider;
		$this->integrityRepo          = $integrityRepo;
		$this->vulnRepo               = $vulnRepo;
	}

	/**
	 * Returns a plain PHP array describing the current security posture.
	 * 
	 * Domain emits business codes - Application layer handles translation.
	 */
	public function snapshot() {

		$keys          = $this->securityKeysProvider->snapshot();
		$fileSecurity  = $this->filePermissionProvider->snapshot();
		$integrityScan = $this->integrityRepo->latestScan();
		$vulns         = $this->vulnRepo->latestKnown();

		// Domain logic - emits business codes, not translated text
		$securityKeysChecks = SecurityKeysChecks::fromSecurityKeys( $keys );
		$fileSecurityChecks = FileSecurityChecks::fromFilePermission( $fileSecurity );

		return array(
			'meta'     => array(
				'generatedAt' => ( new \DateTimeImmutable() )->format( \DateTimeInterface::ATOM ),
				'rokoVersion' => ROKO_PLUGIN_VERSION,
			),
			'sections' => array(
				array(
					'id'          => 'security_keys',
					'title'       => 'Security Keys & Salts',
					'description' => $keys->getSectionSummary()['description'],
					'checks'      => $securityKeysChecks->toArray(),
				),
				array(
					'id'          => 'file_security',
					'title'       => 'File Security',
					'description' => $fileSecurity->getSectionSummary()['description'],
					'checks'      => $fileSecurityChecks->toArray(),
				),
				array(
					'id'          => 'file_integrity',
					'title'       => 'File Integrity',
					'description' => $integrityScan->getSectionSummary()['description'],
					'checks'      => array(), // TODO: Create FileIntegrityChecks sub-aggregate
				),
				array(
					'id'          => 'known_vulnerabilities',
					'title'       => 'Known Vulnerabilities',
					'description' => $vulns->getSectionSummary()['description'],
					'checks'      => array(), // TODO: Create VulnerabilityChecks sub-aggregate
				),
			),
		);
	}

	/**
	 * JSON representation—throws JsonException on encoding failure.
	 *
	 * @throws JsonException
	 */
	public function toJson( $options = 0 ) {
		return json_encode( $this->snapshot(), JSON_THROW_ON_ERROR | $options );
	}
}
