</div><!-- END OF CONTENT -->
</div>
</div>
<section id="imageIcons">
  <div class="container">
      <?php if(have_rows('image_icons')): ?>
         <div class="row justify-content-center">
           <?php $count=0;
           while(have_rows('image_icons')): the_row();
             $count++; ?>
              <div class=" col-6 col-md-3 my-3">

                      <div class="card bg-light card-height">
                          <div class="card-body">
                            <h4 class="card-title text-center my-auto"><?php the_sub_field('image_icon_title'); ?></h4>
                          </div>
                          <div class="overlay-container">
                          <img src="<?php the_sub_field('image_icon')['url']; ?>" alt="<?php echo $service; ?>" style="width:100%">
                          <div class="overlay-top-slide">
                            <div class="overlay-text">
                            <p><?php the_sub_field('image_icon_teaser'); ?></p>
                            <p><i class="fa fa-arrow-down"></i></p>
                          </div>
                          </div>
                        </div>
                          <div class="card-footer text-center"><a href="<?php the_sub_field('image_icon_link'); ?>" class="btn btn-primary mx-auto">Learn More</a></div>

                      </div>

              </div>
           <?php
           if($count % 4 == 0) :  echo '</div><div class="row justify-content-center">'; endif; ?>
      <?php endwhile; ?>
      </div>
      <?php endif; ?>



</section>
<div class="container">
<div class="row">
<div class="col-md-8 mx-auto my-auto">
