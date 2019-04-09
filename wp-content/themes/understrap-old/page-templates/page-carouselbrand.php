<?php
/**
 * Template Name: Brand Carousel
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published
 *
 * @package understrap
 */

get_header(); ?>

  <?php if ( is_active_sidebar( 'hero')) : ?>

  <?php get_template_part( 'hero' ); ?>

<?php endif; ?>

<div class="wrapper" id="page-wrapper">

    <div  id="content" class="container">

	   <div id="primary" class="col-sm-12 col-md-8 col-md-offset-2 content-area">

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

<!-- Modal -->
      <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h2 class="modal-title" id="myModalLabel">Subscribe for Special Offers</h2>
            </div>
            <div class="modal-body">
              <?php echo do_shortcode('[contact-form-7 id="2881" title="Brand Page Optin"]'); ?>




            </div>
          </div>
        </div>
      </div>
<!-- End Modal -->

<?php get_footer(); ?>
