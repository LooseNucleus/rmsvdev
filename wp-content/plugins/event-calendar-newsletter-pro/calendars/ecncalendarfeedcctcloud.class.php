<?php
if ( ! class_exists( 'ECNCalendarFeedCctCloud' ) ) {

class ECNCalendarFeedCctCloud extends ECNCalendarFeed {
	function get_available_format_tags() {
        return array(
            'start_date',
	        'start_time',
            'end_date',
	        'end_time',
            'title',
            'description',
	        'event_cost',
            'location_name',
            'location_address',
	        'location_city',
	        'location_zip',
	        'location_website',
	        'location_phone',
			'contact_email',
            'link',
	        'link_url',
	        'categories',
        );
    }

    function get_all_categories() {
	    $midbi = new Midbi();
	    $midbi->init();
	    $cat_url = $midbi->api . 'ecategory/';
	    $MidbiRest = new MidbiRest();
	    $raw_categories = $MidbiRest->Process( $cat_url );
	    $categories = array();
	    if ( is_array( $raw_categories ) ) {
	    	foreach ( $raw_categories as $category ) {
	    		$categories[ intval( $category->details->cat_id ) ] = $category->details;
		    }
	    }
	    return $categories;
    }

    /**
     * @param $start_date int
     * @param $end_date int
     * @param $data array
     * @return ECNCalendarEvent[]
     */
    function get_events( $start_date, $end_date, $data = array() ) {
        global $post;
        $retval = array();

	    $MidbiEvent = new MidbiEvent();
		$events = $MidbiEvent->GetEvents( '', apply_filters( 'ecn_fetch_events_args-' . $this->get_identifier(), array(), $start_date, $end_date, $data ) );
	    $categories = $this->get_all_categories();

	    foreach ( $events as $id => $event ) {
		    $current_start_date = $event->details->date_start;
		    $current_end_date = $event->details->date_end;
		    // check start/end time but don't break as other date/times might match
		    if ( strtotime( $current_start_date ) < $start_date ) {
			    continue;
		    }
		    if ( strtotime( $current_start_date ) >= $end_date )
			    continue;

		    // Only allow approved and published events
		    if ( 'Y' != $event->details->approved || 'Y' != $event->details->active )
		    	continue;

		    $event_categories = array();
		    if ( is_array( $event->categories ) ) {
		    	foreach ( $event->categories as $event_category ) {
		    		if ( array_key_exists( intval( $event_category ), $categories ) ) {
		    			$event_categories[ intval( $event_category ) ] = $categories[ intval( $event_category ) ];
				    }
			    }
		    }

		    $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
			    'plugin' => $this->get_identifier(),
			    'start_date' => $current_start_date,
			    'end_date' => $current_end_date,
			    'published_date' => $event->details->date_created,
				'title' => sanitize_text_field( $event->details->event_name ),
				'description' => sanitize_text_field( $event->details->description ),

				'location_name' => ( $event->details->location ? sanitize_text_field( $event->details->location ) : false ),
				'location_address' => ( $event->details->address1 ? sanitize_text_field( $event->details->address1 ) : false ),
				'location_city' => ( $event->details->city ? sanitize_text_field( $event->details->city ) : false ),
				'location_zip' => ( $event->details->postalcode ? sanitize_text_field( $event->details->postalcode ) : false ),
				'location_website' => ( $event->details->website ? sanitize_text_field( $event->details->website ) : false ),
				'location_phone' => ( $event->details->phone ? sanitize_text_field( $event->details->phone ) : false ),

				'contact_email' => ( $event->details->email ? sanitize_text_field( $event->details->email ) : false ),
				'link' => esc_url( site_url( '/events/id/' . $event->details->nicename ) ),
			    'categories' => $event_categories,
		    ) ) );
		    $retval[] = $ecn_event;

	    }

	    $retval = $this->sort_events_by_start_date( $retval );
	    return $retval;
    }

    function get_description() {
        return 'CCT Cloud';
    }

    function get_identifier() {
        return 'cct-cloud';
    }

    function is_feed_available() {
        return defined( 'CCT_MIDBI_VERSION' );
    }
}
}