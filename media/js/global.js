$(document).ready(function () {
	$("#logout").click(function () {
		var href = $(this).attr("href");

		$.get(href, {}, function (val) {
			var redirect_url = val.redirect_url
			window.location = redirect_url;
		});

		return false;
	});
});
