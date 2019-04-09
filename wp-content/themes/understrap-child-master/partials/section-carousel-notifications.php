<section id="blog-posts" class="shadow-1">
  <div class="top-border"></div>
<div class="container">
  <div class="section-header">
    <h2>What's New at RMSV</h2>
  </div>
    <div id="carouselExampleControls" class="carousel slide carousel-height" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active" id="carousel-example-generic">
        <div class="row align-items-center" id="blog-post-homepage">
    <div class="col-lg-6 my-auto">
      <div class="my-auto border rounded overlap-content shadow-1">
      <div class="hide-small">
        <h4><a href="<?php echo site_url( '/blog/' ); ?>">Sewing and Vacuum Blog</a></h4>
        <p class="large-text">Learn about our specials, get sewing tips, and read about the latest sewing and vacuum machines. Here's our latest post...</p>
      </div>
      <?php $the_query = new WP_Query( 'posts_per_page=1' ); ?>


        <?php while ($the_query -> have_posts()) : $the_query -> the_post();
        $post_id = get_the_ID();
        $blogImage = get_field('blog_image', $post_id);
        $newBlogImage = get_field('new_blog_image', $post_id);
        $size = 'blog_image';

         ?>




        <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
        <div class="post-excerpt-home">
      		<?php the_excerpt(); ?>
      	</div><!-- post-excerpt -->

    </div>
  </div>

    <div class="col-lg-6 my-auto">
      <a href="<?php the_permalink() ?>"><?php
      if ($blogImage) {
        echo wp_get_attachment_image($blogImage, $size,"",["class" => 'rounded border overlap-image shadow-1']);
      } else if ($newBlogImage) {
        echo wp_get_attachment_image($newBlogImage, $size,"",["class" => 'rounded border overlap-image shadow-1']);
      } ?></a>

    </div>

            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
  </div>
  <?php if (have_rows('notification_carousel')):
    while ( have_rows('notification_carousel')) : the_row();

    $notificationHeadline = get_sub_field('notification_headline');
    $notificationTypeText = get_sub_field('notification_type_text');
    $notificationType = get_sub_field('notification_type');
    $notificationText = get_sub_field('notification_text');
    $notificationImage = get_sub_field('notification_image');
    $notificationImageURL = $notificationImage['url'];
    $newNotificationImage = get_sub_field('new_notification_image');
    $newNotificationImageURL = $newNotificationImage['url'];
    $notificationButton = get_sub_field('notification_button');
    $notificationLink = get_sub_field('notification_link');
    ?>
    <div class="carousel-item" id="carousel-example-generic">
        <div class="row align-items-center" id="blog-post-homepage">
    <div class="col-lg-6">

      <div class="my-auto border rounded overlap-content shadow-1">

            <h3><?php echo $notificationType; ?></h3>
            <p><?php echo $notificationTypeText; ?></p>
            <h4><a href="<?php echo $notificationLink ?>"><?php echo $notificationHeadline; ?></a></h4>
            <div class="hide-small">
            <p><?php echo $notificationText; ?></p>
                </div>

              <div class="fade-anchor">

            </div>
          <div class="fixed-bottom-3rem">
            <a class="btn btn-secondary" href="<?php echo $notificationLink; ?>" role="button"><?php echo $notificationButton; ?></a>
          </div>

  </div>
  </div>

    <div class="col-lg-6">
        <a href="<?php echo $notificationLink; ?>"><img class="rounded border overlap-image shadow-1" src="<?php
        if ($notificationImageURL) {
         echo $notificationImageURL;
       } else if ($newNotificationImageURL) {
         echo $newNotificationImageURL;
       } ?>"></a>
    </div>


    </div>
  </div>
  <?php
endwhile;
endif;
?>

<div class="controls-block">
  <div class="controls">
<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  <span class="sr-only">Previous</span>
</a>
<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
  <span class="carousel-control-next-icon" aria-hidden="true"></span>
  <span class="sr-only">Next</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="bottom-border"></div>
</section>
