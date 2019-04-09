<?php
/**
 * understrap functions and definitions
 *
 * @package understrap
 */

/**
 * Theme setup and custom theme supports.
 */
require get_template_directory() . '/inc/setup.php';

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
require get_template_directory() . '/inc/widgets.php';


/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/custom-comments.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
* Load custom WordPress nav walker.
*/
require get_template_directory() . '/inc/bootstrap-wp-navwalker.php';

/**
* Load WooCommerce functions.
*/
require get_template_directory() . '/inc/woocommerce.php';

add_action( 'rmsv_woocommerce_single_product_tabs', 'woocommerce_output_product_data_tabs', 10 );

add_action( 'rmsv_woocommerce_single_related_products', 'woocommerce_output_related_products', 20 );


/*
 * Moves the front-end ticket purchase form, accepts WP action/hook and optional hook priority
 *
 * @param $ticket_location_action WP Action/hook to display the ticket form at
 * @param $ticket_location_priority Priority for the WP Action
 */
function tribe_etp_move_tickets_purchase_form ( $ticket_location_action, $ticket_location_priority = 10 ) {
	if ( ! class_exists( 'Tribe__Tickets__Tickets') ) return;
	$etp_classes = array(
		'Easy_Digital_Downloads' =>     'Tribe__Tickets_Plus__Commerce__EDD__Main',
		'ShoppVersion' =>               'Tribe__Tickets_Plus__Commerce__Shopp__Main',
		'WP_eCommerce' =>               'Tribe__Tickets_Plus__Commerce__WPEC__Main',
		'Woocommerce' =>                'Tribe__Tickets_Plus__Commerce__WooCommerce__Main',
		'Tribe__Tickets__Tickets' =>    'Tribe__Tickets__RSVP',
	);
	foreach ( $etp_classes as  $ecommerce_class => $ticket_class) {
		if ( ! class_exists( $ecommerce_class ) || ! class_exists( $ticket_class ) ) continue;
		$form_display_function = array( $ticket_class::get_instance(), 'front_end_tickets_form' );
		if ( has_action ( 'tribe_events_single_event_after_the_meta', $form_display_function ) ) {
			remove_action( 'tribe_events_single_event_after_the_meta', $form_display_function, 5 );
			add_action( $ticket_location_action, $form_display_function, $ticket_location_priority );
		}
	}
}
/*
 * TO MOVE THE TICKET FORM UNCOMMENT ONE OF THE FOLLOWING BY REMOVING THE //
 */
/*
 * Uncomment to Move Ticket Form Below Related Events
 */
//tribe_etp_move_tickets_purchase_form( 'tribe_events_single_event_after_the_meta', 20 );
/*
 * Uncomment to Move Ticket Form Below the Event Description
 */
tribe_etp_move_tickets_purchase_form( 'tribe_events_single_event_after_the_content', 5 );
/*
 * Uncomment to Move Ticket Form Above the Event Description
 */
//tribe_etp_move_tickets_purchase_form( 'tribe_events_single_event_before_the_content' );

//Remove User Profile fields
remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');

//Admin Bar Only for Contributors or Higher
function rmsv_remove_admin_bar( $show_admin_bar ) {
	if( current_user_can( 'edit_posts' ) ){
		return $show_admin_bar;
	}else{
		return false;
	}
}
add_filter( 'show_admin_bar' , 'rmsv_remove_admin_bar' );


add_image_size ('blog_image', 780, 390, false);
add_image_size ('large_background', 1920, 1080, false);
add_image_size ('sewing_classes', 534, 300, false);

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

add_filter('tribe_event_label_singular', 'change_single_event_label');
function change_single_event_label() {
	return 'Class';
}

add_filter('tribe_event_label_plural', 'change_plural_event_label');
function change_plural_event_label() {
	return 'Classes';
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

/*
*FUNCTION TO ADD ASYNC TO ALL SCRIPTS

function js_async_attr($tag){

# Do not add async to these scripts
$scripts_to_exclude = array('jquery.js', 'scripts.js', 'internal-scripts.js');

foreach($scripts_to_exclude as $exclude_script){
	if(true == strpos($tag, $exclude_script ) )
	return $tag;
}

# Add async to all remaining scripts
return str_replace( ' src', ' async="async" src', $tag );
}
add_filter( 'script_loader_tag', 'js_async_attr', 10 );
*/

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

/*-------------------------------------
 Move Yoast to the Bottom
---------------------------------------*/
function yoasttobottom() {
 return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');

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

/*
// Prepends category name(s) to the event titles
function tribe_events_title_include_cat ($title, $id) {

	$separator = ' &raquo; '; // HTML Separator between categories and title
	$cats = get_the_terms($id, 'tribe_events_cat');
	$is_ajax = defined('DOING_AJAX') && DOING_AJAX;
	$is_truly_admin = is_admin() && !$is_ajax;

	if (tribe_is_event($id) && $cats && !is_single() && !$is_truly_admin) {
		$cat_titles = array();
		foreach($cats as $i) {
			$cat_titles[] = $i->name;
		}
		$title = implode(', ', $cat_titles) . $separator . $title;
	}

	return $title;
}
add_filter('the_title', 'tribe_events_title_include_cat', 100, 2);
*/

/**
 * Removes json LD from loading on month view
 */
// function tribe_remove_json_ld_month( ) {
// 	global $wp_filter;
// 	if( tribe_is_month() ) {
// 		// Who needs remove_action() ?
// 		foreach ( $wp_filter[ 'wp_head' ][ 10 ] as $key => $filter ) {
// 			if( $filter['function'][ 1 ] == 'json_ld_markup' ) {
// 				$breakpoint = true;
// 				unset( $wp_filter[ 'wp_head' ][ 10 ][ $key ] );
// 			}
// 		}
// 	}
// }
// add_action( 'template_redirect', 'tribe_remove_json_ld_month', 100 );

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

/*
add_filter( 'tribe_events_add_no_index_meta', '__return_false' );
*/

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

/** Disable Ajax Call from WooCommerce on front page and posts*/
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11);
function dequeue_woocommerce_cart_fragments() {
if (is_front_page() || is_single() || tribe_is_month() ) wp_dequeue_script('wc-cart-fragments');
}
