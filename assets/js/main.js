/**
 * SmartObzor Theme JavaScript
 * Main theme functionality and interactions
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {

        // Initialize all theme functions
        initMobileMenu();
        initHeroSlider();
        initNewsCarousel();
        initScrollAnimations();
        initLazyLoading();
        initViewCounter();
        initNewsletterForm();
        initSearchFunctionality();
        initInfiniteScroll();
        initTooltips();
        initSmoothScroll();

    });

    // Mobile Menu Toggle
    function initMobileMenu() {
        const $mobileMenuBtn = $('#mobile-menu-btn');
        const $mobileMenu = $('#mobile-menu');

        $mobileMenuBtn.on('click', function() {
            $mobileMenu.slideToggle(300);
            $(this).find('.material-icons').text(
                $mobileMenu.is(':visible') ? 'close' : 'menu'
            );
        });

        // Close mobile menu on window resize
        $(window).on('resize', function() {
            if ($(window).width() > 768) {
                $mobileMenu.hide();
                $mobileMenuBtn.find('.material-icons').text('menu');
            }
        });
    }

    // Hero Slider Functionality
    function initHeroSlider() {
        const $slider = $('.hero-slider');
        const $slides = $slider.find('.hero-slide');
        const $indicators = $('.hero-indicators .indicator');
        let currentSlide = 0;
        let slideInterval;

        if ($slides.length <= 1) return;

        function showSlide(index) {
            $slides.removeClass('active').eq(index).addClass('active');
            $indicators.removeClass('active').eq(index).addClass('active');
            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % $slides.length;
            showSlide(next);
        }

        function startSlider() {
            slideInterval = setInterval(nextSlide, 6000);
        }

        function stopSlider() {
            clearInterval(slideInterval);
        }

        // Indicator clicks
        $indicators.on('click', function() {
            const index = $(this).index();
            showSlide(index);
            stopSlider();
            startSlider();
        });

        // Auto-start slider
        showSlide(0);
        startSlider();

        // Pause on hover
        $slider.on('mouseenter', stopSlider).on('mouseleave', startSlider);
    }

    // News Carousel (Trending/Latest sections)
    function initNewsCarousel() {
        $('.news-carousel').each(function() {
            const $carousel = $(this);
            const $container = $carousel.find('.carousel-container');
            const $prevBtn = $carousel.find('.carousel-prev');
            const $nextBtn = $carousel.find('.carousel-next');
            const $items = $container.find('.carousel-item');

            if ($items.length <= 1) return;

            let currentIndex = 0;
            const itemsVisible = getVisibleItems();
            const maxIndex = Math.max(0, $items.length - itemsVisible);

            function getVisibleItems() {
                const containerWidth = $container.width();
                const itemWidth = $items.first().outerWidth(true);
                return Math.floor(containerWidth / itemWidth);
            }

            function updateCarousel() {
                const translateX = -currentIndex * ($items.first().outerWidth(true));
                $container.css('transform', `translateX(${translateX}px)`);

                $prevBtn.toggleClass('opacity-50', currentIndex === 0);
                $nextBtn.toggleClass('opacity-50', currentIndex >= maxIndex);
            }

            $prevBtn.on('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });

            $nextBtn.on('click', function() {
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            });

            // Touch/swipe support
            let startX = 0;
            let isDragging = false;

            $container.on('touchstart mousedown', function(e) {
                startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
                isDragging = true;
            });

            $container.on('touchmove mousemove', function(e) {
                if (!isDragging) return;
                e.preventDefault();
            });

            $container.on('touchend mouseup', function(e) {
                if (!isDragging) return;
                isDragging = false;

                const endX = e.type === 'touchend' ? e.changedTouches[0].clientX : e.clientX;
                const deltaX = startX - endX;

                if (Math.abs(deltaX) > 50) {
                    if (deltaX > 0 && currentIndex < maxIndex) {
                        currentIndex++;
                    } else if (deltaX < 0 && currentIndex > 0) {
                        currentIndex--;
                    }
                    updateCarousel();
                }
            });

            // Initialize
            updateCarousel();

            // Update on window resize
            $(window).on('resize', function() {
                currentIndex = Math.min(currentIndex, maxIndex);
                updateCarousel();
            });
        });
    }

    // Scroll Animations
    function initScrollAnimations() {
        const $animatedElements = $('.animate-on-scroll, .section-animate');

        if (!$animatedElements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('visible animated');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        $animatedElements.each(function() {
            observer.observe(this);
        });

        // Staggered animation for grid items
        $('.news-grid .news-card').each(function(index) {
            $(this).css('animation-delay', `${index * 0.1}s`);
        });
    }

    // Lazy Loading for Images
    function initLazyLoading() {
        const $lazyImages = $('img[data-src]');

        if (!$lazyImages.length) return;

        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const $img = $(entry.target);
                    const src = $img.data('src');

                    if (src) {
                        $img.attr('src', src).removeAttr('data-src');
                        $img.on('load', function() {
                            $(this).removeClass('lazy-loading');
                        });
                    }

                    imageObserver.unobserve(entry.target);
                }
            });
        });

        $lazyImages.each(function() {
            imageObserver.observe(this);
        });
    }

    // Post View Counter
    function initViewCounter() {
        if ($('body').hasClass('single-post')) {
            const postId = $('body').data('post-id') || $('article').data('post-id');

            if (postId) {
                // Wait 3 seconds before counting view
                setTimeout(() => {
                    $.post(smartobzor_ajax.ajax_url, {
                        action: 'increment_post_views',
                        post_id: postId,
                        nonce: smartobzor_ajax.nonce
                    });
                }, 3000);
            }
        }
    }

    // Newsletter Form
    function initNewsletterForm() {
        $('.newsletter-form').on('submit', function(e) {
            e.preventDefault();

            const $form = $(this);
            const $email = $form.find('input[type="email"]');
            const $button = $form.find('button[type="submit"]');
            const email = $email.val();

            if (!email) return;

            $button.prop('disabled', true).text('Subscribing...');

            $.post(smartobzor_ajax.ajax_url, {
                action: 'newsletter_subscribe',
                email: email,
                nonce: smartobzor_ajax.nonce
            }).done(function(response) {
                if (response.success) {
                    showNotification('Thank you for subscribing!', 'success');
                    $form[0].reset();
                } else {
                    showNotification(response.data || 'Subscription failed. Please try again.', 'error');
                }
            }).fail(function() {
                showNotification('Network error. Please try again.', 'error');
            }).always(function() {
                $button.prop('disabled', false).text('Subscribe');
            });
        });
    }

    // Search Functionality
    function initSearchFunctionality() {
        const $searchForm = $('.search-form');
        const $searchInput = $searchForm.find('input[type="search"]');
        const $searchResults = $('.search-results');
        let searchTimeout;

        $searchInput.on('input', function() {
            const query = $(this).val().trim();

            clearTimeout(searchTimeout);

            if (query.length < 3) {
                $searchResults.hide();
                return;
            }

            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        function performSearch(query) {
            $.post(smartobzor_ajax.ajax_url, {
                action: 'live_search',
                query: query,
                nonce: smartobzor_ajax.nonce
            }).done(function(response) {
                if (response.success) {
                    $searchResults.html(response.data).show();
                }
            });
        }

        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-form, .search-results').length) {
                $searchResults.hide();
            }
        });
    }

    // Infinite Scroll for Archive Pages
    function initInfiniteScroll() {
        if (!$('body').hasClass('blog') && !$('body').hasClass('archive')) return;

        const $container = $('.news-grid');
        const $loadMore = $('.load-more-posts');
        let loading = false;
        let page = 2;

        function loadMorePosts() {
            if (loading) return;
            loading = true;

            $loadMore.find('.load-text').hide();
            $loadMore.find('.load-spinner').show();

            $.post(smartobzor_ajax.ajax_url, {
                action: 'load_more_posts',
                page: page,
                nonce: smartobzor_ajax.nonce,
                category: $('body').data('category-id') || ''
            }).done(function(response) {
                if (response.success && response.data) {
                    const $newPosts = $(response.data);
                    $container.append($newPosts);

                    // Trigger lazy loading for new images
                    initLazyLoading();

                    // Animate new posts
                    $newPosts.addClass('animate-on-scroll').each(function(index) {
                        setTimeout(() => {
                            $(this).addClass('visible');
                        }, index * 100);
                    });

                    page++;
                } else {
                    $loadMore.hide();
                    showNotification('No more posts to load', 'info');
                }
            }).fail(function() {
                showNotification('Failed to load more posts', 'error');
            }).always(function() {
                loading = false;
                $loadMore.find('.load-text').show();
                $loadMore.find('.load-spinner').hide();
            });
        }

        $loadMore.on('click', loadMorePosts);

        // Auto-load when near bottom
        $(window).on('scroll', function() {
            if (loading) return;

            const scrollTop = $(window).scrollTop();
            const windowHeight = $(window).height();
            const docHeight = $(document).height();

            if (scrollTop + windowHeight >= docHeight - 1000) {
                loadMorePosts();
            }
        });
    }

    // Tooltips
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            const $element = $(this);
            const tooltipText = $element.data('tooltip');

            $element.on('mouseenter', function() {
                const $tooltip = $('<div class="tooltip">' + tooltipText + '</div>');
                $('body').append($tooltip);

                const offset = $element.offset();
                const elementHeight = $element.outerHeight();

                $tooltip.css({
                    top: offset.top - $tooltip.outerHeight() - 10,
                    left: offset.left + ($element.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
                }).addClass('show');
            });

            $element.on('mouseleave', function() {
                $('.tooltip').remove();
            });
        });
    }

    // Smooth Scroll for Anchor Links
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function() {
            const target = $(this.hash);

            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
                return false;
            }
        });
    }

    // Utility Functions
    function showNotification(message, type = 'info') {
        const $notification = $(`
            <div class="notification notification-${type}">
                <span class="material-icons">${getNotificationIcon(type)}</span>
                <span class="notification-text">${message}</span>
                <button class="notification-close">
                    <span class="material-icons">close</span>
                </button>
            </div>
        `);

        $('body').append($notification);

        setTimeout(() => {
            $notification.addClass('show');
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            removeNotification($notification);
        }, 5000);

        // Manual close
        $notification.find('.notification-close').on('click', function() {
            removeNotification($notification);
        });
    }

    function getNotificationIcon(type) {
        switch (type) {
            case 'success': return 'check_circle';
            case 'error': return 'error';
            case 'warning': return 'warning';
            default: return 'info';
        }
    }

    function removeNotification($notification) {
        $notification.removeClass('show');
        setTimeout(() => {
            $notification.remove();
        }, 300);
    }

    // Reading Progress Bar
    $(window).on('scroll', function() {
        if ($('body').hasClass('single-post')) {
            const $content = $('.entry-content');
            if ($content.length) {
                const contentTop = $content.offset().top;
                const contentHeight = $content.outerHeight();
                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();

                const progress = Math.min(100, Math.max(0,
                    ((scrollTop + windowHeight - contentTop) / contentHeight) * 100
                ));

                $('.reading-progress').css('width', progress + '%');
            }
        }
    });

    // Back to Top Button
    $(window).on('scroll', function() {
        const $backToTop = $('#back-to-top');
        if ($(window).scrollTop() > 300) {
            $backToTop.addClass('show');
        } else {
            $backToTop.removeClass('show');
        }
    });

    $('#back-to-top').on('click', function() {
        $('html, body').animate({ scrollTop: 0 }, 500);
    });

    // Comment System Enhancements
    $('.comment-reply-link').on('click', function(e) {
        e.preventDefault();
        const commentId = $(this).data('comment-id');
        const $replyForm = $('#respond');
        const $comment = $('#comment-' + commentId);

        $replyForm.appendTo($comment.find('.comment-content').first());
        $('#comment_parent').val(commentId);
        $('#comment').focus();
    });

    // Rating System
    $('.rating-stars').on('click', '.star', function() {
        const rating = $(this).data('rating');
        const postId = $(this).closest('.rating-stars').data('post-id');
        const $stars = $(this).siblings('.star').addBack();

        $.post(smartobzor_ajax.ajax_url, {
            action: 'rate_post',
            post_id: postId,
            rating: rating,
            nonce: smartobzor_ajax.nonce
        }).done(function(response) {
            if (response.success) {
                $stars.each(function(index) {
                    $(this).toggleClass('active', index < rating);
                });
                showNotification('Thank you for rating!', 'success');
            }
        });
    });

})(jQuery);

// Global functions
window.smartobzorTheme = {
    showNotification: function(message, type) {
        // Allow external scripts to show notifications
        jQuery(document).trigger('show-notification', [message, type]);
    }
};