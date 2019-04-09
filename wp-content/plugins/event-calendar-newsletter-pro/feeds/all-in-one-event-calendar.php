<?php
class ECNProAllInOneEventCalendarFeed extends ECNProFeed {
	function add_filters() {
		add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
		add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'recurring_event_options' ), 10 );
		add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
		add_filter( 'ecn_ai1ec_filters', array( &$this, 'filter_categories' ), 10, 2 );
		add_filter( 'ecn_ai1ec_recurring_once', array( &$this, 'show_recurring_event_once' ) );
	}

	function get_identifier() {
		return 'all-in-one-event-calendar';
	}

	function get_category_taxonomy_id() {
		return 'events_categories';
	}

	function save_recurring_event_options( $data ) {
		if ( isset( $data['recurring_once'] ) and $data['recurring_once'] )
			$this->save_option( 'recurring_once', boolval( $data['recurring_once'] ) );
		else
			$this->save_option( 'recurring_once', false );
	}

	function show_recurring_event_once() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'recurring_once' );
		return $this->get_option( 'recurring_once' );
	}

	function recurring_event_options() {
		?>
		<tr>
			<th><?php echo esc_html( __( 'Recurring Events:', 'event-calendar-newsletter' ) ) ?></th>
			<td>
				<label class="selectit">
					<input value="1" type="checkbox" name="recurring_once" id="recurring_once" <?php if ( $this->show_recurring_event_once() ) echo ' checked="checked"' ?> /> <?php echo esc_html( __( 'Show only first time a recurring event happens', 'event-calendar-newsletter' ) ) ?>
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
		$this->save_recurring_event_options( $data );
		$this->save_selected_categories( $data );
	}


	function filter_categories( $filters, $data ) {
		if ( isset( $data['tax_input'], $data['tax_input'][$this->get_category_taxonomy_id()] ) ) {
			$filters['cat_ids'] = array_values( $data['tax_input'][$this->get_category_taxonomy_id()] );
		}
		return $filters;
	}

}