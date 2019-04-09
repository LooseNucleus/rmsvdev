<?php
/*

Template Name: Home Page

 */

 $location            = get_field('location');

get_header(); ?>

<!-- HERO
================================================== -->
<section id="hero" data-type="background" data-speed="5">
  <article>
    <div class="container clearfix">
      <div class="row">

        <div class="col-md-4 page-logo">
          <!--
          <img src="<?php bloginfo('stylesheet_directory'); ?>/assets/img/rmns-logo-blue-400x400.png" alt="Rocky Mountain Sewing & Vacuum" class="logo">
        -->
          <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2016/08/rocky-mountain-logo-blue-sm-1.svg" alt="Rocky Mountain Sewing & Vacuum" class="logo">

        </div><!-- col -->

        <div class="col-md-8 hero-text">
          <h1>Rocky Mountain Sewing &amp; Vacuum</h1>
              <h3>Sewing Fun Starts Here</h3>

              <div id="feature-blocks" class="featureHeights">

                <div class="price" data-key="featureHeights">
                  <h4>Locations<small>4 Colorado Locations</small></h4>
                  <p><a class="btn btn-lg btn-danger  js--scroll-to-locations" href="#" role="button">Your Store</a></p>

                </div><!-- end price -->

                <div class="price active" data-key="featureHeights">
                  <h4>Special Offers<small>Classes &amp; In-Store</small></h4>
                  <p><button type="button" class="btn btn-lg btn-info" href="#mailmunch-pop-506324">Save $$$</button></p>
                  <!-- Modal -->
                    <!-- <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h2 class="modal-title" id="myModalLabel">Subscribe for Special Offers</h2>
                          </div>
                          <div class="modal-body">





                          </div>
                        </div>
                      </div>
                    </div> -->

                </div><!-- end price -->

                <div class="price" data-key="featureHeights">
                  <h4>Sewing Classes<small>Enroll to Learn</small></h4>
                  <p><a class="btn btn-lg btn-danger" href="sewing-classes" role="button">Enroll Now</a></p>

                </div><!-- end price -->
              </div><!-- feature-blocks -->

        </div><!-- col -->

      </div><!-- row -->
    </div><!-- container -->
  </article>
</section>

<section id="blog-posts">
<div class="container">
  <div class="section-header">
    <h2>The New RMSV Blog</h2>
  </div>
  <div class="row" id="blog-post-homepage">
    <div class="col-md-6">
        <h4><a href="<?php echo site_url( '/blog/' ); ?>">Sewing and Vacuum Blog</a></h4>
        <p class="large-text">Learn about our specials, get sewing tips, and read about the latest sewing and vacuum machines. Here's our latest post...</p>
      <?php $the_query = new WP_Query( 'posts_per_page=1' ); ?>


        <?php while ($the_query -> have_posts()) : $the_query -> the_post(); ?>


            <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
            <div class="post-excerpt-home">
          		<?php the_excerpt(); ?>
          	</div><!-- post-excerpt -->

        </div>
      <div class="col-md-6">
        <figure>
          <a href="blog"><img src="<?php echo site_url( 'wp-content/uploads/'); ?>2017/07/sewing-blog.jpg"></a>
        </figure>
      </div>

            <?php
        endwhile;
        wp_reset_postdata();
        ?>
    </div>
  </div>
</section>
<!-- PRODUCTS
================================================== -->
<section id="products">
<div class="container">

  <div class="section-header">
    <h2>Sewing Machines &amp; Vacuums</h2>
  </div><!-- section-header -->

  <?php if( have_rows('home_page_content') ): ?>


    	<div class="row">

    	<?php while( have_rows('home_page_content') ): the_row();

    		// vars
    		$headline = get_sub_field('headline');
    		$pageContent = get_sub_field('text_and_links');

    		?>

    		<div class="col-md-6 homeParagraph">
          <h4><?php echo $headline; ?></h4>
          <?php echo $pageContent; ?>
        </div>


    	<?php endwhile; ?>

    <?php endif; ?>
  </div>



    <div class="row" id="productPhotos">
        <div class="col-sm-6 col-md-3" data-filter="other-images">
            <figure>
                <a href="sewing-machines">
                    <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2016/05/pfaff-smarter-machines_640x480_acf_cropped.png" />
                    <figcaption>
                        <div>
                            <h3>
                                Sewing Machines
                            </h3>
                            <p>
                                Bernina | Pfaff | Brother | Juki | Handi Quilter
                            </p>
                        </div>
                    </figcaption>
                </a>
            </figure>
        </div>
        <div class="col-sm-6 col-md-3" data-filter="other-images">
            <figure>
                <a href="sewing-machine-repair">
                    <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2017/07/Two_threads_threaded_sewing_machine_640x480_acf_cr.jpg" />
                    <figcaption>
                        <div>
                            <h3>
                                Sewing Repair
                            </h3>
                            <p>
                                Best Repair Shop
                            </p>
                        </div>
                    </figcaption>
                </a>
            </figure>
        </div>
        <div class="col-sm-6 col-md-3" data-filter="other-images">
            <figure>
                <a href="sewing-furniture">
                    <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2017/07/horn-of-america-sewing-cabinet_640x480_acf_cropped.jpg" />
                    <figcaption>
                        <div>
                            <h3>
                                Furniture and Notions
                            </h3>
                            <p>
                                All Your Sewing Needs
                            </p>
                        </div>
                    </figcaption>
                </a>
            </figure>
        </div>

        <div class="col-sm-6 col-md-3" data-filter="other-images">
            <figure>
                <a href="vacuum-cleaners">
                    <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2017/07/miele-vacuum_640x480_acf_cropped.jpg" />
                    <figcaption>
                        <div>
                            <h3>
                                Vacuum Cleaners
                            </h3>
                            <p>
                                Miele | Maytag | Hoover | Riccar
                            </p>
                        </div>
                    </figcaption>
                </a>
            </figure>
        </div>
      </div>



