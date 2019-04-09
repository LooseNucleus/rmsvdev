<?php

if ( file_exists( trailingslashit( dirname( __FILE__ ) ) . 'vars_dev.php' ) )
	include( trailingslashit( dirname( __FILE__ ) ) . 'vars_dev.php' );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
if ( ! defined( 'ECN_EDD_STORE_URL' ) )
	define( 'ECN_EDD_STORE_URL', 'https://eventcalendarnewsletter.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if ( ! class_exists( 'ECN_EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}
// the name of your product. This should match the download name in EDD exactly
define( 'ECN_EDD_ITEM_NAME', 'Event Calendar Newsletter Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of the settings page for the license input to be displayed
define( 'ECN_EDD_LICENSE_PAGE', 'ecn-license' );
define( 'ECN_EDD_LICENSE_STATUS', 'ecn_pro_license_status' );
define( 'ECN_EDD_LICENSE_DATA', 'ecn_pro_license_data' );
define( 'ECN_EDD_LICENSE_KEY', 'ecn_pro_license_key' );


/**
 * Show an error message that license needs to be activated
 */
function ecn_edd_check_license() {
    if ( 'valid' != get_option( ECN_EDD_LICENSE_STATUS ) ) {
        if ( ( ! isset( $_GET['page'] ) or ECN_EDD_LICENSE_PAGE != $_GET['page'] ) )
            add_action( 'admin_notices', 'ecn_edd_activate_notice' );
    }
}
add_action( 'admin_init', 'ecn_edd_check_license' );

function ecn_edd_activate_notice() {
	echo '<div class="error"><p>' .
	     sprintf( __( 'Event Calendar Newsletter needs to be activated. %sActivate Now%s or %sGet a License%s', 'event-calendar-newsletter' ), '<a href="' . admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE ) . '">', '</a>', '<a href="https://eventcalendarnewsletter.com/pro/?utm_campaign=activate-prompt&utm_source=plugin">', '</a>' ) .
	     '</p></div>';
}

function ecn_edd_sl_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( ECN_EDD_LICENSE_KEY ) );

	// setup the updater
	$edd_updater = new ECN_EDD_SL_Plugin_Updater( ECN_EDD_STORE_URL, ECN_PRO_PLUGIN_FILE, array(
			'version'   => ECN_PRO_VERSION,      // current version number
			'license'   => $license_key,         // license key (used get_option above to retrieve from DB)
			'item_name' => ECN_EDD_ITEM_NAME, // name of this plugin
			'author'    => 'Brian Hogg'   // author of this plugin
		)
	);
}
add_action( 'admin_init', 'ecn_edd_sl_updater', 0 );

function ecn_edd_license_menu() {
	add_submenu_page(
		'eventcalendarnewsletter',
		__( 'Event Calendar Newsletter Pro', 'event-calendar-newsletter' ),
		__( 'License', 'event-calendar-newsletter' ),
		apply_filters( 'ecn_admin_capability', 'add_users' ),
		ECN_EDD_LICENSE_PAGE,
		'ecn_edd_license_page'
	);
}
add_action( 'admin_menu', 'ecn_edd_license_menu', 100 );

function ecn_edd_license_page() {
	$license = get_option( ECN_EDD_LICENSE_KEY );
	$status  = get_option( ECN_EDD_LICENSE_STATUS );
	?>
	<div class="wrap">
	<h2><?php _e( 'Plugin License Options' ); ?></h2>
	<form method="post" action="<?php echo admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE ) ?>">

		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row" valign="top">
					<?php _e('License Key'); ?>
				</th>
				<td>
					<input id="<?= ECN_EDD_LICENSE_KEY ?>" name="<?= ECN_EDD_LICENSE_KEY ?>" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
					<label class="description" for="<?= ECN_EDD_LICENSE_KEY ?>"><?php _e('Enter your license key'); ?></label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" valign="top">
					<?php _e('Activate License'); ?>
				</th>
				<td>
					<?php if( $status !== false && $status == 'valid' ) { ?>
						<span style="color:green;"><?php _e('active'); ?></span>
						<?php wp_nonce_field( 'ecn_edd_nonce', 'ecn_edd_nonce' ); ?>
						<input type="submit" class="button-secondary" name="ecn_edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
					<?php } else {
						wp_nonce_field( 'ecn_edd_nonce', 'ecn_edd_nonce' ); ?>
						<input type="submit" class="button-primary" name="ecn_edd_license_activate" value="<?php _e('Activate License'); ?>"/>
					<?php } ?>
				</td>
			</tr>
			</tbody>
		</table>
	</form>
	<?php
}

function ecn_edd_sanitize_license( $new ) {
	$old = get_option( ECN_EDD_LICENSE_KEY );
	if( $old != $new ) {
		delete_option( ECN_EDD_LICENSE_STATUS ); // new license has been entered, so must reactivate
		update_option( ECN_EDD_LICENSE_KEY, $new );
	}
	return $new;
}

