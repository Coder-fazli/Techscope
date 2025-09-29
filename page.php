<?php get_header(); ?>

<div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <!-- Page Content -->
    <div class="max-w-4xl mx-auto">

        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 mb-6">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                <?php the_title(); ?>
            </h1>

            <?php if (has_post_thumbnail()) : ?>
                <div class="w-full h-64 md:h-96 rounded-lg overflow-hidden mb-6">
                    <?php the_post_thumbnail('full', array('class' => 'w-full h-full object-cover')); ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Page Content -->
        <div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
            <div class="prose prose-lg max-w-none page-content">
                <?php the_content(); ?>
            </div>

            <style>
                /* Gutenberg Block Styles for Pages */
                .page-content ul {
                    margin: 1rem 0;
                    padding-left: 2rem;
                    list-style-type: disc;
                    list-style-position: outside;
                }

                .page-content ol {
                    margin: 1rem 0;
                    padding-left: 2rem;
                    list-style-type: decimal;
                    list-style-position: outside;
                }

                .page-content li {
                    margin: 0.5rem 0;
                    color: #4b5563;
                    display: list-item;
                }

                .page-content h2 {
                    font-size: 1.75rem;
                    font-weight: 700;
                    margin: 2rem 0 1rem 0;
                    color: #1f2937;
                }

                .page-content h3 {
                    font-size: 1.5rem;
                    font-weight: 600;
                    margin: 1.5rem 0 0.75rem 0;
                    color: #374151;
                }

                .page-content p {
                    margin: 1rem 0;
                    line-height: 1.75;
                    color: #4b5563;
                }

                .page-content blockquote {
                    border-left: 4px solid #f97316;
                    background: #fff7ed;
                    padding: 1rem 1.5rem;
                    margin: 1.5rem 0;
                    font-style: italic;
                    color: #1e293b;
                }

                .page-content a {
                    color: #f97316;
                    text-decoration: underline;
                }

                .page-content a:hover {
                    color: #ea580c;
                }

                .page-content strong {
                    font-weight: 700;
                    color: #1f2937;
                }

                .page-content em {
                    font-style: italic;
                }

                .page-content code {
                    background: #f3f4f6;
                    padding: 0.2rem 0.4rem;
                    border-radius: 0.25rem;
                    font-family: monospace;
                    font-size: 0.9em;
                }

                .page-content pre {
                    background: #1f2937;
                    color: #f3f4f6;
                    padding: 1rem;
                    border-radius: 0.5rem;
                    overflow-x: auto;
                    margin: 1rem 0;
                }

                .page-content img {
                    max-width: 100%;
                    height: auto;
                    border-radius: 0.5rem;
                    margin: 1rem 0;
                }

                .page-content table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 1rem 0;
                }

                .page-content th,
                .page-content td {
                    border: 1px solid #e5e7eb;
                    padding: 0.5rem 1rem;
                    text-align: left;
                }

                .page-content th {
                    background: #f9fafb;
                    font-weight: 600;
                }
            </style>

            <?php
            wp_link_pages(array(
                'before' => '<div class="page-links mt-8 pt-8 border-t border-gray-200">' . __('Pages:', 'techscope'),
                'after'  => '</div>',
            ));
            ?>
        </div>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            ?>
            <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 mt-6">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>

    </div>

    <?php endwhile; endif; ?>

</div>

<?php get_footer(); ?>