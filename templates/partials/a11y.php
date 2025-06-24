<!-- Accessibility Module - Using Existing Roko Design System -->
<div class="roko-card" id="roko-accessibility-dashboard">
  
  <!-- Header using existing system -->
  <div class="roko-card-header">
    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
      <div>
        <h3 class="roko-card-title roko-d-flex roko-align-items-center">
          Accessibility
          <button class="roko-button-clear roko-p-1 roko-mr-2" title="How we compute accessibility score" aria-label="How we compute accessibility score">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2">
              <circle cx="12" cy="12" r="10"/>
              <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
              <path d="M12 17h.01"/>
            </svg>
          </button>
          <!-- Score using existing badge system -->
          <div class="roko-badge roko-badge-warning roko-ml-3" style="font-size: 14px; padding: 8px 12px; border-radius: 20px; position: relative;" id="roko-accessibility-score" aria-live="polite">
            <span class="roko-score-number" data-target="72">72</span>/100
            <!-- Trend indicator -->
            <div style="position: absolute; top: -8px; right: -8px; background: white; border: 2px solid #00a32a; border-radius: 12px; padding: 2px 6px; font-size: 10px; font-weight: 700; color: #00a32a;">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M7 14l3-3 3 3 5-5"/>
              </svg>
              +4
            </div>
          </div>
        </h3>
        <p class="roko-card-subtitle roko-mt-1">
          <button class="roko-badge roko-badge-info" style="cursor: pointer;" 
                  title="Click to view scan coverage details" 
                  aria-label="View WCAG test coverage and false-positive policy">
            WCAG 2.1 AA rules via axe-core
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M9 18l6-6-6-6"/>
            </svg>
          </button>
        </p>
      </div>
      <div>
        <button class="roko-button" id="roko-run-scan-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          Run Scan
        </button>
        
        <!-- Loading State -->
        <button class="roko-button roko-loading" style="display: none;" disabled id="roko-scan-loading">
          <span class="roko-scan-loading-text">Scanning 4/5 pages</span>
        </button>
      </div>
    </div>
  </div>

  <!-- Meta Bar using existing utilities -->
  <div style="padding: 12px 24px; background: #f6f7f7; border-bottom: 1px solid #c3c4c7; font-size: 13px; color: #646970;">
    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
      <div class="roko-d-flex roko-align-items-center" style="gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12,6 12,12 16,14"/>
        </svg>
        <span>Last scanned: 2 mins ago</span>
      </div>
      <div class="roko-d-flex roko-align-items-center" style="gap: 6px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#00a32a" stroke-width="2">
          <path d="M7 14l3-3 3 3 5-5"/>
        </svg>
        <span title="Resolved −1 | New +5">Trends: +4 since last week</span>
      </div>
    </div>
  </div>

  <!-- Stale Warning using existing error styling -->
  <div class="roko-d-flex roko-align-items-center roko-p-3" style="background: #fcf0f1; border-bottom: 1px solid #f0a5a5; color: #d63638; font-size: 13px; gap: 8px; display: none;" id="roko-stale-warning">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
      <line x1="12" y1="9" x2="12" y2="13"/>
      <line x1="12" y1="17" x2="12.01" y2="17"/>
    </svg>
    <span>Last scanned: 8 days ago (out-of-date)</span>
  </div>

  <div class="roko-card-body">
    
    <!-- Tab Navigation using existing system -->
    <nav class="roko-tab-nav roko-mb-4" id="roko-severity-nav">
      <button class="roko-button roko-button-outline" 
              data-severity="critical" 
              aria-pressed="true"
              aria-label="View critical accessibility issues"
              style="border-top: 3px solid #d63638;">
        <span aria-hidden="true">❗</span>
        Critical issues (3)
      </button>
      <button class="roko-button roko-button-clear" 
              data-severity="medium" 
              aria-pressed="false"
              aria-label="View medium priority accessibility issues">
        <span aria-hidden="true">⚠️</span>
        Medium issues (7)
      </button>
      <button class="roko-button roko-button-clear" 
              data-severity="low" 
              aria-pressed="false"
              aria-label="View low priority accessibility issues">
        <span aria-hidden="true">ℹ️</span>
        Low (12)
      </button>
    </nav>

    <!-- Table using existing system -->
    <div class="roko-table" style="margin-bottom: 0;">
      <table>
        <thead style="position: sticky; top: 116px; z-index: 9;">
          <tr>
            <th style="width: 10%;">Severity</th>
            <th style="width: 45%;">Description</th>
            <th style="width: 20%;">Location</th>
            <th style="width: 25%;">Fix</th>
          </tr>
        </thead>
        <tbody>
          
          <!-- Critical Issues using existing styling -->
          <tr class="roko-site-row" tabindex="0" role="button" aria-label="View detailed information about button accessibility issue">
            <td>
              <span class="roko-badge roko-badge-error" aria-label="Critical accessibility issue">
                ❗ Critical
              </span>
            </td>
            <td>
              <div>
                <div class="roko-site-name">Button lacks accessible name</div>
                <div class="roko-site-url">Interactive elements must have accessible names for screen readers</div>
                <button class="roko-button-clear roko-text-small roko-mt-1" title="View source code and DOM location">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>
                  </svg>
                  View source
                </button>
              </div>
            </td>
            <td>
              <button class="roko-badge roko-badge-primary" style="cursor: pointer; font-family: monospace;" 
                      title="Filter issues on this page"
                      aria-label="Filter to show only issues on checkout page">
                /checkout
              </button>
            </td>
            <td>
              <div>
                <p class="roko-text-small roko-text-muted roko-mb-2">Add aria-label or visible text to the button</p>
                <div style="background: #f6f7f7; border: 1px solid #c3c4c7; border-radius: 4px; padding: 8px; position: relative;">
                  <code class="roko-text-small" style="font-family: monospace; color: #2c3338;">&lt;button aria-label="Add to cart"&gt;+&lt;/button&gt;</code>
                  <button class="roko-button-clear roko-text-small" 
                          style="position: absolute; top: 4px; right: 4px; padding: 4px 8px;"
                          title="Copy code snippet to clipboard" 
                          aria-label="Copy code snippet to clipboard">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    Copy
                  </button>
                  <!-- Copy Success Tooltip -->
                  <div style="position: absolute; top: -32px; right: 4px; background: #00a32a; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: none;" class="roko-copy-success">
                    Snippet copied to clipboard
                  </div>
                </div>
              </div>
            </td>
          </tr>

          <tr class="roko-site-row" tabindex="0" role="button" aria-label="View detailed information about image alt text issue">
            <td>
              <span class="roko-badge roko-badge-error" aria-label="Critical accessibility issue">
                ❗ Critical
              </span>
            </td>
            <td>
              <div>
                <div class="roko-site-name">Images missing alt text</div>
                <div class="roko-site-url">All images must have alternative text for accessibility</div>
                <button class="roko-button-clear roko-text-small roko-mt-1" title="View source code and DOM location">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>
                  </svg>
                  View source
                </button>
              </div>
            </td>
            <td>
              <button class="roko-badge roko-badge-primary" style="cursor: pointer; font-family: monospace;" 
                      title="Filter issues on this page"
                      aria-label="Filter to show only issues on products page, 12 instances found">
                /products
                <span class="roko-badge" style="background: #646970; color: white; margin-left: 4px; font-size: 10px;">×12</span>
              </button>
            </td>
            <td>
              <div>
                <p class="roko-text-small roko-text-muted roko-mb-2">Add descriptive alt attribute to images</p>
                <div style="background: #f6f7f7; border: 1px solid #c3c4c7; border-radius: 4px; padding: 8px; position: relative;">
                  <code class="roko-text-small" style="font-family: monospace; color: #2c3338;">&lt;img src="product.jpg" alt="Blue cotton T-shirt"&gt;</code>
                  <button class="roko-button-clear roko-text-small" 
                          style="position: absolute; top: 4px; right: 4px; padding: 4px 8px;"
                          title="Copy code snippet to clipboard" 
                          aria-label="Copy code snippet to clipboard">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                      <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                    </svg>
                    Copy
                  </button>
                  <div style="position: absolute; top: -32px; right: 4px; background: #00a32a; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: none;" class="roko-copy-success">
                    Snippet copied to clipboard
                  </div>
                </div>
              </div>
            </td>
          </tr>

          <tr class="roko-site-row" tabindex="0" role="button" aria-label="View detailed information about form label issue">
            <td>
              <span class="roko-badge roko-badge-error" aria-label="Critical accessibility issue">
                ❗ Critical
              </span>
            </td>
            <td>
              <div>
                <div class="roko-site-name">Form missing labels</div>
                <div class="roko-site-url">Form inputs must have associated labels</div>
                <button class="roko-button-clear roko-text-small roko-mt-1" title="View source code and DOM location">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 18l6-6-6-6M8 6l-6 6 6 6"/>
                  </svg>
                  View source
                </button>
              </div>
            </td>
            <td>
              <button class="roko-badge roko-badge-primary" style="cursor: pointer; font-family: monospace;" 
                      title="Filter issues on this page"
                      aria-label="Filter to show only issues on contact page">
                /contact
              </button>
            </td>
            <td>
              <div>
                <p class="roko-text-small roko-text-muted roko-mb-2">Connect input with label using for/id attributes</p>
                <div style="background: #f6f7f7; border: 1px solid #c3c4c7; border-radius: 4px; padding: 8px; position: relative;">
                  <code class="roko-text-small" style="font-family: monospace; color: #2c3338; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" id="code-snippet-3">
                    &lt;label for="email"&gt;Email Address&lt;/label&gt;&lt;input type="email" id="email" name="email"&gt;
                  </code>
                  <div style="margin-top: 8px;">
                    <button class="roko-button-clear roko-text-small" 
                            style="padding: 2px 6px; margin-right: 8px;"
                            onclick="toggleCodeExpansion('code-snippet-3', this)">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 9l6 6 6-6"/>
                      </svg>
                      Expand
                    </button>
                    <button class="roko-button-clear roko-text-small" 
                            style="padding: 2px 6px;"
                            title="Copy code snippet to clipboard" 
                            aria-label="Copy code snippet to clipboard">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"/>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                      </svg>
                      Copy
                    </button>
                  </div>
                  <div style="position: absolute; top: -32px; right: 4px; background: #00a32a; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; display: none;" class="roko-copy-success">
                    Snippet copied to clipboard
                  </div>
                </div>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>

    <!-- Gamified Banner using existing card system -->
    <div class="roko-card roko-card-success roko-mt-4" style="background: #f0f6fc; border-left-color: #00a32a;">
      <div class="roko-card-body roko-py-3">
        <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
          <span class="roko-text-dark">Resolve 5 medium issues to reach 85/100</span>
          <button class="roko-button roko-button-outline">View list →</button>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Pre-scan State -->
