<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0001">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
<?php echo get_theme_mod( 'understrap_theme_script_code_setting' ); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">

    <!-- ******************* The Navbar Area ******************* -->
    <div class="wrapper-fluid wrapper-navbar" id="wrapper-navbar">

        <nav class="site-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">

            <div class="navbar navbar-default navbar-fixed-top">

                <div class="container">

                    <div class="row">

                        <div class="col-sm-12">

                            <div class="navbar-header">

                                <!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>

                                <!-- Your site title as branding in the menu -->

                                <!-- If a header images exists. -->
                                <?php if ( get_header_image() ) : ?>
                                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2016/05/rmsv-logo-160x160.png" width="80" height="80" alt="">
                                </a>
                                <?php endif; // End header image check. ?>



                                <button type="button" class="btn btn-danger navbar-btn navbar-left" data-toggle="modal" data-target="#logInModal">Sign in</button>


                            </div>

                            <!-- The WordPress Menu goes here -->
                            <?php wp_nav_menu(
                                    array(
                                        'theme_location' => 'primary',
                                        'container_class' => 'collapse navbar-collapse navbar-responsive-collapse',
                                        'menu_class' => 'nav navbar-nav navbar-right',
                                        'fallback_cb' => '',
                                        'menu_id' => 'main-menu',
                                        'walker' => new wp_bootstrap_navwalker()
                                    )
                            ); ?>

                        </div> <!-- .col-md-11 or col-md-12 end -->

                    </div> <!-- .row end -->

                </div> <!-- .container -->

            </div><!-- .navbar -->

        </nav><!-- .site-navigation -->

    </div><!-- .wrapper-navbar end -->

    <!-- MODAL LOGIN -->
    <div class="modal fade" id="logInModal" role="dialog" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <?php
              if ( ! is_user_logged_in() ) { // Display WordPress login form:
              $args = array(
                  'redirect' => home_url(),
                  'form_id' => 'loginform-custom',
                  'label_username' => __( 'Username' ),
                  'label_password' => __( 'Password' ),
                  'label_log_in' => __( 'Log In' ),
                  'remember' => true
                  );
              wp_login_form( $args );


              } else { // If logged in:
                wp_loginout( home_url() ); // Display "Log Out" link.
                echo " | ";
                wp_register('', ''); // Display "Site Admin" link.
          }
            ?>
          </div>
        <div class="modal-footer">
          <?php
            if ( ! is_user_logged_in() ) {
              echo '<a href="';
              echo wp_lostpassword_url();
              echo '?nocache=1';
              echo '">Lost Password?</a>';
              echo ' | ';
              echo '<a href = ';
              echo wp_register();
              echo '</a>';
            } else {
              echo '<a href="sewing-classes">See New Sewing Classes Now</a>';
            }

          ?>
        </div>


        </div>
      </div>
    </div><!-- MODAL END -->
