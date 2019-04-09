<?php
if ( ! class_exists( 'ECNCalendarFeedCalendarizeIt' ) ) {

	if( ! function_exists( 'rhc_get_events' ) ):
		function rhc_get_events( $args, $atts = array() ){
			if( ! class_exists('rhc_supe_query') ){
				require RHC_PATH.'includes/class.rhc_supe_query.php';
			}
			return rhc_supe_query::get_events( $args );
		}
	endif;

class ECNCalendarFeedCalendarizeIt extends ECNCalendarFeed {

	function get_available_format_tags() {
        return array(
            'start_date',
	        'start_time',
            'end_date',
	        'end_time',
            'title',
            'description',
            'excerpt',
            'location_name',
            'location_address',
	        'location_city',
	        'location_state',
	        'location_zip',
	        'location_country',
	        'location_website',
	        'location_phone',
            'link',
	        'link_url',
            'event_image',
	        'event_image_url',
	        'all_day',
        );
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

	    $post_args = apply_filters( 'ecn_fetch_events_args-' . $this->get_identifier(), array(
		    'page' => '0',
		    'number' => '10000',
		    'date' => date('Y-m-d H:i:s', $start_date),
		    'date_end' => date('Y-m-d H:i:s', $end_date),
	    ), $start_date, $end_date, $data );
	    $events = rhc_get_events( $post_args );

	    foreach ( $events as $event ) {

            // images property on the event
            $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( $event->ID ), apply_filters( 'ecn_image_size', 'medium', $event->ID ) );
            if ( !empty( $image_src ) )
                $image_url = $image_src[0];
            else
                $image_url = false;

			$venues = get_the_terms( $event->ID, 'venue' );
			$venue = ( is_array( $venues ) and count( $venues ) ) ? $venues[0] : false;

	        $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
		        'plugin' => $this->get_identifier(),
		        'start_date' => $event->event_start,
		        'end_date' => $event->event_end,
		        'published_date' => get_the_date( 'Y-m-d H:i:s', $event->ID ),
		        'title' => stripslashes_deep( $event->post_title ),
		        'description' => stripslashes_deep( $event->post_content ),
		        'excerpt' => stripslashes_deep( $event->post_excerpt ),
		        'all_day' => intval( $event->allday ) ? true : false,
		        'location_name' => ( $venue ? $venue->name : '' ),
		        'location_address' => ( $venue ? get_term_meta( $venue->term_id, 'address', true ) : '' ),
		        'location_city' => ( $venue ? get_term_meta( $venue->term_id, 'city', true ) : '' ),
		        'location_state' => ( $venue ? get_term_meta( $venue->term_id, 'state', true ) : '' ),
		        'location_zip' => ( $venue ? get_term_meta( $venue->term_id, 'zip', true ) : '' ),
		        'location_country' => ( $venue ? get_term_meta( $venue->term_id, 'country', true ) : '' ),
		        'location_website' => ( $venue ? get_term_meta( $venue->term_id, 'website', true ) : '' ),
		        'location_phone' => ( $venue ? get_term_meta( $venue->term_id, 'phone', true ) : '' ),
		        'link' => get_the_permalink( $event->ID ),
		        'event_image_url' => $image_url,
	        ) ) );
            $retval[] = $ecn_event;
        }
        return $retval;
    }

    function get_description() {
        return 'CalendarizeIt!';
    }

    function get_identifier() {
        return 'calendarize-it';
    }

    function is_feed_available() {
        return class_exists( 'plugin_righthere_calendar' );
    }
}
}