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

    <!-- HERO SLIDER -->
    <?php if (get_option('techscope_show_hero', 1)) : ?>
    <div class="lg:col-span-2 relative hero-slider section-animate">
      <div class="bg-white rounded-xl overflow-hidden card-hover p-3">
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
                 style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'hero-slider'); ?>')">
              <div class="absolute inset-x-0 bottom-0 p-3 md:p-6">
                <div class="glass-effect-dark text-white rounded-lg p-2 md:p-4">
                  <h2 class="text-base md:text-2xl lg:text-3xl font-extrabold mb-1 md:mb-2">
                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-blue-200 transition-colors">
                      <?php the_title(); ?>
                    </a>
                  </h2>
                  <div class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm opacity-90">
                    <span><?php echo get_the_date('M j, Y'); ?></span>
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
                  <h2 class="text-base md:text-2xl lg:text-3xl font-extrabold mb-1 md:mb-2">Welcome to <?php bloginfo('name'); ?></h2>
                  <div class="flex flex-wrap items-center gap-2 md:gap-4 text-xs md:text-sm opacity-90">
                    <span><?php echo date('M j, Y'); ?></span>
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
    </div>
    <?php endif; ?>

    <!-- TRENDING WIDGET - Right side of hero -->
    <?php if (get_option('techscope_show_trending', 1)) : ?>
    <div class="lg:col-span-1 section-animate stagger-2">
      <div class="bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-4 border border-pink-100 h-full flex flex-col">
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
            <!-- Clean Sidebar Post Card -->
            <div class="bg-white rounded-xl overflow-hidden transition-all duration-300 hover:transform hover:translate-y-1 sidebar-card">
              <div class="w-full h-[140px] relative overflow-hidden tech-img sidebar-image"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>'); background-size: cover; background-position: center;">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-3">
                  <h4 class="font-bold text-sm mb-2 leading-tight text-white">
                    <a href="<?php the_permalink(); ?>" class="text-white hover:text-gray-200 transition-colors">
                      <?php echo techscope_truncate_text(get_the_title(), 45); ?>
                    </a>
                  </h4>
                  <div class="flex items-center justify-between text-xs text-gray-300">
                    <span><?php echo get_the_date('M j'); ?></span>
                    <div class="flex items-center gap-1">
                      <span class="material-icons text-xs">visibility</span>
                      <span><?php echo $view_count; ?></span>
                    </div>
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
              <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-pink-100 flex items-center justify-center">
                <span class="material-icons text-pink-400 text-sm">trending_up</span>
              </div>
              <p class="text-xs"><?php _e('No trending posts yet.', 'techscope'); ?></p>
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
          <div class="text-center mt-3 pt-3 border-t border-pink-100">
            <a href="<?php echo esc_url($see_more_url); ?>"
               class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:from-pink-600 hover:to-rose-600 transition-all duration-300 shadow-sm hover:shadow-md">
              <span><?php echo esc_html($see_more_text); ?></span>
              <span class="material-icons text-sm">arrow_forward</span>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- MAIN CONTENT SECTIONS -->
    <div class="lg:col-span-3 space-y-6 lg:space-y-8">

      <!-- ========== TRENDING TECH SECTION - EDIT DIVIDER HERE ========== -->
      <div class="mb-8 mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span class="text-purple-500">📱</span>
          <?php echo techscope_get_section_title('trending'); ?>
        </h3>
        <div class="w-full h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
      </div>
      <!-- ========== END TRENDING TECH DIVIDER ========== -->

      <!-- ========== TEMPLATE EXACT CAROUSEL - EDIT HERE ========== -->
      <section class="template-carousel-wrapper">
        <div class="carousel-container">
          <div class="carousel-track-wrapper">
            <div class="carousel-slides-track">
              <?php
              $trending_posts = techscope_get_featured_posts();
              if ($trending_posts->have_posts()) :
                $posts_array = [];
                while ($trending_posts->have_posts()) : $trending_posts->the_post();
                  $posts_array[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'permalink' => get_the_permalink(),
                    'author' => get_the_author(),
                    'date' => get_the_date('M j, Y'),
                    'image' => techscope_get_responsive_image(get_the_ID(), 'featured-card'),
                    'excerpt' => get_the_excerpt(),
                    'category' => get_the_category_list(', ')
                  ];
                endwhile;
                wp_reset_postdata();

                // Group posts for each slide (1 large + 3 small per slide)
                $slide_chunks = array_chunk($posts_array, 4);
                foreach ($slide_chunks as $slide_index => $slide_posts) :
                  $featured_post = $slide_posts[0];
                  $side_posts = array_slice($slide_posts, 1, 3);
              ?>
                <!-- ========== CAROUSEL SLIDE ========== -->
                <div class="carousel-slide" data-slide="<?php echo $slide_index; ?>">
                  <div class="slide-layout">
                    <!-- Large Featured Post -->
                    <div class="featured-post-card">
                      <div class="post-image-wrapper">
                        <img src="<?php echo $featured_post['image']; ?>"
                             alt="<?php echo esc_attr($featured_post['title']); ?>"
                             class="post-image">
                      </div>
                      <div class="post-content">
                        <div class="post-meta">
                          <span class="post-date"><?php echo $featured_post['date']; ?></span>
                        </div>
                        <h2 class="post-title">
                          <a href="<?php echo $featured_post['permalink']; ?>">
                            <?php echo $featured_post['title']; ?>
                          </a>
                        </h2>
                        <div class="post-excerpt">
                          <?php echo techscope_truncate_text($featured_post['excerpt'], 120); ?>
                        </div>
                        <div class="post-author">
                          <span class="author-name"><?php echo $featured_post['author']; ?></span>
                        </div>
                      </div>
                    </div>

                    <!-- Small Side Posts -->
                    <div class="side-posts-grid">
                      <?php foreach ($side_posts as $side_post) : ?>
                        <div class="side-post-card">
                          <div class="side-post-image">
                            <img src="<?php echo $side_post['image']; ?>"
                                 alt="<?php echo esc_attr($side_post['title']); ?>">
                          </div>
                          <div class="side-post-content">
                            <div class="side-post-meta">
                              <span class="side-post-date"><?php echo $side_post['date']; ?></span>
                            </div>
                            <h3 class="side-post-title">
                              <a href="<?php echo $side_post['permalink']; ?>">
                                <?php echo techscope_truncate_text($side_post['title'], 50); ?>
                              </a>
                            </h3>
                            <div class="side-post-author">
                              <?php echo $side_post['author']; ?>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; endif; ?>
            </div>
          </div>

          <!-- Navigation Dots -->
          <div class="carousel-pagination">
            <?php for ($i = 0; $i < count($slide_chunks); $i++) : ?>
              <button class="carousel-dot-btn <?php echo $i === 0 ? 'active' : ''; ?>"
                      data-slide="<?php echo $i; ?>"></button>
            <?php endfor; ?>
          </div>
        </div>
      </section>
      <!-- ========== END TEMPLATE CAROUSEL ========== -->

        <!-- ========== TEMPLATE CAROUSEL JAVASCRIPT ========== -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          const track = document.querySelector('.carousel-slides-track');
          const dots = document.querySelectorAll('.carousel-dot-btn');
          const slides = document.querySelectorAll('.carousel-slide');
          let currentSlide = 0;
          let isAnimating = false;

          if (track && slides.length > 0) {
            function updateCarousel() {
              if (isAnimating) return;
              isAnimating = true;

              // Hide all slides
              slides.forEach((slide, index) => {
                slide.style.opacity = index === currentSlide ? '1' : '0';
                slide.style.display = index === currentSlide ? 'block' : 'none';
              });

              // Update dots
              dots.forEach((dot, index) => {
                if (index === currentSlide) {
                  dot.classList.add('active');
                } else {
                  dot.classList.remove('active');
                }
              });

              setTimeout(() => {
                isAnimating = false;
              }, 300);
            }

            // Dot navigation
            dots.forEach((dot, index) => {
              dot.addEventListener('click', () => {
                if (!isAnimating && index !== currentSlide) {
                  currentSlide = index;
                  updateCarousel();
                }
              });
            });

            // Auto-play carousel
            setInterval(() => {
              if (!isAnimating) {
                currentSlide = (currentSlide + 1) % slides.length;
                updateCarousel();
              }
            }, 6000);

            // Initialize
            updateCarousel();
          }
        });
        </script>

        <!-- ========== TEMPLATE CAROUSEL STYLES ========== -->
        <style>
        .template-carousel-wrapper {
          margin: 2rem 0;
          padding: 0;
        }

        .carousel-container {
          position: relative;
          background: #ffffff;
          border-radius: 12px;
          overflow: hidden;
          box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .carousel-track-wrapper {
          position: relative;
          overflow: hidden;
        }

        .carousel-slides-track {
          position: relative;
        }

        .carousel-slide {
          display: none;
          opacity: 0;
          transition: opacity 0.3s ease;
        }

        .carousel-slide:first-child {
          display: block;
          opacity: 1;
        }

        .slide-layout {
          display: grid;
          grid-template-columns: 2fr 1fr;
          gap: 0;
          min-height: 400px;
        }

        /* Featured Post (Large) */
        .featured-post-card {
          position: relative;
          background: #ffffff;
          display: flex;
          flex-direction: column;
        }

        .post-image-wrapper {
          height: 250px;
          overflow: hidden;
        }

        .post-image {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.3s ease;
        }

        .featured-post-card:hover .post-image {
          transform: scale(1.05);
        }

        .post-content {
          padding: 24px;
          flex: 1;
          display: flex;
          flex-direction: column;
        }

        .post-meta {
          margin-bottom: 12px;
        }

        .post-date {
          color: #6b7280;
          font-size: 13px;
          text-transform: uppercase;
          font-weight: 500;
          letter-spacing: 0.5px;
        }

        .post-title {
          font-size: 24px;
          font-weight: 700;
          line-height: 1.3;
          margin: 0 0 16px 0;
          color: #1f2937;
        }

        .post-title a {
          color: inherit;
          text-decoration: none;
          transition: color 0.3s ease;
        }

        .post-title a:hover {
          color: #3b82f6;
        }

        .post-excerpt {
          color: #6b7280;
          font-size: 15px;
          line-height: 1.6;
          margin-bottom: 20px;
          flex: 1;
        }

        .post-author {
          margin-top: auto;
        }

        .author-name {
          color: #374151;
          font-size: 14px;
          font-weight: 600;
        }

        /* Side Posts (Small) */
        .side-posts-grid {
          background: #f9fafb;
          display: flex;
          flex-direction: column;
          border-left: 1px solid #e5e7eb;
        }

        .side-post-card {
          display: flex;
          padding: 20px;
          border-bottom: 1px solid #e5e7eb;
          transition: background-color 0.3s ease;
          flex: 1;
        }

        .side-post-card:hover {
          background: #ffffff;
        }

        .side-post-card:last-child {
          border-bottom: none;
        }

        .side-post-image {
          width: 80px;
          height: 60px;
          margin-right: 16px;
          border-radius: 8px;
          overflow: hidden;
          flex-shrink: 0;
        }

        .side-post-image img {
          width: 100%;
          height: 100%;
          object-fit: cover;
          transition: transform 0.3s ease;
        }

        .side-post-card:hover .side-post-image img {
          transform: scale(1.1);
        }

        .side-post-content {
          flex: 1;
          display: flex;
          flex-direction: column;
        }

        .side-post-meta {
          margin-bottom: 8px;
        }

        .side-post-date {
          color: #9ca3af;
          font-size: 11px;
          text-transform: uppercase;
          font-weight: 500;
          letter-spacing: 0.5px;
        }

        .side-post-title {
          font-size: 14px;
          font-weight: 600;
          line-height: 1.4;
          margin: 0 0 8px 0;
          color: #1f2937;
        }

        .side-post-title a {
          color: inherit;
          text-decoration: none;
          transition: color 0.3s ease;
        }

        .side-post-title a:hover {
          color: #3b82f6;
        }

        .side-post-author {
          color: #6b7280;
          font-size: 12px;
          margin-top: auto;
        }

        /* Pagination */
        .carousel-pagination {
          display: flex;
          justify-content: center;
          padding: 20px;
          gap: 8px;
          background: #ffffff;
        }

        .carousel-dot-btn {
          width: 12px;
          height: 12px;
          border-radius: 50%;
          border: none;
          background: #d1d5db;
          cursor: pointer;
          transition: all 0.3s ease;
        }

        .carousel-dot-btn:hover {
          background: #9ca3af;
          transform: scale(1.2);
        }

        .carousel-dot-btn.active {
          background: #3b82f6;
          transform: scale(1.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
          .slide-layout {
            grid-template-columns: 1fr;
            gap: 0;
          }

          .side-posts-grid {
            border-left: none;
            border-top: 1px solid #e5e7eb;
          }

          .post-content {
            padding: 20px;
          }

          .post-title {
            font-size: 20px;
          }

          .side-post-card {
            padding: 16px;
          }
        }
        </style>
      </section>

    </div>

    <!-- ========== EDITOR'S CHOICE SECTION - EDIT DIVIDER HERE ========== -->
    <div class="mb-8 mt-12">
      <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span class="text-amber-500">⭐</span>
        <?php echo techscope_get_section_title('editor'); ?>
      </h3>
      <div class="w-full h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
    </div>
    <!-- ========== END EDITOR'S CHOICE DIVIDER ========== -->

    <!-- EDITOR'S CHOICE - FULL WIDTH SECTION -->
    <div class="lg:col-span-3 section-animate stagger-2">

      <!-- Content Container - Clean Grid Only -->
      <section class="bg-gray-50 rounded-2xl sm:rounded-3xl overflow-hidden p-2 sm:p-3">
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
                <div class="bg-white rounded-2xl sm:rounded-3xl overflow-hidden card-hover border border-gray-100/50 transform transition-all duration-300 hover:-translate-y-0.5">
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

      <!-- ========== HOT STORIES SECTION - EDIT DIVIDER HERE ========== -->
      <div class="mb-8 mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span class="text-red-500">🔥</span>
          <?php echo techscope_get_section_title('hot'); ?>
        </h3>
        <div class="w-full h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
      </div>
      <!-- ========== END HOT STORIES DIVIDER ========== -->

      <!-- HOT STORIES -->
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
            <div class="bg-white rounded-lg lg:rounded-xl overflow-hidden card-hover">
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

      <!-- ========== MOBILE TECH SECTION - EDIT DIVIDER HERE ========== -->
      <div class="mb-8 mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span class="text-blue-500">📱</span>
          <?php echo techscope_get_section_title('mobile'); ?>
        </h3>
        <div class="w-full h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
      </div>
      <!-- ========== END MOBILE TECH DIVIDER ========== -->

      <!-- MOBILE TECH -->
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
            <div class="bg-white rounded-lg lg:rounded-xl overflow-hidden card-hover">
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

      <!-- ========== AI & GAMING SECTION - EDIT DIVIDER HERE ========== -->
      <div class="mb-8 mt-12">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
          <span class="text-green-500">🤖</span>
          <?php echo techscope_get_section_title('ai'); ?>
        </h3>
        <div class="w-full h-px bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200"></div>
      </div>
      <!-- ========== END AI & GAMING DIVIDER ========== -->

      <!-- AI & GAMING -->
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
            <div class="bg-white rounded-xl overflow-hidden card-hover">
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
  </div>
</div>

<?php get_footer(); ?>