/* Roko Admin – ES6+ Vanilla JavaScript
 * Drop‑in replacement for the previous jQuery implementation.
 * Zero external dependencies, fully modular, and tree‑shakeable.
 */

class RokoAdmin {
    /* ──────────────────────────────
     *  ENTRY POINT
     * ─────────────────────────────*/
    static init() {
        if (!this.instance) {
            this.instance = new RokoAdmin();
            this.instance._init();
        }
    }

    /* ──────────────────────────────
     *  INITIALISER
     * ─────────────────────────────*/
    _init() {
        this._initTabNavigation();
        this._initCardAnimations();
        this._initFormInteractions();
        this._initTooltips();
        this._initLoadingStates();
        this._initStatCounters();
        this._initDropdowns();
        this._registerGlobalShortcuts();
        this._initSmoothScroll();
    }

    /* Utility selectors */
    _qs(sel, ctx = document) { return ctx.querySelector(sel); }
    _qsa(sel, ctx = document) { return [...ctx.querySelectorAll(sel)]; }

    /* Safe closest - handles text nodes and other non-Element nodes */
    _closest(target, selector) {
        // If target is not an Element, get the parent Element
        let element = target;
        if (element.nodeType !== Node.ELEMENT_NODE) {
            element = element.parentElement;
        }
        // Now safely call closest on the Element
        return element ? element.closest(selector) : null;
    }

