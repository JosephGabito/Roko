<?php
namespace JosephG\Roko\Domain\Security\Scoring;

use JosephG\Roko\Domain\Security\Checks\ValueObject\Check;
use JosephG\Roko\Domain\Security\Checks\ValueObject\CheckStatus;

/**
 * Domain Service: Security scoring calculations.
 *
 * Implements the weight-based scoring algorithm with proper context awareness.
 */
final class SecurityScoring {

	const ALGORITHM_VERSION = '1.0.0';

	/**
	 * Default weight table by severity.
	 */
	const WEIGHTS = array(
		'critical' => 10,
		'high'     => 8,
		'medium'   => 5,
		'low'      => 2,
		'notice'   => 1,
	);

	/**
	 * Grade boundaries.
	 */
	const GRADES = array(
		90 => 'A',
		75 => 'B',
		60 => 'C',
		40 => 'D',
	);

	/**
	 * Get default weight for a Check based on its severity.
	 */
	public static function getDefaultWeight( Check $check ) {
		$severity = $check->getSeverity()->value();
		return self::WEIGHTS[ $severity ] ?? 1;
	}

	/**
	 * Get effective weight for a Check (explicit weight or default).
	 */
	public static function getEffectiveWeight( Check $check ) {
		$explicitWeight = $check->getWeight();
		return $explicitWeight !== null ? $explicitWeight : self::getDefaultWeight( $check );
	}

	/**
	 * Calculate section score from an array of Check objects.
	 *
	 * @param Check[] $checks
	 * @return array{value: int, max: int, percentage: int}
	 */
	public static function calculateSectionScore( array $checks ) {
		if ( empty( $checks ) ) {
			return array(
				'value'      => 0,
				'max'        => 0,
				'percentage' => 0,
			);
		}

		$totalWeight  = 0;
		$earnedWeight = 0;

		foreach ( $checks as $check ) {
			$weight       = self::getEffectiveWeight( $check );
			$totalWeight += $weight;

			// Failed checks contribute 0 points (not negative)
			$status = $check->getStatus()->value();
			if ( in_array( $status, array( 'pass', 'notice' ), true ) ) {
				$earnedWeight += $weight;
			}
		}

		$percentage = $totalWeight > 0 ? (int) round( 100 * $earnedWeight / $totalWeight ) : 0;

		return array(
			'value'      => $earnedWeight,
			'max'        => $totalWeight,
			'percentage' => $percentage,
		);
	}

	/**
	 * Calculate overall site score from section scores.
	 *
	 * @param array[] $sectionScores Array of section score arrays with 'percentage' key
	 * @return array{value: int, grade: string, max: int, algorithmVersion: string}
	 */
	public static function calculateSiteScore( array $sectionScores ) {
		if ( empty( $sectionScores ) ) {
			return array(
				'value'            => 0,
				'grade'            => 'F',
				'max'              => 100,
				'algorithmVersion' => self::ALGORITHM_VERSION,
			);
		}

		// Equal weight for each section
		$totalPercentage = 0;
		foreach ( $sectionScores as $sectionScore ) {
			$totalPercentage += $sectionScore['percentage'];
		}

		$averageScore = (int) round( $totalPercentage / count( $sectionScores ) );
		$grade        = self::calculateGrade( $averageScore );

		return array(
			'value'            => $averageScore,
			'grade'            => $grade,
			'max'              => 100,
			'algorithmVersion' => self::ALGORITHM_VERSION,
		);
	}

	/**
	 * Convert numeric score to letter grade.
	 */
	public static function calculateGrade( $score ) {
		foreach ( self::GRADES as $threshold => $grade ) {
			if ( $score >= $threshold ) {
				return $grade;
			}
		}
		return 'F';
	}
}
