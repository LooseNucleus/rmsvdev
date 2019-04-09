

<section id="locations" class="js--section-locations">
  <div class="top-border"></div>
  <div class="container">
    <div class="section-header">
      <h2>Four Colorado Locations to Serve You</h2>
    </div><!-- section-header -->

      <div class="row">
        <div class="col-md-5">
          <div id="map" class="border-primary shadow-1"></div>
        </div>

        <div class="col-md-7">
          <div class="card border-primary shadow-1">
          <div class="card-body">
          <div class="row">

          <nav class="col-5 col-md-4">
            <ul class="nav nav-pills flex-column">
            <li class="nav-item"><a class="nav-link active" href="#All" data-toggle="tab">All Stores</a></li>
            <?php $loop = new WP_Query( array( 'post_type' => 'tribe_venue', 'posts_per_page' => 6, 'orderby' => 'title',
'order' => 'ASC' ) ); ?>



              <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
                  <li class="nav-item"><a class="nav-link" href="#<?php echo get_field('venue_css_id'); ?>" data-toggle="tab"><?php echo tribe_get_venue(); ?></span></a></li>

              <?php endwhile; ?>


            </ul>
          </nav>

          <div class="col-7 col-md-8">
          <div class="tab-content">
            <div class="tab-pane active" id="All">
              <p>All 4 of our Colorado sewing store locations give you access to our expert staff, a full line of the best sewing machines and vacuums, and access to our experienced service and repair team. Please select a store to see more information about it now.</p>
              <h4>Store Hours</h4>
              <p>Mon-Fri 10am to 6pm, Sat. 10am to 5:00pm</p>


            </div>




            <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

                <div class="tab-pane" id="<?php echo get_field('venue_css_id'); ?>">
                  <?php if (get_field('location_video')): ?>
                  <div class="youtube-player" data-id="<?php echo the_field('location_video') ;?>"></div>
                <?php endif; ?>
                  <div class="row">
                    <div class="col-1">
                      <p><i class="fa fa-map-marker" style="font-size: 24px;" aria-hidden="true"></i></p>
                    </div>
                    <div class="col-11">
                    <p><?php echo tribe_get_full_address(); ?></p>
                  </div>
                </div>
                    <div class="row">
                      <div class="col-1">
                        <p><i class="fa fa-map-o" style="font-size: 24px;" aria-hidden="true"></i></p>
                      </div>
                      <div class="col-11">
                      <p><?php echo tribe_get_map_link_html(); ?></p>
                    </div>
                  </div>
                  <?php if (tribe_get_phone()): ?>
                  <div class="row">
                    <div class="col-1">
                      <p><i class="fa fa-phone" style="font-size: 24px;" aria-hidden="true"></i></p>
                    </div>
                    <div class="col-11">
                    <p<strong><?php echo tribe_get_phone(); ?></strong></div>
                  </div>
                <?php endif; ?>
                  <div class="row">
                    <div class="col-12">
                      <a href="<?php echo tribe_get_venue_link(null,false); ?>" class="btn btn-cta" role="button"><?php echo tribe_get_venue(); ?> Page</a>
                    </div>
                  </div>



                  </div>


            <?php endwhile; ?>


          </div><!-- /tab-content -->
        </div>
        </div>
      </div>
      </div>
      </div>

        </div>

  </div><!-- container -->
<div class="bottom-border"></div>
</section>
