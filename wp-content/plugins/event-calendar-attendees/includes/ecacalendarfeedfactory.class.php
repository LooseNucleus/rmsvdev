<?php
if ( ! class_exists( 'ECACalendarFeedFactory' ) ) {
class ECACalendarFeedFactory {
    private static $_plugins = array(
        'AjaxCalendar',
        'TheEventsCalendar',
        'GoogleCalendarEvents',
	    'Ai1ec',
	    'EventsManager',
        'EventOrganiser',
    );

    public static function create( $identifier ) {
        foreach ( self::get_available_calendar_feeds() as $feed ) {
            if ( $identifier == $feed->get_identifier() )
                return $feed;
        }
        throw new Exception( sprintf( __( 'Invalid identifier %s, plugin deactivated?', 'event-calendar-attendees' ), $identifier ) );
    }

    /**
     * Return the available calendar feeds
     *
     * @return ECACalendarFeed[]
     */
    public static function get_available_calendar_feeds() {
        $retval = array();
	    foreach ( apply_filters( 'eca_available_plugins', self::$_plugins ) as $plugin ) {
            $class_name = "ECACalendarFeed$plugin";
		    if ( class_exists( $class_name ) ) {
	            $feed = new $class_name;
	            if ( $feed->is_feed_available() )
	                $retval[] = $feed;
		    }
        }
        return $retval;
    }
}
}