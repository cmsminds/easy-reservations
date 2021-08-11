<?php
/**
 * HTML email content to be sent to the reservatio item owner.
 */

// Posted data.
$item_id = (int) filter_input( INPUT_POST, 'item_id', FILTER_SANITIZE_NUMBER_INT );
$name    = filter_input( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
$email   = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
$phone   = filter_input( INPUT_POST, 'phone', FILTER_SANITIZE_STRING );
$subject = filter_input( INPUT_POST, 'subject', FILTER_SANITIZE_STRING );
$message = filter_input( INPUT_POST, 'message', FILTER_SANITIZE_STRING );

$opening_paragraph = sprintf( __( 'There has been a contact request for the item: %1$s. The details of the item are as follows:', 'easy-reservations' ), get_the_title( $item_id ) );
do_action( 'woocommerce_email_header' );
?>
<table cellspacing="0" cellpadding="6" style="width: 100%; border: 1px solid #eee;" bordercolor="#eee">
	<tbody>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Name', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $name ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Email', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $email ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Phone', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $phone ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Subject', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $subject ); ?></td>
		</tr>
		<tr>
			<th scope="row" style="text-align:left; border: 1px solid #eee;"><?php _e( 'Message', 'easy-reservations' ); ?></th>
			<td style="text-align:left; border: 1px solid #eee;"><?php echo esc_html( $message ); ?></td>
		</tr>
	</tbody>
</table>
<?php do_action( 'woocommerce_email_footer' );
