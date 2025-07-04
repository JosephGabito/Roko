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
use JosephG\Roko\Domain\Security\Checks\FileIntegrityChecks;
use JosephG\Roko\Domain\Security\Checks\VulnerabilityChecks;
use JosephG\Roko\Domain\Security\Scoring\SecurityScoring;

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
		$securityKeysChecks  = SecurityKeysChecks::fromSecurityKeys( $keys );
		$fileSecurityChecks  = FileSecurityChecks::fromFilePermission( $fileSecurity );
		$fileIntegrityChecks = FileIntegrityChecks::fromIntegrityScan( $integrityScan );
		$vulnerabilityChecks = VulnerabilityChecks::fromVulnerabilityCollection( $vulns );

		// Calculate scores for each section
		$securityKeysScore  = SecurityScoring::calculateSectionScore( $securityKeysChecks->getChecks() );
		$fileSecurityScore  = SecurityScoring::calculateSectionScore( $fileSecurityChecks->getChecks() );
		$fileIntegrityScore = SecurityScoring::calculateSectionScore( $fileIntegrityChecks->getChecks() );
		$vulnerabilityScore = SecurityScoring::calculateSectionScore( $vulnerabilityChecks->getChecks() );

		// Calculate overall site score
		$sectionScores = array( $securityKeysScore, $fileSecurityScore, $fileIntegrityScore, $vulnerabilityScore );
		$siteScore     = SecurityScoring::calculateSiteScore( $sectionScores );

		return array(
			'meta'     => array(
				'generatedAt' => ( new \DateTimeImmutable() )->format( \DateTimeInterface::ATOM ),
				'rokoVersion' => ROKO_PLUGIN_VERSION,
				'score'       => $siteScore,
			),
			'sections' => array(
				array(
					'id'          => 'security_keys',
					'title'       => 'Security Keys & Salts',
					'description' => $keys->getSectionSummary()['description'],
					'score'       => array(
						'value' => $securityKeysScore['value'],
						'max'   => $securityKeysScore['max'],
					),
					'checks'      => $securityKeysChecks->toArray(),
				),
				array(
					'id'          => 'file_security',
					'title'       => 'File Security',
					'description' => $fileSecurity->getSectionSummary()['description'],
					'score'       => array(
						'value' => $fileSecurityScore['value'],
						'max'   => $fileSecurityScore['max'],
					),
					'checks'      => $fileSecurityChecks->toArray(),
				),
				array(
					'id'          => 'file_integrity',
					'title'       => 'File Integrity',
					'description' => $integrityScan->getSectionSummary()['description'],
					'score'       => array(
						'value' => $fileIntegrityScore['value'],
						'max'   => $fileIntegrityScore['max'],
					),
					'checks'      => $fileIntegrityChecks->toArray(),
				),
				array(
					'id'          => 'known_vulnerabilities',
					'title'       => 'Known Vulnerabilities',
					'description' => $vulns->getSectionSummary()['description'],
					'score'       => array(
						'value' => $vulnerabilityScore['value'],
						'max'   => $vulnerabilityScore['max'],
					),
					'checks'      => $vulnerabilityChecks->toArray(),
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
