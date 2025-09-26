<?php
/**
 * TechScope Theme Functions
 * Modern technology news theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function techscope_theme_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'techscope'),
        'footer' => __('Footer Menu', 'techscope'),
    ));

    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat'
    ));
}
add_action('after_setup_theme', 'techscope_theme_setup');

/**
 * Enqueue Styles and Scripts
 */
function techscope_enqueue_scripts() {
    // --- Tailwind via CDN, with config that runs FIRST ---

    // 1) Tiny empty handle to hold the config
    wp_register_script('tailwind-config', '', array(), null, false);
    wp_enqueue_script('tailwind-config');

    // 2) Your Tailwind config (no heredoc, no 'before' arg)
    wp_add_inline_script(
        'tailwind-config',
        'tailwind = window.tailwind || {}; tailwind.config = { theme: { extend: {} } };'
    );

    // 3) Load the Tailwind CDN script, depends on the config so it runs after it
    wp_enqueue_script('tailwindcdn', 'https://cdn.tailwindcss.com', array('tailwind-config'), null, false);

    // --- Fonts & styles ---
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Roboto+Mono:wght@400;500&display=swap',
        array(),
        null
    );

    wp_enqueue_style(
        'material-icons',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        array(),
        null
    );

    wp_enqueue_style(
        'techscope-style',
        get_stylesheet_uri(),
        array('google-fonts', 'material-icons'),
        wp_get_theme()->get('Version')
    );

    // --- Theme JS (TEMPORARILY DISABLED FOR TESTING) ---
    /*
    wp_enqueue_script(
        'techscope-script',
        get_template_directory_uri() . '/assets/js/script.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
    */

    // --- AJAX data ---
    wp_localize_script('techscope-script', 'techscope_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('techscope_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'techscope_enqueue_scripts');

/**
 * Register Widget Areas
 */