<div class="roko-card" style="display: none;" id="roko-pre-scan-state">
  <div class="roko-card-header">
    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
      <div>
        <h3 class="roko-card-title">Accessibility</h3>
        <div class="roko-badge" style="background: #f6f7f7; color: #646970; margin-top: 8px;">
          —/100
        </div>
      </div>
      <div>
        <button class="roko-button" style="animation: roko-pulse 2s infinite;" id="roko-first-scan-btn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
            <circle cx="12" cy="12" r="3"/>
          </svg>
          Run first scan
        </button>
      </div>
    </div>
  </div>
  <div class="roko-card-body roko-text-center roko-py-6">
    <svg class="roko-mb-4" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#c3c4c7" stroke-width="1">
      <path d="M9 12l2 2 4-4"/>
      <circle cx="12" cy="12" r="10"/>
    </svg>
    <h4>Scan your site for accessibility issues</h4>
    <p class="roko-text-muted">Get a comprehensive WCAG 2.1 AA compliance report with actionable fixes</p>
  </div>
</div>

<style>
/* Minimal additions to existing system */
@keyframes roko-pulse {
  0%, 100% { box-shadow: 0 0 0 0 rgba(34, 113, 177, 0.4); }
  50% { box-shadow: 0 0 0 8px rgba(34, 113, 177, 0); }
}

