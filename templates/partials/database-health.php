<?php
// File: partials/database-performance.php
?>
<div class="roko-card">
	<div class="roko-card-header">
	<h3 class="roko-card-title">Database Performance</h3>
	<p class="roko-card-subtitle">Size, overhead, slow queries & optimization</p>
	</div>
	<div class="roko-card-body">

	<!-- Summary Metrics -->
	<div class="roko-site-details-grid">
		<div class="roko-detail-card">
		<h4>Total Size</h4>
		<p><strong>120 MB</strong></p>
		<small>12% Overhead</small>
		</div>
		<div class="roko-detail-card">
		<h4>Slow Queries</h4>
		<p><strong>15</strong></p>
		<small>Avg: 45 ms</small>
		<button class="roko-button-small roko-button-outline">View Details</button>
		</div>
		<div class="roko-detail-card">
		<h4>Autoloaded Options</h4>
		<p><strong>42</strong> items</p>
		<small>512 KB</small>
		</div>
	</div>

	<!-- Table Breakdown -->
	<h4 class="roko-mt-6">Table Breakdown</h4>
	<table class="roko-table">
		<thead>
		<tr>
			<th>Table</th>
			<th>Rows</th>
			<th>Size (MB)</th>
			<th>Overhead (MB)</th>
			<th>Action</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>wp_options</td>
			<td>12,345</td>
			<td>45.20</td>
			<td>5.12</td>
			<td><button class="roko-button-small">Optimize</button></td>
		</tr>
		<tr>
			<td>wp_posts</td>
			<td>2,340</td>
			<td>22.50</td>
			<td>1.00</td>
			<td><button class="roko-button-small">Optimize</button></td>
		</tr>
		<tr>
			<td>wp_postmeta</td>
			<td>34,567</td>
			<td>30.75</td>
			<td>2.76</td>
			<td><button class="roko-button-small">Optimize</button></td>
		</tr>
		</tbody>
	</table>

	<!-- Bulk Actions -->
	<div class="roko-mt-6 roko-d-flex" style="gap:8px;">
		<button class="roko-button">Optimize All Tables</button>
		<button class="roko-button roko-button-outline">Clear Transients</button>
		<button class="roko-button roko-button-outline">Clean Expired Sessions</button>
	</div>

	</div>
</div>
