/**
 * Roko Security Dashboard
 * 
 * A comprehensive WordPress security analysis dashboard with AI-powered insights.
 * Features real-time security monitoring, vulnerability detection, and automated recommendations.
 * 
 * @version 1.0.0
 * @author Roko Team
 */

class RokoSecurityDashboard {

    /**
     * Initialize the security dashboard.
     */
    constructor() {
        this.root = document.getElementById('roko-security-dashboard');
        if (!this.root) return;

        this.config = {
            endpoint: this.root.dataset.endpoint,
            nonce: this.root.dataset.nonce,
            localStorageKey: 'rokoShowAll'
        };

        this.state = {
            currentVulnState: 'normal',
            showAll: true
        };

        this.elements = this.get_dom_elements();
        this.init();
    }

    /**
     * Get all required DOM elements.
     */
    get_dom_elements() {
        return {
            pillAll: document.getElementById('roko-pill-all'),
            pillNeed: document.getElementById('roko-pill-need'),
            scoreValue: document.getElementById('roko-score-value'),
            scoreRing: document.getElementById('roko-score-ring'),
            scoreStatus: document.getElementById('roko-score-status'),
            criticalCount: document.getElementById('roko-critical-count'),
            detailsGrid: document.getElementById('roko-details-grid')
        };
    }

    /**
     * Initialize the dashboard.
     */
    async init() {
        this.setup_view_toggle();
        this.load_view_preference();
        this.add_debug_controls();

        try {
            const data = await this.fetch_security_data();
            this.render_dashboard(data);
        } catch (error) {
            this.show_error();
            console.error('Security dashboard error:', error);
        }
    }

    // ==========================================
    // VIEW TOGGLE FUNCTIONALITY
    // ==========================================

    /**
     * Setup view mode toggle between "show all" and "required actions only".
     */
    setup_view_toggle() {
        this.elements.pillAll.addEventListener('click', () => this.set_view_mode(true));
        this.elements.pillNeed.addEventListener('click', () => this.set_view_mode(false));
    }

    /**
     * Load view preference from localStorage.
     */
    load_view_preference() {
        const showAll = localStorage.getItem(this.config.localStorageKey) !== '0';
        this.set_view_mode(showAll);
    }

    /**
     * Set view mode and update UI accordingly.
     */
    set_view_mode(showAll) {
        this.state.showAll = showAll;
        this.root.classList.toggle('hide-ok', !showAll);

        // Update pill buttons
        this.elements.pillAll.classList.toggle('active', showAll);
        this.elements.pillAll.classList.toggle('roko-button-outline', showAll);
        this.elements.pillAll.classList.toggle('roko-button-clear', !showAll);

        this.elements.pillNeed.classList.toggle('active', !showAll);
        this.elements.pillNeed.classList.toggle('roko-button-outline', !showAll);
        this.elements.pillNeed.classList.toggle('roko-button-clear', showAll);

        // Update ARIA attributes
        this.elements.pillAll.setAttribute('aria-pressed', showAll);
        this.elements.pillNeed.setAttribute('aria-pressed', !showAll);

        // Save preference
        localStorage.setItem(this.config.localStorageKey, showAll ? '1' : '0');
    }

    // ==========================================
    // DATA FETCHING
    // ==========================================

