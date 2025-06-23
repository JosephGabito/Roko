<!-- templates/partials/security.php -->
<div class="roko-admin">
	<div class="roko-container">
	<!-- Security Dashboard -->
	<div
		class="roko-card"
		id="roko-security-dashboard"
		data-endpoint="<?php echo esc_url( rest_url( 'roko/v1/security' ) ); ?>"
		data-nonce="<?php echo wp_create_nonce( 'wp_rest' ); ?>"
	>
		<div class="roko-card-header">
		<h3 class="roko-card-title">
			Security Analysis
			<span class="tooltip-icon" data-tooltip="Security status and recommendations for your WordPress site">?</span>
		</h3>
		<p class="roko-card-subtitle">Security status and recommendations for your WordPress site</p>
		</div>

		<div class="roko-card-body">
		<!-- Score -->
		<div class="roko-security-score roko-d-flex roko-align-items-center roko-mb-4">
			<div class="score-circle" id="roko-score-ring">
			<span class="score-value" id="roko-score-value">—</span>
			<span class="score-label">/100</span>
			</div>
			<div class="score-details">
			<p class="roko-boost-score" id="roko-score-status">Loading…</p>
			<p class="roko-text-muted"><span id="roko-critical-count">0</span> critical issues found</p>
			</div>
		</div>

		<!-- Details Grid -->
		<div class="roko-site-details-grid" id="roko-details-grid"></div>

		<!-- Quick Actions -->
		<div class="roko-security-actions roko-mt-5 roko-d-flex roko-gap-3">
			<button class="roko-button" id="roko-btn-scan">Run Security Scan</button>
			<button class="roko-button roko-button-outline" id="roko-btn-update">Update Plugins</button>
			<button class="roko-button roko-button-outline" id="roko-btn-harden">Harden WP</button>
			<button class="roko-button roko-button-outline" id="roko-btn-report">Generate Report</button>
		</div>
		</div>
	</div>
	</div>
</div>

<script type="module">
	document.addEventListener('DOMContentLoaded', () => {
	const root     = document.getElementById('roko-security-dashboard');
	const endpoint = root?.dataset.endpoint;
	const nonce    = root?.dataset.nonce;
	if (!endpoint || !nonce) return;

	fetch(endpoint, {
		credentials: 'same-origin',
		headers: { 'X-WP-Nonce': nonce },
	})
		.then(r => r.ok ? r.json() : Promise.reject(r))
		.then(renderDashboard)
		.catch(() => {
		root.querySelector('#roko-score-status').textContent = 'Error';
		});

	function renderDashboard(data) {
		const score = calcScore(data);
		updateScoreRing(score);
		document.getElementById('roko-critical-count').textContent = countCriticalIssues(data);
		document.getElementById('roko-details-grid').innerHTML = buildCardsHTML(data);
	}

	function calcScore(d) {
		const ok   = (d.fileSecurity.wpDebug ? 0 : 5) + (d.networkSecurity.httpsEnforced ? 10 : 0);
		const risk = d.fileIntegrity.coreModified ? 20 : 0;
		return Math.max(0, Math.min(100, 80 + ok - risk));
	}

	function updateScoreRing(score) {
		const ring  = document.getElementById('roko-score-ring');
		const val   = document.getElementById('roko-score-value');
		val.textContent = score;
		ring.style.background = `conic-gradient(#00a32a ${score}%, #e9ecef ${score}% 100%)`;
		const status = document.getElementById('roko-score-status');
		status.textContent = score >= 80 ? 'Secure' : (score >= 60 ? 'Needs Attention' : 'Critical Issues');
		status.className   = 'roko-boost-score ' + (score >= 80 ? 'good' : (score >= 60 ? 'fair' : 'poor'));
	}

	const boolBadge = (b) => `<span class="roko-badge ${b ? 'roko-badge-success' : 'roko-badge-error'}">${b ? 'Yes' : 'No'}</span>`;

	function countCriticalIssues(d) {
		let c = 0;
		if (d.fileIntegrity.coreModified) c++;
		if (!d.networkSecurity.sslValid)  c++;
		return c;
	}

	function buildCardsHTML(d) {
		return `
		<div class="roko-detail-card">
		<h4>WordPress Security Keys</h4>
		<p class="roko-text-muted">Rotated: ${new Date(d.securityKeys.rotatedAt).toLocaleDateString()}</p>
		${boolBadge(!d.securityKeys.needsRotation)}
		</div>
		<div class="roko-detail-card">
		<h4>File Security</h4>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>wp-config.php</span>${boolBadge(['600','644'].includes(d.fileSecurity.wpConfigPerm))}</div>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>.htaccess</span>${boolBadge(d.fileSecurity.htaccessPerm === '644')}</div>
		</div>
		<div class="roko-detail-card">
		<h4>User Security</h4>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>Default admin user</span>${boolBadge(!d.userSecurity.adminUsernameRisk)}</div>
		</div>
		<div class="roko-detail-card">
		<h4>Network Security</h4>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>HTTPS enforced</span>${boolBadge(d.networkSecurity.httpsEnforced)}</div>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>SSL valid</span>${boolBadge(d.networkSecurity.sslValid)}</div>
		</div>
		<div class="roko-detail-card">
		<h4>File Integrity</h4>
		<div class="security-item roko-d-flex roko-justify-content-between"><span>Core modified</span>${boolBadge(!d.fileIntegrity.coreModified)}</div>
		</div>
		<div class="roko-detail-card">
		<h4>Known Vulnerabilities</h4>
		${d.knownVulnerabilities.length ? d.knownVulnerabilities.slice(0,3).map(v=>`<div class="security-item roko-d-flex roko-justify-content-between"><span>${v.plugin}</span><span class="roko-badge roko-badge-${v.severity}">${v.severity}</span></div>`).join('') : '<p class="roko-text-muted">None</p>'}
		</div>`;
	}
	});
</script>
