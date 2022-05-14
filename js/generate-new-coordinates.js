// Doesn't work, but something similar may be used

jQuery(document).ready(function() {
	jQuery("#gen-new-coords").click(function () {
		console.log('The function is hooked up');
		jQuery.ajax({
			type: "POST",
			url: "/wp-admin/admin-ajax.php",
			data: {
				action: 'generate_new_coordinates',
				// add your parameters here
				message_id: $('#gen-new-coords').val()
			},
			success: function (output) {
				console.log(output);
			}
		});
	});
});
