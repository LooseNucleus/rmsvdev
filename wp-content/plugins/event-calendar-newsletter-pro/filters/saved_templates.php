<?php

function & ecn_pro_get_feed( $saved_template_id ) {
	global $ecn_pro;

	// Load in the plugin functions for some of the ECN feed detection methods to work
	include_once(ABSPATH.'wp-admin/includes/plugin.php');

	$feed = ECNCalendarFeedFactory::create( ecn_get_saved_template_value( $saved_template_id, 'event_calendar' ) );
	return $feed;
}

function ecn_get_start_date( $saved_template_id ) {
	return strtotime( current_time( 'Y-m-d' ) . ' ' . '00:00:00' );
}

function ecn_get_end_date( $saved_template_id ) {
	return ecn_get_start_date( $saved_template_id ) + ( 86400 * ( intval( ecn_get_saved_template_value( $saved_template_id, 'events_future_in_days' ) ) + 1 ) );
}

function ecn_get_saved_template_data( $saved_template_id ) {
	return get_post_meta( $saved_template_id, 'data', true );
}

/**
 * Helper function to get a saved template value.  Uses the ID in the s
 *
 * @param id
 * @param $key
 * @param $sub_key mixed optional if value in key is an array, fetches a key within that array
 *
 * @return mixed
 */
function ecn_get_saved_template_value( $saved_template_id, $key, $sub_key = false ) {
	$id = intval( $saved_template_id );
	$data = ecn_get_saved_template_data( $id );
	if ( ! is_array( $data ) or ! isset( $data[$key] ) or ( false !== $sub_key and ! isset( $data[$key][$sub_key]) ) )
		return false;
	if ( false !== $sub_key )
		return $data[$key][$sub_key];
	return $data[$key];
}

/**
 * Updates an individual value within the saved template data
 *
 * @param $saved_template_id
 * @param $key
 * @param $value
 *
 * @return bool
 */
function ecn_set_saved_template_value( $saved_template_id, $key, $value ) {
	$id = intval( $saved_template_id );
	$data = ecn_get_saved_template_data( $id );
	if ( ! is_array( $data ) )
		return false;
	$data[$key] = $value;
	ecn_save_template_data( $id, $data );
	return true;
}

/**
 * Saves the data for this template
 *
 * @param $saved_template_id
 * @param $data
 */
function ecn_save_template_data( $saved_template_id, $data ) {
	update_post_meta( $saved_template_id, 'data', $data );
}

function ecn_get_saved_template_id() {
	if ( isset( $_GET['saved_template_id' ] ) )
		return intval( $_GET['saved_template_id' ] );
	return false;
}

/**
 * Class ECN_Saved_Templates
 */
class ECN_Saved_Templates {
	function __construct() {
		global $ecn_pro;

		if ( ! $ecn_pro->load_addon() )
			return;

		add_action( 'admin_init', array( &$this, 'process_actions' ) );
		add_action( 'admin_menu', array( &$this, 'add_saved_templates_menu' ), 99 );
		add_action( 'ecn_main_before_results', array( &$this, 'output_save_results_template' ) );
		if ( is_admin() ) {
			add_action( 'wp_ajax_ecn_pro_save_template', array( &$this, 'ajax_save_template' ) );
			add_action( 'wp_ajax_ecn_pro_save_results', array( &$this, 'ajax_save_results' ) );
			add_action( 'wp_ajax_ecn_pro_save_template_feed_event_publication_date', array( &$this, 'ajax_save_template_feed_event_publication_date' ) );
		}
		add_filter( 'template_include', array( &$this, 'override_template_with_rss' ), 100 );
		add_filter( 'ecn_settings_title', array( &$this, 'show_saved_template_title' ) );
		add_action( 'ecn_after_settings_title', array( &$this, 'show_add_new_button' ) );
		add_filter( 'ecn_settings_data', array( &$this, 'load_saved_values' ) );
		add_filter( 'ecn_get_all_options', array( &$this, 'get_saved_values' ) );
		add_action( 'ecn_settings_after_fetch_events', array( &$this, 'show_save_button_when_editing' ) );
		add_action( 'admin_init', array( &$this, 'remove_free_notices' ) );

		// Template overrides for RSS output
		add_filter( 'ecn_format_output_from_event_after', array( &$this, 'add_rss_tags_before_output' ), 1000, 4 );
		add_filter( 'ecn_format_output_from_event_after', array( &$this, 'wrap_content_in_rss_tags' ), 998, 4 );
		add_filter( 'ecn_format_output_from_event_after', array( &$this, 'add_rss_tags_after_output' ), 999, 4 );
	}

	/**
	 * RSS output functions
	 */
	function is_feed_publication_today( $args ) {
		// avoid changing if columns is enabled
		return ( 'start_date' != $args['feed_event_publication_date'] and ( ! isset( $args['enable_columns'] ) or ! $args['enable_columns'] or ! isset( $args['column_count'] ) or ! is_numeric( $args['column_count'] ) or $args['column_count'] <= 1 ) );
	}