    /**
     * Fetch security data from the REST API.
     */
    async fetch_security_data() {
        const response = await fetch(this.config.endpoint, {
            credentials: 'same-origin',
            headers: { 'X-WP-Nonce': this.config.nonce }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        return await response.json();
    }

    // ==========================================
    // DASHBOARD RENDERING
    // ==========================================

    /**
     * Render the complete dashboard.
     */
    render_dashboard(data) {
        const enrichedData = this.enrich_with_mock_data(data);

        this.update_security_score(enrichedData);
        this.render_security_cards(enrichedData);
    }

    /**
     * Add mock data for development and testing.
     */
    enrich_with_mock_data(data) {
        const mockData = SecurityMockData.get_all_mock_data();

        return {
            ...data,
            ...mockData,
            // Override with current vulnerability state for testing
            ...this.get_current_vulnerability_state()
        };
    }

    /**
     * Get current vulnerability state based on debug controls.
     */
    get_current_vulnerability_state() {
        const mockData = SecurityMockData.get_vulnerability_data();

        switch (this.state.currentVulnState) {
            case 'empty':
                return {
                    knownVulnerabilities: mockData.noVulnerabilities,
                    apiStatus: mockData.apiKeyEnabled
                };
            case 'no-api':
                return {
                    knownVulnerabilities: mockData.withVulnerabilities,
                    apiStatus: mockData.noApiKey
                };
            default:
                return {
                    knownVulnerabilities: mockData.withVulnerabilities,
                    apiStatus: mockData.apiKeyEnabled
                };
        }
    }

    /**
     * Update the security score display.
     */
    update_security_score(data) {
        const score = SecurityScoring.calculate_overall_score(data);
        const criticalCount = SecurityScoring.count_critical_issues(data);
        const scoreStatus = SecurityScoring.get_score_status(score);

        // Update score ring
        this.elements.scoreValue.textContent = score;
        this.elements.scoreRing.style.background =
            `conic-gradient(#00a32a ${score}%, #e9ecef ${score}% 100%)`;

        // Update status
        this.elements.scoreStatus.textContent = scoreStatus.text;
        this.elements.scoreStatus.className = `roko-boost-score ${scoreStatus.className}`;

        // Update critical count
        this.elements.criticalCount.textContent = criticalCount;
    }

    /**
     * Render all security cards.
     */
    render_security_cards(data) {
        const cardRenderer = new SecurityCardRenderer();

        // Render main security cards (2x3 grid)
        const topCards = [
            cardRenderer.render_security_keys_card(data.securityKeys || {}),
            cardRenderer.render_file_security_card(data.fileSecurity || {}),
            cardRenderer.render_user_security_card(data.userSecurity || {}),
            cardRenderer.render_network_security_card(data.networkSecurity || {}),
            cardRenderer.render_file_integrity_card(data.fileIntegrity || {}),
            '' // Empty spot for 2x3 grid
        ];

        // Render vulnerabilities section
        const vulnerabilitiesCard = cardRenderer.render_vulnerabilities_card(
            data.knownVulnerabilities || [],
            data.apiStatus || null
        );

        // Update DOM
        this.elements.detailsGrid.innerHTML = topCards.join('');

        const vulnSection = `
      <div id="roko-vulnerabilities-container" class="roko-vulnerabilities-section roko-mt-5">
        ${vulnerabilitiesCard}
      </div>
    `;

        this.elements.detailsGrid.insertAdjacentHTML('afterend', vulnSection);

        // Setup toggle listeners after rendering
        this.setup_card_toggle_listeners();
    }

    /**
     * Setup toggle listeners for "show more" functionality.
     */
    setup_card_toggle_listeners() {
        // Setup regular card toggles
        SecurityToggleManager.setup_card_toggles();

        // Setup vulnerability table toggle
        SecurityToggleManager.setup_vulnerability_toggle();
    }

    /**
     * Show error state.
     */
    show_error() {
        this.elements.scoreStatus.textContent = 'Failed to load';
        this.elements.scoreStatus.className = 'roko-boost-score poor';
    }

    // ==========================================
    // DEBUG CONTROLS (TEMPORARY)
    // ==========================================

    /**
     * Add debug controls for testing different states.
     */
    add_debug_controls() {
        const debugControls = `
      <div style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: white; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="margin-bottom: 8px; font-weight: 600; font-size: 12px;">Test Vulnerability States:</div>
        <button class="roko-button roko-button-outline" id="toggle-vulns-normal" style="font-size: 11px; padding: 4px 8px; margin-right: 4px;">Normal</button>
        <button class="roko-button roko-button-outline" id="toggle-vulns-empty" style="font-size: 11px; padding: 4px 8px; margin-right: 4px;">Empty</button>
        <button class="roko-button roko-button-outline" id="toggle-vulns-no-api" style="font-size: 11px; padding: 4px 8px;">No API</button>
      </div>
    `;

        document.body.insertAdjacentHTML('beforeend', debugControls);
        this.setup_debug_listeners();
    }

    /**
     * Setup debug control listeners.
     */
    setup_debug_listeners() {
        document.getElementById('toggle-vulns-normal').addEventListener('click', () => {
            this.state.currentVulnState = 'normal';
            this.refresh_vulnerabilities();
        });

        document.getElementById('toggle-vulns-empty').addEventListener('click', () => {
            this.state.currentVulnState = 'empty';
            this.refresh_vulnerabilities();
        });

        document.getElementById('toggle-vulns-no-api').addEventListener('click', () => {
            this.state.currentVulnState = 'no-api';
            this.refresh_vulnerabilities();
        });
    }

    /**
     * Refresh only the vulnerabilities section.
     */
    refresh_vulnerabilities() {
        const currentState = this.get_current_vulnerability_state();
        const cardRenderer = new SecurityCardRenderer();

        const vulnCard = cardRenderer.render_vulnerabilities_card(
            currentState.knownVulnerabilities,
            currentState.apiStatus
        );

        const vulnContainer = document.getElementById('roko-vulnerabilities-container');
        if (vulnContainer) {
            vulnContainer.innerHTML = vulnCard;
            SecurityToggleManager.setup_vulnerability_toggle();
        }
    }
}

// ==========================================
// SECURITY SCORING UTILITIES
// ==========================================

class SecurityScoring {

    /**
     * Calculate overall security score (0-100).
     */
    static calculate_overall_score(data) {
        let score = 100;

        // Deduct points for weak security keys
        Object.values(data.securityKeys || {}).forEach(strength => {
            if (strength === 'none') score -= 15;
            else if (strength === 'weak') score -= 10;
        });

        // Deduct points for file integrity issues
        if (data.fileIntegrity?.coreModified) score -= 20;

        // Deduct points for network security issues
        if (!data.networkSecurity?.sslValid) score -= 15;
        if (!data.networkSecurity?.httpsEnforced) score -= 10;

        return Math.max(0, score);
    }

    /**
     * Count critical security issues.
     */
    static count_critical_issues(data) {
        let count = 0;

        // Count critical file integrity issues
        if (data.fileIntegrity?.coreModified) count++;

        // Count critical network security issues
        if (!data.networkSecurity?.sslValid) count++;

        // Count critical security key issues
        Object.values(data.securityKeys || {}).forEach(strength => {
            if (strength === 'none') count++;
        });

        return count;
    }

    /**
     * Get score status text and CSS class.
     */
    static get_score_status(score) {
        if (score >= 80) {
            return { text: 'Secure', className: 'good' };
        } else if (score >= 60) {
            return { text: 'Needs attention', className: 'fair' };
        } else {
            return { text: 'Critical', className: 'poor' };
        }
    }
}

// ==========================================
// SECURITY CARD RENDERER
// ==========================================

class SecurityCardRenderer {

    /**
     * Create a security card with title, description, and content.
     */
    create_card(title, description, content) {
        return `
      <div class="roko-detail-card">
        <h4>${title}</h4>
        <p class="roko-text-muted roko-text-small">${description}</p>
        ${content}
      </div>
    `;
    }

    /**
     * Create a badge with specified text and type.
     */
    create_badge(text, type) {
        return `<span class="roko-badge roko-badge-${type}">${text}</span>`;
    }

    /**
     * Create a "show more" toggle button.
     */
    create_toggle_button(remainingCount) {
        if (remainingCount <= 0) return '';

        return `
      <div class="roko-show-more-toggle roko-mt-3">
        <button class="roko-button roko-button-clear roko-toggle-more-items">
          <span class="show-text">Show more ${remainingCount} items</span>
          <span class="hide-text" style="display: none;">Show less</span>
        </button>
      </div>
    `;
    }

    /**
     * Render security keys card.
     */
    render_security_keys_card(keys) {
        // Convert the new format to the expected format for sorting
        const keyEntries = Object.entries(keys).map(([key, data]) => [
            key,
            typeof data === 'string' ? data : data.strength,
            typeof data === 'object' ? data.description : null
        ]);

        const sortedKeys = keyEntries.sort(([, a], [, b]) => {
            const priorityOrder = { 'none': 0, 'weak': 1, 'strong': 2 };
            const strengthA = typeof a === 'string' ? a : a.strength;
            const strengthB = typeof b === 'string' ? b : b.strength;
            return priorityOrder[strengthA] - priorityOrder[strengthB];
        });

        const { topItems, hiddenItems, remainingCount } = this.process_items(sortedKeys, 5, ([key, strengthData, description]) => {
            const strength = typeof strengthData === 'string' ? strengthData : strengthData.strength;
            const desc = description || (typeof strengthData === 'object' ? strengthData.description : null);
            const status = SecuritySorting.get_security_key_status(strength);
            const badge = this.create_badge(strength, SecuritySorting.get_security_key_badge_type(strength));

            return {
                html: `
          <div class="security-item" data-status="${status}">
            <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
              <span class="security-item-label">${key}</span>
              ${badge}
            </div>
            ${desc ? `<p class="security-item-note">${desc}</p>` : ''}
          </div>
        `,
                status
            };
        });

        const content = topItems + hiddenItems + this.create_toggle_button(remainingCount);
        return this.create_card('Security Keys', 'WordPress secret keys and salts that secure logins & cookies.', content);
    }

    /**
     * Render file security card.
     */
    render_file_security_card(fileSecurity) {
        const checks = SecurityMockData.get_file_security_checks(fileSecurity);
        const sortedChecks = SecuritySorting.sort_by_status(checks, ([, isSecure]) => isSecure ? 'ok' : 'warn');

        const { topItems, hiddenItems, remainingCount } = this.process_items(sortedChecks, 5, ([label, isSecure, note]) => {
            const status = isSecure ? 'ok' : 'warn';
            const badge = this.create_badge(isSecure ? 'Secure' : 'Risk', isSecure ? 'success' : 'error');

            return {
                html: `
          <div class="security-item" data-status="${status}">
            <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
              <span class="security-item-label">${label}</span>
              ${badge}
            </div>
            <p class="security-item-note">${note}</p>
          </div>
        `,
                status
            };
        });

        const content = topItems + hiddenItems + this.create_toggle_button(remainingCount);
        return this.create_card('File Security', 'Permissions, debug flags, exposed files.', content);
    }

    /**
     * Render user security card.
     */
    render_user_security_card(userSecurity) {
        const items = SecurityMockData.get_user_security_items(userSecurity);

        const content = items.map(item => `
      <div class="security-item" data-status="${item.status}">
        <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
          <span class="security-item-label">${item.label}</span>
          ${item.value}
        </div>
        <p class="security-item-note">${item.note}</p>
      </div>
    `).join('');

        return this.create_card('User Security', 'Admin accounts, password hygiene & recent login activity.', content);
    }

    /**
     * Render network security card.
     */
    render_network_security_card(networkSecurity) {
        const checks = SecurityMockData.get_network_security_checks(networkSecurity);
        const sortedChecks = SecuritySorting.sort_network_security_checks(checks);

        const { topItems, hiddenItems, remainingCount } = this.process_items(sortedChecks, 5, ([label, isSecure, note]) => {
            const { status, badge } = this.get_network_security_item_data(label, isSecure, networkSecurity);

            return {
                html: `
          <div class="security-item" data-status="${status}">
            <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
              <span class="security-item-label">${label}</span>
              ${badge}
            </div>
            <p class="security-item-note">${note}</p>
          </div>
        `,
                status
            };
        });

        const content = topItems + hiddenItems + this.create_toggle_button(remainingCount);
        return this.create_card('Network Security', 'HTTPS, SSL certificate, security headers and network-level protections.', content);
    }

    /**
     * Render file integrity card.
     */
    render_file_integrity_card(fileIntegrity) {
        const items = SecurityMockData.get_file_integrity_items(fileIntegrity);

        const content = items.map(item => `
      <div class="security-item" data-status="${item.status}">
        <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
          <span class="security-item-label">${item.label}</span>
          ${item.value}
        </div>
        <p class="security-item-note">${item.note}</p>
      </div>
    `).join('');

        return this.create_card('File Integrity', 'Detects unauthorised changes to core files.', content);
    }

    /**
     * Render vulnerabilities card.
     */
    render_vulnerabilities_card(vulnerabilities, apiStatus = null) {
        const vulnerabilityRenderer = new VulnerabilityRenderer();

        if (apiStatus && !apiStatus.enabled) {
            return vulnerabilityRenderer.render_no_api_state();
        }

        if (!vulnerabilities || vulnerabilities.length === 0) {
            return vulnerabilityRenderer.render_empty_state();
        }

        return vulnerabilityRenderer.render_table(vulnerabilities);
    }

    /**
     * Process items for display with show/hide functionality.
     */
    process_items(items, limit, renderFunction) {
        const topItems = items.slice(0, limit);
        const remainingItems = items.slice(limit);
        const remainingCount = remainingItems.length;

        const topItemsHtml = topItems.map(renderFunction).map(item => item.html).join('');

        const hiddenItemsHtml = remainingItems.map(item => {
            const rendered = renderFunction(item);
            return rendered.html.replace('class="security-item"', 'class="security-item roko-hidden-item" style="display: none;"');
        }).join('');

        return {
            topItems: topItemsHtml,
            hiddenItems: hiddenItemsHtml,
            remainingCount
        };
    }

    /**
     * Get network security item data (status and badge).
     */
    get_network_security_item_data(label, isSecure, networkSecurity) {
        if (label === 'Security headers present') {
            const score = networkSecurity.headersScore || 0;
            return {
                badge: `<span class="roko-badge roko-badge-${score >= 4 ? 'success' : 'error'}">${score}/6</span>`,
                status: score >= 4 ? 'ok' : 'critical'
            };
        }

        if (label === 'Certificate expires in 30 days') {
            const days = networkSecurity.certificateExpiry;
            return SecurityCertificate.get_certificate_status_data(days);
        }

        const isCritical = label.includes('HSTS') || label.includes('HTTPS') || label.includes('SSL');
        return {
            badge: this.create_badge(isSecure ? 'Secure' : 'Missing', isSecure ? 'success' : 'error'),
            status: isSecure ? 'ok' : (isCritical ? 'critical' : 'warn')
        };
    }
}

// ==========================================
// VULNERABILITY RENDERER
// ==========================================

class VulnerabilityRenderer {

    /**
     * Render vulnerabilities table.
     */
    render_table(vulnerabilities) {
        const sortedVulns = SecuritySorting.sort_vulnerabilities(vulnerabilities);
        const topVulns = sortedVulns.slice(0, 5);
        const remainingCount = Math.max(0, sortedVulns.length - 5);

        const tableRows = topVulns.map(vuln => this.create_vulnerability_row(vuln)).join('');
        const hiddenRows = this.create_hidden_rows(sortedVulns.slice(5));
        const showMoreRow = this.create_show_more_row(remainingCount);

        const tableContent = `
      <div class="roko-vulnerabilities-table-container">
        <table class="roko-table">
          <thead>
            <tr>
              <th style="width: 40%;">Plugin / Theme</th>
              <th style="width: 15%;">Installed version</th>
              <th style="width: 15%;">Patched version</th>
              <th style="width: 15%;">Severity</th>
              <th style="width: 15%;">Quick action</th>
            </tr>
          </thead>
          <tbody>
            ${tableRows}
            ${hiddenRows}
            ${showMoreRow}
          </tbody>
        </table>
      </div>
    `;

        return this.create_card('Known Vulnerabilities', 'Latest plugin or theme CVEs affecting your install.', tableContent);
    }

    /**
     * Render empty state.
     */
    render_empty_state() {
        const content = '<p class="roko-text-muted">No known vulnerabilities detected 🎉</p>';
        return this.create_card('Known Vulnerabilities', 'Latest plugin or theme CVEs affecting your install.', content);
    }

    /**
     * Render no API key state.
     */
    render_no_api_state() {
        const tableContent = `
      <div class="roko-vulnerabilities-table-container">
        <table class="roko-table">
          <thead>
            <tr>
              <th>Data source</th>
              <th>Status</th>
              <th>What the user sees</th>
              <th>Quick action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>WPScan feed</strong></td>
              <td><span class="roko-badge roko-badge-warning">Disabled</span></td>
              <td>Vulnerability checks require a WPScan API key.</td>
              <td>
                <button class="roko-button roko-button-outline" id="roko-btn-add-key">
                  Add API key
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="roko-text-muted roko-text-small roko-mt-3">
        Don't have a key yet? 
        <a href="https://wpscan.com/register" target="_blank" rel="noopener">
          Create a free WPScan account
        </a> 
        and paste your key in Settings → Security to enable live CVE alerts.
      </p>
    `;

        return this.create_card('Known Vulnerabilities', 'Latest plugin or theme CVEs affecting your install.', tableContent);
    }

    /**
     * Create a vulnerability table row.
     */
    create_vulnerability_row(vuln, isHidden = false) {
        const severityBadge = this.create_severity_badge(vuln.severity);
        const status = SecuritySorting.get_vulnerability_status(vuln.severity);
        const hiddenClass = isHidden ? ' roko-hidden-vuln-row' : '';
        const hiddenStyle = isHidden ? ' style="display: none;"' : '';

        return `
      <tr data-status="${status}" class="${hiddenClass}"${hiddenStyle}>
        <td>
          <div>
            <strong>${vuln.plugin || vuln.theme}</strong>
            <div class="roko-text-small roko-text-muted">${vuln.type || 'Plugin'}</div>
          </div>
          ${vuln.description ? `<div class="roko-text-small roko-text-muted roko-mt-1" style="max-width: 300px;">${vuln.description}</div>` : ''}
        </td>
        <td>${vuln.installedVersion}</td>
        <td>${vuln.patchedVersion}</td>
        <td>${severityBadge}</td>
        <td>
          <button class="roko-button roko-button-outline roko-btn-update" 
                  data-plugin="${vuln.plugin}" 
                  data-version="${vuln.patchedVersion}">
            Update
          </button>
        </td>
      </tr>
    `;
    }

    /**
     * Create hidden vulnerability rows.
     */
    create_hidden_rows(vulnerabilities) {
        return vulnerabilities.map(vuln => this.create_vulnerability_row(vuln, true)).join('');
    }

    /**
     * Create show more row.
     */
    create_show_more_row(remainingCount) {
        if (remainingCount <= 0) return '';

        return `
      <tr class="roko-show-more-row">
        <td colspan="5" style="text-align: center; padding: 16px;">
          <button class="roko-button roko-button-clear" id="roko-toggle-more-vulns">
            <span class="show-text">Show more ${remainingCount} items</span>
            <span class="hide-text" style="display: none;">Show less</span>
          </button>
        </td>
      </tr>
    `;
    }

    /**
     * Create severity badge.
     */
    create_severity_badge(severity) {
        const severityLower = (severity || 'low').toLowerCase();
        const badgeClass = {
            'high': 'roko-badge-high',
            'medium': 'roko-badge-medium',
            'low': 'roko-badge-low'
        }[severityLower] || 'roko-badge-low';

        return `<span class="roko-badge ${badgeClass}">${severity}</span>`;
    }

    /**
     * Create card wrapper.
     */
    create_card(title, description, content) {
        return `
      <div class="roko-detail-card">
        <h4>${title}</h4>
        <p class="roko-text-muted roko-text-small">${description}</p>
        ${content}
      </div>
    `;
    }
}

// ==========================================
// SECURITY SORTING UTILITIES
// ==========================================

class SecuritySorting {

    /**
     * Sort security keys by priority.
     */
    static sort_security_keys(keys) {
        return Object.entries(keys).sort(([, a], [, b]) => {
            const priorityOrder = { 'none': 0, 'weak': 1, 'strong': 2 };
            const strengthA = typeof a === 'string' ? a : a.strength;
            const strengthB = typeof b === 'string' ? b : b.strength;
            return priorityOrder[strengthA] - priorityOrder[strengthB];
        });
    }

    /**
     * Sort vulnerabilities by severity.
     */
    static sort_vulnerabilities(vulnerabilities) {
        return [...vulnerabilities].sort((a, b) => {
            const priorityOrder = { 'high': 0, 'medium': 1, 'low': 2 };
            return priorityOrder[a.severity.toLowerCase()] - priorityOrder[b.severity.toLowerCase()];
        });
    }

    /**
     * Sort items by status priority.
     */
    static sort_by_status(items, getStatusFn) {
        return items.sort((a, b) => {
            const priorityOrder = { 'critical': 0, 'warn': 1, 'ok': 2 };
            return priorityOrder[getStatusFn(a)] - priorityOrder[getStatusFn(b)];
        });
    }

    /**
     * Sort network security checks by priority.
     */
    static sort_network_security_checks(checks) {
        return checks.sort(([label1, isSecure1], [label2, isSecure2]) => {
            const getStatus = (label, isSecure) => {
                if (label.includes('HSTS') || label.includes('HTTPS') || label.includes('SSL')) {
                    return isSecure ? 'ok' : 'critical';
                }
                return isSecure ? 'ok' : 'warn';
            };
            const priorityOrder = { 'critical': 0, 'warn': 1, 'ok': 2 };
            return priorityOrder[getStatus(label1, isSecure1)] - priorityOrder[getStatus(label2, isSecure2)];
        });
    }

    /**
     * Get security key status.
     */
    static get_security_key_status(strength) {
        const actualStrength = typeof strength === 'string' ? strength : strength.strength;
        return actualStrength === 'strong' ? 'ok' : (actualStrength === 'weak' ? 'warn' : 'critical');
    }

    /**
     * Get security key badge type.
     */
    static get_security_key_badge_type(strength) {
        const actualStrength = typeof strength === 'string' ? strength : strength.strength;
        return actualStrength === 'strong' ? 'success' : (actualStrength === 'weak' ? 'warning' : 'error');
    }

    /**
     * Get vulnerability status.
     */
    static get_vulnerability_status(severity) {
        const severityLower = (severity || 'low').toLowerCase();
        return {
            'high': 'critical',
            'medium': 'warn',
            'low': 'ok'
        }[severityLower] || 'ok';
    }
}

// ==========================================
// CERTIFICATE UTILITIES
// ==========================================

class SecurityCertificate {

    /**
     * Get certificate status data.
     */
    static get_certificate_status_data(days) {
        if (days === null || days === undefined) {
            return {
                badge: `<span class="roko-badge roko-badge-error">Unknown</span>`,
                status: 'critical'
            };
        }

        if (days <= 30) {
            return {
                badge: `<span class="roko-badge roko-badge-error">Risk</span>`,
                status: 'critical'
            };
        } else if (days <= 90) {
            return {
                badge: `<span class="roko-badge roko-badge-warning">Soon</span>`,
                status: 'warn'
            };
        } else {
            return {
                badge: `<span class="roko-badge roko-badge-success">Secure</span>`,
                status: 'ok'
            };
        }
    }

    /**
     * Get certificate note.
     */
    static get_certificate_note(days) {
        if (days === null || days === undefined) {
            return 'Certificate expiry date unknown';
        }

        if (days <= 30) {
            return `Expires in ${days} days - renew soon to avoid outages`;
        } else if (days <= 90) {
            return `Expires in ${days} days - consider renewing`;
        } else {
            return `Expires in ${days} days - certificate is valid`;
        }
    }
}

// ==========================================
// TOGGLE MANAGER
// ==========================================

class SecurityToggleManager {

    /**
     * Setup toggle functionality for security cards.
     */
    static setup_card_toggles() {
        const toggleButtons = document.querySelectorAll('.roko-toggle-more-items');

        toggleButtons.forEach(button => {
            // Remove existing listeners to prevent duplicates
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            newButton.addEventListener('click', (e) => {
                const card = e.target.closest('.roko-detail-card');
                const hiddenItems = card.querySelectorAll('.roko-hidden-item');
                const showText = e.target.querySelector('.show-text');
                const hideText = e.target.querySelector('.hide-text');

                SecurityToggleManager.toggle_items(hiddenItems, showText, hideText);
            });
        });
    }

    /**
     * Setup toggle functionality for vulnerability table.
     */
    static setup_vulnerability_toggle() {
        const toggleBtn = document.getElementById('roko-toggle-more-vulns');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                const hiddenRows = document.querySelectorAll('.roko-hidden-vuln-row');
                const showText = document.querySelector('#roko-toggle-more-vulns .show-text');
                const hideText = document.querySelector('#roko-toggle-more-vulns .hide-text');

                SecurityToggleManager.toggle_vulnerability_rows(hiddenRows, showText, hideText);
            });
        }
    }

    /**
     * Toggle visibility of items.
     */
    static toggle_items(hiddenItems, showText, hideText) {
        const isCurrentlyHidden = hiddenItems[0]?.style.display === 'none';

        hiddenItems.forEach(item => {
            item.style.display = isCurrentlyHidden ? 'block' : 'none';
        });

        if (showText && hideText) {
            showText.style.display = isCurrentlyHidden ? 'none' : 'inline';
            hideText.style.display = isCurrentlyHidden ? 'inline' : 'none';
        }
    }

    /**
     * Toggle visibility of vulnerability table rows.
     */
    static toggle_vulnerability_rows(hiddenRows, showText, hideText) {
        const isCurrentlyHidden = hiddenRows[0]?.style.display === 'none';

        hiddenRows.forEach(row => {
            row.style.display = isCurrentlyHidden ? 'table-row' : 'none';
        });

        if (showText && hideText) {
            showText.style.display = isCurrentlyHidden ? 'none' : 'inline';
            hideText.style.display = isCurrentlyHidden ? 'inline' : 'none';
        }
    }
}

