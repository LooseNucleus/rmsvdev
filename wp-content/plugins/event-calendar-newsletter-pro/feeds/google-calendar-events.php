<?php
class ECNProGoogleCalendarEventsFeed extends ECNProFeed {
    function add_filters() {
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_categories' ), 10 );
        add_filter( 'ecn_additional_filters_settings_html-' . $this->get_identifier(), array( &$this, 'display_calendars' ), 10 );
        add_action( 'ecn_save_options-' . $this->get_identifier(), array( &$this, 'save_form_data' ), 10, 1 );
        add_filter( 'ecn_fetch_events_args-' . $this->get_identifier(), array( &$this, 'filter_events' ), 10, 4 );
    }

    function get_identifier() {
        return 'google-calendar-events';
    }

    function get_category_taxonomy_id() {
        return 'calendar_category';
    }

    function get_post_type() {
        return 'calendar';
    }

    /**
     * Save the data for the next time the options are displayed
     *
     * @param $data
     */
    function save_form_data( $data ) {
        $this->save_selected_categories( $data );
        $this->save_selected_posts( $data );
    }

    function save_selected_posts( $data ) {
        if ( isset( $data['post'] ) and is_array( $data['post'] ) )
            $this->save_option( 'selected_posts', array_map( 'intval', $data['post'] ) );
        else
            $this->save_option( 'selected_posts', array() );
    }

    function get_selected_posts() {
	    if ( ecn_get_saved_template_id() )
		    return ecn_get_saved_template_value( ecn_get_saved_template_id(), 'post' );
        return $this->get_option( 'selected_posts' );
    }

    function is_post_selected( $id ) {
        $posts = $this->get_selected_posts();
        if ( is_array( $posts ) and in_array( $id, $posts ) )
            return true;
        return false;
    }

    function display_calendars() {
        ?>
        <tr>
            <th><?php echo esc_html( __( 'Filter by Calendars:', 'event-calendar-newsletter' ) ) ?></th>
            <td>
                <p><?php echo esc_html( __( 'Leave calendars unchecked to fetch all events', 'event-calendar-newsletter' ) ) ?></p>
                <div class="categorydiv">
                    <ul id="calendarchecklist" class="calendarchecklist">
                        <?php foreach ( get_posts( array( 'post_type' => $this->get_post_type(), 'posts_per_page' => 100 ) ) as $post ): ?>
                            <li id="calendar_post-<?= $post->ID ?>">
                                <label class="selectit">
                                    <input value="<?= $post->ID ?>" type="checkbox" name="post[]" id="cb-select-<?= $post->ID ?>" <?php if ( $this->is_post_selected( $post->ID ) ) echo ' checked="checked"' ?> /> <?php echo esc_html( $post->post_title ) ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </td>
        </tr>
        <?php
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
        $args = $this->filter_posts( $args, $data );
        return $args;
    }

    function filter_posts( $args, $data ) {
        if ( isset( $data['post'] ) and is_array( $data['post'] ) ) {
            $args['post__in'] = array_map( 'intval', $data['post'] );
        }
        return $args;
    }
}

