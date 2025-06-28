<?php
declare(strict_types=1);

namespace JosephG\Roko\Tests\Unit\Domain\Security\SecurityKeys\ValueObject;

use JosephG\Roko\Domain\Security\SecurityKeys\ValueObject\SecurityKey;
use PHPUnit\Framework\TestCase;
use Brain\Monkey;

/**
 * Test class for SecurityKey value object.
 */
class SecurityKeyTest extends TestCase {

    protected function setUp(): void {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void {
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * Test empty key returns 'none' strength.
     */
    public function test_empty_key_returns_none_strength(): void {
        $key = new SecurityKey( '', 'Test Key' );
        
        $this->assertEquals( 'none', $key->strength() );
        $this->assertTrue( $key->isEmpty() );
        $this->assertFalse( $key->isStrong() );
        $this->assertFalse( $key->isWeak() );
    }

    /**
     * Test weak key returns 'weak' strength.
     */
    public function test_weak_key_returns_weak_strength(): void {
        $weak_key = 'password123'; // Too short, no special chars
        $key = new SecurityKey( $weak_key, 'Test Key' );
        
        $this->assertEquals( 'weak', $key->strength() );
        $this->assertFalse( $key->isEmpty() );
        $this->assertFalse( $key->isStrong() );
        $this->assertTrue( $key->isWeak() );
    }

    /**
     * Test strong key returns 'strong' strength.
     */
    public function test_strong_key_returns_strong_strength(): void {
        // Create a strong key: long enough, has all character classes, high entropy
        $strong_key = 'Th1s_1s_4_V3ry_Str0ng_P4ssw0rd_W1th_Sp3c14l_Ch4rs!@#$%^&*()_+{}|:<>?[]\\;\'",./`~';
        $key = new SecurityKey( $strong_key, 'Test Key' );
        
        $this->assertEquals( 'strong', $key->strength() );
        $this->assertFalse( $key->isEmpty() );
        $this->assertTrue( $key->isStrong() );
        $this->assertFalse( $key->isWeak() );
    }

    /**
     * Test key description and value getters.
     */
    public function test_key_getters(): void {
        $key_value = 'test_key_value';
        $description = 'Test Description';
        $key = new SecurityKey( $key_value, $description );
        
        $this->assertEquals( $key_value, $key->value() );
        $this->assertEquals( $description, $key->description() );
        $this->assertEquals( $key_value, (string) $key );
    }

    /**
     * Test generated key is strong.
     */
    public function test_generated_key_is_strong(): void {
        // Mock wp_generate_password to return a known strong password
        Monkey\Functions\when( 'wp_generate_password' )->justReturn(
            'Th1s_1s_4_V3ry_Str0ng_G3n3r4t3d_P4ssw0rd_W1th_Sp3c14l_Ch4rs!@#$%^&*()'
        );
        
        $key = SecurityKey::generate();
        
        $this->assertEquals( 'strong', $key->strength() );
        $this->assertTrue( $key->isStrong() );
        $this->assertEquals( 'WordPress Auth Key', $key->description() );
    }

    /**
     * Test generated key without WordPress function.
     */
    public function test_generated_key_without_wordpress(): void {
        // Ensure wp_generate_password doesn't exist for this test
        Monkey\Functions\when( 'function_exists' )
            ->with( 'wp_generate_password' )
            ->justReturn( false );
        
        $key = SecurityKey::generate();
        
        // Should still generate a valid key using random_bytes fallback
        $this->assertNotEmpty( $key->value() );
        $this->assertEquals( 'WordPress Auth Key', $key->description() );
    }

    /**
     * Test key with insufficient character classes is weak.
     */
    public function test_key_without_all_character_classes_is_weak(): void {
        // Long enough but missing special characters
        $key_value = 'ThisIsALongPasswordButItOnlyHasLettersAndNumbers123456789012345';
        $key = new SecurityKey( $key_value, 'Test Key' );
        
        $this->assertEquals( 'weak', $key->strength() );
        $this->assertTrue( $key->isWeak() );
    }

    /**
     * Test key that's too short is weak.
     */
    public function test_short_key_is_weak(): void {
        // Has all character classes but too short
        $key_value = 'Abc1!';
        $key = new SecurityKey( $key_value, 'Test Key' );
        
        $this->assertEquals( 'weak', $key->strength() );
        $this->assertTrue( $key->isWeak() );
    }

    /**
     * Test key with low entropy is weak.
     */
    public function test_low_entropy_key_is_weak(): void {
        // Long enough, has character classes, but low entropy (repetitive)
        $key_value = 'Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!Aa1!';
        $key = new SecurityKey( $key_value, 'Test Key' );
        
        $this->assertEquals( 'weak', $key->strength() );
        $this->assertTrue( $key->isWeak() );
    }
} 