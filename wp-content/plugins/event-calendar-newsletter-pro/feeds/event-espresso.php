<?php
class ECNProEventEspressoFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_tags' ), 10 );
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'multiple_dates_event_options' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );
	    add_filter( 'ecn_multiple_dates_once', array( &$this, 'show_multiple_dates_event_once' ) );

    }

	function show_multiple_dates_event_once() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'multiple_dates_once' );
		return $this->get_option( 'multiple_dates_once' );
	}

	function save_multiple_dates_options( $data ) {
		if ( isset( $data['multiple_dates_once'] ) and $data['multiple_dates_once'] )
			$this->save_option( 'multiple_dates_once', boolval( $data['multiple_dates_once'] ) );
		else
			$this->save_option( 'multiple_dates_once', false );
	}

	function multiple_dates_event_options() {
		?>
		<tr>
			<th><?php echo esc_html( __( 'Multiple Dates for an Event:', 'event-calendar-newsletter' ) ) ?></th>
			<td>
				<label class="selectit">
					<input value="1" type="checkbox" name="multiple_dates_once" id="multiple_dates_once" <?php if ( $this->show_multiple_dates_event_once() ) echo ' checked="checked"' ?> /> <?php echo esc_html( __( 'Show only first datetime if event has multiple datetimes', 'event-calendar-newsletter' ) ) ?>
				</label>
			</td>
		</tr>
		<?php
	}

	function get_identifier() {
        return 'event-espresso';
    }

    function get_category_taxonomy_id() {
        return 'espresso_event_categories';
    }

    function get_tag_taxonomy_id() {
        return 'post_tag';
    }

    /**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_categories( $data );
        $this->save_selected_tags( $data );
	    $this->save_multiple_dates_options( $data );
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
        return $args;
    }
}
