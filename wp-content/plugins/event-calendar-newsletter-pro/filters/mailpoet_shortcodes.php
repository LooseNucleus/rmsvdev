<?php
/**
 * Translate the new MailPoet 3 shortcodes
 */

function ecn_pro_mailpoet_3_custom_shortcode( $shortcode, $newsletter, $subscriber, $queue, $newsletter_body ) {
	if ( 0 === strpos( $shortcode, '[custom:ecn-' ) ) {
		return ecn_pro_mailpoet_custom_shortcode( str_replace( '[custom:', '', $shortcode ) );
	}
	return $shortcode;
}
add_filter( 'mailpoet_newsletter_shortcode', 'ecn_pro_mailpoet_3_custom_shortcode', 10, 5 );

/**
 * MailPoet 2 shortcodes
 *
 * @param $tag_value
 * @param $user_id
 *
 * @return mixed|string
 */
function ecn_pro_mailpoet_custom_shortcode( $tag_value, $user_id = 0 ) {
	global $ecn_admin_class, $ecn_pro;

	if ( 0 === strpos( $tag_value, 'ecn-' ) ) {
		$event_output = '';
		$saved_template_id = intval( str_replace( 'ecn-', '', $tag_value ) );

		if ( true ) { //! ( $event_output = get_transient( 'ecn_mailpoet_all_output_' . $saved_template_id ) ) ) {
			$ecn_pro->load_feeds();

			$data = ecn_get_saved_template_data( $saved_template_id );
			$event_output = $ecn_admin_class->get_output_from_data( wp_parse_args( array( 'is_mailpoet' => true ), $data ) );

			if ( ! $event_output )
				$event_output = apply_filters( 'ecn_mailpoet_no_events', '' );

			//set_transient( 'ecn_mailpoet_all_output_' . $saved_template_id, $event_output, apply_filters( 'ecn_pro_mailpoet_refresh_time', 60 * 60 ) );
		}
		return $event_output;
	}
	return $tag_value;
}
add_filter( 'wysija_shortcodes', 'ecn_pro_mailpoet_custom_shortcode', 10, 2 );