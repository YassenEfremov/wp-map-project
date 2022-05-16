let number_of_coords;

jQuery(document).ready(function($) {
	if(sessionStorage.getItem("number_of_coords") != null) {
		$('#number-of-coords').val(parseInt(sessionStorage.getItem("number_of_coords")));
	}
	$(document).on("submit", "#coords-form", function(e) {
		e.preventDefault();
		number_of_coords = $("#number-of-coords").val();
		sessionStorage.setItem("number_of_coords", number_of_coords);
		$.ajax({
			type: "POST",
			url: ajax_object.ajax_url,
			data: {
				action: "generate_new_coordinates",
				number_of_coords: number_of_coords
			},
			success: function(response) {
				location.reload();		// This reload would not have been necessary if the map supported AJAX
				console.log(response);
			}
		});
	});
});
