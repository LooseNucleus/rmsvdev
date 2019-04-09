<?php
function ecn_pro_group_events_values( $group_events ) {
	?>
	<option value="day"<?php if ( 'day' == $group_events ) echo ' SELECTED'; ?>>Group by day</option>
	<option value="month"<?php if ( 'month' == $group_events ) echo ' SELECTED'; ?>>Group by month</option>
<?php
}
add_action( 'ecn_additional_group_events_values', 'ecn_pro_group_events_values' );

function ecn_pro_group_events_output( $event_output, $event, $args, $previous_date ) {
	if ( 'day' == $args['group_events'] and date( 'Y-m-d', $previous_date ) != date( 'Y-m-d', $event->get_start_date() ) )
		$event_output = apply_filters( 'ecn_pro_group_by_day_prefix', get_ecn_option( 'group_by_tag_start', '<h3 class="group_event_title" style="padding-top:15px;">' ), $event, $args ) . date_i18n( apply_filters( 'ecn_pro_group_by_day_format', get_option( 'date_format' ) ), $event->get_start_date(), $event ) . apply_filters( 'ecn_pro_group_by_day_suffix', get_ecn_option( 'group_by_tag_end', '</h3>' ), $event, $args ) . $event_output;
	if ( 'month' == $args['group_events'] and date( 'Y-m', $previous_date ) != date( 'Y-m', $event->get_start_date() ) )
		$event_output = apply_filters( 'ecn_pro_group_by_month_prefix', get_ecn_option( 'group_by_tag_start', '<h3 class="group_event_title" style="padding-top:15px;">' ), $event, $args ) . date_i18n( apply_filters( 'ecn_pro_group_by_month_format', 'F' ), $event->get_start_date() ) . apply_filters( 'ecn_pro_group_by_month_suffix', get_ecn_option( 'group_by_tag_end', '</h3>' ), $event, $args ) . $event_output;
	return $event_output;
}
add_filter( 'ecn_event_output_from_format', 'ecn_pro_group_events_output', 10, 4 );