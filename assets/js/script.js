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

  // --- Hero slider ---
  let currentSlide = 0;
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('.hero-dot');
  const totalSlides = slides.length;

  function showSlide(index) {
    if (!totalSlides) return;
    const i = ((index % totalSlides) + totalSlides) % totalSlides; // safe mod
    slides.forEach((s) => s.classList.remove('active'));
    dots.forEach((d) => d.classList.remove('active'));
    slides[i].classList.add('active');
    if (dots[i]) dots[i].classList.add('active');
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % (totalSlides || 1);
    showSlide(currentSlide);
  }

  if (totalSlides) {
    showSlide(0);
    // Auto-slide every 5s
    const intervalMs = 5000;
    let interval = setInterval(nextSlide, intervalMs);

    // Optional: pause on hover over slider area
    const slider = document.querySelector('.hero-slider');
    if (slider) {
      slider.addEventListener('mouseenter', () => clearInterval(interval));
      slider.addEventListener('mouseleave', () => {
        clearInterval(interval);
        interval = setInterval(nextSlide, intervalMs);
      });
    }

    // Dot navigation
    dots.forEach((dot, index) =>
      dot.addEventListener('click', () => {
        currentSlide = index;
        showSlide(currentSlide);
      })
    );
  }

  // --- Section reveal animations (REMOVED - CAUSED SCROLL ISSUES) ---
  // The IntersectionObserver was causing dynamic scroll bar behavior
  // Simply show all sections immediately instead of animating on scroll
  document
    .querySelectorAll('.section-animate')
    .forEach((section) => section.classList.add('visible'));

  // --- Fast loading: swap skeleton -> main content ---
  function showMainContent() {
    const loading = document.getElementById('loading-content');
    const main = document.getElementById('main-content');

    if (!loading || !main) {
      console.warn('Loading elements not found');
      return;
    }

    // Quick transition to show content immediately
    loading.style.opacity = '0';
    loading.style.transition = 'opacity 0.2s ease';

    setTimeout(() => {
      loading.style.display = 'none';
      main.classList.remove('hidden');
      main.style.opacity = '0';
      main.style.transition = 'opacity 0.3s ease';

      // Force reflow
      main.offsetHeight;

      // Show main content
      main.style.opacity = '1';

      // Reset body height calculation
      document.body.style.minHeight = 'auto';

      console.log('Main content loaded successfully');
    }, 200);
  }

  // Show content faster - reduced from 800ms to 300ms
  setTimeout(showMainContent, 300);

  // Fallback: if content still hidden after 1 second, force show it
  setTimeout(() => {
    const main = document.getElementById('main-content');
    if (main && main.classList.contains('hidden')) {
      console.warn('Fallback: Force showing main content');
      showMainContent();
    }
  }, 1000);
});
