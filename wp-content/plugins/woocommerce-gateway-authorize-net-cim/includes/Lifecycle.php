<?php
/**
 * WooCommerce Authorize.Net CIM Gateway
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Authorize.Net CIM Gateway to newer
 * versions in the future. If you wish to customize WooCommerce Authorize.Net CIM Gateway for your
 * needs please refer to http://docs.woocommerce.com/document/authorize-net-cim/
 *
 * @package   WC-Gateway-Authorize-Net-CIM/Plugin
 * @author    SkyVerge
 * @copyright Copyright (c) 2013-2018, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace SkyVerge\WooCommerce\Authorize_Net\CIM;

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_3_0 as Framework;

/**
 * The plugin lifecycle handler.
 *
 * @since 2.10.2
 */
class Lifecycle extends Framework\Plugin\Lifecycle {


	/**
	 * Performs any upgrade tasks based on the provided installed version.
	 *
	 * TODO: if we start adding new upgrade routines, we'll want to break them into their own methods {CW 2018-10-18}
	 *
	 * @since 2.10.2
	 *
	 * @param string $installed_version currently installed version
	 */
	public function upgrade( $installed_version ) {

		// upgrade to 2.0.0
		if ( version_compare( $installed_version, '2.0.0', '<' ) ) {

			$this->get_plugin()->log( 'Starting upgrade to 2.0.0' );


			/** Upgrade settings */

			$old_cc_settings        = get_option( 'woocommerce_authorize_net_cim_settings' );
			$old_echeck_settings    = get_option( 'woocommerce_authorize_net_cim_echeck_settings' );

			if ( $old_cc_settings ) {

				// prior to 2.0.0, there was no settings for tokenization (always on) and enable_customer_decline_messages.
				// eCheck settings were inherited from the credit card gateway by default

				// credit card
				$new_cc_settings = array(
					'enabled'                          => ( isset( $old_cc_settings['enabled'] ) && 'yes' === $old_cc_settings['enabled'] ) ? 'yes' : 'no',
					'title'                            => ( ! empty( $old_cc_settings['title'] ) ) ? $old_cc_settings['title'] : 'Credit Card',
					'description'                      => ( ! empty( $old_cc_settings['description'] ) ) ? $old_cc_settings['description'] : 'Pay securely using your credit card.',
					'enable_csc'                       => ( isset( $old_cc_settings['require_cvv'] ) && 'yes' === $old_cc_settings['require_cvv'] ) ? 'yes' : 'no',
					'transaction_type'                 => ( isset( $old_cc_settings['transaction_type'] ) && 'auth_capture' === $old_cc_settings['transaction_type'] ) ? 'charge' : 'authorization',
					'card_types'                       => ( ! empty( $old_cc_settings['card_types'] ) ) ? $old_cc_settings['card_types'] : array( 'VISA', 'MC', 'AMEX', 'DISC' ),
					'tokenization'                     => 'yes',
					'environment'                      => ( isset( $old_cc_settings['test_mode'] ) && 'yes' === $old_cc_settings['test_mode'] ) ? 'test' : 'production',
					'inherit_settings'                 => 'no',
					'api_login_id'                     => ( ! empty( $old_cc_settings['api_login_id'] ) ) ? $old_cc_settings['api_login_id'] : '',
					'api_transaction_key'              => ( ! empty( $old_cc_settings['api_transaction_key'] ) ) ? $old_cc_settings['api_transaction_key'] : '',
					'test_api_login_id'                => ( ! empty( $old_cc_settings['test_api_login_id'] ) ) ? $old_cc_settings['test_api_login_id'] : '',
					'test_api_transaction_key'         => ( ! empty( $old_cc_settings['test_api_transaction_key'] ) ) ? $old_cc_settings['test_api_transaction_key'] : '',
					'enable_customer_decline_messages' => 'no',
					'debug_mode'                       => ( ! empty( $old_cc_settings['debug_mode'] ) ) ? $old_cc_settings['debug_mode'] : 'off',
				);

				// eCheck
				$new_echeck_settings = array(
					'enabled'                          => ( isset( $old_echeck_settings['enabled'] ) && 'yes' === $old_echeck_settings['enabled'] ) ? 'yes' : 'no',
					'title'                            => ( ! empty( $old_echeck_settings['title'] ) ) ? $old_echeck_settings['title'] : 'eCheck',
					'description'                      => ( ! empty( $old_echeck_settings['description'] ) ) ? $old_echeck_settings['description'] : 'Pay securely using your checking account.',
					'tokenization'                     => 'yes',
					'environment'                      => $new_cc_settings['environment'],
					'inherit_settings'                 => 'yes',
					'api_login_id'                     => '',
					'api_transaction_key'              => '',
					'test_api_login_id'                => '',
					'test_api_transaction_key'         => '',
					'enable_customer_decline_messages' => 'no',
					'debug_mode'                       => $new_cc_settings['debug_mode'],
				);

				// save new settings, remove old ones
				update_option( 'woocommerce_authorize_net_cim_credit_card_settings', $new_cc_settings );
				update_option( 'woocommerce_authorize_net_cim_echeck_settings', $new_echeck_settings );
				delete_option( 'woocommerce_authorize_net_cim_settings' );

				$this->get_plugin()->log( 'Settings upgraded.' );
			}


			/** Update meta key for customer profile ID and shipping profile ID */

			global $wpdb;

			// old key: _wc_authorize_net_cim_profile_id
			// new key: wc_authorize_net_cim_customer_profile_id
			// note that we don't know on a per-user basis what environment the customer ID was set in, so we assume production, just to be safe
			$rows = $wpdb->update( $wpdb->usermeta, array( 'meta_key' => 'wc_authorize_net_cim_customer_profile_id' ), array( 'meta_key' => '_wc_authorize_net_cim_profile_id' ) );

			$this->get_plugin()->log( sprintf( '%d users updated for customer profile ID.', $rows ) );

			// old key: _wc_authorize_net_cim_shipping_profile_id
			// new key: wc_authorize_net_cim_shipping_address_id
			$rows = $wpdb->update( $wpdb->usermeta, array( 'meta_key' => 'wc_authorize_net_cim_shipping_address_id' ), array( 'meta_key' => '_wc_authorize_net_cim_shipping_profile_id' ) );

			$this->get_plugin()->log( sprintf( '%d users updated for shipping address ID', $rows ) );


			/** Update meta values for order payment method & recurring payment method */

			// meta key: _payment_method
			// old value: authorize_net_cim
			// new value: authorize_net_cim_credit_card
			// note that the eCheck method has not changed from 1.x to 2.x
			$rows = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => 'authorize_net_cim_credit_card' ), array( 'meta_key' => '_payment_method', 'meta_value' => 'authorize_net_cim' ) );

