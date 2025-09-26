/**
 * Advanced Analytics Dashboard JavaScript
 * Handles charts, real-time updates, and interactive features
 */

(function($) {
    'use strict';

    const TechScopeAnalytics = {
        charts: {},
        updateInterval: null,

        init: function() {
            this.initCharts();
            this.bindEvents();
            this.startRealTimeUpdates();
            this.initTabs();
            this.animateCards();
        },

        // Initialize all charts
        initCharts: function() {
            this.initTrafficSourcesChart();
            this.initHourlyActivityChart();
            this.initTrendCharts();
        },

        // Initialize traffic sources pie chart
        initTrafficSourcesChart: function() {
            const ctx = document.getElementById('traffic-sources-chart');
            if (!ctx) return;

            this.charts.trafficSources = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Organic Search', 'Direct Traffic', 'Social Media', 'Referral'],
                    datasets: [{
                        data: [45.2, 28.1, 15.7, 11.0],
                        backgroundColor: [
                            '#10B981',
                            '#3B82F6',
                            '#8B5CF6',
                            '#F59E0B'
                        ],
                        borderWidth: 3,
                        borderColor: '#fff',
                        hoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1F1F1F',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#FF4D78',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true
                        }
                    },
                    cutout: '60%',
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        },

        // Initialize hourly activity line chart
        initHourlyActivityChart: function() {
            const ctx = document.getElementById('hourly-activity-chart');
            if (!ctx) return;

            // Generate sample hourly data
            const hours = Array.from({length: 24}, (_, i) => `${i}:00`);
            const activityData = Array.from({length: 24}, () => Math.floor(Math.random() * 100) + 20);

            this.charts.hourlyActivity = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: 'Active Users',
                        data: activityData,
                        borderColor: '#FF4D78',
                        backgroundColor: 'rgba(255, 77, 120, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#FF4D78',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#FF4D78',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#1F1F1F',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#FF4D78',
                            borderWidth: 1,
                            cornerRadius: 8
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                borderColor: '#e0e0e0'
                            },
                            ticks: {
                                color: '#6B7280'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                                borderColor: '#e0e0e0'
                            },
                            ticks: {
                                color: '#6B7280'
                            },
                            beginAtZero: true
                        }
                    },
                    animation: {
                        duration: 2000,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        },

        // Initialize mini trend charts for overview cards
        initTrendCharts: function() {
            $('.analytics-chart').each(function() {
                const $chart = $(this);
                const chartType = $chart.data('chart-type');

                // Create canvas element
                const canvas = $('<canvas>').attr({
                    width: $chart.width(),
                    height: $chart.height()
                });

                $chart.append(canvas);

                // Generate sample trend data
                const trendData = Array.from({length: 7}, () => Math.floor(Math.random() * 50) + 25);

                new Chart(canvas[0], {
                    type: 'line',
                    data: {
                        labels: Array.from({length: 7}, (_, i) => `Day ${i + 1}`),
                        datasets: [{
                            data: trendData,
                            borderColor: 'rgba(255, 77, 120, 0.8)',
                            backgroundColor: 'rgba(255, 77, 120, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false }
                        },
                        scales: {
                            x: { display: false },
                            y: { display: false }
                        },
                        animation: {
                            duration: 1000,
                            delay: (context) => context.dataIndex * 100
                        }
                    }
                });
            });
        },

        // Bind event handlers
        bindEvents: function() {
            // Tab switching
            $('.tab-button').on('click', this.switchTab);

            // Export buttons
            $('.btn-export').on('click', this.handleExport);

            // Period selector
            $('.period-selector').on('change', this.updatePeriod);

            // Run speed test
            $('.btn-run-test').on('click', this.runSpeedTest);

            // Real-time updates toggle
            $('input[type="checkbox"]').on('change', this.handleSettingsChange);
        },

        // Switch tabs in user behavior section
        switchTab: function(e) {
            e.preventDefault();

            const $button = $(this);
            const tabName = $button.data('tab');

            // Update button states
            $('.tab-button').removeClass('active');
            $button.addClass('active');

            // Update content visibility
            $('.tab-content').removeClass('active');
            $(`[data-tab-content="${tabName}"]`).addClass('active');

            // Trigger chart resize if needed
            if (tabName === 'hourly' && TechScopeAnalytics.charts.hourlyActivity) {
                setTimeout(() => {
                    TechScopeAnalytics.charts.hourlyActivity.resize();
                }, 300);
            }
        },

        // Handle export functionality
        handleExport: function(e) {
            e.preventDefault();

            const $button = $(this);
            const format = $button.data('format');
            const originalText = $button.text();

            // Show loading state
            $button.prop('disabled', true).text('Generating...');

            // AJAX export request
            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'techscope_export_analytics',
                    nonce: techscope_ajax.nonce,
                    format: format
                },
                success: function(response) {
                    if (response.success) {
                        TechScopeAnalytics.showNotification(response.data.message, 'success');

                        // Simulate download
                        if (response.data.download_url && response.data.download_url !== '#') {
                            window.open(response.data.download_url, '_blank');
                        }
                    } else {
                        TechScopeAnalytics.showNotification('Export failed', 'error');
                    }
                },
                error: function() {
                    TechScopeAnalytics.showNotification('Network error occurred', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text(originalText);
                }
            });
        },

        // Update chart period
        updatePeriod: function() {
            const period = $(this).val();

            // Show loading indicator
            const $section = $(this).closest('.analytics-section');
            $section.addClass('loading');

            // Simulate data update
            setTimeout(() => {
                $section.removeClass('loading');
                TechScopeAnalytics.showNotification(`Updated to show last ${period} days`, 'info');
            }, 1000);
        },

        // Run speed test
        runSpeedTest: function(e) {
            e.preventDefault();

            const $button = $(this);
            const originalText = $button.text();

            $button.prop('disabled', true).text('Testing...');

            // Simulate speed test
            setTimeout(() => {
                // Update performance metrics with new values
                $('.metric-score').each(function() {
                    const $metric = $(this);
                    const currentValue = parseFloat($metric.text()) || 0;
                    let newValue = currentValue + (Math.random() - 0.5) * 10;

                    if ($metric.text().includes('s')) {
                        newValue = Math.max(0.1, Math.min(5.0, newValue)).toFixed(1) + 's';
                    } else if ($metric.text().includes('.')) {
                        newValue = Math.max(0.01, Math.min(1.0, newValue)).toFixed(2);
                    } else {
                        newValue = Math.max(10, Math.min(100, Math.floor(newValue)));
                    }

                    $metric.text(newValue);
                    $metric.addClass('metric-updated');

                    setTimeout(() => {
                        $metric.removeClass('metric-updated');
                    }, 1000);
                });

                TechScopeAnalytics.showNotification('Speed test completed!', 'success');
                $button.prop('disabled', false).text(originalText);
            }, 3000);
        },

        // Handle settings changes
        handleSettingsChange: function() {
            const $checkbox = $(this);
            const setting = $checkbox.closest('label').text().trim();
            const isEnabled = $checkbox.is(':checked');

            if (setting.includes('Real-time updates')) {
                if (isEnabled) {
                    TechScopeAnalytics.startRealTimeUpdates();
                } else {
                    TechScopeAnalytics.stopRealTimeUpdates();
                }
            }

            TechScopeAnalytics.showNotification(
                `${setting} ${isEnabled ? 'enabled' : 'disabled'}`,
                'info'
            );
        },

        // Start real-time updates
        startRealTimeUpdates: function() {
            this.stopRealTimeUpdates(); // Clear any existing interval

            this.updateInterval = setInterval(() => {
                this.updateAnalyticsData();
                this.updateActivityFeed();
            }, 30000); // Update every 30 seconds
        },

        // Stop real-time updates
        stopRealTimeUpdates: function() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
                this.updateInterval = null;
            }
        },

        // Update analytics data via AJAX
        updateAnalyticsData: function() {
            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'techscope_update_analytics_data',
                    nonce: techscope_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        TechScopeAnalytics.updateStatCards(response.data);
                    }
                }
            });
        },

        // Update statistics cards
        updateStatCards: function(data) {
            $('.main-number').each(function() {
                const $number = $(this);
                const statType = $number.data('stat-type');

                if (data[statType]) {
                    const currentValue = $number.text();
                    const newValue = data[statType];

                    if (currentValue !== newValue.toString()) {
                        $number.text(newValue);

                        // Add update animation
                        $number.closest('.analytics-card').addClass('card-updated');
                        setTimeout(() => {
                            $number.closest('.analytics-card').removeClass('card-updated');
                        }, 1000);
                    }
                }
            });
        },

        // Update real-time activity feed
        updateActivityFeed: function() {
            const activities = [
                'New comment on latest post',
                'Visitor from London viewing homepage',
                'Social share on Facebook',
                'New user registration',
                'Page view milestone reached',
                'Email newsletter opened',
                'Contact form submission'
            ];

            const randomActivity = activities[Math.floor(Math.random() * activities.length)];
            const $newItem = $(`
                <div class="activity-item" style="display: none;">
                    <span class="activity-time">now</span>
                    <span class="activity-text">${randomActivity}</span>
                </div>
            `);

            const $feed = $('#live-activity-feed');
            $feed.prepend($newItem);
            $newItem.slideDown(300);

            // Remove old items (keep only 10)
            const $items = $feed.find('.activity-item');
            if ($items.length > 10) {
                $items.slice(10).slideUp(300, function() {
                    $(this).remove();
                });
            }

            // Update timestamps
            setTimeout(() => {
                $newItem.find('.activity-time').text('1min ago');
            }, 60000);
        },

        // Initialize tab functionality
        initTabs: function() {
            // Set initial active states
            $('.tab-button.active').trigger('click');
        },

        // Animate cards on load
        animateCards: function() {
            $('.analytics-card').each(function(index) {
                const $card = $(this);
                $card.css({
                    opacity: 0,
                    transform: 'translateY(20px)'
                });

                setTimeout(() => {
                    $card.css({
                        transition: 'all 0.5s ease',
                        opacity: 1,
                        transform: 'translateY(0)'
                    });
                }, index * 100);
            });

            // Animate device progress bars
            setTimeout(() => {
                $('.device-fill').each(function() {
                    const width = $(this).css('width');
                    $(this).css('width', '0%');
                    setTimeout(() => {
                        $(this).css({
                            transition: 'width 1.5s ease',
                            width: width
                        });
                    }, 100);
                });
            }, 1000);
        },

        // Show notification
        showNotification: function(message, type) {
            const $notification = $(`
                <div class="analytics-notification ${type}">
                    <span>${message}</span>
                    <button class="notification-close">&times;</button>
                </div>
            `);

            $('body').append($notification);

            // Position notification
            $notification.css({
                position: 'fixed',
                top: '20px',
                right: '20px',
                background: type === 'success' ? '#10B981' :
                           type === 'error' ? '#EF4444' : '#3B82F6',
                color: 'white',
                padding: '12px 20px',
                borderRadius: '8px',
                boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                zIndex: 9999,
                display: 'flex',
                alignItems: 'center',
                gap: '10px',
                maxWidth: '300px',
                fontSize: '14px',
                fontWeight: '500',
                opacity: 0,
                transform: 'translateX(100%)'
            });

            // Animate in
            setTimeout(() => {
                $notification.css({
                    transition: 'all 0.3s ease',
                    opacity: 1,
                    transform: 'translateX(0)'
                });
            }, 10);

            // Auto remove after 5 seconds
            setTimeout(() => {
                $notification.css({
                    opacity: 0,
                    transform: 'translateX(100%)'
                });
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            }, 5000);

            // Manual close
            $notification.find('.notification-close').on('click', function() {
                $notification.css({
                    opacity: 0,
                    transform: 'translateX(100%)'
                });
                setTimeout(() => {
                    $notification.remove();
                }, 300);
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        if ($('.analytics-overview-grid').length) {
            TechScopeAnalytics.init();
        }
    });

    // Add CSS for dynamic elements
    const dynamicCSS = `
        <style>
        .card-updated {
            animation: cardPulse 0.8s ease;
        }

        @keyframes cardPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .metric-updated {
            animation: metricGlow 1s ease;
        }

        @keyframes metricGlow {
            0% { color: inherit; }
            50% { color: #FF4D78; text-shadow: 0 0 8px rgba(255, 77, 120, 0.5); }
            100% { color: inherit; }
        }

        .analytics-section.loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .analytics-notification {
            animation: notificationSlide 0.3s ease;
        }

        @keyframes notificationSlide {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        </style>
    `;

    $('head').append(dynamicCSS);

    // Expose for external access
    window.TechScopeAnalytics = TechScopeAnalytics;

})(jQuery);