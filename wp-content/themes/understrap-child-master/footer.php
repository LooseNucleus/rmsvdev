<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

$the_theme = wp_get_theme();
$container = get_theme_mod( 'understrap_container_type' );

?>

<?php get_sidebar( 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_html( $container ); ?>">

    <?php
    if (have_rows('social_icon_links', 'option')) {
      while(have_rows('social_icon_links', 'options')) {
        the_row();

        if(!empty(get_sub_field('facebook'))) {
          get_template_part('partials/social/icon', 'facebook');
        }

        if(!empty(get_sub_field('pinterest'))) {
          get_template_part('partials/social/icon', 'pinterest');
        }

        if(!empty(get_sub_field('instagram'))) {
          get_template_part('partials/social/icon', 'instagram');
        }

        if(!empty(get_sub_field('youtube'))) {
          get_template_part('partials/social/icon', 'youtube');
        }

        if(!empty(get_sub_field('linkedin'))) {
          get_template_part('partials/social/icon', 'linkedin');
        }


      }
    }



     ?>

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info">
						<p>&copy; <?php echo date("Y"); echo " "; bloginfo('name'); ?>. <a href = " <?php echo esc_url( home_url('/')); echo 'privacy-policy/'; ?>">Privacy</a> | <a href = "<?php echo esc_url( home_url('/')); echo 'terms-conditions/'; ?>">Terms and Conditions</a>
						</p>
					</div>


				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>
