<?php
/**
 * SmartObzor Custom Functions
 * Helper functions for the modern tech design
 */

defined('ABSPATH') || exit;

/**
 * Get responsive image URL with fallback
 */
function smartobzor_get_responsive_image($post_id, $size = 'medium', $fallback_category = 'tech') {
    $image_url = get_the_post_thumbnail_url($post_id, $size);

    if (!$image_url) {
        // Fallback images based on category or general tech images
        $fallback_images = array(
            'tech' => array(
                'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ),
            'mobile' => array(
                'https://images.unsplash.com/photo-1605792657660-596af9009e82?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ),
            'ai' => array(
                'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1555255707-c07966088b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            ),
            'gaming' => array(
                'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'https://images.unsplash.com/photo-1542751371-adc38448a05e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
            )
        );

        $category_images = isset($fallback_images[$fallback_category])
            ? $fallback_images[$fallback_category]
            : $fallback_images['tech'];

        $image_url = $category_images[array_rand($category_images)];
    }

    return $image_url;
}

/**
 * Truncate title with ellipsis
 */
function smartobzor_truncate_title($title, $max_length = 60) {
    if (strlen($title) <= $max_length) {
        return $title;
    }

    $truncated = substr($title, 0, $max_length);
    $last_space = strrpos($truncated, ' ');

    if ($last_space !== false) {
        $truncated = substr($truncated, 0, $last_space);
    }

    return $truncated . '...';
}

/**
 * Get post category with fallback
 */
function smartobzor_get_post_category($post_id) {
    $categories = get_the_category($post_id);

    if (!empty($categories)) {
        return $categories[0];
    }

    // Return a default category object
    return (object) array(
        'name' => 'Tech',
        'term_id' => 1,
        'slug' => 'tech'
    );
}

/**
 * Get category icon based on name
 */
function smartobzor_get_category_icon($category_name) {
    $category_icons = array(
        'мобильные' => 'phone_iphone',
        'mobile' => 'phone_iphone',
        'смартфон' => 'phone_iphone',
        'ии' => 'auto_awesome',
        'ai' => 'auto_awesome',
        'искусственный' => 'auto_awesome',
        'игры' => 'sports_esports',
        'games' => 'sports_esports',
        'gaming' => 'sports_esports',
        'ноутбуки' => 'laptop_mac',
        'laptops' => 'laptop_mac',
        'компьютер' => 'laptop_mac',
        'гаджеты' => 'bolt',
        'gadgets' => 'bolt',
        'устройств' => 'bolt',
        'стартапы' => 'rocket_launch',
        'startups' => 'rocket_launch',
        'startup' => 'rocket_launch',
        'обзоры' => 'reviews',
        'reviews' => 'reviews',
        'обзор' => 'reviews',
        'новости' => 'newspaper',
        'news' => 'newspaper',
        'техника' => 'memory',
        'технологии' => 'psychology',
        'technology' => 'psychology'
    );

    $category_lower = mb_strtolower($category_name, 'UTF-8');

    foreach ($category_icons as $keyword => $icon) {
        if (strpos($category_lower, $keyword) !== false) {
            return $icon;
        }
    }

    return 'devices'; // default icon
}

/**
 * Get formatted post views count
 */
function smartobzor_get_post_views($post_id, $generate_random = true) {
    $views = get_post_meta($post_id, 'post_views_count', true);

    if (empty($views) && $generate_random) {
        // Generate consistent random views based on post ID
        srand($post_id);
        $views = rand(500, 9999);
        srand(); // reset seed
    }

    if ($views > 1000) {
        return number_format($views / 1000, 1) . 'K';
    }

    return number_format($views);
}

/**
 * Generate random rating for posts
 */
function smartobzor_get_post_rating($post_id = null) {
    // You can store real ratings in post meta if needed
    $rating = get_post_meta($post_id, 'post_rating', true);

    if (empty($rating)) {
        // Generate consistent random rating based on post ID
        if ($post_id) {
            srand($post_id);
            $rating = rand(45, 49) / 10; // 4.5 to 4.9
            srand(); // reset seed
        } else {
            $rating = rand(45, 49) / 10;
        }
    }

    return number_format($rating, 1);
}

/**
 * Get reading time estimate
 */
function smartobzor_get_reading_time($post_id) {
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average 200 words per minute

    return max(1, $reading_time); // Minimum 1 minute
}

/**
 * Get trending posts based on views and comments
 */
function smartobzor_get_trending_posts($limit = 4, $category_id = null) {
    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_key' => '_thumbnail_id', // Only posts with featured images
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'date_query' => array(
            array(
                'after' => '1 month ago',
            ),
        ),
    );

    if ($category_id) {
        $args['cat'] = $category_id;
    }

    $posts = get_posts($args);

    // If not enough trending posts, get recent posts
    if (count($posts) < $limit) {
        $args['orderby'] = 'date';
        $args['posts_per_page'] = $limit - count($posts);
        unset($args['date_query']); // Remove date restriction for recent posts
        $recent_posts = get_posts($args);
        $posts = array_merge($posts, $recent_posts);
    }

    return array_slice($posts, 0, $limit); // Ensure we don't exceed limit
}

/**
 * Get hot stories based on engagement
 */
function smartobzor_get_hot_stories($limit = 6, $category_ids = array()) {
    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_key' => '_thumbnail_id',
        'orderby' => 'comment_count',
        'order' => 'DESC',
    );

    if (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    }

    $posts = get_posts($args);

    // If not enough posts, get recent posts
    if (count($posts) < $limit) {
        $args['orderby'] = 'date';
        $args['posts_per_page'] = $limit - count($posts);
        if (!empty($category_ids)) {
            $args['category__in'] = $category_ids;
        }
        $recent_posts = get_posts($args);
        $posts = array_merge($posts, $recent_posts);
    }

    return array_slice($posts, 0, $limit);
}

/**
 * Get slider posts
 */
function smartobzor_get_slider_posts($limit = 3, $category_ids = array()) {
    // First, try to get posts marked as featured/slider posts
    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_key' => '_thumbnail_id',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'featured_post',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key' => 'slider_post',
                'value' => '1',
                'compare' => '='
            )
        )
    );

    if (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    }

    $slider_posts = get_posts($args);

    // If no featured posts, get recent posts with images
    if (empty($slider_posts)) {
        $args = array(
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'meta_key' => '_thumbnail_id',
            'orderby' => 'date',
            'order' => 'DESC'
        );

        if (!empty($category_ids)) {
            $args['category__in'] = $category_ids;
        }

        $slider_posts = get_posts($args);
    }

    return $slider_posts;
}

