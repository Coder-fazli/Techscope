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
    }

    .article-content li {
        margin: 0.5rem 0;
        color: #4b5563;
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
                <nav class="section-animate">
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

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="relative mb-6">
                        <div class="w-full h-[300px] sm:h-[400px] md:h-[500px] tech-img rounded-lg lg:rounded-xl overflow-hidden" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>')">
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- ARTICLE META -->
                    <div class="bg-white rounded-lg p-4 md:p-6 shadow-sm mt-4">
                        <!-- Complete metadata in single line -->
                        <div class="flex flex-wrap items-center gap-3 sm:gap-6 text-sm mb-6 pb-6 border-b border-gray-100">
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-blue-600">calendar_today</span>
                                <span><?php echo get_the_date('M j, Y'); ?></span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-green-600">schedule</span>
                                <span><?php echo do_shortcode('[rt_reading_time label="" postfix="min read"]'); ?></span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-purple-600">visibility</span>
                                <span><?php if (function_exists('pvc_get_post_views')) echo pvc_get_post_views(); else echo '0'; ?> views</span>
                            </span>
                            <span class="flex items-center gap-2 text-gray-700 font-medium">
                                <span class="material-icons text-orange-600">person</span>
                                <span><?php the_author(); ?></span>
                            </span>
                        </div>

                        <!-- Social Share -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-gray-700">Share:</span>
                                <button class="p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors" onclick="navigator.share ? navigator.share({title: '<?php echo esc_js(get_the_title()); ?>', url: '<?php echo esc_js(get_permalink()); ?>'}) : window.open('https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>', '_blank')">
                                    <span class="material-icons text-lg">share</span>
                                </button>
                                <button class="p-2 bg-green-600 text-white rounded-full hover:bg-green-700 transition-colors">
                                    <span class="material-icons text-lg">bookmark</span>
                                </button>
                                <button class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors">
                                    <span class="material-icons text-lg">favorite</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-orange-500 font-semibold">🔥 <?php echo get_comments_number(); ?>K</span>
                                <span class="text-yellow-500 font-semibold">⭐ 4.9</span>
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
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="w-full h-48 tech-img" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>')"></div>
                            <?php endif; ?>
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
            <div class="lg:col-span-1 space-y-4 lg:space-y-6 section-animate stagger-2">

                <!-- AUTHOR INFO -->
                <div class="bg-white rounded-lg p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-blue-500">👤</span> About the Author
                    </h3>
                    <div class="text-center">
                        <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                            <?php echo strtoupper(substr(get_the_author(), 0, 2)); ?>
                        </div>
                        <h4 class="font-bold text-lg mb-2"><?php the_author(); ?></h4>
                        <p class="text-sm text-gray-600 mb-4"><?php echo get_the_author_meta('description'); ?></p>
                        <div class="flex justify-center gap-3">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">Follow</button>
                            <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">Message</button>
                        </div>
                    </div>
                </div>

                <!-- TRENDING NOW -->
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="text-orange-500">🔥</span> Trending Now
                    </h3>
                    <div class="space-y-4">
                        <?php
                        $trending_posts = get_posts(array(
                            'meta_key' => 'post_views_count',
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'numberposts' => 3
                        ));
                        foreach ($trending_posts as $post) : setup_postdata($post);
                        ?>
                        <div class="flex gap-3">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="w-16 h-16 tech-img rounded" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')); ?>')"></div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <h4 class="font-semibold text-sm mb-1">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-blue-600 transition-colors"><?php the_title(); ?></a>
                                </h4>
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <span><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago'; ?></span>
                                    <span class="text-orange-500">🔥 <?php echo rand(1, 15); ?>.<?php echo rand(1, 9); ?>K</span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </div>

                <!-- NEWSLETTER -->
                <div class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg p-6">
                    <h3 class="font-bold text-blue-800 mb-4">📧 Stay Updated</h3>
                    <p class="text-sm text-blue-700 mb-4">Get the latest tech news and AI insights delivered to your inbox weekly.</p>
                    <div class="space-y-3">
                        <input type="email" placeholder="Enter your email" class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:outline-none focus:border-blue-500">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition-colors">Subscribe Now</button>
                    </div>
                    <p class="text-xs text-blue-600 mt-3">Join 142K+ tech enthusiasts</p>
                </div>

                <!-- AD SPACE -->
                <div class="bg-gray-100 rounded-lg p-6 text-center">
                    <div class="text-gray-500 mb-2">Advertisement</div>
                    <div class="w-full h-32 bg-gray-200 rounded flex items-center justify-center text-gray-400">
                        <span class="material-icons text-4xl">image</span>
                    </div>
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
        });
    </script>

</body>

<?php get_footer(); ?>