	function add_rss_tags_before_output( $output, $event, $args, $previous_date ) {
		if ( isset( $args['is_rss'] ) and true === $args['is_rss'] and apply_filters( 'ecn_saved_template_add_rss_tags_before_output', true, $event, $args ) ) {
		    $guid_suffix = '';
			$tags = '<item>';
			$tags .= '<title>' . esc_html( $event->get_title() ) . '</title>';
			$tags .= '<link>' . esc_url( $event->get_link() ) . '</link>';
			$tags .= '<pubDate>';

			if ( 'start_date' == $args['feed_event_publication_date'] ) {
			    $current_timezone = date_default_timezone_get();
				date_default_timezone_set( get_option( 'timezone_string' ) );
				$tags .= mysql2date( 'D, d M Y H:i:s', date( 'Y-m-d H:i:s', $event->get_start_date() ), false ) . ' ' . current_time( 'O' );
				date_default_timezone_set( $current_timezone );
			} elseif ( 'published_date' == $args['feed_event_publication_date'] ) {
				$current_timezone = date_default_timezone_get();
				date_default_timezone_set( get_option( 'timezone_string' ) );
				$tags .= mysql2date( 'D, d M Y H:i:s', date( 'Y-m-d H:i:s', $event->get_published_date() ), false ) . ' ' . current_time( 'O' );
				date_default_timezone_set( $current_timezone );
			} else {
				// setting pubDate to midnight today so it always appears 'new' to MailChimp
				$tags .= mysql2date( 'D, d M Y H:i:s +0000', date( 'Y-m-d' ) . ' 00:00:00', false );
				// modify the guid with the current time so Campaign Monitor and others also see it as a 'new' RSS item
				$guid_suffix = time();
			}
			$tags .= '</pubDate>';
			$tags .= '<guid isPermaLink="false">' . esc_html( apply_filters( 'ecn_rss_event_guid', $event->get_guid() ) ) . $guid_suffix . '</guid>';
			$output = $tags . $output;
		}
		return $output;
	}

	function add_rss_tags_after_output( $output, $event, $args, $previous_date ) {
		if ( isset( $args['is_rss'] ) and true === $args['is_rss'] and apply_filters( 'ecn_saved_template_add_rss_tags_after_output', true, $event, $args ) ) {
			$output .= '</item>';
		}
		return $output;
	}

	function wrap_content_in_rss_tags( $event_output, $event, $args, $previous_date ) {
		if ( isset( $args['is_rss'] ) and true === $args['is_rss'] and apply_filters( 'ecn_saved_template_wrap_content_in_rss_tags', true, $event, $args ) ) {
			$event_output = '<description><![CDATA[' . $event_output . ']]></description>
				<content:encoded><![CDATA[' . $event_output . ']]></content:encoded>';
		}
		return $event_output;
	}

	function remove_free_notices() {
		global $ecn_admin_class;
		remove_action( 'ecn_main_before_results', array( $ecn_admin_class, 'save_templates_notice' ) );
	}

	function show_save_button_when_editing() {
		if ( ! ecn_get_saved_template_id() )
			return;
		?>
		<input id="save_template" data-post-id="<?= ecn_get_saved_template_id() ?>" type="submit" value="<?= esc_attr( __( 'Save Template', 'event-calendar-newsletter' ) ) ?>" class="button" /> <span id="save_template_message"></span>
		<?php
	}

	function show_saved_template_title( $title ) {
		if ( ecn_get_saved_template_id() )
			$title = get_the_title( ecn_get_saved_template_id() );
		return $title;
	}

	function show_add_new_button() {
		if ( ecn_get_saved_template_id() ) {
			?>
			<a href="<?= admin_url( 'admin.php?page=eventcalendarnewsletter' ) ?>" class="page-title-action"><?php echo __( 'Add New' ) ?></a>
			<?php
		}
	}

	/**
	 * Load the saved values for the edit screen
	 */
	function get_saved_values( $data ) {
		if ( ecn_get_saved_template_id() )
			$data = ecn_get_saved_template_data( ecn_get_saved_template_id() );
		return $data;
	}

	function load_saved_values( $data ) {
		if ( ecn_get_saved_template_id() ) {
			// Load any other saved data but keep loaded data as default
			$data['group_events'] = ecn_get_saved_template_value( ecn_get_saved_template_id(), 'group_events' );
			$data = wp_parse_args( $data, ecn_get_saved_template_data( ecn_get_saved_template_id() ) );
		}
		return $data;
	}

	function add_saved_templates_menu() {
		add_submenu_page(
			'eventcalendarnewsletter',
			__( 'Event Calendar Newsletter Pro', 'event-calendar-newsletter' ),
			__( 'Saved Templates', 'event-calendar-newsletter' ),
			apply_filters( 'ecn_admin_capability', 'add_users' ),
			'saved-ecn-templates',
			array( &$this, 'saved_templates_page' )
		);
	}

	function saved_templates_page() {
		include( dirname( __FILE__ ) . '/../templates/admin/saved_templates.php' );
	}

