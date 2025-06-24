<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermission;
use JosephG\Roko\Domain\Security\FileSecurity\Entity\FilePermissionInterface;

use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDirectoryListingOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpDebugOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsEditorOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsDashboardInstallsOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsPHPExecutionInUploadsDirOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\DoesSensitiveFilesExists;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsXMLRPCOn;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsWpConfigPermission644;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\IsHtAccessPermission644;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\AnyBackupExposed;
use JosephG\Roko\Domain\Security\FileSecurity\ValueObject\LogFilesExposed;

final class WpFileSecurityProvider implements FilePermissionInterface {

    private function isWpDebugOn(): bool {
        return defined( 'WP_DEBUG' ) && WP_DEBUG;
    }

    private function isEditorOn(): bool {
        return defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT;
    }

    private function isDashboardInstallsOn(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function isPHPExecutionInUploadsDirOn(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function isDirectoryListingOn(): bool {
        return is_dir( ABSPATH ) && ! is_link( ABSPATH );
    }

    private function doesSensitiveFilesExists(): bool {
        return file_exists( ABSPATH . 'wp-config.php' ) || file_exists( ABSPATH . '.htaccess' );
    }

    private function isXMLRPCOn(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function isWpConfigPermission644(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function isHtAccessPermission644(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function anyBackupExposed(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    private function logFilesExposed(): bool {
        return defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS;
    }

    public function snapshot(): FilePermission {

        $directoryListingIsOff = new IsDirectoryListingOn( $this->isDirectoryListingOn() );

        $wpDebugOn = new IsWpDebugOn( $this->isWpDebugOn() );
        $editorOn  = new IsEditorOn( $this->isEditorOn() );
        $dashboardInstallsOn = new IsDashboardInstallsOn( $this->isDashboardInstallsOn() );
        $phpExecutionInUploadsDirOn = new IsPHPExecutionInUploadsDirOn( $this->isPHPExecutionInUploadsDirOn() );
        $doesSensitiveFilesExists = new DoesSensitiveFilesExists( $this->doesSensitiveFilesExists() );
        $xmlrpcOn = new IsXMLRPCOn( $this->isXMLRPCOn() );
        $wpConfigPermission644 = new IsWpConfigPermission644( $this->isWpConfigPermission644() );
        $htAccessPermission644 = new IsHtAccessPermission644( $this->isHtAccessPermission644() );
        $anyBackupExposed = new AnyBackupExposed( $this->anyBackupExposed() );
        $logFilesExposed = new LogFilesExposed( $this->logFilesExposed() );

        return new FilePermission(
            $directoryListingIsOff,
            $wpDebugOn,
            $editorOn,
            $dashboardInstallsOn,
            $phpExecutionInUploadsDirOn,
            $doesSensitiveFilesExists,
            $xmlrpcOn,
            $wpConfigPermission644,
            $htAccessPermission644,
            $anyBackupExposed,
            $logFilesExposed
        );
    }
}