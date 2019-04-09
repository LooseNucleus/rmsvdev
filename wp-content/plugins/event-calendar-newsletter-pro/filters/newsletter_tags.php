<?php
/**
 * Translate the new MailPoet 3 shortcodes
 */

function ecn_pro_newsletter_insert_custom_tag( $text, $user, $email ) {
	// TODO: Automated test for this

	if ( false !== strpos( $text, '{ecn-' ) ) {
		// Find and replace {ecn-12345} format tag with the saved template content
		preg_match_all( "/{ecn-([0-9]*)}/", $text, $matches );
		if ( is_array( $matches ) and isset( $matches[1] ) and is_array( $matches[1] ) ) {
			foreach ( $matches[1] as $saved_template_id ) {
				$text = str_replace( '{ecn-' . $saved_template_id . '}', ecn_pro_newsletter_custom_tag_content( intval( $saved_template_id ) ), $text );
			}
		}
	}
	return $text;
}
add_filter( 'newsletter_replace', 'ecn_pro_newsletter_insert_custom_tag', 10, 3 );

/**
 * Fetch newsletter output
 *
 * @param $saved_template_id
 *
 * @return string
 */
function ecn_pro_newsletter_custom_tag_content( $saved_template_id ) {
	global $ecn_admin_class, $ecn_pro;

	if ( ! ( $event_output = get_transient( 'ecn_newsletter_all_output_' . $saved_template_id ) ) ) {
		$ecn_pro->load_feeds();

		$data = ecn_get_saved_template_data( $saved_template_id );
		$event_output = $ecn_admin_class->get_output_from_data( wp_parse_args( array( 'is_newsletter' => true ), $data ) );

		if ( ! $event_output )
			$event_output = apply_filters( 'ecn_newsletter_no_events', '' );

		set_transient( 'ecn_mailpoet_all_output_' . $saved_template_id, $event_output, apply_filters( 'ecn_pro_newsletter_refresh_time', 60 ) );
	}

	return $event_output;
}
