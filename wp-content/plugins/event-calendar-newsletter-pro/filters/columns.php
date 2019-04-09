<?php
if ( ! class_exists( 'ECN_Columns' ) ) {

class ECN_Columns {
	function __construct() {
		add_filter( 'ecn_end_settings_page', array( $this, 'add_columns_option' ), 10, 2 );
		add_filter( 'ecn_format_output_from_event', array( $this, 'before_event_output' ), 10, 4 );
		add_filter( 'ecn_format_output_from_event_after', array( $this, 'after_event_output' ), 10, 4 );

		// don't do the RSS output until after all events have been run through
		add_filter( 'ecn_saved_template_add_rss_tags_after_output', array( $this, 'maybe_output_rss_tags_after' ), 10, 3 );
		add_filter( 'ecn_saved_template_wrap_content_in_rss_tags', array( $this, 'maybe_output_rss_tags_after' ), 10, 3 );
		add_filter( 'ecn_saved_template_add_rss_tags_before_output', array( $this, 'maybe_output_rss_tags_after' ), 10, 3 );
		add_filter( 'ecn_final_output_of_events', array( $this, 'add_rss_tags_to_output' ), 10, 3 );
	}

	/**
	 * If we're going to do columns, we want to not output the RSS tags around every event
	 *
	 * @param $will_output
	 * @param $event
	 * @param $args
	 *
	 * @return bool
	 */
	function maybe_output_rss_tags_after( $will_output, $event, $args ) {
		if ( $this->do_columns( $args ) )
			$will_output = false;
		return $will_output;
	}

	function add_rss_tags_to_output( $output, $args, $events ) {
		if ( isset( $args['is_rss'] ) and $args['is_rss'] and $this->do_columns( $args ) ) {
			$output = '<description><![CDATA[' . $output . ']]></description>
				<content:encoded><![CDATA[' . $output . ']]></content:encoded>';
			$tags = '<item>';
			$tags .= '<title>ECN Columns Output</title>';
			$tags .= '<link>' . home_url() . '</link>';
			$tags .= '<pubDate>';
			// setting pubDate to midnight today so it always appears 'new' to MailChimp
			$tags .= mysql2date( 'D, d M Y H:i:s +0000', date( 'Y-m-d' ) . ' 00:00:00', false );
			$tags .= '</pubDate>';
			$tags .= '<guid isPermaLink="false">' . home_url() . '</guid>';
			$output = $tags . $output . '</item>';

		}
		return $output;
	}

	/**
	 * If we're using columns we want the date to be today since we're lumping everything into one
	 * @param $args
	 *
	 * @return mixed
	 */
	function set_feed_event_publication_date_to_today( $args ) {
		if ( $this->do_columns( $args ) )
			$args['feed_event_publication_date'] = 'today';
		return $args;
	}

	function add_columns_option( $current_plugin, $data ) {
		$columns = ( isset( $data['column_count'] ) ? intval( $data['column_count'] ) : 2 );
		?>
		<div id="output_columns">
			<label><input type="checkbox" name="enable_columns" value="1"  <?php checked( 1, ( isset( $data['enable_columns'] ) ? $data['enable_columns'] : false ) ) ?> /> Split output into
				<select name="column_count">
					<option value="2" <?php selected( 2, $columns ) ?>>2</option>
					<option value="3" <?php selected( 3, $columns ) ?>>3</option>
					<option value="4" <?php selected( 4, $columns ) ?>>4</option>
					<option value="5" <?php selected( 5, $columns ) ?>>5</option>
				</select>
				column(s)
			</label>
		</div>
		<?php
	}

	function do_columns( $args ) {
		return ( isset( $args['enable_columns'] ) and $args['enable_columns'] and isset( $args['column_count'] ) and is_numeric( $args['column_count'] ) and $args['column_count'] > 1 );
	}

	/**
	 * Add table row before the event output
	 * Add "first" or "last" class on the table cell depending on $args['event_number']
	 *      (ie. if $args['event_number'] is even or odd
	 * Add the wrapping table HTML if the first event
	 *
	 * @param $output
	 * @param $event
	 * @param $args
	 * @param $previous_date
	 *
	 * @return string
	 */
	function before_event_output( $event_output, $event, $args, $previous_date ) {
		if ( ! $this->do_columns( $args ) )
			return $event_output;

		if ( 1 == $args['event_number'] )
			$event_output .= '<table style="width:100%;" class="event">';

		if ( 1 == $args['event_number'] or ( $args['event_number'] - 1 ) / intval( $args['column_count'] ) == floor( $args['event_number'] / intval( $args['column_count'] ) ) ) {
			// multiple columns, so if one of the even rows, pop in the table row HTML
			$event_output .= '<tr>';

			// If the first of 2 columns, add in the 'first' class
			$extra_class = 'first';
		} else {
			$extra_class = 'last';
		}

		// Begin the table cell HTML
		$event_output .= '<td class="' . $extra_class . '" style="vertical-align:top;padding:5px;width:' . ( 100 / intval( $args['column_count'] ) ) . '%;">';
		return $event_output;
	}

	/**
	 * Add last table row if the last event (based on count( $args['events'] ))
	 *
	 * @param $output
	 * @param $event
	 * @param $args
	 * @param $previous_date
	 *
	 * @return string
	 */
	function after_event_output( $event_output, $event, $args, $previous_date ) {
		if ( ! $this->do_columns( $args ) )
			return $event_output;

		$event_output .= '</td>';
		if ( $args['event_number'] == count( $args['events'] ) or $args['event_number'] / intval( $args['column_count'] ) == floor( $args['event_number'] / intval( $args['column_count'] ) ) )
			$event_output .= '</tr>';
		if ( $args['event_number'] == count( $args['events'] ) )
			$event_output .= '</table>';
		return $event_output;
	}
}

new ECN_Columns();

}