// ==========================================
// MOCK DATA (FOR DEVELOPMENT)
// ==========================================

class SecurityMockData {

    /**
     * Get all mock security data.
     */
    static get_all_mock_data() {
        return {
            securityKeys: this.get_security_keys_data(),
            fileSecurity: this.get_file_security_data(),
            userSecurity: this.get_user_security_data(),
            networkSecurity: this.get_network_security_data(),
            fileIntegrity: this.get_file_integrity_data()
        };
    }

    /**
     * Get mock security keys data with descriptions.
     */
    static get_security_keys_data() {
        return {
            'AUTH_KEY': {
                strength: 'none',
                description: 'Secures authentication cookies and login sessions.'
            },
            'SECURE_AUTH_KEY': {
                strength: 'weak',
                description: 'Provides additional security for authentication over HTTPS.'
            },
            'LOGGED_IN_KEY': {
                strength: 'none',
                description: 'Secures the "remember me" login functionality.'
            },
            'NONCE_KEY': {
                strength: 'strong',
                description: 'Protects forms and URLs from CSRF attacks.'
            },
            'AUTH_SALT': {
                strength: 'none',
                description: 'Adds randomness to authentication cookie encryption.'
            },
            'SECURE_AUTH_SALT': {
                strength: 'none',
                description: 'Strengthens HTTPS authentication cookie security.'
            },
            'LOGGED_IN_SALT': {
                strength: 'none',
                description: 'Enhances security for persistent login cookies.'
            },
            'NONCE_SALT': {
                strength: 'strong',
                description: 'Adds entropy to nonce generation for form protection.'
            }
        };
    }

