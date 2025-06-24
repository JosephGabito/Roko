<?php
namespace JosephG\Roko\Infrastructure\WordPress\Security;

use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeysProviderInterface;
use JosephG\Roko\Domain\Security\SecurityKeys\Entity\SecurityKeys;
use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;

final class WpSecurityKeysProvider implements SecurityKeysProviderInterface {

    public function snapshot(): SecurityKeys {

        $wpConfigAuthKey = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
        $wpConfigSecureAuthKey = defined( 'SECURE_AUTH_KEY' ) ? SECURE_AUTH_KEY : '';
        $wpConfigLoggedInKey = defined( 'LOGGED_IN_KEY' ) ? LOGGED_IN_KEY : '';
        $wpConfigNonceKey = defined( 'NONCE_KEY' ) ? NONCE_KEY : '';
        $wpConfigAuthSalt = defined( 'AUTH_SALT' ) ? AUTH_SALT : '';
        $wpConfigSecureAuthSalt = defined( 'SECURE_AUTH_SALT' ) ? SECURE_AUTH_SALT : '';
        $wpConfigLoggedInSalt = defined( 'LOGGED_IN_SALT' ) ? LOGGED_IN_SALT : '';
        $wpConfigNonceSalt = defined( 'NONCE_SALT' ) ? NONCE_SALT : '';

        $authKey = new SecurityKey( $wpConfigAuthKey );
        $secureAuthKey = new SecurityKey( $wpConfigSecureAuthKey );
        $loggedInKey = new SecurityKey( $wpConfigLoggedInKey );
        $nonceKey = new SecurityKey( $wpConfigNonceKey );
        $authSalt = new SecurityKey( $wpConfigAuthSalt );
        $secureAuthSalt = new SecurityKey( $wpConfigSecureAuthSalt );
        $loggedInSalt = new SecurityKey( $wpConfigLoggedInSalt );
        $nonceSalt = new SecurityKey( $wpConfigNonceSalt );

    
        return new SecurityKeys(
           $authKey,
           $secureAuthKey,
           $loggedInKey,
           $nonceKey,
           $authSalt,
           $secureAuthSalt,
           $loggedInSalt,
           $nonceSalt
        );
    }
}