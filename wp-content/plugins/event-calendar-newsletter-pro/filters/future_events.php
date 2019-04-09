<?php
function ecn_pro_future_events_days( $events_future_in_days ) {
	?>
	<?php for ( $i = 1; $i <= 13; $i++ ): ?>
		<?php if ( 7 == $i ) continue; ?>
		<option value="<?php echo ($i); ?>"<?php echo ( $i == $events_future_in_days ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d day', '%d days', $i, 'event-calendar-newsletter' ), $i ); ?></option>
	<?php endfor; ?>
	<?php
}

add_action( 'ecn_events_future_in_days_before', 'ecn_pro_future_events_days', 10, 1 );