/**
 * Add custom post views tracking
 */
function smartobzor_track_post_views($post_id) {
    if (!is_single()) return;
    if (empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }

    // Don't track admin or bot views
    if (is_admin() || current_user_can('manage_options')) {
        return;
    }

    $count = get_post_meta($post_id, 'post_views_count', true);
    if (empty($count)) {
        delete_post_meta($post_id, 'post_views_count');
        add_post_meta($post_id, 'post_views_count', '1');
    } else {
        $count++;
        update_post_meta($post_id, 'post_views_count', $count);
    }
}

// Hook into single post view to track views
add_action('wp_head', 'smartobzor_track_post_views');

/**
 * Add responsive image sizes
 */
function smartobzor_add_image_sizes() {
    add_image_size('smartobzor-slider', 1200, 600, true);
    add_image_size('smartobzor-featured', 800, 450, true);
    add_image_size('smartobzor-card', 400, 250, true);
    add_image_size('smartobzor-thumbnail', 150, 100, true);
    add_image_size('smartobzor-hero', 1600, 800, true);
}
add_action('after_setup_theme', 'smartobzor_add_image_sizes');

/**
 * Get latest stories for homepage
 */
function smartobzor_get_latest_stories($featured_count = 1, $side_count = 6, $category_ids = array()) {
    $args = array(
        'posts_per_page' => $featured_count + $side_count,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    if (!empty($category_ids)) {
        $args['category__in'] = $category_ids;
    }

    $posts = get_posts($args);

    return array(
        'featured' => array_slice($posts, 0, $featured_count),
        'sidebar' => array_slice($posts, $featured_count, $side_count)
    );
}

/**
 * Get breaking news posts
 */
function smartobzor_get_breaking_news($limit = 3) {
    $args = array(
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
        'date_query' => array(
            array(
                'after' => '24 hours ago',
            ),
        ),
    );

    $posts = get_posts($args);

    // If no recent posts, get latest posts
    if (empty($posts)) {
        $args = array(
            'posts_per_page' => $limit,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC'
        );
        $posts = get_posts($args);
    }

    return $posts;
}

/**
 * Custom search form
 */
function smartobzor_search_form($form) {
    $form = '<form role="search" method="get" class="search-form flex" action="' . home_url('/') . '">
        <input type="search" class="search-field flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:border-blue-500" placeholder="' . esc_attr__('Search...', 'smartobzor') . '" value="' . get_search_query() . '" name="s" />
        <button type="submit" class="search-submit bg-blue-600 text-white px-4 py-2 rounded-r-lg hover:bg-blue-700 transition-colors">
            <span class="material-icons">search</span>
        </button>
    </form>';

    return $form;
}
add_filter('get_search_form', 'smartobzor_search_form');

/**
 * Add post meta boxes for SmartObzor features
 */
function smartobzor_add_meta_boxes() {
    add_meta_box(
        'smartobzor_post_options',
        __('SmartObzor Post Options', 'smartobzor'),
        'smartobzor_post_options_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'smartobzor_add_meta_boxes');

/**
 * Meta box callback for post options
 */
function smartobzor_post_options_callback($post) {
    wp_nonce_field('smartobzor_post_options', 'smartobzor_post_options_nonce');

    $featured_post = get_post_meta($post->ID, 'featured_post', true);
    $slider_post = get_post_meta($post->ID, 'slider_post', true);
    $post_rating = get_post_meta($post->ID, 'post_rating', true);

    ?>
    <p>
        <label>
            <input type="checkbox" name="featured_post" value="1" <?php checked($featured_post, '1'); ?> />
            <?php _e('Featured Post', 'smartobzor'); ?>
        </label>
    </p>
    <p>
        <label>
            <input type="checkbox" name="slider_post" value="1" <?php checked($slider_post, '1'); ?> />
            <?php _e('Include in Slider', 'smartobzor'); ?>
        </label>
    </p>
    <p>
        <label for="post_rating"><?php _e('Post Rating (1-5):', 'smartobzor'); ?></label>
        <input type="number" id="post_rating" name="post_rating" value="<?php echo esc_attr($post_rating); ?>" min="1" max="5" step="0.1" style="width: 100%;" />
    </p>
    <?php
}

/**
 * Save post meta data
 */
function smartobzor_save_post_meta($post_id) {
    if (!isset($_POST['smartobzor_post_options_nonce']) || !wp_verify_nonce($_POST['smartobzor_post_options_nonce'], 'smartobzor_post_options')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save featured post
    if (isset($_POST['featured_post'])) {
        update_post_meta($post_id, 'featured_post', '1');
    } else {
        delete_post_meta($post_id, 'featured_post');
    }

    // Save slider post
    if (isset($_POST['slider_post'])) {
        update_post_meta($post_id, 'slider_post', '1');
    } else {
        delete_post_meta($post_id, 'slider_post');
    }

    // Save post rating
    if (isset($_POST['post_rating']) && is_numeric($_POST['post_rating'])) {
        $rating = floatval($_POST['post_rating']);
        if ($rating >= 1 && $rating <= 5) {
            update_post_meta($post_id, 'post_rating', $rating);
        }
    }
}
add_action('save_post', 'smartobzor_save_post_meta');

/**
 * Add custom columns to posts admin
 */
function smartobzor_posts_columns($columns) {
    $columns['smartobzor_views'] = __('Views', 'smartobzor');
    $columns['smartobzor_rating'] = __('Rating', 'smartobzor');
    $columns['smartobzor_featured'] = __('Featured', 'smartobzor');
    return $columns;
}
add_filter('manage_posts_columns', 'smartobzor_posts_columns');

/**
 * Display custom column content
 */
function smartobzor_posts_custom_column($column, $post_id) {
    switch ($column) {
        case 'smartobzor_views':
            $views = get_post_meta($post_id, 'post_views_count', true);
            echo $views ? number_format($views) : '0';
            break;
        case 'smartobzor_rating':
            $rating = get_post_meta($post_id, 'post_rating', true);
            if ($rating) {
                echo '<span style="color: #fbbf24;">★</span> ' . $rating;
            } else {
                echo '—';
            }
            break;
        case 'smartobzor_featured':
            $featured = get_post_meta($post_id, 'featured_post', true);
            $slider = get_post_meta($post_id, 'slider_post', true);
            $badges = array();
            if ($featured) $badges[] = '<span style="background: #3b82f6; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">Featured</span>';
            if ($slider) $badges[] = '<span style="background: #10b981; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">Slider</span>';
            echo implode(' ', $badges);
            break;
    }
}
add_action('manage_posts_custom_column', 'smartobzor_posts_custom_column', 10, 2);

/**
 * Make custom columns sortable
 */
function smartobzor_sortable_columns($columns) {
    $columns['smartobzor_views'] = 'views';
    $columns['smartobzor_rating'] = 'rating';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'smartobzor_sortable_columns');

/**
 * Handle sorting by custom columns
 */
function smartobzor_posts_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('views' === $orderby) {
        $query->set('meta_key', 'post_views_count');
        $query->set('orderby', 'meta_value_num');
    } elseif ('rating' === $orderby) {
        $query->set('meta_key', 'post_rating');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'smartobzor_posts_orderby');

/**
 * Performance optimization - remove unnecessary WordPress features
 */
function smartobzor_remove_wp_features() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove unnecessary wp_head items
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
}
add_action('init', 'smartobzor_remove_wp_features');

/**
 * Schema markup for articles
 */
function smartobzor_add_schema_markup() {
    if (is_single()) {
        global $post;

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author()
            ),
            'publisher' => array(
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => array(
                    '@type' => 'ImageObject',
                    'url' => get_site_icon_url()
                )
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c')
        );

        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }

        echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
    }
}
add_action('wp_head', 'smartobzor_add_schema_markup');
?>