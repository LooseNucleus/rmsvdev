<?php
/**
 * Template Name: Thank You Page
 *
 * Template for displaying a thank you page with the locations section.
 *
 * @package understrap
 */

get_header(); ?>

<div class="wrapper" id="page-wrapper">

    <div  id="content" class="container">

	   <div id="primary" class="col-md-12 content-area">

            <main id="main" class="site-main" role="main">

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'loop-templates/content', 'page' ); ?>

                    <?php
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || get_comments_number() ) :

                            comments_template();

                        endif;
                    ?>

                <?php endwhile; // end of the loop. ?>

            </main><!-- #main -->

	    </div><!-- #primary -->

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_template_part('content','locations'); ?>

<?php get_footer(); ?>
