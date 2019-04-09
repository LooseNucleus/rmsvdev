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

defined( 'ABSPATH' ) or exit;

use SkyVerge\WooCommerce\PluginFramework\v5_3_0 as Framework;


/**
 * WooCommerce Authorize.Net CIM Gateway main plugin class.
 *
 * @since 2.0.0
 */
class WC_Authorize_Net_CIM extends Framework\SV_WC_Payment_Gateway_Plugin {


	/** string version number */
	const VERSION = '2.10.2';


	/** @var \WC_Authorize_Net_CIM_Webhooks the webhooks handler */
	protected $webhooks;

	/** @var \WC_Authorize_Net_CIM single instance of this plugin */
	protected static $instance;

	/** plugin ID */
	const PLUGIN_ID = 'authorize_net_cim';

	/** string the gateway class name */
	const CREDIT_CARD_GATEWAY_CLASS_NAME = 'WC_Gateway_Authorize_Net_CIM_Credit_Card';

	/** string the gateway ID */
	const CREDIT_CARD_GATEWAY_ID = 'authorize_net_cim_credit_card';

	/** string the gateway class name */
	const ECHECK_GATEWAY_CLASS_NAME = 'WC_Gateway_Authorize_Net_CIM_eCheck';

	/** string the gateway ID */
	const ECHECK_GATEWAY_ID = 'authorize_net_cim_echeck';


	/**
	 * Constructs the class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		parent::__construct(
			self::PLUGIN_ID,
			self::VERSION,
			array(
				'text_domain' => 'woocommerce-gateway-authorize-net-cim',
				'gateways'    => array(
					self::CREDIT_CARD_GATEWAY_ID => self::CREDIT_CARD_GATEWAY_CLASS_NAME,
					self::ECHECK_GATEWAY_ID      => self::ECHECK_GATEWAY_CLASS_NAME,
				),
				'dependencies' => array(
					'php_extensions' => array( 'SimpleXML', 'xmlwriter', 'dom' ),
				),
				'require_ssl' => true,
				'supports'    => array(
					self::FEATURE_CAPTURE_CHARGE,
					self::FEATURE_MY_PAYMENT_METHODS,
					self::FEATURE_CUSTOMER_ID,
				),
			)
		);

		// Load gateway files
		$this->includes();

		// display the admin Shipping Address ID user field
		add_action( 'wc_payment_gateway_' . $this->get_id() . '_user_profile', array( $this, 'display_shipping_address_id_field' ), 15 );

		// save the admin Shipping Address ID user field
		add_action( 'personal_options_update',  array( $this, 'save_shipping_address_id_field' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_shipping_address_id_field' ) );
	}


	/**
	 * Loads API and gateway classes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		// gateway classes
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-authorize-net-cim.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-authorize-net-cim-credit-card.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-gateway-authorize-net-cim-echeck.php' );

		// profile classes
		require_once( $this->get_plugin_path() . '/includes/class-wc-authorize-net-cim-payment-profile.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-authorize-net-cim-payment-profile-handler.php' );
		require_once( $this->get_plugin_path() . '/includes/class-wc-authorize-net-cim-shipping-address.php' );

		require_once( $this->get_plugin_path() . '/includes/webhooks/class-wc-authorize-net-cim-webhooks.php' );
		require_once( $this->get_plugin_path() . '/includes/webhooks/abstract-wc-authorize-net-cim-webhook.php' );
		require_once( $this->get_plugin_path() . '/includes/webhooks/abstract-wc-authorize-net-cim-customer-webhook.php' );
		require_once( $this->get_plugin_path() . '/includes/webhooks/class-wc-authorize-net-cim-customer-profile-webhook.php' );
		require_once( $this->get_plugin_path() . '/includes/webhooks/class-wc-authorize-net-cim-customer-payment-profile-webhook.php' );

		$this->webhooks = new WC_Authorize_Net_CIM_Webhooks( $this );

		if ( is_admin() ) {

			require_once( $this->get_plugin_path() . '/includes/class-wc-authorize-net-cim-payment-profile-editor.php' );

		// require checkout billing fields for non-US stores, as all European card processors require the billing fields
		// in order to successfully process transactions
		} elseif ( ! strncmp( get_option( 'woocommerce_default_country' ), 'US:', 3 ) ) {

			// remove blank arrays from the state fields, otherwise it's hidden
			add_action( 'woocommerce_states', array( $this, 'tweak_states' ), 1 );

			//  require the billing fields
			add_filter( 'woocommerce_get_country_locale', array( $this, 'require_billing_fields' ), 100 );
		}
	}


	/**
	 * Gets the webhooks handler instance.
	 *
	 * @since 2.8.0
	 *
	 * @return \WC_Authorize_Net_CIM_Webhooks
	 */
	public function get_webhooks_instance() {

		return $this->webhooks;
	}