    /**
     * Get mock file security data.
     */
    static get_file_security_data() {
        return {
            wpConfigPerm: { permOctal: '644' },
            htaccessPerm: { permOctal: '644' },
            dirListingOff: false,
            wpDebug: true,
            disallowFileEdit: false,
            disallowFileMods: false,
            uploadsPhpBlocked: false,
            sensitiveFilesPresent: true,
            backupFilesExposed: false,
            logFilesExposed: false,
            xmlrpcBlocked: false
        };
    }

    /**
     * Get mock user security data.
     */
    static get_user_security_data() {
        return {
            adminUsernameRisk: true,
            failedLogins24h: { value: 15 },
            inactiveAdmins: 0,
            stalePasswords: 0
        };
    }

    /**
     * Get mock network security data.
     */
    static get_network_security_data() {
        return {
            httpsEnforced: true,
            sslValid: true,
            certificateExpiry: 45,
            headersScore: 2,
            hstsHeader: false,
            cspHeader: false,
            referrerPolicyHeader: false,
            xFrameOptions: true,
            xmlrpcBlocked: false,
            loginRateLimiting: false
        };
    }

    /**
     * Get mock file integrity data.
     */
    static get_file_integrity_data() {
        return {
            coreModified: false,
            suspiciousFiles: 0
        };
    }

