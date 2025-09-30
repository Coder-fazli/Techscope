<?php get_header(); ?>

<style>
  /* Masonry Layout */
  .masonry-grid {
    column-count: 2;
    column-gap: 1rem;
  }

  @media (min-width: 768px) {
    .masonry-grid {
      column-count: 2;
      column-gap: 1.5rem;
    }
  }

  @media (min-width: 1024px) {
    .masonry-grid {
      column-count: 3;
    }
  }

  .masonry-item {
    break-inside: avoid;
    margin-bottom: 1rem;
    display: inline-block;
    width: 100%;
  }

  @media (min-width: 768px) {
    .masonry-item {
      margin-bottom: 1.5rem;
      border: solid 1.5px #EBEBEB;
      /* padding: 24px; */
    }
  }

  /* Remove line-clamp for masonry to allow natural heights */
  .masonry-item .excerpt-text {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  /* Fade-in animation for posts */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .masonry-item {
    animation: fadeInUp 0.6s ease-out forwards;
    opacity: 0;
  }

  /* Stagger animation for each post */
  .masonry-item:nth-child(1) { animation-delay: 0.1s; }
  .masonry-item:nth-child(2) { animation-delay: 0.2s; }
  .masonry-item:nth-child(3) { animation-delay: 0.3s; }
  .masonry-item:nth-child(4) { animation-delay: 0.4s; }
  .masonry-item:nth-child(5) { animation-delay: 0.5s; }
  .masonry-item:nth-child(6) { animation-delay: 0.6s; }
  .masonry-item:nth-child(7) { animation-delay: 0.7s; }
  .masonry-item:nth-child(8) { animation-delay: 0.8s; }
  .masonry-item:nth-child(9) { animation-delay: 0.9s; }
  .masonry-item:nth-child(n+10) { animation-delay: 1s; }
</style>

<div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6">

  <?php if (have_posts()) : ?>

    <!-- Category Header -->
    <div class="mb-8">
      <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 flex items-center gap-3">
        <span class="material-icons text-orange-600 text-4xl">category</span>
        <?php single_cat_title(); ?>
      </h1>
      <?php if (category_description()) : ?>
        <div class="text-gray-600 text-lg"><?php echo category_description(); ?></div>
      <?php endif; ?>
    </div>

    <!-- Posts Masonry Grid - 3 Columns -->
    <div class="masonry-grid">
      <?php while (have_posts()) : the_post();
        $view_count = techscope_format_view_count(techscope_get_post_views(get_the_ID()));
        $rating = techscope_get_post_rating(get_the_ID());

        // Increment view count
        techscope_increment_post_views(get_the_ID());
      ?>
        <article class="masonry-item bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-shadow">

          <!-- Featured Image -->
          <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block w-full h-48 tech-img bg-cover bg-center"
               style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'medium'); ?>')">
            </a>
          <?php endif; ?>

          <!-- Post Content -->
          <div class="p-4">

            <!-- Category Badge -->
            <?php
            $categories = get_the_category();
            if (!empty($categories)) {
              echo '<span class="inline-block bg-orange-100 text-orange-600 text-xs font-semibold px-3 py-1 rounded-full mb-3">' . esc_html($categories[0]->name) . '</span>';
            }
            ?>

            <!-- Title -->
            <h2 class="font-bold text-lg mb-3 leading-tight line-clamp-2">
              <a href="<?php the_permalink(); ?>" class="text-gray-900 hover:text-orange-600 transition-colors">
                <?php the_title(); ?>
              </a>
            </h2>

            <!-- Excerpt -->
            <p class="text-gray-600 text-sm mb-4 leading-relaxed excerpt-text">
              <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
            </p>

            <!-- Meta Information -->
            <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
              <span class="flex items-center gap-1">
                <span class="material-icons text-sm">schedule</span>
                <?php echo get_the_date('M j'); ?>
              </span>

              <div class="flex items-center gap-3">
                <!-- View Count -->
                <span class="text-orange-500 flex items-center gap-1">
                  <span class="material-icons text-sm">visibility</span>
                  <?php echo $view_count; ?>
                </span>

                <!-- Comments -->
                <span class="text-gray-500 flex items-center gap-1">
                  <span class="material-icons text-sm">comment</span>
                  <?php comments_number('0', '1', '%'); ?>
                </span>
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
        <?php _e('Sorry, no posts were found in this category.', 'techscope'); ?>
      </p>
      <a href="<?php echo esc_url(home_url('/')); ?>"
         class="inline-flex items-center gap-2 bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition-colors">
        <span class="material-icons">home</span>
        <?php _e('Back to Home', 'techscope'); ?>
      </a>
    </div>

  <?php endif; ?>

</div>

<?php get_footer(); ?>