<?php get_header(); ?>

<div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

    <!-- MAIN CONTENT -->
    <div class="lg:col-span-2 space-y-6">

      <?php if (have_posts()) : ?>

        <!-- Page Title -->
        <div class="mb-8">
          <?php if (is_home() && !is_front_page()) : ?>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4"><?php single_post_title(); ?></h1>
          <?php elseif (is_category()) : ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              <span class="material-icons text-blue-600 mr-2">category</span>
              <?php single_cat_title(); ?>
            </h1>
            <?php if (category_description()) : ?>
              <div class="text-gray-600"><?php echo category_description(); ?></div>
            <?php endif; ?>
          <?php elseif (is_tag()) : ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              <span class="material-icons text-blue-600 mr-2">tag</span>
              <?php single_tag_title(); ?>
            </h1>
          <?php elseif (is_author()) : ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              <span class="material-icons text-blue-600 mr-2">person</span>
              <?php _e('Posts by', 'techscope'); ?> <?php the_author(); ?>
            </h1>
          <?php elseif (is_search()) : ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
              <span class="material-icons text-blue-600 mr-2">search</span>
              <?php printf(__('Search Results for: %s', 'techscope'), '<span class="text-blue-600">' . get_search_query() . '</span>'); ?>
            </h1>
          <?php elseif (is_archive()) : ?>
            <h1 class="text-3xl font-bold text-gray-900 mb-4"><?php the_archive_title(); ?></h1>
          <?php endif; ?>
        </div>

        <!-- Posts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <?php while (have_posts()) : the_post();
            $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
            $rating = techscope_get_post_rating(get_the_ID());

            // Increment view count
            techscope_increment_post_views(get_the_ID());
          ?>
            <article class="bg-white rounded-xl overflow-hidden card-hover">

              <!-- Featured Image -->
              <?php if (has_post_thumbnail()) : ?>
                <div class="w-full h-48 tech-img"
                     style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'featured-card'); ?>')">
                  <a href="<?php the_permalink(); ?>" class="block w-full h-full"></a>
                </div>
              <?php endif; ?>

              <!-- Post Content -->
              <div class="p-4">

                <!-- Title -->
                <h2 class="font-bold text-lg mb-3 leading-tight">
                  <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-blue-600 transition-colors">
                    <?php the_title(); ?>
                  </a>
                </h2>

                <!-- Excerpt -->
                <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                  <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                </p>

                <!-- Meta Information -->
                <div class="flex items-center justify-between text-sm text-gray-500">
                  <div class="flex items-center gap-3">
                    <span><?php echo get_the_date('M j, Y'); ?></span>
                  </div>

                  <div class="flex items-center gap-3">
                    <!-- View Count -->
                    <span class="text-orange-500 flex items-center gap-1">
                      <span class="material-icons text-sm">visibility</span>
                      <?php echo $view_count; ?>
                    </span>

                    <!-- Comments -->
                    <span class="text-blue-500 flex items-center gap-1">
                      <span class="material-icons text-sm">comment</span>
                      <?php comments_number('0', '1', '%'); ?>
                    </span>

                    <!-- Rating -->
                    <?php if ($rating > 0) : ?>
                      <span class="text-yellow-500 flex items-center gap-1">
                        <span class="material-icons text-sm">star</span>
                        <?php echo number_format($rating, 1); ?>
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </article>
          <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-12">
          <?php
          the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => '<span class="material-icons">arrow_back</span> ' . __('Previous', 'techscope'),
            'next_text' => __('Next', 'techscope') . ' <span class="material-icons">arrow_forward</span>',
            'class' => 'flex justify-center items-center space-x-2',
          ));
          ?>
        </div>

      <?php else : ?>

        <!-- No Posts Found -->
        <div class="text-center py-12">
          <div class="mb-6">
            <span class="material-icons text-6xl text-gray-300">search_off</span>
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            <?php _e('No posts found', 'techscope'); ?>
          </h2>
          <p class="text-gray-600 mb-6">
            <?php _e('Sorry, no posts were found matching your criteria.', 'techscope'); ?>
          </p>
          <a href="<?php echo esc_url(home_url('/')); ?>"
             class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
            <span class="material-icons">home</span>
            <?php _e('Back to Home', 'techscope'); ?>
          </a>
        </div>

      <?php endif; ?>

    </div>

    <!-- SIDEBAR -->
    <div class="lg:col-span-1">
      <?php get_sidebar(); ?>
    </div>

  </div>
</div>

<?php get_footer(); ?>
