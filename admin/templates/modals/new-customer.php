<?php
/**
 * This file is used for templating the new customer modal on new reservation page.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$password = wp_generate_password( 12, true, true );
?>
<div id="ersrv-new-customer-modal" class="ersrv-modal">
	<div class="ersrv-modal-content">
		<span class="ersrv-close-modal">&times;</span>
		<h3><?php esc_html_e( 'New Customer', 'easy-reservations' ); ?></h3>
		<div class="ersrv-new-customer-details">
			<div class="ersrv-form-messages">
				<p class="ersrv-form-error"></p>
				<p class="ersrv-form-success"></p>
			</div>
			<div class="ersrv-customer-field first-name">
				<label for="ersrv-customer-first-name"><?php esc_html_e( 'First Name (optional)', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-first-name" />
			</div>
			<div class="ersrv-customer-field last-name">
				<label for="ersrv-customer-last-name"><?php esc_html_e( 'Last Name (optional)', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-last-name" />
			</div>
			<div class="ersrv-customer-field email">
				<label for="ersrv-customer-email"><?php esc_html_e( 'Email*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-email" />
				<span class="ersrv-form-field-error email-error"></span>
			</div>
			<div class="ersrv-customer-field password">
				<label for="ersrv-customer-password"><?php esc_html_e( 'Password*', 'easy-reservations' ); ?></label>
				<input type="text" id="ersrv-customer-password" value="<?php echo esc_html( $password ); ?>" />
				<a class="ersrv-generate-password" href="javascript:void(0);"><?php esc_html_e( 'Get new password', 'easy-reservations' ); ?></a>
				<span class="ersrv-form-field-error password-error"></span>
			</div>
		</div>
		<div class="submit-customer">
			<button class="button" type="button"><?php esc_html_e( 'Add New Customer', 'easy-reservations' ); ?></button>
		</div>
	</div>
</div>
