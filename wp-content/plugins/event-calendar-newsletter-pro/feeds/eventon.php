<?php
class ECNProEventonFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_event_types' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );

        // Support for any additional fields
        add_filter( 'ecn_create_calendar_event_args-' . $this->get_identifier(), array( &$this, 'add_data_from_additional_fields' ), 10 );
        add_filter( 'ecn_available_format_tags_display', array( &$this, 'add_additional_fields_as_format_tags' ), 10, 3 );
    }

    function get_identifier() {
        return 'eventon';
    }

	function get_additional_field_count() {
		$evo_opt = get_option( 'evcal_options_evcal_1' );
		return evo_calculate_cmd_count( $evo_opt );
	}

	/**
     * Add any data from the Additional Fields for this event
     *
     * @param $args
     */
	function add_data_from_additional_fields( $args ) {
		if ( ! $this->get_additional_field_count() )
			return $args;

		$args['additional_data'] = array();
		for ( $count = 1; $count <= $this->get_additional_field_count(); $count++ ) {
            // Create the array using the meta key from $custom_fields and the label => value from the data
			// Value stored in meta has _ at beginning and _cus at the end
			$args['additional_data'][$this->get_additional_field_identifier( $count )] = get_post_meta( get_the_ID(), '_' . $this->get_additional_field_identifier( $count ) . '_cus', true );
        }
        return $args;
    }

	function get_additional_field_identifier( $count ) {
		return 'evcal_ec_f' . intval( $count ) . 'a1';
	}

	function get_additional_field_name( $count ) {
		$evo_opt = get_option( 'evcal_options_evcal_1' );
		return ( isset( $evo_opt[$this->get_additional_field_identifier($count)] ) ? $evo_opt[$this->get_additional_field_identifier($count)] : 'Additional Field ' . intval( $count ) );
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
        if ( $this->get_identifier() == $plugin_slug and $this->get_additional_field_count() ) {
            for ( $count = 1; $count <= $this->get_additional_field_count(); $count++ ) {
                $tags[$this->get_additional_field_identifier($count)] = $this->get_additional_field_name( $count );
            }
        }
        return $tags;
    }

    /**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_event_types( $data );
    }

    function get_event_type_count() {
        $event_type_count = 2;
        if ( function_exists( 'evo_get_ett_count' ) ) {
            $evo_opt = get_option('evcal_options_evcal_1');
            $event_type_count = evo_get_ett_count($evo_opt);
        }
        return $event_type_count;
    }

    function get_event_type_identifier( $count ) {
        return ( 1 == $count ? 'event_type' : 'event_type_' . $count );
    }

    function save_selected_event_types( $data ) {
        for ( $count = 1; $count <= $this->get_event_type_count(); $count++ ) {
            $identifier = $this->get_event_type_identifier( $count );
            if ( isset( $data[$identifier] ) and is_array( $data[$identifier] ) )
                $this->save_option( 'selected_' . $identifier, array_map( 'intval', $data[$identifier] ) );
            else
                $this->save_option( 'selected_' . $identifier, array() );
        }
    }

    function get_selected_event_type( $count ) {
	    if ( ecn_get_saved_template_id() )
		    return (array) ecn_get_saved_template_value( ecn_get_saved_template_id(), 'tax_input', $this->get_event_type_identifier( $count ) );
        return $this->get_option( 'selected_' . $this->get_event_type_identifier( $count ) );
    }

    function is_event_type_selected( $id, $count ) {
        $event_types = $this->get_selected_event_type( $count );
        if ( is_array( $event_types ) and in_array( $id, $event_types ) )
            return true;
        return false;
    }

    function display_event_types() {
        // Get custom labels for the event types (if any)
        $options = get_option( 'evcal_options_evcal_1' );

        for ( $count = 1; $count <= $this->get_event_type_count(); $count++ ): ?>
            <?php
            if ( 1 == $count ) {
                $label = ( ! empty( $options['evcal_eventt'] ) ) ? $options['evcal_eventt'] : 'Event Type';
            } else {
                $label = ( ! empty( $options['evcal_eventt' . $count])) ? $options['evcal_eventt' . $count] : 'Event Type ' . $count;
            }
            ?>
            <tr>
                <th><?php echo esc_html( sprintf( __( 'Filter by %s:', 'event-calendar-newsletter' ), $label ) ) ?></th>
                <td>
                    <p><?php echo esc_html( sprintf( __( 'Leave unchecked to not filter by %s', 'event-calendar-newsletter' ), $label ) ) ?></p>
                    <div class="categorydiv">
                        <ul id="eventtype<?= $count ?>checklist" class="eventtype<?= $count ?>checklist categorychecklist cat-checklist tribe_event-checklist">
                            <?php wp_terms_checklist( null, array( 'taxonomy' => $this->get_event_type_identifier( $count ), 'selected_cats' => $this->get_selected_event_type( $count ) ) ) ?>
                        </ul>
                    </div>
                </td>
            </tr>
        <?php endfor;
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
        $args = $this->filter_event_types( $args, $data );
        return $args;
    }

    function filter_event_types( $args, $data ) {
        for ( $count = 1; $count <= $this->get_event_type_count(); $count++ ) {
            $args = $this->filter_taxonomy( $args, $data, $this->get_event_type_identifier( $count ) );
        }
        return $args;
    }
}

