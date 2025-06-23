<div class="roko-card">
	<div class="roko-card-header">
		<h3 class="roko-card-title">Dashboard Settings</h3>
		<p class="roko-card-subtitle">Configure your Roko Intelligence dashboard preferences</p>
	</div>
	<div class="roko-card-body">
		<form method="post" action="options.php">
			<?php settings_fields( 'roko_settings' ); ?>
			<?php do_settings_sections( 'roko_settings' ); ?>

			<div class="roko-mb-4">
				<label for="roko_refresh_interval">Auto-refresh Interval</label>
				<select id="roko_refresh_interval" name="roko_refresh_interval">
					<option value="30">30 seconds</option>
					<option value="60">1 minute</option>
					<option value="300">5 minutes</option>
					<option value="0">Disabled</option>
				</select>
				<small>How often to automatically refresh dashboard data</small>
			</div>

			<div class="roko-mb-4">
				<label for="roko_default_view">Default View</label>
				<select id="roko_default_view" name="roko_default_view">
					<option value="overview">Overview</option>
					<option value="performance">Performance</option>
					<option value="security">Security</option>
					<option value="rum">Real-User Monitoring</option>
				</select>
				<small>Which tab to show when opening the dashboard</small>
			</div>

			<div class="roko-mb-4">
				<label for="roko_notifications">Email Notifications</label>
				<select id="roko_notifications" name="roko_notifications">
					<option value="all">All Events</option>
					<option value="critical">Critical Only</option>
					<option value="none">Disabled</option>
				</select>
				<small>Types of events to receive email notifications for</small>
			</div>

			<div class="roko-mb-4">
				<label for="roko_enable_rum">Enable Real-User Monitoring</label>
				<input type="checkbox" id="roko_enable_rum" name="roko_enable_rum" value="1" <?php checked( 1, get_option( 'roko_enable_rum', 0 ) ); ?> />
			</div>

			<div class="roko-mb-4">
				<label for="roko_enable_errors">Enable Error & Debug Collection</label>
				<input type="checkbox" id="roko_enable_errors" name="roko_enable_errors" value="1" <?php checked( 1, get_option( 'roko_enable_errors', 0 ) ); ?> />
			</div>

			<?php submit_button( 'Save Settings' ); ?>
		</form>
	</div>
</div>

