<section id="featured-slider">
  <div class="top-border"></div>
    <div class="container-fluid">
      <div class="section-header">
        <h2>Featured Products</h2>
      </div><!-- section-header -->
    <div class="row">
<?php
$args = array(
    'post_type' => 'product',
    'tax_query' => array(
      'relation' => 'OR',
      array(
          'taxonomy' => 'product_cat',
          'field' => 'slug',
          'terms' => 'featured'
      ),
    )
);

$featured_query = new WP_Query( $args ); ?>
<div id="slides" class="owl-carousel owl-theme">


  <?php while ($featured_query->have_posts()) : ?>


      <?php $featured_query->the_post(); ?>


        <div class="card bg-light text-center border-primary shadow-1">

            <div class="card-img-top" alt="Card image cap">
                <?php the_post_thumbnail( 'medium', array('class' => 'rounded')); ?>
            </div>
            <div class="card-body">
                <h4>
                  <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h4>
            </div>
            <div class="card-footer">
              <a href="<?php the_permalink(); ?>" class="btn btn-outline-cta">See It Now</a>
            </div>
        </div>


    <?php endwhile; ?>
</div>

<?php wp_reset_query(); ?>


</div>
</div><!-- end container -->
<div class="bottom-border"></div>
</section>
<!-- PRODUCT CARD CAROUSEL -->
