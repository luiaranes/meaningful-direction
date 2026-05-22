<?php get_header(); ?>
<div class="container">
    <div class="columns">

        <div class="column">


            <h1><?php _e('Latest Posts', 'sperling'); ?></h1>

            <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <a href='<?php the_permalink() ?>'>
                            <h2><?php the_title() ?></h2>
                        </a>
                        <?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php. 
                        ?>

                        <hr>

                        <br class="clear">

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



            <?php get_template_part('pagination'); ?>

        </div>

        <?php get_sidebar(); ?>

    </div>
</div>
<?php get_footer(); ?>