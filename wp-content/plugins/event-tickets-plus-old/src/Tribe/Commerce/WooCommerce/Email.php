<?php

if ( class_exists( 'Tribe__Tickets_Plus__Commerce__WooCommerce__Email' ) || ! class_exists( 'WC_Email' ) ) {
	return;
}

class Tribe__Tickets_Plus__Commerce__WooCommerce__Email extends WC_Email {

	public $email_type;
	public $enabled;

	public function __construct() {

		$this->id          = 'wootickets';
		$this->title       = __( 'Tickets', 'event-tickets-plus' );
		$this->description = __( 'Email the user will receive after a completed order with the tickets they purchased.', 'event-tickets-plus' );

		$this->subject = __( 'Your tickets from {site_title}', 'event-tickets-plus' );


		// Triggers for this email
		add_action( 'wootickets-send-tickets-email', array( $this, 'trigger' ) );


		// Call parent constuctor
		parent::__construct();

		$this->enabled = apply_filters( 'wootickets-tickets-email-enabled', 'yes' );
		$this->email_type = 'html';

	}


	public function trigger( $order_id ) {

		if ( $order_id ) {
			$this->object    = new WC_Order( $order_id );
			$this->recipient = method_exists( $this->object, 'get_billing_email' )
				? $this->object->get_billing_email() // WC 3.x
				: $this->object->billing_email; // WC 2.x
		}

		if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
			return;
		}

		$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
	}

	/**
	 * get_subject function.
	 *
	 * @return string
	 */
	public function get_subject() {
		return apply_filters( 'wootickets_ticket_email_subject', $this->format_string( $this->subject ), $this->object );
	}

	/**
	 * get_attachments function.
	 *
	 * Gets an array of attachments (each item to be a full path file name) to
	 * attach to the email.
	 *
	 * @return array
	 */
	public function get_attachments() {
		/**
		 * Filters the array of files to be attached to the WooCommmerce Ticket
		 * email.
		 *
		 * Example use case is the PDF Tickets extension.
		 *
		 * @param array  $attachments  An array of full path file names.
		 * @param int    $this->id     The email method ID.
		 * @param object $this->object Object this email is for, for example a
		 *                             customer, product, or email.
		 */
		return apply_filters( 'tribe_tickets_plus_woo_email_attachments', array(), $this->id, $this->object );
	}


	/**
	 * Retrieve the full HTML for the tickets email
	 *
	 *
	 * @return string
	 */
	public function get_content_html() {

		$wootickets = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_instance();

		$attendees = method_exists( $this->object, 'get_id' )
			? $wootickets->get_attendees_by_id( $this->object->get_id() ) // WC 3.x
			: $wootickets->get_attendees_by_id( $this->object->id ); // WC 2.x

		return $wootickets->generate_tickets_email_content( $attendees );
	}


	/**
	 * Initialise Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'subject' => array(
				'title'            => __( 'Subject', 'woocommerce' ),
				'type'             => 'text',
				'description'      => sprintf( __( 'Defaults to <code>%s</code>', 'woocommerce' ), $this->subject ),
				'placeholder'      => '',
				'default'          => '',
			),
		);
	}
}
