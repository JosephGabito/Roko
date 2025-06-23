<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">Scheduled Actions</h3>
    <p class="roko-card-subtitle">Manage Action Scheduler & WP-Cron jobs</p>
  </div>
  <div class="roko-card-body">
    <div class="roko-site-details-grid">
      <div class="roko-detail-card">
        <h4>Pending Jobs</h4>
        <p><strong>34</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Completed Jobs</h4>
        <p><strong>128</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Failed Jobs</h4>
        <p><strong>2</strong></p>
      </div>
    </div>
    <h4 class="roko-mt-6">Jobs Details</h4>
    <table class="roko-table">
      <thead>
        <tr><th>Hook</th><th>Status</th><th>Next Run</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>email_reminders</td>
          <td>Pending</td>
          <td>in 5 minutes</td>
          <td><button class="roko-button-small">Run Now</button></td>
        </tr>
        <tr>
          <td>cleanup_logs</td>
          <td>Failed</td>
          <td>–</td>
          <td><button class="roko-button-small roko-button-outline">Retry</button></td>
        </tr>
      </tbody>
    </table>
    <div class="roko-mt-4">
      <button class="roko-button">Run All Overdue</button>
      <button class="roko-button roko-button-outline">Cancel All Pending</button>
    </div>
  </div>
</div>