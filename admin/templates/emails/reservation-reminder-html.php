<?php
/**
 * Reservation reminder email.
 */
$opening_paragraph = sprintf( __( 'This is a kind reminder about your reservation order #%1$d that was placed on %2$s. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->order_id, $item_data->order_date );
$view_order_url    = $item_data->order_view_url;
do_action( 'woocommerce_email_header', $email_heading );
$items = $item_data->items;
?>
<p><?php echo $opening_paragraph; ?></p>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" bordercolor="#eee">
	<tbody>
		<?php if ( ! empty( $items ) && is_array( $items ) ) { ?>
			<?php foreach ( $items as $item_obj ) { ?>
				<tr>
					<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $item_obj['item'] ); ?></th>
					<td style="text-align:left; border: 1px solid #eee;">
						<?php
						// Print the item quantity.
						if ( ! empty( $item_obj['quantity'] ) ) {
							echo '<p>' . sprintf( __( 'Quantity: %1$s', 'easy-reservations' ), $item_obj['quantity'] ) . '</p>';
						}

						// Print the item subtotal.
						if ( ! empty( $item_obj['subtotal'] ) ) {
							echo '<p>' . sprintf( __( 'Subtotal: %1$s', 'easy-reservations' ), wc_price( $item_obj['subtotal'] ) ) . '</p>';
						}

						// Print the item checkin date.
						if ( ! empty( $item_obj['checkin_date'] ) ) {
							echo '<p>' . sprintf( __( 'Checkin Date: %1$s', 'easy-reservations' ), $item_obj['checkin_date'] ) . '</p>';
						}

						// Print the item checkout date.
						if ( ! empty( $item_obj['checkout_date'] ) ) {
							echo '<p>' . sprintf( __( 'Checkout Date: %1$s', 'easy-reservations' ), $item_obj['checkout_date'] ) . '</p>';
						}

						// Print the adult count.
						if ( ! empty( $item_obj['adult_count'] ) ) {
							echo '<p>' . sprintf( __( 'Adult Count: %1$s', 'easy-reservations' ), $item_obj['adult_count'] ) . '</p>';
						}

						// Print the adult subtotal.
						if ( ! empty( $item_obj['adult_subtotal'] ) ) {
							echo '<p>' . sprintf( __( 'Adult Subtotal: %1$s', 'easy-reservations' ), wc_price( $item_obj['adult_subtotal'] ) ) . '</p>';
						}

						// Print the kids count.
						if ( ! empty( $item_obj['kids_count'] ) ) {
							echo '<p>' . sprintf( __( 'Kids Count: %1$s', 'easy-reservations' ), $item_obj['kids_count'] ) . '</p>';
						}

						// Print the kids subtotal.
						if ( ! empty( $item_obj['kids_subtotal'] ) ) {
							echo '<p>' . sprintf( __( 'Kids Subtotal: %1$s', 'easy-reservations' ), wc_price( $item_obj['kids_subtotal'] ) ) . '</p>';
						}

						// Print the security amount.
						if ( ! empty( $item_obj['security'] ) ) {
							echo '<p>' . sprintf( __( 'Security: %1$s', 'easy-reservations' ), wc_price( $item_obj['security'] ) ) . '</p>';
						}

						// Print the amenities subtotal.
						if ( ! empty( $item_obj['amenities_subtotal'] ) ) {
							echo '<p>' . sprintf( __( 'Amenities Subtotal: %1$s', 'easy-reservations' ), wc_price( $item_obj['amenities_subtotal'] ) ) . '</p>';
						}
						?>
					</td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>
</table>
<p><?php esc_html_e( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ); ?></p>
<p><?php echo make_clickable( sprintf( __( 'You can view this order in the dashboard here: %s', 'easy-reservations' ), $view_order_url ) ); ?></p>
<?php do_action( 'woocommerce_email_footer' );