function techscope_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'techscope'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'techscope'),
        'before_widget' => '<div id="%1$s" class="widget %2$s bg-white rounded-lg p-4 shadow-sm mb-6">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title font-bold text-gray-800 mb-4">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widgets', 'techscope'),
        'id'            => 'footer-widgets',
        'description'   => __('Add widgets here to appear in your footer.', 'techscope'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title text-lg font-bold mb-4 text-blue-400">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'techscope_widgets_init');

/**
 * Custom Post Meta for Featured Posts
 */
function techscope_add_post_meta_boxes() {
    add_meta_box(
        'techscope_featured_post',
        __('Featured Post Settings', 'techscope'),
        'techscope_featured_post_callback',
        'post',
        'side'
    );
}
add_action('add_meta_boxes', 'techscope_add_post_meta_boxes');

function techscope_featured_post_callback($post) {
    wp_nonce_field('techscope_save_post_meta', 'techscope_post_meta_nonce');

    $featured = get_post_meta($post->ID, '_techscope_featured', true);
    $hero_slider = get_post_meta($post->ID, '_techscope_hero_slider', true);
    $rating = get_post_meta($post->ID, '_techscope_rating', true);

    echo '<p><label>';
    echo '<input type="checkbox" name="techscope_featured" value="1"' . checked($featured, 1, false) . ' />';
    echo ' ' . __('Featured Post', 'techscope') . '</label></p>';

    echo '<p><label>';
    echo '<input type="checkbox" name="techscope_hero_slider" value="1"' . checked($hero_slider, 1, false) . ' />';
    echo ' ' . __('Show in Hero Slider', 'techscope') . '</label></p>';

    echo '<p><label>' . __('Rating (0-10):', 'techscope') . '<br>';
    echo '<input type="number" name="techscope_rating" value="' . esc_attr($rating) . '" min="0" max="10" step="0.1" /></label></p>';
}

function techscope_save_post_meta($post_id) {
    if (!isset($_POST['techscope_post_meta_nonce']) || !wp_verify_nonce($_POST['techscope_post_meta_nonce'], 'techscope_save_post_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    update_post_meta($post_id, '_techscope_featured', isset($_POST['techscope_featured']) ? 1 : 0);
    update_post_meta($post_id, '_techscope_hero_slider', isset($_POST['techscope_hero_slider']) ? 1 : 0);

    if (isset($_POST['techscope_rating'])) {
        update_post_meta($post_id, '_techscope_rating', sanitize_text_field($_POST['techscope_rating']));
    }
}
add_action('save_post', 'techscope_save_post_meta');

/**
 * Helper Functions
 */

// Get featured posts for hero slider
function techscope_get_hero_posts($limit = null) {
    $hero_count = get_option('techscope_hero_count', 3);
    $hero_categories = (array) get_option('techscope_hero_categories', []);

    if ($limit === null) {
        $limit = $hero_count;
    }

    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($hero_categories)) {
        $args['category__in'] = array_map('intval', $hero_categories);
    } else {
        $args['meta_key'] = '_techscope_hero_slider';
        $args['meta_value'] = '1';
    }

    return new WP_Query($args);
}

// Get featured posts
function techscope_get_featured_posts($limit = null, $categories = array()) {
    $trending_count = get_option('techscope_trending_count', 4);
    $trending_categories = (array) get_option('techscope_trending_categories', []);

    if ($limit === null) {
        $limit = $trending_count;
    }

    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $use_categories = !empty($categories) ? $categories : $trending_categories;

    if (!empty($use_categories)) {
        $args['category__in'] = array_map('intval', $use_categories);
    } else {
        $args['meta_key'] = '_techscope_featured';
        $args['meta_value'] = '1';
    }

    return new WP_Query($args);
}

// Get post rating
function techscope_get_post_rating($post_id) {
    $rating = get_post_meta($post_id, '_techscope_rating', true);
    return $rating ? floatval($rating) : 0;
}

// Format post rating for display
function techscope_display_rating($post_id) {
    $rating = techscope_get_post_rating($post_id);
    if ($rating > 0) {
        $percentage = ($rating / 10) * 100;
        return '<div class="flex items-center gap-1">
                    <span>' . number_format($rating, 1) . '</span>
                    <div class="rating-bar w-8 md:w-12" style="--rating: ' . $percentage . '%"></div>
                </div>';
    }
    return '';
}

// Get post view count
function techscope_get_post_views($post_id) {
    $count = get_post_meta($post_id, '_techscope_post_views', true);
    return $count ? intval($count) : 0;
}

// Increment post view count
function techscope_increment_post_views($post_id) {
    $count = techscope_get_post_views($post_id);
    update_post_meta($post_id, '_techscope_post_views', $count + 1);
}

// Format view count for display
function techscope_format_view_count($count) {
    if ($count >= 1000) {
        return number_format($count / 1000, 1) . 'K';
    }
    return $count;
}

// Get responsive image with fallback
function techscope_get_responsive_image($post_id, $size = 'medium', $fallback_url = '') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    } elseif (!empty($fallback_url)) {
        return $fallback_url;
    } else {
        // Default tech image from Unsplash
        return 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
    }
}

// Truncate text
function techscope_truncate_text($text, $length = 100) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Custom Image Sizes
 */
function techscope_image_sizes() {
    add_image_size('hero-slider', 1200, 600, true);
    add_image_size('featured-card', 400, 250, true);
    add_image_size('sidebar-thumb', 80, 80, true);
}
add_action('after_setup_theme', 'techscope_image_sizes');

/**
 * Customize Excerpt
 */
function techscope_custom_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'techscope_custom_excerpt_length');

function techscope_custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'techscope_custom_excerpt_more');

/**
 * Add body classes
 */
function techscope_body_classes($classes) {
    $classes[] = 'bg-gray-100';
    return $classes;
}
add_filter('body_class', 'techscope_body_classes');

/**
 * Remove default WordPress styling
 */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/**
 * Clean up WordPress head
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');

/**
 * Security enhancements
 */
function techscope_remove_version() {
    return '';
}
add_filter('the_generator', 'techscope_remove_version');

// Hide login errors
function techscope_hide_login_errors() {
    return 'Something is wrong!';
}
add_filter('login_errors', 'techscope_hide_login_errors');

/**
 * TechScope Admin Panel
 */

// Add admin menu
function techscope_admin_menu() {
    add_menu_page(
        __('TechScope Settings', 'techscope'),
        __('TechScope', 'techscope'),
        'manage_options',
        'techscope-settings',
        'techscope_admin_page',
        'dashicons-admin-customizer',
        30
    );

    add_submenu_page(
        'techscope-settings',
        __('Homepage Manager', 'techscope'),
        __('Homepage Manager', 'techscope'),
        'manage_options',
        'techscope-homepage',
        'techscope_homepage_page'
    );

    add_submenu_page(
        'techscope-settings',
        __('Section Controls', 'techscope'),
        __('Section Controls', 'techscope'),
        'manage_options',
        'techscope-sections',
        'techscope_sections_page'
    );
}
add_action('admin_menu', 'techscope_admin_menu');

