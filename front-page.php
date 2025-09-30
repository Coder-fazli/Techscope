<?php get_header(); ?>

<!-- LOADING SKELETONS (Hidden, no animations) -->
<div id="loading-content" class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4 py-6" style="display: none;">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content Skeleton -->
    <div class="lg:col-span-2 space-y-8">
      <div class="w-full h-[500px] md:h-[600px] skeleton rounded-xl"></div>

      <div class="space-y-4">
        <div class="h-6 w-48 skeleton rounded"></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="bg-white rounded-xl p-4 space-y-3">
            <div class="h-32 skeleton rounded-lg"></div>
            <div class="h-4 skeleton rounded w-3/4"></div>
            <div class="h-3 skeleton rounded w-1/2"></div>
          </div>
          <div class="bg-white rounded-xl p-4 space-y-3">
            <div class="h-32 skeleton rounded-lg"></div>
            <div class="h-4 skeleton rounded w-3/4"></div>
            <div class="h-3 skeleton rounded w-1/2"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar Skeleton -->
    <div class="space-y-6">
      <div class="bg-white rounded-lg p-4 space-y-4">
        <div class="h-5 skeleton rounded w-32"></div>
        <div class="space-y-3">
          <div class="h-20 skeleton rounded-lg"></div>
          <div class="h-20 skeleton rounded-lg"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MAIN LAYOUT (Visible, main content displayed) -->
