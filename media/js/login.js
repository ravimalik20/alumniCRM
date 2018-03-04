$(document).ready(function () {
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.result == "success") {
				document.cookie = "token="+val.token;

				window.location = "/dashboard";
			}
			else	
				alert("Invalid credentials.");
		});
	
		return false;
	});
});
