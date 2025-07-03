<?php
namespace JosephG\Roko\Domain\Security\Checks;

use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermission;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Check;
use JosephG\Roko\Domain\Security\Checks\ValueObject\CheckStatus;
use JosephG\Roko\Domain\Security\Checks\ValueObject\Severity;

/**
 * Domain Service: Transforms FilePermission entity into security checks.
 *
 * Emits business codes for recommendations - Application layer handles translation.
 */
final class FileSecurityChecks {

	/** @var Check[] */
	private $checks;

	public function __construct( array $checks ) {
		$this->checks = $checks;
	}

	/**
	 * Create FileSecurityChecks from FilePermission domain entity.
	 *
	 * @param FilePermission $filePermission Domain entity with file security checks.
	 */
	public static function fromFilePermission( FilePermission $filePermission ) {
		$checkMappings = self::getFileSecurityMappings();
		$checks        = array();

		foreach ( $filePermission->toArray() as $propertyName => $valueObject ) {
			if ( ! isset( $checkMappings[ $propertyName ] ) ) {
				continue; // Skip unknown checks
			}

			$mapping      = $checkMappings[ $propertyName ];
			$isVulnerable = $valueObject->value(); // Most checks: true = vulnerable

			// Some checks are inverted (false = vulnerable)
			if ( in_array( $propertyName, array( 'wpConfigPermission644', 'htAccessPermission644' ) ) ) {
				$isVulnerable = ! $isVulnerable;
			}

			$status       = self::mapVulnerabilityToStatus( $isVulnerable );
			$businessCode = $isVulnerable ? $mapping['id'] . '_vulnerable' : $mapping['id'] . '_secure';

			$checks[] = new Check(
				$mapping['id'],
				$mapping['label'],
				$status,
				self::calculateSeverity( $isVulnerable, $mapping['id'] ),
				$valueObject->description(),
				self::buildEvidence( $valueObject, $propertyName ),
				$businessCode, // Domain emits business codes
				'roko'
			);
		}

		return new self( $checks );
	}

	/**
	 * Map file security value objects to check identifiers and labels.
	 */
	private static function getFileSecurityMappings() {
		return array(
			'directoryListingIsOn'       => array(
				'id'    => 'directory_listing',
				'label' => 'Directory Listing',
			),
			'wpDebugOn'                  => array(
				'id'    => 'wp_debug',
				'label' => 'Debug Mode',
			),
			'editorOn'                   => array(
				'id'    => 'file_editor',
				'label' => 'File Editor',
			),
			'dashboardInstallsOn'        => array(
				'id'    => 'dashboard_installs',
				'label' => 'Dashboard Installs',
			),
			'phpExecutionInUploadsDirOn' => array(
				'id'    => 'php_exec_uploads',
				'label' => 'PHP Execution in Uploads',
			),
			'doesSensitiveFilesExists'   => array(
				'id'    => 'sensitive_files',
				'label' => 'Sensitive Files Exposed',
			),
			'xmlrpcOn'                   => array(
				'id'    => 'xmlrpc',
				'label' => 'XML-RPC',
			),
			'wpConfigPermission644'      => array(
				'id'    => 'wp_config_perms',
				'label' => 'wp-config.php Permissions',
			),
			'htAccessPermission644'      => array(
				'id'    => 'htaccess_perms',
				'label' => '.htaccess Permissions',
			),
			'anyBackupExposed'           => array(
				'id'    => 'backup_files',
				'label' => 'Backup Files Exposed',
			),
			'logFilesExposed'            => array(
				'id'    => 'log_files',
				'label' => 'Log Files Exposed',
			),
		);
	}

	/**
	 * Map vulnerability state to check status.
	 */
	private static function mapVulnerabilityToStatus( $isVulnerable ) {
		return $isVulnerable ? CheckStatus::fail() : CheckStatus::pass();
	}

	/**
	 * Calculate severity based on vulnerability and check type.
	 */
	private static function calculateSeverity( $isVulnerable, $checkId ) {
		if ( ! $isVulnerable ) {
			return Severity::low(); // Secure = low severity
		}

		// High-risk vulnerabilities
		$highRisk = array( 'wp_config_perms', 'htaccess_perms', 'php_exec_uploads', 'sensitive_files' );
		if ( in_array( $checkId, $highRisk ) ) {
			return Severity::high();
		}

		// Medium-risk vulnerabilities
		$mediumRisk = array( 'directory_listing', 'file_editor', 'backup_files', 'log_files' );
		if ( in_array( $checkId, $mediumRisk ) ) {
			return Severity::medium();
		}

		// Low-risk (informational)
		return Severity::low();
	}

	/**
	 * Build evidence object from value object data.
	 */
	private static function buildEvidence( $valueObject, $propertyName ) {
		return array(
			'check_type'    => $propertyName,
			'value'         => $valueObject->value(),
			'is_vulnerable' => self::isVulnerableState( $valueObject->value(), $propertyName ),
		);
	}

	/**
	 * Determine if the current state represents a vulnerability.
	 */
	private static function isVulnerableState( $value, $propertyName ) {
		// Permission checks are inverted (false = vulnerable)
		if ( in_array( $propertyName, array( 'wpConfigPermission644', 'htAccessPermission644' ) ) ) {
			return ! $value;
		}

		// Most other checks: true = vulnerable
		return $value;
	}

	/**
	 * Convert checks to array format.
	 */
	public function toArray() {
		return array_map(
			function ( Check $check ) {
				return $check->toArray();
			},
			$this->checks
		);
	}
}
