<div class="roko-card">
  <div class="roko-card-header">
    <h3 class="roko-card-title">🛡️ Infrastructure-Level Security</h3>
    <p class="roko-card-subtitle">Recommended: Use proper infrastructure-level protection</p>
  </div>
  <div class="roko-card-body">
    
    <div class="roko-security-notice">
      <h4>🚨 Why Infrastructure-Level Security?</h4>
      <p>Plugin-level security solutions operate <strong>after</strong> malicious requests have already reached your server. 
      For maximum protection, implement security at the infrastructure level where attacks can be blocked before they consume your server resources.</p>
    </div>

    <!-- Recommended Solutions -->
    <div class="roko-recommendations">
      <h4>🌟 Recommended Infrastructure Solutions:</h4>
      
      <div class="roko-recommendation-item">
        <h5>1. Cloudflare (Recommended)</h5>
        <ul>
          <li><strong>Layer 7 WAF:</strong> Advanced application-layer protection</li>
          <li><strong>DDoS Protection:</strong> Handles massive Layer 3/4 attacks</li>
          <li><strong>Rate Limiting:</strong> Perfect for webhook endpoints</li>
          <li><strong>Bot Management:</strong> Intelligent bot detection</li>
          <li><strong>Geographic Blocking:</strong> Country-level restrictions</li>
        </ul>
        <p><code>Internet → Cloudflare → Your Server (Attacks blocked at edge)</code></p>
      </div>

      <div class="roko-recommendation-item">
        <h5>2. Server-Level ModSecurity (Apache/Nginx)</h5>
        <ul>
          <li><strong>OWASP Core Rule Set:</strong> Community-maintained rules</li>
          <li><strong>Custom Rules:</strong> Tailored protection</li>
          <li><strong>Real-time Blocking:</strong> Before PHP/WordPress loads</li>
        </ul>
        <p><code>Internet → Apache/Nginx (ModSecurity) → WordPress</code></p>
      </div>

      <div class="roko-recommendation-item">
        <h5>3. Other Infrastructure Solutions</h5>
        <ul>
          <li><strong>AWS WAF:</strong> For AWS-hosted applications</li>
          <li><strong>Azure Application Gateway:</strong> For Azure environments</li>
          <li><strong>Sucuri WAF:</strong> WordPress-focused cloud WAF</li>
          <li><strong>Incapsula:</strong> Enterprise-grade protection</li>
        </ul>
      </div>
    </div>

    <!-- Setup Guidance -->
    <div class="roko-setup-guidance">
      <h4>🔧 Quick Setup Guide (Cloudflare Example):</h4>
      <ol>
        <li><strong>Sign up for Cloudflare</strong> and add your domain</li>
        <li><strong>Enable WAF:</strong> Firewall → WAF → Managed Rules</li>
        <li><strong>Configure Rate Limiting:</strong> Protect webhook endpoints</li>
        <li><strong>Set up Custom Rules:</strong> Block specific attack patterns</li>
        <li><strong>Enable Bot Fight Mode:</strong> Automatic bot protection</li>
        <li><strong>Monitor Analytics:</strong> Review blocked threats</li>
      </ol>
    </div>

    <!-- Performance Benefits -->
    <div class="roko-performance-benefits">
      <h4>⚡ Performance Benefits:</h4>
      <div class="roko-stats-grid">
        <div class="roko-stat-item">
          <div class="roko-stat-number roko-text-success">0ms</div>
          <div class="roko-stat-label">Server Load from Blocked Attacks</div>
        </div>
        <div class="roko-stat-item">
          <div class="roko-stat-number roko-text-info">99.9%</div>
          <div class="roko-stat-label">Uptime During DDoS</div>
        </div>
        <div class="roko-stat-item">
          <div class="roko-stat-number roko-text-warning">50TB+</div>
          <div class="roko-stat-label">Attack Traffic Handled</div>
        </div>
      </div>
    </div>

    <!-- Webhook-Specific Advice -->
    <div class="roko-webhook-advice">
      <h4>🔗 For Webhook Endpoints:</h4>
      <div class="roko-webhook-tips">
        <div class="roko-tip">
          <strong>Rate Limiting:</strong> Prevent endpoint flooding
        </div>
        <div class="roko-tip">
          <strong>IP Whitelisting:</strong> Only allow trusted sources
        </div>
        <div class="roko-tip">
          <strong>Signature Validation:</strong> Verify webhook authenticity
        </div>
        <div class="roko-tip">
          <strong>Request Size Limits:</strong> Prevent oversized payloads
        </div>
      </div>
    </div>

    <!-- Cost Comparison -->
    <div class="roko-cost-comparison">
      <h4>💰 Cost vs Benefit:</h4>
      <table class="roko-comparison-table">
        <tr>
          <th>Solution</th>
          <th>Cost</th>
          <th>Protection Level</th>
          <th>Server Load</th>
        </tr>
        <tr>
          <td>Plugin-Level WAF</td>
          <td>Free</td>
          <td>⭐⭐</td>
          <td>High (processes all requests)</td>
        </tr>
        <tr>
          <td>Cloudflare Free</td>
          <td>$0/month</td>
          <td>⭐⭐⭐⭐</td>
          <td>Zero (blocks at edge)</td>
        </tr>
        <tr>
          <td>Cloudflare Pro</td>
          <td>$20/month</td>
          <td>⭐⭐⭐⭐⭐</td>
          <td>Zero (blocks at edge)</td>
        </tr>
      </table>
    </div>

    <div class="roko-final-recommendation">
      <p><strong>🎯 Bottom Line:</strong> Invest in infrastructure-level security for real protection. 
      Your server will thank you, your users will thank you, and your sleep schedule will thank you! 😴</p>
    </div>

  </div>
</div>

<style>
.roko-security-notice {
  background: #fff3cd;
  border: 1px solid #ffeaa7;
  border-radius: 4px;
  padding: 15px;
  margin-bottom: 20px;
}

.roko-recommendation-item {
  background: #f8f9fa;
  border-left: 4px solid #007cba;
  padding: 15px;
  margin-bottom: 15px;
}

.roko-recommendation-item h5 {
  color: #007cba;
  margin-top: 0;
}

.roko-recommendation-item code {
  background: #e9ecef;
  padding: 2px 4px;
  border-radius: 3px;
  font-size: 12px;
}

.roko-setup-guidance {
  background: #d1ecf1;
  border: 1px solid #bee5eb;
  border-radius: 4px;
  padding: 15px;
  margin: 20px 0;
}

.roko-performance-benefits {
  margin: 20px 0;
}

.roko-webhook-advice {
  background: #d4edda;
  border: 1px solid #c3e6cb;
  border-radius: 4px;
  padding: 15px;
  margin: 20px 0;
}

.roko-webhook-tips {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 10px;
  margin-top: 10px;
}

.roko-tip {
  background: white;
  padding: 10px;
  border-radius: 4px;
  font-size: 14px;
}

.roko-comparison-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.roko-comparison-table th,
.roko-comparison-table td {
  border: 1px solid #ddd;
  padding: 8px;
  text-align: left;
}

.roko-comparison-table th {
  background-color: #f2f2f2;
}

.roko-final-recommendation {
  background: #e7f3ff;
  border: 1px solid #b8daff;
  border-radius: 4px;
  padding: 15px;
  margin-top: 20px;
  text-align: center;
}
</style> 