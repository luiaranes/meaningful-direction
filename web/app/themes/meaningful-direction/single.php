<?php get_header(); ?>
<div class="container">
    <div class="columns">

        <div class="column">


            <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <!-- post thumbnail -->
                        <?php if (has_post_thumbnail()) : // Check if Thumbnail exists 
                        ?>
                            <?php the_post_thumbnail(); // Fullsize image for the single post 
                            ?>
                        <?php endif; ?>
                        <!-- /post thumbnail -->

                        <!-- post title -->
                        <h1>
                            <?php the_title(); ?>
                        </h1>
                        <!-- /post title -->

                        <!-- post details -->
                        <span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
                        <!-- /post details -->

                        <?php the_content(); // Dynamic Content 
                        ?>

                        <?php the_tags(__('Tags: ', 'sperling'), ', ', '<br>'); // Separated by commas with a line break at the end 
                        ?>

                        <p><?php _e('Categorised in: ', 'sperling');
                            the_category(', '); // Separated by commas 
                            ?></p>

                    </article>
                    <!-- /article -->

                <?php endwhile; ?>

            <?php else: ?>

                <!-- article -->
                <article>

                    <h1><?php _e('Sorry, nothing to display.', 'sperling'); ?></h1>

                </article>
                <!-- /article -->

            <?php endif; ?>
        </div>

        <?php //get_sidebar(); 
        ?>

    </div>
</div>
<?php get_footer(); ?>