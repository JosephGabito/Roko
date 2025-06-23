<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use JosephG\Roko\Domain\Security\FileIntegrity\Repository\FileIntegrityRepositoryInterface;
use JosephG\Roko\Domain\Security\FileIntegrity\Entity\IntegrityScan;

final class WpFileIntegrityRepository implements FileIntegrityRepositoryInterface {

	public function latestScan(): IntegrityScan {
		// naive: core files intact if no modifications in wp-admin / wp-includes
		$coreFolders = array( 'wp-admin', 'wp-includes' );
		$modified    = false;
		foreach ( $coreFolders as $folder ) {
			foreach ( new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( ABSPATH . $folder ) ) as $file ) {
				if ( $file->isFile() && $file->getMTime() > ( time() - 86400 ) ) { // changed last 24h
					$modified = true;
					break 2;
				}
			}
		}
		return new IntegrityScan( ! $modified, 0, new \DateTimeImmutable() );
	}
}
