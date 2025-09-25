<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>

  <!-- Tailwind CSS Configuration -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            'inter': ['Inter', 'system-ui', 'sans-serif'],
          }
        }
      }
    }
  </script>

  <?php wp_head(); ?>
</head>
<body <?php body_class('bg-gray-100 font-inter'); ?>>

<?php wp_body_open(); ?>

<!-- NAVIGATION -->
<nav class="sticky top-0 z-50 mt-2 sm:mt-4">
  <div class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">

      <!-- Mobile Header -->
      <div class="flex items-center justify-between p-4 md:hidden">
        <div class="flex items-center gap-2">
          <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
          <?php else : ?>
            <span class="material-icons text-blue-600">phone_iphone</span>
            <span class="text-blue-600 font-bold text-lg"><?php bloginfo('name'); ?></span>
          <?php endif; ?>
        </div>
        <button id="mobile-menu-btn" class="text-gray-600 hover:text-blue-600 transition-colors">
          <span class="material-icons text-2xl">menu</span>
        </button>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex flex-wrap items-center justify-center gap-2 sm:gap-4 md:gap-8 p-4 border-b border-gray-100">
        <?php
        wp_nav_menu(array(
          'theme_location' => 'primary',
          'menu_class' => 'flex flex-wrap items-center justify-center gap-2 sm:gap-4 md:gap-8',
          'container' => false,
          'fallback_cb' => 'techscope_fallback_menu',
          'walker' => new TechScope_Walker_Nav_Menu()
        ));
        ?>
      </div>

      <!-- Mobile Menu -->
      <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200">
        <div class="py-2">
          <?php
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class' => 'mobile-nav',
            'container' => false,
            'fallback_cb' => 'techscope_fallback_mobile_menu',
            'walker' => new TechScope_Mobile_Walker_Nav_Menu()
          ));
          ?>
        </div>
      </div>

    </div>
  </div>
</nav>