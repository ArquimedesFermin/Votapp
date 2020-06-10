function checkUsername() {
	var username = $('#username').val();
	if(username.length > 0) {
		$.ajax({
			url: global_base_url + "register/check_username",
			type: "get",
			data: {
				"username" : username
			},
			success: function(msg) {
				$('#username_check').html(msg);
			}
		});
	} else {
		$('#username_check').html('');
	}
}

function checkDNI() {
	var dni = $('#dni').val();
	if(dni.length > 0) {
		$.ajax({
			url: global_base_url + "register/check_dni",
			type: "get",
			data: {
				"dni" : dni
			},
			success: function(msg) {
				$('#dni_check').html(msg);
			}
		});
	} else {
		$('#dni_check').html('');
	}
}

$(document).ready(function() {
	$('#username').change(function() {
		checkUsername();
	});
	
		$('#dni').change(function() {
		checkDNI();
	});
});