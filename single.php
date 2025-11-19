<?php
get_header();

if( have_posts() ):
    while( have_posts() ): the_post(); ?>
        <main style="margin-top:60px;">
            <h1><?php the_title(); ?></h1>
            <?php the_post_thumbnail('large'); ?>
            <div class="post-content"><?php the_content(); ?></div>

            <?php
            // Display ACF field
            if( function_exists('get_field') ) {
                $my_field = get_field('my_custom_field'); 
                if( $my_field ) {
                    echo '<div class="acf-field">' . esc_html($my_field) . '</div>';
                }
            }
            ?>

            <div class="post-tags">
                <?php the_tags('', '  '); ?>
            </div>
        </main>
<?php
    endwhile;
endif;

get_footer();
