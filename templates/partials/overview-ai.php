<!-- Global Critical Notice Bar -->
<div class="roko-critical-notice" id="roko-critical-notice" style="display: none;">
	<div class="roko-critical-notice-content">
		<span class="roko-critical-icon">⚠️</span>
		<span class="roko-critical-text">2 critical issues require attention</span>
		<button class="roko-critical-dismiss" aria-label="Dismiss notice">×</button>
	</div>
</div>

<!-- Site Intelligence Overview with Actionable AI Insights -->
<div class="roko-card-group roko-mb-6">

	<!-- AI Health Check Card -->
	<div class="roko-card roko-card-primary roko-overview-card">
		<div class="roko-card-header roko-d-flex roko-justify-content-between roko-align-items-center">
			<div class="roko-d-flex roko-align-items-center">
				<svg class="roko-card-icon roko-mr-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--roko-grey)" stroke-width="2">
					<path d="M9.5 2A2.5 2.5 0 0 0 7 4.5v15A2.5 2.5 0 0 0 9.5 22h5a2.5 2.5 0 0 0 2.5-2.5v-15A2.5 2.5 0 0 0 14.5 2h-5z"/>
					<path d="M12 6h.01M12 12h.01M12 18h.01"/>
				</svg>
				<h4 class="roko-card-title">AI Health Check</h4>
				<button class="roko-info-btn roko-ml-2" title="How we compute Health Score - Click to learn more" data-docs="health-score">ⓘ</button>
			</div>
		</div>
		<div class="roko-card-body">
			<div class="roko-health-score-wrapper roko-mb-3">
				<div class="roko-health-score excellent" role="status" aria-live="polite" data-target="94">0</div>
				<span class="roko-badge roko-badge-success roko-ml-2">Good</span>
			</div>
			<p class="roko-card-text roko-mb-3">
				Your site looks <strong>healthy</strong>, yet <strong>two plugins add 380 ms to TTFB</strong>.
			</p>
			<button class="roko-button roko-button-outline roko-button-small">View 3 AI fixes</button>
		</div>
	</div>

	<!-- Core Web Vitals Trends Card -->
	<div class="roko-card roko-card-success roko-overview-card">
		<div class="roko-card-header roko-d-flex roko-justify-content-between roko-align-items-center">
			<div class="roko-d-flex roko-align-items-center">
				<svg class="roko-card-icon roko-mr-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--roko-grey)" stroke-width="2">
					<path d="M3 3v18h18"/>
					<path d="M7 12l3-3 3 3 5-5"/>
				</svg>
				<h4 class="roko-card-title">Core Web Vitals Trends</h4>
				<button class="roko-info-btn roko-ml-2" title="How we track Core Web Vitals - Click to learn more" data-docs="core-web-vitals">ⓘ</button>
			</div>
		</div>
		<div class="roko-card-body">
			<div class="roko-vitals-sparkline-container roko-mb-3">
				<div class="roko-vitals-sparkline" title="30-day LCP trend: 1.8s → 1.6s → 1.9s → 1.7s → 1.5s">
					<svg width="100" height="40" viewBox="0 0 100 40">
						<!-- Baseline (day 0) -->
						<line x1="0" y1="30" x2="100" y2="30" stroke="var(--roko-grey-light)" stroke-width="1" stroke-dasharray="2,2" opacity="0.5"/>
						<!-- LCP trend line (improving - green) -->
						<polyline fill="none" stroke="var(--roko-green)" stroke-width="2" 
								  points="0,30 25,25 50,32 75,22 100,15"/>
						<!-- Data points -->
						<circle cx="0" cy="30" r="2" fill="var(--roko-green)"/>
						<circle cx="25" cy="25" r="2" fill="var(--roko-green)"/>
						<circle cx="50" cy="32" r="2" fill="var(--roko-green)"/>
						<circle cx="75" cy="22" r="2" fill="var(--roko-green)"/>
						<circle cx="100" cy="15" r="2" fill="var(--roko-green)"/>
					</svg>
				</div>
				<div class="roko-trend-summary">
					<span class="roko-trend-direction roko-trend-up">↗ 12% better</span>
					<span class="roko-badge roko-badge-success roko-ml-2">Good</span>
				</div>
			</div>
			<small class="roko-text-muted">30-day LCP trend (lab)</small>
		</div>
	</div>

	<!-- Security Intelligence Card -->
	<div class="roko-card roko-card-warning roko-overview-card">
		<div class="roko-card-header roko-d-flex roko-justify-content-between roko-align-items-center">
			<div class="roko-d-flex roko-align-items-center">
				<svg class="roko-card-icon roko-mr-2" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--roko-grey)" stroke-width="2">
					<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
					<path d="M9 12l2 2 4-4"/>
				</svg>
				<h4 class="roko-card-title">Security Intelligence</h4>
				<span class="roko-alert-badge-pill roko-ml-2">
					<svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
						<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
					</svg>
					3
				</span>
				<button class="roko-info-btn roko-ml-2" title="How we monitor security - Click to learn more" data-docs="security-monitoring">ⓘ</button>
			</div>
		</div>
		<div class="roko-card-body">
			<div class="roko-status roko-status-warning roko-mb-3">
				<div class="roko-status-dot"></div>
				<span class="roko-badge roko-badge-warning roko-ml-2">Needs work</span>
			</div>
			<p class="roko-card-text roko-mb-3">
				<strong>3 plugins</strong> lagging behind security patches.
			</p>
			<button class="roko-button roko-button-outline roko-button-small">Resolve updates</button>
		</div>
	</div>

