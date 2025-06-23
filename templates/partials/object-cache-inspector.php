<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">Object Cache Inspector</h3>
    <p class="roko-card-subtitle">View and manage your object cache (Redis/Memcached)</p>
  </div>
  <div class="roko-card-body">
    <div class="roko-site-details-grid">
      <div class="roko-detail-card">
        <h4>Total Keys</h4>
        <p><strong>1,234</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Memory Usage</h4>
        <p><strong>45 MB</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Hit Rate</h4>
        <p><strong>92%</strong></p>
      </div>
    </div>
    <h4 class="roko-mt-6">Top Cached Keys</h4>
    <table class="roko-table">
      <thead>
        <tr><th>Key</th><th>TTL</th><th>Size</th><th>Action</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>cache_user_123</td>
          <td>600s</td>
          <td>2.4 KB</td>
          <td><button class="roko-button-small">Delete</button></td>
        </tr>
        <tr>
          <td>cache_posts</td>
          <td>1200s</td>
          <td>10 KB</td>
          <td><button class="roko-button-small">Delete</button></td>
        </tr>
      </tbody>
    </table>
    <div class="roko-mt-4">
      <button class="roko-button">Flush All Cache</button>
      <button class="roko-button roko-button-outline">Disconnect Cache</button>
    </div>
  </div>
</div>