<?php
/**
 * Site Intelligence Center - Admin Interface for Roko
 * 100% Compatible with WordPress Dashboard patterns
 */

namespace JosephG\Roko\Infrastructure\WordPress\Admin;

class AdminPage {
	public function add_admin_page() {
		add_submenu_page(
			'index.php',
			'Site Intelligence',
			'Site Intelligence',
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
		wp_enqueue_script( 'roko-security', ROKO_PLUGIN_URL . 'assets/js/security.js', array(), '1.0.0', true );

		// Localize security script with translated strings
		wp_localize_script(
			'roko-security',
			'rokoSecurity',
			array(
				'siteHealth' => array(
					'title'                => __( 'Core Site Health Overview', 'roko' ),
					'description'          => __( 'See how your site measures up with WordPress\'s own health checks. Roko adds deeper insights and extra recommendations.', 'roko' ),
					'loadingInitial'       => __( 'Running WordPress core health checks...', 'roko' ),
					'loadingFetching'      => __( 'Fetching WordPress Site Health data...', 'roko' ),
					'loadingRunning'       => __( 'Running %d WordPress health checks...', 'roko' ),
					'loadingCompleted'     => __( 'Completed %1$d/%2$d health checks...', 'roko' ),
					'labelHealthCheck'     => __( 'WordPress Health Check', 'roko' ),
					'badgeLoading'         => __( 'Loading...', 'roko' ),
					'badgeIssuesFound'     => __( 'Issues found', 'roko' ),
					'badgeRecommendations' => __( 'Recommendations', 'roko' ),
					'badgeError'           => __( 'Error', 'roko' ),
					'descriptionPassed'    => __( '%1$d of %2$d WordPress core health checks passed', 'roko' ),
					'descriptionError'     => __( 'Unable to load WordPress health checks', 'roko' ),
					'testLabels'           => array(
						'background_updates'   => __( 'Background Updates', 'roko' ),
						'loopback_requests'    => __( 'Loopback Requests', 'roko' ),
						'https_status'         => __( 'HTTPS Status', 'roko' ),
						'dotorg_communication' => __( 'WordPress.org Communication', 'roko' ),
						'authorization_header' => __( 'Authorization Header', 'roko' ),
					),
				),
			)
		);
	}

	/**
	 * Get environment type for display.
	 */
	private function get_environment_type() {
		// Detect environment
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) ) {
			switch ( constant( 'WP_ENVIRONMENT_TYPE' ) ) {
				case 'development':
				case 'local':
					return array(
						'label' => 'DEV',
						'class' => 'roko-env-dev',
					);
				case 'staging':
					return array(
						'label' => 'STAGE',
						'class' => 'roko-env-stage',
					);
				case 'production':
					return array(
						'label' => 'LIVE',
						'class' => 'roko-env-live',
					);
				default:
					return array(
						'label' => 'LIVE',
						'class' => 'roko-env-live',
					);
			}
		}

		// Fallback detection
		$site_url = get_site_url();
		if ( strpos( $site_url, 'localhost' ) !== false ||
			strpos( $site_url, '.local' ) !== false ||
			strpos( $site_url, '127.0.0.1' ) !== false ) {
			return array(
				'label' => 'DEV',
				'class' => 'roko-env-dev',
			);
		} elseif ( strpos( $site_url, 'staging' ) !== false ||
					strpos( $site_url, 'stage' ) !== false ) {
			return array(
				'label' => 'STAGE',
				'class' => 'roko-env-stage',
			);
		}

