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
     * Calculate security score based on various factors.
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

        // Deduct points for file security issues
        if (data.fileSecurity?.wpDebugOn) score -= 5;
        if (data.fileSecurity?.editorOn) score -= 5;
        if (!data.fileSecurity?.wpConfigPermission644) score -= 5;

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

        // Critical security key issues
        if (data.securityKeys) {
            Object.values(data.securityKeys).forEach(strength => {
                if (strength === 'none') count++;
            });
        }

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
            this.render_site_health_card(), // First - shows loading, fast to render
            this.render_security_keys_card(),
            this.render_file_security_card(),
            this.render_file_integrity_card()
        ];

        // Add vulnerabilities section
        const vulnerabilitiesSection = `
            <div class="roko-vulnerabilities-section roko-mt-5">
                ${this.render_vulnerabilities_card()}
            </div>
        `;

        this.elements.detailsGrid.innerHTML = cards.join('');
        this.elements.detailsGrid.insertAdjacentHTML('afterend', vulnerabilitiesSection);

        // Now start the slow WordPress Site Health fetch in the background
        // This happens after our fast security checks are already displayed
        setTimeout(() => {
            // Update loading message to show we're now fetching WordPress data
            const i18n = this.get_site_health_i18n();
            this.update_site_health_loading_message(i18n.loadingFetching);
            this.fetch_site_health_data();
        }, 100); // Small delay to ensure our cards are rendered first
    }

    /**
     * Render security keys card.
     */
    render_security_keys_card() {

        const keys = this.state.data.securityKeys.securityKeys || {};
        const summary = this.state.data.securityKeys.summary || {};

        const items = Object.entries(keys).map(([index, item]) => {

            const status = this.get_key_status(item.strength);
            const badge = this.create_badge(item.strength, this.get_badge_type(item.strength));

            return `
                <div class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${item.key}</span>
                        ${badge}
                    </div>
                    <span class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                        ${item.description}
                    </span>
                </div>
            `;
        }).join('');

        return this.create_card(summary.title, summary.description, items);

    }

    /**
     * Render file security card.
     */
    render_file_security_card() {

        const fileSecurity = this.state.data.fileSecurity.fileSecurity || {};
        const cardSummary = this.state.data.fileSecurity.summary;

        const checks = [
            {
                label: 'Directory listing',
                isSecure: !fileSecurity.directoryListingIsOn.value,
                description: fileSecurity.directoryListingIsOn.description
            },
            {
                label: 'WP Debug',
                isSecure: !fileSecurity.wpDebugOn.value,
                description: fileSecurity.wpDebugOn.description
            },
            {
                label: 'File editor',
                isSecure: !fileSecurity.editorOn.value,
                description: fileSecurity.editorOn.description
            },
            {
                label: 'Dashboard installs',
                isSecure: !fileSecurity.dashboardInstallsOn.value,
                description: fileSecurity.dashboardInstallsOn.description
            },
            {
                label: 'Backup files exposed',
                isSecure: !fileSecurity.anyBackupExposed.value,
                description: fileSecurity.anyBackupExposed.description
            },
            {
                label: 'Sensitive files present',
                isSecure: !fileSecurity.doesSensitiveFilesExists.value,
                description: fileSecurity.doesSensitiveFilesExists.description
            },
            {
                label: 'htaccess permissions',
                isSecure: fileSecurity.htAccessPermission644.value,
                description: fileSecurity.htAccessPermission644.description
            },
            {
                label: 'Log files exposed',
                isSecure: !fileSecurity.logFilesExposed.value,
                description: fileSecurity.logFilesExposed.description
            },
            {
                label: 'PHP execution in uploads',
                isSecure: !fileSecurity.phpExecutionInUploadsDirOn.value,
                description: fileSecurity.phpExecutionInUploadsDirOn.description
            },
            {
                label: 'wp-config permissions',
                isSecure: fileSecurity.wpConfigPermission644.value,
                description: fileSecurity.wpConfigPermission644.description
            },
            {
                label: 'XML-RPC',
                isSecure: !fileSecurity.xmlrpcOn.value,
                description: fileSecurity.xmlrpcOn.description
            }
        ];

        const items = checks.map(check => {
            const status = check.isSecure ? 'ok' : 'warn';
            const badge = this.create_badge(check.isSecure ? 'Secure' : 'Risk', check.isSecure ? 'success' : 'error');
            const description = check.description;
            return `
                <div class="security-item" data-status="${status}" title="${check.description}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${check.label}</span>
                        ${badge}
                    </div>
                    <div class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                        ${description}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card(cardSummary.title, cardSummary.description, items);
    }

    /**
     * Render file integrity card.
     */
    render_file_integrity_card() {
        const fileIntegrity = this.state.data.fileIntegrity || {};

        // Handle async core checksum
        const coreChecksumStatus = fileIntegrity.coreChecksumMismatch?.isAsync
            ? 'pending'
            : (fileIntegrity.coreChecksumMismatch?.hasMismatch ? 'critical' : 'ok');

        const items = [
            {
                label: 'Core checksum',
                value: fileIntegrity.coreChecksumMismatch?.isAsync ? 'Checking...' :
                    fileIntegrity.coreChecksumMismatch?.hasMismatch ? 'Modified' : 'Intact',
                status: coreChecksumStatus,
                description: fileIntegrity.coreChecksumMismatch?.description || ''
            },
            {
                label: 'Executable in uploads',
                value: fileIntegrity.executableInUploads?.count || 0,
                status: fileIntegrity.executableInUploads?.hasIssue ? 'critical' : 'ok',
                description: fileIntegrity.executableInUploads?.description || ''
            },
            {
                label: 'Dot files present',
                value: fileIntegrity.dotFilesPresent?.count || 0,
                status: fileIntegrity.dotFilesPresent?.hasIssue ? 'warn' : 'ok',
                description: fileIntegrity.dotFilesPresent?.description || ''
            },
            {
                label: 'Oversized files',
                value: fileIntegrity.oversizedFilesFound?.count || 0,
                status: fileIntegrity.oversizedFilesFound?.hasIssue ? 'warn' : 'ok',
                description: fileIntegrity.oversizedFilesFound?.description || ''
            },
            {
                label: 'Backup folders',
                value: fileIntegrity.backupFoldersFound?.count || 0,
                status: fileIntegrity.backupFoldersFound?.hasIssue ? 'warn' : 'ok',
                description: fileIntegrity.backupFoldersFound?.description || ''
            },
            {
                label: 'Recent changes',
                value: fileIntegrity.recentFileChanges?.count || 0,
                status: fileIntegrity.recentFileChanges?.hasIssue ? 'warn' : 'ok',
                description: fileIntegrity.recentFileChanges?.description || ''
            },
            {
                label: 'Malware patterns',
                value: fileIntegrity.malwarePatternsFound?.count || 0,
                status: fileIntegrity.malwarePatternsFound?.hasIssue ? 'critical' : 'ok',
                description: fileIntegrity.malwarePatternsFound?.description || ''
            }
        ];

        const itemsHtml = items.map(item => {
            let displayValue;
            let badge;

            if (item.status === 'pending') {
                badge = this.create_badge('Checking...', 'info');
                displayValue = badge;
            } else if (typeof item.value === 'number') {
                if (item.value === 0) {
                    badge = this.create_badge('Clean', 'success');
                } else {
                    badge = this.create_badge(`${item.value} found`, item.status === 'critical' ? 'error' : 'warning');
                }
                displayValue = badge;
            } else {
                badge = this.create_badge(item.value, item.status === 'critical' ? 'error' :
                    item.status === 'warn' ? 'warning' : 'success');
                displayValue = badge;
            }

            return `
                <div class="security-item" data-status="${item.status}" title="${item.description}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${item.label}</span>
                        ${displayValue}
                    </div>
                    <div class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                        ${item.description}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card('File Integrity', 'Comprehensive file system security checks', itemsHtml);
    }

    /**
     * Render Site Health card.
     */
    render_site_health_card() {
        const i18n = this.get_site_health_i18n();

        // Return loading state initially - we'll fetch data later
        const loadingContent = `
            <div class="security-item" data-status="pending">
                <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                    <span class="security-item-label">${i18n.labelHealthCheck}</span>
                    ${this.create_badge(i18n.badgeLoading, 'info')}
                </div>
                <div class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                    ${i18n.loadingInitial}
                </div>
            </div>
        `;

        return this.create_card(i18n.title, i18n.description, loadingContent);
    }

    /**
     * Get Site Health i18n strings.
     */
    get_site_health_i18n() {
        return window.rokoSecurity?.siteHealth || {
            title: 'Core Site Health Overview',
            description: 'See how your site measures up with WordPress\'s own health checks. Roko adds deeper insights and extra recommendations.',
            loadingInitial: 'Running WordPress core health checks...',
            loadingFetching: 'Fetching WordPress Site Health data...',
            loadingRunning: 'Running %d WordPress health checks...',
            loadingCompleted: 'Completed %d/%d health checks...',
            labelHealthCheck: 'WordPress Health Check',
            badgeLoading: 'Loading...',
            badgeIssuesFound: 'Issues found',
            badgeRecommendations: 'Recommendations',
            badgeError: 'Error',
            descriptionPassed: '%d of %d WordPress core health checks passed',
            descriptionError: 'Unable to load WordPress health checks',
            testLabels: {
                'background-updates': 'Background Updates',
                'loopback-requests': 'Loopback Requests',
                'https-status': 'HTTPS Status',
                'dotorg-communication': 'WordPress.org Communication',
                'authorization-header': 'Authorization Header'
            }
        };
    }

    /**
     * Update Site Health card loading message.
     */
    update_site_health_loading_message(message) {
        const i18n = this.get_site_health_i18n();
        const cards = this.elements.detailsGrid.querySelectorAll('.roko-detail-card');
        cards.forEach(card => {
            const title = card.querySelector('h4');
            if (title && title.textContent.trim() === i18n.title) {
                const description = card.querySelector('.security-item-description');
                if (description) {
                    description.textContent = message;
                }
            }
        });
    }

    /**
     * Fetch Site Health data and update the card.
     */
    async fetch_site_health_data() {
        try {
            const i18n = this.get_site_health_i18n();

            // Use individual tests - these are the actual WordPress Site Health API endpoints
            const tests = ['background-updates', 'loopback-requests', 'https-status', 'dotorg-communication', 'authorization-header'];

            // Show progress as we fetch each test
            this.update_site_health_loading_message(this.sprintf(i18n.loadingRunning, tests.length));

            const promises = tests.map((test, index) =>
                this.fetch_single_site_health_test(test).then(result => {
                    // Update progress
                    this.update_site_health_loading_message(this.sprintf(i18n.loadingCompleted, index + 1, tests.length));
                    return result;
                })
            );

            const results = await Promise.allSettled(promises);

            // Process results
            const siteHealthData = {};
            results.forEach((result, index) => {
                const testName = tests[index];
                if (result.status === 'fulfilled') {
                    siteHealthData[testName] = result.value;
                } else {
                    console.error(`Failed to fetch ${testName}:`, result.reason);
                    siteHealthData[testName] = {
                        test: testName,
                        label: this.get_site_health_test_label(testName),
                        status: 'critical',
                        description: 'Test failed to run: ' + (result.reason?.message || 'Unknown error')
                    };
                }
            });

            // Update the Site Health card with real data
            this.update_site_health_card(siteHealthData);

        } catch (error) {
            console.error('Site Health error:', error);
            this.update_site_health_card_error();
        }
    }

    /**
     * Fetch a single Site Health test.
     */
    async fetch_single_site_health_test(testName) {
        const url = `/wp-json/wp-site-health/v1/tests/${testName}`;

        const response = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'X-WP-Nonce': this.get_wp_nonce()
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status} for test ${testName}`);
        }

        return await response.json();
    }

    /**
     * Get WordPress nonce for Site Health API requests.
     */
    get_wp_nonce() {
        // Try to get from security dashboard
        return this.config.nonce || window.wpApiSettings?.nonce || '';
    }

    /**
     * Get human-readable label for Site Health test.
     */
    get_site_health_test_label(testName) {
        const i18n = this.get_site_health_i18n();

        // Convert hyphenated test names (from API) to underscored (for our labels)
        const normalizedTestName = testName.replace(/-/g, '_');

        return i18n.testLabels[normalizedTestName] || testName.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    /**
     * Get user-friendly description for Site Health test results.
     */
    get_site_health_description(test) {
        // Clean up WordPress's HTML description to plain text
        let description = test.description || '';
        if (description) {
            // Remove HTML tags and clean up the description
            description = description.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();

            // Limit length for better UX
            if (description.length > 200) {
                description = description.substring(0, 200) + '...';
            }
        }

        // If test passed, show success message
        if (test.status === 'good') {
            switch (test.test) {
                case 'background_updates':
                    return description || 'WordPress can automatically update itself and plugins securely.';
                case 'loopback_requests':
                    return description || 'Your site can communicate with itself properly for scheduled tasks.';
                case 'https_status':
                    return description || 'Your site is properly secured with HTTPS encryption.';
                case 'dotorg_communication':
                    return description || 'Your site can connect to WordPress.org for updates and security patches.';
                case 'authorization_header':
                    return description || 'REST API authentication is working correctly.';
                default:
                    return description || 'This health check passed successfully.';
            }
        }

        // For failures, show WordPress description plus context
        let result = description;

        // Add helpful context about potential differences with the main Site Health page
        if (test.status === 'critical' || test.status === 'recommended') {
            switch (test.test) {
                case 'loopback_requests':
                    result += ' Note: This API test may be more strict than the main Site Health page. If your site functions normally, this might be a false positive.';
                    break;
                case 'authorization_header':
                    result += ' Note: This mainly affects REST API usage. If you\'re not using API integrations, this warning can often be ignored.';
                    break;
                case 'background_updates':
                    result += ' Fix: Check file permissions on wp-content and ensure WordPress can write files.';
                    break;
                case 'https_status':
                    result += ' Fix: Enable SSL in your hosting control panel.';
                    break;
                case 'dotorg_communication':
                    result += ' Fix: Check if your firewall is blocking connections to WordPress.org.';
                    break;
            }
        }

        return result || 'This health check needs attention.';
    }

    /**
     * Simple sprintf implementation for translated strings.
     */
    sprintf(str, ...args) {
        return str.replace(/%d/g, () => args.shift());
    }

    /**
     * Update Site Health card with fetched data.
     */
    update_site_health_card(siteHealthData) {
        const i18n = this.get_site_health_i18n();
        const tests = Object.values(siteHealthData);

        // Create individual items for each health check
        const items = tests.map(test => {
            let status = 'ok';
            let badgeText = 'Passed';
            let badgeType = 'success';

            if (test.status === 'critical') {
                status = 'critical';
                badgeText = 'Failed';
                badgeType = 'error';
            } else if (test.status === 'recommended') {
                status = 'warn';
                badgeText = 'Warning';
                badgeType = 'warning';
            }

            // Get a more user-friendly description
            const description = this.get_site_health_description(test);

            return `
                <div class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${test.label || this.get_site_health_test_label(test.test)}</span>
                        ${this.create_badge(badgeText, badgeType)}
                    </div>
                    <div class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                        ${description}
                    </div>
                </div>
            `;
        }).join('');

        // Find and update the Site Health card
        const cards = this.elements.detailsGrid.querySelectorAll('.roko-detail-card');
        cards.forEach(card => {
            const title = card.querySelector('h4');
            if (title && title.textContent.trim() === i18n.title) {
                // Replace the entire card content to show individual items
                const cardBody = card.querySelector('.roko-card-body') || card;
                const existingItems = cardBody.querySelectorAll('.security-item');

                // Remove old items
                existingItems.forEach(item => item.remove());

                // Add new items
                cardBody.insertAdjacentHTML('beforeend', items);
            }
        });
    }

    /**
     * Update Site Health card with error state.
     */
    update_site_health_card_error() {
        const i18n = this.get_site_health_i18n();
        const content = `
            <div class="security-item" data-status="critical">
                <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                    <span class="security-item-label">${i18n.labelHealthCheck}</span>
                    ${this.create_badge(i18n.badgeError, 'error')}
                </div>
                <div class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                    ${i18n.descriptionError}
                </div>
            </div>
        `;

        // Find and update the Site Health card
        const cards = this.elements.detailsGrid.querySelectorAll('.roko-detail-card');
        cards.forEach(card => {
            const title = card.querySelector('h4');
            if (title && title.textContent.trim() === i18n.title) {
                const oldContent = card.querySelector('.security-item');
                if (oldContent) {
                    oldContent.outerHTML = content;
                }
            }
        });
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