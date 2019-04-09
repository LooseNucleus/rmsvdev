<?php
$commerce_loader = tribe( 'tickets-plus.main' )->commerce_loader();

// When we don't have EDD nor Woo we bail
if ( ! $commerce_loader->is_woocommerce_active() && ! $commerce_loader->is_edd_active() ) {
	return;
}

?>
<button
	id="ticket_form_toggle"
	class="button-secondary ticket_form_toggle tribe-button-icon tribe-button-icon-plus"
	aria-label="<?php esc_attr_e( 'Add a new ticket', 'event-tickets-plus' ); ?>"
>
	<?php esc_html_e( 'New ticket', 'event-tickets-plus' ); ?>
</button>