    /**
     * Get vulnerability data for testing.
     */
    static get_vulnerability_data() {
        return {
            withVulnerabilities: [
                {
                    plugin: 'WooCommerce',
                    type: 'Plugin',
                    installedVersion: '8.7.0',
                    patchedVersion: '8.7.1',
                    severity: 'high',
                    description: 'SQL injection vulnerability in admin dashboard allows authenticated users to execute arbitrary SQL queries.'
                },
                {
                    plugin: 'Contact Form 7',
                    type: 'Plugin',
                    installedVersion: '5.8.1',
                    patchedVersion: '5.8.4',
                    severity: 'high',
                    description: 'CSRF vulnerability in form submission handler allows unauthorized form modifications.'
                },
                {
                    plugin: 'Elementor',
                    type: 'Plugin',
                    installedVersion: '3.21.4',
                    patchedVersion: '3.21.5',
                    severity: 'medium',
                    description: 'Cross-site scripting (XSS) vulnerability in widget configuration could allow malicious script execution.'
                },
                {
                    plugin: 'WP Super Cache',
                    type: 'Plugin',
                    installedVersion: '1.9.4',
                    patchedVersion: '1.9.6',
                    severity: 'medium',
                    description: 'Directory traversal vulnerability allows unauthorized file access in certain configurations.'
                },
                {
                    plugin: 'Smash Balloon Instagram Feed',
                    type: 'Plugin',
                    installedVersion: '6.2.5',
                    patchedVersion: '6.3.0',
                    severity: 'low',
                    description: 'Information disclosure vulnerability exposes Instagram API tokens in certain configurations.'
                },
                {
                    plugin: 'Yoast SEO',
                    type: 'Plugin',
                    installedVersion: '21.5',
                    patchedVersion: '21.7',
                    severity: 'low',
                    description: 'Privilege escalation vulnerability allows subscribers to modify SEO settings in specific scenarios.'
                },
                {
                    plugin: 'Akismet Anti-Spam',
                    type: 'Plugin',
                    installedVersion: '5.1',
                    patchedVersion: '5.2',
                    severity: 'low',
                    description: 'Minor information disclosure in API response headers.'
                },
                {
                    plugin: 'Jetpack',
                    type: 'Plugin',
                    installedVersion: '12.8',
                    patchedVersion: '12.9',
                    severity: 'medium',
                    description: 'Authentication bypass vulnerability in certain module configurations.'
                }
            ],
            noVulnerabilities: [],
            noApiKey: { enabled: false },
            apiKeyEnabled: { enabled: true }
        };
    }

