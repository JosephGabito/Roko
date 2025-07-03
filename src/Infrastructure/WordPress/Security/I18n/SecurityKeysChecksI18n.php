<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * SecurityKeysChecks recommendation strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class SecurityKeysChecksI18n {

	/**
	 * Get the recommendation for a given scenario.
	 *
	 * @param string $strength Key strength (none, weak, strong).
	 * @param string $source   Key source (constant, db, roko, filter).
	 * @return string The recommendation for the given scenario.
	 */
	public static function recommendation( string $strength, string $source ): string {
		$key             = $strength . '_' . $source;
		$recommendations = self::recommendationsCollection();

		if ( isset( $recommendations[ $key ] ) ) {
			return $recommendations[ $key ];
		}

		// Fallback to generic recommendations
		$fallbacks = self::fallbackRecommendations();
		return $fallbacks[ $strength ] ?? $fallbacks['default'];
	}

	/**
	 * Get the recommendations collection for specific scenarios.
	 *
	 * @return array The recommendations collection.
	 */
	private static function recommendationsCollection(): array {
		return array(
			'none_constant'   => __( 'Add security keys to wp-config.php or let Roko generate them automatically.', 'roko' ),
			'weak_constant'   => __( 'Replace weak keys with strong 64-character secrets or enable Roko management.', 'roko' ),
			'strong_constant' => __( 'Keys look good. Consider Roko\'s auto-rotation for enhanced security.', 'roko' ),
			'strong_roko'     => __( 'Excellent! Roko is managing strong keys with automatic rotation.', 'roko' ),
			'strong_db'       => __( 'WordPress-generated keys are strong. Consider upgrading to Roko management.', 'roko' ),
		);
	}

	/**
	 * Get fallback recommendations for strength levels.
	 *
	 * @return array The fallback recommendations.
	 */
	private static function fallbackRecommendations(): array {
		return array(
			'none'    => __( 'Security keys are missing. Generate strong keys immediately.', 'roko' ),
			'weak'    => __( 'Security keys are weak. Strengthen them to protect your site.', 'roko' ),
			'strong'  => __( 'Security keys are strong. Consider regular rotation for best security.', 'roko' ),
			'default' => __( 'Review key configuration and consider strengthening security.', 'roko' ),
		);
	}
}
