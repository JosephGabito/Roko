/**
 * Roko Security Dashboard
 * 
 * Clean, lightweight security dashboard that displays real API data.
 * Uses new DDD clean architecture schema with sections and Check objects.
 * 
 * @version 3.0.0
 * @author Roko Team
 */

class RokoSecurityDashboard {

    /**
     * Initialize the security dashboard.
     */
    constructor() {
        this.root = document.getElementById('roko-security-dashboard');
        this.wpInternals = JSON.parse(document.getElementById('roko-admin-instance').dataset.rokoAdmin);

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
            siteFoundationReport: document.getElementById('roko-site-foundation-report'),
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
        this.setup_alpine_integration();

        try {
            this.state.data = await this.fetch_security_data();
            this.attach_json_report();
            this.render_dashboard();
            this.emit_security_data_loaded();
        } catch (error) {
            this.show_error();
            console.error('Security dashboard error:', error);
        }
    }

    attach_json_report() {
        this.elements.siteFoundationReport.dataset.jsonReport = JSON.stringify(this.state.data);
    }

    // ==========================================
    // ALPINE.JS INTEGRATION
    // ==========================================

    /**
     * Setup integration with Alpine.js components.
     */
    setup_alpine_integration() {
        // Make dashboard instance globally available
        window.rokoSecurityDashboard = this;

        // Listen for Alpine.js requests for security data
        this.root.addEventListener('roko:request-security-data', () => {
            if (this.state.data) {
                this.emit_security_data_loaded();
            }
        });
    }

    /**
     * Emit security data loaded event for Alpine.js components.
     * Convert new schema back to legacy format for Alpine components.
     */
    emit_security_data_loaded() {
        // Convert new schema to legacy format for Alpine.js compatibility
        const legacyData = this.convert_to_legacy_format();

        const event = new CustomEvent('roko:security-data-loaded', {
            detail: legacyData
        });
        document.dispatchEvent(event);
    }

    /**
     * Convert new DDD schema to legacy format for Alpine.js components.
     */
    convert_to_legacy_format() {
        if (!this.state.data || !this.state.data.sections) {
            return {};
        }

        const legacy = {};

        // Find security keys section and convert to legacy format
        const securityKeysSection = this.get_section('security_keys');
        if (securityKeysSection) {
            legacy.securityKeys = {
                lastRotated: this.get_security_keys_last_rotated()
            };
        }

        return legacy;
    }

