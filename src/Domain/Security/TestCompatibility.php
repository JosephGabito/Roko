<?php

namespace JosephG\Roko\Domain\Security;

/**
 * Test class to check PHP compatibility.
 */
class Test_Compatibility {

    /**
     * This function uses PHP 8.0+ match expression.
     * Will fail PHP 7.4 compatibility check.
     */
    public function get_security_level( $score ) {
        return match( $score ) {
            0, 1, 2 => 'low',
            3, 4, 5 => 'medium',
            6, 7, 8 => 'high',
            default => 'unknown'
        };
    }

    /**
     * This function uses PHP 8.0+ nullsafe operator.
     * Will fail PHP 7.4 compatibility check.
     */
    public function get_user_data( $user ) {
        return $user?->profile?->email ?? 'unknown';
    }
} 