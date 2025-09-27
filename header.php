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

  <!-- SIDEBAR STYLING - TARGET PARENT CONTAINERS FOR SHADOWS -->
  <style>
    /* Individual post cards - rounded corners and hover effects */
    .lg\:col-span-1 .sidebar-card {
      background-color: #FFFFFF !important;
      border-radius: 20px !important;
      border: none !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35) !important, 0 8px 24px rgba(0, 0, 0, 0.25) !important, 0 16px 48px rgba(0, 0, 0, 0.15) !important;
      transition: all 0.4s ease !important;
    }

    .lg\:col-span-1 .sidebar-card:hover {
      transform: translateY(-3px) scale(1.01) !important;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.40) !important, 0 12px 36px rgba(0, 0, 0, 0.30) !important, 0 24px 72px rgba(0, 0, 0, 0.20) !important;
    }

    /* Image containers */
    .lg\:col-span-1 .sidebar-image {
      border-radius: 20px 20px 0 0 !important;
      margin: 0 !important;
      background-size: cover !important;
      background-position: center !important;
      overflow: hidden !important;
    }

    /* Parent container that holds all post cards */
    .lg\:col-span-1 .bg-gradient-to-br.from-pink-50.to-rose-50.rounded-xl {
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important, 0 16px 48px rgba(0, 0, 0, 0.10) !important;
      border-radius: 20px !important;
    }

    /* Cards container */
    .lg\:col-span-1 .space-y-2.flex-grow > * {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.20) !important;
      border-radius: 20px !important;
    }

    /* ALL DIVIDER CSS REMOVED */

    /* Section title styling */
    .section-title-enhanced {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
      padding: 8px 24px;
      border-radius: 50px;
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* ===== EPCL CAROUSEL STYLES - EXACT TEMPLATE MATCH ===== */
    .epcl-carousel {
      position: relative;
      margin: 2rem 0;
      padding: 0;
      overflow: hidden;
    }

    .epcl-carousel-container {
      position: relative;
      overflow: hidden;
      width: 100%;
    }

    .epcl-carousel-track {
      display: flex;
      transition: transform 0.5s ease;
      gap: 1.5rem;
    }

    .epcl-carousel .item {
      flex: 0 0 calc(20% - 1.2rem);
      position: relative;
    }

    .epcl-carousel article {
      position: relative;
      height: 350px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      background: #ffffff;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .epcl-carousel article:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .epcl-carousel .img.cover {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-size: cover !important;
      background-position: center !important;
      background-repeat: no-repeat !important;
      z-index: 1;
    }

    .epcl-carousel .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
      z-index: 2;
    }

    .epcl-carousel .info {
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      transform: translateY(-50%);
      text-align: center;
      z-index: 3;
      padding: 1rem;
    }

    .epcl-carousel .info time {
      display: block;
      color: rgba(255, 255, 255, 0.9);
      font-size: 0.7rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 0.5rem;
    }

    .epcl-carousel .title.white {
      color: #ffffff;
      font-size: 1rem;
      font-weight: 700;
      line-height: 1.3;
      margin: 0;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    .epcl-carousel .author-meta {
      position: absolute;
      bottom: 0.75rem;
      left: 0.75rem;
      right: 0.75rem;
      z-index: 3;
    }

    .epcl-carousel .author-meta a {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #ffffff;
      text-decoration: none;
      transition: opacity 0.3s ease;
    }

    .epcl-carousel .author-meta a:hover {
      opacity: 0.8;
    }

    .epcl-carousel .author-image.cover {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background-size: cover;
      background-position: center;
      border: 1px solid rgba(255, 255, 255, 0.3);
      flex-shrink: 0;
    }

    .epcl-carousel .author-name {
      font-size: 0.75rem;
      font-weight: 500;
      color: rgba(255, 255, 255, 0.95);
    }

    .epcl-carousel .full-link {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 4;
      text-decoration: none;
    }

    .epcl-carousel .clear {
      clear: both;
    }

    /* Navigation Arrows - Red Template Style */
    .epcl-carousel-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 40px;
      height: 40px;
      background: #FF3152;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      box-shadow: 0 2px 10px rgba(255, 49, 82, 0.3);
      outline: none;
    }

    .epcl-carousel-nav:hover:not(:disabled) {
      background: #e02946;
      transform: translateY(-50%) scale(1.1);
      box-shadow: 0 4px 15px rgba(255, 49, 82, 0.4);
    }

    .epcl-carousel-nav:disabled {
      opacity: 0.5;
      cursor: not-allowed;
      background: #cccccc;
    }

    .epcl-carousel-nav.prev {
      left: -20px;
    }

    .epcl-carousel-nav.next {
      right: -20px;
    }

    .epcl-carousel-nav svg {
      width: 16px;
      height: 16px;
      fill: #ffffff;
      pointer-events: none;
    }

    /* Responsive adjustments */
    @media (max-width: 1200px) {
      .epcl-carousel .item {
        flex: 0 0 calc(25% - 1.125rem);
      }
    }

    @media (max-width: 768px) {
      .epcl-carousel .item {
        flex: 0 0 calc(50% - 0.75rem);
      }

      .epcl-carousel article {
        height: 250px;
      }

      .epcl-carousel .title.white {
        font-size: 0.9rem;
      }

      .epcl-carousel .info {
        padding: 0.75rem;
      }

      .epcl-carousel-nav {
        width: 35px;
        height: 35px;
      }

      .epcl-carousel-nav.prev {
        left: -15px;
      }

      .epcl-carousel-nav.next {
        right: -15px;
      }
    }

    @media (max-width: 480px) {
      .epcl-carousel .item {
        flex: 0 0 calc(100% - 0.5rem);
      }

      .epcl-carousel article {
        height: 220px;
      }
    }
    /* ===== END EPCL CAROUSEL STYLES ===== */
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