		return array(
			'label' => 'LIVE',
			'class' => 'roko-env-live',
		);
	}

	/**
	 * Get instant value metrics for header.
	 */
	private function get_instant_metrics() {
		// Mock data - in real implementation, get from your data sources
		return array(
			'security'        => array(
				'score'  => 94,
				'status' => 'good',
			),
			'performance'     => array(
				'score'  => 88,
				'status' => 'good',
			),
			'critical_alerts' => 1,
		);
	}

	public function render_admin_page() {
		$current_tab = roko_get_http_query( 'tab' ) ?? 'overview';
		$environment = $this->get_environment_type();
		$metrics     = $this->get_instant_metrics();
		?>
		<div class="roko-admin">
			<div class="roko-container">
				
				<!-- Confident Header with Instant Value -->
				<div class="roko-header-section roko-mb-6">
					<div class="roko-header-main">
						<img 
						src="<?php echo esc_url( ROKO_PLUGIN_URL . 'assets/images/roko.png' ); ?>" 
						alt="Roko" 
						class="roko-header-logo"
						style="width: 150px; float: left; margin: -20px 10px -20px -20px"
						>
						<h1 class="roko-header-title">
							Watchtower
							<span class="roko-env-pill <?php echo esc_attr( $environment['class'] ); ?>">
								<?php echo esc_html( $environment['label'] ); ?>
							</span>
						</h1>
						<p class="roko-header-subtitle">
							<?php esc_html_e( 'Get early warnings, clear answers, and simple wins.', 'roko' ); ?>
						</p>
					</div>
					
					<!-- Instant Value String -->
					<div class="roko-instant-metrics">
						<div class="roko-metric-item">
							<span class="roko-metric-label">Security</span>
							<span class="roko-metric-score roko-metric-<?php echo esc_attr( $metrics['security']['status'] ); ?>">
								<?php echo esc_html( $metrics['security']['score'] ); ?><span class="roko-metric-total">/100</span>
							</span>
						</div>
						<span class="roko-metric-separator">•</span>
						<div class="roko-metric-item">
							<span class="roko-metric-label">Performance</span>
							<span class="roko-metric-score roko-metric-<?php echo esc_attr( $metrics['performance']['status'] ); ?>">
								<?php echo esc_html( $metrics['performance']['score'] ); ?><span class="roko-metric-total">/100</span>
							</span>
						</div>
						<span class="roko-metric-separator">•</span>
						<div class="roko-metric-item">
							<?php if ( $metrics['critical_alerts'] > 0 ) : ?>
								<span class="roko-metric-label">Critical alerts</span>
								<span class="roko-metric-score roko-metric-critical">
									<?php echo esc_html( $metrics['critical_alerts'] ); ?>
								</span>
							<?php else : ?>
								<span class="roko-metric-label">Status</span>
								<span class="roko-metric-score roko-metric-good">All clear</span>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Main Horizontal Tabs -->
				<nav class="roko-tab-nav">
					<?php
					$tabs = array(
						'overview'    => 'Overview',
						'security'    => 'Site Foundation',
						'performance' => 'Performance Pit Stop',
						'a11y'        => 'Accessibility',
						'internals'   => 'Under the Hood',
						'automations' => 'Gadgets & Gizmos',
						'settings'    => 'Settings',
					);
					foreach ( $tabs as $slug => $label ) {
						$class = $current_tab === $slug ? 'roko-button-outline' : 'roko-button-clear';
						printf(
							'<a href="%s" class="roko-button %s">%s</a>',
							esc_url( add_query_arg( 'tab', $slug, admin_url( 'admin.php?page=roko-admin' ) ) ),
							esc_html( $class ),
							esc_html( $label )
						);
					}
					?>
				</nav>

				<div class="roko-tab-content roko-mt-6">
					<?php if ( 'internals' === $current_tab ) : ?>
							<?php $internal_tab = roko_get_http_query( 'internal_tab' ); ?>
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
						<div class="roko-card">
							<div class="roko-card-header">
								<h3 class="roko-card-title">Under the Hood</h3>
								<p class="roko-card-subtitle">
									This is for those who like knowing exactly what's happening behind the scenes. Dive into logs, caches, transients, and other advanced details. Perfect for developers, or anyone who loves the nitty-gritty
								</p>
							</div>
						</div>
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
							case 'a11y':
										include ROKO_PLUGIN_DIR . '/templates/partials/a11y.php';
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
		
		<style>
		/* Header Section Styling */
		.roko-header-section {
			background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
			border: 1px solid #e0e0e0;
			border-radius: 8px;
			padding: 24px;
			position: relative;
			overflow: hidden;
		}

		.roko-header-section::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 3px;
			background: linear-gradient(90deg, var(--roko-blue, #2271b1) 0%, var(--roko-green, #00a32a) 100%);
		}

		.roko-header-main {
			margin-bottom: 16px;
		}

		.roko-header-title {
			font-size: 28px;
			font-weight: 700;
			color: #1d2327;
			margin: 0 0 8px 0;
			letter-spacing: -0.025em;
			display: flex;
			align-items: center;
			gap: 12px;
		}

		.roko-header-subtitle {
			font-size: 16px;
			color: #646970;
			margin: 0;
			font-weight: 500;
		}

		/* Environment Pills */
		.roko-env-pill {
			display: inline-flex;
			align-items: center;
			padding: 4px 12px;
			border-radius: 20px;
			font-size: 11px;
			font-weight: 700;
			text-transform: uppercase;
			letter-spacing: 0.05em;
		}

		.roko-env-dev {
			background: #e8f5e8;
			color: #0f5132;
			border: 1px solid #00a32a;
		}

		.roko-env-stage {
			background: #fff3cd;
			color: #664d03;
			border: 1px solid #dba617;
		}

		.roko-env-live {
			background: #f8d7da;
			color: #721c24;
			border: 1px solid #d63638;
		}

		/* Instant Metrics */
		.roko-instant-metrics {
			display: flex;
			align-items: center;
			gap: 12px;
			flex-wrap: wrap;
		}

		.roko-metric-item {
			display: flex;
			align-items: center;
			gap: 6px;
		}

		.roko-metric-label {
			font-size: 13px;
			color: #646970;
			font-weight: 500;
		}

		.roko-metric-score {
			font-size: 16px;
			font-weight: 700;
			line-height: 1;
		}

		.roko-metric-total {
			font-size: 12px;
			font-weight: 500;
			color: #8c8f94;
		}

		.roko-metric-separator {
			color: #c3c4c7;
			font-weight: 600;
		}

		/* Metric Score Colors */
		.roko-metric-good {
			color: var(--roko-green, #00a32a);
		}

		.roko-metric-warning {
			color: var(--roko-yellow, #dba617);
		}

		.roko-metric-critical {
			color: var(--roko-red, #d63638);
		}

		/* Responsive Header */
		@media (max-width: 768px) {
			.roko-header-title {
				font-size: 24px;
				flex-direction: column;
				align-items: flex-start;
				gap: 8px;
			}

			.roko-instant-metrics {
				flex-direction: column;
				align-items: flex-start;
				gap: 8px;
			}

			.roko-metric-item {
				justify-content: space-between;
				width: 100%;
				padding: 8px 0;
				border-bottom: 1px solid #f0f0f1;
			}

			.roko-metric-separator {
				display: none;
			}
		}

		/* Enhanced Tab Navigation */
		.roko-tab-nav {
			background: #ffffff;
			padding: 4px;
			display: flex;
			gap: 2px;
		}

		.roko-tab-nav .roko-button {
			font-weight: 500;
			transition: all 0.2s ease;
		}

		.roko-tab-nav .roko-button-outline {
			background: var(--roko-blue, #2271b1);
			color: white;
			border-color: var(--roko-blue, #2271b1);
			box-shadow: 0 2px 4px rgba(34, 113, 177, 0.25);
		}

		.roko-tab-nav .roko-button-clear:hover {
			background: #f6f7f7;
			color: #1d2327;
		}
		</style>
		<?php
	}
}