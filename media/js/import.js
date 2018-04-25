$(document).ready(function () {
	$("form").submit(function () {
		var data = $(this).serializeArray();
		var url = $(this).attr("action");
	
		$.post(url, data, function (val) {
			if (val.status == "success") {
				window.location = "/alumni";
			}
			else	
				$("#error_modal").modal("show");
		});
	
		return false;
	});
	
	$("#spinner").hide();
	
	Dropzone.options.filedrop = {
	  init: function () {
		this.on("addedfile",function () {
			$("#spinner").show();
		});
	  
		this.on("error", function () {
			$("#spinner").hide();
		});
	  
		this.on("complete", function (file) {
		  if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
			$("#spinner").hide();
		  }
		});
	  }
	};
});
