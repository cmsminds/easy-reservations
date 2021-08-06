<?php
/**
 * This file is used for templating the notifications.
 *
 * @since 1.0.0
 * @package Easy_Reservations
 * @subpackage Easy_Reservations/public/templates/notifications
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
	<!-- 
		classes for Bg color
		Notice : bg-warning
		Error  : bg-danger
		success: bg-success

		for Icon
		Notice : <span class="fa fa-exclamation-circle mr-2"></span>
		Error  : <span class="fa fa-skull-crossbones mr-2"></span>
		success: <span class="fa fa-check-circle mr-2"></span>
	-->
	<div class="ersrv-notification toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
		<div class="toast-header bg-transparent">
			<span class="ersrv-notification-icon fa mr-2"></span>
			<strong class="ersrv-notification-heading mr-auto"></strong>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body ersrv-notification-message"></div>
	</div>
</div>
