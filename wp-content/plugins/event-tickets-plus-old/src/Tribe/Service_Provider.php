<?php
/**
 * Class Tribe__Tickets_Plus__Service_Provider
 *
 * Provides the Events Tickets Plus service.
 *
 * This class should handle implementation binding, builder functions and hooking for any first-level hook and be
 * devoid of business logic.
 *
 * @since 4.6
 */
class Tribe__Tickets_Plus__Service_Provider extends tad_DI52_ServiceProvider {
	/**
	 * Binds and sets up implementations.
	 *
	 * @since 4.6
	 */
	public function register() {
		$this->container->singleton( 'tickets-plus.assets', new Tribe__Tickets_Plus__Assets() );
		$this->container->singleton( 'tickets-plus.admin.views', 'Tribe__Tickets_Plus__Admin__Views' );
		$this->container->singleton( 'tickets-plus.editor', 'Tribe__Tickets_Plus__Editor', array( 'hook' ) );

		$this->container->singleton( 'tickets-plus.commerce.warnings', new Tribe__Tickets_Plus__Commerce__Warnings );

		// We use String here to specifically not load it before used
		$this->container->singleton( 'tickets-plus.commerce.woo', 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' );
		$this->container->singleton( 'tickets-plus.commerce.edd', 'Tribe__Tickets_Plus__Commerce__EDD__Main' );

		// Check In Status
		$this->container->singleton( 'tickets-plus.commerce.edd.checkin-stati', 'Tribe__Tickets_Plus__Commerce__EDD__CheckIn_Stati' );
		$this->container->singleton( 'tickets-plus.commerce.woo.checkin-stati', 'Tribe__Tickets_Plus__Commerce__WooCommerce__CheckIn_Stati' );

		$this->hook();
	}

	/**
	 * Any hooking for any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since 4.6
	 */
	protected function hook() {
		tribe( 'tickets-plus.editor' );

		if ( is_admin() ) {
			tribe( 'tickets-plus.admin.views' );
		}

		tribe( 'tickets-plus.assets' )->enqueue_scripts();
		tribe( 'tickets-plus.assets' )->admin_enqueue_scripts();
	}

	/**
	 * Binds and sets up implementations at boot time.
	 *
	 * @since 4.6
	 */
	public function boot() {
		// no ops
	}
}
