<div class="roko-card">
	<div class="roko-card-header">
		<h3 class="roko-card-title">Performance Analysis</h3>
		<p class="roko-card-subtitle">Detailed performance metrics for your WordPress site</p>
	</div>
	<div class="roko-card-body">
		<div class="roko-site-details-grid">
			<div class="roko-detail-card">
				<h4>Core Web Vitals</h4>
				<p><strong>LCP:</strong> <?php echo esc_html( $lcp ); ?>s</p>
				<p><strong>FID:</strong> <?php echo esc_html( $fid ); ?>ms</p>
				<p><strong>CLS:</strong> <?php echo esc_html( $cls ); ?></p>
				<p><strong>TTFB:</strong> <?php echo esc_html( $ttfb ); ?>ms</p>
			</div>
			<div class="roko-detail-card">
				<h4>Speed Metrics</h4>
				<p><strong>Speed Index:</strong> <?php echo esc_html( $speed_index ); ?>s</p>
				<p><strong>Total Blocking Time:</strong> <?php echo esc_html( $tbt ); ?>ms</p>
				<p><strong>Mobile PageSpeed:</strong> <?php echo esc_html( $mobile_score ); ?>/100</p>
				<p><strong>Desktop PageSpeed:</strong> <?php echo esc_html( $desktop_score ); ?>/100</p>
			</div>
			<div class="roko-detail-card">
				<h4>Plugin Heatmap</h4>
				<canvas id="roko-plugin-heatmap"></canvas>
				<small>Plugin execution time per request</small>
			</div>
			<div class="roko-detail-card">
				<h4>DB Query Breakdown</h4>
				<ul>
					<?php foreach ( $query_stats as $table => $time ) : ?>
						<li><?php echo esc_html( $table ); ?>: <?php echo esc_html( round( $time, 2 ) ); ?>ms</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</div>
