<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="roko-card" id="roko-performance-dashboard">
  <div class="roko-card-header roko-d-flex roko-justify-content-between roko-align-items-center">
    <div>
      <h3 class="roko-card-title"><?php esc_html_e( 'Performance Pit Stop', 'roko' ); ?></h3>
      <p class="roko-card-subtitle roko-mr-4">
        <?php esc_html_e( 'Need to know what\'s slowing you down or helping you speed ahead? Roko lays out the winners and the troublemakers in plain sight, so you can make smart decisions to keep your site smooth and fast', 'roko' ); ?>
      </p>
    </div>
    <!-- View mode toggle -->
    <div class="roko-view-toggle" role="group" aria-label="<?php esc_attr_e( 'View mode', 'roko' ); ?>">
      <button id="roko-perf-pill-all" class="roko-button roko-button-outline active" aria-pressed="true"><?php esc_html_e( 'Show all metrics', 'roko' ); ?></button>
      <button id="roko-perf-pill-need" class="roko-button roko-button-clear" aria-pressed="false"><?php esc_html_e( 'Show required actions', 'roko' ); ?></button>
    </div>
  </div>

  <div class="roko-card-body">
    <!-- Performance details grid -->
    <div class="roko-security-details-grid" id="roko-performance-grid">
      
      <!-- Card 1 - Core Web Vitals -->
      <div class="roko-detail-card">
        <h4 role="status">Core Web Vitals</h4>
        <p class="roko-text-muted roko-text-small">Lab data from Google PageSpeed Insights</p>
        
        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Largest Contentful Paint - How we measure & recommended target">LCP</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">1.9 s</strong>
              <span class="roko-badge roko-badge-success" title="Sampled from 847 PageSpeed Insights tests">Good</span>
            </div>
          </div>
          <p class="performance-item-note">Target ≤ 2.5 s</p>
        </div>

        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Interaction to Next Paint - How we measure & recommended target">INP (was FID)</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">220 ms</strong>
              <span class="roko-badge roko-badge-warning" title="Sampled from 847 PageSpeed Insights tests">Warn</span>
            </div>
          </div>
          <p class="performance-item-note">Good is ≤ 200 ms</p>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Cumulative Layout Shift - How we measure & recommended target">CLS</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">0.03</strong>
              <span class="roko-badge roko-badge-success" title="Sampled from 847 PageSpeed Insights tests">Good</span>
            </div>
          </div>
          <p class="performance-item-note">Keep ≤ 0.10</p>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Time to First Byte - How we measure & recommended target">TTFB</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">380 ms</strong>
              <span class="roko-badge roko-badge-success" title="Sampled from 847 PageSpeed Insights tests">Good</span>
            </div>
          </div>
          <p class="performance-item-note">Under 600 ms keeps Google happy</p>
        </div>

        <p class="roko-text-muted roko-text-small roko-mt-3">Lab data – measured on 4G Moto G4 (PSI).</p>
      </div>

      <!-- Card 2 - Speed Metrics -->
      <div class="roko-detail-card">
        <h4 role="status">Speed Metrics</h4>
        <p class="roko-text-muted roko-text-small">Performance scores from Google PageSpeed Insights</p>
        
        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Speed Index - How we measure & recommended target">Speed Index</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">2.7 s</strong>
              <span class="roko-badge roko-badge-success" title="Based on Lighthouse audit">Good</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Total Blocking Time - How we measure & recommended target">Total Blocking Time</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">180 ms</strong>
              <span class="roko-badge roko-badge-warning" title="Based on Lighthouse audit">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="PageSpeed Insights Mobile Score - How we measure & recommended target">PSI Mobile score</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">72 / 100</strong>
              <span class="roko-badge roko-badge-warning" title="Latest PageSpeed Insights report">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="PageSpeed Insights Desktop Score - How we measure & recommended target">PSI Desktop score</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">93 / 100</strong>
              <span class="roko-badge roko-badge-success" title="Latest PageSpeed Insights report">Good</span>
            </div>
          </div>
        </div>

        <div class="roko-show-more-toggle roko-mt-3" style="text-align: center; border-top: 1px solid #f0f0f1; padding-top: 12px;">
          <button class="roko-button roko-button-clear roko-toggle-more-items">
            <span class="show-text">Show more metrics</span>
            <span class="hide-text" style="display: none;">Show less</span>
          </button>
        </div>

        <!-- Hidden metrics -->
        <div class="performance-item roko-hidden-item" data-status="ok" style="display: none;">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label">First Contentful Paint</span>
            <strong>1.2 s</strong>
          </div>
        </div>

        <div class="performance-item roko-hidden-item" data-status="warn" style="display: none;">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label">Time to Interactive</span>
            <strong>3.8 s</strong>
          </div>
        </div>
      </div>

      <!-- Card 3 - Real-User CrUX -->
      <div class="roko-detail-card">
        <h4 role="status">Real-User CrUX</h4>
        <p class="roko-text-muted roko-text-small">Field data from real users (75th percentile)</p>
        
        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Real user Largest Contentful Paint data">LCP</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">2.3 s</strong>
              <span class="roko-badge roko-badge-success" title="From 28-day Chrome UX Report dataset">Good</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Real user Interaction to Next Paint data">INP</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">260 ms</strong>
              <span class="roko-badge roko-badge-warning" title="From 28-day Chrome UX Report dataset">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Real user Cumulative Layout Shift data">CLS</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">0.07</strong>
              <span class="roko-badge roko-badge-success" title="From 28-day Chrome UX Report dataset">Good</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 - Plugin Heatmap -->
      <div class="roko-detail-card">
        <h4 role="status">Plugin Heatmap</h4>
        <p class="roko-text-muted roko-text-small">Based on 24-h sample, 1% of requests</p>
        
        <div class="roko-plugin-table">
          <div class="plugin-row" data-status="warn">
            <div class="plugin-rank">1</div>
            <div class="plugin-name">Elementor</div>
            <div class="plugin-time"><strong>124 ms</strong></div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-warning" title="Sampled from 495 requests">Warn</span></div>
          </div>

          <div class="plugin-row" data-status="warn">
            <div class="plugin-rank">2</div>
            <div class="plugin-name">WooCommerce</div>
            <div class="plugin-time"><strong>92 ms</strong></div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-warning" title="Sampled from 495 requests">Warn</span></div>
          </div>

          <div class="plugin-row" data-status="ok">
            <div class="plugin-rank">3</div>
            <div class="plugin-name">Yoast SEO</div>
            <div class="plugin-time"><strong>47 ms</strong></div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>

          <div class="plugin-row" data-status="ok">
            <div class="plugin-rank">4</div>
            <div class="plugin-name">Contact Form 7</div>
            <div class="plugin-time">32 ms</div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>

          <div class="plugin-row" data-status="ok">
            <div class="plugin-rank">5</div>
            <div class="plugin-name">Jetpack</div>
            <div class="plugin-time">24 ms</div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>

          <!-- Hidden plugins -->
          <div class="plugin-row roko-hidden-item" data-status="ok" style="display: none;">
            <div class="plugin-rank">6</div>
            <div class="plugin-name">Akismet</div>
            <div class="plugin-time">18 ms</div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>

          <div class="plugin-row roko-hidden-item" data-status="ok" style="display: none;">
            <div class="plugin-rank">7</div>
            <div class="plugin-name">WP Rocket</div>
            <div class="plugin-time">12 ms</div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>

          <div class="plugin-row roko-hidden-item" data-status="ok" style="display: none;">
            <div class="plugin-rank">8</div>
            <div class="plugin-name">UpdraftPlus</div>
            <div class="plugin-time">8 ms</div>
            <div class="plugin-badge"><span class="roko-badge roko-badge-success" title="Sampled from 495 requests">Good</span></div>
          </div>
        </div>

        <div class="roko-show-more-toggle roko-mt-3" style="text-align: center; border-top: 1px solid #f0f0f1; padding-top: 12px;">
          <button class="roko-button roko-button-clear roko-toggle-more-items">
            <span class="show-text">Show more 3 plugins</span>
            <span class="hide-text" style="display: none;">Show less</span>
          </button>
        </div>
      </div>

      <!-- Card 5 - DB Query Breakdown -->
      <div class="roko-detail-card">
        <h4 role="status">DB Query Breakdown</h4>
        <p class="roko-text-muted roko-text-small">Database performance analysis</p>
        
        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Total database queries per request">Total queries / request</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">188</strong>
              <span class="roko-badge roko-badge-warning" title="Measured across 24-hour period">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="critical">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Slowest single database query">Slowest single query</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">240 ms</strong>
              <span class="roko-badge roko-badge-error" title="Measured across 24-hour period">Critical</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Total time spent on database queries">Total query time</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">110 ms</strong>
              <span class="roko-badge roko-badge-success" title="Measured across 24-hour period">Good</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Plugin causing most database queries">Top offender</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">WooCommerce (38%)</strong>
              <span class="roko-badge roko-badge-warning" title="Query analysis from Query Monitor">Warn</span>
            </div>
          </div>
        </div>

        <div class="roko-mt-3" style="text-align: center;">
          <a href="#" class="roko-text-primary" style="font-size: 12px;">View full query log</a>
        </div>
      </div>

      <!-- Card 6 - Memory / Cache -->
      <div class="roko-detail-card">
        <h4 role="status">Memory / Cache</h4>
        <p class="roko-text-muted roko-text-small">Server resource usage and caching efficiency</p>
        
        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Peak PHP memory usage per request">Peak PHP memory</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">116 MB</strong>
              <span class="roko-badge roko-badge-warning" title="Monitored over 24-hour period">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Object cache hit rate efficiency">Object-cache hit-rate</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">89%</strong>
              <span class="roko-badge roko-badge-success" title="Redis cache statistics">Good</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="OPCache bytecode cache hit rate">OPCache hit-rate</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">N/A</strong>
              <span class="roko-badge" style="background: #f0f0f1; color: #646970;" title="OPCache not detected">Not available</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 7 - Network & Certificate -->
      <div class="roko-detail-card">
        <h4 role="status">Network & Certificate</h4>
        <p class="roko-text-muted roko-text-small">Network protocols and SSL certificate status</p>
        
        <div class="performance-item" data-status="warn">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="SSL certificate expiration date">TLS cert expiry</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">23 days left</strong>
              <span class="roko-badge roko-badge-warning" title="Certificate expires on March 15, 2024">Warn</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="HTTP/2 protocol support">HTTP/2 enabled</span>
            <div class="roko-d-flex roko-align-items-center">
              <strong class="roko-mr-2">Yes</strong>
              <span class="roko-badge roko-badge-success" title="Server supports HTTP/2 protocol">Good</span>
            </div>
          </div>
        </div>

        <div class="performance-item" data-status="ok">
          <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
            <span class="performance-item-label" title="Server response time trend over 7 days">Server response trend (7d)</span>
            <div class="roko-d-flex roko-align-items-center">
              <div class="mini-sparkline roko-mr-2" title="Daily TTFB: 380ms, 375ms, 390ms, 372ms, 365ms, 360ms, 358ms">
                <!-- Green trending down sparkline -->
                <svg width="50" height="20" viewBox="0 0 50 20">
                  <polyline fill="none" stroke="#00a32a" stroke-width="2" points="0,15 7,14 14,16 21,13 28,11 35,9 42,8 49,7"/>
                </svg>
              </div>
              <strong class="roko-mr-2">↓5%</strong>
              <span class="roko-badge roko-badge-success" title="7-day TTFB trend analysis">Good</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty card for 3x3 grid -->
      <div class="roko-detail-card" style="visibility: hidden;"></div>

    </div>
  </div>
