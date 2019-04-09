<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */
?>

<div class="wrapper" id="wrapper-footer">

    <div class="container">

        <div class="row">

            <div class="col-md-12">

                <footer id="colophon" class="container site-footer" role="contentinfo">

                    <div class="site-info">
                    <p>&copy; <?php echo date("Y"); echo " "; bloginfo('name'); ?>. <a href = " <?php echo esc_url( home_url('/')); echo 'privacy-policy'; ?>">Privacy</a></p>
                  </div>

                </footer><!-- #colophon -->

            </div><!--col end -->

        </div><!-- row end -->

    </div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page -->



<?php wp_footer(); ?>

<!-- Loads slider script and settings if a widget on pos hero is published -->
<?php if ( is_active_sidebar( 'hero' ) && (!empty($startPosition)) ): ?>

<script>
    jQuery(document).ready(function() {
        var owl = jQuery('.owl-carousel');
        owl.owlCarousel({
            loop:true,
            autoplay:false,
            center:true,
            mouseDrag:true,
            autoplayTimeout:<?php echo get_theme_mod( 'understrap_theme_slider_time_setting', 5000 );?>,
            animateOut: 'fadeOut',
            animateIn: 'fadeIn',
            nav: false,
            dots: true,
            autoplayHoverPause:true,
            margin:10,
            startPosition:<?php echo $startPosition; ?>,
            URLhashListener: true,
            responsiveClass:true,
            responsive: {
              0:{
                items:2,
              },
              768:{
                items:6,
              }
            },
            autoHeight:true
        });

        jQuery('.play').on('click',function(){
            owl.trigger('autoplay.play.owl',[1000])
        });
        jQuery('.stop').on('click',function(){
            owl.trigger('autoplay.stop.owl')
        });
    });
</script>
<?php endif; ?>

</body>

</html>
