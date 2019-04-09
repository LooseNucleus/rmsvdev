<?php


/**
 * Provides functionality shared by all Event Tickets Plus ticketing providers.
 */
abstract class Tribe__Tickets_Plus__Tickets extends Tribe__Tickets__Tickets {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Indicates if we currently require users to be logged in before they can obtain
	 * tickets.
	 *
	 * @return bool
	 */
	protected function login_required() {
		$requirements = (array) tribe_get_option( 'ticket-authentication-requirements', array() );

		return in_array( 'event-tickets-plus_all', $requirements );
	}

	/**
	 * Processes the front-end tickets form data to handle requests common to all type of tickets.
	 *
	 * Children classes should call this method when overriding.
	 */
	public function process_front_end_tickets_form() {
		$meta_store = new Tribe__Tickets_Plus__Meta__Storage();
		$meta_store->maybe_set_attendee_meta_cookie();
	}

	/**
	 * Returns the class name of the default module/provider.
	 *
	 * @since 4.6
	 *
	 * @return string
	 */
	public static function get_default_module() {
		$modules = array_keys( self::modules() );

		if ( 1 === count( $modules ) ) {
			// There's only one, just return it.
			self::$default_module = array_shift( $modules );
		} else {
			// Remove RSVP for this part
			unset( $modules[ array_search( 'Tribe__Tickets__RSVP', $modules ) ] );

			// We just return the first, so we don't show favoritism
			self::$default_module = array_shift( $modules );
		}

		/**
		 * Filters the default commerce module (provider)
		 *
		 * @since 4.6
		 *
		 * @param string default ticket module class name
		 * @param array array of ticket module class names
		 */
		return apply_filters( 'tribe_tickets_get_default_module', self::$default_module, $modules );
	}

	/**
	 * Get the saved or default ticket provider
	 *
	 * @since 4.6
	 *
	 * @param int $event_id - the post id of the event the ticket is attached to.
	 *
	 * @return string ticket module class name
	 */
	public static function get_event_ticket_provider( $event_id = null ) {

		// if  post ID is set, and a value has been saved, return the saved value
		if ( ! empty( $event_id ) ) {
			$saved = get_post_meta( $event_id, tribe( 'tickets.handler' )->key_provider_field, true );

			if ( ! empty( $saved ) ) {
				return $saved;
			}
		}

		// otherwise just return the default
		return self::get_default_module();
	}

	/**
	 * Returns the amount of global stock set for the event.
	 *
	 * A positive value does not necessarily mean global stock is currently in effect;
	 * always combine a call to this method with a call to $this->uses_global_stock()!
	 *
	 * @since 4.6
	 *
	 * @param int $post_id
	 * @return int
	 */
	protected function global_stock_level( $post_id ) {
		// In some cases (version mismatch with Event Tickets) the Global Stock class may not be available
		if ( ! class_exists( 'Tribe__Tickets__Global_Stock' ) ) {
			return 0;
		}

		$global_stock = new Tribe__Tickets__Global_Stock( $post_id );

		return $global_stock->get_stock_level();
	}

	/**
	 * Hooks into tribe_tickets_ajax_refresh_tables
	 * to add the capacty table and total capacity line to the refreshed tables
	 *
	 * @deprecated 4.6.2
	 * @since 4.6
	 *
	 * @param array $return data to be returned to ajax function
	 * @param int $event_id - the post id of the event/post the ticket is attached to.
	 * @return array $return data to return to ajax function
	 */
	public function refresh_tables( $return, $post_id ) {
		_deprecated_function( __METHOD__, '4.6.2', '' );

		// Add the capacity table to the return
		$return['capacity_table'] = tribe( 'tickets.admin.views' )->template( 'editor/capacity-table', null, false );
		$return['total_capacity'] = tribe( 'tickets-plus.admin.views' )->template( 'editor/total-capacity', null, false );

		return $return;
	}
}
