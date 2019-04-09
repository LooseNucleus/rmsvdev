<?php
class ECNProGeodirFeed extends ECNProFeed {
    function add_filters() {
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
	    add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_tags' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );
    }

    function get_identifier() {
        return 'geodir';
    }

	function get_category_taxonomy_id() {
		return 'gd_eventcategory';
	}

	function get_tag_taxonomy_id() {
		return 'gd_event_tags';
	}


	/**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_categories( $data );
	    $this->save_selected_tags( $data );
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

    function filter_event_types( $args, $data ) {
        for ( $count = 1; $count <= $this->get_event_type_count(); $count++ ) {
            $args = $this->filter_taxonomy( $args, $data, $this->get_event_type_identifier( $count ) );
        }
        return $args;
    }
}