</div>

<!-- AI Site Analysis & Recommendations (Collapsible) -->
<div class="roko-card roko-ai-analysis-card">
	<div class="roko-card-header roko-collapsible-header" role="button" tabindex="0" aria-expanded="false">
		<div class="roko-d-flex roko-justify-content-between roko-align-items-center roko-w-100">
			<div>
				<h3 class="roko-card-title roko-d-flex roko-align-items-center">
					AI Site Analysis & Recommendations
					<button class="roko-info-btn roko-ml-2" title="How AI analysis works - Click to learn more" data-docs="ai-analysis">ⓘ</button>
				</h3>
				<div class="roko-collapsed-summary">
					<p class="roko-card-subtitle roko-mb-2">Last AI run: 8 h ago · 5 suggestions</p>
					<div class="roko-severity-dots">
						<span class="roko-severity-dot roko-severity-critical">●</span> 1 critical
						<span class="roko-severity-dot roko-severity-warn">●</span> 2 warn  
						<span class="roko-severity-dot roko-severity-info">●</span> 2 info
					</div>
				</div>
			</div>
			<svg class="roko-chevron" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--roko-grey)" stroke-width="2">
				<path d="M9 18l6-6-6-6"/>
			</svg>
		</div>
	</div>
	<div class="roko-card-body roko-collapsible-content" style="display: none;">
		<ul class="roko-recommendation-list">
			<li class="roko-recommendation-item roko-rec-critical" data-status="critical">
				<strong>Critical Plugin Vulnerability:</strong> WooCommerce 8.7.0 has a known SQL injection flaw, update to 8.7.1 immediately.
			</li>
			<li class="roko-recommendation-item roko-rec-warn" data-status="warn">
				<strong>Performance Optimization:</strong> Enable object caching to reduce database queries by ~40% and improve TTFB.
			</li>
			<li class="roko-recommendation-item roko-rec-warn" data-status="warn">
				<strong>Security Headers Missing:</strong> Add HSTS and CSP headers to improve security score from 72 to 89.
			</li>
			<li class="roko-recommendation-item roko-rec-info" data-status="ok">
				<strong>Image Optimization:</strong> 23 images could be compressed to save 2.1 MB and improve LCP by 0.3s.
			</li>
			<li class="roko-recommendation-item roko-rec-info" data-status="ok">
				<strong>Unused CSS Removal:</strong> 47 KB of unused CSS detected in theme and plugin stylesheets.
			</li>
		</ul>
	</div>
</div>

