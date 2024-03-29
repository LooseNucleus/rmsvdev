<?php
$post_id = get_the_ID();
$total_tickets = tribe( 'tickets.handler' )->get_total_event_capacity( $post_id );

// only show if there are tickets
if ( empty( $total_tickets ) ) {
	return;
}
$post = get_post( $post_id );

$args = array(
	'post_type' => $post->post_type,
	'page' => 'tickets-orders',
	'event_id' => $post->ID,
);
$url = add_query_arg( $args, admin_url( 'edit.php' ) );

/**
 * Filter the Attendee Report Url
 *
 * @since TDB
 *
 * @param string $url  a url to attendee report
 * @param int    $post ->ID post id
 */
$url = apply_filters( 'tribe_filter_attendee_order_link', $url, $post->ID );
?>
<a
	href="<?php echo esc_url( $url ); ?>"
	class="button-secondary"
>
	<?php esc_html_e( 'View Orders', 'event-tickets-plus' ); ?>
</a>
