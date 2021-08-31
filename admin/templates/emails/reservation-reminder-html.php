<?php
/**
 * Reservation reminder email.
 *
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/emails
 */
defined( 'ABSPATH' ) || exit;

$opening_paragraph = sprintf( __( 'This is a kind reminder about your reservation order #%1$d that was placed on %2$s. The details about the reservation item are as follows:', 'easy-reservations' ), $item_data->order_id, $item_data->order_date );
$view_order_url    = $item_data->order_view_url;
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

				// Print the item checkin date.
				if ( ! empty( $order_item['checkin_date'] ) ) {
					echo '<p>' . sprintf( __( 'Checkin Date: %1$s', 'easy-reservations' ), $order_item['checkin_date'] ) . '</p>';
				}

				// Print the item checkout date.
				if ( ! empty( $order_item['checkout_date'] ) ) {
					echo '<p>' . sprintf( __( 'Checkout Date: %1$s', 'easy-reservations' ), $order_item['checkout_date'] ) . '</p>';
				}

				// Print the adult count.
				if ( ! empty( $order_item['adult_count'] ) ) {
					echo '<p>' . sprintf( __( 'Adult Count: %1$s', 'easy-reservations' ), $order_item['adult_count'] ) . '</p>';
				}

				// Print the adult subtotal.
				if ( ! empty( $order_item['adult_subtotal'] ) ) {
					echo '<p>' . sprintf( __( 'Adult Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['adult_subtotal'] ) ) . '</p>';
				}

				// Print the kids count.
				if ( ! empty( $order_item['kids_count'] ) ) {
					echo '<p>' . sprintf( __( 'Kids Count: %1$s', 'easy-reservations' ), $order_item['kids_count'] ) . '</p>';
				}

				// Print the kids subtotal.
				if ( ! empty( $order_item['kids_subtotal'] ) ) {
					echo '<p>' . sprintf( __( 'Kids Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['kids_subtotal'] ) ) . '</p>';
				}

				// Print the security amount.
				if ( ! empty( $order_item['security'] ) ) {
					echo '<p>' . sprintf( __( 'Security: %1$s', 'easy-reservations' ), wc_price( $order_item['security'] ) ) . '</p>';
				}

				// Print the amenities subtotal.
				if ( ! empty( $order_item['amenities_subtotal'] ) ) {
					echo '<p>' . sprintf( __( 'Amenities Subtotal: %1$s', 'easy-reservations' ), wc_price( $order_item['amenities_subtotal'] ) ) . '</p>';
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
<p><?php esc_html_e( 'This is a system generated email. Please DO NOT respond to it.', 'easy-reservations' ); ?></p>
<p><?php echo make_clickable( sprintf( __( 'You can view this order in the dashboard here: %s', 'easy-reservations' ), $view_order_url ) ); ?></p>
<?php do_action( 'woocommerce_email_footer' );
