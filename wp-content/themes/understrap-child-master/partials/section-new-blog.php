<section id="blog-posts">
<div class="container">
  <div class="section-header">
    <h2>The New RMSV Blog</h2>
  </div>
  <div class="row" id="blog-post-homepage">
    <div class="col-lg-6">
      <div class="my-auto border rounded overlap-content shadow-1">
      <div class="hide-small">
        <h4><a href="<?php echo site_url( '/blog/' ); ?>">Sewing and Vacuum Blog</a></h4>
        <p class="large-text">Learn about our specials, get sewing tips, and read about the latest sewing and vacuum machines. Here's our latest post...</p>
      </div>
      <?php $the_query = new WP_Query( 'posts_per_page=1' ); ?>


        <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>




        <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
        <div class="post-excerpt-home">
      		<?php the_excerpt(); ?>
      	</div><!-- post-excerpt -->

    </div>
  </div>

    <div class="col-lg-6 my-auto">
        <a href="blog"><?php the_post_thumbnail('post-thumbnail', ['class' => 'rounded border overlap-image shadow-1']); ?></a>
    </div>

            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
  </div>
</section>