<!-- Smart Actions -->
<div class="roko-card roko-smart-actions-card roko-mt-4">
	<div class="roko-card-header">
		<div class="roko-d-flex roko-align-items-center">
			<h3 class="roko-card-title">Smart Actions</h3>
			<button class="roko-info-btn roko-ml-2" title="How AI-powered optimizations work - Click to learn more" data-docs="smart-actions">ⓘ</button>
		</div>
		<p class="roko-card-subtitle">AI-powered optimizations executed instantly.</p>
	</div>
	<div class="roko-card-body">
		<div class="roko-smart-actions-grid">
			
			<!-- Primary recommended action (rotates based on top AI recommendation) -->
			<div class="roko-smart-action roko-smart-action-primary">
				<button class="roko-button roko-button-primary roko-smart-action-btn" data-action="optimize-db">
					<span class="roko-btn-text">Optimize DB Now</span>
					<span class="roko-btn-spinner" style="display: none;">
						<svg class="roko-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M21 12a9 9 0 1 1-9-9"/>
						</svg>
						Running…
					</span>
					<span class="roko-btn-success" style="display: none;">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M20 6L9 17l-5-5"/>
						</svg>
						Done
					</span>
				</button>
				<p class="roko-action-description">Clean transients, optimize tables</p>
			</div>

			<!-- Secondary actions -->
			<div class="roko-smart-action">
				<button class="roko-button roko-button-outline roko-smart-action-btn" data-action="resolve-conflicts">
					<span class="roko-btn-text">Scan & Fix Security</span>
					<span class="roko-btn-spinner" style="display: none;">
						<svg class="roko-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M21 12a9 9 0 1 1-9-9"/>
						</svg>
						Running…
					</span>
					<span class="roko-btn-success" style="display: none;">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M20 6L9 17l-5-5"/>
						</svg>
						Done
					</span>
				</button>
				<p class="roko-action-description">Apply safe-headers & key rotation</p>
			</div>

			<div class="roko-smart-action">
				<button class="roko-button roko-button-outline roko-smart-action-btn" data-action="fix-conflicts">
					<span class="roko-btn-text">Resolve Plugin Conflicts</span>
					<span class="roko-btn-spinner" style="display: none;">
						<svg class="roko-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M21 12a9 9 0 1 1-9-9"/>
						</svg>
						Running…
					</span>
					<span class="roko-btn-success" style="display: none;">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
							<path d="M20 6L9 17l-5-5"/>
						</svg>
						Done
					</span>
				</button>
				<p class="roko-action-description">Diagnose fatal error loop before updates</p>
			</div>

		</div>
	</div>
</div>

<!-- Celebratory State (Alternative when everything is good) -->
<!-- 
<div class="roko-card roko-card-success roko-celebration-card" style="display: none;">
  <div class="roko-card-body roko-text-center">
    <div class="roko-celebration-icon roko-mb-3">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <path d="M9 11l3 3L22 4"/>
      </svg>
    </div>
    <h4 class="roko-mb-2">Everything looks great! 🎉</h4>
    <p class="roko-text-muted">Your site is secure, fast, and optimized. No action needed right now.</p>
    <button class="roko-button roko-button-outline roko-mt-3">View detailed reports</button>
  </div>
</div>
-->

<style>
/* CSS Custom Properties for Dark Mode Readiness */
:root {
	--roko-green: #00a32a;
	--roko-yellow: #dba617;
	--roko-red: #d63638;
	--roko-blue: #2271b1;
	--roko-grey: #646970;
	--roko-grey-light: #c3c4c7;
	--roko-orange: #f56e28;
}

/* Dark mode overrides (future-ready) */
.roko-admin.dark-mode {
	--roko-green: #4ade80;
	--roko-yellow: #fbbf24;
	--roko-red: #f87171;
	--roko-blue: #60a5fa;
	--roko-grey: #9ca3af;
	--roko-grey-light: #6b7280;
}

/* Critical Notice Bar */
.roko-critical-notice {
	background: var(--roko-red);
	color: white;
	padding: 12px 0;
	margin-bottom: 20px;
	border-radius: 4px;
}

.roko-critical-notice-content {
	display: flex;
	align-items: center;
	justify-content: center;
	position: relative;
	cursor: pointer;
}

.roko-critical-icon {
	margin-right: 8px;
}

.roko-critical-dismiss {
	position: absolute;
	right: 16px;
	background: none;
	border: none;
	color: white;
	font-size: 18px;
	cursor: pointer;
	padding: 0;
	width: 20px;
	height: 20px;
}

/* Overview card styling */
.roko-overview-card {
	min-height: 200px;
	display: flex;
	flex-direction: column;
}

