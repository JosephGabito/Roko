<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use JosephG\Roko\Domain\Security\FileSecurity\Repository\FileSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermission;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\Path;

final class WpFileSecurityRepository implements FileSecurityRepositoryInterface {

	public function currentPermissions(): object {
		$wpConfig = ABSPATH . 'wp-config.php';
		$htaccess = ABSPATH . '.htaccess';
		return (object) array(
			'wpConfig'                 => new FilePermission( new Path( $wpConfig ), substr( sprintf( '%o', @fileperms( $wpConfig ) ), -3 ) ),
			'htaccess'                 => new FilePermission( new Path( $htaccess ), substr( sprintf( '%o', @fileperms( $htaccess ) ), -3 ) ),
			'directoryListingDisabled' => ! $this->isDirectoryListingEnabled(),
			'wpDebug'                  => defined( 'WP_DEBUG' ) && WP_DEBUG,
		);
	}

	private function isDirectoryListingEnabled(): bool {
		// crude check: if .htaccess contains -Indexes, assume disabled
		$htaccess = ABSPATH . '.htaccess';
		if ( ! file_exists( $htaccess ) ) {
			return true; // uncertain, treat as enabled (risk)
		}
		return ! str_contains( file_get_contents( $htaccess ), '-Indexes' );
	}
}
