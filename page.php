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
            <div class="prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>

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