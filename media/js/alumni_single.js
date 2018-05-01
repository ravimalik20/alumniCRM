$(document).ready(function () {
	
	var table_notes = $('#data_table_notes').DataTable({
		"order": [0, 'desc']
	});
	
	var table_work = $('#data_table_work').DataTable();
	
	$("asdsad").submit(function () {
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
	
	$("#user_token_form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				$("#token_field").
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
	
	$("#work_form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				table_work.row.add([
					val.data.date_end + " - " + val.data.date_end,
					val.data.title,
					"<a href='"+ val.delete_url +"' class='btn btn-danger delete_work'><i class='fa fa-trash'></i></a>"
				]).draw('full-reset');
				
				$('html,body').animate({
					scrollTop: $("#work_form").offset().top
				}, 'slow');
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
	
	$("#add_note").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				table_work.row.add([
					val.data.created_at,
					val.data.note,
					val.created_by,
					"<a href='"+ val.delete_url +"' class='btn btn-danger delete_note'><i class='fa fa-trash'></i></a>"
				]).draw('full-reset');
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
	
	$("body").on("click", ".delete_work", function () {
		var url = $(this).attr('href');
		var self = $(this);
		
		$.get(url, {}, function (val) {
			if (val.status == "success") {
				table_work.row( self.parents('tr') ).remove().draw();
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
	
	$("body").on("click", ".delete_note", function () {
		var url = $(this).attr('href');
		var self = $(this);
		
		$.get(url, {}, function (val) {
			if (val.status == "success") {
				table_notes.row( self.parents('tr') ).remove().draw();
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
