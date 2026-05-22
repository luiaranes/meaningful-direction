<?php get_header(); ?>
<div class="container">
    <div class="columns">

        <div class="column">

            <!-- article -->
            <article id="post-404">

                <h1><?php _e('Page not found', 'sperling'); ?></h1>
                <h2>
                    <a href="<?php echo home_url(); ?>"><?php _e('Return home?', 'sperling'); ?></a>
                </h2>

            </article>
            <!-- /article -->
        </div>

        <?php //get_sidebar(); 
        ?>

    </div>
</div>
<?php get_footer(); ?>