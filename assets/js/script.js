// script.js
document.addEventListener('DOMContentLoaded', () => {
  // --- Rating bar animations ---
  document.querySelectorAll('.rating-bar').forEach((bar) => {
    const rating = bar.style.getPropertyValue('--rating') || '0%';
    bar.style.setProperty('--rating', '0%');
    setTimeout(() => bar.style.setProperty('--rating', rating), 500);
  });

  // --- Navbar shadow on scroll ---
  const navbar = document.querySelector('nav');
  if (navbar) {
    const toggleShadow = () =>
      navbar.classList.toggle('shadow-xl', window.scrollY > 100);
    toggleShadow();
    window.addEventListener('scroll', toggleShadow);
  }

  // --- Mobile menu toggle ---
  const mobileMenuBtn = document.getElementById('mobile-menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
      const icon = mobileMenuBtn.querySelector('.material-icons');
      if (icon) {
        icon.textContent = mobileMenu.classList.contains('hidden')
          ? 'menu'
          : 'close';
        icon.setAttribute('aria-hidden', 'true');
      }
    });
  }

  // --- Hero slider with minimalistic navigation ---
  let currentSlide = 0;
  const slides = document.querySelectorAll('.hero-slide');
  const prevBtn = document.querySelector('.hero-prev');
  const nextBtn = document.querySelector('.hero-next');
  const totalSlides = slides.length;
  let isTransitioning = false;
  let slideInterval;

  console.log('🎬 Hero Slider Init:', totalSlides, 'slides found');
  console.log('🎯 Navigation buttons:', prevBtn ? 'prev ✓' : 'prev ✗', nextBtn ? 'next ✓' : 'next ✗');

  function showSlide(index) {
    if (!totalSlides || isTransitioning) return;

    isTransitioning = true;
    const i = ((index % totalSlides) + totalSlides) % totalSlides;
    slides.forEach((s) => s.classList.remove('active'));
    slides[i].classList.add('active');
    currentSlide = i;

    // Reset transition lock
    setTimeout(() => {
      isTransitioning = false;
    }, 1000);

    console.log('📍 Showing slide:', i);
  }

  function nextSlide() {
    const next = (currentSlide + 1) % totalSlides;
    showSlide(next);
  }

  function prevSlide() {
    const prev = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(prev);
  }

  function startSlider() {
    if (totalSlides > 1) {
      slideInterval = setInterval(nextSlide, 5000);
    }
  }

  function stopSlider() {
    clearInterval(slideInterval);
  }

  if (totalSlides > 0) {
    // Hide navigation if only one slide
    if (totalSlides <= 1) {
      const nav = document.querySelector('.hero-nav');
      if (nav) nav.style.display = 'none';
    }

    showSlide(0);
    startSlider();

    // Navigation button events
    if (nextBtn) {
      nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('▶️ Next button clicked');
        nextSlide();
        stopSlider();
        setTimeout(startSlider, 1000);
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('⏮️ Prev button clicked');
        prevSlide();
        stopSlider();
        setTimeout(startSlider, 1000);
      });
    }

    // Pause on hover
    const slider = document.querySelector('.hero-slider');
    if (slider) {
      slider.addEventListener('mouseenter', stopSlider);
      slider.addEventListener('mouseleave', startSlider);
    }

    // Touch/swipe support
    let startX = 0;
    let isDragging = false;

    if (slider) {
      slider.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        isDragging = true;
        stopSlider();
      });

      slider.addEventListener('touchmove', (e) => {
        if (!isDragging) return;
        e.preventDefault();
      });

      slider.addEventListener('touchend', (e) => {
        if (!isDragging) return;
        isDragging = false;

        const endX = e.changedTouches[0].clientX;
        const deltaX = startX - endX;

        if (Math.abs(deltaX) > 50) {
          if (deltaX > 0) {
            nextSlide();
          } else {
            prevSlide();
          }
        }

        setTimeout(startSlider, 1000);
      });
    }

    console.log('✅ Hero slider initialized with navigation');
  }

  // --- Section reveal animations (REMOVED - CAUSED SCROLL ISSUES) ---
  // The IntersectionObserver was causing dynamic scroll bar behavior
  // Simply show all sections immediately instead of animating on scroll
  document
    .querySelectorAll('.section-animate')
    .forEach((section) => section.classList.add('visible'));

  // --- Loading functionality removed from here ---
  // Loading is now handled in front-page.php inline script to avoid conflicts
});
