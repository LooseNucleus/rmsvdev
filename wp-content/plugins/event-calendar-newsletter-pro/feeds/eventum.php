<?php
class ECNProEventumFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_tags' ), 10 );
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_cities' ), 10 );
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_states' ), 10 );
	    add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( $this, 'filter_events' ), 10, 4 );
    }

    function get_identifier() {
        return 'eventum';
    }

    function get_category_taxonomy_id() {
        return 'ecategory';
    }

    function get_tag_taxonomy_id() {
	    return 'etags';
    }

	/**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_categories( $data );
        $this->save_selected_tags( $data );
	    $this->save_selected_cities( $data );
	    $this->save_selected_states( $data );
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
	    $args = $this->filter_cities( $args, $data );
	    // TODO: Also add condition to filter_cities to check for meta_query
	    // TODO: Move this to a class to add arbitrary number of conditions/filters
	    $args = $this->filter_states( $args, $data );
        return $args;
    }

	/**
	 * Filtering by EVENTUM states
	 */
	function save_selected_states( $data ) {
		if ( isset( $data['state'] ) and is_array( $data['state'] ) )
			$this->save_option( 'selected_states', array_map( 'intval', $data['state'] ) );
		else
			$this->save_option( 'selected_states', array() );
	}

	function get_selected_states() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'state' );
		return $this->get_option( 'selected_states' );
	}

	function is_state_selected( $id ) {
		$states = $this->get_selected_states();
		if ( is_array( $states ) and in_array( $id, $states ) )
			return true;
		return false;
	}

	function display_states() {
		if ( ! function_exists( 'ecn_fetch_eventum_cities' ) )
			return;

		$state_data = ecn_fetch_eventum_states();
		?>
		<tr>
			<th><?php echo esc_html( __( 'Filter by State/Zone:', 'event-calendar-newsletter' ) ) ?></th>
			<td>
				<p><?php echo esc_html( __( 'Leave states unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
				<div class="categorydiv">
					<ul id="statechecklist" class="cat-checklist">
						<?php foreach ( $state_data as $data ): ?>
							<?php if ( ! is_array( $data ) ) continue; ?>
							<li id="eventum_state-<?= $data['zones_id'] ?>">
								<label class="selectit">
									<input value="<?= $data['zones_id'] ?>" type="checkbox" name="state[]" id="cb-select-<?= $data['zones_id'] ?>" <?php checked( $this->is_state_selected( $data['zones_id'] ) ) ?> /> <?php echo esc_html( strip_tags( $data['zone_name'] ) ) ?>
								</label>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</td>
		</tr>
		<?php
	}

	function filter_states( $args, $data ) {
		if ( isset( $data['state'] ) and is_array( $data['state'] ) ) {
			$meta_query_part = array(
				'key' => 'zones_id',
				'operator' => 'IN',
				'value' => $data['state'],
			);
			if ( isset( $args['meta_query'] ) and is_array( $args['meta_query'] ) ) {
				$args['meta_query'][] = $meta_query_part;
			} else {
				$args['meta_query'] = array(
					'relation' => 'AND',
					$meta_query_part,
				);
			}
		}
		return $args;
	}
	/**
	 * FILTERING BY EVENTUM CITIES
	 */

	function save_selected_cities( $data ) {
		if ( isset( $data['city'] ) and is_array( $data['city'] ) )
			$this->save_option( 'selected_cities', array_map( 'intval', $data['city'] ) );
		else
			$this->save_option( 'selected_cities', array() );
	}

	function get_selected_cities() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'city' );
		return $this->get_option( 'selected_cities' );
	}

	function is_city_selected( $id ) {
		$cities = $this->get_selected_cities();
		if ( is_array( $cities ) and in_array( $id, $cities ) )
			return true;
		return false;
	}

	function display_cities() {
    	if ( ! function_exists( 'ecn_fetch_eventum_cities' ) )
    		return;

	    $city_data = ecn_fetch_eventum_cities();
	    ?>
	    <tr>
		    <th><?php echo esc_html( __( 'Filter by City:', 'event-calendar-newsletter' ) ) ?></th>
		    <td>
			    <p><?php echo esc_html( __( 'Leave cities unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>

			    <div class="categorydiv">
				    <ul id="citychecklist" class="cat-checklist">
					    <?php foreach ( $city_data as $data ): ?>
						    <?php if ( ! is_array( $data ) ) continue; ?>
						    <li id="eventum_city-<?= $data['ID'] ?>">
							    <label class="selectit">
								    <input value="<?= $data['ID'] ?>" type="checkbox" name="city[]" id="cb-select-<?= $data['ID'] ?>" <?php checked( $this->is_city_selected( $data['ID'] ) ) ?> /> <?php echo esc_html( strip_tags( $data['title'] ) . ', ' . strip_tags( $data['country_name'] ) ) ?>
							    </label>
						    </li>
					    <?php endforeach; ?>
				    </ul>
			    </div>
		    </td>
	    </tr>
	    <?php
    }

	function filter_cities( $args, $data ) {
		if ( isset( $data['city'] ) and is_array( $data['city'] ) ) {
			$args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key' => 'post_city_id',
					'operator' => 'IN',
					'value' => $data['city'],
//					'type' => 'NUMERIC'
				),
			);
		}
		return $args;
	}

}
