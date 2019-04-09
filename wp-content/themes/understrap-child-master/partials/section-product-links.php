
<!-- PRODUCTS -->
<section id="products">
  <div class="top-border"></div>
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

          <div class="col-md-6 homeParagraph mb-5">
            <h4><?php echo $headline; ?></h4>
            <?php echo $pageContent; ?>
          </div>


        <?php endwhile; ?>
  </div>
      <?php endif; ?>





    <div class="row" id="productPhotos">
        <div class="col-6 col-md-3">

                <a href="sewing-machines">
                  <figure>
                    <img class="mb-2 zoom" src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/Icons_SewingMachine-1.svg" />
                    </figure>

                        <div class="text-center">
                            <h3>Sewing Machines</h3>
                            <p>Bernina | Pfaff | Brother | Janome | Juki | Handi Quilter</p>
                        </div>

                </a>

        </div>
        <div class="col-6 col-md-3">

                <a href="sewing-machine-repair/">
                  <figure>
                    <img class="mb-2 zoom" src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/Icons_ServiceRepair-1.svg" />
                      </figure>

                        <div class="text-center">
                            <h3>
                                Repair and Service
                            </h3>
                            <p>
                                <a href="sewing-machine-repair/">Sewing Machine Repair</a> | <a href="vacuum-cleaner-repair-savings">Vacuum Repair</a>
                            </p>
                        </div>

                </a>

        </div>
        <div class="col-6 col-md-3">

                <a href="sewing-furniture">
                    <figure>
                    <img class="mb-2 zoom" src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/Icons_FurnitureNotions-1.svg" />
                      </figure>

                        <div class="text-center">
                            <h3>
                                Furniture and Notions
                            </h3>
                            <p>
                                All Your Sewing Needs
                            </p>
                        </div>

                </a>

        </div>

        <div class="col-6 col-md-3">
                <a href="vacuum-cleaners">
                    <figure>
                    <img class="mb-2 zoom" src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/Icons_VacuumCleaner-1.svg" />
                      </figure>

                        <div class="text-center">
                            <h3>
                                Vacuum Cleaners
                            </h3>
                            <p>
                                Miele | Royal | Hoover | Riccar | Simplicity
                            </p>
                        </div>

                </a>

        </div>
      </div>



</div><!-- container -->
<div class="bottom-border"></div>
</section><!-- end products -->
