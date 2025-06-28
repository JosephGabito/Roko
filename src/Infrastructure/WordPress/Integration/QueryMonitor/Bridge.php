<?php
namespace JosephG\Roko\Infrastructure\WordPress\Integration\QueryMonitor;

use function roko_send_event;
use function roko_get_http_post;

class Bridge {

	private static $qmDbQueries;

	public static function init() {
		// Only hook QM when profiling is explicitly enabled.
		if ( '1' === filter_input( INPUT_GET, 'roko_qm_profile' ) ) {
			add_filter( 'qm/collectors', array( self::class, 'register' ), 20, 2 );
			add_action( 'shutdown', array( self::class, 'collect' ), 99999 );
		}

		// Render our form and handle submission.
		add_action( 'roko_tools_middle', array( self::class, 'render_tools_middle' ) );
	}

	public static function register( $collectors, $qm ) {
		self::$qmDbQueries = $collectors['db_queries'];
		return $collectors;
	}

	public static function collect() {
		$data = self::$qmDbQueries->get_data();
		if ( ! $data ) {
			return;
		}

		roko_send_event(
			array(
				'site_id'     => get_current_blog_id(),
				'integration' => 'query_monitor',
				'event_type'  => 'query_monitor_collect',
				'payload'     => array( 'queries' => $data ),
				'occurred_at' => gmdate( 'c' ),
			)
		);
	}

	public static function render_tools_middle() {
		// If form was submitted, process it first:
		if ( ! empty( $_POST['roko_profiler_submit'] ) && check_admin_referer( 'roko_profiler_action', 'roko_profiler_nonce' ) ) {
			$profilerUrls = roko_get_http_post( 'roko_profiler_urls', INPUT_POST, FILTER_UNSAFE_RAW );
			$profilerCount = roko_get_http_post( 'roko_profiler_count', INPUT_POST, FILTER_VALIDATE_INT );

			echo '<div class="roko-notice roko-notice-success"><p>Profiling started:</p><ul>';
			foreach ( $profilerUrls as $url ) {
				$full = home_url( ltrim( $url, '/' ) );
				for ( $i = 0; $i < $profilerCount; $i++ ) {
					// Trigger Query Monitor with our flag
					$profile_url = add_query_arg( 'roko_qm_profile', '1', $full );
					wp_remote_get( $profile_url, array( 'timeout' => 30 ) );
					echo '<li>' . esc_html( $profile_url ) . '</li>';
				}
			}
			echo '</ul></div>';
		}

		// Always show the form
		?>
		<div class="roko-tools-middle">
			<h2>Profiler</h2>
			<form method="post">
			<?php wp_nonce_field( 'roko_profiler_action', 'roko_profiler_nonce' ); ?>
			<label>
				Pages to profile (comma-separated):<br>
				<input type="text" name="roko_profiler_urls" value="/" class="roko-input" />
			</label><br><br>
			<label>
				Iterations per URL:<br>
				<input type="number" name="roko_profiler_count" value="5" min="1" class="roko-input" />
			</label><br><br>
			<button name="roko_profiler_submit" class="roko-button">Run Profiler</button>
			</form>
		</div>
		<?php
	}
}
