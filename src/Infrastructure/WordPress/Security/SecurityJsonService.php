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
	}

	/**
	 * Only site admins get the raw security JSON.
	 * You can swap to a custom capability if needed.
	 */
	public function permissions_check(): bool {
		return current_user_can( 'manage_options' );
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
