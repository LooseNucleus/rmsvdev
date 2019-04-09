<?php
function ecn_pro_header_text_setting( $plugin, $data ) {
	?>
	<tr>
		<th scope="row"><?php echo esc_html( __( 'Header Text:', 'event-calendar-newsletter' ) ) ?></th>
		<td>
			<div>
				<?php echo esc_html( __( 'Optional.  Header text will only appear if there is at least one event.', 'event-calendar-newsletter' ) ); ?>

				<div>
					<div>
						<?php wp_editor( ( isset( $data['headertext'] ) ? $data['headertext'] : '' ), 'headertext', array( 'textarea_rows' => 5, 'wpautop' => false, 'media_buttons' => false ) ); ?>
					</div>
				</div>
			</div>
		</td>
	</tr>

	<tr>
		<th scope="row"><?php echo esc_html( __( 'Footer Text:', 'event-calendar-newsletter' ) ) ?></th>
		<td>
			<div>
				<?php echo esc_html( __( 'Optional. Footer text will only appear if there is at least one event.', 'event-calendar-newsletter' ) ); ?>

				<div>
					<div>
						<?php wp_editor( ( isset( $data['footertext'] ) ? $data['footertext'] : '' ), 'footertext', array( 'textarea_rows' => 5, 'wpautop' => false, 'media_buttons' => false ) ); ?>
					</div>
				</div>
			</div>
		</td>
	</tr>
	<?php
}

add_action( 'ecn_additional_filters_settings_html', 'ecn_pro_header_text_setting', 10, 2 );

function ecn_pro_save_header_footer_text( $data ) {
	save_ecn_option( 'headertext', ( isset( $data['headertext'] ) ? $data['headertext'] : '' ) );
	save_ecn_option( 'footertext', ( isset( $data['footertext'] ) ? $data['footertext'] : '' ) );
}

add_action( 'ecn_save_options', 'ecn_pro_save_header_footer_text' );

function ecn_pro_add_header_text( $event_output, $event, $args, $previous_date ) {
	if ( isset( $args['headertext'] ) and $args['headertext'] and 1 == $args['event_number'] ) {
		$event_output = $args['headertext'] . $event_output;
	}
	return $event_output;
}

add_filter( 'ecn_format_output_from_event', 'ecn_pro_add_header_text', 10, 4 );

function ecn_pro_add_footer_text( $event_output, $event, $args, $previous_date ) {
	if ( isset( $args['footertext'] ) and $args['footertext'] and count( $args['events'] ) == $args['event_number'] ) {
		$event_output .= $args['footertext'];
	}
	return $event_output;
}

add_filter( 'ecn_format_output_from_event_after', 'ecn_pro_add_footer_text', 99, 4 );

/**
 * Load saved template data for header/footer
 *
 * @param $data
 *
 * @return mixed
 */
function ecn_pro_load_saved_template_header_footer_text( $data ) {
	if ( ecn_get_saved_template_id() ) {
		$data['headertext'] = ecn_get_saved_template_value( ecn_get_saved_template_id(), 'headertext' );
		$data['footertext'] = ecn_get_saved_template_value( ecn_get_saved_template_id(), 'footertext' );
	}
	return $data;
}

add_filter( 'ecn_settings_data', 'ecn_pro_load_saved_template_header_footer_text' );