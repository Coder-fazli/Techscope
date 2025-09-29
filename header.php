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

  <!-- Preload Critical CSS Resources -->
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" as="style">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" as="style">
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" as="style">

  <!-- Slick Slider Assets -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

  <!-- FontAwesome for arrows -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- EPCL Carousel Styles -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/epcl-carousel.css?v=<?php echo time(); ?>">

  <!-- Header Component Styles -->
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/header-styles.css?v=<?php echo time(); ?>">

  <?php wp_head(); ?>

  <!-- CRITICAL CSS TO PREVENT FOUC - MUST STAY INLINE -->
  <style>
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background-color: #F3F4F6;
      margin: 0;
      padding: 0;
      visibility: visible !important;
    }

    /* Navigation Critical Styles */
    nav {
      position: sticky;
      top: 0;
      z-index: 50;
      margin-top: 0.5rem;
    }

    nav > div {
      max-width: 100%;
      margin-left: auto;
      margin-right: auto;
      padding-left: 0.75rem;
      padding-right: 0.75rem;
    }

    nav .bg-white {
      background-color: white;
      border-radius: 0.5rem;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      border: 1px solid #E5E7EB;
    }

    /* Mobile header critical layout */
    nav .flex {
      display: flex;
    }

    nav .items-center {
      align-items: center;
    }

    nav .justify-between {
      justify-content: space-between;
    }

    nav .justify-center {
      justify-content: center;
    }

    nav .gap-2 {
      gap: 0.5rem;
    }

    nav .p-4 {
      padding: 1rem;
    }

    nav .hidden {
      display: none;
    }

    /* Desktop menu visibility */
    @media (min-width: 768px) {
      nav {
        margin-top: 1rem;
      }

      nav > div {
        padding-left: 1rem;
        padding-right: 1rem;
      }

      nav .md\:flex {
        display: flex !important;
      }

      nav .md\:hidden {
        display: none !important;
      }
    }

    @media (min-width: 1024px) {
      nav > div {
        max-width: 80rem;
      }
    }

    .max-w-7xl {
      max-width: 80rem;
      margin-left: auto;
      margin-right: auto;
    }

    .bg-white {
      background-color: white;
    }

    .rounded-lg {
      border-radius: 0.5rem;
    }

    .flex-wrap {
      flex-wrap: wrap;
    }

    .border-b {
      border-bottom-width: 1px;
    }

    .border-gray-100 {
      border-color: #F3F4F6;
    }

    /* ===== LOADING SCREEN ===== */
    #page-loader {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #f8f9fa 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    #page-loader.loaded {
      opacity: 0;
      visibility: hidden;
    }

    .loader-icon {
      position: relative;
      width: 80px;
      height: 80px;
    }

    /* Animated tech icon - CPU/chip style */
    .tech-icon {
      width: 60px;
      height: 60px;
      border: 3px solid #6366f1;
      border-radius: 8px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: pulse 1.5s ease-in-out infinite;
    }

    .tech-icon::before,
    .tech-icon::after {
      content: '';
      position: absolute;
      background: #6366f1;
    }

    /* Horizontal lines (chip pins) */
    .tech-icon::before {
      width: 20px;
      height: 3px;
      left: -23px;
      top: 50%;
      transform: translateY(-50%);
      box-shadow: 0 -15px 0 #6366f1, 0 15px 0 #6366f1;
    }

    .tech-icon::after {
      width: 20px;
      height: 3px;
      right: -23px;
      top: 50%;
      transform: translateY(-50%);
      box-shadow: 0 -15px 0 #6366f1, 0 15px 0 #6366f1;
    }

    /* Inner chip detail */
    .tech-icon-inner {
      position: absolute;
      width: 30px;
      height: 30px;
      border: 2px solid #6366f1;
      border-radius: 4px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: spin 2s linear infinite;
    }

    @keyframes pulse {
      0%, 100% {
        transform: translate(-50%, -50%) scale(1);
        border-color: #6366f1;
      }
      50% {
        transform: translate(-50%, -50%) scale(1.1);
        border-color: #818cf8;
      }
    }

    @keyframes spin {
      from {
        transform: translate(-50%, -50%) rotate(0deg);
      }
      to {
        transform: translate(-50%, -50%) rotate(360deg);
      }
    }
  </style>
</head>
<body <?php body_class('bg-gray-100 font-inter'); ?>>

<!-- Page Loader -->
<div id="page-loader">
  <div class="loader-icon">
    <div class="tech-icon">
      <div class="tech-icon-inner"></div>
    </div>
  </div>
</div>

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
            <span class="material-icons text-orange-600">phone_iphone</span>
            <span class="text-orange-600 font-bold text-lg"><?php bloginfo('name'); ?></span>
          <?php endif; ?>
        </div>
        <button id="mobile-menu-btn" class="text-amber-700 hover:text-orange-600 transition-colors">
          <span class="material-icons text-2xl">menu</span>
        </button>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex flex-wrap items-center justify-between gap-2 sm:gap-4 md:gap-8 p-4 border-b border-gray-100">
        <!-- Logo on Desktop -->
        <div class="flex items-center gap-2">
          <?php if (has_custom_logo()) : ?>
            <?php the_custom_logo(); ?>
          <?php else : ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-2">
              <span class="material-icons text-orange-600 text-2xl">smartphone</span>
              <span class="text-orange-600 font-bold text-xl"><?php bloginfo('name'); ?></span>
            </a>
          <?php endif; ?>
        </div>

        <!-- Navigation Menu -->
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