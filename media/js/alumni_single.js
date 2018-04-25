$(document).ready(function () {
	
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				$('#success_modal').modal('show');

				setTimeout(function (){
					var redirect_url = val.redirect_url;
					window.location = redirect_url;
				}, 2000);
			}
			else {
				$("#errors_list").html("");

				for (i = 0 ; i < val.errors.length ; i++) {
					$("#errors_list").append("<li>"+val.errors[i]+"</li>");
				}

				$('#error_modal').modal('show');
			}
		});
	
		return false;
	});
	
	$('#data_table').DataTable({
		"order": [0, 'desc']
	});
	
	$('.async_reload').click(function () {
		var url = $(this).attr('href');
		
		$.get(url, {}, function (val) {
			if (val.status == "success") {
				$('#success_modal').modal('show');

				setTimeout(function (){
					var redirect_url = val.redirect_url;
					window.location = redirect_url;
				}, 2000);
			}
			else {	
				$("#errors_list").html("");

				for (i = 0 ; i < val.errors.length ; i++) {
					$("#errors_list").append("<li>"+val.errors[i]+"</li>");
				}

				$('#error_modal').modal('show');
			}
		});
		
		return false;
	});
});
