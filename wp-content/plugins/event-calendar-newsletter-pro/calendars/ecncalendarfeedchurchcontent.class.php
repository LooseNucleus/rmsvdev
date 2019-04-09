<?php
if ( ! class_exists( 'ECNCalendarFeedChurchContent' ) ) {

class ECNCalendarFeedChurchContent extends ECNCalendarFeed {
	function get_available_format_tags() {

		/* FROM THE EVENT DATA FUNCTION

		 * 			'start_date',
			'end_date',
			'time', // Time Description
			'start_time',
			'end_time',
			'hide_time_range',
			'recurrence',
			'recurrence_end_date',
			'recurrence_weekly_interval',	// Custom Recurring Events add-on
			'recurrence_monthly_interval',	// Custom Recurring Events add-on
			'recurrence_monthly_type',		// Custom Recurring Events add-on
			'recurrence_monthly_week',		// Custom Recurring Events add-on
			'venue',
			'address',
			'show_directions_link',
			'map_lat',
			'map_lng',
			'map_type',
			'map_zoom',
			'registration_url',
		 *
		 */

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
            'link',
	        'link_url',
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
        global $post;
        $retval = array();

	    // everything is stored in UTC so need to set that timezone
	    date_default_timezone_set('UTC');

	    $post_args = apply_filters( 'ecn_fetch_events_args-' . $this->get_identifier(),
		    array(
			    'post_type'			=> 'ctc_event',
			    'numberposts'		=> -1,
			    'meta_query' 		=> array(
				    'relation' => 'AND',
				    array(
					    'key'			=> '_ctc_event_end_date', // the latest date that the event goes to (could be start date)
					    'value' 		=> date_i18n( 'Y-m-d H:i:s', $start_date ), // today's date, localized
					    'compare' 		=> '>=',
					    'type' 			=> 'DATE'
				    ),
				    array(
					    'key'			=> '_ctc_event_end_date', // the latest date that the event goes to (could be start date)
					    'value' 		=> date_i18n( 'Y-m-d H:i:s', $end_date ), // today's date, localized
					    'compare' 		=> '<=',
					    'type' 			=> 'DATE'
				    ),
			    ),
			    'meta_key' 			=> '_ctc_event_start_date_start_time',
			    'meta_type' 		=> 'DATETIME',
			    'orderby'			=> 'meta_value',
			    'order'				=> 'ASC',
			    'suppress_filters'	=> false, // keep WPML from showing posts from all languages: http://bit.ly/I1JIlV + http://bit.ly/1f9GZ7D
		    ), $start_date, $end_date, $data );
	    $events = get_posts( $post_args );

	    foreach ( $events as $post ) {
		    $event = $post;
			setup_postdata( $post );
            $meta = $this->ctfw_event_data();
		    $current_start_date = trim( $meta['start_date'] . ' ' . $meta['start_time'] );
	        $current_end_date = trim( $meta['end_date'] . ' ' . $meta['end_time'] );
	        if ( strtotime( $current_start_date ) < $start_date ) {
		        continue;
	        }
	        if ( strtotime( $current_start_date ) > $end_date )
		        break;
            $image_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), apply_filters( 'ecn_image_size', 'medium', get_the_ID() ) );
            if ( !empty( $image_src ) )
                $image_url = $image_src[0];
            else
                $image_url = false;

	        $ecn_event = new ECNCalendarEvent( apply_filters( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array(
		        'plugin' => $this->get_identifier(),
		        'start_date' => $current_start_date,
		        'end_date' => $current_end_date,
		        'published_date' => get_the_date( 'Y-m-d H:i:s', $event->ID ),
		        'title' => stripslashes_deep( $event->post_title ),
		        'description' => stripslashes_deep( $event->post_content ),
		        'excerpt' => stripslashes_deep( get_the_excerpt() ),

		        // using "all day" when we're hiding the time range
		        'all_day' => ( $meta['hide_time_range'] ? true : false ),

		        'location_name' => $meta['venue'],
		        'location_address' => $meta['address'],

		        'link' => get_the_permalink(),
		        'event_image_url' => $image_url,
		        'event_website' => $meta['registration_url'],
	        ) ) );
            $retval[] = $ecn_event;
        }
        return $retval;
    }

    function get_description() {
        return 'Church Content';
    }

    function get_identifier() {
        return 'church-content';
    }

    function is_feed_available() {
        return class_exists( 'Church_Theme_Content' );
    }

	/**
	 * Get event data
	 *
	 * @since 0.9
	 * @param array|int $args post_id or array of arguments; If no post ID, current post used
	 * @return array Event data
	 */
	function ctfw_event_data( $args = array() ) {

		// Post ID given instead of args array?
		if ( is_numeric( $args ) ) {
			$args = array(
				'post_id' => $args,
			);
		}

		// Default arguments
		$args = wp_parse_args( $args, array(
			'post_id'				=> null, // use current
			/* translators: time range (%1$s) and description (%2$s) for an event */
			'time_and_desc_format'	=> __( '%1$s <span>(%2$s)</span>', 'church-theme-framework' ),
			'abbreviate_month'		=> false, // use Dec instead of December (replaced F with M in date_format setting)
		) );

		// Extract arguments to variables
		extract( $args );

		// Get meta values
		$meta = $this->ctfw_get_meta_data( array(
			'start_date',
			'end_date',
			'time', // Time Description
			'start_time',
			'end_time',
			'hide_time_range',
			'recurrence',
			'recurrence_end_date',
			'recurrence_weekly_interval',	// Custom Recurring Events add-on
			'recurrence_monthly_interval',	// Custom Recurring Events add-on
			'recurrence_monthly_type',		// Custom Recurring Events add-on
			'recurrence_monthly_week',		// Custom Recurring Events add-on
			'venue',
			'address',
			'show_directions_link',
			'map_lat',
			'map_lng',
			'map_type',
			'map_zoom',
			'registration_url',
		), $post_id );

		// Empty Custom Recurring Events add-on values if plugin not active
		// This keeps theme from displaying recurrence data that may be stored but is not effective
		if ( ! defined( 'CTC_CRE_VERSION' ) ) {
			$meta['recurrence_weekly_interval'] = 1;
			$meta['recurrence_monthly_interval'] = 1;
			$meta['recurrence_monthly_type'] = 'day';
			$meta['recurrence_monthly_week'] = '';
		}

		// Timestamps
		$start_date_timestamp = strtotime( $meta['start_date'] );
		$end_date_timestamp = strtotime( $meta['end_date'] );

		// Date format from settings
		$original_date_format = get_option( 'date_format' );
		$date_format = $original_date_format;

		// Abbreviate month in date format (e.g. December becomes Dec)
		if ( $abbreviate_month ) {

			//$date_format = str_replace( 'F', 'M', $date_format );

			$date_format = $this->ctfw_abbreviate_date_format( array(
				'date_format'		=> $date_format,
				'abbreviate_month'	=> true,
				'remove_year'		=> false,
			) );

		}

		// Add friendly date
		if ( $meta['end_date'] != $meta['start_date'] ) { // date range

			// Date formats
			// Make compact range of "June 1 - 5, 2015 if using "F j, Y" format (month and year removed from start date as not to be redundant)
			// If year is same but month different, becomes "June 30 - July 1, 2015"
			$start_date_format = $date_format;
			$end_date_format = $date_format;
			if ( 'F j, Y' == $original_date_format && date_i18n( 'Y', $start_date_timestamp ) == date_i18n( 'Y', $end_date_timestamp ) ) { // Year on both dates must be same

				// Remove year from start date
				$start_date_format = str_replace( ', Y', '', $start_date_format );

				// Months and year is same
				// Remove month from end date
				if ( date_i18n( 'F', $start_date_timestamp ) == date_i18n( 'F', $end_date_timestamp ) ) {
					$end_date_format = 'j, Y';
				}

			}

			// Format dates
			$start_date_formatted = date_i18n( $start_date_format, $start_date_timestamp );
			$end_date_formatted = date_i18n( $end_date_format, $end_date_timestamp );

			// Build range
			/* translators: date range */
			$meta['date'] = sprintf(
				_x( '%1$s &ndash; %2$s', 'dates', 'church-theme-framework' ),
				$start_date_formatted,
				$end_date_formatted
			);

		} else { // start date only
			$meta['date'] = date_i18n( $date_format, $start_date_timestamp );
		}

		// Format Start and End Time
		$time_format = get_option( 'time_format' );
		$meta['start_time_formatted'] = $meta['start_time'] ? date_i18n( $time_format, strtotime( $meta['start_time'] ) ) : '';
		$meta['end_time_formatted'] = $meta['end_time'] ? date_i18n( $time_format, strtotime( $meta['end_time'] ) ) : '';

		// Time Range
		// Show Start/End Time range (or only Start Time)
		$meta['time_range'] = '';
		if ( $meta['start_time_formatted'] ) {

			// Start Time Only
			$meta['time_range'] = $meta['start_time_formatted'];

			// Start and End Time (Range)
			if ( $meta['end_time_formatted'] ) {

				// Time Range
				/* translators: time range */
				$meta['time_range'] = sprintf(
					_x( '%1$s &ndash; %2$s', 'times', 'church-theme-framework' ),
					$meta['start_time_formatted'],
					$meta['end_time_formatted']
				);

			}

		}

		// Time and/or Description
		// Show Start/End Time (if given) and maybe Time Description (if given) in parenthesis
		// If no Start/End Time (or it is set to hide), show Time Description by itself
		// This is useful for event post header
		$meta['time_range_and_description'] = '';
		$meta['time_range_or_description'] = '';
		if ( $meta['time_range'] && ! $meta['hide_time_range'] ) { // Show Time Range and maybe Description after it

			// Definitely show time range
			$meta['time_range_and_description'] = $meta['time_range'];
			$meta['time_range_or_description'] = $meta['time_range'];

			// Maybe show description after time range
			if ( $meta['time'] ) {

				// Time and Description
				$meta['time_range_and_description'] = sprintf(
					$time_and_desc_format,
					$meta['time_range'],
					$meta['time']
				);

			}

		} else { // Show description only
			$meta['time_range_and_description'] = $meta['time'];
			$meta['time_range_or_description'] = $meta['time'];
		}

		// Add directions URL (empty if show_directions_link not set)
		$meta['directions_url'] = $meta['show_directions_link'] ? $this->ctfw_directions_url( $meta['address'] ) : '';

		// Recurrence note
		$recurrence_note = $this->ctfw_event_recurrence_note( false, $meta );
		$meta['recurrence_note'] = isset( $recurrence_note['full'] ) ? $recurrence_note['full'] : ''; // sentence such as "Every 3 months on the second Wednesday until January 24, 2018"
		$meta['recurrence_note_short'] = isset( $recurrence_note['short'] ) ? $recurrence_note['short'] : ''; // short version such as "Every 3 Months" (can show this with full on tooltip)

		// Map has coordinates?
		$meta['map_has_coordinates'] = ( $meta['map_lat'] && $meta['map_lng'] ) ? true : false;

		// Return filtered
		//return apply_filters( 'ctfw_event_data', $meta, $post_id );
		return $meta;
	}

	/**********************************
	 * EVENT RECURRENCE
	 **********************************/

	/**
	 * Recurrence note
	 *
	 * This describes the recurrence pattern.
	 * It considers the Custom Recurring Events add-on.
	 *
	 * Returns array with short and full keys.
	 * short - "Every 3 Months"
	 * full - "Every 3 months on second Tuesday until January 14, 2018"
	 *
	 * Tip: Show the short version with full in tooltip
	 *
	 * @since 1.5
	 * @param object|int $post Post object or post ID for event
	 * @return array Keys are full and short
	 */
	function ctfw_event_recurrence_note( $post_id = false, $data = false ) {

		$note = array();

		// Get event data if not provided
		if ( empty( $data ) ) {
			$data = $this->ctfw_event_data( $post_id );
		}

		// Is this a recurring event?
		$recurrence = $data['recurrence'];
		if ( $recurrence && $recurrence != 'none' ) {

			$note['full'] = '';
			$note['short'] = '';

			// Get recurrence data
			$recurrence_end_date = $data['recurrence_end_date'];
			$weekly_interval = $data['recurrence_weekly_interval'];
			$monthly_interval = $data['recurrence_monthly_interval'];
			$monthly_type = $data['recurrence_monthly_type'];
			$monthly_week = $data['recurrence_monthly_week'];

			// Localized end date
			$recurrence_end_date_localized = '';
			if ( $recurrence_end_date ) {
				$date_format = get_option( 'date_format' );
				$end_date_ts = strtotime( $recurrence_end_date );
				$recurrence_end_date_localized = date_i18n( $date_format, $end_date_ts );
			}

			// Get day of week for start date
			$start_date = $data['start_date'];
			$start_day_of_week = ! empty( $start_date ) ? date_i18n( 'l', strtotime( $start_date ) ) : '';

			// Words for week of month
			$monthly_week_word = '';
			if ( $monthly_week ) {

				$monthly_week_words = array(
					'1'		=> _x( 'first', 'week of month', 'church-theme-framework' ),
					'2'		=> _x( 'second', 'week of month', 'church-theme-framework' ),
					'3'		=> _x( 'third', 'week of month', 'church-theme-framework' ),
					'4'		=> _x( 'fourth', 'week of month', 'church-theme-framework' ),
					'last'	=> _x( 'last', 'week of month', 'church-theme-framework' ),
				);

				$monthly_week_word = $monthly_week_words[$monthly_week];

			}

			// Frequency
			switch ( $recurrence ) {

				case 'weekly' :

					// Full
					if ( $recurrence_end_date ) {

						/* translators: %1$s is interval, %2$s is recurrence end date */
						$note['full'] = sprintf(
							_n(
								'Every week until %2$s',
								'Every %1$s weeks until %2$s',
								$weekly_interval,
								'church-theme-framework'
							),
							$weekly_interval,
							$recurrence_end_date_localized
						);

					} else {

						/* translators: %1$s is interval */
						$note['full'] = sprintf(
							_n(
								'Every week',
								'Every %1$s weeks',
								$weekly_interval,
								'church-theme-framework'
							),
							$weekly_interval
						);

					}

					// Short
					/* translators: %1$s is interval */
					$note['short'] = sprintf(
						_n(
							'Every Week',
							'Every %1$s Weeks',
							$weekly_interval,
							'church-theme-framework'
						),
						$weekly_interval
					);

					break;

				case 'monthly' :

					// On specific week
					if ( 'week' == $monthly_type && $start_day_of_week ) { // only if start date is present

						// Has recurrence end date
						if ( $recurrence_end_date ) {

							/* translators: %1$s is interval, %2$s is week of month, %3$s is day of week, %4$s is recurrence end date */
							$note['full'] = sprintf(
								_n(
									'Every month on the %2$s %3$s until %4$s',
									'Every %1$s months on the %2$s %3$s until %4$s',
									$monthly_interval,
									'church-theme-framework'
								),
								$monthly_interval,
								$monthly_week_word,
								$start_day_of_week,
								$recurrence_end_date_localized
							);

						}

						// No recurrence end date
						else {

							/* translators: %1$s is interval, %2$s is week of month, %3$s is day of week */
							$note['full'] = sprintf(
								_n(
									'Every month on the %2$s %3$s',
									'Every %1$s months on the %2$s %3$s',
									$monthly_interval,
									'church-theme-framework'
								),
								$monthly_interval,
								$monthly_week_word,
								$start_day_of_week
							);

						}

						// On same day of month
					} else {

						// Has recurrence end date
						if ( $recurrence_end_date ) {

							/* translators: %1$s is interval, %2$s is recurrence end date */
							$note['full'] = sprintf(
								_n(
									'Every month until %2$s',
									'Every %1$s months until %2$s',
									$monthly_interval,
									'church-theme-framework'
								),
								$monthly_interval,
								$recurrence_end_date_localized
							);

						}

						// No recurrence end date
						else {

							/* translators: %1$s is interval */
							$note['full'] = sprintf(
								_n(
									'Every month',
									'Every %1$s months',
									$monthly_interval,
									'church-theme-framework'
								),
								$monthly_interval
							);

						}

					}

					/* translators: %1$s is interval */
					$note['short'] = sprintf(
						_n(
							'Every Month',
							'Every %1$s Months',
							$monthly_interval,
							'church-theme-framework'
						),
						$monthly_interval
					);

					break;

				case 'yearly' :

					// Full
					if ( $recurrence_end_date ) {

						/* translators: %1$s is recurrence end date */
						$note['full'] = sprintf(
							__( 'Every year until %1$s', 'church-theme-framework' ),
							$recurrence_end_date_localized
						);

					} else {
						$note['full'] = __( 'Every year', 'church-theme-framework' );
					}

					// Short
					$note['short'] = __( 'Every Year', 'church-theme-framework' );

					break;

			}

		}

		// Filter
		$note = apply_filters( 'ctfw_event_recurrence_note', $note, $post_id, $data );

		return $note;

	}


	/*******************************************
	 * HELPERS
	 *******************************************/

	/**
	 * Build Google Maps directions URL from address
	 *
	 * @since 0.9
	 * @param string $address Address to get directions URL for
	 * @return string URL for directions on Google Maps
	 */
	function ctfw_directions_url( $address ) {

		$directions_url = '';

		if ( $address ) {

			// Convert address to one line (replace newlines with commas)
			$directions_address = ctfw_address_one_line( $address );

			// Build URL to Google Maps
			$directions_url = 'https://www.google.com/maps/dir//' . urlencode( $directions_address ) . '/'; // works with new and old maps

		}

		return apply_filters( 'ctfw_directions_url', $directions_url, $address );

	}

	/*********************************
	 * META DATA
	 *********************************

	/**
	 * Get meta data for a post/type (without prefix)
	 *
	 * @since 0.9
	 * @param array $fields Provide $fields as array without meta field's post type prefix (_ctc_sermon_ for example)
	 * @param int $post_id Optional post ID; otherwise current post used
	 * @param string $prefix Optional prefix override; otherwise post type used as prefix
	 * @return array Meta data
	 */
	function ctfw_get_meta_data( $fields, $post_id = null, $prefix = null ) {

		$meta = array();

		// Have fields
		if ( ! empty( $fields ) ) {

			// Use current post ID if none set
			if ( ! isset( $post_id ) ) {
				$post_id = get_the_ID();
			}

			// Have post ID
			if ( $post_id ) {

				// Post type as prefix for meta field
				if ( ! isset( $prefix ) ) {
					$post_type = get_post_type( $post_id );
					$prefix = '_' . $post_type . '_';
				}

				// Loop fields to get values
				foreach ( $fields as $field ) {
					$meta[$field] = get_post_meta( $post_id, $prefix . $field, true );
				}

			}

		}

		return apply_filters( 'ctfw_get_meta_data', $meta, $post_id );

	}


	/*************************************************
	 * DATES
	 *************************************************/

	/**
	 * Abbreviate date format
	 *
	 * Convert common date formats to abbreviated version
	 * by abbreviating month and/or removing year.
	 *
	 * @since 2.0
	 * @param string $date_format Date format to abbreviate; if none given, uses get_option( 'date_format' )
	 * @param array $args Array of bools: abbreviate_month (e.g. convert January to Jan), remove_year (both default true)
	 * @return string Abbreviated date format
	 */
	function ctfw_abbreviate_date_format( $args = array() ) {

		// Default args
		$args = wp_parse_args( $args, array(
			'date_format'		=> get_option( 'date_format' ),
			'abbreviate_month'	=> true, // January => Jan (F => M)
			'remove_year'		=> true,
		) );
		extract( $args );

		// Use format from settings if no abbreviation made
		$abbreviated_date_format = $date_format;

		// Abbreviate given format based on arguments
		switch( $date_format ) {

			// January 1, 2017
			case 'F j, Y':

				// Jan 1
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'M j';
				}

				// Jan 1, 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'M j, Y';
				}

				// January 1
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'F j, Y';
				}

				break;

			// Jan 1, 2017
			case 'M j, Y':

				// Jan 1
				if ( $remove_year ) {
					$abbreviated_date_format = 'M j';
				}

				break;

			// January 1st, 2017
			case 'F jS, Y':

				// Jan 1st
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'M jS';
				}

				// Jan 1st, 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'M jS, Y';
				}

				// January 1st
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'F jS';
				}

				break;

			// Jan 1st, 2017
			case 'M jS, Y':

				// Jan 1st
				if ( $remove_year ) {
					$abbreviated_date_format = 'M jS';
				}

				break;

			// 1 January, 2017
			case 'j F, Y':

				// 1 Jan
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'j M';
				}

				// 1 Jan, 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'j M, Y';
				}

				// 1 January
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'j F';
				}

				break;

			// 1 Jan, 2017
			case 'j M, Y':

				// 1 Jan
				if ( $remove_year ) {
					$abbreviated_date_format = 'j M';
				}

				break;

			// 1st January, 2017
			case 'jS F, Y':

				// 1st Jan
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'jS M';
				}

				// 1st Jan, 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'jS M, Y';
				}

				// 1st January
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'jS F';
				}

				break;

			// 1st Jan, 2017
			case 'jS M, Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'jS M';
				}

				break;

			// January 1 2017
			case 'F j Y':

				// Jan 1
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'M j';
				}

				// Jan 1 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'M j Y';
				}

				// January 1
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'F j Y';
				}

				break;

			// Jan 1 2017
			case 'M j Y':

				// Jan 1
				if ( $remove_year ) {
					$abbreviated_date_format = 'M j';
				}

				break;

			// January 1st 2017
			case 'F jS Y':

				// Jan 1st
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'M jS';
				}

				// Jan 1st 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'M jS Y';
				}

				// January 1st
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'F jS';
				}

				break;

			// Jan 1st 2017
			case 'M jS Y':

				// Jan 1st
				if ( $remove_year ) {
					$abbreviated_date_format = 'M jS';
				}

				break;

			// 1 January 2017
			case 'j F Y':

				// 1 Jan
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'j M';
				}

				// 1 Jan 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'j M Y';
				}

				// 1 January
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'j F';
				}

				break;

			// 1 Jan 2017
			case 'j M Y':

				// 1 Jan
				if ( $remove_year ) {
					$abbreviated_date_format = 'j M';
				}

				break;

			// 1st January 2017
			case 'jS F Y':

				// 1st Jan
				if ( $abbreviate_month && $remove_year ) {
					$abbreviated_date_format = 'jS M';
				}

				// 1st Jan 2017
				elseif ( $abbreviate_month ) {
					$abbreviated_date_format = 'jS M Y';
				}

				// 1st January
				elseif ( $remove_year ) {
					$abbreviated_date_format = 'jS F';
				}

				break;

			// 1st Jan 2017
			case 'jS M Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'jS M';
				}

				break;

			// 2017/06/01
			case 'Y/m/d':

				if ( $remove_year ) {
					$abbreviated_date_format = 'm/d';
				}

				break;

			// 2017-06-01 = 06-01
			case 'Y-m-d':

				if ( $remove_year ) {
					$abbreviated_date_format = 'm-d';
				}

				break;

			// 06/01/2017 = 06/01
			case 'm/d/Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'm/d';
				}

				break;

			// 06-01-2017 = 06-01
			case 'm-d-Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'm-d';
				}

				break;

			// 01/06/2017 = 01/06
			case 'd/m/Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'd/m';
				}

				break;

			// 01-06-2017 = 01-06
			case 'd-m-Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'd-m';
				}

				break;

			// 1/6/2017 = 1/6
			case 'j/n/Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'j/n';
				}

				break;

			// 1-6-2017 = 1-6
			case 'j-n-Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'j-n';
				}

				break;

			// 2017/6/31 = 6/31
			case 'Y/n/j':

				if ( $remove_year ) {
					$abbreviated_date_format = 'n/j';
				}

				break;

			// 2017-6-31 = 6-31
			case 'Y-n-j':

				if ( $remove_year ) {
					$abbreviated_date_format = 'n-j';
				}

				break;

			// 6/31/2017 = 6/31
			case 'n/j/Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'n/j';
				}

				break;

			// 6-31-2017 = 6-31
			case 'n-j-Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'n-j';
				}

				break;


			// 31/6/2017 = 31/6
			case 'j/n/Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'j/n';
				}

				break;

			// 31-6-2017 = 31-6
			case 'j-n-Y':

				if ( $remove_year ) {
					$abbreviated_date_format = 'j-n';
				}

				break;

		}

		return apply_filters( 'ctfw_abbreviate_date_format', $abbreviated_date_format, $args );

	}

}
}