<?php get_header(); ?>

<style>
    .card-hover {
        transition: all 300ms ease-out;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .glass-effect-dark {
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .tech-img {
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .tech-badge {
        background: linear-gradient(45deg, #8b5cf6, #3b82f6);
    }

    /* Section Animations */
    .section-animate {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }

    .section-animate.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Staggered animations */
    .stagger-1 { transition-delay: 0.1s; }
    .stagger-2 { transition-delay: 0.2s; }
    .stagger-3 { transition-delay: 0.3s; }
    .stagger-4 { transition-delay: 0.4s; }

    /* Article Content Styling */
    .article-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 2rem 0 1rem 0;
        color: #1f2937;
    }

    .article-content h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 1.5rem 0 0.75rem 0;
        color: #374151;
    }

    .article-content p {
        margin: 1rem 0;
        line-height: 1.75;
        color: #4b5563;
    }

    .article-content blockquote {
        border-left: 4px solid #3b82f6;
        background: #f8fafc;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #1e293b;
    }

    .article-content ul {
        margin: 1rem 0;
        padding-left: 2rem;
        list-style-type: disc;
        list-style-position: outside;
    }

    .article-content ol {
        margin: 1rem 0;
        padding-left: 2rem;
        list-style-type: decimal;
        list-style-position: outside;
    }

    .article-content li {
        margin: 0.5rem 0;
        color: #4b5563;
        display: list-item;
    }

    /* Prevent horizontal scroll */
    * {
        box-sizing: border-box;
    }

    body {
        overflow-x: hidden;
    }

    /* Progress Bar */
    .progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        z-index: 9999;
        transition: width 0.3s ease;
    }
</style>

<body class="bg-gray-100 font-['Inter']">

    <!-- Progress Bar -->
    <div class="progress-bar" id="progress-bar"></div>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- MAIN LAYOUT -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">

            <!-- MAIN ARTICLE -->
            <div class="lg:col-span-2 space-y-6 lg:space-y-8">

                <!-- BREADCRUMBS -->
                <nav class="section-animate mt-4 relative z-40">
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <a href="<?php echo home_url(); ?>" class="hover:text-blue-600 transition-colors">Главная</a>
                        <span class="material-icons text-xs">chevron_right</span>
                        <?php
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="hover:text-blue-600 transition-colors">' . esc_html($categories[0]->name) . '</a>';
                        }
                        ?>
                        <span class="material-icons text-xs">chevron_right</span>
                        <span class="text-gray-800"><?php the_title(); ?></span>
                    </div>
                </nav>

                <!-- ARTICLE HERO -->
                <article class="section-animate stagger-1">
                    <!-- Article Title -->
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm mb-6">
                        <div class="tech-badge px-3 py-1 rounded-full text-xs uppercase tracking-widest font-semibold inline-block mb-4">🔥 TRENDING</div>
                        <h1 class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 leading-tight"><?php the_title(); ?></h1>
                    </div>

                    <!-- Featured Image - Enhanced with Fallback System -->
                    <div class="relative mb-6">
                        <div class="w-full h-[300px] sm:h-[400px] md:h-[500px] tech-img rounded-lg lg:rounded-xl overflow-hidden" style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'full'); ?>')">
                        </div>
                    </div>

                    <!-- ARTICLE META -->
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm mt-4">
                        <!-- Complete metadata in single line -->
                        <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-sm mb-6 pb-6 border-b border-gray-100">
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-orange-600">calendar_today</span>
                                <span><?php echo get_the_date('M j, Y'); ?></span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-green-600">schedule</span>
                                <span><?php echo techscope_reading_time(); ?></span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-orange-600">visibility</span>
                                <span><?php if (function_exists('pvc_get_post_views')) echo pvc_get_post_views(); else echo '0'; ?> views</span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-gray-600">person</span>
                                <span><?php the_author(); ?></span>
                            </span>
                        </div>

                        <!-- Social Share -->
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="text-sm font-medium text-gray-700">Share:</span>

                                <!-- Twitter/X -->
                                <button class="p-2.5 bg-black text-white rounded-lg hover:bg-gray-800 transition-all hover:scale-105 shadow-sm" onclick="window.open('https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>', '_blank', 'width=600,height=400')" title="Share on Twitter">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </button>

                                <!-- Facebook -->
                                <button class="p-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all hover:scale-105 shadow-sm" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>', '_blank', 'width=600,height=400')" title="Share on Facebook">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </button>

                                <!-- WhatsApp -->
                                <button class="p-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all hover:scale-105 shadow-sm" onclick="window.open('https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>', '_blank')" title="Share on WhatsApp">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </button>

                                <!-- Telegram -->
                                <button class="p-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-all hover:scale-105 shadow-sm" onclick="window.open('https://t.me/share/url?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>', '_blank')" title="Share on Telegram">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                                </button>

                                <!-- LinkedIn -->
                                <button class="p-2.5 bg-blue-700 text-white rounded-lg hover:bg-blue-800 transition-all hover:scale-105 shadow-sm" onclick="window.open('https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>', '_blank', 'width=600,height=400')" title="Share on LinkedIn">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </button>

                                <!-- Copy Link -->
                                <button class="p-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-all hover:scale-105 shadow-sm" onclick="navigator.clipboard.writeText('<?php echo esc_js(get_permalink()); ?>').then(() => alert('Link copied to clipboard!'))" title="Copy Link">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ARTICLE CONTENT -->
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm article-content">
                        <div class="prose prose-lg max-w-none">
                            <?php the_content(); ?>
                        </div>
                    </div>

                    <!-- TAGS -->
                    <?php if (has_tag()) : ?>
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-4">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $tags = get_the_tags();
                            $colors = ['blue', 'purple', 'green', 'orange', 'red', 'yellow', 'indigo'];
                            foreach ($tags as $index => $tag) {
                                $color = $colors[$index % count($colors)];
                                echo '<span class="bg-' . $color . '-100 text-' . $color . '-800 px-3 py-1 rounded-full text-sm font-medium">' . esc_html($tag->name) . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </article>

                <!-- COMMENTS SECTION -->
                <?php if (comments_open() || get_comments_number()) : ?>
                <section class="section-animate stagger-2">
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">💬 Comments (<?php echo get_comments_number(); ?>)</h3>
                        <?php comments_template(); ?>
                    </div>
                </section>
                <?php endif; ?>

                <!-- RELATED ARTICLES -->
                <section class="section-animate stagger-3">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">📖 Related Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php
                        $related_posts = get_posts(array(
                            'category__in' => wp_get_post_categories(get_the_ID()),
                            'numberposts'  => 2,
                            'post__not_in' => array(get_the_ID())
                        ));
                        foreach ($related_posts as $post) : setup_postdata($post);
                        ?>
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden card-hover">
                            <!-- Related Article Image - Enhanced with Fallback System -->
                            <div class="w-full h-48 tech-img" style="background-image: url('<?php echo techscope_ensure_image(get_the_ID(), 'medium'); ?>')"></div>
                            <div class="p-4">
                                <h4 class="font-bold text-lg mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors"><?php the_title(); ?></a>
                                </h4>
                                <p class="text-sm text-gray-600 mb-3"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500"><?php echo get_the_date('M j, Y'); ?></span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-orange-500">🔥 <?php echo rand(1, 10); ?>.<?php echo rand(1, 9); ?>K</span>
                                        <span class="text-yellow-500">⭐ 4.<?php echo rand(5, 9); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </section>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div class="lg:col-span-1 space-y-4 lg:space-y-6">
                <div class="techscope-sidebar-container lg:sticky lg:top-4">
                    <!-- DYNAMIC WIDGET AREA -->
                    <?php if (is_active_sidebar('single-post-sidebar')) : ?>
                        <?php dynamic_sidebar('single-post-sidebar'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php endwhile; endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Progress Bar
            const progressBar = document.getElementById('progress-bar');

            function updateProgressBar() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const documentHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const progress = (scrollTop / documentHeight) * 100;
                progressBar.style.width = progress + '%';
            }

            window.addEventListener('scroll', updateProgressBar);

            // Section Animations - Intersection Observer
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            // Observe all animated sections
            document.querySelectorAll('.section-animate').forEach(section => {
                observer.observe(section);
            });

            // Trigger initial animations
            setTimeout(() => {
                document.querySelectorAll('.section-animate').forEach((section, index) => {
                    setTimeout(() => {
                        if (section.getBoundingClientRect().top < window.innerHeight) {
                            section.classList.add('visible');
                        }
                    }, index * 100);
                });
            }, 300);

            // Smart fallback image system for single page - only replaces actually broken images
            function setupFallbackImages() {
                console.log('Setting up smart fallback image system for single page...');

                const processedImages = new Set();
                const elements = document.querySelectorAll('.tech-img[style*="background-image"]');

                elements.forEach(element => {
                    const style = element.getAttribute('style');
                    const urlMatch = style.match(/background-image:\s*url\(['"]?([^'"]+)['"]?\)/);

                    if (urlMatch) {
                        const imageUrl = urlMatch[1];

                        // Skip if already processed or if it's already our fallback image
                        if (processedImages.has(imageUrl) || imageUrl.includes('27002.jpg')) {
                            return;
                        }

                        processedImages.add(imageUrl);

                        const img = new Image();

                        img.onload = function() {
                            // Image loaded successfully, remove any error class
                            element.classList.remove('image-error');
                            console.log('Single page image loaded successfully:', imageUrl);
                        };

                        img.onerror = function() {
                            console.log('404 Error on single page - Failed to load image:', imageUrl);
                            // Only now replace with fallback image
                            const fallbackUrl = '<?php echo get_template_directory_uri(); ?>/27002.jpg';
                            element.style.backgroundImage = `url('${fallbackUrl}')`;
                            element.classList.add('image-error');
                        };

                        // Set a timeout for slow-loading images
                        setTimeout(() => {
                            if (!img.complete) {
                                console.log('Single page image loading timeout:', imageUrl);
                                img.onerror();
                            }
                        }, 5000);

                        img.src = imageUrl;
                    }
                });
            }

            // Run fallback image setup
            setupFallbackImages();

            // Re-check after content loads
            setTimeout(setupFallbackImages, 2000);
        });
    </script>

</body>

<?php get_footer(); ?>