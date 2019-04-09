<?php
if ( ! class_exists( 'ECNCalendarFeedGeoDirectory' ) ) {

class ECNCalendarFeedGeodir extends ECNCalendarFeed {
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
            'description',
            'excerpt',
            'location_address',
	        'location_city',
	        'location_state',
	        'location_country',
	        'location_zip',
	        'contact_website',
	        'contact_email',
	        'contact_phone',
	        'link',
	        'link_url',
            'event_image',
	        'event_image_url',
	        'categories',
	        'category_links',
	        'tags',
	        'tag_links',
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

	    $query_args = apply_filters( 'ecn_fetch_events_args-' . $this->get_identifier(), array(
		    'geodir_event_type' => 'all',
		    'event_related_id' => '',
		    'posts_per_page' => -1,
		    'is_geodir_loop' => true,
		    'gd_location' 	 => false,
		    'post_type' => 'gd_event',
		    'order_by' => 'latest',
		    'excerpt_length' => 50,
		    'character_count' => 50,
	    ), $start_date, $end_date, $data );

	    $events = geodir_get_widget_listings( $query_args );

	    foreach ( $events as $post ) {
			setup_postdata( $post );
            $event = $post;
	        $current_start_date = date( 'Y-m-d H:i:s', strtotime( trim( date( 'Y-m-d', strtotime( $post->event_date ) ) . ' ' . $post->event_starttime ) ) );
		    if ( '' != $post->event_enddate and '0000-00-00' != $post->event_enddate )
	            $current_end_date = date( 'Y-m-d H:i:s', strtotime( trim( $post->event_enddate . ' ' . $post->event_endtime ) ) );
		    else
		    	$current_end_date = date( 'Y-m-d H:i:s', strtotime( trim( date( 'Y-m-d', strtotime( $post->event_date ) ) . ' ' . $post->event_endtime ) ) );
	        if ( strtotime( $current_start_date ) < $start_date ) {
		        continue;
	        }
	        if ( strtotime( $current_start_date ) >= $end_date )
		        continue;

		    $image_url = false;
		    $image = geodir_get_featured_image( $event->ID, apply_filters( 'ecn_image_size', 'medium', $event->ID ), true, $event->featured_image );
		    if ( is_object( $image ) )
		    	$image_url = $image->src;

		    $permalink = get_the_permalink( $event->ID );
		    $permalink .= ( ( false !== strpos( $permalink, '?' ) ) ? '&gde=' : '?gde=' ) . date( 'Y-m-d', strtotime( $current_start_date ) );

		    $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
		        'plugin' => $this->get_identifier(),
		        'start_date' => $current_start_date,
		        'end_date' => $current_end_date,
		        'published_date' => get_the_date( 'Y-m-d H:i:s', $event->ID ),
		        'title' => stripslashes_deep( $event->post_title ),
		        'categories' => get_the_terms( $event->ID, 'gd_eventcategory' ),
		        'tags' => get_the_terms( $event->ID, 'gd_event_tags' ),
		        'description' => stripslashes_deep( $event->post_content ),
		        'excerpt' => stripslashes_deep( $event->post_excerpt ),
		        'all_day' => ( ( isset( $event->all_day ) && $event->all_day != '1' ) ? true : false ),
		        'location_address' => ( isset( $event->post_address ) ? $event->post_address : '' ),
		        'location_city' => ( isset( $event->post_city ) ? $event->post_city : '' ),
		        'location_state' => ( isset( $event->post_region ) ? $event->post_region : '' ),
		        'location_country' => ( isset( $event->post_country ) ? $event->post_country : '' ),
		        'location_zip' => ( isset( $event->post_zip ) ? $event->post_zip : '' ),
		        'contact_website' => ( isset( $event->geodir_website ) ? $event->geodir_website : '' ),
		        'contact_email' => ( isset( $event->geodir_email ) ? $event->geodir_email : '' ),
		        'contact_phone' => ( isset( $event->geodir_contact ) ? $event->geodir_contact : '' ),
		        'link' => $permalink,
		        'event_image_url' => $image_url,
		        'event_website' => get_post_meta( $event->ID, 'evcal_lmlink', true ),
	        ) ) );
            $retval[] = $ecn_event;
        }
	    $retval = $this->sort_events_by_start_date( $retval );
	    return $retval;
    }

    function get_description() {
        return 'Geodirectory Events';
    }

    function get_identifier() {
        return 'geodir';
    }

    function is_feed_available() {
        return defined( 'GDEVENTS_VERSION' );
    }
}
}