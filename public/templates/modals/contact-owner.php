<?php

/**
 * This file is used for templating the reservable item quick view modal.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/modals
 */

defined('ABSPATH') || exit; // Exit if accessed directly.
?>
<div id="ersrv-contact-owner-modal" class="ersrv-modal">
	<div class="ersrv-modal-content modal-content modal-lg m-auto p-3">
		<h3><?php esc_html_e('Contact Owner', 'easy-reservations'); ?></h3>
		<span class="ersrv-close-modal quick-close close">×</span>
		<div class="modal-body">
			<form method="post">
				<!-- <h3>Drop Us a Message</h3> -->
				<div class="row form-row">
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" name="txtName" class="form-control" placeholder="Your Name" value="" />
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="email" name="txtEmail" class="form-control" placeholder="Your Email" value="" />
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" name="txtPhone" class="form-control" placeholder="Your Phone Number" value="" />
						</div>
					</div>
					<div class="col-12 col-md-6">
						<div class="form-group">
							<input type="text" name="txtSubject" class="form-control" placeholder="Your Subject" value="" />
						</div>
					</div>
					<div class="col-12">
						<div class="form-group">
							<textarea name="txtMsg" class="form-control" placeholder="Your Message" style="width: 100%; height: 100px;"></textarea>
						</div>
						<div class="form-group text-right">
							<button class="btn btn-primary" type="submit">Send Message</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>