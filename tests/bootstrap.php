<?php
/**
 * PHPUnit bootstrap file for Roko Plugin tests.
 */

// Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Initialize Brain Monkey for WordPress function mocking.
\Brain\Monkey\setUp();

// Mock WordPress functions that we commonly use.
if ( ! function_exists( 'wp_generate_password' ) ) {
    function wp_generate_password( $length = 12, $special_chars = true, $extra_special_chars = false ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if ( $special_chars ) {
            $chars .= '!@#$%^&*()';
        }
        if ( $extra_special_chars ) {
            $chars .= '-_ []{}<>~`+=,.;:/?|';
        }
        
        return substr( str_shuffle( str_repeat( $chars, ceil( $length / strlen( $chars ) ) ) ), 1, $length );
    }
}

// Set up WordPress constants if not defined.
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', '/tmp/wordpress/' );
} 