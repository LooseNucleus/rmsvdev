<div class="wrap tribe-attendees-page">
	<div id="icon-edit" class="icon32 icon32-tickets-orders"><br></div>

	<div id="tribe-attendees-summary" class="welcome-panel">
		<div class="welcome-panel-content">
			<div class="welcome-panel-column-container">

				<div class="welcome-panel-column welcome-panel-first">
					<h3><?php esc_html_e( 'Event Details', 'event-tickets-plus' ); ?></h3>
					<ul>
						<?php
						/**
						 * Provides an action that allows for the injections of fields at the top of the order report details meta ul
						 *
						 * @var $event_id
						 */
						do_action( 'tribe_tickets_plus_report_event_details_list_top', $event_id );

						/**
						 * Provides an action that allows for the injections of fields at the bottom of the order report details ul
						 *
						 * @var $event_id
						 */
						do_action( 'tribe_tickets_plus_report_event_details_list_bottom', $event_id );
						?>
					</ul>

					<?php
					/**
					 * Fires after the event details list (in the context of the WooCommerce Orders admin view).
					 *
					 * @param WP_Post      $event
					 * @param bool|WP_User $organizer
					 */
					do_action( 'tribe_tickets_plus_after_event_details_list', $event, $organizer );
					?>

				</div>
				<div class="welcome-panel-column welcome-panel-middle">
					<h3>
						<?php esc_html_e( 'Sales by Ticket Type', 'event-tickets-plus' ); ?>
						<?php echo $order_overview->get_sale_by_ticket_tooltip(); ?>
					</h3>
					<?php
					foreach ( $tickets_sold as $ticket_sold ) {

						//Only Display if a WooCommerce Ticket otherwise kick out
						if ( 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' != $ticket_sold['ticket']->provider_class ) {
							continue;
						}

						echo $order_overview->get_ticket_sale_infomation( $ticket_sold, $event_id );

					}
					?>
				</div>
				<div class="welcome-panel-column welcome-panel-last alternate">

					<div class="totals-header">
						<h3>
							<?php
							$completed_status = $order_overview->get_completed_status_class();
							$totals_header = sprintf(
								'%1$s: %2$s (%3$s)',
								__( 'Total Ticket Sales', 'event-tickets-plus' ),
								tribe_format_currency( number_format( $completed_status->get_line_total(), 2 ), $event_id ),
								$completed_status->get_qty()
							);
							echo esc_html( $totals_header );
							echo $order_overview->get_total_sale_tooltip();
							?>
						</h3>

						<div class="order-total">
							<?php
							$totals_header = sprintf(
								'%1$s: %2$s (%3$s)',
								__( 'Total Tickets Ordered', 'event-tickets-plus' ),
								tribe_format_currency( number_format( $order_overview->get_line_total(), 2 ), $event_id ),
								$order_overview->get_qty()
							);
							echo esc_html( $totals_header );
							echo $order_overview->get_total_order_tooltip();
							?>
						</div>
					</div>

					<div id="sales_breakdown_wrapper" class="tribe-event-meta-note">

						<?php
						/**
						 * Add Completed Status First and Skip in Loop
						 */
						?>
						<div>
							<strong><?php esc_html_e( 'Completed', 'event-tickets-plus' ); ?>:</strong>
							<?php echo esc_html( tribe_format_currency( number_format( $completed_status->get_line_total(), 2 ), $event_id ) ); ?>
							<span id="total_issued">(<?php echo esc_html( $completed_status->get_qty() ); ?>)</span>
						</div>

						<?php
						foreach ( $order_overview->statuses as $provider_key => $status ) {

							// skip the completed order as we always display it above
							if ( $order_overview->completed_status_id === $provider_key ) {
								continue;
							}

							// do not show status if no tickets
							if ( 0 >= (int) $status->get_qty() ) {
								continue;
							}
							?>
							<div>
								<strong><?php esc_html_e( $status->name, 'event-tickets-plus' ); ?>:</strong>
								<?php echo esc_html( tribe_format_currency( number_format( $status->get_line_total(), 2 ), $event_id ) ); ?>
								<span id="total_issued">(<?php echo esc_html( $status->get_qty() ); ?>)</span>
							</div>
							<?php

						}
						?>
						<div>
							<strong><?php esc_html_e( 'Discounts:', 'event-tickets-plus' ); ?></strong>
							<?php
							$sign = $discounts > 0 ? '-' : '';
							echo esc_html( $sign . tribe_format_currency( number_format( $discounts, 2 ), $event_id ) );
							?>
						</div>
					</div>

					<?php
					if ( $event_fees ) {
						?>
						<div class="tribe-event-meta tribe-event-meta-total-ticket-sales">
							<strong><?php esc_html_e( 'Total Ticket Sales:', 'event-tickets-plus' ) ?></strong>
							<?php echo esc_html( tribe_format_currency( number_format( $event_sales, 2 ), $event_id ) ); ?>
						</div>
						<div class="tribe-event-meta tribe-event-meta-total-site-fees">
							<strong><?php esc_html_e( 'Total Site Fees:', 'event-tickets-plus' ) ?></strong>
							<?php echo esc_html( tribe_format_currency( number_format( $event_fees, 2 ), $event_id ) ); ?>
							<div class="tribe-event-meta-note">
								<?php
								echo apply_filters( 'tribe_events_orders_report_site_fees_note', '', $event, $organizer );
								?>
							</div>
						</div>
						<?php
					}//end if
					?>
				</div>
			</div>
		</div>
	</div>

	<form id="topics-filter" method="get">
		<input type="hidden" name="<?php echo esc_attr( is_admin() ? 'page' : 'tribe[page]' ); ?>" value="<?php echo esc_attr( isset( $_GET['page'] ) ? $_GET['page'] : '' ); ?>"/>
		<input type="hidden" name="<?php echo esc_attr( is_admin() ? 'event_id' : 'tribe[event_id]' ); ?>" id="event_id" value="<?php echo esc_attr( $event_id ); ?>"/>
		<input type="hidden" name="<?php echo esc_attr( is_admin() ? 'post_type' : 'tribe[post_type]' ); ?>" value="<?php echo esc_attr( $event->post_type ); ?>"/>
		<?php echo $table; ?>
	</form>
</div>