<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package understrap
 */

get_header(); ?>

<div class="wrapper" id="woocommerce-wrapper">

    <div class="container">

	   <div id="primary" class="col-md-12 content-area">

            <main id="main" class="site-main" role="main">

                <!-- The WooCommerce loop -->
                <?php woocommerce_content(); ?>

            </main><!-- #main -->

	    </div><!-- #primary -->

    </div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
