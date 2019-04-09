
<section id="hero">
  <?php

   $location            = get_field('location');
   $heroImage           = get_field('hero_image');
   $heroImageURL        = $heroImage['url'];

  ?>


  <!-- HERO
  ================================================== -->

  <style>
  #hero {
  	background: url('<?php echo $heroImageURL; ?>') 50% 0 no-repeat;
  	background-size: cover;
  	min-height: 100vh;
  	position: relative;
  	display: table;
  	width: 100%;
  	padding: 40px 0;
  	color: white;
  	-webkit-font-smoothing: antialiased;
  	text-rendering: optimizelegibility;
  }

  @media screen and (min-width: 768px) {
  	#hero {
  	background: url('<?php echo $heroImageURL; ?>') 50% 0 no-repeat fixed;
  		background-size: cover;
  		min-height: calc(100vh - 60px);
  		position: relative;
  		display: table;
  		width: 100%;
  		padding: 40px 0;
  		color: white;
  		-webkit-font-smoothing: antialiased;
  		text-rendering: optimizelegibility;
  	}
  }
  </style>


<div class="hero-position">
  <div class="container text-center my-auto">
      <div class="row">
        <div class="col hero-text">
        <h1>Rocky Mountain Sewing &amp; Vacuum</h1>
            <h3>Sewing Fun Starts Here</h3>
        </div>
      </div>
        <div class="row">
          <div class="col-6 col-lg-3">
          <div class="page-logo my-auto">
            <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/RMSV10yearlogo-1.svg" alt="Rocky Mountain Sewing & Vacuum" class="logo mb-2">

          </div><!-- logo -->
        </div>
        <div class="col-6 col-lg-3 my-auto">

            <div class="card card-matchHeight text-white bg-transparent mb-2">
              <div class="bg-gradient-cta-80 rounded">
              <div class="card-body">
                <h4 class="card-title">
                  Locations
                </h4>
                <p class="card-text d-none d-sm-block">
                  4 Colorado Stores
                </p>
              </div>
              <div class="card-footer px-3 text-center">
                <div class="row">
                  <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                    <button class="btn btn-lg btn-primary btn-block js--scroll-to-locations">Your Store</button>
                  </div><!-- /col -->
                </div><!-- /row -->

              </div>

            </div>
          </div>
        </div>



<div class="col-6 col-lg-3 my-auto">
            <div class="card card-matchHeight text-white bg-transparent mb-2">
              <div class="bg-gradient-primary-80 rounded">
              <div class="card-body">
                <h4 class="card-title">
                  Special Offers
                </h4>
                <p class="card-text d-none d-sm-block">Email &amp; In-Store</p>
              </div>
              <div class="card-footer px-3 text-center">
                <div class="row">
                  <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                    <button type="button" class="btn btn-lg btn-cta btn-block" href="#mailmunch-pop-506324">Save $$$</button>
                  </div>
                </div>
              </div>
            </div>
              </div>
            </div>
<div class="col-6 col-lg-3 my-auto">


            <div class="card card-matchHeight text-white bg-transparent mb-2">
              <div class="bg-gradient-cta-80 rounded">
              <div class="card-body">
                <h4 class="card-title">
                  Sewing Classes
                </h4>
                <p class="card-text d-none d-sm-block">
                  Sew Much More to Learn
                </p>

            </div>
            <div class="card-footer px-3 text-center">
              <div class="row">
                <div class="col-md-10 offset-md-1 col-lg-8 offset-lg-2">
            <a class="btn btn-lg btn-primary btn-block" href="sewing-classes" role="button">Join Us</a>
          </div>
        </div>
          </div>


            </div><!-- end price -->
          </div>
    </div><!-- row -->
    </div><!-- container -->
  </div>
</div>
</section>
