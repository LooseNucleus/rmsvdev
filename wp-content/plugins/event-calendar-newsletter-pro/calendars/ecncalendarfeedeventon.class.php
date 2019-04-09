<?php
if ( ! class_exists( 'ECNCalendarFeedEventon' ) ) {

class ECNCalendarFeedEventon extends ECNCalendarFeed {
	protected $REPEAT_DAY = 'daily';
	protected $REPEAT_WEEK = 'weekly';
	protected $REPEAT_MONTH = 'monthly';
	protected $REPEAT_YEAR = 'yearly';

	function get_available_format_tags() {
        return array(
            'start_date',
	        'start_time',
            'end_date',
	        'end_time',
            'title',
	        'subtitle',
            'description',
            'excerpt',
            'location_name',
            'location_address',
            'location_city',
            'location_state',
            'location_country',
            'contact_name',
            'contact_info',
            'link',
	        'link_url',
	        'colour',
            'event_image',
	        'event_image_url',
	        'event_website',
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
        global $post, $eventon;
        $retval = array();

	    // everything is stored in UTC so need to set that timezone
	    date_default_timezone_set('UTC');

	    // TODO: Add hide_mult_occur option to hide all but first of multi-occurrence?


        // Default arguments normally from shortcode, will add in ours afterwards
        $shortcode_args = apply_filters( 'ecn_fetch_events_extra_args-' . $this->get_identifier(), array(
            'focus_start_date_range' => $start_date,
            'focus_end_date_range' => $end_date,
        ), $start_date, $end_date, $data );

        $post_args = apply_filters(
            'ecn_fetch_events_args-' . $this->get_identifier(),
            array(),
            $start_date,
            $end_date,
            $data
        );

        $event_list_array = $eventon->evo_generator->evo_get_wp_events_array(
            $post_args,
            $shortcode_args
        );

	    foreach ( $event_list_array as $event ) {
	        $post = get_post( $event['event_id'] );
			setup_postdata( $post );
	        $current_start_date = date( 'Y-m-d H:i:s', intval( $event['event_start_unix'] ) );
	        $current_end_date = date( 'Y-m-d H:i:s', intval( $event['event_end_unix'] ) );
	        if ( strtotime( $current_start_date ) < $start_date ) {
		        continue;
	        }
	        if ( strtotime( $current_start_date ) > $end_date ) {
                continue;
            }
            $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), apply_filters( 'ecn_image_size', 'medium', get_the_ID() ) );
            if ( !empty( $image_src ) )
                $image_url = $image_src[0];
            else
                $image_url = false;

            // location if any
            $location = $location_meta = false;
            $locations = get_the_terms( $post->ID, 'event_location' );
            if ( is_array( $locations ) ) {
                $location = array_shift( $locations );
                $location_meta = evo_get_term_meta( 'event_location', $location->term_id );
            }

	        $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
		        'plugin' => $this->get_identifier(),
		        'start_date' => $current_start_date,
		        'end_date' => ( get_post_meta( $post->ID, 'evo_hide_endtime', true ) == 'yes' ? 0 : $current_end_date ),
		        'published_date' => get_the_date( 'Y-m-d H:i:s', $post->ID ),
		        'title' => stripslashes_deep( $post->post_title ),
		        'subtitle' => get_post_meta( $post->ID, 'evcal_subtitle', true ),
		        'description' => stripslashes_deep( $post->post_content ),
		        'excerpt' => stripslashes_deep( $post->post_excerpt ),
		        'all_day' => ( get_post_meta( $post->ID, 'evcal_allday', true ) == 'yes' ? true : false ),
		        'featured' => ( get_post_meta( $post->ID, '_featured', true ) == 'yes' ? true : false ),
		        'color' => get_post_meta( $post->ID, 'evcal_event_color', true ),

		        'location_name' => ( $location ? $location->name : '' ),
		        'location_address' => ( $location_meta ? $location_meta['location_address'] : '' ),
                'location_city' => ( $location_meta ? $location_meta['location_city'] : '' ),
                'location_state' => ( $location_meta ? $location_meta['location_state'] : '' ),
                'location_country' => ( $location_meta ? $location_meta['location_country'] : '' ),

		        'contact_name' => get_post_meta( $post->ID, 'evcal_organizer', true ),
		        'contact_info' => get_post_meta( $post->ID, 'evcal_org_contact', true ),

		        'repeat_freq' => ( get_post_meta( $post->ID, 'evcal_repeat', true ) == 'yes' ? get_post_meta( $event->ID, 'evcal_rep_freq', true ) : false ),

		        'link' => get_the_permalink(),
		        'event_image_url' => $image_url,
		        'event_website' => get_post_meta( $post->ID, 'evcal_lmlink', true ),
	        ) ) );
            $retval[] = $ecn_event;
        }
        return $retval;
    }

    function get_description() {
        return 'EventON';
    }

    function get_identifier() {
        return 'eventon';
    }

    function is_feed_available() {
        return class_exists( 'EventON' );
    }
}
}