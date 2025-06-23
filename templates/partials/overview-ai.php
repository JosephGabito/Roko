<!-- Site Intelligence Overview with Actionable AI Insights -->
<div class="roko-card-group roko-mb-6">

	<!-- Performance Score Card with Detailed Insights -->
	<div class="roko-card roko-card-primary">
		<div class="roko-card-header">
			<h4 class="roko-card-title">Site Health Insights (AI Powered)</h4>
		</div>
		<div class="roko-card-body">
			<div class="roko-health-score excellent"><?php echo esc_html( $overview_score ); ?></div>
			<p class="roko-card-text">
				Your site is <strong><?php echo esc_html( $overview_health ); ?></strong>, but <strong><?php echo esc_html( $slow_plugins ); ?> plugins</strong> cause slow TTFB. <button class="roko-button-small">View Recommendations</button>
			</p>
		</div>
	</div>

	<!-- Core Web Vitals Historical Trends -->
	<div class="roko-card roko-card-success">
		<div class="roko-card-header">
			<h4 class="roko-card-title">Core Web Vitals Trends</h4>
		</div>
		<div class="roko-card-body">
			<canvas id="roko-core-vitals-chart"></canvas>
			<small>Past 30 days performance trend.</small>
		</div>
	</div>

	<!-- Proactive Security Insights -->
	<div class="roko-card roko-card-info">
		<div class="roko-card-header">
			<h4 class="roko-card-title">Security Intelligence</h4>
		</div>
		<div class="roko-card-body">
			<div class="roko-status roko-status-warning roko-mb-3">
				<div class="roko-status-dot"></div>
				Attention Required
			</div>
			<p class="roko-card-text">
				<strong><?php echo esc_html( $outdated_plugins ); ?> outdated plugins</strong> detected with known vulnerabilities.
			</p>
			<button class="roko-button roko-button-small roko-button-outline">Resolve Now</button>
		</div>
	</div>

</div>

<!-- AI-Driven Site Analysis -->
<div class="roko-card">
	<div class="roko-card-header">
		<h3 class="roko-card-title">AI Site Analysis & Recommendations</h3>
		<p class="roko-card-subtitle">Advanced analysis tailored specifically for your WordPress site.</p>
	</div>
	<div class="roko-card-body">
		<ul class="roko-recommendation-list">
			<?php foreach ( $recommendations as $rec ) : ?>
				<li>🚨 <strong><?php echo esc_html( $rec['title'] ); ?>:</strong> <?php echo wp_kses_post( $rec['description'] ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<!-- One-Click Smart Actions -->
<div class="roko-card roko-mt-4">
	<div class="roko-card-header">
		<h3 class="roko-card-title">Smart Actions</h3>
		<p class="roko-card-subtitle">AI-powered optimizations executed instantly.</p>
	</div>
	<div class="roko-card-body">
		<button class="roko-button roko-button-outline">Auto-Optimize Database</button>
		<button class="roko-button roko-button-outline">Auto-Resolve Plugin Conflicts</button>
		<button class="roko-button roko-button-outline">AI-Security Scan &amp; Fix</button>
	</div>
</div>