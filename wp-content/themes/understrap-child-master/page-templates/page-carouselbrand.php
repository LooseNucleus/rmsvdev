<?php
/**
 * Template Name: Brand Carousel
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published
 *
 * @package understrap
 */

get_header(); ?>

<?php if ( is_active_sidebar( 'brand')) : ?>

<?php get_template_part( 'brand' ); ?>

<?php endif; ?>

<div class="wrapper" id="page-wrapper">

    <div  id="content" class="container">


	   <div id="primary" class="col-sm-12 col-md-8 offset-md-2 content-area">

            <main id="main" class="site-main" role="main">

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php get_template_part( 'loop-templates/content', 'brandpage' ); ?>

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



<?php get_footer(); ?>