</div><!-- container -->
</section><!-- products -->

<?php get_template_part('content','locations'); ?>


  <!-- Sewing Classes Section -->

<section id="sewing-classes">
    <div class="container clearfix">
      <div class="section-header">
        <h2>Denver Metro and Colorado Springs Sewing Classes</h2>
      </div><!-- section-header -->

      <div class="row">
        <div class="col-sm-12">
         <div class="col-md-4 col-sm-6">
           <div class="card-container">
              <div class="card">
                  <div class="front">
                      <div class="cover">
                          <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2016/05/brother-dream-machine.png"/>
                      </div>
                      <div class="content">
                          <div class="main">
                              <h3 class="name">Embroidery Classes</h3>
                              <p class="profession">Your Best Embroidery</p>
                              <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Arvada</h5>
                              <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Aurora</h5>
                              <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Littleton</h5>
                              <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Colorado Springs</h5>
                          </div>
                          <div class="footer">
                              <div class="rating">
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                                  <i class="fa fa-star"></i>
                              </div>
                          </div>
                      </div>
                  </div> <!-- end front panel -->
                  <div class="back">
                      <div class="header">
                          <h5 class="motto">"Learning is Sew Fun!"</h5>
                      </div>
                      <div class="content">
                          <div class="main">
                              <h4 class="text-center">Monthly Classes</h4>
                              <p>Held at All Locations</p>
                              <h4 class="text-center">Get More Info Now</h4>
                              <p>Don't just learn embroidery skills. Use them along with us.</p>
                              <h5><a class="btn btn-lg btn-danger" href="sewing-classes/category/embroidery-classes" role="button">Enroll Now</a></h5>
                          </div>
                      </div>

                  </div> <!-- end back panel -->
              </div> <!-- end card -->
          </div> <!-- end card-container -->
        </div> <!-- end col sm 3 -->
<!--         <div class="col-sm-1"></div> -->
 <div class="col-md-4 col-sm-6">
     <div class="card-container">
        <div class="card">
            <div class="front">
                <div class="cover">
                    <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2016/05/Pfaff-Smarter-Machines-green.png"/>
                </div>
                <div class="content">
                    <div class="main">
                        <h3 class="name">Beginning Sewing</h3>
                        <p class="profession">Learn the Basics</p>
                        <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Arvada</h5>
                        <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Aurora</h5>
                        <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Littleton</h5>
                        <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Colorado Springs</h5>
                    </div>
                    <div class="footer">
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                </div>
            </div> <!-- end front panel -->
            <div class="back">
                <div class="header">
                    <h5 class="motto">"Learning is Sew Fun!"</h5>
                </div>
                <div class="content">
                    <div class="main">
                        <h4 class="text-center">Monthly Classes</h4>
                        <p>Held at All Locations</p>
                        <h4 class="text-center">Get More Info Now</h4>
                        <p>You'll be amazed at how quickly you can pick up a new skill. Wear what you learn!</p>
                        <h5><a class="btn btn-lg btn-danger" href="sewing-classes/category/beginning-sewing" role="button">Enroll Now</a></h5>
                    </div>
                </div>

            </div> <!-- end back panel -->
        </div> <!-- end card -->
    </div> <!-- end card-container -->
</div> <!-- end col sm 3 -->
<!--         <div class="col-sm-1"></div> -->
        <div class="col-md-4 col-sm-6">
          <div class="card-container">
             <div class="card">
                 <div class="front">
                     <div class="cover">
                         <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>/2016/05/Bernina-790.png"/>
                     </div>
                     <div class="content">
                         <div class="main">
                             <h3 class="name">Quilting Classes</h3>
                             <p class="profession">Better Quilting With Us</p>
                             <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Arvada</h5>
                             <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Aurora</h5>
                             <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Littleton</h5>
                             <h5><i class="fa fa-map-marker fa-fw text-muted"></i> Colorado Springs</h5>
                         </div>
                         <div class="footer">
                             <div class="rating">
                                 <i class="fa fa-star"></i>
                                 <i class="fa fa-star"></i>
                                 <i class="fa fa-star"></i>
                                 <i class="fa fa-star"></i>
                             </div>
                         </div>
                     </div>
                 </div> <!-- end front panel -->
                 <div class="back">
                     <div class="header">
                         <h5 class="motto">"Learning is Sew Fun!"</h5>
                     </div>
                     <div class="content">
                         <div class="main">
                             <h4 class="text-center">Monthly Classes</h4>
                             <p>Held at All Locations</p>
                             <h4 class="text-center">Get More Info Now</h4>
                             <p>Our quilting classes and clubs teach beginner and advanced quilting skills.</p>
                             <h5><a class="btn btn-lg btn-danger" href="sewing-classes/category/quilting-classes-clubs" role="button">Enroll Now</a></h5>
                         </div>
                     </div>

                 </div> <!-- end back panel -->
             </div> <!-- end card -->
         </div> <!-- end card-container -->
        </div> <!-- end col-sm-3 -->
        </div> <!-- end col-sm-10 -->
    </div> <!-- end row -->
      </div>
    </div>
</section>


<?php get_footer(); ?>