	/** Frontend methods ******************************************************/


	/**
	 * Removes blank State array values from countries.
	 *
	 * Before requiring all billing fields, the state array has to be removed of blank arrays, otherwise
	 * the field is hidden.
	 *
	 * @internal
	 *
	 * @see WC_Countries::__construct()
	 *
	 * @since 2.0.0
	 *
	 * @param array $countries the available countries
	 * @return array the available countries
	 */
	public function tweak_states( $countries ) {

		foreach ( $countries as $country_code => $states ) {

			if ( is_array( $countries[ $country_code ] ) && empty( $countries[ $country_code ] ) ) {
				$countries[ $country_code ] = null;
			}
		}

		return $countries;
	}


	/**
	 * Sets all state billing fields as required.
	 *
	 * This is hooked in when using a European payment processor.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @param array $locales countries and locale-specific address field info
	 * @return array
	 */
	public function require_billing_fields( $locales ) {

		foreach ( $locales as $country_code => $fields ) {

			if ( isset( $locales[ $country_code ]['state']['required'] ) ) {
				$locales[ $country_code ]['state']['required'] = true;
				$locales[ $country_code ]['state']['label']    = $this->get_state_label( $country_code );
			}
		}

		return $locales;
	}


	/**
	 * Gets a label for states that don't have one set by WooCommerce.
	 *
	 * @since 2.6.1
	 *
	 * @param string $country_code the 2-letter country code for the billing country
	 * @return string the label for the "billing state" field at checkout
	 */
	protected function get_state_label( $country_code ) {

		switch( $country_code ) {

			case 'AF':
			case 'AT':
			case 'BI':
			case 'KR':
			case 'PL':
			case 'PT':
			case 'LK':
			case 'SE':
			case 'VN':
				$label = __( 'Province', 'woocommerce-gateway-authorize-net-cim' );
			break;

			case 'AX':
			case 'YT':
				$label = __( 'Island', 'woocommerce-gateway-authorize-net-cim' );
			break;

			case 'DE':
				$label = __( 'State', 'woocommerce-gateway-authorize-net-cim' );
			break;

			case 'EE':
			case 'NO':
				$label = __( 'County', 'woocommerce-gateway-authorize-net-cim' );
			break;

			case 'FI':
			case 'IL':
			case 'LB':
				$label = __( 'District', 'woocommerce-gateway-authorize-net-cim' );
			break;

			default:
				$label = __( 'Region', 'woocommerce-gateway-authorize-net-cim' );
		}

		return $label;
	}


	/** Admin methods ******************************************************/


	/**
	 * Gets the "Configure Credit Cards" or "Configure eCheck" plugin action links that go
	 * directly to the gateway settings page.
	 *
	 * @see Framework\SV_WC_Payment_Gateway_Plugin::get_settings_url()
	 *
	 * @since 2.0.0
	 *
	 * @param string $gateway_id the gateway ID
	 * @return string plugin configure link
	 */
	public function get_settings_link( $gateway_id = null ) {

		return sprintf( '<a href="%s">%s</a>',
			$this->get_settings_url( $gateway_id ),
			self::CREDIT_CARD_GATEWAY_ID === $gateway_id ? __( 'Configure Credit Cards', 'woocommerce-gateway-authorize-net-cim' ) : __( 'Configure eChecks', 'woocommerce-gateway-authorize-net-cim' )
		);
	}