.roko-tab-nav .roko-button-outline[aria-pressed="true"] {
  background: white;
  border-bottom-color: white;
  border-top-width: 3px;
}

.roko-tab-nav .roko-button-outline[data-severity="medium"][aria-pressed="true"] {
  border-top-color: #dba617;
}

.roko-tab-nav .roko-button-outline[data-severity="low"][aria-pressed="true"] {
  border-top-color: #2271b1;
}

/* Focus enhancement for accessibility */
.roko-site-row:focus {
  outline: 2px solid #2271b1;
  outline-offset: 2px;
  background: #f6f7f7;
}

.roko-copy-success {
  animation: roko-copy-feedback 1.5s ease-out;
}

@keyframes roko-copy-feedback {
  0% { opacity: 0; transform: translateY(5px); }
  20%, 80% { opacity: 1; transform: translateY(0); }
  100% { opacity: 0; transform: translateY(-5px); }
}
</style>

<script>
// Clean JavaScript using existing patterns
document.addEventListener('DOMContentLoaded', function() {
  initializeAccessibilityModule();
});

function initializeAccessibilityModule() {
  // Tab switching
  document.querySelectorAll('[data-severity]').forEach(tab => {
    tab.addEventListener('click', function() {
      // Update active states using existing classes
      document.querySelectorAll('[data-severity]').forEach(t => {
        t.classList.remove('roko-button-outline');
        t.classList.add('roko-button-clear');
        t.setAttribute('aria-pressed', 'false');
      });
      
      this.classList.remove('roko-button-clear');
      this.classList.add('roko-button-outline');
      this.setAttribute('aria-pressed', 'true');
      
      // Announce to screen readers
      announceTabChange(this.dataset.severity);
    });
  });
  
  // Copy functionality
  document.querySelectorAll('button[title*="Copy"]').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const codeElement = this.closest('div').querySelector('code');
      const code = codeElement.textContent;
      
      navigator.clipboard.writeText(code).then(() => {
        const feedback = this.parentElement.querySelector('.roko-copy-success');
        feedback.style.display = 'block';
        setTimeout(() => {
          feedback.style.display = 'none';
        }, 1500);
      });
    });
  });
  
  // Row click handlers using existing patterns
  document.querySelectorAll('.roko-site-row[role="button"]').forEach(row => {
    row.addEventListener('click', function() {
      console.log('Open modal for:', this.getAttribute('aria-label'));
    });
    
    row.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
      }
    });
  });
  
  // Scan button
  const scanBtn = document.getElementById('roko-run-scan-btn');
  const loadingBtn = document.getElementById('roko-scan-loading');
  
  if (scanBtn) {
    scanBtn.addEventListener('click', function() {
      scanBtn.style.display = 'none';
      loadingBtn.style.display = 'inline-flex';
      
      // Simulate scan
      setTimeout(() => {
        loadingBtn.style.display = 'none';
        scanBtn.style.display = 'inline-flex';
        
        // Update score
        animateScore();
        updateTimestamp();
      }, 3000);
    });
  }
  
  // Check for stale scans
  checkStaleness();
}

