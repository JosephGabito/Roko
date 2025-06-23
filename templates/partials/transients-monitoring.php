<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">Transients Monitoring</h3>
    <p class="roko-card-subtitle">View and manage WordPress transients</p>
  </div>
  <div class="roko-card-body">
    <!-- Summary -->
    <div class="roko-site-details-grid">
      <div class="roko-detail-card">
        <h4>Total Transients</h4>
        <p><strong>27</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Expired Transients</h4>
        <p><strong>5</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Next Expiry</h4>
        <p><strong>in 2 hours</strong></p>
      </div>
    </div>

    <!-- Transients Table -->
    <h4 class="roko-mt-6">Transient Details</h4>
    <table class="roko-table">
      <thead>
        <tr><th>Name</th><th>Timeout</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>wp_transient_analytics_cache</td>
          <td>2025-06-24 14:00</td>
          <td><span class="roko-status roko-status-success">Active</span></td>
          <td><button class="roko-button-small">Delete</button></td>
        </tr>
        <tr>
          <td>wp_transient_session_data</td>
          <td>Expired</td>
          <td><span class="roko-status roko-status-warning">Expired</span></td>
          <td><button class="roko-button-small roko-button-outline">Delete</button></td>
        </tr>
      </tbody>
    </table>

    <!-- Bulk Actions -->
    <div class="roko-mt-6 roko-d-flex" style="gap:8px;">
      <button class="roko-button">Delete All Expired</button>
      <button class="roko-button roko-button-outline">Refresh All</button>
    </div>
  </div>
</div>