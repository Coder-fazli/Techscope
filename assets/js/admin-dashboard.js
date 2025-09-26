/**
 * Enhanced Admin Dashboard JavaScript
 * Modular sections with drag & drop functionality
 */

(function($) {
    'use strict';

    // Dashboard functionality
    const TechScopeDashboard = {

        init: function() {
            this.initSortableWidgets();
            this.initExpandableWidgets();
            this.initRealTimeUpdates();
            this.initQuickActions();
            this.loadWidgetSettings();
        },

        // Make dashboard widgets sortable
        initSortableWidgets: function() {
            if (typeof $ !== 'undefined' && $.fn.sortable) {
                $('.meta-box-sortables').sortable({
                    placeholder: 'sortable-placeholder',
                    connectWith: '.meta-box-sortables',
                    handle: '.hndle',
                    cursor: 'move',
                    tolerance: 'pointer',
                    forcePlaceholderSize: true,
                    helper: 'clone',
                    opacity: 0.8,
                    start: function(e, ui) {
                        ui.placeholder.height(ui.helper.height());
                        ui.helper.addClass('dashboard-widget-dragging');
                    },
                    stop: function(e, ui) {
                        ui.item.removeClass('dashboard-widget-dragging');
                        TechScopeDashboard.saveWidgetOrder();
                    }
                });
            }
        },

        // Initialize expandable/collapsible widgets
        initExpandableWidgets: function() {
            $(document).on('click', '.techscope-widget-toggle', function(e) {
                e.preventDefault();
                const $widget = $(this).closest('.postbox');
                const $content = $widget.find('.inside');
                const $toggle = $(this);

                $content.slideToggle(300, function() {
                    if ($content.is(':visible')) {
                        $toggle.removeClass('collapsed')
                               .find('.dashicons')
                               .removeClass('dashicons-arrow-down')
                               .addClass('dashicons-arrow-up');
                        $widget.removeClass('closed');
                    } else {
                        $toggle.addClass('collapsed')
                               .find('.dashicons')
                               .removeClass('dashicons-arrow-up')
                               .addClass('dashicons-arrow-down');
                        $widget.addClass('closed');
                    }

                    TechScopeDashboard.saveWidgetSettings();
                });
            });
        },

        // Real-time statistics updates
        initRealTimeUpdates: function() {
            this.updateDashboardStats();

            // Update every 30 seconds
            setInterval(() => {
                this.updateDashboardStats();
            }, 30000);
        },

        // Update dashboard statistics via AJAX
        updateDashboardStats: function() {
            if (typeof techscope_ajax !== 'undefined') {
                $.ajax({
                    url: techscope_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'techscope_update_dashboard_stats',
                        nonce: techscope_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            TechScopeDashboard.updateStatCards(response.data);
                        }
                    }
                });
            }
        },

        // Update statistics cards
        updateStatCards: function(stats) {
            $('.techscope-stat-card').each(function() {
                const $card = $(this);
                const cardType = $card.data('stat-type');

                if (stats[cardType]) {
                    $card.find('.stat-number').text(stats[cardType]);

                    // Add subtle animation
                    $card.addClass('stat-updated');
                    setTimeout(() => {
                        $card.removeClass('stat-updated');
                    }, 600);
                }
            });

            // Update last updated time
            const now = new Date().toLocaleTimeString();
            $('.last-updated-time').text(now);
        },

        // Enhanced quick actions
        initQuickActions: function() {
            // Add New Post with category preselect
            $('.quick-action-new-post').on('click', function(e) {
                if ($(this).data('category')) {
                    const categoryId = $(this).data('category');
                    const url = `${$(this).attr('href')}&category=${categoryId}`;
                    window.open(url, '_blank');
                    e.preventDefault();
                }
            });

            // Quick category management
            $('.quick-action-categories').on('click', function(e) {
                TechScopeDashboard.showCategoryQuickEdit();
                e.preventDefault();
            });
        },

        // Save widget order
        saveWidgetOrder: function() {
            const order = $('.meta-box-sortables').sortable('toArray', {
                attribute: 'id'
            });

            if (typeof techscope_ajax !== 'undefined') {
                $.ajax({
                    url: techscope_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'techscope_save_widget_order',
                        nonce: techscope_ajax.nonce,
                        widget_order: JSON.stringify(order)
                    }
                });
            }
        },

        // Save widget settings (collapsed/expanded)
        saveWidgetSettings: function() {
            const settings = {};

            $('.postbox').each(function() {
                const id = $(this).attr('id');
                const isCollapsed = $(this).hasClass('closed');
                if (id) {
                    settings[id] = { collapsed: isCollapsed };
                }
            });

            localStorage.setItem('techscope_widget_settings', JSON.stringify(settings));
        },

        // Load widget settings
        loadWidgetSettings: function() {
            const settings = localStorage.getItem('techscope_widget_settings');

            if (settings) {
                const parsed = JSON.parse(settings);

                Object.keys(parsed).forEach(widgetId => {
                    const $widget = $('#' + widgetId);
                    if (parsed[widgetId].collapsed) {
                        $widget.addClass('closed');
                        $widget.find('.inside').hide();
                        $widget.find('.techscope-widget-toggle')
                               .addClass('collapsed')
                               .find('.dashicons')
                               .removeClass('dashicons-arrow-up')
                               .addClass('dashicons-arrow-down');
                    }
                });
            }
        },

        // Show category quick edit modal
        showCategoryQuickEdit: function() {
            const modal = `
                <div class="techscope-modal-overlay">
                    <div class="techscope-modal">
                        <div class="techscope-modal-header">
                            <h2>Quick Category Management</h2>
                            <button class="techscope-modal-close">&times;</button>
                        </div>
                        <div class="techscope-modal-content">
                            <p>Loading categories...</p>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modal);
            $('.techscope-modal-overlay').fadeIn(300);

            // Load categories via AJAX
            if (typeof techscope_ajax !== 'undefined') {
                $.ajax({
                    url: techscope_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'techscope_get_categories_quick_edit',
                        nonce: techscope_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.techscope-modal-content').html(response.data);
                        }
                    }
                });
            }

            // Close modal functionality
            $(document).on('click', '.techscope-modal-close, .techscope-modal-overlay', function(e) {
                if (e.target === this) {
                    $('.techscope-modal-overlay').fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        TechScopeDashboard.init();

        // Add expand/collapse toggles to widgets
        $('.postbox h2.hndle').each(function() {
            if (!$(this).find('.techscope-widget-toggle').length) {
                $(this).append('<button class="techscope-widget-toggle" title="Toggle widget"><span class="dashicons dashicons-arrow-up"></span></button>');
            }
        });

        // Add stat-type data attributes for real-time updates
        $('.techscope-stat-card').each(function(index) {
            const statTypes = ['total_posts', 'total_comments', 'total_categories', 'draft_posts'];
            $(this).attr('data-stat-type', statTypes[index] || 'total_posts');
        });

        // Smooth scrolling for admin navigation
        $('.techscope-quick-actions a').on('click', function(e) {
            const href = $(this).attr('href');
            if (href.indexOf('#') === 0) {
                e.preventDefault();
                const target = $(href);
                if (target.length) {
                    $('html, body').animate({
                        scrollTop: target.offset().top - 50
                    }, 500);
                }
            }
        });
    });

})(jQuery);