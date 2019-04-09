<?php
class ECNProTheEventsCalendarFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_tags' ), 10 );
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_venues' ), 10 );
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'featured_events_only' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );

        // Support for the "Additional Fields" in The Events Calendar PRO
        add_filter( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array( &$this, 'add_data_from_additional_fields' ), 10 );
        add_filter( 'ecn_available_format_tags_display', array( &$this, 'add_additional_fields_as_format_tags' ), 10, 3 );
    }

    function get_identifier() {
        return 'the-events-calendar';
    }

    function get_category_taxonomy_id() {
        return 'tribe_events_cat';
    }

	function show_featured_events_only() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'featured_events_only' );
		return $this->get_option( 'featured_events_only' );
	}

    /**
     * Display the available venues for filtering
     */
    function display_venues() {
        $selected_venues = $this->get_selected_venues();
        ?>
        <tr>
            <th><?php echo esc_html( __( 'Filter by Venues:', 'event-calendar-newsletter' ) ) ?></th>
            <td>
                <p><?php echo esc_html( __( 'Leave venues unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
                <div class="categorydiv">
                    <ul id="venuechecklist" class="categorychecklist cat-checklist tribe_event-checklist">
                        <?php foreach ( get_posts( array( 'post_type' => $this->get_venue_post_type_id(), 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC' ) ) as $venue ): ?>
                            <li id="tribe_venue-<?php echo $venue->ID ?>" class="popular-category">
                                <label class="selectit">
                                    <input value="<?php echo $venue->ID ?>" type="checkbox" name="tribe_venue[]" id="in-tribe_venues-<?php echo $venue->ID ?>"<?php if ( in_array( $venue->ID, $selected_venues ) ) echo ' checked'; ?>>
                                    <?php echo esc_html( $venue->post_title ) ?>
                                </label>
                            </li>
                            <?php endforeach; ?>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
    }

    function get_venue_post_type_id() {
        return 'tribe_venue';
    }

    /**
     * Get the selected venues, if any
     *
     * @return array
     */
    function get_selected_venues() {
        if ( ecn_get_saved_template_id() ) {
            return (array) ecn_get_saved_template_value( ecn_get_saved_template_id(), 'tribe_venue' );
        }
        return $this->get_option( 'selected_venues' );
    }

    /**
     * Save the selected venues to memory for the next time
     *
     * @param $data
     */
    function save_selected_venues( $data ) {
        if ( isset( $data['tribe_venue'] ) ) {
            $this->save_option( 'selected_venues', array_values( $data['tribe_venue'] ) );
        } else {
            $this->save_option( 'selected_venues', array() );
        }
    }

    function featured_events_only() {
		?>
		<tr>
			<th><?php echo esc_html( __( 'Featured Events Only:', 'event-calendar-newsletter' ) ) ?></th>
			<td>
				<label class="selectit">
					<input value="1" type="checkbox" name="featured_events_only" id="featured_events_only" <?php if ( $this->show_featured_events_only() ) echo ' checked="checked"' ?> /> <?php echo esc_html( __( 'Show only events marked as "featured"', 'event-calendar-newsletter' ) ) ?>
				</label>
			</td>
		</tr>
		<?php
	}

    /**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_categories( $data );
        $this->save_selected_tags( $data );
        $this->save_selected_venues( $data );
    }

    /**
     * Filter the events by any selected categories
     *
     * @param $args
     * @param $start_date
     * @param $end_date
     * @param $data
     *
     * @return array
     */
    function filter_events( $args, $start_date, $end_date, $data ) {
        $args = $this->filter_categories( $args, $data );
        $args = $this->filter_tags( $args, $data );
        $args = $this->filter_venues( $args, $data );
        $args = $this->filter_featured_only( $args, $data );
        return $args;
    }

    function filter_venues( $args, $data ) {
        if ( isset( $data['tribe_venue'] ) and $data['tribe_venue'] ) {
            if ( ! isset( $args['meta_query'] ) or ! is_array( $args['meta_query'] ) )
                $args['meta_query'] = array();

            $args['meta_query'][] = array(
                'key' => '_EventVenueID',
                'value' => $data['tribe_venue'],
                'type' => 'numeric',
                'compare' => 'IN',
            );
        }
        return $args;
    }

    function filter_featured_only( $args, $data ) {
	    if ( isset( $data['featured_events_only'] ) and $data['featured_events_only'] ) {
		    if ( ! isset( $args['meta_query'] ) or ! is_array( $args['meta_query'] ) )
			    $args['meta_query'] = array();

		    $args['meta_query'][] = array(
			    'key' => '_tribe_featured',
			    'value' => 1,
			    'type' => 'numeric',
		    );
	    }
	    return $args;
    }

    /**
     * Add any data from the Additional Fields for this event
     *
     * @param $args
     */
    function add_data_from_additional_fields( $args ) {
        if ( function_exists( 'tribe_get_option' ) and function_exists( 'tribe_get_custom_fields' ) ) {
            // Get all the available custom fields
            $custom_fields = tribe_get_option( 'custom-fields', false );

            // Get the data from the additional fields for this post/event
            $custom_field_data = tribe_get_custom_fields();

            // Create the array using the meta key from $custom_fields and the label => value from the data
            $args['additional_data'] = array();
            if ( is_array( $custom_fields ) ) {
                foreach ( $custom_fields as $field ) {
                    $args['additional_data'][$field['name']] = ( isset( $custom_field_data[$field['label']] ) ? $custom_field_data[$field['label']] : '' );
                }
            }
        }
        return $args;
    }

    /**
     * Add any Additional Fields from The Events Calendar PRO to the list
     *
     * @param $tags array
     * @param $plugin_slug
     *
     * @return mixed
     */
    function add_additional_fields_as_format_tags( $tags, $plugin_slug ) {
        if ( $this->get_identifier() == $plugin_slug and function_exists( 'tribe_get_option' ) ) {
            $custom_fields = tribe_get_option( 'custom-fields', false );
            if ( is_array( $custom_fields ) ) {
                foreach ( $custom_fields as $field ) {
                    $tags[$field['name']] = $field['label'];
                }
            }
        }
        return $tags;
    }
}
