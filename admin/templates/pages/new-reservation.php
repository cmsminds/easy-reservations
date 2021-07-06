<?php
/**
 * This file is used for templating the new reservation from admin.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/pages
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Get the reservable items.
$items_query = ersrv_get_posts( 'product' );
$items       = $items_query->posts;

// Get the customer.
$customers = get_users();
?>
<div class="wrap">
	<h1><?php esc_html_e( 'New Reservation', 'easy-reservations' ); ?></h1>
	<h4><?php esc_html_e( 'Fill in the details below to add a new customer reservation.', 'easy-reservations' ); ?></h4>
	<table class="form-table">
		<tbody>
			<!-- FIELD: ITEM ID -->
			<tr>
				<th scope="row"><label for="item-id"><?php esc_html_e( 'Reservation Item', 'easy-reservations' ); ?></label></th>
				<td>
					<select id="item-id">
						<option value=""><?php esc_html_e( 'Select item...', 'easy-reservations' ); ?></option>
						<?php
						if ( ! empty( $items ) && is_array( $items ) ) {
							foreach ( $items as $item_id ) {
								$item_title = get_the_title( $item_id );
								$item_title = "#{$item_id} - {$item_title}";
								echo wp_kses(
									'<option value="' . $item_id . '">' . $item_title . '</option>',
									array(
										'option' => array(
											'value' => array(),
										),
									)
								);
							}
						}
						?>
					</select>
					<p class="ersrv-form-description-text"><?php esc_html_e( 'Select the item to be reserved here.', 'easy-reservations' ); ?></p>
				</td>
			</tr>

			<!-- CUSTOMER -->
			<tr>
				<th scope="row"><label for="customer-id"><?php esc_html_e( 'Customer', 'easy-reservations' ); ?></label></th>
				<td>
					<select id="customer-id">
						<option value=""><?php esc_html_e( 'Select customer...', 'easy-reservations' ); ?></option>
						<?php
						if ( ! empty( $customers ) && is_array( $customers ) ) {
							foreach ( $customers as $customer_data ) {
								$customer_name  = $customer_data->data->display_name;
								$customer_email = $customer_data->data->user_email;
								$customer_name  = "#{$customer_data->ID} [{$customer_email}] - {$customer_name}";
								echo wp_kses(
									'<option value="' . $customer_data->ID . '">' . $customer_name . '</option>',
									array(
										'option' => array(
											'value' => array(),
										),
									)
								);
							}
						}
						?>
					</select>
					<a class="ersrv-create-new-customer-link" href="javascript:void(0);"><?php esc_html_e( 'Not listed here? Create new from here.', 'easy-reservations' ); ?></a>
					<p class="ersrv-form-description-text"><?php esc_html_e( 'Select the customer whom the reservation would be assigned.', 'easy-reservations' ); ?></p>
				</td>
			</tr>

			<!-- ACCOMODATION -->
			<tr>
				<th scope="row">
					<label for="accomodation"><?php esc_html_e( 'Accomodation', 'easy-reservations' ); ?></label>
					<small><?php esc_html_e( 'Limit: --', 'easy-reservations' ); ?></small>
				</th>
				<td>
					<p><input type="number" id="adult-accomodation-count" min="1" max="12" step="1" class="regular-text" placeholder="<?php esc_html_e( 'No. of adults.', 'easy-reservations' ); ?>"></p>
					<p><input type="number" id="kid-accomodation-count" min="1" max="12" step="1" class="regular-text" placeholder="<?php esc_html_e( 'No. of kids.', 'easy-reservations' ); ?>"></p>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" id="accomodation-limit" value="" />
	<button type="button" class="button ersrv-add-new-reservation"><?php esc_html_e( 'Add New Reservation Demo', 'easy-reservations' ); ?></button>
</div>
