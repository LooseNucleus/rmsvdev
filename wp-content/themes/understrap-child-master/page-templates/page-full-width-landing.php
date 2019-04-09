<?php
/*

Template Name: Full Width Landing Page

 */

 /*
 $location            = get_field('location');
 */

get_header(); ?>



<?php
  $heroImage = get_field('full_width_image');
  $size = 'full';
  // $heroImageURL = $heroImage['url'];
  // $heroMobile = get_field('mobile_portrait_image');
  // $heroMobileImage = $heroMobile['url'];

  ?>

<!-- HERO
================================================== -->
<section id="heroImage">
    <div class="container-fluid clearfix">
      <?php if($heroImage) {
        echo wp_get_attachment_image($heroImage, $size);
      }
      ?>



    </div><!-- container -->
</section>

<section id="pageContent">
  <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto my-auto">
          <div class="article">
          <?php the_content('Read More...') ?>
          </div>
        </div>
      </div>
    </div>
</section>

<!-- Blog posts -->


<!-- LOCATIONS SECTION -->

<?php //get_template_part('content','locations'); ?>

<?php //get_template_part('content', 'products'); ?>

<?php //get_template_part('content', 'classes'); ?>

<?php //get_template_part('content', 'blog'); ?>



<!-- PRODUCTS
================================================== -->




<?php get_footer(); ?>
