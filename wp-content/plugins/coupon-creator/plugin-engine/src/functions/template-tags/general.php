<?php
/**
 * Plugin Engine Functions
 */

//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'Pngx__Main' ) ) {
	return;
}

if ( ! function_exists( 'pngx_sanitize_url' ) ) {
	/**
	 * Sanitize URL for display on Front End
	 *
	 * @param $url
	 *
	 * @return mixed
	 */
	function pngx_sanitize_url( $url ) {

		$encode        = array( '&amp;', '&#038;', '&' );
		$replacement   = '%%PNGXAMP%%';
		$sanitized_url = $url;

		//replace ampersand before escaping to prevent breaking url
		$sanitized_url = str_replace( $encode, $replacement, $sanitized_url );
		$sanitized_url = esc_url( $sanitized_url );

		//add back ampersand after escaping
		return str_replace( $replacement, '&', $sanitized_url );
	}
}


if ( ! function_exists( 'pngx_detect_change' ) ) {
	/**
	 * Detect Changes to Meta Settings
	 *
	 * @param $_POST_arr
	 * @param $post_id
	 * @param $meta_key
	 * @param $post_key
	 *
	 * @return bool
	 */
	function pngx_detect_change( $_POST_arr, $post_id, $meta_key, $post_key ) {

		$current_val = get_post_meta( $post_id, $meta_key, true );
		$updated_val = ! empty( $_POST_arr[$post_key] ) ? esc_attr( $_POST_arr[$post_key] ) : null;

		if ( $current_val !== $updated_val ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'pngx_wp_strtotime' ) ) {
	/**
	 * Set Time Based off WordPress Timezone
	 *
	 * https://gist.github.com/anthonyeden/0cf8eb86f7e634b3d5ded4debc59cb84#file-wordpress-strtotime-php
	 *
	 * @param $str
	 *
	 * @return string
	 */
	function pngx_wp_strtotime( $str ) {
		// This function behaves a bit like PHP's StrToTime() function, but taking into account the Wordpress site's timezone
		// CAUTION: It will throw an exception when it receives invalid input - please catch it accordingly
		// From https://mediarealm.com.au/
		$tz_string = get_option( 'timezone_string' );
		$tz_offset = get_option( 'gmt_offset', 0 );
		if ( ! empty( $tz_string ) ) {
			// If site timezone option string exists, use it
			$timezone = $tz_string;
		} elseif ( $tz_offset == 0 ) {
			// get UTC offset, if it isn’t set then return UTC
			$timezone = 'UTC';
		} else {
			$timezone = $tz_offset;
			if ( substr( $tz_offset, 0, 1 ) != "-" && substr( $tz_offset, 0, 1 ) != "+" && substr( $tz_offset, 0, 1 ) != "U" ) {
				$timezone = "+" . $tz_offset;
			}
		}
		$datetime = new DateTime( $str, new DateTimeZone( $timezone ) );

		return $datetime->format( 'U' );
	}
}