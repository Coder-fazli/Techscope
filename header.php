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

  <!-- Yandex Market Widget -->
  <script async src="https://aflt.market.yandex.ru/widget/script/api" type="text/javascript"></script>

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
     (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
     m[i].l=1*new Date();
     for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
     k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
     (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

     ym(97490064, "init", {
          clickmap:true,
          trackLinks:true,
          accurateTrackBounce:true,
          webvisor:true
     });
  </script>
  <noscript><div><img src="https://mc.yandex.ru/watch/97490064" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->

  <!-- Additional Meta Tags -->
  <link rel="profile" href="https://gmpg.org/xfn/11" />
  <meta name="google-site-verification" content="EVv8JI1rI99r26zArKjlnhP4Bh0y4Jy9JBpxNncgoyw" />
  <meta name="yandex-verification" content="003691d1a0a98324" />

  <?php wp_head(); ?>

  <!-- SIDEBAR CARD STYLING - DIRECT HEAD CSS FOR MAXIMUM PRIORITY -->
  <style>
    .lg\:col-span-1 .sidebar-card {
      background-color: #FFFFFF !important;
      border-radius: 20px !important;
      border: none !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18) !important, 0 8px 24px rgba(0, 0, 0, 0.12) !important, 0 16px 48px rgba(0, 0, 0, 0.08) !important;
      transition: all 0.4s ease !important;
    }

    .lg\:col-span-1 .sidebar-card:hover {
      transform: translateY(-8px) scale(1.03) !important;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25) !important, 0 16px 48px rgba(0, 0, 0, 0.18) !important, 0 32px 96px rgba(0, 0, 0, 0.12) !important;
    }

    .lg\:col-span-1 .sidebar-image {
      border-radius: 20px 20px 0 0 !important;
      margin: 0 !important;
      background-size: cover !important;
      background-position: center !important;
      overflow: hidden !important;
    }
  </style>
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