/*!
 * EPCL Carousel JavaScript
 * Initialize Slick Slider for EPCL Carousel
 */

jQuery(document).ready(function($) {
  // Check if carousel exists on page
  if ($('.epcl-carousel').length) {

    // Initialize EPCL Carousel with exact template settings
    $('.epcl-carousel').slick({
      infinite: true,
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 3000,
      arrows: true,
      dots: false,
      centerMode: false,
      variableWidth: false,
      prevArrow: '<button type="button" class="slick-prev"></button>',
      nextArrow: '<button type="button" class="slick-next"></button>',
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });

    // Add custom navigation arrow behavior
    $('.epcl-carousel .slick-arrow').on('mouseenter', function() {
      $(this).addClass('hover-effect');
    }).on('mouseleave', function() {
      $(this).removeClass('hover-effect');
    });

    // Debug: Log when carousel is initialized
    console.log('EPCL Carousel initialized successfully');
  }
});