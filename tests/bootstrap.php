<?php
/**
 * PHPUnit bootstrap file for Roko Plugin tests.
 */

// Composer autoloader - this handles PSR-4 autoloading.
$autoloader = require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Initialize Brain Monkey for WordPress function mocking.
\Brain\Monkey\setUp();

// Set up WordPress constants if not defined.
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', '/tmp/wordpress/' );
}

if ( ! defined( 'WP_DEBUG' ) ) {
    define( 'WP_DEBUG', true );
}

if ( ! defined( 'WP_DEBUG_LOG' ) ) {
    define( 'WP_DEBUG_LOG', true );
}

// Mock WordPress functions that we commonly use.
if ( ! function_exists( 'wp_generate_password' ) ) {
    /**
     * Mock wp_generate_password function.
     */
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

// Mock other common WordPress functions.
if ( ! function_exists( 'esc_html' ) ) {
    function esc_html( $text ) {
        return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
    }
}

if ( ! function_exists( 'sanitize_text_field' ) ) {
    function sanitize_text_field( $str ) {
        return trim( strip_tags( $str ) );
    }
}

if ( ! function_exists( '__' ) ) {
    function __( $text, $domain = 'default' ) {
        return $text;
    }
}

// Register a shutdown function to clean up Brain Monkey.
register_shutdown_function( function() {
    \Brain\Monkey\tearDown();
}); 