<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security\I18n;

/**
 * FileIntegrityChecks recommendation strings collection.
 *
 * @package Roko
 * @subpackage Infrastructure
 * @subpackage WordPress
 * @subpackage Security
 * @subpackage I18n
 */
final class FileIntegrityChecksI18n {

	/**
	 * Get the recommendation for a given file integrity business code.
	 *
	 * @param string $businessCode Business code from domain (e.g., 'core_checksum_mismatch').
	 * @return string The recommendation for the given business code.
	 */
	public static function recommendation( $businessCode ) {
		$recommendations = self::recommendationsCollection();
		
		if ( isset( $recommendations[ $businessCode ] ) ) {
			return $recommendations[ $businessCode ];
		}
		
		// Fallback for unknown codes
		return __( 'Review file integrity findings and investigate any anomalies.', 'roko' );
	}

	/**
	 * Get the recommendations collection for file integrity business codes.
	 *
	 * @return array The recommendations collection.
	 */
	private static function recommendationsCollection() {
		return array(
			// Core Checksum
			'core_checksum_mismatch' => __( 'Core files have been modified. Reinstall WordPress or restore from backup to ensure integrity.', 'roko' ),
			'core_checksum_clean'    => __( 'Core files match WordPress.org checksums. Your installation is clean and secure.', 'roko' ),
			
			// Executable Files in Uploads
			'executable_uploads_found' => __( 'Remove executable files from uploads directory immediately. These could be malicious scripts.', 'roko' ),
			'executable_uploads_clean' => __( 'No executable files found in uploads directory. Good security practice!', 'roko' ),
			
			// Dot Files
			'dot_files_found' => __( 'Remove hidden files like .DS_Store, .git, or backup files from web-accessible directories.', 'roko' ),
			'dot_files_clean' => __( 'No suspicious hidden files detected in web-accessible areas.', 'roko' ),
			
			// Oversized Files
			'oversized_files_found' => __( 'Review large files for legitimacy. Remove debug logs and unnecessary files to optimize performance.', 'roko' ),
			'oversized_files_clean' => __( 'No oversized files detected. Your site storage is well-maintained.', 'roko' ),
			
			// Backup Folders
			'backup_folders_found' => __( 'Move backup folders outside web root or to secure cloud storage immediately.', 'roko' ),
			'backup_folders_clean' => __( 'No backup folders found in web-accessible areas. Good security practice!', 'roko' ),
			
			// Recent File Changes
			'recent_changes_detected' => __( 'Review recent file changes to ensure they are legitimate and not from unauthorized access.', 'roko' ),
			'recent_changes_clean'    => __( 'No suspicious recent file changes detected.', 'roko' ),
			
			// Malware Patterns
			'malware_patterns_found' => __( 'Malware signatures detected! Clean infected files immediately and scan your entire site.', 'roko' ),
			'malware_patterns_clean' => __( 'No malware signatures detected. Your site is clean from known threats.', 'roko' ),
		);
	}
} 