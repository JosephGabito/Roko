<?php
/**
 * -Style Admin Interface for Roko
 * 100% Compatible with Manage Dashboard patterns
 */

namespace JosephG\Roko\Infrastructure\WordPress\Admin;

class AdminPage {
	public function add_admin_page() {
		add_submenu_page(
			'index.php',
			'Intelligence',
			'Intelligence',
			'manage_options',
			'roko-admin',
			array( $this, 'render_admin_page' )
		);
		do_action( 'roko_admin_menu_registered' );
	}

	public function enqueue_admin_assets( $hook ) {
		if ( 'dashboard_page_roko-admin' !== $hook ) {
			return;
		}
		wp_enqueue_style( 'roko-admin', ROKO_PLUGIN_URL . 'assets/css/miligram.css', array(), '1.0.0' );
		wp_enqueue_script( 'roko-admin', ROKO_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), '1.0.0', true );
	}

	public function render_admin_page() {
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'overview';
		?>
		<div class="roko-admin">
			<div class="roko-container">
				<div class="roko-mb-6">
					<h1>Site Intelligence Dashboard</h1>
					<p>Comprehensive analysis and insights for <?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
				</div>

				<!-- Main Horizontal Tabs -->
				<nav class="roko-tab-nav">
					<?php
					$tabs = array(
						'overview'    => 'Overview',
						'performance' => 'Performance',
						'security'    => 'Security',
						'rum'         => 'Real-User Monitoring',
						'internals'   => 'Internals',
						'automations' => 'Integrations',
						'settings'    => 'Settings',
					);
					foreach ( $tabs as $slug => $label ) {
						$class = $current_tab === $slug ? 'roko-button-outline' : 'roko-button-clear';
						printf(
							'<a href="%s" class="roko-button %s">%s</a>',
							esc_url( add_query_arg( 'tab', $slug, admin_url( 'admin.php?page=roko-admin' ) ) ),
							$class,
							esc_html( $label )
						);
					}
					?>
				</nav>

				<div class="roko-tab-content roko-mt-6">
					<?php if ( 'internals' === $current_tab ) : ?>
							<?php $internal_tab = isset( $_GET['internal_tab'] ) ? sanitize_text_field( $_GET['internal_tab'] ) : 'transients'; ?>
						<?php
						$all_internal_tabs = array(
							'transients'   => 'Transients',
							'cron'         => 'Cron',
							'database'     => 'Database Health',
							'rewrite'      => 'Rewrite Rules',
							'environment'  => 'Environment',
							'http'         => 'HTTP Logger',
							'roles'        => 'Roles & Caps',
							'cache'        => 'Object.Cache',
							'actions'      => 'Scheduled Actions',
							'hooks'        => 'Hooks & Filters',
							'slow_queries' => 'Slow Queries',
							'debug_log'    => 'Debug Log',
						);
						$primary           = array( 'transients', 'cron', 'database', 'environment', 'http' );
						$advanced          = array_diff( array_keys( $all_internal_tabs ), $primary );
						?>
						<nav class="roko-subtab-nav roko-mt-2 roko-pl-4">
							<ul class="roko-d-flex" style="list-style:none; margin:0; padding:0; gap:16px;">
							<?php foreach ( $primary as $slug ) : ?>
								<?php
								$label      = $all_internal_tabs[ $slug ];
								$link_class = ( $internal_tab === $slug ) ? 'roko-text-primary roko-font-weight-bold' : 'roko-text-muted';
								?>
								<li><a href="
								<?php
								echo esc_url(
									add_query_arg(
										array(
											'tab'          => 'internals',
											'internal_tab' => $slug,
										),
										admin_url( 'admin.php?page=roko-admin' )
									)
								);
								?>
												" class="roko-text-link <?php echo esc_attr( $link_class ); ?>"><?php echo esc_html( $label ); ?></a></li>
							<?php endforeach; ?>

							<?php if ( ! empty( $advanced ) ) : ?>
								<li class="roko-actions-menu">
								<button class="roko-actions-toggle">More (<?php echo count( $advanced ); ?>) ▾</button>
								<div class="roko-actions-dropdown">
									<?php foreach ( $advanced as $slug ) : ?>
										<?php
										$label      = $all_internal_tabs[ $slug ];
										$link_class = ( $internal_tab === $slug ) ? 'roko-text-primary roko-font-weight-bold' : 'roko-text-muted';
										?>
									<a href="
										<?php
										echo esc_url(
											add_query_arg(
												array(
													'tab' => 'internals',
													'internal_tab' => $slug,
												),
												admin_url( 'admin.php?page=roko-admin' )
											)
										);
										?>
												" class="<?php echo esc_attr( $link_class ); ?>"><?php echo esc_html( $label ); ?></a>
									<?php endforeach; ?>
								</div>
								</li>
							<?php endif; ?>
							</ul>
						</nav>
						<div class="roko-mt-4">
							<?php
							switch ( $internal_tab ) {
								case 'transients':
											include ROKO_PLUGIN_DIR . '/templates/partials/transients-monitoring.php';
									break;
								case 'cron':
											include ROKO_PLUGIN_DIR . '/templates/partials/cron-monitoring.php';
									break;
								case 'database':
											include ROKO_PLUGIN_DIR . '/templates/partials/database-health.php';
									break;
								case 'rewrite':
											include ROKO_PLUGIN_DIR . '/templates/partials/rewrite-rules.php';
									break;
								case 'environment':
											include ROKO_PLUGIN_DIR . '/templates/partials/environment.php';
									break;
								case 'http':
											include ROKO_PLUGIN_DIR . '/templates/partials/http-logger.php';
									break;
								case 'roles':
											include ROKO_PLUGIN_DIR . '/templates/partials/roles-capabilities.php';
									break;
								case 'cache':
											include ROKO_PLUGIN_DIR . '/templates/partials/object-cache.php';
									break;
								case 'actions':
											include ROKO_PLUGIN_DIR . '/templates/partials/scheduled-actions.php';
									break;
								case 'hooks':
											include ROKO_PLUGIN_DIR . '/templates/partials/hooks-filters.php';
									break;
								case 'slow_queries':
											include ROKO_PLUGIN_DIR . '/templates/partials/slow-query-profiler.php';
									break;
								case 'debug_log':
											include ROKO_PLUGIN_DIR . '/templates/partials/debug-log-viewer.php';
									break;
								default:
											include ROKO_PLUGIN_DIR . '/templates/partials/transients-monitoring.php';
									break;
							}
							?>
						</div>
					<?php else : ?>
						<?php
						switch ( $current_tab ) {
							case 'overview':
										include ROKO_PLUGIN_DIR . '/templates/partials/overview-ai.php';
								break;
							case 'performance':
										include ROKO_PLUGIN_DIR . '/templates/partials/performance.php';
								break;
							case 'security':
										include ROKO_PLUGIN_DIR . '/templates/partials/security.php';
								break;
							case 'rum':
										include ROKO_PLUGIN_DIR . '/templates/partials/rum.php';
								break;
							case 'automations':
										include ROKO_PLUGIN_DIR . '/templates/partials/integrations.php';
								break;
							case 'settings':
										include ROKO_PLUGIN_DIR . '/templates/partials/settings.php';
								break;
							default:
										include ROKO_PLUGIN_DIR . '/templates/partials/overview-ai.php';
								break;
						}
						?>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}