    /**
     * Get file security checks array.
     */
    static get_file_security_checks(fileSecurity) {
        return [
            ['wp-config.php perms', ['600', '644'].includes(fileSecurity.wpConfigPerm?.permOctal), 'File permissions are secure'],
            ['.htaccess perms', fileSecurity.htaccessPerm?.permOctal === '644', 'Standard web server permissions'],
            ['Directory listing off', fileSecurity.dirListingOff, 'Prevents browsing of directory contents'],
            ['WP_DEBUG disabled', !fileSecurity.wpDebug, 'Debug mode is turned off in production'],
            ['Editor disabled', fileSecurity.disallowFileEdit ?? false, 'WordPress file editor is disabled'],
            ['Dashboard installs off', fileSecurity.disallowFileMods ?? false, 'Plugin/theme installs blocked via dashboard'],
            ['Uploads PHP blocked', fileSecurity.uploadsPhpBlocked ?? false, 'PHP execution blocked in uploads folder'],
            ['Sensitive files removed', !(fileSecurity.sensitiveFilesPresent ?? true), 'readme.html, license.txt etc. removed'],
            ['Backups exposed', !(fileSecurity.backupFilesExposed ?? false), 'Backup files not accessible via web'],
            ['Log files exposed', !(fileSecurity.logFilesExposed ?? false), 'Log files not accessible via web'],
            ['XML-RPC blocked', fileSecurity.xmlrpcBlocked ?? false, 'Legacy XML-RPC endpoint is blocked']
        ];
    }

