<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">Cron Monitoring</h3>
    <p class="roko-card-subtitle">View and manage scheduled WP-Cron events</p>
  </div>
  <div class="roko-card-body">
    <!-- Summary -->
    <div class="roko-site-details-grid">
      <div class="roko-detail-card">
        <h4>Scheduled Events</h4>
        <p><strong>12</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Overdue Events</h4>
        <p><strong>3</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Next Run</h4>
        <p><strong>in 10 minutes</strong></p>
      </div>
    </div>

    <!-- Cron Jobs Table -->
    <h4 class="roko-mt-6">Cron Job Details</h4>
    <table class="roko-table">
      <thead>
        <tr><th>Hook</th><th>Schedule</th><th>Next Run</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>daily_cleanup</td>
          <td>Daily at 02:00</td>
          <td>2025-06-24 02:00</td>
          <td><button class="roko-button-small">Run Now</button></td>
        </tr>
        <tr>
          <td>send_newsletter</td>
          <td>Hourly</td>
          <td>2025-06-23 18:00</td>
          <td><button class="roko-button-small roko-button-outline">Disable</button></td>
        </tr>
      </tbody>
    </table>

    <!-- Bulk Actions -->
    <div class="roko-mt-6 roko-d-flex" style="gap:8px;">
      <button class="roko-button">Run All Due</button>
      <button class="roko-button roko-button-outline">Clear All Crons</button>
    </div>
  </div>
</div>

