<?php
/**
 * Reservation cancellation request declined email.
 */
$opening_paragraph = sprintf( __( 'This is to update you that your request for cancelling reservation for %1$s in order #%2$s that you placed on %3$s has been declined. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->item['item'], $item_data->order_id, $item_data->order_date );
$order_item        = $item_data->item;
do_action( 'woocommerce_email_header', $email_heading );
?>
<p><?php echo $opening_paragraph; ?></p>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" bordercolor="#eee">
	<tbody>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $order_item['item'] ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;">
				<?php
				// Print the item subtotal.
				if ( ! empty( $order_item['subtotal'] ) ) {
					echo '<p>' . sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['subtotal'] ) ) . '</p>';
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Status', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php esc_html_e( 'Declined', 'easy-reservations' ); ?></td>
		</tr>
	</tbody>
</table>
<p><?php esc_html_e( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ); ?></p>
<p><?php echo make_clickable( sprintf( __( 'You can view this order in the dashboard here: %s', 'easy-reservations' ), $item_data->order_view_url ) ); ?></p>
<?php do_action( 'woocommerce_email_footer' );
