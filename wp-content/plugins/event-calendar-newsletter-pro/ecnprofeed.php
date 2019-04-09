<?php
abstract class ECNProFeed {
    const OPTION_NAME = 'ecn_pro';

    function __construct() {
        global $ecn_pro;
        if ( $ecn_pro->load_addon() )
            $this->add_filters();
    }

    abstract function get_identifier();
    abstract function add_filters();

    function get_all_options() {
        return get_option( self::OPTION_NAME, array() );
    }

    function get_option( $option_name, $default = array() ) {
        $options = $this->get_all_options();
        return ( isset( $options[$this->get_identifier()], $options[$this->get_identifier()][$option_name] ) ? $options[$this->get_identifier()][$option_name] : $default );
    }

    function save_option( $option_name, $value ) {
        $options = $this->get_all_options();
        $options[$this->get_identifier()][$option_name] = $value;
        update_option( self::OPTION_NAME, $options );
    }

    /**
     * The category tax ID, override in plugins that use categories for events or event calendars
     *
     * @return string
     */
    function get_category_taxonomy_id() {
        return '';
    }

    /**
     * The tag tax ID, override in plugins that use a different taxonomy for tags for events or event calendars
     *
     * @return string
     */
    function get_tag_taxonomy_id() {
        return 'post_tag';
    }

	/**
	 * Display the available categories for filtering
	 */
    function display_categories() {
        ?>
        <tr>
            <th><?php echo esc_html( __( 'Filter by Categories:', 'event-calendar-newsletter' ) ) ?></th>
            <td>
                <p><?php echo esc_html( __( 'Leave categories unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
                <div class="categorydiv">
                    <ul id="categorychecklist" class="categorychecklist cat-checklist tribe_event-checklist">
                        <?php wp_terms_checklist( null, array( 'taxonomy' => $this->get_category_taxonomy_id(), 'selected_cats' => $this->get_selected_categories() ) ) ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php
    }

	/**
	 * Get the selected categories, if any
	 *
	 * @return array
	 */
	function get_selected_categories() {
		if ( ecn_get_saved_template_id() ) {
			return (array) ecn_get_saved_template_value( ecn_get_saved_template_id(), 'tax_input', $this->get_category_taxonomy_id() );
		}
		return $this->get_option( 'selected_cats' );
	}

    /**
     * Save the selected categories to memory for the next time
     *
     * @param $data
     */
    function save_selected_categories( $data ) {
        if ( isset( $data['tax_input'], $data['tax_input'][$this->get_category_taxonomy_id()] ) ) {
            $this->save_option( 'selected_cats', array_values( $data['tax_input'][$this->get_category_taxonomy_id()] ) );
        } else {
            $this->save_option( 'selected_cats', array() );
        }
    }

    /**
     * Filter the WP_Query by categories, for plugins that use one
     *
     * @param $args
     * @param $data
     *
     * @return mixed
     */
    function filter_categories( $args, $data ) {
        return $this->filter_taxonomy( $args, $data, $this->get_category_taxonomy_id() );
    }

    function filter_taxonomy( $args, $data, $taxonomy ) {
        if ( isset( $data['tax_input'], $data['tax_input'][$taxonomy] ) ) {
            if ( ! isset( $args['tax_query'] ) or ! is_array( $args['tax_query'] ) )
                $args['tax_query'] = array();

            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => array_values( $data['tax_input'][$taxonomy] ),
            );
        }
        return $args;

    }

    /**
     * Get any additional taxonomies that we want to filter by.
     *
     * @return array of taxonomy ID => description
     */
    function get_additional_taxonomies() {
        return array();
    }

    function get_selected_tags() {
        if ( ecn_get_saved_template_id() ) {
            return (array) ecn_get_saved_template_value( ecn_get_saved_template_id(), 'tax_input', $this->get_tag_taxonomy_id() );
        }
        return $this->get_option( 'selected_tags' );
    }

    function display_tags() {
        ?>
        <tr>
            <th><?php echo esc_html( __( 'Filter by Tags:', 'event-calendar-newsletter' ) ) ?></th>
            <td>
                <p><?php echo esc_html( __( 'Leave tags unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
                <div class="categorydiv">
                    <ul id="tagschecklist" class="categorychecklist cat-checklist tribe_event-checklist">
                        <?php wp_terms_checklist( null, array( 'taxonomy' => $this->get_tag_taxonomy_id(), 'selected_cats' => $this->get_selected_tags() ) ) ?>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
    }

    /**
     * Save the selected categories to memory for the next time
     *
     * @param $data
     */
    function save_selected_tags( $data ) {
        if ( isset( $data['tax_input'], $data['tax_input'][$this->get_tag_taxonomy_id()] ) ) {
            $this->save_option( 'selected_tags', array_values( $data['tax_input'][$this->get_tag_taxonomy_id()] ) );
        } else {
            $this->save_option( 'selected_tags', array() );
        }
    }

    function filter_tags( $args, $data ) {
        return $this->filter_taxonomy( $args, $data, $this->get_tag_taxonomy_id() );
    }
}