	/**
	 * Process any actions for the page before the template is rendered
	 */
	function process_actions() {
		if ( isset( $_GET['_wpnonce'], $_GET['action'], $_GET['post'], $_GET['page'] ) and 'saved-ecn-templates' == $_GET['page'] ) {
			switch ( $_GET['action'] ) {
				case 'clear_cache':
					if ( wp_verify_nonce( $_GET['_wpnonce'], 'ecn_clear_cache_' . intval( $_GET['post'] ) ) ) {
						delete_transient( $this->get_events_transient_id( intval( $_GET['post'] ) ) );
						delete_transient( 'ecn_mailpoet_all_output_' . intval( $_GET['post'] ) );
						die( __( 'Cache cleared', 'event-calendar-newsletter' ) );
					}
					break;
			}
		}
	}

	/**
	 * Updates an existing template
	 */
	function ajax_save_template() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ecn_admin' ) or
		     ! current_user_can( apply_filters( 'ecn_admin_capability', 'add_users' ) ) ) {
			status_header( 500 );
			die();
		}

		if ( ! isset( $_POST['data'], $_POST['id'] ) )
			die();

		// Save the form's data in the post meta
		parse_str( $_POST['data'], $data );
		ecn_save_template_data( intval( $_POST['id'] ), $data );

		echo __( 'Saved!', 'event-calendar-newsletter' );
		die();
	}

	/**
	 * Save the feed event publication date override value for this saved template, which determines if events are
	 * shown more than once for an RSS campaign
	 */
	function ajax_save_template_feed_event_publication_date() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ecn_admin' ) or
		     ! current_user_can( apply_filters( 'ecn_admin_capability', 'add_users' ) ) or
		     ! isset( $_POST['saved_template_id'], $_POST['feed_event_publication_date'] ) ) {
			status_header( 500 );
			die();
		}
		if ( ! ecn_set_saved_template_value( intval( $_POST['saved_template_id'] ), 'feed_event_publication_date', $_POST['feed_event_publication_date'] ) ) {
			status_header( 500 );
			die( 'Unable to save!' );
		}
		die( 'Saved' );
	}

	/**
	 * Saves the form data as a new template, as a new post
	 */
	function ajax_save_results() {
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ecn_admin' ) or
		     ! current_user_can( apply_filters( 'ecn_admin_capability', 'add_users' ) ) )
			die();
		if ( ! $this->create_new_saved_template( $_POST ) ) {
			echo __( 'Unable to save template', 'event-calendar-newsletter' );
			status_header( 500 );
			die();
		}
		echo sprintf( __( 'Saved successfully!  %sView saved templates%s', 'event-calendar-newsletter' ), '<a href="' . admin_url( 'admin.php?page=saved-ecn-templates' ) . '">', '</a>' );
		die();
	}

	/**
	 * Create a new
	 * @param $template_data array that contains title, and the data element which is the submitted form
	 *
	 * @return bool
	 */
	function create_new_saved_template( $template_data ) {
		$post_id = wp_insert_post(
			array(
				'post_title' => $template_data['title'],
				'post_status' => 'publish',
				'post_type' => ECNPro::SAVED_TEMPLATE_CPT
			)
		);

		if ( ! is_numeric( $post_id ) )
			return false;

		// Save the form's data in the post meta
		parse_str( $template_data['data'], $data );
		ecn_save_template_data( $post_id, $data );

		return $post_id;
	}

	/**
	 * HTML to save the generated results on the main settings page
	 */
	function output_save_results_template() {
		?>
		<div id="poststuff">
			<div id="save_results_box" class="postbox">
				<h2 class="hndle">
					<span><?php echo esc_html__( 'Save as Template', 'event-calendar-newsletter' ) ?></span>
				</h2>
				<div class="inside">
					<p><?php echo esc_html__( 'Allows you to automatically add events into MailChimp and quickly re-generate the results from the Saved Templates screen.', 'event-calendar-newsletter' ) ?></p>
					<p><strong><?php echo esc_html__( 'Title', 'event-calendar-newsletter' ) ?></strong></p>
					<input type="text" required class="save_results_title" />
					<p><input type="submit" class="save_results button button-primary button-large" value="<?php echo esc_attr__( 'Save', 'event-calendar-newsletter' ) ?>" /></p>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * If this is a ECN post, replace it with the RSS feed template
	 *
	 * @param $template
	 *
	 * @return string
	 */
	function override_template_with_rss( $template ) {
		global $ecn_pro;
		if ( is_singular( ECNPro::SAVED_TEMPLATE_CPT ) or ECNPro::SAVED_TEMPLATE_CPT == get_post_type() ) {
			$template = plugin_dir_path( __FILE__ ) . '../templates/ecn-rss.php';

			// Ensure the feeds are loaded, and include the admin function required to use
			// is_plugin_active() on the front end
			if ( ! function_exists( 'is_plugin_active' ) )
				include_once(ABSPATH.'wp-admin/includes/plugin.php');
			$ecn_pro->load_feeds();
		}
		return $template;
	}

	function get_events_transient_id( $post_id ) {
		return 'ecn_pro_rss_events-' . $post_id;
	}
}
