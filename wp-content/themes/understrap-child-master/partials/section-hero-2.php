
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
    <div class="container text-center">
      <div class="row">
        <div class="col hero-text">
        <h1>Rocky Mountain Sewing &amp; Vacuum</h1>
            <h3>Sewing Fun Starts Here</h3>
        </div>
      </div>
        <div class="row">
          <div class="col-md-3">
          <div class="page-logo my-auto">
            <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2016/08/rocky-mountain-logo-blue-sm-1.svg" alt="Rocky Mountain Sewing & Vacuum" class="logo">

          </div><!-- logo -->
        </div>
        <div class="col-md-9">
          <div class="row">
          <div class="col-md-4">

            <div class="card text-white bg-transparent">
              <div class="bg-gradient-cta-80 rounded">
              <div class="card-body text-center">
                <h4 class="card-title">
                  Locations
                </h4>
                <p class="card-text">
                  4 Colorado Locations
                </p>
              </div>
              <div class="card-footer">
                <a class="btn btn-lg btn-primary  js--scroll-to-locations" href="#" role="button">Your Store</a>
              </div>

            </div>
          </div>
        </div>



        <div class="col-md-4">
            <div class="card text-white bg-transparent mx-3">
              <div class="bg-gradient-primary-80 rounded">
              <div class="card-body">
                <h4 class="card-title">
                  Special Offers
                </h4>
                <p class="card-text">Classes &amp; In-Store</p>
              </div>
              <div class="card-footer">
              <button type="button" class="btn btn-lg btn-cta" href="#mailmunch-pop-506324">Save $$$</button>
              </div>
            </div>
              </div>
            </div>

        <div class="col-md-4">

            <div class="card text-white bg-transparent">
              <div class="bg-gradient-cta-80 rounded">
              <div class="card-body">
                <h4 class="card-title">
                  Sewing Classes
                </h4>
                <p class="card-text">
                  Enroll to Learn
                </p>
              </div>
              <div class="card-footer">
                <a class="btn btn-lg btn-primary" href="sewing-classes" role="button">Enroll Now</a>
              </div>
            </div>

            </div><!-- end price -->
          </div>
</div>
</div>
    </div>
    </div><!-- container -->
  </div>
</section>
