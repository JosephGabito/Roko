<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use JosephG\Roko\Domain\Ingress\IngressConfig;

// Dev.
$ingress_url = IngressConfig::url();
?>
<!-- templates/partials/security.php -->
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
			<button id="roko-pill-all" class="roko-button roko-button-outline active" aria-pressed="true"><?php esc_html_e( 'Show all checks', 'roko' ); ?></button>
			<button id="roko-pill-need" class="roko-button roko-button-clear" aria-pressed="false"><?php esc_html_e( 'Show required actions', 'roko' ); ?></button>
		</div>
		</div>

		<div class="roko-card-body">
		<!-- Score display -->
		<div class="roko-security-score roko-d-flex roko-align-items-center roko-mb-4">
			<div class="score-circle roko-mr-4" id="roko-score-ring">
				<span class="score-value" id="roko-score-value">…</span>
				<span class="score-label">/100</span>
			</div>
			<div class="score-details roko-ml-4">
				<span class="roko-boost-score" id="roko-score-status"><?php esc_html_e( 'Loading…', 'roko' ); ?></span> <br/>
				<span class="roko-text-muted"><span id="roko-critical-count">0</span> <?php esc_html_e( 'critical issues found', 'roko' ); ?></span>
			</div>
		</div>

		<div
			class="roko-mb-4"
			x-data="{ data: null, loading: false }"
			id="roko-site-foundation-report"
			data-json-report='{"foo":"bar"}'
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
					.catch(err => console.error(err))
					.finally(() => { loading = false })
				"
				class="roko-button active roko-button-outline"
			>
				<span x-show="!loading">
					<svg style="position:relative; top: 3px; color:#01a229;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" class="w-4 h-4 shrink-0 text-gray-700 hover:text-gray-800 dark:text-gray-400 hover:dark:text-gray-200"><g fill="currentColor"><path d="M5.658,2.99l-1.263-.421-.421-1.263c-.137-.408-.812-.408-.949,0l-.421,1.263-1.263,.421c-.204,.068-.342,.259-.342,.474s.138,.406,.342,.474l1.263,.421,.421,1.263c.068,.204,.26,.342,.475,.342s.406-.138,.475-.342l.421-1.263,1.263-.421c.204-.068,.342-.259,.342-.474s-.138-.406-.342-.474Z" fill="currentColor" data-stroke="none" stroke="none"></path><polygon points="9.5 2.75 11.412 7.587 16.25 9.5 11.412 11.413 9.5 16.25 7.587 11.413 2.75 9.5 7.587 7.587 9.5 2.75" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></polygon></g></svg>
					Get AI Recommendations
				</span>
				<span x-show="loading">Loading…</span>
			</button>
			</div>


		<!-- Security details grid -->
		<div class="roko-security-details-grid" id="roko-details-grid">
			<!-- Cards will be inserted here -->
		</div>

		<!-- Action buttons -->
		<div class="roko-security-actions roko-mt-5 roko-d-flex">
			<button class="roko-button roko-button-outline roko-mr-3"><?php esc_html_e( 'Safe-update plugins', 'roko' ); ?></button>
			<button class="roko-button roko-button-outline roko-mr-3"><?php esc_html_e( 'Auto-fix issues', 'roko' ); ?></button>
			<button class="roko-button roko-button-outline"><?php esc_html_e( 'Generate report', 'roko' ); ?></button>
		</div>
		</div>
	</div>

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

</style>