	/**
	 * Adds any necessary admin notices for configuration issues.
	 *
	 * @see Framework\SV_WC_Plugin::add_admin_notices()
	 *
	 * @since 2.0.0
	 */
	public function add_admin_notices() {

		// show any dependency notices
		parent::add_admin_notices();

		$settings = get_option( 'woocommerce_authorize_net_cim_credit_card_settings' );

		// install notice
		if ( empty( $settings ) && ! $this->get_admin_notice_handler()->is_notice_dismissed( 'install-notice' ) ) {

			$this->get_admin_notice_handler()->add_admin_notice(
				sprintf( __( 'Thanks for installing the WooCommerce Authorize.Net CIM Gateway! To start accepting payments, %sset your Authorize.Net API credentials%s. Need help? See the %sdocumentation%s.', 'woocommerce-gateway-authorize-net-cim' ),
					'<a href="' . $this->get_settings_url() . '">', '</a>',
					'<a target="_blank" href="' . $this->get_documentation_url() . '">', '</a>'
				), 'install-notice', array( 'notice_class' => 'updated' )
			);
		}

		$gateway = $this->get_gateway( self::CREDIT_CARD_GATEWAY_ID );

		// bail if gateway is not available, as proper credentials are needed first
		if ( ! $gateway->is_available() ) {
			return;
		}

		// check if CIM feature is enabled on customer's authorize.net account
		if ( ! get_option( 'wc_authorize_net_cim_feature_enabled' ) ) {

			if ( $gateway->is_cim_feature_enabled() ) {
				update_option( 'wc_authorize_net_cim_feature_enabled', true );
			} else {

				if ( ! $this->get_admin_notice_handler()->is_notice_dismissed( 'cim-add-on-notice' ) ) {
					$this->get_admin_notice_handler()->add_admin_notice(
						sprintf( __( 'The CIM Add-On is not enabled on your Authorize.Net account. Please %scontact Authorize.Net%s to enable CIM. You will be unable to process transactions until CIM is enabled. ', 'woocommerce-gateway-authorize-net-cim' ), '<a href="http://support.authorize.net" target="_blank">', '</a>' ),
						'cim-add-on-notice' );
				}
			}
		}

		if ( $gateway->is_accept_js_enabled() && isset( $_GET['page'] ) && 'wc-settings' === $_GET['page'] ) {

			$message = '';

			if ( ! $gateway->get_client_key() ) {
				$message = sprintf( __( "%s: A valid Client Key is required to use Accept.js at checkout.", 'woocommerce-gateway-authorize-net-cim' ), '<strong>' . $this->get_plugin_name() . '</strong>' );
			} elseif ( ! wc_checkout_is_https() ) {
				$message = sprintf( __( "%s: SSL is required to use Accept.js at checkout.", 'woocommerce-gateway-authorize-net-cim' ), '<strong>' . $this->get_plugin_name() . '</strong>' );
			}

			if ( $message ) {
				$this->get_admin_notice_handler()->add_admin_notice( $message, 'accept-js-status', array(
					'dismissible'  => false,
					'notice_class' => 'error',
				) );
			}
		}
	}


