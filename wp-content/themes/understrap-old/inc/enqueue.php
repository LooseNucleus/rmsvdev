<?php
/**
 * understrap enqueue scripts
 *
 * @package understrap
 */

function understrap_scripts() {
    wp_enqueue_style( 'understrap-theme', get_stylesheet_directory_uri() . '/css/theme.min.css', array(), '0.3.7' );
    /* wp_enqueue_style( 'understrap-theme', get_stylesheet_directory_uri() . '/css/app.clean.css', array(), '0.3.7' );
    */
    wp_enqueue_script('jquery');

	  wp_enqueue_script( 'understrap-navigation', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '20120206', true );

    if (is_page_template( 'page-templates/page-landing.php') )
      {wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/scripts.js', array(), 'null', true);
      wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
    if (is_page_template( 'page-templates/page-store-landing.php') )
      {wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/scripts.js', array(), 'null', true);
      wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
    if (is_front_page() )
        {wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/scripts.js', array(), 'null', true);
        wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
        wp_enqueue_script('google-maps-api');}
    if (is_page_template( 'page-templates/page-locations.php') )
      {wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/internal-scripts.js', array(), 'null', true);
        wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
      if (is_page_template( 'page-templates/page-thankyou.php') )
        {wp_enqueue_script( 'understrap-scripts', get_template_directory_uri() . '/js/scripts.js', array(), 'null', true);
        wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
        wp_enqueue_script('google-maps-api');}





    wp_enqueue_script( 'understrap-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	   if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
  		wp_enqueue_script( 'comment-reply' );
  	 }

    if (( is_active_sidebar( 'hero' ) ) && (is_page_template( 'page-templates/page-carouselbrand.php'))) {
        wp_enqueue_style( 'understrap-carousel-style', get_template_directory_uri() . '/css/owl.carousel.css', array(), '20024', false );
        wp_enqueue_style( 'understrap-carousel-theme', get_template_directory_uri() . '/css/owl.theme.css', array(), '20024', false);
        wp_enqueue_script( 'understrap-carousel-script', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), '20024', true );
        wp_enqueue_script( 'owl-carousel-settings', get_template_directory_uri() .
        '/js/add-owl-settings.js', array(), '20024', true );
        $carouselPosition = get_field('carousel_position');

        wp_localize_script( 'owl-carousel-settings', 'simplur_startPosition', $carouselPosition);
      }
  }

add_action( 'wp_enqueue_scripts', 'understrap_scripts' );
