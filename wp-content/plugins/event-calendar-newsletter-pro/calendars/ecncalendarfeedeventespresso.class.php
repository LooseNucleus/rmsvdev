<?php
if ( ! class_exists( 'ECNCalendarFeedEventEspresso' ) ) {

class ECNCalendarFeedEventEspresso extends ECNCalendarFeed {
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
	        'location_zip',
	        'location_country',
	        'location_website',
	        'location_phone',
	        'contact_phone',
            'link',
	        'link_url',
            'event_image',
	        'event_image_url',
	        'categories',
	        'category_links',
	        'tags',
	        'tag_links',
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

        $args = apply_filters( 'ecn_fetch_events_args-' . $this->get_identifier(), array(
	        'limit' => -1,
	        'post_status' => 'publish',
	        'show_expired' => FALSE,
	        'month' => NULL,
	        'category_slug' => NULL,
	        'order_by' => 'start_date',
	        'sort' => 'ASC'
        ), $start_date, $end_date, $data );

	    $events_query = new \EventEspresso\core\domain\services\wp_queries\EventListQuery( $args );
	    // assign results to a variable so we can return it
	    $events = $events_query->have_posts() ? $events_query->posts : array();
	    // but first reset the query and postdata
	    wp_reset_query();
	    wp_reset_postdata();
	    EED_Events_Archive::remove_all_events_archive_filters();
	    unset( $events_query );

	    $post_ids = array();

	    foreach ( $events as $post ) {
			$event = $post->EE_Event;
            if (isset($post->EE_Event) && $post->EE_Event instanceof EE_Event) {
                $event = $post->EE_Event;
            } else {
                $event = EEM_Event::instance()->instantiate_class_from_post_object($post);
            }
		    $datetimes = $event->datetimes_ordered();
			if ( is_array( $datetimes ) and ! empty( $datetimes ) ) {
				foreach ( $datetimes as $datetime ) {
					if ( apply_filters( 'ecn_multiple_dates_once', false ) and in_array( $post->ID, $post_ids ) )
						continue;

					$post_ids[] = $post->ID;

					// TODO: Will need to flush out meta in repeat_intervals rather than stopping based on first occurrence start/end time
					$current_start_date = $datetime->start_date_and_time();
					$current_end_date = $datetime->end_date_and_time();
					// check start/end time but don't break as other date/times might match
					if ( strtotime( $current_start_date ) < $start_date ) {
						continue;
					}
					if ( strtotime( $current_start_date ) > $end_date )
						continue;
					$image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), apply_filters( 'ecn_image_size', 'medium', get_the_ID() ) );
					if ( !empty( $image_src ) )
						$image_url = $image_src[0];
					else
						$image_url = false;

					// grab the first venue.  I'm not seeing anything in the UI that allows more than one to be added to an event, but it's an array
					$venue = false;
					$venues = $event->venues();
					if ( is_array( $venues ) and count( $venues ) )
						$venue = array_pop( $venues );

					$ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
						'plugin' => $this->get_identifier(),
						'start_date' => $current_start_date,
						'end_date' => $current_end_date,
						'published_date' => get_the_date( 'Y-m-d H:i:s', $post->ID ),
						'title' => stripslashes_deep( $post->post_title ),
						'subtitle' => $datetime->get_pretty( 'DTT_name' ),
						'description' => stripslashes_deep( $post->post_content ),
						'excerpt' => stripslashes_deep( $post->post_excerpt ),
						'categories' => get_the_terms( $post->ID, 'espresso_event_categories' ),
						'tags' => get_the_terms( $post->ID, 'post_tag' ),

						'location_name' => ( $venue ? $venue->name() : false ),
						'location_address' => ( $venue ? $venue->address() : false ),
						'location_city' => ( $venue ? $venue->city() : false ),
						'location_state' => ( $venue ? $venue->state_name() : false ),
						'location_country' => ( $venue ? $venue->country_name() : false ),
						'location_website' => ( $venue ? $venue->venue_url() : false ),
						'location_phone' => ( $venue ? $venue->phone() : false ),

						'contact_phone' => $event->phone(),
						'link' => get_the_permalink(),
						'event_image_url' => $image_url,

						'additional_data' => array(
							'reg_limit' => $datetime->reg_limit()
						)
					) ) );
					//die(var_dump($ecn_event));
					$retval[] = $ecn_event;
				}
			}
        }
	    $retval = $this->sort_events_by_start_date( $retval );
	    return $retval;
    }

    function get_description() {
        return 'Event Espresso';
    }

    function get_identifier() {
        return 'event-espresso';
    }

    function is_feed_available() {
        return function_exists( 'espresso_version' );
    }
}
}