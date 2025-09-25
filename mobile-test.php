<?php
/**
 * Mobile Horizontal Scroll Diagnostic Test Page
 * Template Name: Mobile Test
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobile Scroll Test - <?php bloginfo('name'); ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    /* Test styles */
    * { box-sizing: border-box; }
    html, body { overflow-x: hidden; width: 100%; margin: 0; padding: 0; }

    .test-section {
      border: 2px solid #e5e5e5;
      margin: 10px 0;
      padding: 10px;
      background: #f9f9f9;
    }

    .overflow-detector {
      position: fixed;
      top: 10px;
      right: 10px;
      background: red;
      color: white;
      padding: 5px;
      z-index: 9999;
      font-size: 12px;
      border-radius: 3px;
    }

    /* Copy current theme mobile styles */
    @media (max-width: 640px) {
      * { max-width: 100vw !important; }
      body, html { width: 100vw !important; overflow-x: hidden !important; }
      nav, header, main, section, footer, div { max-width: 100vw !important; }
      .material-icons { flex-shrink: 0; max-width: 24px; }
    }
  </style>
</head>
<body class="bg-gray-100">

<div class="overflow-detector" id="overflow-detector">
  Checking overflow...
</div>

<div class="max-w-full mx-auto px-4 py-4">

  <!-- TEST 1: Simple Navigation Mock -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 1: Navigation</h3>
    <nav class="sticky top-0 z-50 mt-4">
      <div class="max-w-full lg:max-w-7xl mx-auto px-3 sm:px-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <div class="flex items-center justify-between p-4">
            <div class="flex items-center gap-2">
              <span class="material-icons text-blue-600">phone_iphone</span>
              <span class="text-blue-600 font-bold text-lg"><?php bloginfo('name'); ?></span>
            </div>
            <button class="text-gray-600">
              <span class="material-icons text-2xl">menu</span>
            </button>
          </div>
        </div>
      </div>
    </nav>
  </div>

  <!-- TEST 2: Grid Layout -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 2: Grid Layout</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded">Item 1</div>
      <div class="bg-white p-4 rounded">Item 2</div>
      <div class="bg-white p-4 rounded">Item 3</div>
      <div class="bg-white p-4 rounded">Item 4</div>
    </div>
  </div>

  <!-- TEST 3: Hero Slider Mock -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 3: Hero Slider</h3>
    <div class="w-full h-64 bg-blue-500 rounded-lg relative overflow-hidden">
      <div class="absolute inset-0 flex items-end p-4">
        <div class="bg-black bg-opacity-70 text-white p-4 rounded">
          <h2 class="text-xl font-bold">Test Hero Title That Could Be Long</h2>
          <p class="text-sm">Test description text</p>
        </div>
      </div>
    </div>
  </div>

  <!-- TEST 4: Long Text Content -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 4: Long Text</h3>
    <div class="bg-white p-4 rounded">
      <h4 class="font-bold text-lg">VeryLongTextWithoutSpacesToTestOverflowBehaviorOnMobileDevices</h4>
      <p class="text-sm">This is a test paragraph with some very long words: supercalifragilisticexpialidociousandextremelylongwordsthatmightcauseoverflowissues</p>
    </div>
  </div>

  <!-- TEST 5: Flex Layout -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 5: Flex Layout</h3>
    <div class="flex items-center gap-4 bg-white p-4 rounded">
      <div class="w-12 h-12 bg-red-500 rounded flex-shrink-0"></div>
      <div class="flex-1">
        <h5 class="font-bold">Flexible content area</h5>
        <p class="text-sm">Content that should wrap properly</p>
      </div>
      <button class="bg-blue-500 text-white px-4 py-2 rounded">Button</button>
    </div>
  </div>

  <!-- TEST 6: Form Elements -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 6: Forms</h3>
    <form class="bg-white p-4 rounded space-y-3">
      <input type="text" placeholder="Test input field" class="w-full px-4 py-2 border rounded">
      <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded">Submit Button</button>
    </form>
  </div>

  <!-- TEST 7: Footer Mock -->
  <div class="test-section">
    <h3 class="font-bold mb-2">TEST 7: Footer</h3>
    <footer class="bg-gray-900 text-white p-4 rounded">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
          <h4 class="font-bold text-blue-400 mb-2">Site Name</h4>
          <div class="flex space-x-3">
            <span class="material-icons">facebook</span>
            <span class="material-icons">twitter</span>
            <span class="material-icons">instagram</span>
          </div>
        </div>
        <div>
          <h4 class="font-bold text-blue-400 mb-2">Links</h4>
          <p class="text-sm">Test footer content</p>
        </div>
      </div>
    </footer>
  </div>

</div>

<script>
// Overflow Detection Script
function detectOverflow() {
  const body = document.body;
  const html = document.documentElement;
  const detector = document.getElementById('overflow-detector');

  const bodyWidth = body.scrollWidth;
  const htmlWidth = html.scrollWidth;
  const viewportWidth = window.innerWidth;

  const hasOverflow = bodyWidth > viewportWidth || htmlWidth > viewportWidth;

  if (hasOverflow) {
    detector.style.background = 'red';
    detector.innerHTML = `OVERFLOW DETECTED!<br>Body: ${bodyWidth}px<br>HTML: ${htmlWidth}px<br>Viewport: ${viewportWidth}px`;
  } else {
    detector.style.background = 'green';
    detector.innerHTML = `NO OVERFLOW<br>Width: ${viewportWidth}px`;
  }

  // Find elements causing overflow
  if (hasOverflow) {
    const elements = document.querySelectorAll('*');
    const overflowElements = [];

    elements.forEach(el => {
      const rect = el.getBoundingClientRect();
      if (rect.right > viewportWidth) {
        overflowElements.push({
          element: el.tagName,
          class: el.className,
          width: rect.width,
          right: rect.right
        });
      }
    });

    if (overflowElements.length > 0) {
      console.log('Elements causing overflow:', overflowElements);
      detector.innerHTML += `<br>Check console for details`;
    }
  }
}

// Run detection
detectOverflow();
window.addEventListener('resize', detectOverflow);

// Re-run every 2 seconds to catch dynamic content
setInterval(detectOverflow, 2000);
</script>

</body>
</html>