// Main admin page
function techscope_admin_page() {
    if (isset($_POST['submit'])) {
        check_admin_referer('techscope_settings');

        // Save general settings
        update_option('techscope_theme_color', sanitize_text_field($_POST['theme_color']));
        update_option('techscope_layout_mode', sanitize_text_field($_POST['layout_mode']));

        echo '<div class="notice notice-success"><p>' . __('Settings saved!', 'techscope') . '</p></div>';
    }

    $theme_color = get_option('techscope_theme_color', 'blue');
    $layout_mode = get_option('techscope_layout_mode', 'standard');
    ?>
    <div class="wrap">
        <h1><?php _e('TechScope Theme Settings', 'techscope'); ?></h1>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('General Settings', 'techscope'); ?></h2>
            <form method="post">
                <?php wp_nonce_field('techscope_settings'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Theme Color', 'techscope'); ?></th>
                        <td>
                            <select name="theme_color">
                                <option value="blue" <?php selected($theme_color, 'blue'); ?>><?php _e('Blue', 'techscope'); ?></option>
                                <option value="purple" <?php selected($theme_color, 'purple'); ?>><?php _e('Purple', 'techscope'); ?></option>
                                <option value="green" <?php selected($theme_color, 'green'); ?>><?php _e('Green', 'techscope'); ?></option>
                                <option value="orange" <?php selected($theme_color, 'orange'); ?>><?php _e('Orange', 'techscope'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Layout Mode', 'techscope'); ?></th>
                        <td>
                            <select name="layout_mode">
                                <option value="standard" <?php selected($layout_mode, 'standard'); ?>><?php _e('Standard', 'techscope'); ?></option>
                                <option value="wide" <?php selected($layout_mode, 'wide'); ?>><?php _e('Wide', 'techscope'); ?></option>
                                <option value="full" <?php selected($layout_mode, 'full'); ?>><?php _e('Full Width', 'techscope'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
    </div>
    <?php
}

// Homepage manager page
function techscope_homepage_page() {
    if (isset($_POST['submit'])) {
        check_admin_referer('techscope_homepage');

        // Hero Slider Settings
        update_option('techscope_hero_count', intval($_POST['hero_count']));
        if (isset($_POST['hero_categories'])) {
            update_option('techscope_hero_categories', array_map('intval', $_POST['hero_categories']));
        }
        update_option('techscope_hero_autoplay', isset($_POST['hero_autoplay']) ? 1 : 0);
        update_option('techscope_hero_timing', intval($_POST['hero_timing']));

        // Trending Tech Settings
        update_option('techscope_trending_count', intval($_POST['trending_count']));
        if (isset($_POST['trending_categories'])) {
            update_option('techscope_trending_categories', array_map('intval', $_POST['trending_categories']));
        }
        update_option('techscope_trending_layout', sanitize_text_field($_POST['trending_layout']));

        // Editor's Choice Settings
        if (isset($_POST['editor_categories'])) {
            update_option('techscope_editor_categories', array_map('intval', $_POST['editor_categories']));
        }
        update_option('techscope_editor_secondary_count', intval($_POST['editor_secondary_count']));

        // Mobile Tech Settings
        if (isset($_POST['mobile_categories'])) {
            update_option('techscope_mobile_categories', array_map('intval', $_POST['mobile_categories']));
        }
        update_option('techscope_mobile_count', intval($_POST['mobile_count']));
        update_option('techscope_mobile_title', sanitize_text_field($_POST['mobile_title']));

        // AI & Gaming Settings
        if (isset($_POST['ai_categories'])) {
            update_option('techscope_ai_categories', array_map('intval', $_POST['ai_categories']));
        }
        update_option('techscope_ai_count', intval($_POST['ai_count']));
        update_option('techscope_ai_title', sanitize_text_field($_POST['ai_title']));

        // HOT STORIES Settings
        if (isset($_POST['hot_categories'])) {
            update_option('techscope_hot_categories', array_map('intval', $_POST['hot_categories']));
        }
        update_option('techscope_hot_count', intval($_POST['hot_count']));
        update_option('techscope_hot_title', sanitize_text_field($_POST['hot_title']));

        // Section Titles
        update_option('techscope_trending_title', sanitize_text_field($_POST['trending_title']));
        update_option('techscope_editor_title', sanitize_text_field($_POST['editor_title']));

        echo '<div class="notice notice-success"><p>' . __('Homepage settings saved!', 'techscope') . '</p></div>';
    }

    // Get current settings
    $hero_count = get_option('techscope_hero_count', 3);
    $hero_autoplay = get_option('techscope_hero_autoplay', 1);
    $hero_timing = get_option('techscope_hero_timing', 5000);

    $trending_count = get_option('techscope_trending_count', 4);
    $trending_layout = get_option('techscope_trending_layout', '2x2');
    $editor_secondary_count = get_option('techscope_editor_secondary_count', 4);

    $mobile_count = get_option('techscope_mobile_count', 3);
    $mobile_title = get_option('techscope_mobile_title', 'MOBILE TECH');

    $ai_count = get_option('techscope_ai_count', 3);
    $ai_title = get_option('techscope_ai_title', 'AI & GAMING');

    $hot_count = get_option('techscope_hot_count', 4);
    $hot_title = get_option('techscope_hot_title', 'HOT STORIES');

    $trending_title = get_option('techscope_trending_title', 'TRENDING TECH');
    $editor_title = get_option('techscope_editor_title', "EDITOR'S CHOICE");

    ?>
    <div class="wrap">
        <h1><?php _e('Homepage Manager', 'techscope'); ?></h1>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Handle parent category change
            $('.parent-category-dropdown').on('change', function() {
                const parentId = $(this).val();
                const subcategoryDropdown = $(this).closest('tr').next().find('.subcategory-dropdown');

                if (parentId) {
                    // Enable and load subcategories
                    subcategoryDropdown.prop('disabled', false);
                    subcategoryDropdown.html('<option value="">Loading...</option>');

                    $.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            action: 'techscope_load_subcategories',
                            parent_id: parentId,
                            nonce: '<?php echo wp_create_nonce("techscope_admin_nonce"); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                subcategoryDropdown.html(response.data);
                            } else {
                                subcategoryDropdown.html('<option value="">Error loading subcategories</option>');
                            }
                        },
                        error: function() {
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
                    $(this).trigger('change');
                }
            });
        });
        </script>

        <form method="post">
            <?php wp_nonce_field('techscope_homepage'); ?>

            <div class="postbox" style="margin-top: 20px;">
                <h2 class="hndle"><?php _e('🎯 Hero Slider Settings', 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Number of Slides', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="hero_count" value="<?php echo $hero_count; ?>" min="1" max="7" />
                                <p class="description"><?php _e('Number of posts to show in hero slider (1-7)', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_hero_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show featured posts only.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Auto-play', 'techscope'); ?></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="hero_autoplay" value="1" <?php checked($hero_autoplay, 1); ?> />
                                    <?php _e('Enable auto-play', 'techscope'); ?>
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Slide Timing (ms)', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="hero_timing" value="<?php echo $hero_timing; ?>" min="2000" max="10000" step="500" />
                                <p class="description"><?php _e('Time between slides in milliseconds (2000-10000)', 'techscope'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <h2 class="hndle"><?php _e('🔥 Trending Tech Settings', 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Section Title', 'techscope'); ?></th>
                            <td>
                                <input type="text" name="trending_title" value="<?php echo esc_attr($trending_title); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Number of Posts', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="trending_count" value="<?php echo $trending_count; ?>" min="2" max="8" />
                                <p class="description"><?php _e('Number of trending posts to display (2-8)', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_trending_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Layout', 'techscope'); ?></th>
                            <td>
                                <select name="trending_layout">
                                    <option value="2x2" <?php selected($trending_layout, '2x2'); ?>><?php _e('2x2 Grid', 'techscope'); ?></option>
                                    <option value="1x4" <?php selected($trending_layout, '1x4'); ?>><?php _e('1x4 Row', 'techscope'); ?></option>
                                    <option value="4x1" <?php selected($trending_layout, '4x1'); ?>><?php _e('4x1 Column', 'techscope'); ?></option>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <h2 class="hndle"><?php _e("⭐ Editor's Choice Settings", 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Section Title', 'techscope'); ?></th>
                            <td>
                                <input type="text" name="editor_title" value="<?php echo esc_attr($editor_title); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_editor_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Secondary Posts Count', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="editor_secondary_count" value="<?php echo $editor_secondary_count; ?>" min="1" max="5" />
                                <p class="description"><?php _e('Number of small posts in right grid (1-5)', 'techscope'); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <h2 class="hndle"><?php _e('📱 Mobile Tech Settings', 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Section Title', 'techscope'); ?></th>
                            <td>
                                <input type="text" name="mobile_title" value="<?php echo esc_attr($mobile_title); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_mobile_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Number of Posts', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="mobile_count" value="<?php echo $mobile_count; ?>" min="2" max="6" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <h2 class="hndle"><?php _e('🤖 AI & Gaming Settings', 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Section Title', 'techscope'); ?></th>
                            <td>
                                <input type="text" name="ai_title" value="<?php echo esc_attr($ai_title); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_ai_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Number of Posts', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="ai_count" value="<?php echo $ai_count; ?>" min="2" max="6" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="postbox">
                <h2 class="hndle"><?php _e('🔥 HOT STORIES Settings', 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Section Title', 'techscope'); ?></th>
                            <td>
                                <input type="text" name="hot_title" value="<?php echo esc_attr($hot_title); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_hot_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Number of Posts', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="hot_count" value="<?php echo $hot_count; ?>" min="2" max="8" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php submit_button(__('Save Homepage Settings', 'techscope')); ?>
        </form>
    </div>
    <?php
}

// Section controls page
function techscope_sections_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Section Controls', 'techscope'); ?></h1>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php _e('Section Visibility', 'techscope'); ?></h2>
            <p><?php _e('Control which sections appear on your homepage.', 'techscope'); ?></p>

            <form method="post">
                <?php wp_nonce_field('techscope_sections'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Hero Slider', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_hero" value="1" checked />
                                <?php _e('Show Hero Slider', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Trending Tech', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_trending" value="1" checked />
                                <?php _e('Show Trending Tech Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e("Editor's Choice", 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_editor" value="1" checked />
                                <?php _e("Show Editor's Choice Section", 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('HOT STORIES', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_hot" value="1" checked />
                                <?php _e('Show HOT STORIES Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Mobile Tech', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_mobile" value="1" checked />
                                <?php _e('Show Mobile Tech Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('AI & Gaming', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_ai" value="1" checked />
                                <?php _e('Show AI & Gaming Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Save Section Settings', 'techscope')); ?>
            </form>
        </div>
    </div>
    <?php
}

// Helper functions for admin-controlled sections
function techscope_get_editor_posts() {
    $editor_categories = (array) get_option('techscope_editor_categories', []);
    $editor_secondary_count = get_option('techscope_editor_secondary_count', 4);

    $args = array(
        'posts_per_page' => $editor_secondary_count + 1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($editor_categories)) {
        $args['category__in'] = array_map('intval', $editor_categories);
    } else {
        $args['meta_key'] = '_techscope_featured';
        $args['meta_value'] = '1';
    }

    return new WP_Query($args);
}

function techscope_get_mobile_posts() {
    $mobile_categories = (array) get_option('techscope_mobile_categories', []);
    $mobile_count = get_option('techscope_mobile_count', 3);

    $args = array(
        'posts_per_page' => $mobile_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($mobile_categories)) {
        $args['category__in'] = array_map('intval', $mobile_categories);
    }

    return new WP_Query($args);
}


function techscope_get_section_title($section) {
    switch ($section) {
        case 'trending':
            return get_option('techscope_trending_title', 'TRENDING TECH');
        case 'editor':
            return get_option('techscope_editor_title', "EDITOR'S CHOICE");
        case 'mobile':
            return get_option('techscope_mobile_title', 'MOBILE TECH');
        case 'ai':
            return get_option('techscope_ai_title', 'AI & GAMING');
        case 'hot':
            return get_option('techscope_hot_title', 'HOT STORIES');
        default:
            return '';
    }
}

// AJAX handler for loading subcategories
function techscope_load_subcategories() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    $parent_id = intval($_POST['parent_id']);

    if (empty($parent_id)) {
        wp_send_json_error('No parent ID provided');
    }

    // Use child_of to get ALL subcategories (children and grandchildren)
    $subcategories = get_categories(array(
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
        'child_of' => $parent_id
    ));

    $options = '<option value="">All subcategories</option>';

    if (!empty($subcategories)) {
        foreach ($subcategories as $cat) {
            // Add indentation for nested levels
            $level = 0;
            $parent_check = $cat->parent;
            while ($parent_check != $parent_id && $parent_check != 0) {
                $parent_cat = get_category($parent_check);
                if ($parent_cat) {
                    $parent_check = $parent_cat->parent;
                    $level++;
                } else {
                    break;
                }
            }

            $indent = str_repeat('—', $level);
            $options .= '<option value="' . $cat->term_id . '">';
            $options .= $indent . ' ' . esc_html($cat->name) . ' (' . $cat->count . ')';
            $options .= '</option>';
        }
    } else {
        $options .= '<option value="" disabled>No subcategories</option>';
    }

    wp_send_json_success($options);
}
add_action('wp_ajax_techscope_load_subcategories', 'techscope_load_subcategories');

// Helper function to get parent categories dropdown
function techscope_get_parent_categories_dropdown($selected_value = '', $name = 'parent_category', $all_option_text = 'All Categories') {
    // Get only parent categories (parent = 0)
    $parent_categories = get_categories(array(
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
        'parent' => 0
    ));

    $output = '<select name="' . $name . '" class="parent-category-dropdown" data-subcategory-target="' . str_replace('parent_', '', $name) . '">';
    $output .= '<option value="">' . $all_option_text . '</option>';

    foreach ($parent_categories as $cat) {
        $selected = selected($selected_value, $cat->term_id, false);
        $output .= '<option value="' . $cat->term_id . '"' . $selected . '>';
        $output .= esc_html($cat->name) . ' (' . $cat->count . ')';
        $output .= '</option>';
    }

    $output .= '</select>';
    return $output;
}

// Helper function to get subcategories dropdown
function techscope_get_subcategories_dropdown($parent_id = '', $selected_value = '', $name = 'subcategory', $placeholder = 'Select parent first') {
    $disabled = empty($parent_id) ? 'disabled' : '';
    $output = '<select name="' . $name . '" class="subcategory-dropdown" ' . $disabled . '>';

    if (empty($parent_id)) {
        $output .= '<option value="">' . $placeholder . '</option>';
    } else {
        // Use child_of to get ALL subcategories (children and grandchildren)
        $subcategories = get_categories(array(
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC',
            'child_of' => intval($parent_id)
        ));

        $output .= '<option value="">All subcategories</option>';

        if (!empty($subcategories)) {
            foreach ($subcategories as $cat) {
                // Add indentation for nested levels
                $level = 0;
                $parent_check = $cat->parent;
                while ($parent_check != $parent_id && $parent_check != 0) {
                    $parent_cat = get_category($parent_check);
                    if ($parent_cat) {
                        $parent_check = $parent_cat->parent;
                        $level++;
                    } else {
                        break;
                    }
                }

                $indent = str_repeat('—', $level);
                $selected = selected($selected_value, $cat->term_id, false);
                $output .= '<option value="' . $cat->term_id . '"' . $selected . '>';
                $output .= $indent . ' ' . esc_html($cat->name) . ' (' . $cat->count . ')';
                $output .= '</option>';
            }
        } else {
            $output .= '<option value="" disabled>No subcategories</option>';
        }
    }

    $output .= '</select>';
    return $output;
}

// Update helper functions for new structure
function techscope_get_ai_gaming_posts() {
    $ai_categories = (array) get_option('techscope_ai_categories', []);
    $ai_count = get_option('techscope_ai_count', 3);

    $args = array(
        'posts_per_page' => $ai_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($ai_categories)) {
        $args['category__in'] = array_map('intval', $ai_categories);
    }

    return new WP_Query($args);
}

function techscope_get_hot_stories_posts() {
    $hot_categories = (array) get_option('techscope_hot_categories', []);
    $hot_count = get_option('techscope_hot_count', 4);

    $args = array(
        'posts_per_page' => $hot_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($hot_categories)) {
        $args['category__in'] = array_map('intval', $hot_categories);
    }

    return new WP_Query($args);
}

// Helper function to get all categories dropdown - Using WordPress correct method for subcategories
function techscope_get_categories_dropdown($selected_value = '', $name = 'category', $all_option_text = 'All Categories') {
    $output = '<select name="' . esc_attr($name) . '" class="regular-text">';
    $output .= '<option value="">' . esc_html($all_option_text) . '</option>';

    // First get all parent categories (parent = 0)
    $parent_categories = get_categories(array(
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC',
        'parent' => 0
    ));

    $total_categories = count($parent_categories);
    $total_subcategories = 0;

    if (!empty($parent_categories)) {
        foreach ($parent_categories as $parent_cat) {
            $selected = selected($selected_value, $parent_cat->term_id, false);

            // Add parent category
            $output .= '<option value="' . esc_attr($parent_cat->term_id) . '"' . $selected . '>';
            $output .= esc_html($parent_cat->name) . ' (Parent)';
            $output .= '</option>';

            // Get subcategories for this parent using 'parent' parameter as shown in StackOverflow
            $subcategories = get_categories(array(
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
                'parent' => $parent_cat->term_id
            ));

            $total_subcategories += count($subcategories);

            // Add subcategories
            if (!empty($subcategories)) {
                foreach ($subcategories as $sub_cat) {
                    $selected = selected($selected_value, $sub_cat->term_id, false);
                    $output .= '<option value="' . esc_attr($sub_cat->term_id) . '"' . $selected . '>';
                    $output .= '— ' . esc_html($sub_cat->name) . ' (Sub of: ' . esc_html($parent_cat->name) . ')';
                    $output .= '</option>';
                }
            }
        }
    }

    $output .= '</select>';


    return $output;
}


// TechScope theme admin functions are now integrated directly

// Helper function to display category checkboxes with direct database query
function techscope_display_category_checkboxes($option_name) {
    $selected = (array) get_option($option_name, []);
    $field_name = str_replace('techscope_', '', str_replace('_categories', '', $option_name));

    echo '<div class="categorydiv" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">';

    global $wpdb;
    $prefix = $wpdb->prefix;

    // Get all parent categories first
    $parent_categories = $wpdb->get_results("SELECT t.term_id, t.name, tt.parent FROM {$prefix}terms t JOIN {$prefix}term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' AND tt.parent = 0 ORDER BY t.name ASC");

    foreach($parent_categories as $parent) {
        $checked = in_array($parent->term_id, $selected) ? 'checked="checked"' : '';
        echo '<label class="selectit">';
        echo '<input value="' . $parent->term_id . '" type="checkbox" name="' . $field_name . '_categories[]" id="in-category-' . $field_name . '-' . $parent->term_id . '" ' . $checked . '>';
        echo ' ' . esc_html($parent->name);
        echo '</label><br>';

        // Get subcategories for this parent
        $subcategories = $wpdb->get_results($wpdb->prepare("SELECT t.term_id, t.name FROM {$prefix}terms t JOIN {$prefix}term_taxonomy tt ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' AND tt.parent = %d ORDER BY t.name ASC", $parent->term_id));

        foreach($subcategories as $subcat) {
            $checked = in_array($subcat->term_id, $selected) ? 'checked="checked"' : '';
            echo '<label class="selectit" style="margin-left: 20px;">';
            echo '<input value="' . $subcat->term_id . '" type="checkbox" name="' . $field_name . '_categories[]" id="in-category-' . $subcat->term_id . '" ' . $checked . '>';
            echo ' — ' . esc_html($subcat->name);
            echo '</label><br>';
        }
    }

    echo '</div>';
}

// Menu Walker Classes and Fallback Functions
class TechScope_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Get icon based on menu item title
        $icons = array(
            'mobile' => 'phone_iphone',
            'мобильные' => 'phone_iphone',
            'ai' => 'auto_awesome',
            'ии' => 'auto_awesome',
            'games' => 'sports_esports',
            'игры' => 'sports_esports',
            'laptops' => 'laptop_mac',
            'ноутбуки' => 'laptop_mac',
            'gadgets' => 'bolt',
            'гаджеты' => 'bolt',
            'startups' => 'rocket_launch',
            'стартапы' => 'rocket_launch',
            'reviews' => 'reviews',
            'обзоры' => 'reviews'
        );

        $item_name_lower = strtolower($item->title);
        $icon = isset($icons[$item_name_lower]) ? $icons[$item_name_lower] : 'category';

        $current_class = in_array('current-menu-item', $classes) ? 'text-blue-600' : 'text-gray-500 hover:text-gray-700';

        $output .= '<a href="' . $item->url . '" class="relative pb-3 px-1 sm:px-2 ' . $current_class . ' font-medium text-base sm:text-lg transition-colors duration-200">';
        $output .= '<span class="flex items-center gap-2">';
        $output .= '<span class="material-icons">' . $icon . '</span>';
        $output .= $item->title;
        $output .= '</span>';
        if (in_array('current-menu-item', $classes)) {
            $output .= '<div class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-full"></div>';
        }
        $output .= '</a>';
    }
}

class TechScope_Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Get icon based on menu item title
        $icons = array(
            'mobile' => 'phone_iphone',
            'мобильные' => 'phone_iphone',
            'ai' => 'auto_awesome',
            'ии' => 'auto_awesome',
            'games' => 'sports_esports',
            'игры' => 'sports_esports',
            'laptops' => 'laptop_mac',
            'ноутбуки' => 'laptop_mac',
            'gadgets' => 'bolt',
            'гаджеты' => 'bolt',
            'startups' => 'rocket_launch',
            'стартапы' => 'rocket_launch',
            'reviews' => 'reviews',
            'обзоры' => 'reviews'
        );

        $item_name_lower = strtolower($item->title);
        $icon = isset($icons[$item_name_lower]) ? $icons[$item_name_lower] : 'category';

        $current_class = in_array('current-menu-item', $classes) ? 'text-blue-600 font-medium bg-blue-50 border-r-2 border-blue-600' : 'text-gray-600 hover:bg-gray-50';

        $output .= '<a href="' . $item->url . '" class="w-full text-left px-4 py-3 ' . $current_class . ' transition-colors block">';
        $output .= '<span class="flex items-center gap-3">';
        $output .= '<span class="material-icons">' . $icon . '</span>';
        $output .= $item->title;
        $output .= '</span>';
        $output .= '</a>';
    }
}

// Fallback function for desktop menu
function techscope_fallback_menu() {
    // Show categories as fallback
    $categories = get_categories(array(
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty' => true,
        'number' => 7
    ));

    $icons = array(
        'mobile' => 'phone_iphone',
        'мобильные' => 'phone_iphone',
        'ai' => 'auto_awesome',
        'games' => 'sports_esports',
        'laptops' => 'laptop_mac',
        'gadgets' => 'bolt',
        'reviews' => 'reviews'
    );

    foreach ($categories as $category) {
        $cat_name_lower = strtolower($category->name);
        $icon = isset($icons[$cat_name_lower]) ? $icons[$cat_name_lower] : 'category';

        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="relative pb-3 px-1 sm:px-2 text-gray-500 hover:text-gray-700 font-medium text-base sm:text-lg transition-colors duration-200">';
        echo '<span class="flex items-center gap-2">';
        echo '<span class="material-icons">' . $icon . '</span>';
        echo esc_html($category->name);
        echo '</span>';
        echo '</a>';
    }
}

// Fallback function for mobile menu
function techscope_fallback_mobile_menu() {
    // Show categories as fallback
    $categories = get_categories(array(
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty' => true,
        'number' => 7
    ));

    $icons = array(
        'mobile' => 'phone_iphone',
        'мобильные' => 'phone_iphone',
        'ai' => 'auto_awesome',
        'games' => 'sports_esports',
        'laptops' => 'laptop_mac',
        'gadgets' => 'bolt',
        'reviews' => 'reviews'
    );

    foreach ($categories as $category) {
        $cat_name_lower = strtolower($category->name);
        $icon = isset($icons[$cat_name_lower]) ? $icons[$cat_name_lower] : 'category';

        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="w-full text-left px-4 py-3 text-gray-600 hover:bg-gray-50 transition-colors block">';
        echo '<span class="flex items-center gap-3">';
        echo '<span class="material-icons">' . $icon . '</span>';
        echo esc_html($category->name);
        echo '</span>';
        echo '</a>';
    }
}

// Include custom functions
if (file_exists(get_template_directory() . '/inc/custom-functions.php')) {
    require_once get_template_directory() . '/inc/custom-functions.php';
}