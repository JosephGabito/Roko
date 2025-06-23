<div class="roko-card">
	<div class="roko-card-header">
		<h3 class="roko-card-title">Real-User Monitoring</h3>
		<p class="roko-card-subtitle">Live visitor metrics & session insights</p>
	</div>
	<div class="roko-card-body">
		<div class="roko-rum-grid">
			<canvas id="roko-rum-lcp"></canvas>
			<canvas id="roko-rum-fid"></canvas>
			<canvas id="roko-rum-cls"></canvas>
		</div>
		<div id="roko-rum-geo-map"></div>
		<table class="roko-table">
			<thead><tr><th>Page</th><th>LCP</th><th>FID</th><th>CLS</th><th>Session</th></tr></thead>
			<tbody><?php foreach ( $sessions as $s ) :
				?><tr><td><?php echo esc_html( $s['path'] ); ?></td><td><?php echo esc_html( $s['lcp'] ); ?></td><td><?php echo esc_html( $s['fid'] ); ?></td><td><?php echo esc_html( $s['cls'] ); ?></td><td><a href="<?php echo esc_url( $s['replay_url'] ); ?>">Replay</a></td></tr><?php endforeach; ?></tbody>
		</table>
	</div>
</div>