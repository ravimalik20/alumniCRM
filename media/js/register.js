$(document).ready(function () {
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				var redirect_url = val.redirect_url

				window.location = redirect_url;
			}
			else	
				alert("Invalid credentials.");
		});
	
		return false;
	});
});
