<?php
/*
Plugin Name: Event Calendar Newsletter Pro
Plugin URI: https://eventcalendarnewsletter.com
Description: Easy put events from your calendar inside of your newsletter. Spend less time promoting your events!
Version: 2.19
Author: Event Calendar Newsletter
Author URI: https://eventcalendarnewsletter.com
Text Domain: event-calendar-newsletter
License: GPL2
*/

/*  Copyright Brian Hogg <email: brian@brianhogg.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kittays please!' );

define( 'ECN_PRO_VERSION', '2.19' );

define( 'ECN_PRO_PLUGIN_FILE', __FILE__ );

include_once dirname( __FILE__ ) . '/core/includes/wp-requirements.php';

// Check plugin requirements before loading plugin.
$this_plugin_checks = new ECN_WP_Requirements( 'Event Calendar Newsletter', plugin_basename( __FILE__ ), array(
	'PHP'        => '5.3.3',
	'WordPress'  => '4.1',
	'Extensions' => array(
	),
) );
if ( $this_plugin_checks->pass() === false ) {
	$this_plugin_checks->halt();
	return;
}

/**
 * Load in any language files that we have setup
 */
function ecn_pro_load_textdomain() {
	load_plugin_textdomain( 'event-calendar-newsletter', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ecn_pro_load_textdomain' );


require_once( dirname( __FILE__ ) . '/edd/edd.php' );
require_once( dirname( __FILE__ ) . '/ecnprofeed.php' );
require_once( dirname( __FILE__ ) . '/filters/future_events.php' );
require_once( dirname( __FILE__ ) . '/filters/group_events.php' );
require_once( dirname( __FILE__ ) . '/filters/header_footer_text.php' );
require_once( dirname( __FILE__ ) . '/filters/custom_date_range.php' );
require_once( dirname( __FILE__ ) . '/filters/offset_date.php' );
require_once( dirname( __FILE__ ) . '/filters/saved_templates.php' );
require_once( dirname( __FILE__ ) . '/filters/mailpoet_shortcodes.php' );
require_once( dirname( __FILE__ ) . '/filters/newsletter_tags.php' );
require_once( dirname( __FILE__ ) . '/filters/settings.php' );
require_once( dirname( __FILE__ ) . '/helpers/enqueue_scripts_and_styles.php' );

// Designs
require_once( dirname( __FILE__ ) . '/output_formats/table.php' );

class ECNPro {
	const SAVED_TEMPLATE_CPT = 'ecn';

	function init() {
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'init', array( &$this, 'register_post_types' ) );
		add_action( 'admin_init', array( &$this, 'load_feeds' ) );
		add_action( 'ecn_available_plugins', array( &$this, 'add_pro_plugins' ) );
	}

	function load_addon() {
		return true;
		return ( 'valid' == get_option( 'ecn_pro_license_status' ) );
	}

	function activate() {
		$this->register_post_types();
		flush_rewrite_rules();
	}

	function register_post_types() {
		$args = array(
			'description'        => __( 'Saved event calendar newsletters', 'event-calendar-newsletter' ),
			'public'             => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => self::SAVED_TEMPLATE_CPT ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'supports'           => array( 'title' )
		);

		register_post_type( 'ecn', $args );
	}

	/**
	 * Add any pro-only plugins to the plugin factory
	 *
	 * @param $plugins array
	 * @return array
	 */
	function add_pro_plugins( $plugins ) {
		$plugins[] = 'Eventum';
		$plugins[] = 'Eventon';
		$plugins[] = 'CalendarizeIt';
		$plugins[] = 'EventEspresso';
		$plugins[] = 'CctCloud';
		$plugins[] = 'Geodir';
		$plugins[] = 'ChurchContent';
		return $plugins;
	}

	function load_feeds() {
		/**
		 * Load any pro-only plugins
		 */
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedcalendarizeit.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedeventum.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedeventon.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedcctcloud.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedeventespresso.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedgeodir.class.php' );
		require_once( dirname( __FILE__ ) . '/calendars/ecncalendarfeedchurchcontent.class.php' );

		foreach ( glob( dirname( __FILE__ ) . '/feeds/*.php' ) as $plugin_filename ) {
			require_once( dirname( __FILE__ ) . '/feeds/' . basename( $plugin_filename ) );
			$class_name = 'ECNPro' . str_replace( ' ', '', ucwords( str_replace( '-', ' ', str_replace( '.php', '', basename( $plugin_filename ) ) ) ) ) . 'Feed';
			if ( ! isset( $GLOBALS['ecn_feed_' . $class_name] ) )
				$GLOBALS['ecn_feed_' . $class_name] = new $class_name;
		}
	}

}

global $ecn_pro;
$ecn_pro = new ECNPro();
$ecn_pro->init();

function ecn_pro_deactivate_free_version_notice() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php echo sprintf( __( 'You need to deactivate and delete the old Event Calendar Newsletter plugin on the %splugins page%s', 'event-calendar-newsletter' ), '<a href="' . wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=event-calendar-newsletter%2Fevent-calendar-newsletter.php&amp;plugin_status=all&amp;paged=1&amp;s=', 'deactivate-plugin_event-calendar-newsletter/event-calendar-newsletter.php' ) . '">', '</a>' ); ?></p>
	</div>
	<?php
}

function ecn_pro_add_core() {
	if ( class_exists( 'ECNAdmin' ) ) {
		add_action( 'admin_notices', 'ecn_pro_deactivate_free_version_notice' );
		return;
	}
	require_once( 'core/event-calendar-newsletter.php' );

	$license_data = get_option( ECN_EDD_LICENSE_DATA );
	if ( edd_ecn_get_price_id() >= 2 )
		require_once( trailingslashit( dirname( __FILE__) ) . 'filters/columns.php' );
}
add_action( 'plugins_loaded', 'ecn_pro_add_core' );

// Handling of saved templates
$GLOBALS['ecn_saved_templates'] = new ECN_Saved_Templates();
