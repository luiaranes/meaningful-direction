<?php get_header(); ?>
<div class="container">
    <div class="columns">

        <div class="column">

            <h1><?php echo sprintf(__('%s Search Results for ', 'sperling'), $wp_query->found_posts);
                echo get_search_query(); ?></h1>

            <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <!-- post thumbnail -->
                        <?php if (has_post_thumbnail()) : // Check if thumbnail exists 
                        ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <?php the_post_thumbnail(array(120, 120)); // Declare pixel size you need inside the array 
                                ?>
                            </a>
                        <?php endif; ?>
                        <!-- /post thumbnail -->

                        <!-- post title -->
                        <h2>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <!-- /post title -->

                        <!-- post details -->
                        <span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
                        <!-- /post details -->

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


    </div>
</div>
<?php get_footer(); ?>