    /**
     * Get network security checks array.
     */
    static get_network_security_checks(networkSecurity) {
        return [
            ['HTTPS enforced', networkSecurity.httpsEnforced, networkSecurity.httpsEnforced ? 'All traffic redirects to https://' : 'HTTP traffic not redirected'],
            ['SSL certificate valid', networkSecurity.sslValid, networkSecurity.sslValid ? 'No browser errors detected' : 'Certificate has issues'],
            ['Certificate expires in 30 days', networkSecurity.certificateExpiry > 30, SecurityCertificate.get_certificate_note(networkSecurity.certificateExpiry)],
            ['Security headers present', (networkSecurity.headersScore || 0) >= 4, (networkSecurity.headersScore || 0) >= 4 ? 'Good header coverage' : 'Missing standard hardening headers'],
            ['HSTS header (Strict-Transport-Security)', networkSecurity.hstsHeader ?? false, (networkSecurity.hstsHeader ?? false) ? 'Forces future HTTPS connections' : 'Add to force future HTTPS'],
            ['Content-Security-Policy header', networkSecurity.cspHeader ?? false, (networkSecurity.cspHeader ?? false) ? 'XSS protection active' : 'Helps block XSS attacks'],
            ['Referrer-Policy header', networkSecurity.referrerPolicyHeader ?? false, (networkSecurity.referrerPolicyHeader ?? false) ? 'URL leaking controlled' : 'Prevents leaking full URLs'],
            ['Click-jacking protection (X-Frame-Options)', networkSecurity.xFrameOptions ?? false, (networkSecurity.xFrameOptions ?? false) ? 'Admin can\'t be embedded in iframes' : 'Site can be embedded in iframes'],
            ['XML-RPC pingback blocked', networkSecurity.xmlrpcBlocked ?? false, (networkSecurity.xmlrpcBlocked ?? false) ? 'Stops old DDoS vector' : 'Legacy attack vector still open'],
            ['Login-page rate-limiting active', networkSecurity.loginRateLimiting ?? false, (networkSecurity.loginRateLimiting ?? false) ? 'Brute-force protection active' : 'Install a brute-force limiter']
        ];
    }

