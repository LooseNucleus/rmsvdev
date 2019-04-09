<?php
function understrap_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );

    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );

    // Removes the parent themes stylesheet and scripts from inc/enqueue.php
}

register_sidebar( array(
  'name'          => __( 'Simplur Brand Slider', 'understrap-child-master' ),
  'id'            => 'brand',
  'description'   => 'Hero slider area. Place two or more widgets here and they will slide!',
  // 'before_widget' => '<div class="carousel-item">',
  // 'after_widget'  => '</div>',
  'before_title'  => '',
  'after_title'   => '',
) );

add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {

	// Get the theme data
	$the_theme = wp_get_theme();
    wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
    wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'popper-scripts', get_template_directory_uri() . '/js/popper.min.js', array(), false);
  wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );

  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
      wp_enqueue_script( 'comment-reply' );
      wp_register_script( 'simplur-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), null, true);
      wp_register_script( 'simplur-internal-scripts', get_stylesheet_directory_uri() . '/js/internal-scripts.js', array(), null, true);

  }

  /* CSS for Extras like Owl Carousel */
  wp_register_script('understrap-carousel-script', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', '20024', true);
  wp_enqueue_style( 'understrap-carousel-style', get_stylesheet_directory_uri() . '/css/owl.carousel.css', array(), '20024', false );
  wp_enqueue_style( 'understrap-carousel-theme', get_stylesheet_directory_uri() . '/css/owl.theme.css', array(), '20024', false);
  wp_enqueue_script( 'understrap-carousel-script');
  wp_enqueue_script( 'owl-carousel-settings', get_stylesheet_directory_uri() .
  '/js/slider_settings.js', array(), '20024', true );

        /* Conditional Script Loading */

    if (is_page_template( 'page-templates/page-landing.php') ) {
      wp_enqueue_script( 'simplur-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), 'null', true);
      wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
    if (is_page_template( 'page-templates/page-store-landing.php') ) {
      wp_enqueue_script( 'simplur-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), 'null', true);
      wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
    if (is_front_page() ) {
          wp_enqueue_script( 'simplur-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), 'null', true);
          wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
          wp_enqueue_script('google-maps-api');
        }
    if (is_page_template( 'page-templates/page-locations.php') ) {
      wp_enqueue_script( 'simplur-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), 'null', true);
        wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
      wp_enqueue_script('google-maps-api');}
    if (is_page_template( 'page-templates/page-thankyou.php') )
        {wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/scripts.js', array(), 'null', true);
        wp_register_script('google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyBB_kCZS6xhGU3KaFITZjN1S8ZA5YUTRuU&callback=initMap', array(), '3.15', true);
        wp_enqueue_script('google-maps-api');}

    if (( is_active_sidebar( 'brand' ) ) && (is_page_template( 'page-templates/page-carouselbrand.php'))) {
            wp_enqueue_style( 'understrap-carousel-style', get_stylesheet_directory_uri() . '/css/owl.carousel.css', array(), '20024', false );
            wp_enqueue_style( 'understrap-carousel-theme', get_stylesheet_directory_uri() . '/css/owl.theme.css', array(), '20024', false);
            wp_enqueue_script( 'understrap-carousel-script', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array(), '20024', true );
            wp_dequeue_script('owl-carousel-settings');
            wp_enqueue_script( 'owl-carousel-settings-brand', get_stylesheet_directory_uri() . '/js/add-owl-settings.js', array(), '20024', true );
            $carouselPosition = get_field('carousel_position');

            wp_localize_script( 'owl-carousel-settings-brand', 'simplur_startPosition', $carouselPosition);
          }

          if (is_woocommerce()) {
              wp_register_script( 'stickyfill', get_stylesheet_directory_uri() . '/js/stickyfill.min.js', array(), $the_theme->get( 'Version'), false);
              wp_enqueue_script('stickyfill');
              wp_register_script( 'simplur-woocommerce-scripts', get_stylesheet_directory_uri() . '/js/simplur-wc-scripts.js', array('stickyfill'), 'null', false);
              wp_enqueue_script( 'simplur-woocommerce-scripts');
            }
}

/* IMAGE SIZES */

add_image_size ('blog_image', 780, 568, false);
add_image_size ('large_background', 1920, 1080, false);
add_image_size ('sewing_classes', 534, 300, false);
add_image_size ('featured_image', 400, 300, true);
add_image_size ('location_image', 300, 150, false);
add_image_size ('square_card', 300, 300, true);


//Admin Bar Only for Contributors or Higher
function rmsv_remove_admin_bar( $show_admin_bar ) {
	if( current_user_can( 'edit_posts' ) ){
		return $show_admin_bar;
	}else{
		return false;
	}
}
add_filter( 'show_admin_bar' , 'rmsv_remove_admin_bar' );

//Remove User Profile fields
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');


// YOAST REPLACEMENT IMAGES

add_filter( 'the_seo_framework_og_image_args', 'my_awesome_og_image' );
function my_awesome_og_image( $args ) {

   //* You don't have to escape this url <img draggable="false" class="emoji" alt="🙂" src="https://s.w.org/images/core/emoji/72x72/1f642.png">
   $args['image'] = home_url('wp-content/uploads/2016/03/rmns-logo-blue-400x400.png');

   //* Set this to true if you don't want featured images to be used in og:image
   //* args['image'] has to be set for this to work
   $args['override'] = true;

   //* Set this to false if you wish that the homepage featured image overrides the URL set above
   $args['frontpage'] = true;

   return $args;
}

global $rmsvImageUrl;

function my_acf_format_value( $value, $post_id, $field ) {

	return esc_attr($value);

}

add_filter('acf/format_value/type=url', 'my_acf_format_value', 10, 3);

/*
* WOOCOMMERCE DEFAULT IMAGE REPLACEMENT Replace the image filename/path with your own :)
*
**/
add_action( 'init', 'custom_fix_thumbnail' );

function custom_fix_thumbnail() {
  add_filter('woocommerce_placeholder_img_src', 'custom_woocommerce_placeholder_img_src');

	function custom_woocommerce_placeholder_img_src( $src ) {
	$upload_dir = wp_upload_dir();
	$uploads = untrailingslashit( $upload_dir['baseurl'] );
	$src = $uploads . '/2016/03/rmns-logo-blue-400x400.png';

	return $src;
	}
}




function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo site_url( '/wp-content/uploads/' ); ?>2016/02/cropped-cropped-rmns-logo-80x80.png);
            padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Rocky Mountain Sewing and Vacuum';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );



// Remove WP Version From Styles
add_filter( 'style_loader_src', 'sdt_remove_ver_css_js', 9999 );
// Remove WP Version From Scripts
add_filter( 'script_loader_src', 'sdt_remove_ver_css_js', 9999 );

// Function to remove version numbers
function sdt_remove_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

//YOUTUBE VIDEO EMBEDS
// Hook onto 'oembed_dataparse' and get 2 parameters
add_filter( 'oembed_dataparse','responsive_wrap_oembed_dataparse',10,2);

function responsive_wrap_oembed_dataparse( $html, $data ) {
 // Verify oembed data (as done in the oEmbed data2html code)
 if ( ! is_object( $data ) || empty( $data->type ) )
 return $html;

 // Verify that it is a video
 if ( !($data->type == 'video') )
 return $html;

 // Calculate aspect ratio
 $ar = $data->width / $data->height;

 // Set the aspect ratio modifier
 $ar_mod = ( abs($ar-(4/3)) < abs($ar-(16/9)) ? 'embed-responsive-4by3' : 'embed-responsive-16by9');

 // Strip width and height from html
 $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );

 // Return code
 return '<div class="embed-responsive '.$ar_mod.'" data-aspectratio="'.number_format($ar, 5, '.').'">'.$html.'</div>';
}

if (tribe_is_month) {
	add_filter( 'tribe_events_add_no_index_meta', '__return_false' );
}

function simplur_order_status_sorting( $order_statuses ){
	remove_filter( 'wc_order_statuses', $order_statuses, 10, 1 );
	$order_statuses = array(
		'wc-completed'  => _x( 'Completed', 'Order status', 'woocommerce' ),
		'wc-pending'    => _x( 'Pending payment', 'Order status', 'woocommerce' ),
		'wc-processing' => _x( 'Processing', 'Order status', 'woocommerce' ),
		'wc-on-hold'    => _x( 'On hold', 'Order status', 'woocommerce' ),
		'wc-cancelled'  => _x( 'Cancelled', 'Order status', 'woocommerce' ),
		'wc-refunded'   => _x( 'Refunded', 'Order status', 'woocommerce' ),
		'wc-failed'     => _x( 'Failed', 'Order status', 'woocommerce' ),
	);
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'simplur_order_status_sorting' );

//EVENTS CALENDAR NEWSLETTER BRAND OPTION

function ecn_add_brand_design_option( $data ) {
	?>
	<label><input type="radio" name="design" value="table"<?php checked( 'table', $data['design'] ) ?>> <?= __( 'Single Row With Brand CTA Color Button', 'event-calendar-newsletter' ) ?></label><br />
	<?php
}
add_action( 'ecn_designs', 'ecn_add_brand_design_option', 10, 1 );

// function ecn_output_format_brand( $format, $event, $args, $previous_date ) {
// 	if ( isset( $args['design'] ) and 'table' == $args['design'] )
// 		$format = '<table style="width:100%">
// <tr>
// {if_event_image_url}
// <td width="15%" style="text-align:right; padding-right:5px;"><img src="{event_image_url}" width="100%" /></td>
// {/if_event_image_url}
// {if_not_event_image_url}
// <td width="15%">&nbsp;</td>
// {/if_not_event_image_url}
// <td width="70%" valign="top">
// <strong><a href="{link_url}">{title}</a></strong>
// <br>
// <div style="margin-top:5px;">{start_date} {if_not_all_day}@ {start_time}{if_end_time} to {end_time}{/if_end_time}{/if_not_all_day}</div>
// </td>
// <td width="15%"><a href="{link_url}" style="background-color:#ec676b;background-image:none;border-radius:4px;border:0;box-shadow:none;color:#ffffff;cursor:pointer;display:inline-block;font-size:11px;font-weight:500;letter-spacing: 1px;line-height: normal;padding:6px 11px;text-align:center;text-decoration:none;text-transform:uppercase;vertical-align:middle;zoom:1;white-space:nowrap;">View</a></td>
// </tr>
// </table>';
// 	return $format;
// }
// add_filter( 'ecn_output_format', 'ecn_output_format_brand', 10, 4 );

function ecn_output_format_brand( $format, $event, $args, $previous_date ) {
	if ( isset( $args['design'] ) and 'table' == $args['design'] )
		$format = '<table style="width:100%">
<tr>
{if_event_image_url}
<td width="15%" style="text-align:right; padding-right:5px;"><img src="{event_image_url}" width="100%" /></td>
{/if_event_image_url}
{if_not_event_image_url}
<td width="15%">&nbsp;</td>
{/if_not_event_image_url}
<td width="85%" valign="middle">
<strong><a href="{link_url}">{title}</a></strong> -- {start_date} {if_not_all_day}@ {start_time}{if_end_time} to {end_time}{/if_end_time}{/if_not_all_day}
</td>
</tr>
</table>';
	return $format;
}
add_filter( 'ecn_output_format', 'ecn_output_format_brand', 10, 4 );

// Filter custom logo with correct classes.
add_filter( 'get_custom_logo', 'understrap_child_change_logo_class' );

if ( ! function_exists( 'understrap_child_change_logo_class' ) ) {
	/**
	 * Replaces logo CSS class.
	 *
	 * @param string $html Markup.
	 *
	 * @return mixed
	 */
	function understrap_child_change_logo_class( $html ) {

		$html = str_replace( 'class="custom-logo"', 'class="img-fluid"', $html );
		$html = str_replace( 'class="custom-logo-link"', 'class="navbar-brand custom-logo-link"', $html );
		$html = str_replace( 'alt=""', 'title="Home" alt="logo"' , $html );

		return $html;
	}
}


function simplur_image_icons_function() {
  ob_start();
  get_template_part('partials/section', 'image-icons');
  return ob_get_clean();
}

add_shortcode(
  'image_icons',
  'simplur_image_icons_function'
);

function simplur_signup_coupon() {
  ob_start();
  get_template_part('partials/section', 'signup-coupon');
  return ob_get_clean();
}

add_shortcode(
  'signup_coupon',
  'simplur_signup_coupon'
);

// WOOCOMMERCE TEMPLATE Functions

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

add_action( 'woocommerce_shop_loop_item_title', 'simplur_woocommerce_template_loop_product_title', 10 );

if ( ! function_exists( 'simplur_woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function simplur_woocommerce_template_loop_product_title() {
		echo '<div class="card-body flex-text"><div class="card-title woocommerce-loop-product__title">' . get_the_title() . '</div>';
	}
}

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

add_action( 'woocommerce_shop_loop_subcategory_title', 'simplur_woocommerce_template_loop_category_title', 10 );

if ( ! function_exists( 'simplur_woocommerce_template_loop_category_title' ) ) {

	/**
	 * Show the subcategory title in the product loop.
	 *
	 * @param object $category Category object.
	 */
	function simplur_woocommerce_template_loop_category_title( $category ) {
		?>
    <div class="card-body flex-text">
		<div class="card-title woocommerce-loop-category__title">
			<?php
			echo esc_html( $category->name );

			if ( $category->count > 0 ) {
				echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $category->count ) . ')</mark>', $category ); // WPCS: XSS ok.
			}
			?>
		</div>

		<?php
	}
}

add_filter( 'woocommerce_product_subcategories_args', 'custom_woocommerce_product_subcategories_args' );		function custom_woocommerce_product_subcategories_args( $args ) {	  $args['exclude'] = get_option( 'default_product_cat' );	  return $args;	}

remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

add_action( 'woocommerce_shop_loop_item_title', 'simplur_woocommerce_template_loop_product_title', 10 );

if ( ! function_exists( 'simplur_woocommerce_template_loop_product_title' ) ) {

	/**
	 * Show the product title in the product loop. By default this is an H2.
	 */
	function simplur_woocommerce_template_loop_product_title() {
		echo '<div class="card-body flex-text"><div class="card-title woocommerce-loop-product__title">' . get_the_title() . '</div>';
	}
}

add_action( 'woocommerce_after_subcategory', 'simplur_woocommerce_template_loop_category_card_body_close', 5 );

function simplur_woocommerce_template_loop_category_card_body_close() {
  echo '</div>';
}

add_action( 'woocommerce_after_shop_loop_item_title', 'simplur_woocommerce_template_loop_product_card_body_close', 99 );

function simplur_woocommerce_template_loop_product_card_body_close() {
  echo '</div>';
}

remove_action( 'woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10 );

add_action( 'woocommerce_shop_loop_subcategory_title', 'simplur_woocommerce_template_loop_category_title', 10 );

if ( ! function_exists( 'simplur_woocommerce_template_loop_category_title' ) ) {

	/**
	 * Show the subcategory title in the product loop.
	 *
	 * @param object $category Category object.
	 */
	function simplur_woocommerce_template_loop_category_title( $category ) {
		?>
    <div class="card-body flex-text">
		<div class="card-title woocommerce-loop-category__title">
			<?php
			echo esc_html( $category->name );

			if ( $category->count > 0 ) {
				echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . esc_html( $category->count ) . ')</mark>', $category ); // WPCS: XSS ok.
			}
			?>
		</div>

		<?php
	}
}

function category_sidebar() {
    register_sidebar(
        array (
            'name' => __( 'Category Sidebar', 'understrap-child-master' ),
            'id' => 'category-sidebar',
            'description' => __( 'Category Sidebar', 'understrap-child-master' ),
            // 'before_widget' => '<div class="widget-content">',
            // 'after_widget' => "</div>",
            // 'before_title' => '',
            // 'after_title' => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'category_sidebar' );

/**
 * Code goes in theme functions.php.
 *
 * If you use dropdown instead of hierachical view,
 * hook to the following filter instead:
 *      `woocommerce_product_categories_widget_dropdown_args`
 */
add_filter( 'woocommerce_product_categories_widget_dropdown_args', 'custom_woocommerce_product_categories_widget_args' );

function custom_woocommerce_product_categories_widget_args( $args ) {
  $args['exclude'] = get_option( 'default_product_cat' );
  return $args;
}


/*-------------------------------------
 Move Yoast to the Bottom
---------------------------------------*/
function yoasttobottom() {
 return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

/** Disable Ajax Call from WooCommerce on front page and posts*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page() || is_single() || tribe_is_month() ) wp_dequeue_script('wc-cart-fragments');
}

/* SIMPLUR UNDERSTRAP SOCIAL SETTINGS */


add_action( 'admin_menu', 'simplur_social_add_admin_menu' );
add_action( 'admin_init', 'simplur_social_settings_init' );


function simplur_social_add_admin_menu(  ) {

	add_options_page( 'Simplur Social', 'Simplur Social', 'manage_options', 'simplur_social', 'simplur_social_options_page' );

}


function simplur_social_settings_init(  ) {

	register_setting( 'pluginPage', 'simplur_social_settings' );

	add_settings_section(
		'simplur_social_pluginPage_section',
		__( 'Social Network Links', 'wordpress' ),
		'simplur_social_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'simplur_social_facebook',
		__( 'Facebook link', 'wordpress' ),
		'simplur_social_facebook_render',
		'pluginPage',
		'simplur_social_pluginPage_section'
	);

	add_settings_field(
		'simplur_social_pinterest',
		__( 'Pinterest link', 'wordpress' ),
		'simplur_social_pinterest_render',
		'pluginPage',
		'simplur_social_pluginPage_section'
	);

	add_settings_field(
		'simplur_social_instagram',
		__( 'Instagram Link', 'wordpress' ),
		'simplur_social_instagram_render',
		'pluginPage',
		'simplur_social_pluginPage_section'
	);

	add_settings_field(
		'simplur_social_youtube',
		__( 'YouTube link', 'wordpress' ),
		'simplur_social_youtube_render',
		'pluginPage',
		'simplur_social_pluginPage_section'
	);


}


function simplur_social_facebook_render(  ) {

	$options = get_option( 'simplur_social_settings' );
	?>
	<input type='text' name='simplur_social_settings[simplur_social_facebook]' value='<?php echo $options['simplur_social_facebook']; ?>'>
	<?php

}


function simplur_social_pinterest_render(  ) {

	$options = get_option( 'simplur_social_settings' );
	?>
	<input type='text' name='simplur_social_settings[simplur_social_pinterest]' value='<?php echo $options['simplur_social_pinterest']; ?>'>
	<?php

}


function simplur_social_instagram_render(  ) {

	$options = get_option( 'simplur_social_settings' );
	?>
	<input type='text' name='simplur_social_settings[simplur_social_instagram]' value='<?php echo $options['simplur_social_instagram']; ?>'>
	<?php

}


function simplur_social_youtube_render(  ) {

	$options = get_option( 'simplur_social_settings' );
	?>
	<input type='text' name='simplur_social_settings[simplur_social_youtube]' value='<?php echo $options['simplur_social_youtube']; ?>'>
	<?php

}


function simplur_social_settings_section_callback(  ) {

	echo __( 'This section description', 'wordpress' );

}


function simplur_social_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>Simplur Social</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Theme Header Settings',
	// 	'menu_title'	=> 'Header',
	// 	'parent_slug'	=> 'theme-general-settings',
	// ));
  //
	// acf_add_options_sub_page(array(
	// 	'page_title' 	=> 'Theme Footer Settings',
	// 	'menu_title'	=> 'Footer',
	// 	'parent_slug'	=> 'theme-general-settings',
	// ));

}

function google_analytics_script() {
  global $template;
  $pageType = basename($template, ".php");

  if (tribe_is_month()) {
    $subPage = "-month";
  } else if (tribe_is_venue()) {
    $subPage = "-venue";
  } else if (tribe_is_list_view()) {
    $subPage = "-list";
  } else if (is_woocommerce() && is_product() ) {
    $subPage = "-single";
  } else if (is_woocommerce() && is_product_category() ) {
    $subPage = '-category';
  }

  $pageType .= $subPage;

  ?>

  <!-- Google Analytics -->
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-4083965-2', 'auto');
  ga('create', 'UA-106914072-1', 'auto', 'newAccount');
  ga('newAccount.set', 'contentGroup1', '<?php echo $pageType; ?>' );
  ga('send', 'pageview');
  ga('newAccount.send', 'pageview');
  </script>
  <!-- End Google Analytics -->
  <?php
}

add_action('wp_head', 'google_analytics_script');

add_filter('catalog_visibility_alternate_price_html', 'my_alternate_price_text', 10, 1);


function my_alternate_price_text() {

    $catalogText = 'Special In-Store Pricing';
    if ( is_product() ) {
      simplur_geoip_city_cta();
    } else {
      return $catalogText;
    }
}


function simplur_geoip_city_cta() {
  $state = getenv('HTTP_GEOIP_REGION');

  if ($state == 'CO') { ?>
    <button type="button" alt="Get Pricing Info" class="btn btn-cta my-3 mr-3" data-toggle="modal" data-target="#rfq-modal-lg" onClick="ga('send', 'event', ,'rfq button click', 'click', 'product quote button Click');">Get Pricing Info</button>Special In-Store Pricing
<?php
}
}

function hidden_geoip_shortcode() {
  $hiddenCity = getenv('HTTP_GEOIP_CITY');
return 'City = ' . $hiddenCity . '.';
}
add_shortcode( 'geocity', 'hidden_geoip_shortcode' );

function add_query_vars_filter ($vars) {
  $vars[] .= 'mc_email';
  $vars[] .= 'mc_date';
  return $vars;
}

add_filter ('query_vars', 'add_query_vars_filter');

/* THE EVENTS CALENDAR FUNCTIONS */


/*
 * Alters event's archive titles
 */


function tribe_alter_event_archive_titles ( $original_recipe_title, $depth ) {
	// Modify the titles here
	// Some of these include %1$s and %2$s, these will be replaced with relevant dates
	$title_upcoming =   'Upcoming Sewing Classes'; // List View: Upcoming events
	$title_past =       'Past Sewing Classes'; // List view: Past events
	$title_range =      'Sewing Classes for %1$s - %2$s'; // List view: range of dates being viewed
	$title_month =      'Sewing Classes for %1$s'; // Month View, %1$s = the name of the month
	$title_day =        'Sewing Classes for %1$s'; // Day View, %1$s = the day
	$title_all =        'All Sewing Classes for %s'; // showing all recurrences of an event, %s = event title
	$title_week =       'Sewing Classes for week of %s'; // Week view
	// Don't modify anything below this unless you know what it does
	global $wp_query;
	$tribe_ecp = Tribe__Events__Main::instance();
	$date_format = apply_filters( 'tribe_events_pro_page_title_date_format', tribe_get_date_format( true ) );
	// Default Title
	$title = $title_upcoming;
	// If there's a date selected in the tribe bar, show the date range of the currently showing events
	if ( isset( $_REQUEST['tribe-bar-date'] ) && $wp_query->have_posts() ) {
		if ( $wp_query->get( 'paged' ) > 1 ) {
			// if we're on page 1, show the selected tribe-bar-date as the first date in the range
			$first_event_date = tribe_get_start_date( $wp_query->posts[0], false );
		} else {
			//otherwise show the start date of the first event in the results
			$first_event_date = tribe_event_format_date( $_REQUEST['tribe-bar-date'], false );
		}
		$last_event_date = tribe_get_end_date( $wp_query->posts[ count( $wp_query->posts ) - 1 ], false );
		$title = sprintf( $title_range, $first_event_date, $last_event_date );
	} elseif ( tribe_is_past() ) {
		$title = $title_past;
	}
	// Month view title
	if ( tribe_is_month() ) {
		$title = sprintf(
			$title_month,
			date_i18n( tribe_get_option( 'monthAndYearFormat', 'F Y' ), strtotime( tribe_get_month_view_date() ) )
		);
	}
	// Day view title
	if ( tribe_is_day() ) {
		$title = sprintf(
			$title_day,
			date_i18n( tribe_get_date_format( true ), strtotime( $wp_query->get( 'start_date' ) ) )
		);
	}
	// All recurrences of an event
	if ( function_exists('tribe_is_showing_all') && tribe_is_showing_all() ) {
		$title = sprintf( $title_all, get_the_title() );
	}
	// Week view title
	if ( function_exists('tribe_is_week') && tribe_is_week() ) {
		$title = sprintf(
			$title_week,
			date_i18n( $date_format, strtotime( tribe_get_first_week_day( $wp_query->get( 'start_date' ) ) ) )
		);
	}
	if ( is_tax( $tribe_ecp->get_event_taxonomy() ) && $depth ) {
		$cat = get_queried_object();
		$title = '<a href="' . esc_url( tribe_get_events_link() ) . '">' . $title . '</a>';
		$title .= ' &#8250; ' . $cat->name;
	}
	return $title;
}
add_filter( 'tribe_get_events_title', 'tribe_alter_event_archive_titles', 11, 2 );


/* PREVENT YOAST ARCHIVE TITLES*/

add_action( 'pre_get_posts', 'tribe_remove_wpseo_title_rewrite', 20 );
function tribe_remove_wpseo_title_rewrite() {
    if ( class_exists( 'Tribe__Events__Main' ) && class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() || tribe_is_map() || tribe_is_photo() || tribe_is_week() ) {
            $wpseo_front = WPSEO_Frontend::get_instance();
            remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
            remove_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
        }
    } elseif ( class_exists( 'Tribe__Events__Main' ) && !class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() ) {
            $wpseo_front = WPSEO_Frontend::get_instance();
            remove_filter( 'wp_title', array( $wpseo_front, 'title' ), 15 );
            remove_filter( 'pre_get_document_title', array( $wpseo_front, 'title' ), 15 );
        }
    }
};


/**
 * Enable custom field support for venue posts.
 *
 * @param  array $args
 * @return array
 */
function tribe_venues_custom_field_support( $args ) {
	$args['supports'][] = 'custom-fields';
	return $args;
}

add_filter( 'tribe_events_register_venue_type_args', 'tribe_venues_custom_field_support' );


function tribe_remove_json_ld_month() {
	if ( tribe_is_month() ) {
		tribe_remove_anonymous_hook( 'wp_head', 'Tribe__Events__Template__Month', 'json_ld_markup' );
	}
}
add_action( 'template_redirect', 'tribe_remove_json_ld_month', 100 );
if ( ! function_exists( 'tribe_remove_anonymous_hook' ) ) {
	/**
	 * Removes a filter or action added by anonymous objects
	 *
	 * Use this when you can not get the instance of a class attached to a hook.
	 * If the given method is attached multiple times to the hook, only one is removed.
	 *
	 * Example: tribe_remove_anonymous_hook( 'plugins_loaded', 'Tribe__Class', 'method_name' );
	 *
	 * @param string        $tag             Name of the filter or action.
	 * @param object|string $anonymous_class Object or classname that contains the method.
	 * @param string        $method          Method name.
	 * @param int           $priority        Priority the hook was attached to.
	 */
	function tribe_remove_anonymous_hook( $tag, $anonymous_class, $method, $priority = 10 ) {
		global $wp_filter;
		if ( ! isset( $wp_filter[ $tag ] ) ) {
			return;
		}
		$wp_hook = $wp_filter[ $tag ];
		// Ensure callbacks are attached to the priority
		if ( ! isset( $wp_hook->callbacks[ $priority ] ) ) {
			return;
		}
		foreach ( $wp_hook->callbacks[ $priority ] as $callback ) {
			$function = $callback['function'];
			// Skip callbacks that aren't methods
			if ( ! is_array( $function ) ) {
				continue;
			}
			// Check the class type and method name until we find a match.
			if (
				$function[0] instanceof $anonymous_class &&
				$function[1] === $method
			) {
				remove_filter( $tag, $function, $priority );
				break;
			}
		}
	}
}


/*
* Events Tickets Plus - WooCommerce Tickets - Prevent Ticket Email from being sent.
* @ Version 4.0
*/
add_action( 'init', 'wootickets_stop_sending_email' );
function wootickets_stop_sending_email() {
	if ( class_exists( 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' ) ) {
		$woo = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_instance();
		remove_filter( 'woocommerce_email_classes', array( $woo, 'add_email_class_to_woocommerce' ) );
		add_action( 'woocommerce_email_after_order_table', array( $woo, 'add_tickets_msg_to_email' ) );
	}
}

/*
* Events Tickets Plus - WooCommerce Tickets - Hide You'll receive your tickets in another email.
* @ Version 4.0
*/
add_filter( 'wootickets_email_message', 'woo_tickets_filter_completed_order', 10 );
function woo_tickets_filter_completed_order( $text ) {
	$text = "";

	return $text;
}

// [print_button]
function print_button_shortcode( $atts ){
return '<a class="print-link" href="javascript:window.print()">Print This Page</a>';
}
add_shortcode( 'print_button', 'print_button_shortcode' );


/**
 * Redirect event category requests to list view.
 *
 * @param $query
 */

function use_list_view_for_categories( $query ) {
	// Disregard anything except a main archive query
	if ( is_admin() || ! $query->is_main_query() || ! is_archive() ) return;

	// We only want to catch *event* category requests being issued
	// against something other than list view
	if ( ! $query->get( 'tribe_events_cat' ) ) return;
	if ( tribe_is_list_view() ) return;

	// Get the term object
	$term = get_term_by( 'slug', $query->get( 'tribe_events_cat' ), Tribe__Events__Main::TAXONOMY );

	// If it's invalid don't go any further
	if ( ! $term ) return;

	// Get the list-view taxonomy link and redirect to it
	header( 'Location: ' . tribe_get_listview_link( $term->term_id ) );
	exit();
}

// Use list view for category requests by hooking into pre_get_posts for event queries
add_action( 'tribe_events_pre_get_posts', 'use_list_view_for_categories' );


/**
 * Allows visitors to page forward/backwards in any direction within month view
 * an "infinite" number of times (ie, outwith the populated range of months).
 */
if ( class_exists( 'Tribe__Events__Main' ) ) {
	class ContinualMonthViewPagination {
	    public function __construct() {
	        add_filter( 'tribe_events_the_next_month_link', array( $this, 'next_month' ) );
	        add_filter( 'tribe_events_the_previous_month_link', array( $this, 'previous_month' ) );
	    }
	    public function next_month() {
	        $url = tribe_get_next_month_link();
	        $text = tribe_get_next_month_text();
	        $date = Tribe__Events__Main::instance()->nextMonth( tribe_get_month_view_date() );
	        return '<a data-month="' . $date . '" href="' . $url . '" rel="next">' . $text . ' <span>&raquo;</span></a>';
	    }
	    public function previous_month() {
	        $url = tribe_get_previous_month_link();
	        $text = tribe_get_previous_month_text();
	        $date = Tribe__Events__Main::instance()->previousMonth( tribe_get_month_view_date() );
	        return '<a data-month="' . $date . '" href="' . $url . '" rel="prev"><span>&laquo;</span> ' . $text . ' </a>';
	    }
	}
	new ContinualMonthViewPagination;
}

add_filter( 'tribe_tickets_attendees_admin_expire', 'custom_tickets_attendees_expire' );
add_filter( 'tribe_tickets_attendees_expire', 'custom_tickets_attendees_expire' );
function custom_tickets_attendees_expire () {
    return 0;
}

/**
 *  Event Tickets Plus - Disable Taxes for Ticket Products
 */
 add_action( 'event_tickets_after_save_ticket', 'tribe_disable_taxes_ticket_product', 10, 2 );
 function tribe_disable_taxes_ticket_product( $post_id, $ticket ) {
 update_post_meta( $ticket->ID, '_tax_status', 'none' );
 update_post_meta( $ticket->ID, '_tax_class', 'zero-rate' );
 }

 add_filter( 'register_post_type_args', function( $args, $post_type ) {

     if ( 'tribe_events' === $post_type ) {
         $args['show_in_graphql'] = true;
         $args['graphql_single_name'] = 'tribeEvent';
         $args['graphql_plural_name'] = 'tribeEvents';
     }

     return $args;

 }, 10, 2 );
