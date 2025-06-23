<?php
namespace JosephG\Roko\Infrastructure\WordPress\Repository;

use JosephG\Roko\Domain\Security\UserSecurity\Repository\UserSecurityRepositoryInterface;
use JosephG\Roko\Domain\Security\UserSecurity\Entity\UserSecurityProfile;
use JosephG\Roko\Domain\Security\UserSecurity\ValueObject\Username;
use JosephG\Roko\Domain\Security\UserSecurity\ValueObject\FailedLoginCount;

final class WpUserSecurityRepository implements UserSecurityRepositoryInterface {

	public function currentProfile(): UserSecurityProfile {
		global $wpdb;
		// failed logins meta key example (depends on plugin); fallback 0
		$failed = (int) get_site_option( 'roko_failed_login_24h', 0 );
		$admin  = get_user_by( 'id', 1 );

		return new UserSecurityProfile(
			new Username( $admin ? $admin->user_login : 'admin' ),
			new FailedLoginCount( $failed )
		);
	}
}
