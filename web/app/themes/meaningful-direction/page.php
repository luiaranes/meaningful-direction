<?php get_header(); ?>
<div class="container">
    <div class="columns">

        <div class="column">

            <h1><?php the_title(); ?></h1>

            <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php the_content(); ?>

                    </article>
                    <!-- /article -->

                <?php endwhile; ?>

            <?php else: ?>

                <!-- article -->
                <article>

                    <h2><?php _e('Sorry, nothing to display.', 'sperling'); ?></h2>

                </article>
                <!-- /article -->

            <?php endif; ?>


        </div>

        <?php // get_sidebar(); 
        ?>

    </div>
</div>
<?php get_footer(); ?>