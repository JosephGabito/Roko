<div class="roko-card">
	<div class="roko-card-header">
	<h3 class="roko-card-title">WP Config &amp; Environment</h3>
	<p class="roko-card-subtitle">Key constants, server settings &amp; health checks</p>
	</div>
	<div class="roko-card-body">
	<table class="roko-table">
		<thead>
		<tr><th>Setting</th><th>Value</th><th>Status</th></tr>
		</thead>
		<tbody>
		<!-- General Settings -->
		<tr class="roko-group-header"><td colspan="3"><strong>General Settings</strong></td></tr>
		<tr>
			<td>WP_ENV</td>
			<td><?php echo defined( 'WP_ENV' ) ? esc_html( WP_ENV ) : '<em>not set</em>'; ?></td>
			<td><span class="roko-status <?php echo ( defined( 'WP_ENV' ) && WP_ENV === 'production' ) ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo ( defined( 'WP_ENV' ) && WP_ENV === 'production' ) ? 'Production' : 'Non-prod'; ?></span></td>
		</tr>
		<tr>
			<td>WP_DEBUG</td>
			<td><?php echo WP_DEBUG ? 'true' : 'false'; ?></td>
			<td><span class="roko-status <?php echo WP_DEBUG ? 'roko-status-warning' : 'roko-status-success'; ?>">
			<span class="roko-status-dot"></span> <?php echo WP_DEBUG ? 'Debug On' : 'OK'; ?></span></td>
		</tr>
		<tr>
			<td>WP Version</td>
			<td><?php echo esc_html( get_bloginfo( 'version' ) ); ?></td>
			<td><span class="roko-status roko-status-success">
			<span class="roko-status-dot"></span> Up to date</span></td>
		</tr>

		<!-- PHP Settings -->
		<tr class="roko-group-header"><td colspan="3"><strong>PHP Settings</strong></td></tr>
		<tr>
			<td>PHP Version</td>
			<td><?php echo esc_html( PHP_VERSION ); ?></td>
			<td><span class="roko-status <?php echo version_compare( PHP_VERSION, '7.4', '>=' ) ? 'roko-status-success' : 'roko-status-error'; ?>">
			<span class="roko-status-dot"></span> <?php echo version_compare( PHP_VERSION, '7.4', '>=' ) ? 'Supported' : 'Upgrade'; ?></span></td>
		</tr>
		<tr>
			<td>memory_get_usage()</td>
			<td>
			<?php
			$u = memory_get_usage( true );
			echo size_format( $u );
			?>
			</td>
			<td><span class="roko-status <?php echo $u < wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) * 0.8 ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo $u < wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) * 0.8 ? 'OK' : 'High'; ?></span></td>
		</tr>
		<tr>
			<td>upload_max_filesize</td>
			<td><?php echo ini_get( 'upload_max_filesize' ); ?></td>
			<td><span class="roko-status <?php echo (int) ini_get( 'upload_max_filesize' ) >= 10 ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo (int) ini_get( 'upload_max_filesize' ) >= 10 ? 'OK' : 'Increase'; ?></span></td>
		</tr>
		<tr>
			<td>max_execution_time</td>
			<td><?php echo ini_get( 'max_execution_time' ); ?>s</td>
			<td><span class="roko-status <?php echo ini_get( 'max_execution_time' ) >= 30 ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo ini_get( 'max_execution_time' ) >= 30 ? 'OK' : 'Low'; ?></span></td>
		</tr>
		<tr>
			<td>Disabled PHP Functions</td>
			<td><?php echo esc_html( ini_get( 'disable_functions' ) ?: 'None' ); ?></td>
			<td><span class="roko-status <?php echo ini_get( 'disable_functions' ) ? 'roko-status-warning' : 'roko-status-success'; ?>">
			<span class="roko-status-dot"></span> <?php echo ini_get( 'disable_functions' ) ? 'Review' : 'OK'; ?></span></td>
		</tr>
		<tr>
			<td>PHP Extensions</td>
			<td><?php echo esc_html( implode( ', ', get_loaded_extensions() ) ); ?></td>
			<td><span class="roko-status roko-status-info">
			<span class="roko-status-dot"></span> Info</span></td>
		</tr>

		<!-- Database Settings -->
		<tr class="roko-group-header"><td colspan="3"><strong>Database Settings</strong></td></tr>
		<tr>
			<td>MySQL Version</td>
			<td>
			<?php
			global $wpdb;
			echo esc_html( $wpdb->db_version() );
			?>
			</td>
			<td><span class="roko-status <?php echo version_compare( $wpdb->db_version(), '5.7', '>=' ) ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo version_compare( $wpdb->db_version(), '5.7', '>=' ) ? 'OK' : 'Upgrade'; ?></span></td>
		</tr>
		<tr>
			<td>MySQL Extensions</td>
			<td>
			<?php
			echo esc_html(
				implode(
					', ',
					array_filter(
						get_loaded_extensions(),
						function ( $e ) {
							return stripos( $e, 'mysql' ) !== false;
						}
					)
				)
			);
			?>
			</td>
			<td><span class="roko-status roko-status-info">
			<span class="roko-status-dot"></span> Info</span></td>
		</tr>

		<!-- Filesystem & Cron -->
		<tr class="roko-group-header"><td colspan="3"><strong>Filesystem &amp; Cron</strong></td></tr>
		<tr>
			<td>Disk I/O Test (1MB Write)</td>
			<td>
			<?php
			$start = microtime( true );
			file_put_contents( WP_CONTENT_DIR . '/roko-test.tmp', str_repeat( '0', 1024 * 1024 ) );
			$dur = microtime( true ) - $start;
			unlink( WP_CONTENT_DIR . '/roko-test.tmp' );
			echo round( $dur * 1000, 2 ) . ' ms';
			?>
			</td>
			<td><span class="roko-status <?php echo $dur < 0.05 ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo $dur < 0.05 ? 'Fast' : 'Slow'; ?></span></td>
		</tr>
		<tr>
			<td>Last WP-Cron Run</td>
			<td>
			<?php
			$last = get_option( 'last_cron_run', 0 );
			echo $last ? date_i18n( 'M j, Y g:i A', $last ) : '<em>Never</em>';
			?>
			</td>
			<td><span class="roko-status <?php echo time() - $last < 3600 ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo time() - $last < 3600 ? 'Recent' : 'Stale'; ?></span></td>
		</tr>

		<!-- Network & API -->
		<tr class="roko-group-header"><td colspan="3"><strong>Network &amp; API</strong></td></tr>
		<tr>
			<td>REST API Endpoint</td>
			<td>
			<?php
			$resp = wp_remote_head( home_url( '/wp-json/' ) );
			echo wp_remote_retrieve_response_code( $resp );
			?>
			</td>
			<td><span class="roko-status <?php echo ( wp_remote_retrieve_response_code( $resp ) === 200 ) ? 'roko-status-success' : 'roko-status-error'; ?>">
			<span class="roko-status-dot"></span> <?php echo ( wp_remote_retrieve_response_code( $resp ) === 200 ) ? 'OK' : 'Error'; ?></span></td>
		</tr>
		<tr>
			<td>XML Sitemap</td>
			<td>
			<?php
			$sresp = wp_remote_head( home_url( '/sitemap_index.xml' ) );
			echo wp_remote_retrieve_response_code( $sresp );
			?>
			</td>
			<td><span class="roko-status <?php echo ( wp_remote_retrieve_response_code( $sresp ) === 200 ) ? 'roko-status-success' : 'roko-status-warning'; ?>">
			<span class="roko-status-dot"></span> <?php echo ( wp_remote_retrieve_response_code( $sresp ) === 200 ) ? 'Accessible' : 'Missing'; ?></span></td>
		</tr>
		</tbody>
	</table>
	<div class="roko-mt-4">
		<button class="roko-button roko-button-outline" id="roko-download-phpinfo">Download phpinfo()</button>
	</div>
	</div>
</div>
