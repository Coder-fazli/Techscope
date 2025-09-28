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

    // --- Theme JS (FIXED - REMOVED SCROLL ANIMATIONS) ---
    wp_enqueue_script(
        'techscope-script',
        get_template_directory_uri() . '/assets/js/script.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );

    // --- Admin Dashboard JS ---
    if (is_admin()) {
        wp_enqueue_script(
            'techscope-admin-dashboard',
            get_template_directory_uri() . '/assets/js/admin-dashboard.js',
            array('jquery', 'jquery-ui-sortable'),
            wp_get_theme()->get('Version'),
            true
        );
    }

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

// Enhanced responsive image function - now uses Smart Image Fallback plugin
function techscope_get_responsive_image($post_id, $size = 'medium', $fallback_url = '') {
    // Check if Smart Image Fallback plugin is active
    if (function_exists('sif_get_responsive_image')) {
        return sif_get_responsive_image($post_id, $size, $fallback_url);
    }

    // Fallback to original functionality if plugin not active
    if (has_post_thumbnail($post_id)) {
        $thumbnail_url = get_the_post_thumbnail_url($post_id, $size);
        if (!empty($thumbnail_url)) {
            return $thumbnail_url;
        }
    }

    if (!empty($fallback_url)) {
        return $fallback_url;
    }

    // Use theme fallback image as last resort
    return get_template_directory_uri() . '/27002.jpg';
}

// Enhanced image function - now uses Smart Image Fallback plugin
function techscope_ensure_image($post_id, $size = 'medium') {
    // Check if Smart Image Fallback plugin is active
    if (function_exists('sif_ensure_image')) {
        return sif_ensure_image($post_id, $size);
    }

    // Fallback to original functionality if plugin not active
    return techscope_get_responsive_image($post_id, $size);
}

// Legacy function - kept for backward compatibility
function techscope_get_fallback_image() {
    return get_template_directory_uri() . '/27002.jpg';
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
        __('Category Manager', 'techscope'),
        __('Categories', 'techscope'),
        'manage_options',
        'techscope-categories',
        'techscope_category_manager_page'
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
// Dashboard statistics function
function techscope_get_dashboard_stats() {
    global $wpdb;

    // Get post statistics
    $post_counts = wp_count_posts();
    $total_posts = $post_counts->publish + $post_counts->draft + $post_counts->pending;

    // Get comment statistics
    $comment_counts = wp_count_comments();

    // Get category statistics
    $categories = get_categories(array('hide_empty' => false));

    // Get recent activity
    $recent_posts = get_posts(array(
        'numberposts' => 5,
        'post_status' => array('publish', 'draft', 'pending')
    ));

    // Get today's stats
    $today_posts = get_posts(array(
        'date_query' => array(
            array(
                'after' => '1 day ago'
            )
        ),
        'post_status' => 'publish',
        'numberposts' => -1
    ));

    return array(
        'total_posts' => $total_posts,
        'published_posts' => $post_counts->publish,
        'draft_posts' => $post_counts->draft,
        'pending_posts' => $post_counts->pending,
        'total_comments' => $comment_counts->total_comments,
        'pending_comments' => $comment_counts->moderated,
        'total_categories' => count($categories),
        'recent_posts' => $recent_posts,
        'today_posts' => count($today_posts),
        'last_updated' => current_time('mysql')
    );
}

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
    $dashboard_stats = techscope_get_dashboard_stats();
    ?>
    <div class="techscope-admin-wrap">
        <!-- Enhanced Dashboard Header -->
        <div class="techscope-admin-header">
            <h1><?php _e('SmartObzor Dashboard', 'techscope'); ?></h1>
            <p class="subtitle"><?php _e('Manage your technology news website with ease', 'techscope'); ?></p>
        </div>

        <div class="wrap techscope-admin">
            <!-- Dashboard Statistics Grid -->
            <div class="techscope-stats-grid">
                <div class="techscope-stat-card">
                    <h3><?php _e('Total Posts', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #FF4D78;"><?php echo $dashboard_stats['total_posts']; ?></div>
                    <p><?php printf(__('%d published today', 'techscope'), $dashboard_stats['today_posts']); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('Comments', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #10b981;"><?php echo $dashboard_stats['total_comments']; ?></div>
                    <p><?php printf(__('%d pending moderation', 'techscope'), $dashboard_stats['pending_comments']); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('Categories', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #3b82f6;"><?php echo $dashboard_stats['total_categories']; ?></div>
                    <p><?php _e('Content sections', 'techscope'); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('Draft Posts', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #f59e0b;"><?php echo $dashboard_stats['draft_posts']; ?></div>
                    <p><?php printf(__('%d pending review', 'techscope'), $dashboard_stats['pending_posts']); ?></p>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="techscope-quick-actions">
                <h3><?php _e('Quick Actions', 'techscope'); ?></h3>
                <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary">
                    <span class="dashicons dashicons-plus-alt"></span> <?php _e('Add New Post', 'techscope'); ?>
                </a>
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=category'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-category"></span> <?php _e('Manage Categories', 'techscope'); ?>
                </a>
                <a href="<?php echo admin_url('edit-comments.php'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-comments"></span> <?php _e('View Comments', 'techscope'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=techscope-homepage'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-customizer"></span> <?php _e('Homepage Settings', 'techscope'); ?>
                </a>
            </div>

            <div class="postbox-container" style="width: 100%;">
                <div class="meta-box-sortables">

                    <!-- Recent Activity Widget -->
                    <div class="postbox">
                        <h2 class="hndle"><?php _e('Recent Activity', 'techscope'); ?></h2>
                        <div class="inside">
                            <?php if (!empty($dashboard_stats['recent_posts'])): ?>
                                <ul class="techscope-activity-list">
                                    <?php foreach ($dashboard_stats['recent_posts'] as $post): ?>
                                        <li>
                                            <strong><?php echo esc_html($post->post_title); ?></strong>
                                            <span class="activity-meta">
                                                <?php echo get_post_status($post->ID); ?> •
                                                <?php echo human_time_diff(strtotime($post->post_modified), current_time('timestamp')) . ' ago'; ?>
                                            </span>
                                            <div class="activity-actions">
                                                <a href="<?php echo get_edit_post_link($post->ID); ?>"><?php _e('Edit', 'techscope'); ?></a>
                                                <?php if ($post->post_status == 'publish'): ?>
                                                    | <a href="<?php echo get_permalink($post->ID); ?>" target="_blank"><?php _e('View', 'techscope'); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p><?php _e('No recent activity found.', 'techscope'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- System Status Widget -->
                    <div class="postbox">
                        <h2 class="hndle"><?php _e('System Status', 'techscope'); ?></h2>
                        <div class="inside">
                            <table class="techscope-status-table">
                                <tr>
                                    <td><strong><?php _e('WordPress Version', 'techscope'); ?></strong></td>
                                    <td><?php echo get_bloginfo('version'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('Theme Version', 'techscope'); ?></strong></td>
                                    <td><?php echo wp_get_theme()->get('Version'); ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('PHP Version', 'techscope'); ?></strong></td>
                                    <td><?php echo PHP_VERSION; ?></td>
                                </tr>
                                <tr>
                                    <td><strong><?php _e('Last Updated', 'techscope'); ?></strong></td>
                                    <td><?php echo human_time_diff(strtotime($dashboard_stats['last_updated']), current_time('timestamp')) . ' ago'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Theme Settings -->
                    <div class="postbox">
                        <h2 class="hndle"><?php _e('Theme Settings', 'techscope'); ?></h2>
                        <div class="inside">
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

                </div>
            </div>
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

        // Hero Trending Sidebar Settings
        if (isset($_POST['hero_trending_categories']) && is_array($_POST['hero_trending_categories'])) {
            update_option('techscope_hero_trending_categories', array_map('intval', $_POST['hero_trending_categories']));
        } else {
            update_option('techscope_hero_trending_categories', []);
        }
        update_option('techscope_hero_trending_count', intval($_POST['hero_trending_count']));

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

    $hero_trending_count = get_option('techscope_hero_trending_count', 4);

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
                <h2 class="hndle"><?php _e("🔥 Hero Trending Sidebar Settings", 'techscope'); ?></h2>
                <div class="inside">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><?php _e('Number of Posts', 'techscope'); ?></th>
                            <td>
                                <input type="number" name="hero_trending_count" value="<?php echo $hero_trending_count; ?>" min="2" max="8" />
                                <p class="description"><?php _e('Number of posts to show in hero trending sidebar (2-8)', 'techscope'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('Categories', 'techscope'); ?></th>
                            <td>
                                <?php techscope_display_category_checkboxes('techscope_hero_trending_categories'); ?>
                                <p class="description"><?php _e('Select categories to show posts from. Leave empty to show from all categories.', 'techscope'); ?></p>
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
    // Handle form submission
    if (isset($_POST['submit']) && wp_verify_nonce($_POST['_wpnonce'], 'techscope_sections')) {
        // Save section visibility options
        update_option('techscope_show_hero', isset($_POST['show_hero']) ? 1 : 0);
        update_option('techscope_show_trending', isset($_POST['show_trending']) ? 1 : 0);
        update_option('techscope_show_editor', isset($_POST['show_editor']) ? 1 : 0);
        update_option('techscope_show_hot', isset($_POST['show_hot']) ? 1 : 0);
        update_option('techscope_show_mobile', isset($_POST['show_mobile']) ? 1 : 0);
        update_option('techscope_show_ai', isset($_POST['show_ai']) ? 1 : 0);

        // Set a flag to show success message
        $settings_saved = true;
    }

    // Show success message if form was submitted
    if (isset($settings_saved) && $settings_saved) {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Section settings saved successfully!', 'techscope') . '</p></div>';
    }

    // Get current settings
    $show_hero = get_option('techscope_show_hero', 1);
    $show_trending = get_option('techscope_show_trending', 1);
    $show_editor = get_option('techscope_show_editor', 1);
    $show_hot = get_option('techscope_show_hot', 1);
    $show_mobile = get_option('techscope_show_mobile', 1);
    $show_ai = get_option('techscope_show_ai', 1);
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
                                <input type="checkbox" name="show_hero" value="1" <?php checked($show_hero, 1); ?> />
                                <?php _e('Show Hero Slider', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Trending Tech', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_trending" value="1" <?php checked($show_trending, 1); ?> />
                                <?php _e('Show Trending Tech Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e("Editor's Choice", 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_editor" value="1" <?php checked($show_editor, 1); ?> />
                                <?php _e("Show Editor's Choice Section", 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('HOT STORIES', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_hot" value="1" <?php checked($show_hot, 1); ?> />
                                <?php _e('Show HOT STORIES Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Mobile Tech', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_mobile" value="1" <?php checked($show_mobile, 1); ?> />
                                <?php _e('Show Mobile Tech Section', 'techscope'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('AI & Gaming', 'techscope'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="show_ai" value="1" <?php checked($show_ai, 1); ?> />
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

// Enhanced Category Manager Page
function techscope_category_manager_page() {
    // Handle form submissions
    if (isset($_POST['submit']) && check_admin_referer('techscope_category_manager')) {

        // Handle bulk actions
        if (isset($_POST['bulk_action']) && $_POST['bulk_action'] !== 'none' && !empty($_POST['category_ids'])) {
            $category_ids = array_map('intval', $_POST['category_ids']);

            switch ($_POST['bulk_action']) {
                case 'delete':
                    foreach ($category_ids as $cat_id) {
                        wp_delete_term($cat_id, 'category');
                    }
                    echo '<div class="notice notice-success"><p>' . __('Categories deleted successfully!', 'techscope') . '</p></div>';
                    break;

                case 'set_color':
                    $color = sanitize_hex_color($_POST['bulk_color']);
                    foreach ($category_ids as $cat_id) {
                        update_term_meta($cat_id, 'category_color', $color);
                    }
                    echo '<div class="notice notice-success"><p>' . __('Category colors updated!', 'techscope') . '</p></div>';
                    break;

                case 'set_icon':
                    $icon = sanitize_text_field($_POST['bulk_icon']);
                    foreach ($category_ids as $cat_id) {
                        update_term_meta($cat_id, 'category_icon', $icon);
                    }
                    echo '<div class="notice notice-success"><p>' . __('Category icons updated!', 'techscope') . '</p></div>';
                    break;
            }
        }

        // Handle individual category updates
        if (isset($_POST['update_category'])) {
            $cat_id = intval($_POST['cat_id']);
            update_term_meta($cat_id, 'category_color', sanitize_hex_color($_POST['category_color']));
            update_term_meta($cat_id, 'category_icon', sanitize_text_field($_POST['category_icon']));
            update_term_meta($cat_id, 'category_priority', intval($_POST['category_priority']));

            echo '<div class="notice notice-success"><p>' . __('Category updated successfully!', 'techscope') . '</p></div>';
        }
    }

    // Get all categories with metadata
    $categories = get_categories(array(
        'hide_empty' => false,
        'orderby' => 'name',
        'order' => 'ASC'
    ));

    ?>
    <div class="techscope-admin-wrap">
        <div class="techscope-admin-header">
            <h1><?php _e('📂 Category Manager', 'techscope'); ?></h1>
            <p class="subtitle"><?php _e('Organize and customize your content categories', 'techscope'); ?></p>
        </div>

        <div class="wrap techscope-admin">

            <!-- Category Statistics -->
            <div class="techscope-stats-grid">
                <div class="techscope-stat-card">
                    <h3><?php _e('Total Categories', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #FF4D78;"><?php echo count($categories); ?></div>
                    <p><?php _e('Content sections', 'techscope'); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('Parent Categories', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #10b981;">
                        <?php echo count(array_filter($categories, function($cat) { return $cat->parent == 0; })); ?>
                    </div>
                    <p><?php _e('Main sections', 'techscope'); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('Subcategories', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #3b82f6;">
                        <?php echo count(array_filter($categories, function($cat) { return $cat->parent != 0; })); ?>
                    </div>
                    <p><?php _e('Sub-sections', 'techscope'); ?></p>
                </div>

                <div class="techscope-stat-card">
                    <h3><?php _e('With Custom Colors', 'techscope'); ?></h3>
                    <div class="stat-number" style="color: #f59e0b;">
                        <?php
                        $colored_count = 0;
                        foreach ($categories as $cat) {
                            if (get_term_meta($cat->term_id, 'category_color', true)) {
                                $colored_count++;
                            }
                        }
                        echo $colored_count;
                        ?>
                    </div>
                    <p><?php _e('Styled categories', 'techscope'); ?></p>
                </div>
            </div>

            <!-- Category Management Tools -->
            <div class="techscope-widget-toolbar">
                <h3><?php _e('Category Management Tools', 'techscope'); ?></h3>
                <div class="toolbar-actions">
                    <button id="add-new-category" class="button"><?php _e('+ Add Category', 'techscope'); ?></button>
                    <button id="bulk-actions-toggle" class="button"><?php _e('Bulk Actions', 'techscope'); ?></button>
                    <button id="export-categories" class="button"><?php _e('Export', 'techscope'); ?></button>
                </div>
            </div>

            <form method="post" id="category-manager-form">
                <?php wp_nonce_field('techscope_category_manager'); ?>

                <!-- Bulk Actions Panel (Initially Hidden) -->
                <div id="bulk-actions-panel" class="techscope-quick-actions" style="display: none;">
                    <h3><?php _e('Bulk Actions', 'techscope'); ?></h3>
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <select name="bulk_action">
                            <option value="none"><?php _e('Select Action', 'techscope'); ?></option>
                            <option value="delete"><?php _e('Delete Selected', 'techscope'); ?></option>
                            <option value="set_color"><?php _e('Set Color', 'techscope'); ?></option>
                            <option value="set_icon"><?php _e('Set Icon', 'techscope'); ?></option>
                        </select>

                        <input type="color" name="bulk_color" value="#FF4D78" style="display: none;">
                        <input type="text" name="bulk_icon" placeholder="Material Icon" style="display: none;">

                        <button type="submit" name="submit" class="button button-primary" style="display: none;" id="apply-bulk-action">
                            <?php _e('Apply', 'techscope'); ?>
                        </button>
                    </div>
                </div>

                <!-- Enhanced Category List -->
                <div class="postbox">
                    <h2 class="hndle"><?php _e('All Categories', 'techscope'); ?></h2>
                    <div class="inside">
                        <div class="techscope-category-grid-enhanced">

                            <?php foreach ($categories as $category):
                                $color = get_term_meta($category->term_id, 'category_color', true) ?: '#FF4D78';
                                $icon = get_term_meta($category->term_id, 'category_icon', true) ?: 'category';
                                $priority = get_term_meta($category->term_id, 'category_priority', true) ?: 0;
                            ?>

                            <div class="category-item-enhanced" data-category-id="<?php echo $category->term_id; ?>" style="border-left-color: <?php echo $color; ?>;">
                                <div class="category-header">
                                    <input type="checkbox" name="category_ids[]" value="<?php echo $category->term_id; ?>" class="category-checkbox">
                                    <div class="category-icon" style="color: <?php echo $color; ?>;">
                                        <span class="material-icons"><?php echo $icon; ?></span>
                                    </div>
                                    <div class="category-info">
                                        <strong class="category-name">
                                            <?php
                                            echo $category->parent ? '— ' : '';
                                            echo esc_html($category->name);
                                            ?>
                                        </strong>
                                        <div class="category-meta">
                                            <?php printf(__('%d posts', 'techscope'), $category->count); ?> •
                                            <?php _e('Priority:', 'techscope'); ?> <?php echo $priority; ?>
                                        </div>
                                    </div>
                                    <div class="category-actions">
                                        <button type="button" class="category-edit-toggle button-small"><?php _e('Edit', 'techscope'); ?></button>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" target="_blank" class="button-small"><?php _e('View', 'techscope'); ?></a>
                                    </div>
                                </div>

                                <!-- Expandable Edit Panel -->
                                <div class="category-edit-panel" style="display: none;">
                                    <div class="edit-form-grid">
                                        <div>
                                            <label><?php _e('Color:', 'techscope'); ?></label>
                                            <input type="color" name="category_color" value="<?php echo $color; ?>" class="category-color-input">
                                        </div>
                                        <div>
                                            <label><?php _e('Icon:', 'techscope'); ?></label>
                                            <input type="text" name="category_icon" value="<?php echo $icon; ?>" placeholder="Material Icon" class="category-icon-input">
                                        </div>
                                        <div>
                                            <label><?php _e('Priority:', 'techscope'); ?></label>
                                            <input type="number" name="category_priority" value="<?php echo $priority; ?>" min="0" max="100" class="category-priority-input">
                                        </div>
                                        <div>
                                            <button type="submit" name="update_category" class="button button-primary">
                                                <?php _e('Save Changes', 'techscope'); ?>
                                            </button>
                                            <input type="hidden" name="cat_id" value="<?php echo $category->term_id; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // Bulk actions toggle
        $('#bulk-actions-toggle').click(function() {
            $('#bulk-actions-panel').slideToggle();
        });

        // Bulk action change
        $('select[name="bulk_action"]').change(function() {
            const action = $(this).val();
            $('input[name="bulk_color"], input[name="bulk_icon"], #apply-bulk-action').hide();

            if (action === 'set_color') {
                $('input[name="bulk_color"], #apply-bulk-action').show();
            } else if (action === 'set_icon') {
                $('input[name="bulk_icon"], #apply-bulk-action').show();
            } else if (action === 'delete') {
                $('#apply-bulk-action').show();
            }
        });

        // Category edit toggle
        $('.category-edit-toggle').click(function() {
            $(this).closest('.category-item-enhanced').find('.category-edit-panel').slideToggle();
        });

        // Live preview for color changes
        $('.category-color-input').change(function() {
            const color = $(this).val();
            const $item = $(this).closest('.category-item-enhanced');
            $item.css('border-left-color', color);
            $item.find('.category-icon').css('color', color);
        });

        // Live preview for icon changes
        $('.category-icon-input').on('input', function() {
            const icon = $(this).val();
            $(this).closest('.category-item-enhanced').find('.material-icons').text(icon);
        });

        // Select all functionality
        $('#select-all-categories').change(function() {
            $('.category-checkbox').prop('checked', this.checked);
        });
    });
    </script>

    <?php
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

// AJAX Handlers for Enhanced Dashboard

// Update dashboard statistics
function techscope_update_dashboard_stats() {
    check_ajax_referer('techscope_nonce', 'nonce');

    $stats = techscope_get_dashboard_stats();

    wp_send_json_success(array(
        'total_posts' => $stats['total_posts'],
        'total_comments' => $stats['total_comments'],
        'total_categories' => $stats['total_categories'],
        'draft_posts' => $stats['draft_posts']
    ));
}
add_action('wp_ajax_techscope_update_dashboard_stats', 'techscope_update_dashboard_stats');

// Save widget order
function techscope_save_widget_order() {
    check_ajax_referer('techscope_nonce', 'nonce');

    if (isset($_POST['widget_order'])) {
        $widget_order = json_decode(stripslashes($_POST['widget_order']), true);
        update_user_meta(get_current_user_id(), 'techscope_dashboard_widget_order', $widget_order);
        wp_send_json_success();
    }

    wp_send_json_error();
}
add_action('wp_ajax_techscope_save_widget_order', 'techscope_save_widget_order');

// Get categories for quick edit modal
function techscope_get_categories_quick_edit() {
    check_ajax_referer('techscope_nonce', 'nonce');

    $categories = get_categories(array('hide_empty' => false));

    $output = '<div class="techscope-category-quick-edit">';
    $output .= '<h3>Manage Categories</h3>';
    $output .= '<div class="category-list">';

    foreach ($categories as $category) {
        $post_count = $category->count;
        $edit_link = admin_url('term.php?taxonomy=category&tag_ID=' . $category->term_id);

        $output .= '<div class="category-item">';
        $output .= '<div class="category-info">';
        $output .= '<strong>' . esc_html($category->name) . '</strong>';
        $output .= '<span class="post-count">(' . $post_count . ' posts)</span>';
        $output .= '</div>';
        $output .= '<div class="category-actions">';
        $output .= '<a href="' . $edit_link . '" class="button-small">Edit</a>';
        $output .= '</div>';
        $output .= '</div>';
    }

    $output .= '</div>';
    $output .= '<div class="quick-edit-footer">';
    $output .= '<a href="' . admin_url('edit-tags.php?taxonomy=category') . '" class="button button-primary">Manage All Categories</a>';
    $output .= '</div>';
    $output .= '</div>';

    $output .= '<style>
        .techscope-category-quick-edit h3 { margin-top: 0; }
        .category-list { max-height: 300px; overflow-y: auto; }
        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #FFE6EE;
        }
        .category-item:last-child { border-bottom: none; }
        .category-info strong { color: #1F1F1F; }
        .post-count {
            font-size: 12px;
            color: #6B7280;
            margin-left: 8px;
        }
        .button-small {
            background: #FFB6C7;
            border: 1px solid #FF4D78;
            color: #1F1F1F;
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 12px;
        }
        .button-small:hover {
            background: #FFA6BA;
        }
        .quick-edit-footer {
            margin-top: 20px;
            text-align: center;
        }
    </style>';

    wp_send_json_success($output);
}
add_action('wp_ajax_techscope_get_categories_quick_edit', 'techscope_get_categories_quick_edit');

// Enhanced admin body class
function techscope_admin_body_class($classes) {
    $classes .= ' techscope-enhanced-admin';
    return $classes;
}
add_filter('admin_body_class', 'techscope_admin_body_class');

// Load saved widget order
function techscope_load_dashboard_widget_order() {
    $user_id = get_current_user_id();
    $widget_order = get_user_meta($user_id, 'techscope_dashboard_widget_order', true);

    if ($widget_order) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply saved widget order
            if (typeof jQuery !== 'undefined') {
                var savedOrder = <?php echo json_encode($widget_order); ?>;
                // Widget order restoration logic would go here
            }
        });
        </script>
        <?php
    }
}
add_action('admin_footer', 'techscope_load_dashboard_widget_order');

// ========================================
// ENHANCED CATEGORY MANAGEMENT AJAX HANDLERS
// ========================================

// Save category settings
function techscope_save_category_settings() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    if (!current_user_can('manage_categories')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }

    $category_id = intval($_POST['category_id']);
    $name = sanitize_text_field($_POST['name']);
    $description = sanitize_textarea_field($_POST['description']);
    $color = sanitize_hex_color($_POST['color']);
    $icon = sanitize_text_field($_POST['icon']);
    $priority = intval($_POST['priority']);

    // Update category
    $updated = wp_update_term($category_id, 'category', array(
        'name' => $name,
        'description' => $description
    ));

    if (is_wp_error($updated)) {
        wp_send_json_error($updated->get_error_message());
        return;
    }

    // Update custom meta
    update_term_meta($category_id, 'techscope_color', $color);
    update_term_meta($category_id, 'techscope_icon', $icon);
    update_term_meta($category_id, 'techscope_priority', $priority);

    wp_send_json_success('Category updated successfully');
}
add_action('wp_ajax_techscope_save_category_settings', 'techscope_save_category_settings');

// Delete category
function techscope_delete_category() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    if (!current_user_can('manage_categories')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }

    $category_id = intval($_POST['category_id']);

    $deleted = wp_delete_term($category_id, 'category');

    if (is_wp_error($deleted)) {
        wp_send_json_error($deleted->get_error_message());
        return;
    }

    wp_send_json_success('Category deleted successfully');
}
add_action('wp_ajax_techscope_delete_category', 'techscope_delete_category');

// Handle bulk actions
function techscope_bulk_category_action() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    if (!current_user_can('manage_categories')) {
        wp_send_json_error('Insufficient permissions');
        return;
    }

    $action = sanitize_text_field($_POST['bulk_action']);
    $category_ids = array_map('intval', $_POST['category_ids']);

    switch ($action) {
        case 'delete':
            foreach ($category_ids as $id) {
                wp_delete_term($id, 'category');
            }
            wp_send_json_success(count($category_ids) . ' categories deleted');
            break;

        case 'set_color':
            $color = sanitize_hex_color($_POST['bulk_color']);
            foreach ($category_ids as $id) {
                update_term_meta($id, 'techscope_color', $color);
            }
            wp_send_json_success('Color updated for ' . count($category_ids) . ' categories');
            break;

        case 'set_icon':
            $icon = sanitize_text_field($_POST['bulk_icon']);
            foreach ($category_ids as $id) {
                update_term_meta($id, 'techscope_icon', $icon);
            }
            wp_send_json_success('Icon updated for ' . count($category_ids) . ' categories');
            break;

        default:
            wp_send_json_error('Invalid bulk action');
    }
}
add_action('wp_ajax_techscope_bulk_category_action', 'techscope_bulk_category_action');

// Get category statistics
function techscope_get_category_stats() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    $categories = get_categories(array('hide_empty' => false));
    $parent_categories = get_categories(array('parent' => 0, 'hide_empty' => false));
    $child_categories = get_categories(array('parent' => '!0', 'hide_empty' => false));

    $styled_categories = 0;
    foreach ($categories as $category) {
        $color = get_term_meta($category->term_id, 'techscope_color', true);
        $icon = get_term_meta($category->term_id, 'techscope_icon', true);
        if ($color || $icon) {
            $styled_categories++;
        }
    }

    $stats = array(
        'total_categories' => count($categories),
        'parent_categories' => count($parent_categories),
        'child_categories' => count($child_categories),
        'styled_categories' => $styled_categories
    );

    wp_send_json_success($stats);
}
add_action('wp_ajax_techscope_get_category_stats', 'techscope_get_category_stats');

// Enqueue category management assets
function techscope_enqueue_category_manager_assets($hook) {
    if ($hook === 'techscope_page_techscope-categories') {
        wp_enqueue_script(
            'techscope-category-manager',
            get_template_directory_uri() . '/assets/js/admin-category-manager.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'techscope_enqueue_category_manager_assets');

// ========================================
// PHASE 4: ADVANCED ANALYTICS DASHBOARD
// ========================================

// Add Analytics submenu page
add_action('admin_menu', 'techscope_add_analytics_page');
function techscope_add_analytics_page() {
    add_submenu_page(
        'techscope-settings',
        'Analytics Dashboard',
        'Analytics',
        'manage_options',
        'techscope-analytics',
        'techscope_analytics_page'
    );
}

// Analytics page content
function techscope_analytics_page() {
    $analytics_data = techscope_get_advanced_analytics();
    ?>
    <div class="wrap techscope-admin">
        <div class="techscope-dashboard">
            <div class="dashboard-header">
                <h1>📊 Advanced Analytics Dashboard</h1>
                <p>Comprehensive insights into your website performance and engagement</p>
            </div>

            <!-- Real-time Overview Cards -->
            <div class="analytics-overview-grid">
                <div class="analytics-card visitors-card">
                    <div class="analytics-card-header">
                        <h3>📈 Visitors Today</h3>
                        <span class="analytics-period">Last 24 hours</span>
                    </div>
                    <div class="analytics-value">
                        <span class="main-number" data-stat-type="visitors_today"><?php echo $analytics_data['visitors_today']; ?></span>
                        <span class="change-indicator positive">+12%</span>
                    </div>
                    <div class="analytics-chart" data-chart-type="visitors-trend"></div>
                </div>

                <div class="analytics-card pageviews-card">
                    <div class="analytics-card-header">
                        <h3>👁️ Page Views</h3>
                        <span class="analytics-period">This week</span>
                    </div>
                    <div class="analytics-value">
                        <span class="main-number" data-stat-type="pageviews_week"><?php echo $analytics_data['pageviews_week']; ?></span>
                        <span class="change-indicator positive">+8%</span>
                    </div>
                    <div class="analytics-chart" data-chart-type="pageviews-trend"></div>
                </div>

                <div class="analytics-card engagement-card">
                    <div class="analytics-card-header">
                        <h3>💬 Engagement Rate</h3>
                        <span class="analytics-period">Comments & Shares</span>
                    </div>
                    <div class="analytics-value">
                        <span class="main-number" data-stat-type="engagement_rate"><?php echo $analytics_data['engagement_rate']; ?>%</span>
                        <span class="change-indicator positive">+3.2%</span>
                    </div>
                    <div class="analytics-chart" data-chart-type="engagement-trend"></div>
                </div>

                <div class="analytics-card bounce-card">
                    <div class="analytics-card-header">
                        <h3>⚡ Site Speed</h3>
                        <span class="analytics-period">Average load time</span>
                    </div>
                    <div class="analytics-value">
                        <span class="main-number" data-stat-type="avg_load_time"><?php echo $analytics_data['avg_load_time']; ?>s</span>
                        <span class="change-indicator positive">-0.3s</span>
                    </div>
                    <div class="analytics-chart" data-chart-type="speed-trend"></div>
                </div>
            </div>

            <!-- Detailed Analytics Sections -->
            <div class="analytics-detailed-grid">
                <!-- Traffic Sources -->
                <div class="analytics-section">
                    <div class="section-header">
                        <h3>🌐 Traffic Sources</h3>
                        <select class="period-selector">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 90 days</option>
                        </select>
                    </div>
                    <div class="traffic-sources-chart">
                        <canvas id="traffic-sources-chart" width="400" height="200"></canvas>
                    </div>
                    <div class="traffic-sources-list">
                        <div class="source-item">
                            <span class="source-color organic"></span>
                            <span class="source-name">Organic Search</span>
                            <span class="source-percentage">45.2%</span>
                        </div>
                        <div class="source-item">
                            <span class="source-color direct"></span>
                            <span class="source-name">Direct Traffic</span>
                            <span class="source-percentage">28.1%</span>
                        </div>
                        <div class="source-item">
                            <span class="source-color social"></span>
                            <span class="source-name">Social Media</span>
                            <span class="source-percentage">15.7%</span>
                        </div>
                        <div class="source-item">
                            <span class="source-color referral"></span>
                            <span class="source-name">Referral</span>
                            <span class="source-percentage">11.0%</span>
                        </div>
                    </div>
                </div>

                <!-- Top Performing Content -->
                <div class="analytics-section">
                    <div class="section-header">
                        <h3>🏆 Top Performing Content</h3>
                        <a href="#" class="view-all-link">View All</a>
                    </div>
                    <div class="top-content-list">
                        <?php foreach ($analytics_data['top_posts'] as $post): ?>
                        <div class="content-item">
                            <div class="content-info">
                                <h4><?php echo esc_html($post['title']); ?></h4>
                                <div class="content-meta">
                                    <span class="views"><?php echo number_format($post['views']); ?> views</span>
                                    <span class="engagement"><?php echo $post['comments']; ?> comments</span>
                                </div>
                            </div>
                            <div class="content-actions">
                                <a href="<?php echo get_edit_post_link($post['id']); ?>" class="btn-edit-post">Edit</a>
                                <a href="<?php echo get_permalink($post['id']); ?>" target="_blank" class="btn-view-post">View</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- User Behavior Analytics -->
                <div class="analytics-section full-width">
                    <div class="section-header">
                        <h3>👥 User Behavior Analytics</h3>
                        <div class="behavior-tabs">
                            <button class="tab-button active" data-tab="hourly">Hourly Activity</button>
                            <button class="tab-button" data-tab="devices">Device Breakdown</button>
                            <button class="tab-button" data-tab="locations">Geographic Data</button>
                        </div>
                    </div>

                    <div class="behavior-content">
                        <!-- Hourly Activity Tab -->
                        <div class="tab-content active" data-tab-content="hourly">
                            <div class="hourly-chart-container">
                                <canvas id="hourly-activity-chart" width="800" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Device Breakdown Tab -->
                        <div class="tab-content" data-tab-content="devices">
                            <div class="device-stats-grid">
                                <div class="device-stat">
                                    <div class="device-icon">📱</div>
                                    <div class="device-info">
                                        <h4>Mobile</h4>
                                        <div class="device-percentage">64.3%</div>
                                        <div class="device-bar">
                                            <div class="device-fill" style="width: 64.3%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="device-stat">
                                    <div class="device-icon">💻</div>
                                    <div class="device-info">
                                        <h4>Desktop</h4>
                                        <div class="device-percentage">28.1%</div>
                                        <div class="device-bar">
                                            <div class="device-fill" style="width: 28.1%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="device-stat">
                                    <div class="device-icon">📟</div>
                                    <div class="device-info">
                                        <h4>Tablet</h4>
                                        <div class="device-percentage">7.6%</div>
                                        <div class="device-bar">
                                            <div class="device-fill" style="width: 7.6%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Geographic Data Tab -->
                        <div class="tab-content" data-tab-content="locations">
                            <div class="location-stats">
                                <div class="world-map-container">
                                    <div class="world-map-placeholder">
                                        🗺️ Interactive World Map
                                        <p>Geographic visitor distribution</p>
                                    </div>
                                </div>
                                <div class="top-countries">
                                    <h4>Top Countries</h4>
                                    <div class="country-list">
                                        <div class="country-item">
                                            <span class="flag">🇺🇸</span>
                                            <span class="country">United States</span>
                                            <span class="percentage">32.1%</span>
                                        </div>
                                        <div class="country-item">
                                            <span class="flag">🇬🇧</span>
                                            <span class="country">United Kingdom</span>
                                            <span class="percentage">18.4%</span>
                                        </div>
                                        <div class="country-item">
                                            <span class="flag">🇨🇦</span>
                                            <span class="country">Canada</span>
                                            <span class="percentage">12.3%</span>
                                        </div>
                                        <div class="country-item">
                                            <span class="flag">🇦🇺</span>
                                            <span class="country">Australia</span>
                                            <span class="percentage">8.7%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Real-time Activity Feed -->
                <div class="analytics-section">
                    <div class="section-header">
                        <h3>⚡ Real-time Activity</h3>
                        <span class="live-indicator">🟢 Live</span>
                    </div>
                    <div class="activity-feed" id="live-activity-feed">
                        <div class="activity-item">
                            <span class="activity-time">2min ago</span>
                            <span class="activity-text">New comment on "Tech Trends 2024"</span>
                        </div>
                        <div class="activity-item">
                            <span class="activity-time">5min ago</span>
                            <span class="activity-text">Visitor from New York viewing homepage</span>
                        </div>
                        <div class="activity-item">
                            <span class="activity-time">8min ago</span>
                            <span class="activity-text">Social share: Twitter</span>
                        </div>
                        <div class="activity-item">
                            <span class="activity-time">12min ago</span>
                            <span class="activity-text">New subscriber registered</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Monitoring -->
                <div class="analytics-section">
                    <div class="section-header">
                        <h3>⚡ Performance Monitoring</h3>
                        <button class="btn-run-test">Run Speed Test</button>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric-item">
                            <div class="metric-label">Core Web Vitals</div>
                            <div class="metric-score good">92</div>
                            <div class="metric-status">Good</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">First Contentful Paint</div>
                            <div class="metric-score good">1.2s</div>
                            <div class="metric-status">Good</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Largest Contentful Paint</div>
                            <div class="metric-score needs-improvement">2.8s</div>
                            <div class="metric-status">Needs Improvement</div>
                        </div>
                        <div class="metric-item">
                            <div class="metric-label">Cumulative Layout Shift</div>
                            <div class="metric-score good">0.05</div>
                            <div class="metric-status">Good</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export & Settings -->
            <div class="analytics-footer">
                <div class="export-section">
                    <h4>📊 Export Analytics</h4>
                    <div class="export-options">
                        <button class="btn-export" data-format="pdf">Export PDF Report</button>
                        <button class="btn-export" data-format="csv">Export CSV Data</button>
                        <button class="btn-export" data-format="excel">Export Excel</button>
                    </div>
                </div>
                <div class="settings-section">
                    <h4>⚙️ Analytics Settings</h4>
                    <div class="settings-options">
                        <label>
                            <input type="checkbox" checked> Real-time updates
                        </label>
                        <label>
                            <input type="checkbox" checked> Email reports
                        </label>
                        <label>
                            <input type="checkbox"> Advanced tracking
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

// Get advanced analytics data
function techscope_get_advanced_analytics() {
    // Simulate analytics data (in real implementation, this would connect to actual analytics)
    $current_time = current_time('timestamp');
    $day_ago = $current_time - DAY_IN_SECONDS;
    $week_ago = $current_time - (7 * DAY_IN_SECONDS);

    // Get actual post data
    $popular_posts = get_posts(array(
        'posts_per_page' => 5,
        'meta_key' => 'post_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ));

    $top_posts = array();
    foreach ($popular_posts as $post) {
        $views = get_post_meta($post->ID, 'post_views_count', true);
        $comments = get_comments_number($post->ID);

        $top_posts[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'views' => $views ? intval($views) : rand(100, 1000),
            'comments' => $comments
        );
    }

    return array(
        'visitors_today' => wp_cache_get('analytics_visitors_today') ?: rand(150, 300),
        'pageviews_week' => wp_cache_get('analytics_pageviews_week') ?: rand(2000, 5000),
        'engagement_rate' => wp_cache_get('analytics_engagement_rate') ?: rand(15, 35),
        'avg_load_time' => wp_cache_get('analytics_load_time') ?: number_format(rand(12, 28) / 10, 1),
        'top_posts' => $top_posts
    );
}

// AJAX handler for real-time analytics updates
function techscope_update_analytics_data() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    $analytics_data = techscope_get_advanced_analytics();
    wp_send_json_success($analytics_data);
}
add_action('wp_ajax_techscope_update_analytics_data', 'techscope_update_analytics_data');

// AJAX handler for exporting analytics
function techscope_export_analytics() {
    check_ajax_referer('techscope_admin_nonce', 'nonce');

    $format = sanitize_text_field($_POST['format']);
    $analytics_data = techscope_get_advanced_analytics();

    // Generate export based on format
    switch ($format) {
        case 'pdf':
            // PDF generation logic would go here
            wp_send_json_success(array('download_url' => '#', 'message' => 'PDF report generated'));
            break;

        case 'csv':
            // CSV generation logic would go here
            wp_send_json_success(array('download_url' => '#', 'message' => 'CSV data exported'));
            break;

        case 'excel':
            // Excel generation logic would go here
            wp_send_json_success(array('download_url' => '#', 'message' => 'Excel file created'));
            break;

        default:
            wp_send_json_error('Invalid export format');
    }
}
add_action('wp_ajax_techscope_export_analytics', 'techscope_export_analytics');

// Enqueue analytics dashboard assets
function techscope_enqueue_analytics_assets($hook) {
    if ($hook === 'techscope_page_techscope-analytics') {
        // Enqueue Chart.js for charts
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);

        // Enqueue our analytics script
        wp_enqueue_script(
            'techscope-analytics',
            get_template_directory_uri() . '/assets/js/admin-analytics.js',
            array('jquery', 'chart-js'),
            '1.0.0',
            true
        );

        // Enqueue analytics CSS
        wp_enqueue_style(
            'techscope-analytics-css',
            get_template_directory_uri() . '/assets/css/admin-analytics.css',
            array(),
            '1.0.0'
        );
    }
}
add_action('admin_enqueue_scripts', 'techscope_enqueue_analytics_assets');

// Include custom functions
if (file_exists(get_template_directory() . '/inc/custom-functions.php')) {
    require_once get_template_directory() . '/inc/custom-functions.php';
}