    /**
     * Get user security items array.
     */
    static get_user_security_items(userSecurity) {
        const cardRenderer = new SecurityCardRenderer();

        return [
            {
                label: 'Admin username custom',
                value: cardRenderer.create_badge(!userSecurity.adminUsernameRisk ? 'Secure' : 'Risk', !userSecurity.adminUsernameRisk ? 'success' : 'error'),
                status: !userSecurity.adminUsernameRisk ? 'ok' : 'critical',
                note: !userSecurity.adminUsernameRisk ? 'Admin username is not "admin"' : 'Change default "admin" username'
            },
            {
                label: 'Failed logins (24h)',
                value: `<span class="roko-text-dark">${userSecurity.failedLogins24h?.value || 0}</span>`,
                status: (userSecurity.failedLogins24h?.value || 0) <= 10 ? 'ok' : ((userSecurity.failedLogins24h?.value || 0) <= 30 ? 'warn' : 'critical'),
                note: 'Number of failed login attempts in last 24 hours'
            },
            {
                label: 'Inactive admin accounts',
                value: `<span class="roko-text-dark">${userSecurity.inactiveAdmins || 0}</span>`,
                status: (userSecurity.inactiveAdmins || 0) === 0 ? 'ok' : ((userSecurity.inactiveAdmins || 0) <= 2 ? 'warn' : 'critical'),
                note: 'Admin accounts that haven\'t logged in recently'
            },
            {
                label: 'Stale passwords (>1 yr)',
                value: `<span class="roko-text-dark">${userSecurity.stalePasswords || 0}</span>`,
                status: (userSecurity.stalePasswords || 0) === 0 ? 'ok' : ((userSecurity.stalePasswords || 0) <= 2 ? 'warn' : 'critical'),
                note: 'User passwords that haven\'t been changed in over a year'
            }
        ];
    }

    /**
     * Get file integrity items array.
     */
    static get_file_integrity_items(fileIntegrity) {
        const cardRenderer = new SecurityCardRenderer();

        return [
            {
                label: 'Core modified',
                value: cardRenderer.create_badge(!fileIntegrity.coreModified ? 'Secure' : 'Risk', !fileIntegrity.coreModified ? 'success' : 'error'),
                status: !fileIntegrity.coreModified ? 'ok' : 'critical',
                note: !fileIntegrity.coreModified ? 'WordPress core files are unchanged' : 'Core files have been modified'
            },
            {
                label: 'Suspicious files',
                value: `<span class="roko-text-dark">${fileIntegrity.suspiciousFiles || 0}</span>`,
                status: (fileIntegrity.suspiciousFiles || 0) === 0 ? 'ok' : 'warn',
                note: 'Files with suspicious patterns or locations detected'
            }
        ];
    }
}

// ==========================================
// INITIALIZATION
// ==========================================

/**
 * Initialize the security dashboard when DOM is ready.
 */
document.addEventListener('DOMContentLoaded', () => {
    new RokoSecurityDashboard();
});

/**
 * Export for potential external use.
 */
window.RokoSecurityDashboard = RokoSecurityDashboard;