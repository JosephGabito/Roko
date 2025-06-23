/**
 * Roko Admin - Modern JavaScript
 * Vercel/Clerk-inspired interactions
 */

jQuery(document).ready(function ($) {
    'use strict';

    /**
     * Initialize Roko Admin Interface
     */
    const RokoAdmin = {

        /**
         * Initialize all components
         */
        init: function () {
            this.initTabNavigation();
            this.initCardAnimations();
            this.initFormInteractions();
            this.initTooltips();
            this.initLoadingStates();
            this.initStatCounters();
            this.initDropdowns();            // Added dropdown initialization
        },

        /**
         * Enhanced tab navigation with smooth transitions
         */
        initTabNavigation: function () {
            $('.roko-tab-nav .roko-button').on('click', function (e) {
                const $this = $(this);
                const href = $this.attr('href');

                // Add loading state
                $this.addClass('roko-loading');

                // Animate tab content
                $('.roko-tab-content').fadeOut(150, function () {
                    $(this).addClass('roko-fade-in').fadeIn(300);
                });

                // Remove loading state after animation
                setTimeout(() => {
                    $this.removeClass('roko-loading');
                }, 400);
            });
        },

        /**
         * Card hover animations and interactions
         */
        initCardAnimations: function () {
            // Add stagger animation to card groups
            $('.roko-card-group .roko-card').each(function (index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
                $(this).addClass('roko-fade-in');
            });

            // Enhanced hover states for interactive cards
            $('.roko-card').hover(
                function () {
                    $(this).addClass('roko-card-hover');
                },
                function () {
                    $(this).removeClass('roko-card-hover');
                }
            );

            // Smooth scroll for card actions
            $('.roko-card .roko-button').on('click', function (e) {
                if ($(this).attr('href') === '#') {
                    e.preventDefault();
                    $(this).addClass('roko-loading');

                    // Simulate action
                    setTimeout(() => {
                        $(this).removeClass('roko-loading');
                        // Add success feedback
                        RokoAdmin.showNotification('Action completed successfully!', 'success');
                    }, 1500);
                }
            });
        },

        /**
         * Enhanced form interactions
         */
        initFormInteractions: function () {
            // Floating labels effect
            $('input, select, textarea').on('focus blur', function (e) {
                const $label = $(this).siblings('label');
                if (e.type === 'focus' || this.value.length > 0) {
                    $label.addClass('roko-label-float');
                } else {
                    $label.removeClass('roko-label-float');
                }
            });

            // Form validation feedback
            $('form').on('submit', function (e) {
                const $form = $(this);
                const $submitBtn = $form.find('button[type="submit"], input[type="submit"]');

                $submitBtn.addClass('roko-loading');

                // Add visual feedback
                setTimeout(() => {
                    $submitBtn.removeClass('roko-loading');
                    RokoAdmin.showNotification('Settings saved successfully!', 'success');
                }, 2000);
            });

            // Real-time input validation
            $('input[required]').on('blur', function () {
                const $input = $(this);
                if (!$input.val()) {
                    $input.addClass('roko-input-error');
                } else {
                    $input.removeClass('roko-input-error');
                }
            });
        },

        /**
         * Simple tooltip system
         */
        initTooltips: function () {
            // Add tooltips to elements with title attributes
            $('[title]').each(function () {
                const $element = $(this);
                const title = $element.attr('title');

                $element.removeAttr('title'); // Remove default tooltip

                $element.hover(
                    function (e) {
                        const tooltip = $('<div class="roko-tooltip">' + title + '</div>');
                        $('body').append(tooltip);

                        tooltip.css({
                            position: 'absolute',
                            top: e.pageY - 35,
                            left: e.pageX - (tooltip.outerWidth() / 2),
                            background: '#111827',
                            color: '#ffffff',
                            padding: '6px 12px',
                            borderRadius: '6px',
                            fontSize: '12px',
                            whiteSpace: 'nowrap',
                            zIndex: 9999,
                            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)'
                        }).addClass('roko-fade-in');
                    },
                    function () {
                        $('.roko-tooltip').remove();
                    }
                );
            });
        },

        /**
         * Loading states for async actions
         */
        initLoadingStates: function () {
            // Add loading state to buttons with data-loading attribute
            $('[data-loading]').on('click', function () {
                const $btn = $(this);
                const loadingText = $btn.data('loading') || 'Loading...';
                const originalText = $btn.text();

                $btn.addClass('roko-loading')
                    .text(loadingText)
                    .prop('disabled', true);

                // Simulate async operation
                setTimeout(() => {
                    $btn.removeClass('roko-loading')
                        .text(originalText)
                        .prop('disabled', false);
                }, 2000);
            });
        },

        /**
         * Animated stat counters
         */
        initStatCounters: function () {
            $('.roko-stat-number').each(function () {
                const $counter = $(this);
                const finalNumber = parseInt($counter.text().replace(/[^0-9]/g, ''));
                const suffix = $counter.text().replace(/[0-9.,]/g, '');

                if (!finalNumber) return;

                let currentNumber = 0;
                const increment = finalNumber / 30; // 30 frames for smooth animation

                const timer = setInterval(() => {
                    currentNumber += increment;
                    if (currentNumber >= finalNumber) {
                        currentNumber = finalNumber;
                        clearInterval(timer);
                    }

                    // Format number with commas
                    const formattedNumber = Math.floor(currentNumber).toLocaleString();
                    $counter.text(formattedNumber + suffix);
                }, 50);
            });
        },

        /**
         * Toggle the "More" dropdown on click, and close on outside click
         */
        initDropdowns: function () {
            // Toggle on More ▾ button click
            $(document).on('click', '.roko-actions-toggle', function (e) {
                e.stopPropagation();
                const $menu = $(this).siblings('.roko-actions-dropdown');
                // Hide any other open menus
                $('.roko-actions-dropdown').not($menu).removeClass('show');
                $menu.toggleClass('show');
            });

            // Close when clicking anywhere else
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.roko-actions-menu').length) {
                    $('.roko-actions-dropdown').removeClass('show');
                }
            });
        },

        /**
         * Show notification messages
         */
        showNotification: function (message, type = 'info') {
            const notification = $(`
                <div class="roko-notification roko-notification-${type}">
                    <div class="roko-notification-content">
                        <span class="roko-notification-icon">${this.getNotificationIcon(type)}</span>
                        <span class="roko-notification-message">${message}</span>
                        <button class="roko-notification-close">&times;</button>
                    </div>
                </div>
            `);

            // Add notification styles
            notification.css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                zIndex: 10000,
                minWidth: '300px',
                maxWidth: '500px',
                backgroundColor: type === 'success' ? '#dcfce7' : type === 'error' ? '#fecaca' : '#dbeafe',
                color: type === 'success' ? '#16a34a' : type === 'error' ? '#dc2626' : '#0ea5e9',
                border: '1px solid ' + (type === 'success' ? '#bbf7d0' : type === 'error' ? '#fca5a5' : '#93c5fd'),
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
                transform: 'translateX(100%)',
                opacity: 0
            });

            notification.find('.roko-notification-content').css({
                display: 'flex',
                alignItems: 'center',
                padding: '12px 16px',
                gap: '8px'
            });

            notification.find('.roko-notification-close').css({
                background: 'none',
                border: 'none',
                fontSize: '18px',
                cursor: 'pointer',
                marginLeft: 'auto',
                opacity: 0.7
            });

            $('body').append(notification);

            // Animate in
            notification.animate({
                transform: 'translateX(0)',
                opacity: 1
            }, 300);

            // Auto dismiss after 4 seconds
            setTimeout(() => {
                this.dismissNotification(notification);
            }, 4000);

            // Manual dismiss
            notification.find('.roko-notification-close').on('click', () => {
                this.dismissNotification(notification);
            });
        },

        /**
         * Dismiss notification
         */
        dismissNotification: function (notification) {
            notification.animate({
                transform: 'translateX(100%)',
                opacity: 0
            }, 300, function () {
                $(this).remove();
            });
        },

        /**
         * Get notification icon based on type
         */
        getNotificationIcon: function (type) {
            switch (type) {
                case 'success': return '✓';
                case 'error': return '✕';
                case 'warning': return '⚠';
                default: return 'ℹ';
            }
        },

        /**
         * Utility: Debounce function for performance
         */
        debounce: function (func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // Initialize when DOM is ready
    RokoAdmin.init();

    // Add some global utility functions to window
    window.RokoAdmin = RokoAdmin;

    // Add keyboard shortcuts
    $(document).on('keydown', function (e) {
        // Ctrl/Cmd + S to save settings (prevent browser save)
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const $form = $('form').first();
            if ($form.length) {
                $form.submit();
            }
        }
    });

    // Add smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function (e) {
        const target = $(this.getAttribute('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 20
            }, 500);
        }
    });
});