<div id="main-content" class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4 pt-4 sm:pt-6 pb-2">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

    <!-- HERO SLIDER -->
    <?php if (techscope_should_show_section('hero')) : ?>
    <div class="lg:col-span-2 relative hero-slider section-animate mb-2">
      <!-- Hero Slider - Modern Card Style -->
      <div class="rounded-xl overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-300 bg-white border border-gray-100">
        <div class="w-full h-[350px] sm:h-[450px] md:h-[500px] lg:h-[600px] rounded-xl overflow-hidden relative">
          <?php
          $hero_posts = techscope_get_hero_posts();
          $slide_count = 0;

          if ($hero_posts->have_posts()) :
            while ($hero_posts->have_posts()) : $hero_posts->the_post();
              $slide_count++;
              $active_class = $slide_count === 1 ? 'active' : '';
              $rating = techscope_get_post_rating(get_the_ID());
              $view_count = techscope_get_post_views(get_the_ID());
          ?>
            <!-- Slide <?php echo $slide_count; ?> -->
            <div class="hero-slide <?php echo $active_class; ?> tech-img"
                 style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'hero-slider'); ?>')">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
              <div class="absolute inset-x-0 bottom-8 md:bottom-12 p-4 md:p-6">
                <?php
                $categories = get_the_category();
                if (!empty($categories)) :
                  $category = $categories[0];
                ?>
                <div class="mb-3">
                  <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    <?php echo esc_html($category->name); ?>
                  </span>
                </div>
                <?php endif; ?>

                <h2 class="text-lg md:text-xl lg:text-2xl font-bold mb-3 text-white leading-tight">
                  <a href="<?php the_permalink(); ?>" class="text-white hover:text-gray-200 transition-colors">
                    <?php the_title(); ?>
                  </a>
                </h2>

                <div class="flex flex-wrap items-center gap-2 md:gap-4 text-sm text-gray-300">
                  <span class="font-medium"><?php echo get_the_author(); ?></span>
                  <span>•</span>
                  <span><?php echo get_the_date('F j, Y'); ?></span>
                  <span>•</span>
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo $view_count; ?>
                  </span>
                </div>
              </div>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
            // Fallback slides if no hero posts
            for ($i = 1; $i <= 3; $i++) :
              $active_class = $i === 1 ? 'active' : '';
          ?>
            <div class="hero-slide <?php echo $active_class; ?> tech-img"
                 style="background-image: url('https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80')">
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
              <div class="absolute inset-x-0 bottom-8 md:bottom-12 p-4 md:p-6">
                <div class="mb-3">
                  <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm font-medium">
                    Technology
                  </span>
                </div>

                <h2 class="text-lg md:text-xl lg:text-2xl font-bold mb-3 text-white leading-tight">
                  <a href="#" class="text-white hover:text-gray-200 transition-colors">
                    Welcome to <?php bloginfo('name'); ?>
                  </a>
                </h2>

                <div class="flex flex-wrap items-center gap-2 md:gap-4 text-sm text-gray-300">
                  <span class="font-medium">Admin</span>
                  <span>•</span>
                  <span><?php echo date('F j, Y'); ?></span>
                  <span>•</span>
                  <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                      <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    0
                  </span>
                </div>
              </div>
            </div>
          <?php endfor; endif; ?>

        </div>
      </div>

      <!-- Modern Navigation Buttons - Left -->
      <button class="hero-nav-btn hero-prev group" aria-label="Previous slide" style="position: absolute !important; left: 20px !important; top: 33% !important; transform: translateY(-50%) !important; width: 56px !important; height: 56px !important; border-radius: 12px !important; background: rgba(255, 255, 255, 0.75) !important; color: #f97316 !important; border: none !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; backdrop-filter: blur(8px) !important; transition: all 0.3s ease !important; z-index: 999 !important;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px !important; height: 24px !important; stroke-width: 2.5 !important;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>

      <!-- Modern Navigation Buttons - Right -->
      <button class="hero-nav-btn hero-next group" aria-label="Next slide" style="position: absolute !important; right: 20px !important; top: 33% !important; transform: translateY(-50%) !important; width: 56px !important; height: 56px !important; border-radius: 12px !important; background: rgba(255, 255, 255, 0.75) !important; color: #f97316 !important; border: none !important; display: flex !important; align-items: center !important; justify-content: center !important; cursor: pointer !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important; backdrop-filter: blur(8px) !important; transition: all 0.3s ease !important; z-index: 999 !important;">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 24px !important; height: 24px !important; stroke-width: 2.5 !important;">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
        </svg>
      </button>

      <style>
        .hero-nav-btn:hover {
          background: linear-gradient(135deg, #f97316 0%, #ef4444 100%) !important;
          color: white !important;
          transform: scale(1.1) !important;
          box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.4), 0 4px 6px -2px rgba(249, 115, 22, 0.2) !important;
        }

        .hero-nav-btn:active {
          transform: scale(0.95) !important;
        }
      </style>
    </div>
    <?php endif; ?>

    <!-- TRENDING WIDGET - Right side of hero -->
    <?php if (techscope_should_show_section('trending')) : ?>
    <div class="lg:col-span-1 section-animate stagger-2">
      <!-- Trending Widget - Katen Style: Remove gradient background -->
      <div class="katen-section-container h-full flex flex-col">
        <div class="space-y-2 flex-grow">
          <?php
          // Get admin settings with proper defaults
          $hero_trending_count = intval(get_option('techscope_hero_trending_count', 4));
          if ($hero_trending_count <= 0) {
            $hero_trending_count = 4;
          }
          $hero_trending_categories = (array) get_option('techscope_hero_trending_categories', []);

          $args = array(
            'posts_per_page' => $hero_trending_count,
            'meta_key' => '_techscope_post_views',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'post_status' => 'publish'
          );

          if (!empty($hero_trending_categories)) {
            $args['category__in'] = $hero_trending_categories;
          }

          $hero_trending_posts = new WP_Query($args);

          if ($hero_trending_posts->have_posts()) :
            $post_counter = 0;
            while ($hero_trending_posts->have_posts()) : $hero_trending_posts->the_post();
              $post_counter++;
              $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
              $categories = get_the_category();
              $category_name = !empty($categories) ? esc_html($categories[0]->name) : 'TRENDING';
          ?>
            <!-- Modern Sidebar Card -->
            <div class="group overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-white border border-gray-100">
              <a href="<?php the_permalink(); ?>" class="block relative">
                <!-- Image Container -->
                <div class="relative h-[180px] overflow-hidden">
                  <div class="w-full h-full tech-img transform group-hover:scale-105 transition-transform duration-500"
                       style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'featured-card'); ?>'); background-size: cover; background-position: center;">
                  </div>
                  <!-- Gradient Overlay -->
                  <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                  <!-- Number Badge -->
                  <div class="absolute top-3 left-3">
                    <span class="w-8 h-8 flex items-center justify-center bg-orange-500 text-white font-bold text-sm rounded-full shadow-lg">
                      <?php echo $post_counter; ?>
                    </span>
                  </div>

                  <!-- Category Badge -->
                  <div class="absolute top-3 right-3">
                    <span class="px-2 py-1 bg-white/90 backdrop-blur-sm text-orange-600 text-xs font-semibold rounded-full shadow-sm">
                      <?php echo $category_name; ?>
                    </span>
                  </div>
                </div>

                <!-- Content Container -->
                <div class="p-4">
                  <h4 class="font-bold text-sm mb-3 leading-tight text-gray-900 group-hover:text-orange-600 transition-colors line-clamp-2">
                    <?php echo techscope_truncate_text(get_the_title(), 60); ?>
                  </h4>

                  <!-- Meta Info -->
                  <div class="flex items-center justify-between text-xs text-gray-500">
                    <div class="flex items-center gap-1">
                      <span class="material-icons text-sm">schedule</span>
                      <span><?php echo get_the_date('M j'); ?></span>
                    </div>
                    <div class="flex items-center gap-1 text-orange-500">
                      <span class="material-icons text-sm">visibility</span>
                      <span class="font-medium"><?php echo $view_count; ?></span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
          ?>
            <div class="text-center text-gray-500 py-12 px-6 bg-white rounded-xl border-2 border-dashed border-gray-200">
              <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center">
                <span class="material-icons text-orange-500 text-3xl">trending_up</span>
              </div>
              <p class="text-sm font-medium text-gray-700 mb-2"><?php _e('Пока нет трендовых постов', 'techscope'); ?></p>
              <p class="text-xs text-gray-500"><?php _e('Скоро здесь появятся популярные статьи', 'techscope'); ?></p>
            </div>
          <?php endif; ?>

        </div>

        <!-- See More Button - Anchored to Bottom -->
        <?php if ($hero_trending_posts->have_posts()) :
          // Determine the link URL based on selected categories
          $see_more_url = get_permalink(get_option('page_for_posts')); // Default to blog page
          $see_more_text = 'See More Trending';

          if (!empty($hero_trending_categories) && count($hero_trending_categories) == 1) {
            // If only one category is selected, link to that category
            $category = get_category($hero_trending_categories[0]);
            if ($category) {
              $see_more_url = get_category_link($category->term_id);
              $see_more_text = 'See More ' . $category->name;
            }
          }
        ?>
          <div class="text-center mt-4 pt-4">
            <a href="<?php echo esc_url($see_more_url); ?>"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:from-orange-600 hover:to-red-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:scale-105 group">
              <span><?php echo esc_html($see_more_text); ?></span>
              <span class="material-icons text-base group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- MAIN CONTENT SECTIONS -->
    <div class="lg:col-span-3 space-y-4 lg:space-y-6">

      <?php if (techscope_should_show_section('trending_tech')) : ?>
      <!-- ========== TRENDING TECH SECTION - KATEN STYLE DIVIDER ========== -->
      <div class="mb-8 mt-12 section-divider-katen">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span class="text-purple-500">📱</span>
          <?php echo techscope_get_section_title('trending'); ?>
        </h3>
      </div>
      <!-- ========== END TRENDING TECH DIVIDER ========== -->

      <!-- ========== EXACT EPCL CAROUSEL - ORIGINAL TEMPLATE STRUCTURE ========== -->
      <section class="epcl-carousel slick-slider section outer-arrows slides-5" data-show="5" data-rtl="" data-aos="fade">
        <?php
        $trending_posts = techscope_get_featured_posts();
        if ($trending_posts->have_posts()) :
          while ($trending_posts->have_posts()) : $trending_posts->the_post();
        ?>
          <div class="item">
            <article>
              <div class="img cover" role="img" alt="<?php echo esc_attr(get_the_title()); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>" style="background: url('<?php echo techscope_ensure_image(get_the_ID(), 'featured-card'); ?>');">
                <div class="info border-effect">
                  <time datetime="<?php echo get_the_date('Y-m-d'); ?>">
                    <?php echo get_the_date('F j, Y'); ?>
                  </time>
                  <h2 class="title white"><?php the_title(); ?></h2>
                </div>
                <div class="clear"></div>
                <footer class="views-meta">
                  <div class="views-count">
                    <?php
                    $views = get_post_meta(get_the_ID(), 'post_views_count', true);
                    if (!$views) $views = '0';
                    echo number_format($views) . ' views';
                    ?>
                  </div>
                  <div class="clear"></div>
                </footer>
                <a href="<?php the_permalink(); ?>" class="full-link" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                  <span style="display:none;"><?php the_title(); ?></span>
                </a>
                <div class="overlay"></div>
              </div>
            </article>
          </div>
        <?php
          endwhile;
          wp_reset_postdata();
        endif;
        ?>
      </section>
      <?php endif; ?>



    </div>

    <?php if (techscope_should_show_section('editor')) : ?>
    <!-- ========== EDITOR'S CHOICE SECTION - KATEN STYLE CONTAINER ========== -->
    <div class="lg:col-span-3 katen-section-container">
      <!-- Section Header -->
      <div class="katen-section-header">
        <h3 class="flex items-center gap-2">
          <span class="text-amber-500">⭐</span>
          <?php echo techscope_get_section_title('editor'); ?>
        </h3>
      </div>

      <!-- EDITOR'S CHOICE Content -->
      <section class="section-animate stagger-2">
        <?php
        $editor_posts = techscope_get_editor_posts();
        $editor_secondary_count = get_option('techscope_editor_secondary_count', 4); // Increased default to 4

        if ($editor_posts->have_posts() && count($editor_posts->posts) > 0) :
          $all_posts = $editor_posts->posts;
          $featured_post = $all_posts[0]; // First post as featured
          $secondary_posts = array_slice($all_posts, 1, $editor_secondary_count); // Remaining posts
        ?>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-2 sm:gap-3 min-h-[250px] lg:h-96 w-full overflow-hidden">
          <!-- LEFT: Large Featured Post (3/5 width) - Full Height -->
          <div class="lg:col-span-3 flex">
            <?php
            $temp_post = $post;
            $post = $featured_post;
            setup_postdata($post);
            $view_count = techscope_format_view_count(techscope_get_post_views($post->ID));
            $rating = techscope_get_post_rating($post->ID);
            ?>
            <!-- Editor's Choice Large Post - Katen Style: Remove white background -->
            <div class="relative overflow-hidden rounded-3xl group flex-1">
              <div class="w-full h-full tech-img relative"
                   style="background-image: url('<?php echo techscope_ensure_image($post->ID, 'hero-slider'); ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6">
                  <div class="text-white">
                    <div class="flex items-center gap-2 mb-3">
                      <span class="bg-purple-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                        ⭐ Editor's Pick
                      </span>
                      <?php if ($rating > 0) : ?>
                        <span class="text-yellow-400 text-sm">⭐ <?php echo number_format($rating, 1); ?></span>
                      <?php endif; ?>
                    </div>
                    <h2 class="text-xl md:text-2xl lg:text-2xl font-bold mb-3 leading-tight group-hover:text-blue-200 transition-colors">
                      <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-200">
                        <?php the_title(); ?>
                      </a>
                    </h2>
                    <p class="text-gray-200 mb-4 text-sm md:text-base">
                      <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                    </p>
                    <div class="flex flex-wrap items-center gap-4 text-xs md:text-sm text-gray-300">
                      <span><?php echo get_the_date('M j, Y'); ?></span>
                      <span>•</span>
                      <span class="text-orange-400">🔥 <?php echo $view_count; ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php
            $post = $temp_post;
            wp_reset_postdata();
            ?>
          </div>

          <!-- RIGHT: Small Posts 2x2 Grid (2/5 width) - Equal Heights -->
          <div class="lg:col-span-2 flex">
            <div class="grid grid-cols-2 gap-2 sm:gap-3 w-full content-start overflow-hidden">
              <?php
              foreach ($secondary_posts as $secondary_post) :
                $temp_post = $post;
                $post = $secondary_post;
                setup_postdata($post);
                $view_count = techscope_format_view_count(techscope_get_post_views($post->ID));
                $rating = techscope_get_post_rating($post->ID);
              ?>
                <!-- Editor's Choice Small Card - Katen Style: Remove white background box -->
                <div class="overflow-hidden card-hover-katen transform transition-all duration-300 hover:-translate-y-0.5">
                  <!-- Editor's Choice Small Card Content - Katen Style: Image as main element -->
                  <div class="w-full h-36 sm:h-44 overflow-hidden relative">
                    <div class="w-full h-full tech-img transform transition-transform duration-300 hover:scale-105"
                         style="background-image: url('<?php echo techscope_ensure_image($post->ID, 'featured-card'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
                      <!-- Overlay gradient -->
                      <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                      <!-- Title overlay -->
                      <div class="absolute bottom-0 left-0 right-0 p-3">
                        <h4 class="font-semibold text-white text-sm leading-tight mb-1 line-clamp-2">
                          <a href="<?php the_permalink(); ?>" class="text-white hover:text-gray-200 transition-colors">
                            <?php the_title(); ?>
                          </a>
                        </h4>
                        <div class="flex items-center justify-between text-xs text-gray-300">
                          <span><?php echo get_the_date('M j'); ?></span>
                          <div class="flex items-center gap-1">
                            <?php if ($rating > 0) : ?>
                              <span class="text-yellow-400">⭐ <?php echo number_format($rating, 1); ?></span>
                            <?php else : ?>
                              <span class="text-orange-400">🔥 <?php echo $view_count; ?></span>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
                $post = $temp_post;
              endforeach;
              wp_reset_postdata();
              ?>
            </div>
          </div>
        </div>

        <?php endif; ?>
      </section>
    </div>
    <?php endif; ?>

    <!-- CONTINUE MAIN CONTENT COLUMN -->
    <div class="lg:col-span-2 space-y-6 lg:space-y-8">

      <?php if (techscope_should_show_section('hot')) : ?>
      <!-- ========== HOT STORIES SECTION - KATEN STYLE CONTAINER ========== -->
      <div class="katen-section-container">
        <!-- Section Header -->
        <div class="katen-section-header">
          <h3 class="flex items-center gap-2">
            <span class="text-red-500">🔥</span>
            <?php echo techscope_get_section_title('hot'); ?>
          </h3>
        </div>

        <!-- HOT STORIES Content -->
        <section class="section-animate stagger-3">
        <?php
        $hot_posts = techscope_get_hot_stories_posts();
        $hot_count = get_option('techscope_hot_count', 4);
        $hot_grid_class = 'grid gap-3 md:gap-6 ';

        // Dynamic grid based on post count - Mobile-first approach
        if ($hot_count <= 2) {
          $hot_grid_class .= 'grid-cols-1 sm:grid-cols-2';
        } elseif ($hot_count == 3) {
          $hot_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        } else {
          $hot_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4';
        }
        ?>
        <div class="<?php echo $hot_grid_class; ?>">
          <?php if ($hot_posts->have_posts()) :
            while ($hot_posts->have_posts()) : $hot_posts->the_post();
              $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
              $rating = techscope_get_post_rating(get_the_ID());
          ?>
            <!-- Hot Stories Card - Katen Style: Remove white background box -->
            <div class="overflow-hidden card-hover-katen">
              <a href="<?php the_permalink(); ?>" class="block w-full h-32 md:h-40 tech-img relative"
                 style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'featured-card'); ?>')">
                <div class="absolute top-2 left-2">
                  <span class="hot-badge px-2 py-1 text-xs font-bold bg-red-500 text-white rounded">HOT</span>
                </div>
              </a>
              <div class="p-3 md:p-4 bg-white">
                <h4 class="font-bold text-sm md:text-base mb-2 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 50); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500"><?php echo get_the_date('M j'); ?></span>
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                </div>
              </div>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
        </section>
      </div>
      <?php endif; ?>

      <?php if (techscope_should_show_section('mobile')) : ?>
      <!-- ========== MOBILE TECH SECTION - KATEN STYLE CONTAINER ========== -->
      <div class="katen-section-container">
        <!-- Section Header -->
        <div class="katen-section-header">
          <h3 class="flex items-center gap-2">
            <span class="text-blue-500">📱</span>
            <?php echo techscope_get_section_title('mobile'); ?>
          </h3>
        </div>

        <!-- MOBILE TECH Content -->
        <section class="section-animate stagger-4">
        <?php
        $mobile_posts = techscope_get_mobile_posts();
        $mobile_count = get_option('techscope_mobile_count', 3);
        $mobile_grid_class = 'grid gap-3 md:gap-6 ';

        // Dynamic grid based on post count - Mobile-first approach
        if ($mobile_count <= 2) {
          $mobile_grid_class .= 'grid-cols-1 sm:grid-cols-2';
        } elseif ($mobile_count == 3) {
          $mobile_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        } else {
          $mobile_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3';
        }
        ?>
        <div class="<?php echo $mobile_grid_class; ?>">
          <?php if ($mobile_posts->have_posts()) :
            while ($mobile_posts->have_posts()) : $mobile_posts->the_post();
              $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
              $rating = techscope_get_post_rating(get_the_ID());
          ?>
            <!-- Mobile Tech Card - Katen Style: Remove white background box -->
            <div class="overflow-hidden card-hover-katen">
              <a href="<?php the_permalink(); ?>" class="block w-full h-40 md:h-48 tech-img"
                 style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'featured-card'); ?>')">
              </a>
              <div class="p-3 md:p-4 bg-white">
                <h4 class="font-bold text-sm md:text-base mb-2 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 50); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-gray-500"><?php echo get_the_date('M j'); ?></span>
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                </div>
              </div>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
        </section>
      </div>
      <?php endif; ?>

      <?php if (techscope_should_show_section('ai')) : ?>
      <!-- ========== AI & GAMING SECTION - KATEN STYLE CONTAINER ========== -->
      <div class="katen-section-container">
        <!-- Section Header -->
        <div class="katen-section-header">
          <h3 class="flex items-center gap-2">
            <span class="text-green-500">🤖</span>
            <?php echo techscope_get_section_title('ai'); ?>
          </h3>
        </div>

        <!-- AI & GAMING Content -->
        <section class="section-animate stagger-5">
        <?php
        $ai_gaming_posts = techscope_get_ai_gaming_posts();
        $ai_count = get_option('techscope_ai_count', 3);
        $ai_grid_class = 'grid gap-3 md:gap-6 ';

        // Dynamic grid based on post count - Mobile-first approach
        if ($ai_count <= 2) {
          $ai_grid_class .= 'grid-cols-1 sm:grid-cols-2';
        } elseif ($ai_count == 3) {
          $ai_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        } else {
          $ai_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3';
        }
        ?>
        <div class="<?php echo $ai_grid_class; ?>">
          <?php if ($ai_gaming_posts->have_posts()) :
            while ($ai_gaming_posts->have_posts()) : $ai_gaming_posts->the_post();
              $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
              $rating = techscope_get_post_rating(get_the_ID());
          ?>
            <!-- AI & Gaming Card - Katen Style: Remove white background box -->
            <div class="overflow-hidden card-hover-katen">
              <a href="<?php the_permalink(); ?>" class="block w-full h-48 tech-img"
                 style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'featured-card'); ?>')">
              </a>
              <div class="p-3 md:p-4 bg-white">
                <h4 class="font-bold text-sm md:text-base mb-2">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 60); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-500"><?php echo get_the_date('M j, Y'); ?></span>
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                </div>
              </div>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          endif;
          ?>
        </div>
        </section>
      </div>
      <?php endif; ?>

    </div>
  </div>
</div>


<?php get_footer(); ?>