function toggleCodeExpansion(codeId, button) {
  const code = document.getElementById(codeId);
  const svg = button.querySelector('svg path');
  
  if (code.style.webkitLineClamp === 'unset') {
    // Collapse
    code.style.webkitLineClamp = '2';
    code.style.overflow = 'hidden';
    svg.setAttribute('d', 'M6 9l6 6 6-6');
    button.innerHTML = button.innerHTML.replace('Collapse', 'Expand');
  } else {
    // Expand
    code.style.webkitLineClamp = 'unset';
    code.style.overflow = 'visible';
    svg.setAttribute('d', 'M18 15l-6-6-6 6');
    button.innerHTML = button.innerHTML.replace('Expand', 'Collapse');
  }
}

function animateScore() {
  const scoreElement = document.querySelector('.roko-score-number');
  if (scoreElement) {
    let current = 0;
    const target = 72;
    const duration = 600;
    const startTime = performance.now();
    
    function update(currentTime) {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      current = Math.floor(target * progress);
      scoreElement.textContent = current;
      
      if (progress < 1) {
        requestAnimationFrame(update);
      }
    }
    requestAnimationFrame(update);
  }
}

function updateTimestamp() {
  const timestamp = document.querySelector('[data-timestamp]');
  if (timestamp) {
    timestamp.textContent = 'Last scanned: just now';
  }
}

function checkStaleness() {
  // Show stale warning if needed
  const staleWarning = document.getElementById('roko-stale-warning');
  // For demo - would check actual timestamp
  // staleWarning.style.display = 'flex';
}

function announceTabChange(severity) {
  const severityNames = {
    'critical': 'critical',
    'medium': 'medium priority',
    'low': 'low priority'
  };
  
  // Screen reader announcement
  const announcement = document.createElement('div');
  announcement.setAttribute('aria-live', 'polite');
  announcement.style.position = 'absolute';
  announcement.style.left = '-10000px';
  announcement.textContent = `Now viewing ${severityNames[severity]} accessibility issues.`;
  
  document.body.appendChild(announcement);
  setTimeout(() => document.body.removeChild(announcement), 1500);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
  if (e.key === '?' && e.shiftKey) {
    e.preventDefault();
    alert('Keyboard shortcuts:\nTab: Navigate\nEnter/Space: Activate\nShift+?: Help');
  }
});
</script>