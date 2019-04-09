<?php
function ecn_pro_custom_date_range( $events_future_in_days ) {
	?>
	<option value="0"<?php echo ( 0 == $events_future_in_days ? ' SELECTED' : '' ); ?>>Custom</option>
	<?php
}
add_action( 'ecn_events_future_in_days_after', 'ecn_pro_custom_date_range', 10, 1 );

function ecn_pro_custom_date_range_inputs( $data ) {
	?>
	<div id="custom_datepickers">
		<input type="text" class="datepicker" id="custom_date_from" name="custom_date_from" placeholder="From..." required value="<?= ( isset( $data['custom_date_from'] ) ? esc_attr( $data['custom_date_from'] ) : date( 'Y-m-d' ) ) ?>"> to
		<input type="text" class="datepicker" id="custom_date_to" name="custom_date_to" placeholder="To..." required value="<?= ( isset( $data['custom_date_to'] ) ? esc_attr( $data['custom_date_to'] ) : date( 'Y-m-d' ) ) ?>">
	</div>
	<?php
}
add_action( 'ecn_events_future_in_days_after_select', 'ecn_pro_custom_date_range_inputs' );

