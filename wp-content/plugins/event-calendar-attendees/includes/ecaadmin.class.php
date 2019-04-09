<?php

define( 'ECA_VERSION', 12 );
define( 'ECA_SAVED_OPTIONS_NAME', 'eca_saved_options' );
define( 'ECA_CUSTOM_DATE_RANGE_DAYS', 0 );
/**
 * Helper functions to get/save ECA specific options
 */

if ( ! function_exists( 'get_eca_option' ) ) {
	function get_eca_option( $option, $default = '' ) {
		global $eca_admin_class;
		return $eca_admin_class->get_eca_option( $option, $default );
	}

	function save_eca_option( $option, $value ) {
		global $eca_admin_class;
		return $eca_admin_class->save_eca_option( $option, $value );
	}
}

if ( ! class_exists( 'ECAAdmin' ) ) {

class ECAAdmin {
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
        if ( is_admin() ) {
            add_action( 'admin_init', array( &$this, 'admin_init' ) );
            add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
            add_action( 'wp_ajax_fetch_events', array( &$this, 'ajax_fetch_events' ) );
            add_action( 'wp_ajax_fetch_other_plugin_options', array( &$this, 'ajax_fetch_other_plugin_options' ) );
            add_action( 'wp_ajax_fetch_allowed_tags', array( &$this, 'ajax_fetch_allowed_tags' ) );
            if ( isset( $_GET['page'] ) and 'eventcalendarattendees' == $_GET['page'] ) {
                add_action( 'admin_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
            }
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_menu_css' ) );

	        add_action( 'eca_main_before_results', array( &$this, 'save_templates_notice' ) );

            // Load any additional settings for plugins
            add_action( 'eca_additional_filters_settings_html', array( &$this, 'load_additional_settings' ) );
        }
    }

    function init() {
    }

    function admin_init() {
    }

    function admin_menu_css() {
	    wp_register_style( 'eca.icon.css', plugins_url( 'css/icon.css', __FILE__ ), false, ECA_VERSION );
	    wp_enqueue_style( 'eca.icon.css' );
    }

    function enqueue_scripts() {
        wp_register_script( 'eca.admin.js', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery', 'backbone', 'underscore', 'jquery-ui-core', 'jquery-ui-sortable' ), ECA_VERSION );
        wp_enqueue_script( 'eca.admin.js' );
        wp_register_style( 'eca.admin.css', plugins_url( 'css/admin.css', __FILE__ ), false, ECA_VERSION );
        wp_enqueue_style( 'eca.admin.css' );
    }

    function admin_menu() {
        add_menu_page( __( 'Event Calendar Attendees', 'event-calendar-attendees' ), __( 'Event Calendar Attendees', 'event-calendar-attendees' ), apply_filters( 'eca_admin_capability', 'add_users' ), 'eventcalendarattendees', array( &$this, 'admin_page' ), null, 41 );
    }

	function save_templates_notice() {
		?>
		<div id="poststuff">
			<div id="save_results_box" class="postbox">
				<h2 class="hndle">
					<span><?php echo esc_html__( 'Save as Template', 'event-calendar-attendees' ) ?></span>
				</h2>
				<div class="inside">
					<p><?php echo esc_html__( 'This pro-only feature allows you to automatically add your events into newsletters with MailChimp, Active Campaign and more.  You can also save and quickly re-generate these results with Saved Templates.', 'event-calendar-attendees' ); ?></p>
					<a target="_blank" href="https://eventcalendarattendees.com/pro/?utm_source=wordpress.org&utm_medium=link&utm_campaign=event-cal-plugin&utm_content=savedtemplates" class="upgrade button button-primary"><?php echo esc_html__( 'Get Event Calendar Attendees Pro', 'event-calendar-attendees' ) ?></a>
				</div>
			</div>
		</div>

	<?php
	}

    function get_available_calendar_feeds() {
        $available_feed_objects = ECACalendarFeedFactory::get_available_calendar_feeds();
        $available_feeds = array();
        foreach ( $available_feed_objects as $feed_object ) {
            $available_feeds[$feed_object->get_identifier()] = $feed_object->get_description();
        }
        return $available_feeds;
    }

    function load_additional_settings( $plugin ) {
        $filename = dirname( __FILE__ ) . '/admin/additional-settings/' . basename( $plugin ) . '.php';
        if ( file_exists( $filename ) )
            include( $filename );
    }

    private function get_eca_options() {
        return get_option( ECA_SAVED_OPTIONS_NAME, array() );
    }

    public function get_eca_option( $option_name, $default = '' ) {
        $eca_options = apply_filters( 'eca_get_all_options', $this->get_eca_options(), $option_name, $default );
        if ( isset( $eca_options[$option_name] ) )
            return $eca_options[$option_name];
        else
            return $default;
    }

    private function save_eca_options( $options ) {
        if ( ! is_array( $options ) )
            throw new Exception( __( 'Invalid options array', 'event-calendar-attendees' ) );
        add_option( ECA_SAVED_OPTIONS_NAME, $options );
        update_option( ECA_SAVED_OPTIONS_NAME, $options );
    }

    public function save_eca_option( $option_name, $value ) {
        $options = $this->get_eca_options();
        $options[$option_name] = $value;
        $this->save_eca_options( $options );
    }

    private function get_default_format() {
    	return __( "<h2>{title}</h2>\n{if_event_image}<p>{event_image}</p>{/if_event_image}\n<p>{start_date} @ {start_time}{if_end_time} to {end_time}{/if_end_time}{if_location_name} at {location_name}{/if_location_name}</p>\n<p>{description}</p>\n<p>{link}</p>", 'event-calendar-attendees' );
    }

    private function get_saved_format() {
    	if ( ! $this->get_eca_option( 'format', false ) )
    		return $this->get_eca_option( 'saved_format', wp_kses( $this->get_default_format(), array( 'h2' => array(), 'p' => array() ) ) );
	    return $this->get_eca_option( 'format' );
    }

    public function save_format( $format ) {
        $this->save_eca_option( 'saved_format', $format );
    }

    public function get_design() {
    	// If there's no saved design option and the format is not the default, its custom
    	if ( false === $this->get_eca_option( 'design', false ) and $this->get_saved_format() != $this->get_default_format() )
    		return 'custom';
	    return $this->get_eca_option( 'design', false );
    }

    public function save_design( $design ) {
    	$this->save_eca_option( 'design', $design );
    }

    public function get_future_events_to_use() {
        return $this->get_eca_option( 'events_future_in_days', 30 );
    }

	private function save_group_events_value( $group_events ) {
		$this->save_eca_option( 'saved_group_events', $group_events );
	}

	private function get_group_events_value() {
		return $this->get_eca_option( 'saved_group_events', 'normal' );
	}

    private function save_future_events_to_use( $future_events_to_use_in_days ) {
        $this->save_eca_option( 'events_future_in_days', $future_events_to_use_in_days );
    }

    private function get_event_calendar_plugin() {
        return $this->get_eca_option( 'event_calendar' );
    }

    private function save_event_calendar_plugin( $plugin ) {
        $this->save_eca_option( 'event_calendar', $plugin );
    }

    /**
     * Function to fetch any customizations for a plugin
     */
    function ajax_fetch_other_plugin_options() {
        if ( ! wp_verify_nonce( $_GET['nonce'], 'eca_admin' ) )
            die();
        do_action( 'eca_additional_filters_settings_html-' . $_GET['event_calendar'] );
        do_action( 'eca_additional_filters_settings_html', $_GET['event_calendar'] );
        die();
    }

    /**
     * Function to return the allowed tags for the given plugin
     */
    function ajax_fetch_allowed_tags() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'eca_admin' ) )
            die();
        echo json_encode( array( 'success' => true, 'result' => ECACalendarEvent::get_available_format_tags( $_POST['event_calendar'] ) ) );
        die();
    }

	/**
	 * Get the output for the given events and arguments
	 *
	 * Events are fetched via get_events() and data sanatized by process_and_sanitize_data()
	 *
	 * @param $events
	 * @param array $args
	 *
	 * @return string
	 */
	function get_output_from_events( $events, $args = array() ) {
		$default = array(
			'format' => '',
			'group_events' => 'normal',
		);
		$args = wp_parse_args( $args, $default );

		// Load up any output templates found
		foreach ( glob( trailingslashit( dirname( __FILE__ ) ) . 'output_formats/*.php' ) as $template ) {
			require_once( $template );
		}
		do_action( 'eca_load_output_formats', $events, $args );

		$output = '';
		$previous_date = strtotime( '2000-01-01' );
		$args['event_number'] = 0;
		foreach ( $events as $event ) {
			$args['event_number']++;
			$output .= "\n";
			$output .= $this->get_output_from_event( $event, $args, $previous_date );
			$previous_date = $event->get_start_date();
			if ( apply_filters( 'eca_limit_total_output', false, $args['event_number'] ) )
				break;
		}
		return apply_filters( 'eca_final_output_of_events', $output, $args, $events );
	}

	/**
	 * Get the output for an individual event
	 *
	 * @param $event
	 * @param $args array with format and group_events values
	 * @param $previous_date
	 *
	 * @return string
	 */
	function get_output_from_event( $event, $args, $previous_date ) {
		$output = '';
		$args = apply_filters( 'eca_override_args_for_output', $args, $event, $previous_date );
		$output = apply_filters( 'eca_format_output_from_event', $output, $event, $args, $previous_date );
		$output .= apply_filters( 'eca_event_output_from_format', $event->get_from_format( apply_filters( 'eca_output_format', $args['format'], $event, $args, $previous_date ) ), $event, $args, $previous_date );
		$output = apply_filters( 'eca_format_output_from_event_after', $output, $event, $args, $previous_date );
		return $output;
	}

    /**
     * Function to grab events for the given event calendar plugin
     *
     * @throws Exception
     */
    function ajax_fetch_events() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'eca_admin' ) )
            die();

        // Grab the serialized form data
        parse_str( $_POST['data'], $data );
	    if ( ! $this->is_data_valid( $data ) )
		    die( json_encode( array( 'error' => true, 'message' => 'Data not found' ) ) );
	    $data = $this->process_and_sanitize_data( $data );
	    $this->save_last_run_data( $data );
	    $output = $this->process_output( $data );
	    if ( is_string( $output ) ) {
		    echo json_encode( array(
				    'success' => true,
				    'result' => $output
			    )
		    );
	    } else {
		    // Exception
		    echo json_encode( array( 'error' => true, 'message' => $output->getMessage() ) );
	    }
	    die();
    }

	function is_data_valid( $data ) {
		if ( ! isset( $data['events_future_in_days'], $data['event_calendar'], $data['format'] ) )
			return false;
		return true;
	}

	/**
	 * When run via the admin UI, save the options so the next time the page is loaded the options are preserved
	 *
	 * @param $data
	 */
	function save_last_run_data( $data ) {
		$this->save_format( $data['format'] );
		$this->save_design( $data['design'] );
		$this->save_event_calendar_plugin( $data['event_calendar'] );
		$this->save_future_events_to_use( $data['events_future_in_days'] );
		$this->save_group_events_value( $data['group_events'] );

		// Allow filters to save any additional data, like category/tag filters
		do_action( 'eca_save_options-' . $data['event_calendar'], $data );
		do_action( 'eca_save_options', $data, $data['event_calendar'] );
	}

	/**
	 * Sanitize the data coming in from the form where needed
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	function process_and_sanitize_data( $data ) {
		$data['events_future_in_days'] = intval( $data['events_future_in_days'] );
		$data['format'] = stripslashes_deep( $data['format'] );
		$data['group_events'] = isset( $data['group_events'] ) ? $data['group_events'] : 'normal';
		return $data;
	}

	/**
	 *
	 *
	 * Additional data can be passed in unique to the events calendar being used.
	 * It will be passed via get_events() and get_output_from_event()
	 *
	 * Should be run through process_and_sanitize_data() first
	 *
	 * @param $data array See get_output_from_data() for details of what $data can have
	 * @return string of event output, or Exception if error
	 */
	function process_output( $data ) {
		try {
			return $this->get_output_from_data( $data );
		} catch ( Exception $e ) {
			return $e;
		}
	}

	/**
	 *
	 * @param $data array of options:
	 *
	 * events_future_in_days - number of days from now to fetch events, or 0 if custom
	 * event_calendar - the identifier of the calendar to fetch events from
	 * format - the format for a single event with tags like {title} and {link}
	 * group_events - whether to group events or not, or 'normal' if missing
	 * custom_date_from - custom date to start from in yyyy-mm-dd format. Requires events_future_in_days to be 0
	 * custom_date_to - custom date to end in yyyy-mm-dd
	 *
	 * @return string of event output
	 */
	function get_output_from_data( $data ) {
		if ( ! isset( $data['events'] ) )
			$data['events'] = $this->get_events( $data );
		return $this->get_output_from_events( $data['events'], $data );
	}

	/**
	 * Get the events based on the given data
	 *
	 * @param $data array See get_output_from_data()
	 * @return ECACalendarEvent[]
	 */
	function get_events( $data ) {
		$feed = ECACalendarFeedFactory::create( $data['event_calendar'] );

		// grab the start and end dates, and have the period end at midnight on the end date
		$start_date = strtotime( current_time( 'Y-m-d' ) . ' 00:00:00' );
		$end_date = $start_date + ( 86400 * ( $data['events_future_in_days'] + 1 ) );
		if ( ECA_CUSTOM_DATE_RANGE_DAYS == $data['events_future_in_days'] and isset( $data['custom_date_from'], $data['custom_date_to'] ) and FALSE !== strtotime( $data['custom_date_from'] ) and FALSE !== strtotime( $data['custom_date_to'] ) ) {
			$start_date = strtotime( $data['custom_date_from'] . ' 00:00:00' );
			// Calculate the end date as the very beginning of the next day
			$end_date = strtotime( $data['custom_date_to'] . ' 00:00:00' ) + 86400;
		}
		return $feed->get_events( $start_date, $end_date, $data );
	}

    function admin_page() {
	    // Check if the saved event calendar plugin is still available
	    $available_plugins = $this->get_available_calendar_feeds();
	    $event_calendar_plugin = $this->get_event_calendar_plugin();
		if ( ! isset( $available_plugins[$event_calendar_plugin] ) )
			// Plugin no longer available, clear the event calendar plugin option
			$this->save_event_calendar_plugin( '' );

	    $data = apply_filters( 'eca_settings_data', wp_parse_args( array(
		    'format' => $this->get_saved_format(),
		    'events_future_in_days' => $this->get_future_events_to_use(),
		    'event_calendar' => $this->get_event_calendar_plugin(),
		    'available_plugins' => $this->get_available_calendar_feeds(),
		    'group_events' => $this->get_group_events_value(),
		    'design' => $this->get_design(),
	    ), $this->get_eca_options() ) );

        include( dirname( __FILE__ ) . '/admin/main.php' );
    }
}

$GLOBALS['eca_admin_class'] = new ECAAdmin();
}