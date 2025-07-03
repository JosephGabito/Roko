<?php
namespace JosephG\Roko\Application\Security\Async;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Async;

/**
 * Application Service: Determines if security checks should run asynchronously.
 *
 * Focuses on CHECK performance characteristics, not business operation complexity.
 * Async checks show 'pending' status until AJAX completes with real results.
 */
final class AsyncDeterminationService {

	/**
	 * Mapping of expensive check types to their async endpoints.
	 */
	const ASYNC_CHECK_ENDPOINTS = array(
		// Current async checks
		'log_files' => '/wp-json/roko/v1/async/log-files-check',

		// Future async checks (commented for reference)
		// 'file_integrity_scan'     => '/wp-json/roko/v1/async/file-integrity',
		// 'malware_detection'       => '/wp-json/roko/v1/async/malware-scan',
		// 'vulnerability_check'     => '/wp-json/roko/v1/async/vulnerability-check',
	);

	/**
	 * Determine if a check should run asynchronously based on performance characteristics.
	 *
	 * @param string $checkId The check identifier
	 * @param string $businessCode The business code (unused for now, reserved for future)
	 * @param array $evidence Check evidence (unused for now, reserved for future)
	 * @return Async
	 */
	public static function determineAsync( $checkId, $businessCode, $evidence ) {
		// log_files requires two-step process:
		// 1. Fast: glob(ABSPATH.'*.log') to find potential files
		// 2. Slow: HTTP requests to test if files are actually web-accessible

		$expensiveChecks = array(
			'log_files', // Two-step: glob + HTTP accessibility test
		);

		if ( in_array( $checkId, $expensiveChecks ) ) {
			$endpoint = self::getEndpointForCheck( $checkId );
			return Async::yes( $endpoint );
		}

		// Default: Fast synchronous execution during page load
		return Async::nope();
	}

	/**
	 * Get async endpoint for a specific check ID.
	 *
	 * @param string $checkId The check identifier
	 * @return string Default endpoint if specific mapping not found
	 */
	private static function getEndpointForCheck( $checkId ) {
		// Try to find specific endpoint mapping
		foreach ( self::ASYNC_CHECK_ENDPOINTS as $pattern => $endpoint ) {
			if ( strpos( $checkId, $pattern ) !== false ) {
				return $endpoint;
			}
		}

		// Fallback to generic async endpoint
		return '/wp-json/roko/v1/async/check';
	}
}
