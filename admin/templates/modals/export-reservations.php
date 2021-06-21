<?php
/**
 * This file is used for templating the export reservations modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/admin/templates/modals
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div id="ersrv-export-reservations-modal" class="ersrv-modal">
	<div class="ersrv-modal-content">
		<span class="ersrv-close-modal">&times;</span>
		<h3><?php esc_html_e( 'Export Reservations', 'easy-reservations' ); ?></h3>
		<div class="ersrv-date-ranges">
			<div class="from">
				<label for="ersrv-date-from"><?php esc_html_e( 'From', 'easy-reservations' ); ?></label>
				<input type="date" id="ersrv-date-from" />
			</div>
			<div class="to">
				<label for="ersrv-date-to"><?php esc_html_e( 'To', 'easy-reservations' ); ?></label>
				<input type="date" id="ersrv-date-to" />
			</div>
			<div class="format">
				<label for="ersrv-export-format"><?php esc_html_e( 'Format', 'easy-reservations' ); ?></label>
				<select id="ersrv-export-format">
					<option value="csv"><?php esc_html_e( 'CSV', 'easy-reservations' ); ?></option>
					<option value="pdf"><?php esc_html_e( 'PDF', 'easy-reservations' ); ?></option>
					<option value="xlsx"><?php esc_html_e( 'XSLX', 'easy-reservations' ); ?></option>
					<option value="json"><?php esc_html_e( 'JSON', 'easy-reservations' ); ?></option>
				</select>
			</div>
			<div class="submit-export">
				<button class="button export-reservations" type="button"><?php esc_html_e( 'Submit', 'easy-reservations' ); ?></button>
			</div>
		</div>
	</div>
</div>
