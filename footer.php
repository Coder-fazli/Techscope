<!-- FOOTER -->
<footer class="bg-gray-900 text-white py-12 mt-16">
  <div class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

      <div>
        <h4 class="text-lg font-bold mb-4 text-blue-400"><?php bloginfo('name'); ?></h4>
        <p class="text-gray-300 text-sm mb-4">
          <?php
          $description = get_bloginfo('description');
          if (empty($description)) {
            echo 'Your ultimate destination for the latest technology news, reviews, and insights. Stay ahead with cutting-edge tech coverage.';
          } else {
            echo esc_html($description);
          }
          ?>
        </p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
            <span class="material-icons">facebook</span>
          </a>
          <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
            <span class="material-icons">twitter</span>
          </a>
          <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
            <span class="material-icons">instagram</span>
          </a>
          <a href="#" class="text-gray-300 hover:text-blue-400 transition-colors">
            <span class="material-icons">youtube_activity</span>
          </a>
        </div>
      </div>

      <div>
        <h4 class="text-lg font-bold mb-4 text-blue-400"><?php _e('Categories', 'techscope'); ?></h4>
        <ul class="space-y-2 text-sm">
          <?php
          $categories = get_categories(array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'hide_empty' => true,
            'number' => 7
          ));
          foreach ($categories as $category) :
          ?>
            <li>
              <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                 class="text-gray-300 hover:text-blue-400 transition-colors">
                <?php echo esc_html($category->name); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <div>
        <h4 class="text-lg font-bold mb-4 text-blue-400"><?php _e('Popular Posts', 'techscope'); ?></h4>
        <div class="space-y-3">
          <?php
          $popular_posts = new WP_Query(array(
            'posts_per_page' => 3,
            'meta_key' => '_techscope_post_views',
            'orderby' => 'meta_value_num',
            'order' => 'DESC',
            'post_status' => 'publish'
          ));

          if ($popular_posts->have_posts()) :
            while ($popular_posts->have_posts()) : $popular_posts->the_post();
          ?>
            <div class="flex gap-3">
              <div class="w-12 h-12 tech-img rounded"
                   style="background-image: url('<?php echo techscope_get_responsive_image(get_the_ID(), 'thumbnail'); ?>')">
              </div>
              <div class="flex-1">
                <h5 class="text-sm font-semibold text-gray-200 mb-1">
                  <a href="<?php the_permalink(); ?>" class="hover:text-blue-400 transition-colors">
                    <?php echo techscope_truncate_text(get_the_title(), 40); ?>
                  </a>
                </h5>
                <p class="text-xs text-gray-400"><?php echo get_the_date(); ?></p>
              </div>
            </div>
          <?php
            endwhile;
            wp_reset_postdata();
          else :
          ?>
            <!-- Fallback content -->
            <div class="flex gap-3">
              <div class="w-12 h-12 tech-img rounded" style="background-image: url('https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80')"></div>
              <div class="flex-1">
                <h5 class="text-sm font-semibold text-gray-200 mb-1"><?php _e('AI Breakthroughs 2025', 'techscope'); ?></h5>
                <p class="text-xs text-gray-400"><?php echo date('M j, Y'); ?></p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div>
        <h4 class="text-lg font-bold mb-4 text-blue-400"><?php _e('Newsletter', 'techscope'); ?></h4>
        <p class="text-gray-300 text-sm mb-4"><?php _e('Subscribe to get the latest tech news delivered to your inbox weekly.', 'techscope'); ?></p>
        <div class="space-y-3">
          <form class="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
            <input type="email"
                   name="newsletter_email"
                   placeholder="<?php _e('Enter your email', 'techscope'); ?>"
                   class="w-full px-4 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-400"
                   required>
            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition-colors">
              <?php _e('Subscribe', 'techscope'); ?>
            </button>
            <input type="hidden" name="action" value="newsletter_subscribe">
            <?php wp_nonce_field('newsletter_nonce', 'newsletter_nonce'); ?>
          </form>
        </div>
        <p class="text-xs text-gray-400 mt-3"><?php _e('We respect your privacy. Unsubscribe at any time.', 'techscope'); ?></p>
      </div>

    </div>

    <div class="border-t border-gray-700 mt-12 pt-8">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="text-gray-400 text-sm mb-4 md:mb-0">
          © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'techscope'); ?>
        </div>
        <div class="flex space-x-6 text-sm">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_class' => 'flex space-x-6 text-sm',
            'container' => false,
            'fallback_cb' => 'techscope_footer_menu_fallback'
          ));
          ?>
        </div>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>

<?php
// Fallback footer menu
function techscope_footer_menu_fallback() {
  ?>
  <a href="<?php echo get_privacy_policy_url(); ?>" class="text-gray-400 hover:text-blue-400 transition-colors"><?php _e('Privacy Policy', 'techscope'); ?></a>
  <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors"><?php _e('Terms of Service', 'techscope'); ?></a>
  <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="text-gray-400 hover:text-blue-400 transition-colors"><?php _e('Contact Us', 'techscope'); ?></a>
  <a href="<?php echo esc_url(home_url('/about')); ?>" class="text-gray-400 hover:text-blue-400 transition-colors"><?php _e('About', 'techscope'); ?></a>
  <?php
}
?>