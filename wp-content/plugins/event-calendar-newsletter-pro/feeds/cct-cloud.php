<?php
class ECNProCctCloudFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_cct_categories' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );

    }

	function get_identifier() {
        return 'cct-cloud';
    }


	/**
	 * Display the available categories for filtering
	 */
	function save_selected_cct_categories( $data ) {
		if ( isset( $data['cct_categories'] ) and is_array( $data['cct_categories'] ) )
			$this->save_option( 'selected_cct_categories', array_map( 'intval', $data['cct_categories'] ) );
		else
			$this->save_option( 'selected_cct_categories', array() );
	}

	function get_selected_cct_categories() {
		if ( ecn_get_saved_template_id() )
			return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'cct_categories' );
		return $this->get_option( 'selected_cct_categories' );
	}

	function is_cct_category_selected( $id ) {
		$categories = $this->get_selected_cct_categories();
		if ( is_array( $categories ) and in_array( $id, $categories ) )
			return true;
		return false;
	}

	function display_cct_categories() {
		$midbi = new Midbi();
		$midbi->init();
		$cat_url = $midbi->api . 'ecategory/';
		$MidbiRest = new MidbiRest();
		$categories = $MidbiRest->Process( $cat_url );
//		$categories = get_transient( 'cct_cloud_ecn_categories' );
//		if ( ! $categories ) {
//			$categories = $MidbiRest->Process( $cat_url );
//			if ( $categories ) {
//				set_transient( 'cct_cloud_ecn_categories', $categories, HOUR_IN_SECONDS );
//			}
//
//		}
		if ( is_array( $categories ) ) {
			?>
			<tr>
				<th><?php echo esc_html( __( 'Filter by Categories:', 'event-calendar-newsletter' ) ) ?></th>
				<td>
					<p><?php echo esc_html( __( 'Leave categories unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
					<div class="categorydiv">
						<ul id="categorychecklist" class="categorychecklist cat-checklist tribe_event-checklist">
							<?php foreach ( $categories as $category ): ?>
								<li id="cct_category-<?= $category->details->cat_id ?>">
									<label class="selectit">
										<input value="<?= esc_attr( $category->details->cat_id ) ?>" type="checkbox" name="cct_categories[]" id="cb-select-<?= $category->details->cat_id ?>" <?php if ( $this->is_cct_category_selected( $category->details->cat_id ) ) echo ' checked="checked"' ?> /> <?php echo esc_html( $category->details->name ) ?>
									</label>
								</li>
							<?php //wp_terms_checklist( null, array( 'taxonomy' => $this->get_category_taxonomy_id(), 'selected_cats' => $this->get_selected_categories() ) ) ?>
							<?php endforeach; ?>
						</ul>
					</div>
				</td>
			</tr>
			<?php
		}
	}


	/**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_cct_categories( $data );
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
        $args = $this->filter_cct_categories( $args, $data );
        return $args;
    }

	function filter_cct_categories( $args, $data ) {
		if ( isset( $data['cct_categories'] ) and is_array( $data['cct_categories'] ) ) {
			$args['category'] = array_map( 'intval', $data['cct_categories'] );
		}
		return $args;
	}
}
