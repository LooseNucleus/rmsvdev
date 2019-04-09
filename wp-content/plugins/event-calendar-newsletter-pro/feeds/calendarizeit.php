<?php
class ECNProCalendarizeItFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_calendars' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );
    }

    function get_identifier() {
        return 'calendarize-it';
    }

    function get_calendar_taxonomy_id() {
        return 'calendar';
    }

	/**
	 * Display the available categories for filtering
	 *
	 * @param string $label
	 */
	function display_calendars() {
		?>
		<tr>
			<th><?php echo esc_html( __( 'Filter by Calendars:', 'event-calendar-newsletter' ) ) ?></th>
			<td>
				<p><?php echo esc_html( __( 'Leave calendars unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
				<div class="categorydiv">
					<ul id="categorychecklist" class="categorychecklist cat-checklist tribe_event-checklist">
						<?php wp_terms_checklist( null, array( 'taxonomy' => $this->get_calendar_taxonomy_id(), 'selected_cats' => $this->get_selected_calendars() ) ) ?>
					</ul>
				</div>
			</td>
		</tr>
		<?php
	}

	/**
	 * Get the selected calendars, if any
	 *
	 * @return array
	 */
	function get_selected_calendars() {
	    // TODO: Needs to be slug not ID?
		if ( ecn_get_saved_template_id() ) {
			return (array) ecn_get_saved_template_value( ecn_get_saved_template_id(), 'tax_input', $this->get_calendar_taxonomy_id() );
		}
		return $this->get_option( 'selected_calendars' );
	}

	function save_selected_calendars( $data ) {
		if ( isset( $data['tax_input'], $data['tax_input'][$this->get_calendar_taxonomy_id()] ) ) {
			$this->save_option( 'selected_calendars', array_values( $data['tax_input'][$this->get_calendar_taxonomy_id()] ) );
		} else {
			$this->save_option( 'selected_calendars', array() );
		}
	}

	/**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_calendars( $data );
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
        $args = $this->filter_calendars( $args, $data );
        return $args;
    }

	function filter_calendars( $args, $data ) {
		if ( isset( $data['tax_input'], $data['tax_input'][$this->get_calendar_taxonomy_id()] ) ) {
			$args['taxonomy'] = $this->get_calendar_taxonomy_id();

			// We're storing the term IDs but we need to get the slugs instead for filtering
            $slugs = array();
			foreach ( array_values( $data['tax_input'][$this->get_calendar_taxonomy_id()] ) as $term_id ) {
			    $term = get_term( $term_id, $this->get_calendar_taxonomy_id() );
			    if ( $term && ! is_wp_error( $term ) ) {
			        $slugs[] = $term->slug;
                }
            }

			$args['terms'] = implode( ',', $slugs );
		}
		return $args;
	}
}
