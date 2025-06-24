/**
 * Roko Security Dashboard
 * 
 * Clean, lightweight security dashboard that displays real API data.
 * 
 * @version 2.0.0
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
            showAll: true,
            data: null
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

        try {
            this.state.data = await this.fetch_security_data();
            this.render_dashboard();
        } catch (error) {
            this.show_error();
            console.error('Security dashboard error:', error);
        }
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
    // DASHBOARD RENDERING
    // ==========================================

    /**
     * Render the complete dashboard.
     */
    render_dashboard() {
        this.update_security_score();
        this.render_security_cards();
    }

    /**
     * Update the security score display.
     */
    update_security_score() {
        const score = this.calculate_security_score();
        const criticalCount = this.count_critical_issues();
        const { text, className } = this.get_score_status(score);

        // Update score ring
        this.elements.scoreValue.textContent = score;
        this.elements.scoreRing.style.background =
            `conic-gradient(#00a32a ${score}%, #e9ecef ${score}% 100%)`;

        // Update status
        this.elements.scoreStatus.textContent = text;
        this.elements.scoreStatus.className = `roko-boost-score ${className}`;

        // Update critical count
        this.elements.criticalCount.textContent = criticalCount;
    }

    /**
     * Calculate overall security score based on API data.
     */
    calculate_security_score() {
        let score = 100;
        const data = this.state.data;

        // Deduct points for security keys
        if (data.securityKeys) {
            Object.values(data.securityKeys).forEach(strength => {
                if (strength === 'none') score -= 15;
                else if (strength === 'weak') score -= 10;
            });
        }

        // Deduct points for file integrity issues
        if (data.fileIntegrity?.coreModified) score -= 20;
        if (data.fileIntegrity?.suspiciousFiles > 0) score -= 10;

        // Deduct points for network security issues
        if (!data.networkSecurity?.sslValid) score -= 15;
        if (!data.networkSecurity?.httpsEnforced) score -= 10;

        // Deduct points for file security issues
        if (data.fileSecurity?.wpDebugOn) score -= 5;
        if (data.fileSecurity?.editorOn) score -= 5;
        if (!data.fileSecurity?.wpConfigPermission644) score -= 5;

        // Deduct points for user security issues
        if (data.userSecurity?.adminUsernameRisk) score -= 10;

        // Deduct points for vulnerabilities
        if (data.knownVulnerabilities?.length > 0) {
            score -= data.knownVulnerabilities.length * 5;
        }

        return Math.max(0, Math.min(100, score));
    }

    /**
     * Count critical security issues.
     */
    count_critical_issues() {
        let count = 0;
        const data = this.state.data;

        // Critical file integrity issues
        if (data.fileIntegrity?.coreModified) count++;

        // Critical network security issues
        if (!data.networkSecurity?.sslValid) count++;

        // Critical security key issues
        if (data.securityKeys) {
            Object.values(data.securityKeys).forEach(strength => {
                if (strength === 'none') count++;
            });
        }

        // Critical user security issues
        if (data.userSecurity?.adminUsernameRisk) count++;

        return count;
    }

    /**
     * Get score status text and CSS class.
     */
    get_score_status(score) {
        if (score >= 80) {
            return { text: 'Secure', className: 'good' };
        } else if (score >= 60) {
            return { text: 'Needs attention', className: 'fair' };
        } else {
            return { text: 'Critical', className: 'poor' };
        }
    }

    /**
     * Render all security cards.
     */
    render_security_cards() {
        const cards = [
            this.render_security_keys_card(),
            this.render_file_security_card(),
            this.render_user_security_card(),
            this.render_network_security_card(),
            this.render_file_integrity_card(),
            '' // Empty spot for 2x3 grid
        ];

        // Add vulnerabilities section
        const vulnerabilitiesSection = `
            <div class="roko-vulnerabilities-section roko-mt-5">
                ${this.render_vulnerabilities_card()}
            </div>
        `;

        this.elements.detailsGrid.innerHTML = cards.join('');
        this.elements.detailsGrid.insertAdjacentHTML('afterend', vulnerabilitiesSection);
    }

    /**
     * Render security keys card.
     */
    render_security_keys_card() {
        const keys = this.state.data.securityKeys || {};

        const items = Object.entries(keys).map(([key, strength]) => {
            const status = this.get_key_status(strength);
            const badge = this.create_badge(strength, this.get_badge_type(strength));

            return `
                <div class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${key}</span>
                        ${badge}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('Security Keys', 'WordPress secret keys and salts', items);
    }

    /**
     * Render file security card.
     */
    render_file_security_card() {
        const fileSecurity = this.state.data.fileSecurity || {};

        const checks = [
            ['Directory listing', !fileSecurity.directoryListingIsOn],
            ['WP Debug', !fileSecurity.wpDebugOn],
            ['File editor', !fileSecurity.editorOn],
            ['Dashboard installs', !fileSecurity.dashboardInstallsOn],
            ['Backup files exposed', !fileSecurity.anyBackupExposed],
            ['Sensitive files present', !fileSecurity.doesSensitiveFilesExists],
            ['htaccess permissions', fileSecurity.htAccessPermission644],
            ['Log files exposed', !fileSecurity.logFilesExposed],
            ['PHP execution in uploads', !fileSecurity.phpExecutionInUploadsDirOn],
            ['wp-config permissions', fileSecurity.wpConfigPermission644],
            ['XML-RPC', !fileSecurity.xmlrpcOn]
        ];

        const items = checks.map(([label, isSecure]) => {
            const status = isSecure ? 'ok' : 'warn';
            const badge = this.create_badge(isSecure ? 'Secure' : 'Risk', isSecure ? 'success' : 'error');

            return `
                <div class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${label}</span>
                        ${badge}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('File Security', 'File permissions and system settings', items);
    }

    /**
     * Render user security card.
     */
    render_user_security_card() {
        const userSecurity = this.state.data.userSecurity || {};

        const items = [
            {
                label: 'Admin username risk',
                value: userSecurity.adminUsernameRisk,
                status: userSecurity.adminUsernameRisk ? 'critical' : 'ok'
            },
            {
                label: 'Failed logins (24h)',
                value: userSecurity.failedLogins24h?.value || 0,
                status: (userSecurity.failedLogins24h?.value || 0) > 10 ? 'warn' : 'ok'
            }
        ];

        const itemsHtml = items.map(item => {
            const displayValue = typeof item.value === 'boolean'
                ? this.create_badge(item.value ? 'Risk' : 'Secure', item.value ? 'error' : 'success')
                : `<span class="roko-text-dark">${item.value}</span>`;

            return `
                <div class="security-item" data-status="${item.status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${item.label}</span>
                        ${displayValue}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('User Security', 'User accounts and access control', itemsHtml);
    }

    /**
     * Render network security card.
     */
    render_network_security_card() {
        const networkSecurity = this.state.data.networkSecurity || {};

        const checks = [
            ['HTTPS enforced', networkSecurity.httpsEnforced],
            ['SSL valid', networkSecurity.sslValid],
            ['Security headers', (networkSecurity.headersScore || 0) >= 4]
        ];

        const items = checks.map(([label, isSecure]) => {
            const status = isSecure ? 'ok' : 'critical';
            let badge;

            if (label === 'Security headers') {
                const score = networkSecurity.headersScore || 0;
                badge = `<span class="roko-badge roko-badge-${score >= 4 ? 'success' : 'error'}">${score}/6</span>`;
            } else {
                badge = this.create_badge(isSecure ? 'Secure' : 'Risk', isSecure ? 'success' : 'error');
            }

            return `
                <div class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${label}</span>
                        ${badge}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('Network Security', 'HTTPS, SSL and security headers', items);
    }

    /**
     * Render file integrity card.
     */
    render_file_integrity_card() {
        const fileIntegrity = this.state.data.fileIntegrity || {};

        const items = [
            {
                label: 'Core modified',
                value: fileIntegrity.coreModified,
                status: fileIntegrity.coreModified ? 'critical' : 'ok'
            },
            {
                label: 'Suspicious files',
                value: fileIntegrity.suspiciousFiles || 0,
                status: (fileIntegrity.suspiciousFiles || 0) > 0 ? 'warn' : 'ok'
            }
        ];

        const itemsHtml = items.map(item => {
            const displayValue = typeof item.value === 'boolean'
                ? this.create_badge(item.value ? 'Risk' : 'Secure', item.value ? 'error' : 'success')
                : `<span class="roko-text-dark">${item.value}</span>`;

            return `
                <div class="security-item" data-status="${item.status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${item.label}</span>
                        ${displayValue}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('File Integrity', 'Core file changes and suspicious files', itemsHtml);
    }

    /**
     * Render vulnerabilities card.
     */
    render_vulnerabilities_card() {
        const vulnerabilities = this.state.data.knownVulnerabilities || [];

        if (vulnerabilities.length === 0) {
            const content = '<p class="roko-text-muted">No known vulnerabilities detected 🎉</p>';
            return this.create_card('Known Vulnerabilities', 'Security vulnerabilities in plugins and themes', content);
        }

        // If vulnerabilities exist, create a simple table
        const tableRows = vulnerabilities.map(vuln => `
            <tr>
                <td><strong>${vuln.plugin || vuln.theme || 'Unknown'}</strong></td>
                <td>${vuln.installedVersion || 'Unknown'}</td>
                <td>${vuln.patchedVersion || 'Unknown'}</td>
                <td><span class="roko-badge roko-badge-${this.get_severity_class(vuln.severity)}">${vuln.severity || 'Unknown'}</span></td>
            </tr>
        `).join('');

        const tableContent = `
            <table class="roko-table">
                <thead>
                    <tr>
                        <th>Plugin / Theme</th>
                        <th>Installed</th>
                        <th>Patched</th>
                        <th>Severity</th>
                    </tr>
                </thead>
                <tbody>
                    ${tableRows}
                </tbody>
            </table>
        `;

        return this.create_card('Known Vulnerabilities', 'Security vulnerabilities in plugins and themes', tableContent);
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    /**
     * Create a security card.
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
     * Create a badge.
     */
    create_badge(text, type) {
        return `<span class="roko-badge roko-badge-${type}">${text}</span>`;
    }

    /**
     * Get security key status.
     */
    get_key_status(strength) {
        if (strength === 'strong') return 'ok';
        if (strength === 'weak') return 'warn';
        return 'critical';
    }

    /**
     * Get badge type for security key.
     */
    get_badge_type(strength) {
        if (strength === 'strong') return 'success';
        if (strength === 'weak') return 'warning';
        return 'error';
    }

    /**
     * Get severity CSS class.
     */
    get_severity_class(severity) {
        const severityLower = (severity || 'low').toLowerCase();
        return {
            'high': 'high',
            'medium': 'medium',
            'low': 'low'
        }[severityLower] || 'low';
    }

    /**
     * Show error state.
     */
    show_error() {
        this.elements.scoreStatus.textContent = 'Failed to load';
        this.elements.scoreStatus.className = 'roko-boost-score poor';
        this.elements.detailsGrid.innerHTML = '<p class="roko-text-muted">Error loading security data</p>';
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