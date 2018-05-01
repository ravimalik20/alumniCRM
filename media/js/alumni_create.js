$(document).ready(function () {
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			$("div.form-group").removeClass("has-error");
			$("span.help-block").hide();
		
			if (val.status == "success") {
				$('#success_modal').modal('show');

				setTimeout(function () {
					var redirect_url = val.redirect_url;
					window.location = redirect_url;
				}, 2000);
			}
			else {
				for (e in val.errors) {
					error = val.errors[e];
					
					$("input[name="+error[0]+"]").parent("div.form-group").addClass("has-error");
					$("input[name="+error[0]+"]").parent("div.form-group").find("span.help-block").html(error[1]);
					$("input[name="+error[0]+"]").parent("div.form-group").find("span.help-block").show();
				}
			}
		});
	
		return false;
	});
});