    /* ──────────────────────────────
     *  TAB NAVIGATION
     * ─────────────────────────────*/
    _initTabNavigation() {
        this._qsa('.roko-tab-nav .roko-button').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.classList.add('roko-loading');
                this._qsa('.roko-tab-content').forEach(content => {
                    content.classList.remove('roko-fade-in');
                    content.style.opacity = 0;
                    setTimeout(() => {
                        content.classList.add('roko-fade-in');
                        content.style.opacity = 1;
                    }, 150);
                });
                setTimeout(() => btn.classList.remove('roko-loading'), 400);
            });
        });
    }

    /* ──────────────────────────────
     *  CARD ANIMATIONS
     * ─────────────────────────────*/
    _initCardAnimations() {
        this._qsa('.roko-card-group .roko-card').forEach((card, i) => {
            card.style.animationDelay = `${i * 0.1}s`;
            card.classList.add('roko-fade-in');
            card.addEventListener('mouseenter', () => card.classList.add('roko-card-hover'));
            card.addEventListener('mouseleave', () => card.classList.remove('roko-card-hover'));
        });
        this._qsa('.roko-card .roko-button').forEach(btn => {
            btn.addEventListener('click', e => {
                if (btn.getAttribute('href') === '#') {
                    e.preventDefault();
                    btn.classList.add('roko-loading');
                    setTimeout(() => {
                        btn.classList.remove('roko-loading');
                        this._showNotification('Action completed successfully!', 'success');
                    }, 1500);
                }
            });
        });
    }

    /* ──────────────────────────────
     *  FORM INTERACTIONS
     * ─────────────────────────────*/
    _initFormInteractions() {
        this._qsa('input, select, textarea').forEach(el => {
            ['focus', 'blur', 'input'].forEach(ev => {
                el.addEventListener(ev, () => {
                    const label = el.parentElement.querySelector('label');
                    if (!label) return;
                    if (document.activeElement === el || el.value.length) {
                        label.classList.add('roko-label-float');
                    } else {
                        label.classList.remove('roko-label-float');
                    }
                });
            });
        });
        this._qsa('form').forEach(form => {
            form.addEventListener('submit', () => {
                const submit = form.querySelector('button[type="submit"], input[type="submit"]');
                if (!submit) return;
                submit.classList.add('roko-loading');
                setTimeout(() => {
                    submit.classList.remove('roko-loading');
                    this._showNotification('Settings saved successfully!', 'success');
                }, 2000);
            });
        });
        this._qsa('input[required]').forEach(input => {
            input.addEventListener('blur', () => {
                input.classList.toggle('roko-input-error', !input.value.trim());
            });
        });
    }

    /* ──────────────────────────────
     *  TOOLTIPS
     * ─────────────────────────────*/
    _initTooltips() {
        document.addEventListener('mouseenter', e => {
            const tgt = this._closest(e.target, '[data-roko-title]');
            if (!tgt) return;
            const tooltip = document.createElement('div');
            tooltip.className = 'roko-tooltip roko-fade-in';
            tooltip.textContent = tgt.dataset.rokoTitle;
            document.body.appendChild(tooltip);
            const { top, left, width } = tgt.getBoundingClientRect();
            tooltip.style.position = 'absolute';
            tooltip.style.top = `${window.scrollY + top - 35}px`;
            tooltip.style.left = `${window.scrollX + left + width / 2 - tooltip.offsetWidth / 2}px`;
            tgt._tooltip = tooltip;
        }, true);
        document.addEventListener('mouseleave', e => {
            const tgt = this._closest(e.target, '[data-roko-title]');
            if (tgt && tgt._tooltip) {
                tgt._tooltip.remove();
                delete tgt._tooltip;
            }
        }, true);
    }

    /* ──────────────────────────────
     *  LOADING STATES
     * ─────────────────────────────*/
    _initLoadingStates() {
        document.addEventListener('click', e => {
            const btn = this._closest(e.target, '[data-loading]');
            if (!btn) return;
            const loadingText = btn.dataset.loading || 'Loading…';
            const original = btn.textContent;
            btn.classList.add('roko-loading');
            btn.textContent = loadingText;
            btn.disabled = true;
            setTimeout(() => {
                btn.classList.remove('roko-loading');
                btn.textContent = original;
                btn.disabled = false;
            }, 2000);
        });
    }

    /* ──────────────────────────────
     *  STAT COUNTERS
     * ─────────────────────────────*/
    _initStatCounters() {
        this._qsa('.roko-stat-number').forEach(counter => {
            const raw = counter.textContent;
            const finalNum = parseInt(raw.replace(/[^0-9]/g, ''), 10);
            const suffix = raw.replace(/[0-9.,]/g, '');
            if (!finalNum) return;
            let current = 0;
            const step = finalNum / 30;
            const timer = setInterval(() => {
                current += step;
                if (current >= finalNum) {
                    current = finalNum;
                    clearInterval(timer);
                }
                counter.textContent = Math.floor(current).toLocaleString() + suffix;
            }, 50);
        });
    }

    /* ────────────────────────════
     *  DROPDOWNS
     * ─────────────────────────────*/
    _initDropdowns() {
        document.addEventListener('click', e => {
            const toggle = this._closest(e.target, '.roko-actions-toggle');
            const open = this._qsa('.roko-actions-dropdown.show');
            if (toggle) {
                e.stopPropagation();
                const menu = toggle.parentElement.querySelector('.roko-actions-dropdown');
                open.filter(d => d !== menu).forEach(d => d.classList.remove('show'));
                menu?.classList.toggle('show');
            } else {
                open.forEach(d => d.classList.remove('show'));
            }
        });
    }

    /* ──────────────────────────────
     *  NOTIFICATIONS
     * ─────────────────────────────*/
    _showNotification(msg, type = 'info') {
        const note = document.createElement('div');
        note.className = `roko-notification roko-notification-${type}`;
        note.innerHTML = `
      <div class="roko-notification-content">
        <span class="roko-notification-icon">${this._icon(type)}</span>
        <span class="roko-notification-message">${msg}</span>
        <button class="roko-notification-close">&times;</button>
      </div>`;
        Object.assign(note.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            zIndex: 10000,
            minWidth: '300px',
            maxWidth: '500px',
            backgroundColor: type === 'success' ? '#dcfce7' : type === 'error' ? '#fecaca' : '#dbeafe',
            color: type === 'success' ? '#16a34a' : type === 'error' ? '#dc2626' : '#0ea5e9',
            border: `1px solid ${type === 'success' ? '#bbf7d0' : type === 'error' ? '#fca5a5' : '#93c5fd'}`,
            borderRadius: '8px',
            boxShadow: '0 4px 12px rgba(0,0,0,.15)',
            transform: 'translateX(100%)',
            opacity: 0,
            transition: 'transform .3s, opacity .3s'
        });
        document.body.appendChild(note);
        requestAnimationFrame(() => {
            note.style.transform = 'translateX(0)';
            note.style.opacity = '1';
        });
        const dismiss = () => this._dismissNotification(note);
        note.querySelector('.roko-notification-close').addEventListener('click', dismiss);
        setTimeout(dismiss, 4000);
    }

    _dismissNotification(el) {
        el.style.transform = 'translateX(100%)';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 300);
    }

    _icon(type) {
        return ({ success: '✓', error: '✕', warning: '⚠' }[type] || 'ℹ');
    }

    _registerGlobalShortcuts() {
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                this._qs('form')?.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    _initSmoothScroll() {
        document.addEventListener('click', e => {
            const link = this._closest(e.target, 'a[href^="#"]');
            if (!link) return;
            const target = this._qs(link.getAttribute('href'));
            if (target) {
                e.preventDefault();
                window.scrollTo({ top: target.offsetTop - 20, behavior: 'smooth' });
            }
        });
    }
}

/* Boot */
document.addEventListener('DOMContentLoaded', () => {
    RokoAdmin.init();
    window.RokoAdmin = RokoAdmin;
});