			$this->get_plugin()->log( sprintf( '%d orders updated for payment method meta', $rows ) );

			// meta key: _recurring_payment_method
			// old value: authorize_net_cim
			// new value: authorize_net_cim_credit_card
			$rows = $wpdb->update( $wpdb->postmeta, array( 'meta_value' => 'authorize_net_cim_credit_card' ), array( 'meta_key' => '_recurring_payment_method', 'meta_value' => 'authorize_net_cim' ) );

			$this->get_plugin()->log( sprintf( '%d orders updated for recurring payment method meta', $rows ) );


			/** Convert payment profiles stored in legacy format to framework payment token format */

			$this->get_plugin()->log( 'Starting payment profile upgrade.' );

			$user_ids = $wpdb->get_col( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = '_wc_authorize_net_cim_payment_profiles'" );

			if ( $user_ids ) {

				// iterate through each user with a payment profile
				foreach ( $user_ids as $user_id ) {

					$customer_profile_id = get_user_meta( $user_id, 'wc_authorize_net_cim_customer_profile_id', true );

					$payment_profiles = get_user_meta( $user_id, '_wc_authorize_net_cim_payment_profiles', true );

					$cc_tokens = $echeck_tokens = array();

					// iterate through each payment profile
					foreach ( $payment_profiles as $profile_id => $profile ) {

						// bail if corrupted
						if ( ! $profile_id || empty( $profile['type'] ) ) {
							continue;
						}

						// parse expiry date
						if ( ! empty( $profile['exp_date'] ) && Framework\SV_WC_Helper::str_exists( $profile['exp_date'], '/' ) ) {
							list( $exp_month, $exp_year ) = explode( '/', $profile['exp_date'] );
						} else {
							$exp_month = $exp_year = '';
						}

						if ( 'Bank Account' === $profile['type'] ) {

							// eCheck tokens
							$echeck_tokens[ $profile_id ] = array(
								'type'                => 'echeck',
								'last_four'           => ! empty( $profile['last_four'] ) ? $profile['last_four'] : '',
								'customer_profile_id' => $customer_profile_id,
								'billing_hash'        => '',
								'payment_hash'        => '',
								'default'             => ( ! empty( $profile['active'] ) && $profile['active'] ),
								'exp_month'           => $exp_month,
								'exp_year'            => $exp_year,
							);

						} else {

							// parse card type
							switch ( $profile['type'] ) {
								case 'Visa':             $card_type = 'visa';   break;
								case 'American Express': $card_type = 'amex';   break;
								case 'MasterCard':       $card_type = 'mc';     break;
								case 'Discover':         $card_type = 'disc';   break;
								case 'Diners Club':      $card_type = 'diners'; break;
								case 'JCB':              $card_type = 'jcb';    break;
								default:                 $card_type = '';
							}

							// credit card tokens
							$cc_tokens[ $profile_id ] = array(
								'type'                => 'credit_card',
								'last_four'           => ! empty( $profile['last_four'] ) ? $profile['last_four'] : '',
								'customer_profile_id' => $customer_profile_id,
								'billing_hash'        => '',
								'payment_hash'        => '',
								'default'             => ( ! empty( $profile['active'] ) && $profile['active'] ),
								'card_type'           => $card_type,
								'exp_month'           => $exp_month,
								'exp_year'            => $exp_year,
							);
						}
					}

					// update credit card tokens
					if ( ! empty( $cc_tokens ) ) {
						update_user_meta( $user_id, '_wc_authorize_net_cim_credit_card_payment_tokens', $cc_tokens );
					}

					// update eCheck tokens
					if ( ! empty( $echeck_tokens ) ) {
						update_user_meta( $user_id, '_wc_authorize_net_cim_echeck_payment_tokens', $echeck_tokens );
					}

					// save the legacy payment profiles in case we need them later
					update_user_meta( $user_id, '_wc_authorize_net_cim_legacy_tokens', $payment_profiles );
					delete_user_meta( $user_id, '_wc_authorize_net_cim_payment_profiles' );

					$this->get_plugin()->log( sprintf( 'Converted payment profile for user ID: %s', $user_id) ) ;
				}
			}

			$this->get_plugin()->log( 'Completed payment profile upgrade.' );

			$this->get_plugin()->log( 'Completed upgrade for 2.0.0' );
		}

		// TODO: remove _wc_authorize_net_cim_legacy_tokens meta in a future version @MR 2015-07
	}


}