</div>

<style>
/* Performance-specific styling */
.performance-item {
  padding: 12px 0;
  border-bottom: 1px solid #f0f0f1;
}

.performance-item:last-child {
  border-bottom: none;
}

.performance-item-label {
  font-weight: 500;
  color: #1d2327;
  cursor: help;
}

.performance-item-note {
  font-size: 12px;
  color: #646970;
  margin: 4px 0 0 0;
  line-height: 1.4;
}

/* Plugin heatmap table */
.roko-plugin-table {
  margin: 16px 0;
}

.plugin-row {
  display: grid;
  grid-template-columns: 30px 1fr auto auto;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
  border-bottom: 1px solid #f0f0f1;
}

.plugin-row:last-child {
  border-bottom: none;
}

.plugin-rank {
  font-weight: 600;
  color: #646970;
  text-align: center;
}

.plugin-name {
  font-weight: 500;
  color: #1d2327;
}

.plugin-time {
  font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
  font-size: 13px;
  text-align: right;
}

.plugin-badge {
  text-align: right;
}

/* Mini sparkline */
.mini-sparkline {
  display: inline-block;
  vertical-align: middle;
}

/* Sparkline colors based on trend */
.trend-up polyline {
  stroke: #d63638; /* Red for trending up (bad) */
}

.trend-down polyline {
  stroke: #00a32a; /* Green for trending down (good) */
}

/* Status-based colors for performance items */
.performance-item[data-status="critical"] .performance-item-label {
  color: #d63638;
}

.performance-item[data-status="warn"] .performance-item-label {
  color: #996800;
}

.performance-item[data-status="ok"] .performance-item-label {
  color: #00a32a;
}

/* Hide OK items when in "required actions" mode */
.hide-ok [data-status="ok"] {
  display: none;
}
</style>