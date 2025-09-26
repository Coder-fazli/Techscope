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
      <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover p-3">
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
    </div>
    <?php endif; ?>

    <!-- TRENDING WIDGET - Right side of hero -->
    <?php if (get_option('techscope_show_trending', 1)) : ?>
    <div class="lg:col-span-1 section-animate stagger-2">
      <div class="bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl p-4 shadow-lg border border-pink-100 h-full">
        <div class="flex items-center justify-center mb-4">
          <div class="bg-gradient-to-r from-pink-500 to-rose-500 text-white px-3 py-2 rounded-full text-xs font-bold tracking-wide">
            🔥 TRENDING
          </div>
        </div>
        <div class="space-y-4">
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
            <!-- Featured Trending Post Design for All Posts -->
            <div class="relative overflow-hidden rounded-xl shadow-lg bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white card-hover h-[140px]">
              <div class="absolute inset-0 opacity-70">
                <img src="<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-contain">
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-transparent"></div>
              <div class="absolute bottom-0 left-0 right-0 p-3">
                <div class="flex items-center gap-2 mb-1">
                  <span class="bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs px-2 py-0.5 rounded-full font-bold shadow-lg">
                    <?php echo $category_name; ?>
                  </span>
                  <span class="text-pink-200 text-xs font-medium">TRENDING</span>
                </div>
                <h4 class="font-bold text-sm mb-1 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="text-white hover:text-pink-200 transition-colors duration-200">
                    <?php echo techscope_truncate_text(get_the_title(), 50); ?>
                  </a>
                </h4>

                <!-- Short Description -->
                <p class="text-gray-200 text-xs mb-2 line-clamp-1 leading-relaxed">
                  <?php echo techscope_truncate_text(get_the_excerpt() ?: wp_trim_words(get_the_content(), 10), 60); ?>
                </p>

                <div class="flex items-center justify-between text-xs">
                  <div class="flex items-center gap-2 text-gray-300">
                    <span class="font-medium"><?php echo get_the_date('M j, Y'); ?></span>
                    <span>•</span>
                    <span><?php the_author(); ?></span>
                  </div>
                  <div class="flex items-center gap-1 text-pink-300">
                    <span class="material-icons text-xs">visibility</span>
                    <span class="font-bold"><?php echo $view_count; ?></span>
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

          <!-- See More Button -->
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
            <div class="text-center mt-4">
              <a href="<?php echo esc_url($see_more_url); ?>"
                 class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 to-rose-500 text-white px-6 py-3 rounded-full font-bold text-sm hover:from-pink-600 hover:to-rose-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                <span><?php echo esc_html($see_more_text); ?></span>
                <span class="material-icons text-sm">arrow_forward</span>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- MAIN CONTENT SECTIONS -->
    <div class="lg:col-span-3 space-y-6 lg:space-y-8">

      <!-- TRENDING TECH SLIDER -->
      <section class="section-animate stagger-1">
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg md:text-xl font-extrabold uppercase tracking-wider text-purple-800">
            🔥 <?php echo techscope_get_section_title('trending'); ?>
          </h3>
          <div class="flex items-center gap-2">
            <button class="trending-slider-prev w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
              <span class="material-icons text-sm">chevron_left</span>
            </button>
            <button class="trending-slider-next w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors">
              <span class="material-icons text-sm">chevron_right</span>
            </button>
          </div>
        </div>

        <div class="trending-slider-container overflow-hidden px-2">
          <div class="trending-slider flex gap-8 transition-transform duration-300 ease-in-out py-4">
            <?php
            $trending_posts = techscope_get_featured_posts();
            if ($trending_posts->have_posts()) :
              // Group posts into chunks of 4
              $posts_array = [];
              while ($trending_posts->have_posts()) : $trending_posts->the_post();
                $posts_array[] = [
                  'id' => get_the_ID(),
                  'title' => get_the_title(),
                  'permalink' => get_the_permalink(),
                  'author' => get_the_author(),
                  'date' => get_the_date('M j'),
                  'image' => techscope_get_responsive_image(get_the_ID(), 'featured-card'),
                  'view_count' => techscope_format_view_count(techscope_get_post_views(get_the_ID()))
                ];
              endwhile;
              wp_reset_postdata();

              // Create slides with 4 posts each
              $post_chunks = array_chunk($posts_array, 4);
              foreach ($post_chunks as $chunk) :
            ?>
              <!-- SLIDE with 4 posts -->
              <div class="trending-slide flex-shrink-0 w-full">
                <div class="grid grid-cols-4 gap-6">
                  <?php foreach ($chunk as $post_data) : ?>
                    <div class="trending-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 card-hover">
                      <div class="trending-card-image w-full h-48 relative overflow-hidden">
                        <img src="<?php echo $post_data['image']; ?>"
                             alt="<?php echo esc_attr($post_data['title']); ?>"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        <div class="absolute top-3 left-3">
                          <span class="bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                            🔥 TRENDING
                          </span>
                        </div>
                      </div>
                      <div class="p-6">
                        <h4 class="font-bold text-lg mb-3 leading-tight">
                          <a href="<?php echo $post_data['permalink']; ?>" class="text-gray-900 hover:text-blue-600 transition-colors">
                            <?php echo techscope_truncate_text($post_data['title'], 60); ?>
                          </a>
                        </h4>
                        <div class="flex items-center justify-between text-sm">
                          <div class="flex items-center gap-2 text-gray-600">
                            <span class="font-medium">BY <?php echo strtoupper($post_data['author']); ?></span>
                          </div>
                          <div class="flex items-center gap-3 text-gray-500">
                            <span class="flex items-center gap-1">
                              <span class="material-icons text-sm text-orange-500">visibility</span>
                              <?php echo $post_data['view_count']; ?>
                            </span>
                            <span><?php echo $post_data['date']; ?></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php
              endforeach;
            endif;
            ?>
          </div>
        </div>

        <!-- Modern Slider JavaScript with Superdesign Aesthetics -->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          const slider = document.querySelector('.trending-slider');
          const prevBtn = document.querySelector('.trending-slider-prev');
          const nextBtn = document.querySelector('.trending-slider-next');
          const container = document.querySelector('.trending-slider-container');
          let currentSlide = 0;
          let isAnimating = false;

          if (slider && prevBtn && nextBtn && container) {
            setTimeout(() => {
              const slides = slider.querySelectorAll('.trending-slide');
              const totalSlides = slides.length;

              function updateSliderPosition(smooth = true) {
                if (isAnimating) return;
                isAnimating = true;

                const slideWidth = container.offsetWidth;
                const gapSize = 32; // 8 * 4px = 32px gap between slides
                const translateX = -currentSlide * (slideWidth + gapSize);

                // Modern superdesign animation
                slider.style.transition = smooth ?
                  'transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)' : 'none';
                slider.style.transform = `translateX(${translateX}px) translateZ(0)`;

                // Smooth button state transitions
                updateButtonStates();

                // Add subtle card animations during slide
                if (smooth) {
                  animateCards();
                }

                setTimeout(() => {
                  isAnimating = false;
                }, smooth ? 800 : 0);
              }

              function updateButtonStates() {
                // Smooth button transitions with superdesign style
                prevBtn.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                nextBtn.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';

                if (currentSlide === 0) {
                  prevBtn.style.opacity = '0.3';
                  prevBtn.style.transform = 'scale(0.9)';
                  prevBtn.style.pointerEvents = 'none';
                } else {
                  prevBtn.style.opacity = '1';
                  prevBtn.style.transform = 'scale(1)';
                  prevBtn.style.pointerEvents = 'auto';
                }

                if (currentSlide === totalSlides - 1) {
                  nextBtn.style.opacity = '0.3';
                  nextBtn.style.transform = 'scale(0.9)';
                  nextBtn.style.pointerEvents = 'none';
                } else {
                  nextBtn.style.opacity = '1';
                  nextBtn.style.transform = 'scale(1)';
                  nextBtn.style.pointerEvents = 'auto';
                }
              }

              function animateCards() {
                // Subtle card animations during transition
                const currentSlideElement = slides[currentSlide];
                if (currentSlideElement) {
                  const cards = currentSlideElement.querySelectorAll('.trending-card');
                  cards.forEach((card, index) => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                    card.style.transform = 'translateY(20px)';
                    card.style.opacity = '0.7';

                    setTimeout(() => {
                      card.style.transform = 'translateY(0)';
                      card.style.opacity = '1';
                    }, 200 + (index * 100));
                  });
                }
              }

              // Set initial slide widths with proper flexbox
              slides.forEach((slide, index) => {
                slide.style.width = container.offsetWidth + 'px';
                slide.style.flexShrink = '0';
                slide.style.willChange = 'transform';
              });

              // Modern button interactions
              nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!isAnimating && currentSlide < totalSlides - 1) {
                  // Add button press animation
                  nextBtn.style.transform = 'scale(0.95)';
                  setTimeout(() => {
                    nextBtn.style.transform = currentSlide === totalSlides - 2 ? 'scale(0.9)' : 'scale(1)';
                  }, 100);

                  currentSlide++;
                  updateSliderPosition();
                }
              });

              prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (!isAnimating && currentSlide > 0) {
                  // Add button press animation
                  prevBtn.style.transform = 'scale(0.95)';
                  setTimeout(() => {
                    prevBtn.style.transform = currentSlide === 1 ? 'scale(0.9)' : 'scale(1)';
                  }, 100);

                  currentSlide--;
                  updateSliderPosition();
                }
              });

              // Add touch/swipe support for modern feel
              let startX = 0;
              let currentX = 0;
              let isDragging = false;

              slider.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
                slider.style.transition = 'none';
              });

              slider.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                currentX = e.touches[0].clientX;
                const diffX = currentX - startX;
                const slideWidth = container.offsetWidth;
                const gapSize = 32;
                const currentTranslateX = -currentSlide * (slideWidth + gapSize);
                slider.style.transform = `translateX(${currentTranslateX + diffX}px) translateZ(0)`;
              });

              slider.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;
                const diffX = currentX - startX;
                const threshold = container.offsetWidth * 0.2;

                if (Math.abs(diffX) > threshold) {
                  if (diffX > 0 && currentSlide > 0) {
                    currentSlide--;
                  } else if (diffX < 0 && currentSlide < totalSlides - 1) {
                    currentSlide++;
                  }
                }
                updateSliderPosition();
              });

              // Initialize with smooth entrance
              updateSliderPosition(false);
              setTimeout(() => {
                animateCards();
              }, 100);

              // Responsive resize with debouncing
              let resizeTimeout;
              window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                  slides.forEach(slide => {
                    slide.style.width = container.offsetWidth + 'px';
                  });
                  updateSliderPosition(false);
                }, 150);
              });

            }, 100);
          }
        });
        </script>

        <style>
        .trending-card-image {
          background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        /* Modern Superdesign Slider Styles */
        .trending-slider-container {
          position: relative;
          width: 100%;
          overflow: hidden;
          border-radius: 12px;
          background: linear-gradient(135deg, #FFEFF3 0%, #f8fafc 100%);
          padding: 8px;
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .trending-slider {
          display: flex;
          width: 100%;
          will-change: transform;
          backface-visibility: hidden;
          perspective: 1000px;
        }

        .trending-slide {
          min-width: 100%;
          width: 100%;
          flex-shrink: 0;
          box-sizing: border-box;
          transform: translateZ(0);
          margin-right: 2rem; /* Add spacing between slides */
        }

        .trending-slide:last-child {
          margin-right: 0; /* Remove margin from last slide */
        }

        /* Enhanced Card Hover Effects with Superdesign */
        .trending-card {
          transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          will-change: transform, box-shadow;
          backface-visibility: hidden;
        }

        .trending-card:hover {
          transform: translateY(-8px) scale(1.02);
          box-shadow:
            0 20px 25px -5px rgba(255, 77, 120, 0.15),
            0 10px 10px -5px rgba(0, 0, 0, 0.04),
            0 4px 12px rgba(255, 77, 120, 0.1);
        }

        .trending-card:hover .trending-card-image img {
          transform: scale(1.05);
        }

        /* Modern Button Styles with Superdesign Aesthetic */
        .trending-slider-prev,
        .trending-slider-next {
          background: linear-gradient(135deg, #FF80A5 0%, #FF3366 100%);
          border: none;
          box-shadow:
            0 4px 6px -1px rgba(255, 77, 120, 0.25),
            0 2px 4px -1px rgba(0, 0, 0, 0.06);
          transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          backdrop-filter: blur(10px);
        }

        .trending-slider-prev:hover,
        .trending-slider-next:hover {
          background: linear-gradient(135deg, #FF3366 0%, #FF1744 100%);
          transform: scale(1.1);
          box-shadow:
            0 8px 12px -2px rgba(255, 77, 120, 0.35),
            0 4px 8px -2px rgba(0, 0, 0, 0.1);
        }

        .trending-slider-prev .material-icons,
        .trending-slider-next .material-icons {
          color: white;
          font-weight: bold;
        }

        /* Responsive grid adjustments */
        @media (max-width: 1024px) {
          .trending-slide .grid {
            grid-template-columns: repeat(3, 1fr); /* 3 cards on tablet */
            gap: 1rem;
          }
        }

        @media (max-width: 768px) {
          .trending-slide .grid {
            grid-template-columns: repeat(2, 1fr); /* 2 cards on small tablet */
            gap: 1rem;
          }
        }

        @media (max-width: 640px) {
          .trending-slide .grid {
            grid-template-columns: 1fr; /* 1 card on mobile */
            gap: 1rem;
          }
        }
        </style>
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
  </div>
</div>

<?php get_footer(); ?>