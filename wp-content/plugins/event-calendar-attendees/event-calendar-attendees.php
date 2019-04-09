<?php
/*
Plugin Name: Event Calendar Attendees
Plugin URI: http://wordpress.org/extend/plugins/event-calendar-attendees/
Description: Easily put events from your WordPress event calendar inside of a newsletter. Spend less time promoting your events!
Version: 2.6.1
Author: Event Calendar Attendees
Author URI: https://eventcalendarnewsletter.com/?utm_source=plugin&utm_campaign=author-link&utm_medium=link
Text Domain: event-calendar-attendees
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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'ECA_PLUGINS_URL', plugins_url( '', __FILE__ ) );

include_once dirname( __FILE__ ) . '/includes/wp-requirements.php';

// Check plugin requirements before loading plugin.
$this_plugin_checks = new ECA_WP_Requirements( 'Event Calendar Attendees', plugin_basename( __FILE__ ), array(
    'PHP'        => '5.3.3',
    'WordPress'  => '4.1',
    'Extensions' => array(
    ),
) );
if ( $this_plugin_checks->pass() === false ) {
    $this_plugin_checks->halt();
    return;
}


if ( file_exists( dirname( __FILE__ ) . '/config_dev.php' ) )
    include( dirname( __FILE__ ) . '/config_dev.php' );

require_once( dirname( __FILE__ ) . '/includes/ecaadmin.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarevent.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeed.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedfactory.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecasettings.class.php' );

// Supported plugins
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedajaxcalendar.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedtheeventscalendar.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedgooglecalendarevents.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedai1ec.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedeventsmanager.class.php' );
require_once( dirname( __FILE__ ) . '/includes/ecacalendarfeedeventorganiser.class.php' );

// Upgrade link
if ( ! function_exists( 'eca_add_action_links' ) ) {
    function eca_add_action_links( $links ) {
        $mylinks = array(
            '<a target="_blank" style="color:#3db634; font-weight: bold;" href="https://eventcalendarnewsletter.com/pro/?utm_source=plugin-list&utm_medium=upgrade-link&utm_campaign=plugin-list&utm_content=action-link">Upgrade</a>',
        );
        return array_merge( $links, $mylinks );
    }
    add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'eca_add_action_links' );
}

if ( ! function_exists( 'eca_load_textdomain' ) ) {
    /**
     * Load in any language files that we have setup
     */
    function eca_load_textdomain() {
        load_plugin_textdomain( 'event-calendar-attendees', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }
    add_action( 'plugins_loaded', 'eca_load_textdomain' );
}
