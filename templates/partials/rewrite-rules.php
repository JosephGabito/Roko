<div class="roko-card">
	<div class="roko-card-header">
	<h3 class="roko-card-title">Rewrite Rules & Permalinks</h3>
	<p class="roko-card-subtitle">Inspect your site's URL rewrite rules</p>
	</div>
	<div class="roko-card-body">
	<table class="roko-table">
		<thead>
		<tr><th>Pattern</th><th>Rule</th></tr>
		</thead>
		<tbody>
		<?php foreach ( get_option( 'rewrite_rules' ) as $pattern => $query ) : ?>
		<tr>
			<td><code><?php echo esc_html( $pattern ); ?></code></td>
			<td><code><?php echo esc_html( $query ); ?></code></td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<button class="roko-button" id="roko-flush-rewrite">Flush Rewrite Rules</button>
	</div>
</div>