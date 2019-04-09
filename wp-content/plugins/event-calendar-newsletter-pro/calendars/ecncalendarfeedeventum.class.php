<?php
if ( ! class_exists( 'ECNCalendarFeedEventum' ) ) {

if ( ! function_exists( 'ecn_fetch_eventum_cities' ) ) {
	function ecn_fetch_eventum_cities() {
		global $post,$wpdb,$zones_table,$country_table,$multicity_table;
		$multicity_data = array();
		$order_by = 'mc.cityname';
		$order = 'ASC';
		$sql = "select mc.*,z.zone_name,c.country_name from $multicity_table mc, $zones_table z ,$country_table c where mc.country_id=c.country_id AND mc.zones_id=z.zones_id AND c.country_id=z.country_id ORDER BY $order_by $order ";
		$multicitiyinfo = $wpdb->get_results($sql);
		if($multicitiyinfo)
		{
			foreach($multicitiyinfo as $resobj) :
				if($resobj->map_type=='ROADMAP')
					$map_type='Road Map';
				elseif($resobj->map_type=='TERRAIN')
					$map_type='Terrain Map';
				elseif($resobj->map_type=='SATELLITE')
					$map_type='Satellite Map';
				elseif($resobj->map_type=='HYBRID')
					$map_type='Hybrid Map';
				elseif($resobj->map_type=='streetview')
					$map_type='Street View Map';


				if (function_exists('icl_register_string')) {
					/*City name translate using wpml*/
					icl_register_string('location-manager', 'location_city_'.$resobj->city_slug,$resobj->cityname);
					$resobj->cityname = icl_t('location-manager', 'location_city_'.$resobj->city_slug,$resobj->cityname);

					/*Zone name translate using wpml */
					icl_register_string('location-manager', 'location_zone_'.$resobj->zones_id,$resobj->zone_name);
					$resobj->zone_name = icl_t('location-manager', 'location_zone_'.$resobj->zones_id,$resobj->zone_name);
					/* Country name translate using wpml*/
					icl_register_string('location-manager', 'location_country_'.$resobj->country_id,$resobj->country_name);
					$resobj->country_name = icl_t('location-manager', 'location_country_'.$resobj->country_id,$resobj->country_name);

				}
				$cityname = $resobj->cityname;

				$multicity_data[] =  array(
					'ID'             => $resobj->city_id,
					'title'		    => $cityname,
					'country_name'   => $resobj->zone_name.', '.$resobj->country_name,
					'map_type'       => $map_type,
					'city_post_type' => $resobj->post_type,
					'message'        => substr($resobj->message,0,50),
					'scaling_factor' => $resobj->scall_factor,
					'set_default'    => '<a id="default_city_'.$resobj->city_id.'" '. @$onclick.'>'.$resobj->is_default.'</a>',
					'zone_name'      => $resobj->zone_name,
					'zones_id'       => $resobj->zones_id,
				);
			endforeach;
		}
		return $multicity_data;
	}

	function ecn_fetch_eventum_states() {
		$city_data = ecn_fetch_eventum_cities();
		$state_data = array();
		foreach ( $city_data as $city ) {
			if ( ! isset( $state_data[$city['zones_id']] ) )
				$state_data[$city['zones_id']] = $city;
		}
		usort( $state_data, 'ecn_sort_eventum_states' );
		return $state_data;
	}

	function ecn_sort_eventum_states( $a, $b ) {
		return strcmp( $a['zone_name'], $b['zone_name'] );
	}
}

class ECNCalendarFeedEventum extends ECNCalendarFeed {
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
//            'location_zip',
            'location_country',
//	        'location_phone',
//	        'location_website',
            //'contact_name',
            'contact_email',
            'contact_website',
            'contact_phone',
	        'organizer_name',
	        'organizer_email',
	        'organizer_website',
            'link',
	        'link_url',
            'event_image',
	        'event_image_url',
            'event_cost',
	        'categories',
	        'category_links',
	        'tags',
	        //'all_day',
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
		    'post_type' => 'event',
		    'posts_per_page' => 10000,
		    'post_status' => 'publish',
		    'meta_query' => array( 'relation' => 'AND',
			    array(
				    'key'     => 'set_st_time',
				    'value'   => date( 'Y-m-d H:i', $start_date ),
				    'compare' => '>=',
				    'type' => 'DATETIME'
			    ),
			    array(
				    'key'     => 'set_end_time',
				    'value'   => date( 'Y-m-d H:i', $end_date ),
				    'compare' => '<=',
				    'type' => 'DATETIME',
			    )
		    ),
		    'meta_key' => 'st_date',
		    'orderby' => 'meta_value',
		    'order' => 'ASC'
	    ), $start_date, $end_date, $data );
	    $events = get_posts( $post_args );

	    // Fetch data for the cities of each event
	    $cities = array();
	    if ( function_exists( 'ecn_fetch_eventum_cities' ) )
		    $cities = ecn_fetch_eventum_cities();

	    $states = array();
	    if ( function_exists( 'ecn_fetch_eventum_states' ) )
	        $states = ecn_fetch_eventum_states();

	    foreach ( $events as $post ) {
			setup_postdata( $post );
            $event = $post;
	        $current_start_date = get_post_meta( $event->ID, 'set_st_time', true );
	        $current_end_date = get_post_meta( $event->ID, 'set_end_time', true );
	        if ( strtotime( $current_start_date ) < $start_date )
		        continue;
	        if ( strtotime( $current_start_date ) > $end_date )
		        break;
            $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), apply_filters( 'ecn_image_size', 'medium', get_the_ID() ) );
            if ( !empty( $image_src ) )
                $image_url = $image_src[0];
            else
                $image_url = false;

		    $city = $country = '';
		    foreach ( $cities as $city_data ) {
		    	if ( is_array( $city_data ) and isset( $city_data['ID'] ) and $city_data['ID'] == get_post_meta( $event->ID, 'post_city_id', true ) ) {
		    		$city = strip_tags( $city_data['title'] );
				    $country = strip_tags( $city_data['country_name'] );
				    break;
			    }
		    }
		    
		    $state = '';
		    foreach ( $states as $state_data ) {
			    if ( is_array( $state_data ) and isset( $state_data['zones_id'] ) and $state_data['zones_id'] == get_post_meta( $event->ID, 'zones_id', true ) ) {
				    $state = strip_tags( $state_data['zone_name'] );
				    break;
			    }
		    }

		    $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
		        'plugin' => $this->get_identifier(),
		        'start_date' => $current_start_date,
		        'end_date' => $current_end_date,
		        'published_date' => get_the_date( 'Y-m-d H:i:s', $event->ID ),
		        'title' => stripslashes_deep( $event->post_title ),
		        'categories' => get_the_terms( $event->ID, 'ecategory' ),
		        'tags' => get_the_terms( $event->ID, 'etags' ),
		        'description' => stripslashes_deep( $event->post_content ),
		        'excerpt' => stripslashes_deep( $event->post_excerpt ),
		        'location_name' => get_post_meta( $event->ID, 'address', true ),
		        'location_address' => get_post_meta( $event->ID, 'address', true ),
		        'location_city' => $city,
			    'location_state' => $state,
		        'location_country' => $country,
		        'contact_email' => get_post_meta( $event->ID, 'email', true ),
		        'contact_website' => get_post_meta( $event->ID, 'website', true ),
		        'contact_phone' => get_post_meta( $event->ID, 'phone', true ),
		        'organizer_email' => get_post_meta( $event->ID, 'organizer_email', true ),
		        'organizer_website' => get_post_meta( $event->ID, 'organizer_website', true ),
		        'organizer_name' => get_post_meta( $event->ID, 'organizer_name', true ),
		        'link' => get_the_permalink(),
		        'event_image_url' => $image_url,
		        'event_cost' => get_post_meta( $event->ID, 'reg_fees', true ),
	        ) ) );
	        //var_dump($ecn_event->get_categories());
            $retval[] = $ecn_event;
        }
        return $retval;
    }

    function get_description() {
        return 'Tevolution Events / Eventum';
    }

    function get_identifier() {
        return 'eventum';
    }

    function is_feed_available() {
        return defined( 'TEVOLUTION_EVENT_VERSION' );
    }
}
}