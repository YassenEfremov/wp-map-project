jQuery(document).ready(function($) {
	$("#gen-new-coords").click(function() {
		$.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			data: {
				action: 'generate_new_coordinates'
			},
			success: function(response) {
				location.reload();		// This reload would not have been necessary if the map supported AJAX
				console.log(response);
			}
		});
	});
});
