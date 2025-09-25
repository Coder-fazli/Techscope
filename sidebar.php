<!-- HOT STORIES -->
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-orange-500">🔥</span> <?php _e('HOT STORIES', 'techscope'); ?>
  </h3>
  <div class="space-y-4">
    <?php
    $hot_posts = new WP_Query(array(
      'posts_per_page' => 2,
      'meta_key' => '_techscope_post_views',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'post_status' => 'publish'
    ));

    if ($hot_posts->have_posts()) :
      while ($hot_posts->have_posts()) : $hot_posts->the_post();
        $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
        $rating = techscope_get_post_rating(get_the_ID());
    ?>
      <div class="bg-white rounded-lg shadow-md card-hover overflow-hidden">
        <img src="<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>"
             alt="<?php the_title_attribute(); ?>"
             class="w-full h-40 object-cover rounded-t-lg">
        <div class="p-3">
          <h4 class="font-semibold text-sm mb-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
              <?php echo techscope_truncate_text(get_the_title(), 50); ?>
            </a>
          </h4>
          <div class="flex items-center justify-between text-xs">
            <span class="text-gray-500"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
            <div class="flex items-center gap-2">
              <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
              <?php if ($rating > 0) : ?>
                <span class="text-yellow-500">⭐ <?php echo number_format($rating, 1); ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
    ?>
      <div class="text-center text-gray-500 py-4">
        <?php _e('No hot stories yet.', 'techscope'); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- COMMUNITY STATS -->
<div class="bg-gradient-to-br from-purple-50 to-blue-50 rounded-xl p-6 mb-6">
  <h3 class="font-bold text-purple-800 mb-4">💬 <?php _e('COMMUNITY', 'techscope'); ?></h3>
  <div class="grid grid-cols-2 gap-4">
    <div class="bg-purple-600 text-white rounded-lg p-4 text-center">
      <div class="text-2xl font-bold"><?php echo wp_count_posts()->publish; ?>K</div>
      <div class="text-xs"><?php _e('Posts', 'techscope'); ?></div>
    </div>
    <div class="bg-blue-600 text-white rounded-lg p-4 text-center">
      <div class="text-2xl font-bold"><?php echo count(get_users()); ?></div>
      <div class="text-xs"><?php _e('Authors', 'techscope'); ?></div>
    </div>
    <div class="bg-green-600 text-white rounded-lg p-4 text-center">
      <div class="text-2xl font-bold"><?php echo wp_count_comments()->approved; ?></div>
      <div class="text-xs"><?php _e('Comments', 'techscope'); ?></div>
    </div>
    <div class="bg-orange-600 text-white rounded-lg p-4 text-center">
      <div class="text-2xl font-bold"><?php echo count(get_categories()); ?></div>
      <div class="text-xs"><?php _e('Categories', 'techscope'); ?></div>
    </div>
  </div>
</div>

<!-- TOP REVIEWS -->
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-blue-500">📖</span> <?php _e('TOP REVIEWS', 'techscope'); ?>
  </h3>
  <div class="space-y-4">
    <?php
    $review_posts = new WP_Query(array(
      'posts_per_page' => 3,
      'meta_key' => '_techscope_rating',
      'meta_compare' => '>',
      'meta_value' => '0',
      'orderby' => 'meta_value_num',
      'order' => 'DESC',
      'post_status' => 'publish'
    ));

    if ($review_posts->have_posts()) :
      while ($review_posts->have_posts()) : $review_posts->the_post();
        $rating = techscope_get_post_rating(get_the_ID());
    ?>
      <div>
        <h4 class="font-semibold text-sm mb-1">
          <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
            <?php echo techscope_truncate_text(get_the_title(), 60); ?>
          </a>
        </h4>
        <div class="flex items-center gap-2 text-xs text-gray-500">
          <span><?php echo get_the_date('M j, Y'); ?></span>
          <?php if ($rating > 0) : ?>
            <span class="text-yellow-500">⭐ <?php echo number_format($rating, 1); ?></span>
          <?php endif; ?>
        </div>
      </div>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
    ?>
      <div class="text-center text-gray-500 py-4">
        <?php _e('No reviews yet.', 'techscope'); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- LATEST -->
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-green-500">📰</span> <?php _e('LATEST', 'techscope'); ?>
  </h3>
  <div class="space-y-3">
    <?php
    $latest_posts = new WP_Query(array(
      'posts_per_page' => 3,
      'orderby' => 'date',
      'order' => 'DESC',
      'post_status' => 'publish'
    ));

    if ($latest_posts->have_posts()) :
      while ($latest_posts->have_posts()) : $latest_posts->the_post();
    ?>
      <div>
        <h4 class="font-semibold text-sm mb-1">
          <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
            <?php echo techscope_truncate_text(get_the_title(), 60); ?>
          </a>
        </h4>
        <p class="text-xs text-gray-500">
          <?php echo get_the_date('M j, Y'); ?> • <?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?>
        </p>
      </div>
    <?php
      endwhile;
      wp_reset_postdata();
    else :
    ?>
      <div class="text-center text-gray-500 py-4">
        <?php _e('No posts yet.', 'techscope'); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- CATEGORIES -->
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-purple-500">📂</span> <?php _e('CATEGORIES', 'techscope'); ?>
  </h3>
  <div class="space-y-2">
    <?php
    $categories = get_categories(array(
      'orderby' => 'name',
      'order'   => 'ASC',
      'hide_empty' => true
    ));

    foreach ($categories as $category) :
    ?>
      <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
         class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
        <span class="text-sm text-gray-700"><?php echo esc_html($category->name); ?></span>
        <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full"><?php echo $category->count; ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<!-- SEARCH -->
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-blue-500">🔍</span> <?php _e('SEARCH', 'techscope'); ?>
  </h3>
  <form method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
    <input type="search"
           name="s"
           value="<?php echo get_search_query(); ?>"
           placeholder="<?php _e('Search posts...', 'techscope'); ?>"
           class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    <button type="submit"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
      <span class="material-icons">search</span>
    </button>
  </form>
</div>

<!-- TAGS -->
<?php
$tags = get_tags(array('hide_empty' => true, 'number' => 20));
if ($tags) :
?>
<div class="bg-white rounded-lg p-4 shadow-sm mb-6">
  <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
    <span class="text-orange-500">🏷️</span> <?php _e('TAGS', 'techscope'); ?>
  </h3>
  <div class="flex flex-wrap gap-2">
    <?php foreach ($tags as $tag) : ?>
      <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
         class="inline-block bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full hover:bg-blue-100 hover:text-blue-700 transition-colors">
        <?php echo esc_html($tag->name); ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<?php
// Display WordPress widgets if any are added
if (is_active_sidebar('sidebar-1')) :
?>
  <div class="space-y-6">
    <?php dynamic_sidebar('sidebar-1'); ?>
  </div>
<?php endif; ?>