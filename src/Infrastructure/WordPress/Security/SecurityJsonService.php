<?php
/**
 * Infrastructure → WordPress
 * Security JSON Service
 *
 * Registers a REST endpoint (`/wp-json/roko/v1/security`) that returns the
 * domain aggregate snapshot in JSON. Serves as the thin adapter layer between
 * WordPress and your application (the SecurityAggregate).
 */

namespace JosephG\Roko\Infrastructure\WordPress\Security;

use WP_REST_Request;
use WP_REST_Server;
use JosephG\Roko\Infrastructure\WordPress\Security\SaltVault;
use JosephG\Roko\Domain\Security\SecurityAggregate;

final class SecurityJsonService {

	private SecurityAggregate $aggregate;

	public function __construct( SecurityAggregate $aggregate ) {
		$this->aggregate = $aggregate;
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * REST route: GET /wp-json/roko/v1/security
	 */
	public function register_routes(): void {
		register_rest_route(
			'roko/v1',
			'/security',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'permissions_check' ),
				'callback'            => array( $this, 'handle_request' ),
			)
		);

		register_rest_route(
			'roko/v1',
			'/security/regenerate-salts',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'permissions_check' ),
				'callback'            => array( $this, 'regenerate_salts' ),
			)
		);

		register_rest_route(
			'roko/v1',
			'/security/disable-roko-salts',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'permission_callback' => array( $this, 'permissions_check' ),
				'callback'            => array( $this, 'disable_roko_salts' ),
			)
		);
	}

	/**
	 * Only site admins get the raw security JSON.
	 * You can swap to a custom capability if needed.
	 */
	public function permissions_check(): bool {
		return current_user_can( 'manage_options' );
	}

	/**
	 * POST /wp-json/roko/v1/security/salts/rotate
	 */
	public function regenerate_salts( WP_REST_Request $request ) {

		// 1 Security: capability + nonce (header sent by wp.apiFetch)
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 'roko_forbidden', __( 'Sorry, you are not allowed to rotate salts.' ), array( 'status' => 403 ) );
		}

		if ( ! wp_verify_nonce( $request->get_header( 'x_wp_nonce' ), 'wp_rest' ) ) {
			return new \WP_Error( 'roko_invalid_nonce', __( 'Nonce check failed.' ), array( 'status' => 403 ) );
		}

		// 2 Generate a fresh 64-char key + salt per scheme
		$schemes = array( 'auth', 'secure_auth', 'logged_in', 'nonce' );
		$payload = array();

		foreach ( $schemes as $s ) {
			$payload[ $s ] = array(
				wp_generate_password( 64, true, true ), // key
				wp_generate_password( 64, true, true ), // salt
			);
		}

		// 3 Persist in the encrypted vault
		try {
			SaltVault::put( $payload );
		} catch ( \Throwable $e ) {
			error_log( 'Roko SaltVault error: ' . $e->getMessage() );
			return new \WP_Error(
				'roko_vault_write_failed',
				__( 'Could not store new salts. Check file-system permissions or server error log.' ),
				array( 'status' => 422 )
			);
		}

		// 4 Track when salts were last rotated
		update_option( 'roko_salts_last_rotated', current_time( 'timestamp' ) );

		// 5 Return JSON; front-end can redirect or toast as it likes
		return rest_ensure_response(
			array(
				'rotated'   => true,
				'schemes'   => array_keys( $payload ),
				'nextLogin' => wp_login_url(),
				'rotatedAt' => current_time( 'timestamp' ),
			)
		)->set_status( 201 ); // Created
	}

	/**
	 * Disable Roko salt management and revert to WordPress defaults.
	 */
	public function disable_roko_salts( WP_REST_Request $request ) {

		// Security: capability + nonce
		if ( ! current_user_can( 'manage_options' ) ) {
			return new \WP_Error( 'roko_forbidden', __( 'Sorry, you are not allowed to disable Roko salt management.' ), array( 'status' => 403 ) );
		}

		if ( ! wp_verify_nonce( $request->get_header( 'x_wp_nonce' ), 'wp_rest' ) ) {
			return new \WP_Error( 'roko_invalid_nonce', __( 'Nonce check failed.' ), array( 'status' => 403 ) );
		}

		// Check if Roko salts exist
		if ( ! SaltVault::get() && ! get_option( 'roko_salts_last_rotated' ) ) {
			return new \WP_Error(
				'roko_no_salts_found',
				__( 'No Roko-managed salts found. Nothing to disable.' ),
				array( 'status' => 404 )
			);
		}

		// Clear Roko's salt vault and tracking
		try {
			SaltVault::clear();
			delete_option( 'roko_salts_last_rotated' );
		} catch ( \Throwable $e ) {
			error_log( 'Roko SaltVault clear error: ' . $e->getMessage() );
			return new \WP_Error(
				'roko_vault_clear_failed',
				__( 'Could not clear Roko salt management. Check server error log.' ),
				array( 'status' => 422 )
			);
		}

		return rest_ensure_response(
			array(
				'disabled'  => true,
				'message'   => __( 'Roko salt management disabled. WordPress will use default salts.' ),
				'nextLogin' => wp_login_url(),
			)
		)->set_status( 200 );
	}

	/**
	 * Returns the security snapshot.
	 */
	public function handle_request( WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
		try {
			return rest_ensure_response( $this->aggregate->snapshot() );
		} catch ( \Throwable $e ) {
			// Enhanced error logging
			error_log( 'Roko Security Error: ' . $e->getMessage() );
			error_log( 'File: ' . $e->getFile() . ' Line: ' . $e->getLine() );
			error_log( 'Stack trace: ' . $e->getTraceAsString() );

			return new \WP_Error(
				'roko_security_error',
				'Security data collection failed: ' . $e->getMessage(),
				array( 'status' => 422 )
			);
		}
	}
}
