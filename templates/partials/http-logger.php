<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">HTTP/API Request Logger</h3>
    <p class="roko-card-subtitle">Last 30 outgoing HTTP calls</p>
  </div>
  <div class="roko-card-body">
    <table class="roko-table">
      <thead>
        <tr>
          <th>Date/Time</th>
          <th>Caller (Function)</th>
          <th>File:Line</th>
          <th>Time (ms)</th>
          <th>Blocking?</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr class="roko-card-success">
          <td>2025-06-23 10:15:32</td>
          <td>send_ping</td>
          <td>ping.php:45</td>
          <td>12</td>
          <td>Yes</td>
          <td><span class="roko-status roko-status-success">200</span></td>
        </tr>
        <tr>
          <td>2025-06-23 10:14:08</td>
          <td>fetch_metrics</td>
          <td>metrics.php:102</td>
          <td>87</td>
          <td>No</td>
          <td><span class="roko-status roko-status-success">200</span></td>
        </tr>
        <tr>
          <td>2025-06-23 10:13:44</td>
          <td>update_stats</td>
          <td>stats.php:58</td>
          <td>230</td>
          <td>Yes</td>
          <td><span class="roko-status roko-status-success">200</span></td>
        </tr>
        <tr>
          <td>2025-06-23 10:12:07</td>
          <td>notify_admin</td>
          <td>notify.php:22</td>
          <td>412</td>
          <td>No</td>
          <td><span class="roko-status roko-status-error">500</span></td>
        </tr>
        <tr class="roko-card-error">
          <td>2025-06-23 10:11:33</td>
          <td>backup_check</td>
          <td>backup.php:78</td>
          <td>1203</td>
          <td>Yes</td>
          <td><span class="roko-status roko-status-error">502</span></td>
        </tr>
        <!-- ...additional 25 rows... -->
      </tbody>
    </table>
    <div class="roko-mt-4">
      <strong>Fastest call:</strong> 12ms (send_ping)<br>
      <strong>Slowest call:</strong> 1203ms (backup_check)
    </div>
    <button class="roko-button" id="roko-clear-http-log">Clear Log</button>
  </div>
</div>