	/**
	 * Displays the admin Shipping Address ID user field.
	 *
	 * @since 2.6.3
	 *
	 * @param \WP_User $user user object
	 */
	public function display_shipping_address_id_field( $user ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		$unique_meta_key = '';
		$environments    = array();

		foreach ( $this->get_gateways() as $gateway ) {
			$environments[] = $gateway->get_environment();
		}

		$environments = array_unique( $environments );

		foreach ( $this->get_gateways() as $gateway ) {

			$meta_key = sprintf( 'wc_%s_shipping_address_id', $this->get_id() );

			if ( ! $gateway->is_production_environment() ) {
				$meta_key .= '_' . $gateway->get_environment();
			}

			// If a field with this meta key has already been set, skip this gateway
			if ( $meta_key === $unique_meta_key ) {
				continue;
			}

			$label = __( 'Shipping Address ID', 'woocommerce-gateway-authorize-net-cim' );

			if ( count( $environments ) > 1 ) {
				$label .= ' (' . $gateway->get_environment_name() . ')';
			}

			$value = get_user_meta( $user->ID, $meta_key, true );

			?>

			<tr>
				<th><label for="<?php esc_attr_e( $meta_key ); ?>"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<input class="regular-text" name="<?php esc_attr_e( $meta_key ); ?>" value="<?php esc_attr_e( $value ); ?>" type="text" /><br/>
					<span class="description"><?php esc_html_e( 'The shipping profile ID for the user. Only edit this if necessary.', 'woocommerce-gateway-authorize-net-cim' ); ?></span>
				</td>
			</tr>

			<?php

			$unique_meta_key = $meta_key;
		}
	}


	/**
	 * Saves the admin Shipping Address ID user field.
	 *
	 * @since 2.6.3
	 *
	 * @param int $user_id user profile ID
	 */
	public function save_shipping_address_id_field( $user_id ) {

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return;
		}

		foreach ( $this->get_gateways() as $gateway ) {

			$field_name = sprintf( 'wc_%s_shipping_address_id', $this->get_id() );

			if ( ! $gateway->is_production_environment() ) {
				$field_name .= '_' . $gateway->get_environment();
			}

			if ( isset( $_POST[ $field_name ] ) ) {
				update_user_meta( $user_id, $field_name, wc_clean( $_POST[ $field_name ] ) );
			}
		}
	}


	/** Helper methods ******************************************************/


	/**
	 * Gets the main plugin instance.
	 *
	 * Ensures only one instance is/can be loaded.
	 *
	 * @see wc_authorize_net_cim()
	 *
	 * @since 1.4.0
	 *
	 * @return \WC_Authorize_Net_CIM
	 */
	public static function instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Gets the plugin documentation URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_documentation_url()
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_documentation_url() {
		return 'http://docs.woocommerce.com/document/authorize-net-cim/';
	}


	/**
	 * Gets the plugin support URL.
	 *
	 * @see Framework\SV_WC_Plugin::get_support_url()
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function get_support_url() {

		return 'https://woocommerce.com/my-account/marketplace-ticket-form/';
	}


	/**
	 * Gets the plugin sales page URL.
	 *
	 * @since 2.10.0
	 *
	 * @return string
	 */
	public function get_sales_page_url() {

		return 'https://woocommerce.com/products/authorize-net-cim/';
	}


	/**
	 * Gets the plugin name, localized.
	 *
	 * @see Framework\SV_WC_Plugin::get_plugin_name()
	 *
	 * @since 1.1.0
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return __( 'WooCommerce Authorize.Net CIM Gateway', 'woocommerce-gateway-authorize-net-cim' );
	}


	/**
	 * Returns __FILE__
	 *
	 * @see Framework\SV_WC_Plugin::get_file()
	 *
	 * @since 1.1.0
	 *
	 * @return string the full path and filename of the plugin file
	 */
	protected function get_file() {
		return __FILE__;
	}


	/** Lifecycle methods *****/


	protected function init_lifecycle_handler() {

		require_once( $this->get_plugin_path() . '/includes/Lifecycle.php' );

		$this->lifecycle_handler = new \SkyVerge\WooCommerce\Authorize_Net\CIM\Lifecycle( $this );
	}


} // end WC_Authorize_Net_CIM


/**
 * Returns the One True Instance of Authorize.Net CIM.
 *
 * @since 1.4.0
 *
 * @return \WC_Authorize_Net_CIM
 */
function wc_authorize_net_cim() {
	return WC_Authorize_Net_CIM::instance();
}
