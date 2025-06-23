<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">Slow Query Profiler</h3>
    <p class="roko-card-subtitle">Identify and analyze slow SQL queries</p>
  </div>
  <div class="roko-card-body">
    <div class="roko-site-details-grid">
      <div class="roko-detail-card">
        <h4>Total Queries</h4>
        <p><strong>1,102</strong></p>
      </div>
      <div class="roko-detail-card">
        <h4>Avg Query Time</h4>
        <p><strong>45 ms</strong></p>
      </div>
    </div>
    <h4 class="roko-mt-6">Top Slow Queries</h4>
    <table class="roko-table">
      <thead>
        <tr><th>Query</th><th>Time (ms)</th><th>Rows</th><th>Caller</th></tr>
      </thead>
      <tbody>
        <tr>
          <td>SELECT * FROM wp_posts WHERE post_status='publish'</td>
          <td>230</td>
          <td>120</td>
          <td>posts.php:245</td>
        </tr>
        <tr>
          <td>SELECT COUNT(*) FROM wp_postmeta</td>
          <td>185</td>
          <td>34567</td>
          <td>meta.php:78</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>