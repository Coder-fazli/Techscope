<!-- FOOTER -->
<footer class="bg-gray-900 text-white py-12 mt-16">
  <div class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">

      <!-- Footer Column 1 -->
      <div class="footer-widget-area">
        <?php if (is_active_sidebar('footer-1')) : ?>
          <?php dynamic_sidebar('footer-1'); ?>
        <?php else : ?>
          <!-- Default content for Footer 1 -->
          <h4 class="text-lg font-bold mb-4 text-orange-400"><?php bloginfo('name'); ?></h4>
          <p class="text-gray-300 text-sm mb-4">
            <?php
            $description = get_bloginfo('description');
            if (empty($description)) {
              echo 'Your ultimate destination for the latest technology news, reviews, and insights.';
            } else {
              echo esc_html($description);
            }
            ?>
          </p>
          <div class="flex space-x-3 sm:space-x-4">
            <a href="#" class="text-gray-300 hover:text-orange-400 transition-colors">
              <span class="material-icons">facebook</span>
            </a>
            <a href="#" class="text-gray-300 hover:text-orange-400 transition-colors">
              <span class="material-icons">twitter</span>
            </a>
            <a href="#" class="text-gray-300 hover:text-orange-400 transition-colors">
              <span class="material-icons">instagram</span>
            </a>
          </div>
        <?php endif; ?>
      </div>

      <!-- Footer Column 2 -->
      <div class="footer-widget-area">
        <?php if (is_active_sidebar('footer-2')) : ?>
          <?php dynamic_sidebar('footer-2'); ?>
        <?php else : ?>
          <!-- Default content for Footer 2 -->
          <h4 class="text-lg font-bold mb-4 text-orange-400"><?php _e('Quick Links', 'techscope'); ?></h4>
          <ul class="space-y-2 text-sm">
            <li><a href="<?php echo home_url('/'); ?>" class="text-gray-300 hover:text-orange-400 transition-colors"><?php _e('Home', 'techscope'); ?></a></li>
            <li><a href="<?php echo home_url('/about'); ?>" class="text-gray-300 hover:text-orange-400 transition-colors"><?php _e('About', 'techscope'); ?></a></li>
            <li><a href="<?php echo home_url('/contact'); ?>" class="text-gray-300 hover:text-orange-400 transition-colors"><?php _e('Contact', 'techscope'); ?></a></li>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Footer Column 3 -->
      <div class="footer-widget-area">
        <?php if (is_active_sidebar('footer-3')) : ?>
          <?php dynamic_sidebar('footer-3'); ?>
        <?php else : ?>
          <!-- Default content for Footer 3 -->
          <h4 class="text-lg font-bold mb-4 text-orange-400"><?php _e('Categories', 'techscope'); ?></h4>
          <ul class="space-y-2 text-sm">
            <?php
            $categories = get_categories(array(
              'orderby' => 'name',
              'order'   => 'ASC',
              'hide_empty' => true,
              'number' => 5
            ));
            foreach ($categories as $category) :
            ?>
              <li>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                   class="text-gray-300 hover:text-orange-400 transition-colors">
                  <?php echo esc_html($category->name); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Footer Column 4 -->
      <div class="footer-widget-area">
        <?php if (is_active_sidebar('footer-4')) : ?>
          <?php dynamic_sidebar('footer-4'); ?>
        <?php else : ?>
          <!-- Default content for Footer 4 -->
          <h4 class="text-lg font-bold mb-4 text-orange-400"><?php _e('Newsletter', 'techscope'); ?></h4>
          <p class="text-gray-300 text-sm mb-4"><?php _e('Subscribe to get the latest tech news.', 'techscope'); ?></p>
          <form class="space-y-3">
            <input type="email"
                   placeholder="<?php _e('Enter your email', 'techscope'); ?>"
                   class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-orange-400">
            <button type="submit"
                    class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 px-3 rounded-lg font-semibold transition-colors">
              <?php _e('Subscribe', 'techscope'); ?>
            </button>
          </form>
        <?php endif; ?>
      </div>

    </div>

    <style>
      /* Footer Widget Styling */
      .footer-widget-area .widget ul {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .footer-widget-area .widget ul li {
        margin: 0.5rem 0;
      }

      .footer-widget-area .widget ul li a {
        color: #d1d5db;
        text-decoration: none;
        transition: color 0.2s;
        font-size: 0.875rem;
      }

      .footer-widget-area .widget ul li a:hover {
        color: #fb923c;
      }

      .footer-widget-area .widget p {
        color: #d1d5db;
        font-size: 0.875rem;
        line-height: 1.6;
      }

      .footer-widget-area .textwidget {
        color: #d1d5db;
        font-size: 0.875rem;
      }
    </style>

    <div class="border-t border-gray-700 mt-12 pt-8">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="text-gray-400 text-sm mb-4 md:mb-0">
          © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'techscope'); ?>
        </div>
        <div class="flex flex-wrap gap-3 sm:gap-4 md:gap-6 text-sm justify-center md:justify-end">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_class' => 'flex flex-wrap gap-3 sm:gap-4 md:gap-6 text-sm justify-center md:justify-end',
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

<!-- Page Loader Script - Hide after page loads -->
<script>
  // Hide loader immediately when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
      var loader = document.getElementById('page-loader');
      if (loader) {
        loader.classList.add('loaded');
      }
    }, 400); // Short 400ms delay for smooth experience
  });

  // Fallback: Hide loader when everything is fully loaded
  window.addEventListener('load', function() {
    setTimeout(function() {
      var loader = document.getElementById('page-loader');
      if (loader) {
        loader.classList.add('loaded');
      }
    }, 500);
  });
</script>

<!-- jQuery and Slick Slider -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

<!-- Main Theme JavaScript (includes loading logic) -->
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/script.js?v=<?php echo time(); ?>"></script>

<!-- EPCL Carousel JavaScript -->
<script src="<?php echo get_template_directory_uri(); ?>/js/epcl-carousel.js?v=<?php echo time(); ?>"></script>


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