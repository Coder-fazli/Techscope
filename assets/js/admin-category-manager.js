/**
 * Enhanced Category Management JavaScript
 * Handles all category management interactions
 */

(function($) {
    'use strict';

    const CategoryManager = {

        init: function() {
            this.bindEvents();
            this.initColorPickers();
            this.initIconSelectors();
            this.loadCategorySettings();
        },

        bindEvents: function() {
            // Toggle category edit panels
            $(document).on('click', '.btn-edit', this.toggleEditPanel);

            // Save category changes
            $(document).on('click', '.btn-save', this.saveCategory);

            // Cancel category editing
            $(document).on('click', '.btn-cancel', this.cancelEdit);

            // Delete category
            $(document).on('click', '.btn-delete', this.deleteCategory);

            // Bulk actions
            $(document).on('click', '.btn-bulk', this.handleBulkAction);

            // Live preview updates
            $(document).on('input', '.edit-form-group input, .edit-form-group textarea', this.updateLivePreview);
            $(document).on('change', '.edit-form-group select', this.updateLivePreview);

            // Color picker changes
            $(document).on('input', '.category-color', this.updateColorPreview);

            // Icon selection
            $(document).on('click', '.icon-option', this.selectIcon);

            // Checkbox selection for bulk actions
            $(document).on('change', '.category-checkbox', this.updateBulkActions);
        },

        toggleEditPanel: function(e) {
            e.preventDefault();

            const $button = $(this);
            const $categoryItem = $button.closest('.category-item-enhanced');
            const $editPanel = $categoryItem.find('.category-edit-panel');

            // Close other open panels
            $('.category-item-enhanced').not($categoryItem).removeClass('expanded');
            $('.category-edit-panel').not($editPanel).removeClass('active').slideUp(300);

            // Toggle current panel
            if ($editPanel.hasClass('active')) {
                $categoryItem.removeClass('expanded');
                $editPanel.removeClass('active').slideUp(300);
                $button.text('Edit');
            } else {
                $categoryItem.addClass('expanded');
                $editPanel.addClass('active').slideDown(300);
                $button.text('Close');

                // Initialize live preview
                CategoryManager.updateLivePreview.call($editPanel.find('input').first()[0]);
            }
        },

        saveCategory: function(e) {
            e.preventDefault();

            const $button = $(this);
            const $editPanel = $button.closest('.category-edit-panel');
            const $categoryItem = $button.closest('.category-item-enhanced');
            const categoryId = $categoryItem.data('category-id');

            // Collect form data
            const formData = {
                action: 'techscope_save_category_settings',
                nonce: techscope_ajax.nonce,
                category_id: categoryId,
                name: $editPanel.find('[name="category_name"]').val(),
                description: $editPanel.find('[name="category_description"]').val(),
                color: $editPanel.find('[name="category_color"]').val(),
                icon: $editPanel.find('[name="category_icon"]').val(),
                priority: $editPanel.find('[name="category_priority"]').val()
            };

            // Show loading state
            $button.prop('disabled', true).text('Saving...');

            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        // Update the category display
                        CategoryManager.updateCategoryDisplay($categoryItem, formData);

                        // Close edit panel
                        $editPanel.removeClass('active').slideUp(300);
                        $categoryItem.removeClass('expanded');

                        // Show success message
                        CategoryManager.showNotice('Category updated successfully!', 'success');
                    } else {
                        CategoryManager.showNotice(response.data || 'Failed to update category', 'error');
                    }
                },
                error: function() {
                    CategoryManager.showNotice('Network error occurred', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Save Changes');
                }
            });
        },

        cancelEdit: function(e) {
            e.preventDefault();

            const $button = $(this);
            const $editPanel = $button.closest('.category-edit-panel');
            const $categoryItem = $button.closest('.category-item-enhanced');

            // Reset form to original values
            CategoryManager.resetForm($editPanel);

            // Close panel
            $editPanel.removeClass('active').slideUp(300);
            $categoryItem.removeClass('expanded');
            $categoryItem.find('.btn-edit').text('Edit');
        },

        deleteCategory: function(e) {
            e.preventDefault();

            if (!confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                return;
            }

            const $button = $(this);
            const $categoryItem = $button.closest('.category-item-enhanced');
            const categoryId = $categoryItem.data('category-id');
            const categoryName = $categoryItem.find('.category-main-details h4').text();

            $button.prop('disabled', true).text('Deleting...');

            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'techscope_delete_category',
                    nonce: techscope_ajax.nonce,
                    category_id: categoryId
                },
                success: function(response) {
                    if (response.success) {
                        $categoryItem.fadeOut(400, function() {
                            $(this).remove();
                        });
                        CategoryManager.showNotice(`Category "${categoryName}" deleted successfully!`, 'success');
                        CategoryManager.updateCategoryStats();
                    } else {
                        CategoryManager.showNotice(response.data || 'Failed to delete category', 'error');
                        $button.prop('disabled', false).text('Delete');
                    }
                },
                error: function() {
                    CategoryManager.showNotice('Network error occurred', 'error');
                    $button.prop('disabled', false).text('Delete');
                }
            });
        },

        handleBulkAction: function(e) {
            e.preventDefault();

            const $form = $(this).closest('.bulk-actions-form');
            const action = $form.find('[name="bulk_action"]').val();
            const selectedCategories = [];

            $('.category-checkbox:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            if (selectedCategories.length === 0) {
                CategoryManager.showNotice('Please select at least one category', 'warning');
                return;
            }

            if (!confirm(`Are you sure you want to ${action} ${selectedCategories.length} categories?`)) {
                return;
            }

            const bulkData = {
                action: 'techscope_bulk_category_action',
                nonce: techscope_ajax.nonce,
                bulk_action: action,
                category_ids: selectedCategories
            };

            // Add action-specific data
            if (action === 'set_color') {
                bulkData.bulk_color = $form.find('[name="bulk_color"]').val();
            } else if (action === 'set_icon') {
                bulkData.bulk_icon = $form.find('[name="bulk_icon"]').val();
            }

            const $button = $(this);
            $button.prop('disabled', true).text('Processing...');

            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: bulkData,
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Refresh to show changes
                    } else {
                        CategoryManager.showNotice(response.data || 'Bulk action failed', 'error');
                    }
                },
                error: function() {
                    CategoryManager.showNotice('Network error occurred', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Apply');
                }
            });
        },

        updateLivePreview: function() {
            const $input = $(this);
            const $editPanel = $input.closest('.category-edit-panel');
            const $preview = $editPanel.find('.category-preview');

            const name = $editPanel.find('[name="category_name"]').val() || 'Category Name';
            const color = $editPanel.find('[name="category_color"]').val() || '#FF4D78';
            const icon = $editPanel.find('[name="category_icon"]').val() || '📁';

            $preview.find('.preview-name').text(name);
            $preview.find('.preview-icon').css('background-color', color).text(icon);
        },

        updateColorPreview: function() {
            const $input = $(this);
            const color = $input.val();
            const $preview = $input.siblings('.color-preview');

            $preview.css('background-color', color);

            // Update live preview if in edit panel
            const $editPanel = $input.closest('.category-edit-panel');
            if ($editPanel.length) {
                CategoryManager.updateLivePreview.call(this);
            }
        },

        selectIcon: function(e) {
            e.preventDefault();

            const $option = $(this);
            const $selector = $option.closest('.icon-selector');
            const $hiddenInput = $selector.siblings('input[name="category_icon"]');
            const icon = $option.text();

            // Update selection
            $selector.find('.icon-option').removeClass('selected');
            $option.addClass('selected');
            $hiddenInput.val(icon);

            // Update live preview
            const $editPanel = $option.closest('.category-edit-panel');
            if ($editPanel.length) {
                CategoryManager.updateLivePreview.call($hiddenInput[0]);
            }
        },

        updateBulkActions: function() {
            const checkedCount = $('.category-checkbox:checked').length;
            const $bulkPanel = $('.category-bulk-actions');

            if (checkedCount > 0) {
                $bulkPanel.addClass('has-selection');
                $bulkPanel.find('.selection-count').text(checkedCount);
            } else {
                $bulkPanel.removeClass('has-selection');
            }
        },

        initColorPickers: function() {
            $('.category-color').each(function() {
                const $input = $(this);
                const initialColor = $input.val();
                const $preview = $('<div class="color-preview"></div>');

                $preview.css('background-color', initialColor);
                $input.after($preview);

                $preview.on('click', function() {
                    $input.click();
                });
            });
        },

        initIconSelectors: function() {
            $('.icon-selector').each(function() {
                const $selector = $(this);
                const $hiddenInput = $selector.siblings('input[name="category_icon"]');
                const currentIcon = $hiddenInput.val();

                // Common icons for categories
                const icons = ['📁', '📰', '💡', '🔧', '🎨', '🏆', '🎯', '📱', '💼', '🏠',
                              '⚡', '🌟', '🎮', '📚', '🎬', '🎵', '🍕', '🚗', '✈️', '🏃'];

                icons.forEach(icon => {
                    const $option = $(`<div class="icon-option" title="${icon}">${icon}</div>`);
                    if (icon === currentIcon) {
                        $option.addClass('selected');
                    }
                    $selector.append($option);
                });
            });
        },

        updateCategoryDisplay: function($categoryItem, data) {
            // Update name
            $categoryItem.find('.category-main-details h4').text(data.name);

            // Update icon and color
            const $icon = $categoryItem.find('.category-icon');
            $icon.css('background-color', data.color).text(data.icon);
        },

        updateCategoryStats: function() {
            $.ajax({
                url: techscope_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'techscope_get_category_stats',
                    nonce: techscope_ajax.nonce
                },
                success: function(response) {
                    if (response.success && response.data) {
                        $('.category-stat-card').each(function() {
                            const $card = $(this);
                            const statType = $card.data('stat-type');
                            if (response.data[statType]) {
                                $card.find('.stat-number').text(response.data[statType]);
                            }
                        });
                    }
                }
            });
        },

        resetForm: function($editPanel) {
            $editPanel.find('input, textarea, select').each(function() {
                const $input = $(this);
                const originalValue = $input.data('original-value');
                if (originalValue !== undefined) {
                    $input.val(originalValue);
                }
            });

            // Reset live preview
            CategoryManager.updateLivePreview.call($editPanel.find('input').first()[0]);
        },

        loadCategorySettings: function() {
            // Store original values for reset functionality
            $('.edit-form-group input, .edit-form-group textarea, .edit-form-group select').each(function() {
                $(this).data('original-value', $(this).val());
            });
        },

        showNotice: function(message, type) {
            const $notice = $(`
                <div class="notice notice-${type} is-dismissible">
                    <p>${message}</p>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">Dismiss this notice.</span>
                    </button>
                </div>
            `);

            $('.techscope-category-stats').before($notice);

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                $notice.fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);

            // Manual dismiss
            $notice.find('.notice-dismiss').on('click', function() {
                $notice.fadeOut(400, function() {
                    $(this).remove();
                });
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        if ($('.techscope-category-grid-enhanced').length) {
            CategoryManager.init();
        }
    });

})(jQuery);