$(document).ready(function () {
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				window.location = "/alumni";
			}
			else	
				alert("Incorrect data.");
		});
	
		return false;
	});
});
