/**
 * TechScope Admin Panel JavaScript
 */

jQuery(document).ready(function($) {

    // Add admin wrapper class
    $('.wrap').addClass('techscope-admin');

    // Add enhanced header
    $('.techscope-admin > h1').wrap('<div class="techscope-admin-header"></div>');
    $('.techscope-admin-header h1').after('<div class="subtitle">Configure your TechScope theme settings</div>');
    $('.techscope-admin').prepend('<div class="techscope-admin-wrap">');

    // Add section classes to different postboxes
    $('.postbox').each(function(index) {
        const title = $(this).find('h2').text().toLowerCase();

        if (title.includes('hero') || title.includes('slider')) {
            $(this).addClass('section-hero');
        } else if (title.includes('trending')) {
            $(this).addClass('section-trending');
        } else if (title.includes('editor')) {
            $(this).addClass('section-editor');
        } else if (title.includes('mobile')) {
            $(this).addClass('section-mobile');
        } else if (title.includes('ai') || title.includes('gaming')) {
            $(this).addClass('section-ai');
        } else if (title.includes('hot')) {
            $(this).addClass('section-hot');
        }
    });

    // Enhanced category checkboxes
    $('.form-table input[type="checkbox"]').each(function() {
        if ($(this).closest('div').children().length > 5) {
            $(this).closest('div').addClass('techscope-category-grid');
        }
    });

    // Stats card animations on hover
    $('.stat-card').addClass('techscope-stat-card');

    // Handle parent category change for dependent dropdowns
    $('.parent-category-dropdown').on('change', function() {
        const parentId = $(this).val();
        const parentName = $(this).attr('name');
        // Find the corresponding subcategory dropdown based on naming pattern
        const subcategoryName = parentName.replace('parent_category', 'subcategory');
        const subcategoryDropdown = $('select[name="' + subcategoryName + '"]');

        if (parentId) {
            console.log('Loading subcategories for parent ID:', parentId);
            // Enable and load subcategories
            subcategoryDropdown.prop('disabled', false);
            subcategoryDropdown.html('<option value="">Loading...</option>');

            $.ajax({
                url: techscope_admin.ajax_url,
                type: 'POST',
                data: {
                    action: 'techscope_load_subcategories',
                    parent_id: parentId,
                    nonce: techscope_admin.nonce
                },
                success: function(response) {
                    console.log('AJAX response:', response);
                    if (response.success) {
                        subcategoryDropdown.html(response.data);
                    } else {
                        subcategoryDropdown.html('<option value="">Error loading subcategories</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error:', error);
                    subcategoryDropdown.html('<option value="">Error loading subcategories</option>');
                }
            });
        } else {
            // Disable and clear subcategory dropdown
            subcategoryDropdown.prop('disabled', true);
            subcategoryDropdown.html('<option value="">Select parent first</option>');
        }
    });

    // Initialize subcategories on page load for saved values
    $('.parent-category-dropdown').each(function() {
        if ($(this).val()) {
            console.log('Initializing subcategories for:', $(this).attr('name'), $(this).val());
            $(this).trigger('change');
        }
    });

    // Debug: Log when parent dropdowns are found
    console.log('Found parent category dropdowns:', $('.parent-category-dropdown').length);

    // Form validation
    $('form').on('submit', function(e) {
        let hasError = false;

        // Check required fields
        $(this).find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).css('border-color', '#ef4444');
                hasError = true;
            } else {
                $(this).css('border-color', '#e5e7eb');
            }
        });

        // Check number ranges
        $(this).find('input[type="number"]').each(function() {
            const min = parseInt($(this).attr('min'));
            const max = parseInt($(this).attr('max'));
            const val = parseInt($(this).val());

            if (val < min || val > max) {
                $(this).css('border-color', '#ef4444');
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();

            // Show error notice
            if (!$('.techscope-validation-error').length) {
                $('<div class="notice notice-error techscope-validation-error"><p>Please check the highlighted fields and try again.</p></div>')
                    .insertAfter('.techscope-admin h1')
                    .hide()
                    .fadeIn();
            }

            // Scroll to first error
            $('html, body').animate({
                scrollTop: $('input[style*="border-color: rgb(239, 68, 68)"], select[style*="border-color: rgb(239, 68, 68)"]').first().offset().top - 100
            }, 500);
        } else {
            // Remove validation error if exists
            $('.techscope-validation-error').fadeOut();
        }
    });

    // Auto-save functionality (draft)
    let autoSaveTimeout;
    $('input, select, textarea').on('change', function() {
        clearTimeout(autoSaveTimeout);

        const $form = $(this).closest('form');
        const formData = $form.serialize();

        autoSaveTimeout = setTimeout(function() {
            // Show saving indicator
            if (!$('.auto-save-indicator').length) {
                $('<div class="auto-save-indicator" style="position: fixed; top: 32px; right: 20px; background: #6366f1; color: white; padding: 8px 16px; border-radius: 4px; font-size: 12px; z-index: 9999;">Saving...</div>')
                    .appendTo('body')
                    .fadeIn();
            }

            // Simulate auto-save (you can implement actual AJAX save here)
            setTimeout(function() {
                $('.auto-save-indicator').text('Saved').delay(1000).fadeOut();
            }, 500);
        }, 2000); // Wait 2 seconds after user stops typing
    });

    // Enhanced tooltips for form fields
    $('[data-tooltip]').each(function() {
        $(this).hover(
            function() {
                const tooltip = $('<div class="techscope-tooltip">' + $(this).data('tooltip') + '</div>')
                    .appendTo('body')
                    .css({
                        position: 'absolute',
                        background: '#374151',
                        color: 'white',
                        padding: '8px 12px',
                        borderRadius: '4px',
                        fontSize: '12px',
                        whiteSpace: 'nowrap',
                        zIndex: 9999,
                        opacity: 0
                    })
                    .animate({opacity: 1}, 200);

                const offset = $(this).offset();
                tooltip.css({
                    top: offset.top - tooltip.outerHeight() - 8,
                    left: offset.left + ($(this).outerWidth() / 2) - (tooltip.outerWidth() / 2)
                });
            },
            function() {
                $('.techscope-tooltip').remove();
            }
        );
    });

    // Collapsible sections
    $('.postbox h2').on('click', function() {
        const $postbox = $(this).closest('.postbox');
        const $inside = $postbox.find('.inside');

        if ($inside.is(':visible')) {
            $inside.slideUp(300);
            $postbox.addClass('closed');
        } else {
            $inside.slideDown(300);
            $postbox.removeClass('closed');
        }
    });

    // Search functionality for category lists
    $('.techscope-category-grid').each(function() {
        const $grid = $(this);
        const $search = $('<input type="text" placeholder="Search categories..." class="search-categories" style="width: 100%; padding: 8px; margin-bottom: 12px; border: 1px solid #e5e7eb; border-radius: 4px;">');

        $grid.prepend($search);

        $search.on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();

            $grid.find('label').each(function() {
                const categoryName = $(this).text().toLowerCase();
                if (categoryName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
});