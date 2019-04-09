<?php

function ecn_pro_offset_date_settings( $data ) {
	?>
	<tr id="offset_in_days">
        <th scope="row"><label for="events_offset_in_days"><?php echo esc_html( __( 'Start Events From:', 'event-calendar-newsletter' ) ) ?></label></th>
        <td>
			<select id="events_offset_in_days" name="events_offset_in_days">
                <option value="0"<?php echo ( 0 == $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php echo esc_html__( 'Today', 'event-calendar-newsletter' ); ?></option>
				<?php for ( $i = 1; $i <= 13; $i++ ): ?>
					<?php if ( 7 == $i ) continue; ?>
                    <option value="<?php echo ($i); ?>"<?php echo ( $i == $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d day', '%d days', $i, 'event-calendar-newsletter' ), $i ); ?></option>
				<?php endfor; ?>
				<?php for ( $i = 1; $i < 4; $i++ ): ?>
					<option value="<?php echo ($i * 7); ?>"<?php echo ( $i * 7 == $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d week', '%d weeks', $i, 'event-calendar-newsletter' ), $i ); ?></option>
				<?php endfor; ?>
				<?php for ( $i = 1; $i <= 12; $i++ ): ?>
					<option value="<?php echo ($i * 30); ?>"<?php echo ( $i * 30 == $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php echo sprintf( _n( '%d month', '%d months', $i, 'event-calendar-newsletter' ), $i ); ?></option>
				<?php endfor; ?>

                <option value="Monday"<?php echo ( 'Monday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Monday', 'event-calendar-newsletter' ) ?></option>
                <option value="Tuesday"<?php echo ( 'Tuesday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Tuesday', 'event-calendar-newsletter' ) ?></option>
                <option value="Wednesday"<?php echo ( 'Wednesday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Wednesday', 'event-calendar-newsletter' ) ?></option>
                <option value="Thursday"<?php echo ( 'Thursday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Thursday', 'event-calendar-newsletter' ) ?></option>
                <option value="Friday"<?php echo ( 'Friday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Friday', 'event-calendar-newsletter' ) ?></option>
                <option value="Saturday"<?php echo ( 'Saturday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Saturday', 'event-calendar-newsletter' ) ?></option>
                <option value="Sunday"<?php echo ( 'Sunday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Sunday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Monday"<?php echo ( 'next Monday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Monday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Tuesday"<?php echo ( 'next Tuesday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Tuesday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Wednesday"<?php echo ( 'next Wednesday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Wednesday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Thursday"<?php echo ( 'next Thursday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Thursday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Friday"<?php echo ( 'next Friday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Friday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Saturday"<?php echo ( 'next Saturday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Saturday', 'event-calendar-newsletter' ) ?></option>
                <option value="next Sunday"<?php echo ( 'next Sunday' === $data['events_offset_in_days'] ? ' SELECTED' : '' ); ?>><?php esc_html_e( 'Next Sunday', 'event-calendar-newsletter' ) ?></option>
            </select>
		</td>
	</tr>
	<?php
}
add_action( 'ecn_events_future_in_days_after_tr', 'ecn_pro_offset_date_settings' );

/**
 * Load saved template data for offset
 *
 * @param $data
 *
 * @return mixed
 */
function ecn_pro_load_saved_template_offset_date( $data ) {
	if ( ecn_get_saved_template_id() ) {
		$data['events_offset_in_days'] = ecn_get_saved_template_value( ecn_get_saved_template_id(), 'events_offset_in_days' );
	}
	if ( ! isset( $data['events_offset_in_days'] ) ) {
	    $data['events_offset_in_days'] = 0;
    }
	return $data;
}
add_filter( 'ecn_settings_data', 'ecn_pro_load_saved_template_offset_date' );