    /**
     * Get security keys last rotated timestamp from Check evidence.
     */
    get_security_keys_last_rotated() {
        const section = this.get_section('security_keys');
        if (!section || !section.checks) return null;

        // Look for lastRotated in any check's evidence
        for (const check of section.checks) {
            if (check.evidence && check.evidence.lastRotated) {
                return check.evidence.lastRotated;
            }
        }
        return null;
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
    // SCHEMA HELPERS
    // ==========================================

    /**
     * Get a section by ID from the new schema.
     */
    get_section(sectionId) {
        if (!this.state.data || !this.state.data.sections) {
            return null;
        }
        return this.state.data.sections.find(section => section.id === sectionId);
    }

    /**
     * Get all checks with a specific status across all sections.
     */
    get_checks_by_status(status) {
        if (!this.state.data || !this.state.data.sections) {
            return [];
        }

        const checks = [];
        for (const section of this.state.data.sections) {
            if (section.checks) {
                checks.push(...section.checks.filter(check => check.status === status));
            }
        }
        return checks;
    }

    /**
     * Get all checks with a specific severity across all sections.
     */
    get_checks_by_severity(severity) {
        if (!this.state.data || !this.state.data.sections) {
            return [];
        }

        const checks = [];
        for (const section of this.state.data.sections) {
            if (section.checks) {
                checks.push(...section.checks.filter(check => check.severity === severity));
            }
        }
        return checks;
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
     * Update the security score display using new scoring system.
     */
    update_security_score() {
        // Get score data from API (new scoring system)
        const scoreData = this.state.data?.meta?.score;

        if (!scoreData) {
            // No score data available - show loading state
            this.elements.scoreValue.textContent = '...';
            this.elements.scoreStatus.textContent = 'Loading...';
            this.elements.criticalCount.textContent = '0';
            return;
        }

        const score = scoreData.value;
        const grade = scoreData.grade;
        const criticalCount = this.count_critical_issues();

        // Update score ring
        this.elements.scoreValue.textContent = score;
        this.elements.scoreRing.style.background =
            `conic-gradient(#00a32a ${score}%, #e9ecef ${score}% 100%)`;

        // Update status using letter grade
        const gradeText = this.get_grade_description(grade);
        this.elements.scoreStatus.textContent = gradeText.text;
        this.elements.scoreStatus.className = `roko-boost-score ${gradeText.className}`;

        // Update critical count
        this.elements.criticalCount.textContent = criticalCount;

        // Update algorithm version if available
        this.update_algorithm_info(scoreData);
    }

    /**
     * Update algorithm information display.
     */
    update_algorithm_info(scoreData) {
        const algorithmInfo = document.querySelector('.algorithm-info');
        if (algorithmInfo && scoreData && scoreData.algorithmVersion) {
            algorithmInfo.textContent = `Weighted scoring algorithm v${scoreData.algorithmVersion}`;
        }
    }

    /**
     * Map letter grades to display text and CSS classes.
     */
    get_grade_description(grade) {
        const gradeMap = {
            'A': { text: 'Excellent Security', className: 'good' },
            'B': { text: 'Good Security', className: 'good' },
            'C': { text: 'Needs Attention', className: 'fair' },
            'D': { text: 'Poor Security', className: 'poor' },
            'F': { text: 'Critical Issues', className: 'poor' }
        };
        return gradeMap[grade] || { text: 'Unknown', className: 'poor' };
    }

    /**
     * Count critical security issues using Check objects.
     */
    count_critical_issues() {
        const criticalFailed = this.get_checks_by_severity('critical').filter(check => check.status === 'fail');
        const highSeverityFailed = this.get_checks_by_severity('high').filter(check => check.status === 'fail');
        return criticalFailed.length + highSeverityFailed.length;
    }

    /**
     * Render all security cards using new schema.
     */
    render_security_cards() {
        const cards = [
            this.render_section_card('file_security'),
            this.render_section_card('security_keys'),
            this.render_section_card('file_integrity'),
            this.render_section_card('known_vulnerabilities'),
            this.render_site_health_card(), // Keep separate as it's fetched async
        ];

        this.elements.detailsGrid.innerHTML = cards.filter(card => card).join('');

        // Start the slow WordPress Site Health fetch in the background
        setTimeout(() => {
            const i18n = this.get_site_health_i18n();
            this.update_site_health_loading_message(i18n.loadingFetching);
            this.fetch_site_health_data();
        }, 100);
    }

    /**
     * Render a section card using the new schema.
     */
    render_section_card(sectionId) {
        const section = this.get_section(sectionId);
        if (!section) {
            return null;
        }

        // Add section score display if available
        let scoreDisplay = '';
        if (section.score && section.score.max > 0) {
            const percentage = Math.round((section.score.value / section.score.max) * 100);
            const scoreClass = percentage >= 80 ? 'good' : (percentage >= 60 ? 'fair' : 'poor');
            scoreDisplay = `<span class="section-score score-${scoreClass}">${percentage}%</span>`;
        }

        const items = section.checks.map(check => {
            const status = check.status === 'pass' ? 'ok' :
                (check.severity === 'critical' || check.severity === 'high' ? 'critical' : 'warn');
            const badge = this.create_check_badge(check);

            return `
                <div 
                    x-data="{ open: false }"
                    x-on:click="open = !open"
                    class="security-item roko-pointer-cursor" 
                    data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${check.label}</span>
                        ${badge}
                    </div>
                    <div 
                        x-show="open"
                        class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
                        <strong>Status:</strong> ${check.description}<br/>
                        <strong>Recommendation:</strong> ${check.recommendation}
                        ${this.render_check_evidence(check)}
                    </div>
                </div>
            `;
        }).join('');

        return this.create_card_with_score(section.title, section.description, items, scoreDisplay);
    }

    /**
     * Create a badge for a Check object.
     */
    create_check_badge(check) {
        if (check.status === 'pass') {
            return this.create_badge('Secure', 'success');
        }

        // For failed checks, show based on severity
        switch (check.severity) {
            case 'critical':
                return this.create_badge('Critical', 'error');
            case 'high':
                return this.create_badge('High Risk', 'error');
            case 'medium':
                return this.create_badge('Warning', 'warning');
            case 'low':
                return this.create_badge('Advisory', 'info');
            default:
                return this.create_badge('Issue', 'warning');
        }
    }

    /**
     * Render evidence from a Check object if available.
     */
    render_check_evidence(check) {
        if (!check.evidence || Object.keys(check.evidence).length === 0) {
            return '';
        }

        let evidenceHtml = '<br/><strong>Details:</strong><br/>';

        // Handle common evidence patterns
        for (const [key, value] of Object.entries(check.evidence)) {
            if (key === 'count' && typeof value === 'number') {
                evidenceHtml += `• Found: ${value} items<br/>`;
            } else if (key === 'files' && Array.isArray(value) && value.length > 0) {
                evidenceHtml += `• Files: ${value.slice(0, 3).join(', ')}${value.length > 3 ? '...' : ''}<br/>`;
            } else if (key === 'strength' || key === 'source') {
                evidenceHtml += `• ${key.charAt(0).toUpperCase() + key.slice(1)}: ${value}<br/>`;
            } else if (typeof value === 'boolean') {
                evidenceHtml += `• ${key}: ${value ? 'Yes' : 'No'}<br/>`;
            } else if (typeof value === 'string' && value.length < 100) {
                evidenceHtml += `• ${key}: ${value}<br/>`;
            }
        }

        return evidenceHtml;
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
        const url = `${this.wpInternals.restApiUrl}wp-site-health/v1/tests/${testName}`;

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
        // For failures, show WordPress description plus context
        let result = description;

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
                <div 
                    x-data="{ open: false }"
                    x-on:click="open = !open"
                    class="security-item" data-status="${status}">
                    <div class="roko-d-flex roko-justify-content-between roko-align-items-center">
                        <span class="security-item-label">${test.label || this.get_site_health_test_label(test.test)}</span>
                        ${this.create_badge(badgeText, badgeType)}
                    </div>
                    <div 
                        x-show="open"
                        class="security-item-description roko-text-muted roko-text-small roko-block roko-mt-3">
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

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    /**
     * Create a card with title, description, and content.
     */
    create_card(title, description, content) {
        return this.create_card_with_score(title, description, content, '');
    }

    /**
     * Create a card with optional score display.
     */
    create_card_with_score(title, description, content, scoreDisplay) {
        return `
            <div class="roko-detail-card" role="region" aria-labelledby="${this.slugify(title)}-title">
                <div class="roko-d-flex roko-justify-content-between roko-align-items-center roko-mb-3">
                    <div>
                        <h4 id="${this.slugify(title)}-title">${title}</h4>
                        <p class="roko-text-muted roko-text-small">${description}</p>
                    </div>
                    ${scoreDisplay ? `<div class="section-score-container">${scoreDisplay}</div>` : ''}
                </div>
                <div class="security-items">
                    ${content}
                </div>
            </div>
        `;
    }

    /**
     * Convert text to slug for IDs.
     */
    slugify(text) {
        return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    }

    /**
     * Create a badge.
     */
    create_badge(text, type) {
        return `<span class="roko-badge roko-badge-${type}">${text}</span>`;
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