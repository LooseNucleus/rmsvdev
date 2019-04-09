<?php
/**
 * Template Name: Landing Page
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published
 *
 * @package understrap
 */
get_header('landingpage');

global $rmsvImageUrl;
?>
<?php
$imageArray = get_field('large_background');
$rmsvImageUrl = $imageArray['url'];
$colorOpacity = get_field('color_opacity') / 100;
$lpHeadline = get_field('lp_headline');
$lpSubHeadline = get_field('lp_subheadline');
$lpCta = get_field('button_cta');
$mailMunch = get_field('mailmunch_form_id');
$lpConditions = get_field('lp_conditions');
 ?>

<section id="hero-landing" style="background: linear-gradient(rgba(34, 66, 149, <?php echo $colorOpacity; ?>), rgba(34, 66, 149, <?php echo $colorOpacity; ?>)), url('<?php echo $rmsvImageUrl; ?>'); background-size: cover;">
  <article>
    <div class="container clearfix">
      <div class="row">

        <div class="col-sm-4 page-logo">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/rmns-logo-blue-400x400.png" alt="Rocky Mountain Sewing & Vacuum" class="logo">
        </div><!-- col -->

        <div class="col-sm-8 hero-text-landing">
              <h1><?php echo $lpHeadline; ?></h1>
              <h3><?php echo $lpSubHeadline; ?></h3>

              <div id="feature-blocks">
                <div class="price-landing">
                  <h4>Get Coupons: Save Now and Later!</h4>
                  <p><button type="button" class="btn-danger btn-lg btn-info" href="<?php echo $mailMunch; ?>">Save $$$</button></p>
                </div>
              </div><!-- feature-blocks -->
              <p><?php echo $lpConditions; ?></p>
          </div><!-- end hero-text-landing -->
              </div>

        </div><!-- col -->

      </div><!-- row -->
    </div><!-- container -->
  </article>
</section>

<?php get_template_part('content','locations'); ?>

<?php get_footer(); ?>