/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function ecn_edd_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ecn_edd_license_activate'] ) ) {

		// run a quick security check
		if( ! check_admin_referer( 'ecn_edd_nonce', 'ecn_edd_nonce' ) )
			return; // get out if we didn't click the Activate button

		// see if there's a new license key to save
		if ( isset( $_POST[ECN_EDD_LICENSE_KEY] ) )
			ecn_edd_sanitize_license( $_POST[ECN_EDD_LICENSE_KEY] );

		// retrieve the license from the database
		$license = trim( get_option( ECN_EDD_LICENSE_KEY ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_name'  => urlencode( ECN_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ECN_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

		} else {

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {

				switch( $license_data->error ) {

					case 'expired' :

						$message = sprintf(
							__( 'Your license key expired on %s.' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked' :

						$message = __( 'Your license key has been disabled.' );
						break;

					case 'missing' :

						$message = __( 'Invalid license.' );
						break;

					case 'invalid' :
					case 'site_inactive' :

						$message = __( 'Your license is not active for this URL.' );
						break;

					case 'item_name_mismatch' :

						$message = sprintf( __( 'This appears to be an invalid license key for %s.' ), EDD_SAMPLE_ITEM_NAME );
						break;

					case 'no_activations_left':

						$message = __( 'Your license key has reached its activation limit.' );
						break;

					default :

						$message = __( 'An error occurred, please try again.' );
						break;
				}

			}

		}

		// Check if anything passed on a message constituting a failure
		if ( ! empty( $message ) ) {
			$base_url = admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'ecn_sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// $license_data->license will be either "valid" or "invalid"

		update_option( ECN_EDD_LICENSE_STATUS, $license_data->license );
		update_option( ECN_EDD_LICENSE_DATA, $license_data );
		wp_redirect( admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE ) );
		exit();
	}
}
add_action( 'admin_init', 'ecn_edd_activate_license' );


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will decrease the site count
 ***********************************************/

function ecn_edd_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ecn_edd_license_deactivate'] ) ) {

		// run a quick security check
		if( ! check_admin_referer( 'ecn_edd_nonce', 'ecn_edd_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( ECN_EDD_LICENSE_KEY ) );


		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => urlencode( ECN_EDD_ITEM_NAME ), // the name of our product in EDD
			'url'        => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ECN_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.' );
			}

			$base_url = admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE );
			$redirect = add_query_arg( array( 'ecn_sl_activation' => 'false', 'message' => urlencode( $message ) ), $base_url );

			wp_redirect( $redirect );
			exit();
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' ) {
			delete_option( ECN_EDD_LICENSE_STATUS );
		}

		wp_redirect( admin_url( 'admin.php?page=' . ECN_EDD_LICENSE_PAGE ) );
		exit();

	}
}
add_action( 'admin_init', 'ecn_edd_deactivate_license' );

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function ecn_edd_admin_notices() {
	if ( isset( $_GET['ecn_sl_activation'] ) && ! empty( $_GET['message'] ) ) {

		switch( $_GET['ecn_sl_activation'] ) {

			case 'false':
				$message = urldecode( $_GET['message'] );
				?>
				<div class="error">
					<p><?php echo $message; ?></p>
				</div>
				<?php
				break;

			case 'true':
			default:
				// Developers can put a custom success message here for when activation is successful if they way.
				break;

		}
	}
}
add_action( 'admin_notices', 'ecn_edd_admin_notices' );

function edd_ecn_check_license() {
	global $wp_version;

	$check_cache_key = 'ecn_edd_license_check';

	// Don't check again if the cache key option hasn't expired.  Use get_option vs. transient
	// due to potential issue with some cache systems
	if ( get_option( $check_cache_key ) and get_option( $check_cache_key ) > current_time( 'timestamp' ) )
		return;

	$license = trim( get_option( ECN_EDD_LICENSE_KEY ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( ECN_EDD_ITEM_NAME ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( ECN_EDD_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) ) {
		// try again in 2 hours
		update_option( $check_cache_key, current_time( 'timestamp' ) + ( 60 * 60 * 2 ) );
		return false;
	}

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	update_option( ECN_EDD_LICENSE_STATUS, $license_data->license );
	update_option( ECN_EDD_LICENSE_DATA, $license_data );

	// check again in 24 hours
	update_option( $check_cache_key, current_time( 'timestamp' ) + ( 60 * 60 * 24 ) );
}
add_action( 'admin_init', 'edd_ecn_check_license' );

function edd_ecn_get_price_id() {
	$license_data = get_option( ECN_EDD_LICENSE_DATA );
	if ( is_object( $license_data ) and isset( $license_data->price_id ) )
		return $license_data->price_id;
	return false;
}
