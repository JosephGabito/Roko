<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use JosephG\Roko\Domain\Ingress\IngressConfig;

// Dev.
$ingress_url = IngressConfig::url();
?>
<!-- templates/partials/security.php - Updated for DDD Clean Architecture Schema v3.0 -->
	<div class="roko-card" id="roko-security-dashboard"
		data-endpoint="<?php echo esc_url( rest_url( 'roko/v1/security' ) ); ?>"
		data-nonce="<?php echo wp_create_nonce( 'wp_rest' ); ?>">

		<div class="roko-card-header roko-d-flex roko-justify-content-between roko-align-items-center">
		<div>
			<h3 class="roko-card-title"><?php esc_html_e( 'Site Foundation', 'roko' ); ?></h3>
			<p class="roko-card-subtitle roko-mr-4">
				<?php esc_html_e( 'Everything that holds your site together: core health, essential security checks, and the basics that keep you from waking up to a mess. Roko finds weak spots, gives you fixes, or tells you exactly who to tap for help', 'roko' ); ?>
			</p>
		</div>

		<!-- View mode toggle -->
		<div class="roko-view-toggle" role="group" aria-label="<?php esc_attr_e( 'View mode', 'roko' ); ?>">
			<button id="roko-pill-all" class="roko-button roko-button-outline active" aria-pressed="true">
				<?php esc_html_e( 'Show all checks', 'roko' ); ?>
			</button>
			<button id="roko-pill-need" class="roko-button roko-button-clear" aria-pressed="false">
				<?php esc_html_e( 'Show required actions', 'roko' ); ?>
			</button>
		</div>
		</div>

		<div class="roko-card-body">
		<!-- Security Score Display -->
		<div class="roko-security-score roko-d-flex roko-align-items-center roko-mb-4">
			<div class="score-circle roko-mr-4" id="roko-score-ring">
				<span class="score-value" id="roko-score-value">…</span>
				<span class="score-label">/100</span>
			</div>
			<div class="score-details roko-ml-4">
				<span class="roko-boost-score" id="roko-score-status">
					<?php esc_html_e( 'Loading security data…', 'roko' ); ?>
				</span> <br/>
				<span class="roko-text-muted">
					<span id="roko-critical-count">0</span> 
					<?php esc_html_e( 'critical/high-severity issues found', 'roko' ); ?>
				</span>
				<div class="algorithm-info roko-text-muted roko-text-small" style="margin-top: 8px;">
					<?php esc_html_e( 'Weighted scoring algorithm v1.0.0', 'roko' ); ?>
				</div>
			</div>
		</div>

		<!-- AI Recommendations Section -->
		<div
			class="roko-mb-4"
			x-data="{ data: null, loading: false }"
			id="roko-site-foundation-report"
			data-json-report='{"loading": true}'
			>
			<div x-html="data || ''"></div>
			<button
				:disabled="loading"
				x-show="data !== ''"
				@click="
					loading = true;
					fetch( '<?php echo $ingress_url; ?>/ingress', {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: document
						.getElementById('roko-site-foundation-report')
						.dataset.jsonReport
					})
					.then(res => res.json())
					.then(payload => {
						console.log(payload.data);
						data = payload.data
					})
					.catch(err => console.error('AI recommendations error:', err))
					.finally(() => { loading = false })
				"
				class="roko-button active roko-button-outline"
			>
				<span x-show="!loading">
					<svg style="position:relative; top: 3px; color:#01a229;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" class="w-4 h-4 shrink-0 text-gray-700 hover:text-gray-800 dark:text-gray-400 hover:dark:text-gray-200"><g fill="currentColor"><path d="M5.658,2.99l-1.263-.421-.421-1.263c-.137-.408-.812-.408-.949,0l-.421,1.263-1.263,.421c-.204,.068-.342,.259-.342,.474s.138,.406,.342,.474l1.263,.421,.421,1.263c.068,.204,.26,.342,.475,.342s.406-.138,.475-.342l.421-1.263,1.263-.421c.204-.068,.342-.259,.342-.474s-.138-.406-.342-.474Z" fill="currentColor" data-stroke="none" stroke="none"></path><polygon points="9.5 2.75 11.412 7.587 16.25 9.5 11.412 11.413 9.5 16.25 7.587 11.413 2.75 9.5 7.587 7.587 9.5 2.75" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></polygon></g></svg>
					<?php esc_html_e( 'Get AI Recommendations', 'roko' ); ?>
				</span>
				<span x-show="loading"><?php esc_html_e( 'Loading…', 'roko' ); ?></span>
			</button>
			</div>

		<!-- Security Keys Management Section -->
		<div class="roko-detail-card roko-mb-4" 
			x-data="saltRegeneration()"
			x-init="init"
			role="region"
			aria-labelledby="security-keys-title">
			
			<div class="roko-d-flex roko-justify-content-between roko-align-items-center roko-mb-3">
				<div>
					<h4 id="security-keys-title"><?php esc_html_e( 'Security Keys & Salts Management', 'roko' ); ?></h4>
					<p class="roko-text-muted roko-text-small">
						<?php esc_html_e( 'Give your site a quick security tune-up—refresh these secret codes to lock down your site. (Heads up: everyone will need to log in again.)', 'roko' ); ?>
						<?php esc_html_e( "They're extra-strong locks inside your website. Changing them now and then keeps hackers out—even if they found an old password.", 'roko' ); ?>
					</p>
					<p class="roko-text-muted roko-text-small" style="margin-top: 4px;">
						<strong><?php esc_html_e( 'Last rotated:', 'roko' ); ?></strong> 
						<span x-text="getLastRotatedText()" style="font-weight: normal;"></span>
					</p>
				</div>
				<div class="roko-d-flex roko-gap-2">
					<button 
						@click="regenerateSalts"
						:disabled="regenerating || disabling"
						class="roko-button roko-button-outline roko-button-small"
						:aria-label="regenerating ? '<?php esc_attr_e( 'Rotating security keys...', 'roko' ); ?>' : '<?php esc_attr_e( 'Rotate security keys', 'roko' ); ?>'">
						<span x-show="!regenerating"><?php esc_html_e( 'Rotate Keys', 'roko' ); ?></span>
						<span x-show="regenerating"><?php esc_html_e( 'Rotating...', 'roko' ); ?></span>
					</button>
					
					<button 
						x-show="lastRotated"
						@click="disableRokoSalts"
						:disabled="regenerating || disabling"
						class="roko-button roko-button-clear roko-button-small"
						style="color: #d63638; border-color: #d63638;"
						:aria-label="disabling ? '<?php esc_attr_e( 'Disabling Roko salt management...', 'roko' ); ?>' : '<?php esc_attr_e( 'Disable Roko salt management', 'roko' ); ?>'">
						<span x-show="!disabling"><?php esc_html_e( 'Disable Roko Salts', 'roko' ); ?></span>
						<span x-show="disabling"><?php esc_html_e( 'Disabling...', 'roko' ); ?></span>
					</button>
				</div>
			</div>
			
			<!-- Error/Success Messages -->
			<div x-show="errorMessage" 
				class="roko-alert roko-alert-error roko-mb-3"
				x-transition
				role="alert">
				<p x-text="errorMessage"></p>
			</div>

			<!-- Confirmation Modal -->
			<div x-show="showModal" 
				x-transition:enter="roko-modal-enter"
				x-transition:leave="roko-modal-leave"
				class="roko-modal-overlay"
				@click.self="cancelAction()"
				role="dialog"
				aria-modal="true"
				:aria-labelledby="modalTitle">
				<div class="roko-modal-content"
					x-transition:enter="roko-modal-content-enter"
					x-transition:leave="roko-modal-content-leave">
					<div class="roko-modal-header">
						<h3 x-text="modalTitle" id="modal-title"></h3>
						<button @click="cancelAction()" class="roko-modal-close" aria-label="<?php esc_attr_e( 'Close dialog', 'roko' ); ?>">&times;</button>
					</div>
					<div class="roko-modal-body">
						<p x-text="modalMessage"></p>
					</div>
					<div class="roko-modal-footer">
						<button @click="cancelAction()" class="roko-button roko-button-clear">
							<?php esc_html_e( 'Cancel', 'roko' ); ?>
						</button>
						<button @click="confirmAction()" class="roko-button roko-button-primary">
							<?php esc_html_e( 'Continue', 'roko' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Security Details Grid - Populated by JavaScript using new DDD schema -->
		<div class="roko-security-details-grid" id="roko-details-grid" role="region" aria-label="<?php esc_attr_e( 'Security check details', 'roko' ); ?>">
			<!-- Security check cards will be dynamically inserted here by security.js -->
			<div class="roko-loading-placeholder roko-text-center roko-py-4">
				<p class="roko-text-muted"><?php esc_html_e( 'Loading security checks...', 'roko' ); ?></p>
			</div>
		</div>

		<!-- Action Buttons -->
		<div class="roko-security-actions roko-mt-5 roko-d-flex" role="group" aria-label="<?php esc_attr_e( 'Security actions', 'roko' ); ?>">
			<button class="roko-button roko-button-outline roko-mr-3" disabled title="<?php esc_attr_e( 'Feature coming soon', 'roko' ); ?>">
				<?php esc_html_e( 'Safe-update plugins', 'roko' ); ?>
			</button>
			<button id="roko-autofix-all" class="roko-button roko-button-outline roko-mr-3" disabled>
				<span id="roko-autofix-text"><?php esc_html_e( 'Auto-fix issues', 'roko' ); ?></span>
				<span id="roko-autofix-count" class="roko-badge" style="display: none; margin-left: 6px; background: #00a32a; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px;">0</span>
			</button>
			<button class="roko-button roko-button-outline" disabled title="<?php esc_attr_e( 'Feature coming soon', 'roko' ); ?>">
				<?php esc_html_e( 'Generate report', 'roko' ); ?>
			</button>
		</div>
		</div>
	</div>

<script>
/**
 * Alpine.js component for security keys management.
 * Compatible with new DDD clean architecture schema.
 */
function saltRegeneration() {
	return {
		regenerating: false,
		disabling: false,
		errorMessage: '',
		lastRotated: null,
		showModal: false,
		modalAction: null,
		modalTitle: '',
		modalMessage: '',
		
		init() {
			// Listen for security data from the main dashboard
			document.addEventListener('roko:security-data-loaded', (e) => {
				const data = e.detail;
				if (data && data.securityKeys && data.securityKeys.lastRotated) {
					this.lastRotated = data.securityKeys.lastRotated;
				}
			});

			// Request data from main dashboard if it's already loaded
			setTimeout(() => {
				const event = new CustomEvent('roko:request-security-data');
				const dashboard = document.getElementById('roko-security-dashboard');
				if (dashboard) {
					dashboard.dispatchEvent(event);
				}
			}, 100);
		},

		getLastRotatedText() {
			if (!this.lastRotated) {
				return '<?php echo esc_js( __( 'Never rotated with Roko', 'roko' ) ); ?>';
			}
			
			const date = new Date(this.lastRotated * 1000);
			const options = {
				year: 'numeric',
				month: 'short',
				day: '2-digit',
				hour: '2-digit',
				minute: '2-digit',
				hour12: true
			};
			
			return date.toLocaleDateString('en-US', options);
		},

		showConfirmModal(action, title, message) {
			this.modalAction = action;
			this.modalTitle = title;
			this.modalMessage = message;
			this.showModal = true;
		},

		confirmAction() {
			this.showModal = false;
			if (this.modalAction === 'rotate') {
				this.executeRotation();
			} else if (this.modalAction === 'disable') {
				this.executeDisable();
			}
		},

		cancelAction() {
			this.showModal = false;
			this.modalAction = null;
		},

		async disableRokoSalts() {
			this.showConfirmModal(
				'disable',
				'<?php echo esc_js( __( 'Disable Roko Salt Management', 'roko' ) ); ?>',
				'<?php echo esc_js( __( "Turn off Roko's extra security and go back to WordPress's basic settings? Heads up: You'll need to log in again, and you'll lose Roko's tougher locks on your site.", 'roko' ) ); ?>'
			);
		},

		async executeDisable() {
			this.disabling = true;
			this.errorMessage = '';
			
			try {
				const response = await fetch('<?php echo esc_url( rest_url( 'roko/v1/security/disable-roko-salts' ) ); ?>', {
					method: 'GET',
					credentials: 'same-origin',
					headers: {
						'X-WP-Nonce': document.getElementById('roko-security-dashboard').dataset.nonce
					}
				});
				
				if (response.ok) {
					try {
						const result = await response.json();
						if (result.disabled) {
							window.location.href = result.nextLogin;
						} else {
							throw new Error(result.message || '<?php echo esc_js( __( 'Failed to disable Roko management', 'roko' ) ); ?>');
						}
					} catch (jsonError) {
						console.log('JSON parse error (disable likely succeeded):', jsonError);
						window.location.href = '<?php echo wp_login_url(); ?>';
					}
				} else {
					const errorText = await response.text();
					throw new Error(`<?php echo esc_js( __( 'HTTP Error', 'roko' ) ); ?> ${response.status}: ${errorText}`);
				}
			} catch (error) {
				if (!error.message.includes('JSON') && !error.message.includes('Unexpected')) {
					this.errorMessage = error.message || '<?php echo esc_js( __( 'Failed to disable Roko salt management. Please try again.', 'roko' ) ); ?>';
				} else {
					console.log('Assuming disable succeeded despite JSON error');
					window.location.href = '<?php echo wp_login_url(); ?>';
				}
			} finally {
				this.disabling = false;
			}
		},

		async regenerateSalts() {
			this.showConfirmModal(
				'rotate',
				'<?php echo esc_js( __( 'Ready for a fresh set of security codes?', 'roko' ) ); ?>',
				'<?php echo esc_js( __( "With one click, Roko locks in brand new secret codes, safely encrypted in your site's vault. Your security gets a big upgrade—and everyone will need to log in again.", 'roko' ) ); ?>'
			);
		},

		async executeRotation() {
			this.regenerating = true;
			this.errorMessage = '';
			
			try {
				const response = await fetch('<?php echo esc_url( rest_url( 'roko/v1/security/regenerate-salts' ) ); ?>', {
					method: 'GET',
					credentials: 'same-origin',
					headers: {
						'X-WP-Nonce': document.getElementById('roko-security-dashboard').dataset.nonce
					}
				});
				
				if (response.ok) {
					try {
						const result = await response.json();
						if (result.rotated) {
							if (result.rotatedAt) {
								this.lastRotated = result.rotatedAt;
							}
							window.location.href = result.nextLogin;
						} else {
							throw new Error(result.message || '<?php echo esc_js( __( 'Rotation failed', 'roko' ) ); ?>');
						}
					} catch (jsonError) {
						console.log('JSON parse error (rotation likely succeeded):', jsonError);
						this.lastRotated = Math.floor(Date.now() / 1000);
						window.location.href = '<?php echo wp_login_url(); ?>';
					}
				} else {
					const errorText = await response.text();
					throw new Error(`<?php echo esc_js( __( 'HTTP Error', 'roko' ) ); ?> ${response.status}: ${errorText}`);
				}
			} catch (error) {
				if (!error.message.includes('JSON') && !error.message.includes('Unexpected')) {
					this.errorMessage = error.message || '<?php echo esc_js( __( 'Failed to rotate security keys. Please try again.', 'roko' ) ); ?>';
				} else {
					console.log('Assuming rotation succeeded despite JSON error');
					window.location.href = '<?php echo wp_login_url(); ?>';
				}
			} finally {
				this.regenerating = false;
			}
		}
	}
}
</script>

<style>
/* Score circle styling */
.score-circle {
	width: 80px;
	height: 80px;
	border-radius: 50%;
	background: conic-gradient(#00a32a 0%, #e9ecef 0% 100%);
	display: flex;
	align-items: center;
	justify-content: center;
	position: relative;
	flex-shrink: 0;
}

.score-circle::before {
	content: '';
	position: absolute;
	width: 60px;
	height: 60px;
	background: white;
	border-radius: 50%;
}

.score-value {
	font-size: 18px;
	font-weight: 700;
	color: #1d2327;
	z-index: 1;
}

.score-label {
	font-size: 12px;
	color: #646970;
	z-index: 1;
}

/* View toggle styling */
.roko-view-toggle {
	display: flex;
	gap: 4px;
}

.roko-view-toggle .roko-button {
	font-size: 12px;
	padding: 6px 12px;
	min-height: 32px;
}

.roko-view-toggle .roko-button.active {
	background: #2271b1;
	color: white;
	border-color: #2271b1;
}

/* Security item styling */
.security-item {
	padding: 12px 0;
	border-bottom: 1px solid #f0f0f1;
}

.security-item:last-child {
	border-bottom: none;
}

.security-item-label {
	font-weight: 500;
	color: #1d2327;
	margin-bottom: 4px;
}

.security-item-note {
	font-size: 12px;
	color: #646970;
	margin: 0;
	line-height: 1.4;
}

/* Hide OK items when in "required actions" mode */
.hide-ok [data-status="ok"] {
	display: none;
}

/* Status-based text colors */
.security-item[data-status="critical"] .security-item-label {
	color: #d63638;
}

.security-item[data-status="warn"] .security-item-label {
	color: #996800;
}

.security-item[data-status="ok"] .security-item-label {
	color: #00a32a;
}

.security-item[data-status="pending"] .security-item-label {
	color: #2271b1;
}

/* Vulnerability table specific styling */
.roko-vulnerabilities-table-container {
	margin: 16px 0;
}

.roko-vulnerabilities-table-container .roko-table {
	margin: 0;
}

/* Severity badge colors */
.roko-badge-high {
	background-color: #f8d7da;
	color: #721c24;
	border: 1px solid #d63638;
}

.roko-badge-medium {
	background-color: #fff3cd;
	color: #664d03;
	border: 1px solid #dba617;
}

.roko-badge-low {
	background-color: #d1ecf1;
	color: #0c5460;
	border: 1px solid #17a2b8;
}

/* Roko-managed keys badge */
.roko-badge-roko {
	background-color: #e7f3ff;
	color: #0073aa;
	border: 1px solid #0073aa;
	font-weight: 600;
}

/* Salt regeneration section styling */
.roko-detail-card {
	background: #f9f9f9;
	border: 1px solid #e0e0e0;
	border-radius: 6px;
	padding: 16px;
}

.roko-gap-2 {
	gap: 8px;
}

.roko-button-small {
	font-size: 12px;
	padding: 6px 12px;
	min-height: 28px;
}

/* Alert styling */
.roko-alert {
	padding: 12px 16px;
	border-radius: 4px;
	border: 1px solid transparent;
}

.roko-alert-error {
	background-color: #f8d7da;
	color: #721c24;
	border-color: #d63638;
}

/* Modal styling */
.roko-modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: rgba(0, 0, 0, 0.5);
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 1000;
}

.roko-modal-content {
	background: white;
	border-radius: 8px;
	box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
	max-width: 500px;
	width: 90%;
	margin: 20px;
}

.roko-modal-header {
	padding: 20px 24px 0;
	display: flex;
	justify-content: space-between;
	align-items: center;
	border-bottom: none;
}

.roko-modal-header h3 {
	margin: 0;
	font-size: 18px;
	font-weight: 600;
	color: #1d2327;
}

.roko-modal-close {
	background: none;
	border: none;
	font-size: 24px;
	color: #646970;
	cursor: pointer;
	padding: 0;
	width: 24px;
	height: 24px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.roko-modal-close:hover {
	color: #1d2327;
}

.roko-modal-body {
	padding: 16px 24px;
}

.roko-modal-body p {
	margin: 0;
	line-height: 1.5;
	color: #646970;
}

.roko-modal-footer {
	padding: 16px 24px 24px;
	display: flex;
	justify-content: flex-end;
	gap: 12px;
}

.roko-button-primary {
	background: #2271b1;
	color: white;
	border-color: #2271b1;
}

.roko-button-primary:hover {
	background: #135e96;
	border-color: #135e96;
}

/* Modal transitions */
.roko-modal-enter {
	transition: opacity 150ms ease-out;
}

.roko-modal-leave {
	transition: opacity 150ms ease-in;
}

.roko-modal-content-enter {
	transition: all 150ms ease-out;
	transform: scale(0.95);
}

.roko-modal-content-leave {
	transition: all 150ms ease-in;
	transform: scale(0.95);
}

/* Section score styling */
.section-score-container {
	display: flex;
	align-items: center;
}

.section-score {
	padding: 4px 12px;
	border-radius: 4px;
	font-size: 12px;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.5px;
}

.section-score.score-good {
	background-color: #d1e7dd;
	color: #0f5132;
	border: 1px solid #badbcc;
}

.section-score.score-fair {
	background-color: #fff3cd;
	color: #664d03;
	border: 1px solid #ffecb5;
}

.section-score.score-poor {
	background-color: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c2c7;
}

/* Enhanced card layout */
.roko-detail-card h4 {
	margin-bottom: 4px;
}

.security-items {
	border-top: 1px solid #e0e0e0;
	padding-top: 16px;
}
</style>
