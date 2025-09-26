<?php get_header(); ?>

<!-- LOADING SKELETONS (Initially visible) -->
<div id="loading-content" class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4 py-6">
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

<!-- MAIN LAYOUT (Initially hidden) -->
<div id="main-content" class="hidden max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4 py-4 sm:py-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

    <!-- MAIN CONTENT -->
    <div class="lg:col-span-2 space-y-6 lg:space-y-8">

      <!-- HERO SLIDER -->
      <div class="relative hero-slider section-animate">
        <div class="w-full h-[350px] sm:h-[450px] md:h-[500px] lg:h-[600px] rounded-lg lg:rounded-xl relative overflow-hidden">
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
                 style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'hero-slider'); ?>')">
              <div class="absolute inset-x-0 bottom-0 p-3 md:p-6">
                <div class="glass-effect-dark text-white rounded-lg p-2 md:p-4">
                  <div class="text-xs uppercase tracking-widest opacity-90 mb-1 tech-badge px-2 py-1 rounded">
                    🔥 <?php _e('TRENDING', 'techscope'); ?>
                  </div>
                  <h2 class="text-base md:text-2xl lg:text-3xl font-extrabold mb-1 md:mb-2">
                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-200 transition-colors">
                      <?php the_title(); ?>
                    </a>
                  </h2>
                  <div class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm opacity-90">
                    <span><?php echo get_the_date('M j, Y'); ?></span>
                    <span class="hidden md:inline">•</span>
                    <span class="hidden md:inline"><?php the_author(); ?></span>
                    <span class="hidden md:inline">•</span>
                    <span class="hidden sm:inline"><?php comments_number('0 comments', '1 comment', '% comments'); ?></span>
                    <?php if ($rating > 0) : ?>
                      <span class="hidden md:inline">•</span>
                      <?php echo techscope_display_rating(get_the_ID()); ?>
                    <?php endif; ?>
                  </div>
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
              <div class="absolute inset-x-0 bottom-0 p-3 md:p-6">
                <div class="glass-effect-dark text-white rounded-lg p-2 md:p-4">
                  <div class="text-xs uppercase tracking-widest opacity-90 mb-1 tech-badge px-2 py-1 rounded">🔥 TRENDING</div>
                  <h2 class="text-base md:text-2xl lg:text-3xl font-extrabold mb-1 md:mb-2">Welcome to <?php bloginfo('name'); ?></h2>
                  <div class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm opacity-90">
                    <span><?php echo date('M j, Y'); ?></span>
                    <span class="hidden md:inline">•</span>
                    <span class="hidden md:inline">Admin</span>
                  </div>
                </div>
              </div>
            </div>
          <?php endfor; endif; ?>

          <!-- Dots -->
          <div class="hero-dots">
            <?php for ($i = 0; $i < max($slide_count, 3); $i++) : ?>
              <div class="hero-dot <?php echo $i === 0 ? 'active' : ''; ?>" data-slide="<?php echo $i; ?>"></div>
            <?php endfor; ?>
          </div>
        </div>
      </div>

      <!-- TRENDING TECH -->
      <section class="section-animate stagger-1">
        <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider mb-3 md:mb-4 text-purple-800">
          🔥 <?php echo techscope_get_section_title('trending'); ?>
        </h3>
        <?php
        $trending_posts = techscope_get_featured_posts();
        $trending_count = get_option('techscope_trending_count', 4);
        $trending_grid_class = 'grid gap-3 md:gap-6 ';

        // Dynamic grid based on post count - Mobile-first approach
        if ($trending_count <= 2) {
          $trending_grid_class .= 'grid-cols-1 sm:grid-cols-2';
        } elseif ($trending_count == 3) {
          $trending_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3';
        } else {
          $trending_grid_class .= 'grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4';
        }
        ?>
        <div class="<?php echo $trending_grid_class; ?>">
          <?php if ($trending_posts->have_posts()) :
            while ($trending_posts->have_posts()) : $trending_posts->the_post();
              $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
              $rating = techscope_get_post_rating(get_the_ID());
          ?>
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm overflow-hidden card-hover">
              <div class="w-full h-32 md:h-32 tech-img"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>')">
              </div>
              <div class="p-3 md:p-3">
                <h4 class="font-semibold text-sm md:text-sm mb-2 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 40); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                  <?php if ($rating > 0) : ?>
                    <span class="text-yellow-500">⭐ <?php echo number_format($rating, 1); ?></span>
                  <?php endif; ?>
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

    <!-- EDITOR'S CHOICE - FULL WIDTH SECTION -->
    <div class="lg:col-span-3 section-animate stagger-2">
      <!-- Section Title - Outside Container -->
      <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider mb-6 text-purple-800">
        ⭐ <?php echo techscope_get_section_title('editor'); ?>
      </h3>

      <!-- Content Container - Clean Grid Only -->
      <section class="bg-gray-50 rounded-2xl sm:rounded-3xl shadow-lg shadow-gray-200/50 overflow-hidden p-2 sm:p-3">
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
            <div class="relative overflow-hidden rounded-3xl bg-white group flex-1">
              <div class="w-full h-full tech-img relative"
                   style="background-image: url('<?php echo techscope_get_responsive_image($post->ID, 'hero-slider'); ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;">
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
                      <span><?php the_author(); ?></span>
                      <span>•</span>
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
                <div class="bg-white rounded-2xl sm:rounded-3xl shadow-md shadow-gray-200/30 overflow-hidden card-hover border border-gray-100/50 transform transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                  <div class="w-full h-36 sm:h-44 overflow-hidden rounded-2xl sm:rounded-3xl relative">
                    <div class="w-full h-full tech-img transform transition-transform duration-300 hover:scale-105"
                         style="background-image: url('<?php echo techscope_get_responsive_image($post->ID, 'featured-card'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
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

    <!-- CONTINUE MAIN CONTENT COLUMN -->
    <div class="lg:col-span-2 space-y-6 lg:space-y-8">

      <!-- HOT STORIES -->
      <section class="section-animate stagger-3">
        <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider mb-3 md:mb-4 text-red-800">
          🔥 <?php echo techscope_get_section_title('hot'); ?>
        </h3>
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
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm overflow-hidden card-hover">
              <div class="w-full h-32 md:h-40 tech-img"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>')">
                <div class="absolute top-2 left-2">
                  <span class="hot-badge px-2 py-1 text-xs font-bold bg-red-500 text-white rounded">HOT</span>
                </div>
              </div>
              <div class="p-3 md:p-4">
                <h4 class="font-bold text-sm md:text-base mb-2 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 50); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                  <?php if ($rating > 0) : ?>
                    <span class="text-yellow-500">⭐ <?php echo number_format($rating, 1); ?></span>
                  <?php endif; ?>
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

      <!-- MOBILE TECH -->
      <section class="section-animate stagger-4">
        <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider mb-3 md:mb-4 text-blue-800">
          📱 <?php echo techscope_get_section_title('mobile'); ?>
        </h3>
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
            <div class="bg-white rounded-lg lg:rounded-xl shadow-sm overflow-hidden card-hover">
              <div class="w-full h-40 md:h-48 tech-img"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>')">
              </div>
              <div class="p-3 md:p-4">
                <h4 class="font-bold text-sm md:text-base mb-2 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 50); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-xs">
                  <span class="text-orange-500">🔥 <?php echo $view_count; ?></span>
                  <?php if ($rating > 0) : ?>
                    <span class="text-yellow-500">⭐ <?php echo number_format($rating, 1); ?></span>
                  <?php endif; ?>
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

      <!-- AI & GAMING -->
      <section class="section-animate stagger-5">
        <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider mb-3 md:mb-4 text-green-800">
          🤖 <?php echo techscope_get_section_title('ai'); ?>
        </h3>
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
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover">
              <div class="w-full h-48 tech-img"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>')">
              </div>
              <div class="p-4">
                <h4 class="font-bold text-base mb-2">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 60); ?>
                  </a>
                </h4>
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-500"><?php echo get_the_date('M j, Y'); ?></span>
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
          endif;
          ?>
        </div>
      </section>

    </div>

    <!-- RIGHT SIDEBAR -->
    <div class="lg:col-span-1 space-y-4 lg:space-y-6 section-animate stagger-2">
      <?php get_sidebar(); ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>