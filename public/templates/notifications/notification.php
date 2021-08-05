<button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>

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
	<div id="liveToast" class="ersrv-notification toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
		<div class="toast-header bg-transparent">
		<span class="fa fa-exclamation-circle mr-2"></span>
			<strong class="mr-auto">Bootstrap</strong>
			<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="toast-body">
			Hello, world! This is a toast message. <a href="javascript:void(0);">Go here</a>
		</div>
	</div>
</div>