.roko-overview-card .roko-card-body {
	flex: 1;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

/* Card icons */
.roko-card-icon {
	flex-shrink: 0;
}

/* Info buttons with focus ring */
.roko-info-btn {
	background: none;
	border: none;
	color: var(--roko-grey);
	font-size: 12px;
	cursor: help;
	padding: 2px 4px;
	border-radius: 2px;
	transition: all 0.2s ease;
}

.roko-info-btn:hover {
	background: #f0f0f1;
	color: var(--roko-grey);
}

.roko-info-btn:focus {
	outline: 2px solid var(--roko-blue);
	outline-offset: 1px;
}

/* Health score with animation */
.roko-health-score-wrapper {
	display: flex;
	align-items: center;
}

.roko-health-score {
	font-size: 36px;
	font-weight: 700;
	color: var(--roko-green);
	line-height: 1;
}

/* Alert badge pill with icon */
.roko-alert-badge-pill {
	background: var(--roko-red);
	color: white;
	padding: 4px 8px;
	border-radius: 12px;
	font-size: 11px;
	font-weight: 600;
	display: flex;
	align-items: center;
	gap: 4px;
}

/* Vitals sparkline */
.roko-vitals-sparkline-container {
	display: flex;
	align-items: center;
	justify-content: space-between;
}

.roko-vitals-sparkline {
	flex: 1;
	max-width: 100px;
}

.roko-trend-summary {
	text-align: right;
}

.roko-trend-direction {
	font-weight: 600;
	font-size: 13px;
}

.roko-trend-up {
	color: var(--roko-green);
}

.roko-trend-down {
	color: var(--roko-red);
}

/* Collapsible AI Analysis */
.roko-collapsible-header {
	cursor: pointer;
	transition: background-color 0.2s ease;
}

.roko-collapsible-header:hover {
	background: #f6f7f7;
}

.roko-collapsible-header:focus {
	outline: 2px solid var(--roko-blue);
	outline-offset: -2px;
}

.roko-chevron {
	transition: transform 0.2s ease;
}

.roko-collapsible-header[aria-expanded="true"] .roko-chevron {
	transform: rotate(90deg);
}

.roko-collapsible-content {
	transition: all 0.3s ease;
}

/* Collapsed state severity dots */
.roko-severity-dots {
	display: flex;
	gap: 16px;
	font-size: 12px;
	color: var(--roko-grey);
}

.roko-severity-dot {
	font-weight: 600;
}

.roko-severity-critical {
	color: var(--roko-red);
}

.roko-severity-warn {
	color: var(--roko-yellow);
}

.roko-severity-info {
	color: var(--roko-green);
}

/* Recommendation list with left borders */
.roko-recommendation-list {
	list-style: none;
	padding: 0;
	margin: 0;
}

.roko-recommendation-item {
	padding: 12px 16px;
	margin-bottom: 8px;
	background: #f9f9f9;
	border-radius: 4px;
	border-left: 4px solid transparent;
	line-height: 1.5;
}

.roko-recommendation-item:last-child {
	margin-bottom: 0;
}

.roko-rec-critical {
	border-left-color: var(--roko-red);
}

.roko-rec-warn {
	border-left-color: var(--roko-yellow);
}

.roko-rec-info {
	border-left-color: var(--roko-green);
}

/* Smart Actions */
.roko-smart-actions-card {
	background: linear-gradient(135deg, #ffffff 0%, #f6f7f7 100%);
}

.roko-smart-actions-grid {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
	gap: 20px;
}

.roko-smart-action {
	text-align: center;
	padding: 16px;
	border-radius: 4px;
	background: white;
	border: 1px solid #e0e0e0;
}

.roko-smart-action-primary {
	border-color: var(--roko-blue);
	background: linear-gradient(135deg, #f0f6fc 0%, #ffffff 100%);
}

.roko-action-description {
	margin: 8px 0 0 0;
	font-size: 12px;
	color: var(--roko-grey);
	line-height: 1.4;
}

/* Smart Action Button States */
.roko-smart-action-btn {
	position: relative;
	min-width: 160px;
}

.roko-btn-spinner,
.roko-btn-success {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
}

/* Spinner animation */
.roko-spinner {
	animation: roko-spin 1s linear infinite;
}

@keyframes roko-spin {
	from { transform: rotate(0deg); }
	to { transform: rotate(360deg); }
}

/* Count-up animation */
@keyframes roko-countup {
	from { opacity: 0; transform: translateY(10px); }
	to { opacity: 1; transform: translateY(0); }
}

.roko-health-score.animate {
	animation: roko-countup 0.6s ease-out;
}

/* Button sizing */
.roko-button-small {
	padding: 6px 12px;
	font-size: 12px;
	min-height: 28px;
}

/* Focus rings for accessibility */
.roko-button:focus,
.roko-collapsible-header:focus {
	outline: 2px solid var(--roko-blue);
	outline-offset: 2px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
	.roko-card-group {
		display: block;
	}
	
	.roko-overview-card {
		margin-bottom: 16px;
		min-height: auto;
	}
	
	.roko-smart-actions-grid {
		grid-template-columns: 1fr;
		gap: 12px;
	}
	
	.roko-vitals-sparkline-container {
		flex-direction: column;
		align-items: flex-start;
		gap: 8px;
	}
	
	.roko-severity-dots {
		flex-direction: column;
		gap: 4px;
	}
}

/* Utility classes */
.roko-w-100 { width: 100%; }
.roko-ml-2 { margin-left: 8px; }
.roko-mr-2 { margin-right: 8px; }
.roko-mb-0 { margin-bottom: 0; }
.roko-mb-2 { margin-bottom: 8px; }
.roko-mb-3 { margin-bottom: 12px; }
.roko-mt-3 { margin-top: 12px; }
.roko-text-center { text-align: center; }
.roko-d-flex { display: flex; }
.roko-align-items-center { align-items: center; }
.roko-justify-content-between { justify-content: space-between; }
</style>

<script>
// Enhanced Overview functionality
document.addEventListener('DOMContentLoaded', function() {
	
	// Health score count-up animation
	const healthScore = document.querySelector('.roko-health-score');
	if (healthScore) {
		const target = parseInt(healthScore.dataset.target);
		let current = 0;
		const increment = target / 60; // 60 frames for 0.6s at 60fps
		const timer = setInterval(() => {
			current += increment;
			if (current >= target) {
				current = target;
				clearInterval(timer);
				healthScore.classList.add('animate');
			}
			healthScore.textContent = Math.floor(current);
		}, 10);
	}

	// Critical notice functionality
	const criticalNotice = document.getElementById('roko-critical-notice');
	const criticalItems = document.querySelectorAll('[data-status="critical"]');
	
	if (criticalItems.length > 0 && criticalNotice) {
		criticalNotice.style.display = 'block';
		criticalNotice.querySelector('.roko-critical-text').textContent = 
			`${criticalItems.length} critical issue${criticalItems.length > 1 ? 's' : ''} require attention`;
		
		// Click to scroll to first critical item
		criticalNotice.addEventListener('click', function(e) {
			if (!e.target.classList.contains('roko-critical-dismiss')) {
				const firstCritical = criticalItems[0];
				firstCritical.scrollIntoView({ behavior: 'smooth', block: 'center' });
				firstCritical.style.animation = 'roko-highlight 2s ease-in-out';
			}
		});
		
		// Dismiss notice
		criticalNotice.querySelector('.roko-critical-dismiss').addEventListener('click', function(e) {
			e.stopPropagation();
			criticalNotice.style.display = 'none';
		});
	}

	// Collapsible AI Analysis functionality
	const collapsibleHeader = document.querySelector('.roko-collapsible-header');
	const collapsibleContent = document.querySelector('.roko-collapsible-content');
	
	if (collapsibleHeader && collapsibleContent) {
		collapsibleHeader.addEventListener('click', function() {
			const isExpanded = this.getAttribute('aria-expanded') === 'true';
			
			this.setAttribute('aria-expanded', !isExpanded);
			
			if (isExpanded) {
				collapsibleContent.style.display = 'none';
			} else {
				collapsibleContent.style.display = 'block';
			}
		});
		
		// Keyboard navigation
		collapsibleHeader.addEventListener('keydown', function(e) {
			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				this.click();
			}
		});
	}

	// Smart Action button functionality
	const actionButtons = document.querySelectorAll('.roko-smart-action-btn');
	
	actionButtons.forEach(button => {
		button.addEventListener('click', function() {
			const textSpan = this.querySelector('.roko-btn-text');
			const spinnerSpan = this.querySelector('.roko-btn-spinner');
			const successSpan = this.querySelector('.roko-btn-success');
			
			// Show spinner
			textSpan.style.display = 'none';
			spinnerSpan.style.display = 'flex';
			this.disabled = true;
			
			// Simulate action completion after 2-4 seconds
			const delay = Math.random() * 2000 + 2000;
			
			setTimeout(() => {
				// Show success
				spinnerSpan.style.display = 'none';
				successSpan.style.display = 'flex';
				
				// Reset after 3 seconds
				setTimeout(() => {
					successSpan.style.display = 'none';
					textSpan.style.display = 'inline';
					this.disabled = false;
				}, 3000);
			}, delay);
		});
	});

	// Info button functionality (placeholder for docs integration)
	const infoButtons = document.querySelectorAll('.roko-info-btn');
	infoButtons.forEach(button => {
		button.addEventListener('click', function() {
			const docsSection = this.dataset.docs;
			console.log(`Opening docs for: ${docsSection}`);
			// Integrate with your docs system here
		});
	});
});

// Highlight animation for critical items
const style = document.createElement('style');
style.textContent = `
	@keyframes roko-highlight {
		0%, 100% { background-color: transparent; }
		50% { background-color: rgba(214, 54, 56, 0.1); }
	}
`;
document.head.appendChild(style);
</script>