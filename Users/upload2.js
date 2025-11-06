$(document).ready(function(){
	$("#close_modal").hide();
	$("#close_modal").click(function(){
		$("#myModal").modal('toggle');
                  $(this).hide();
	});
	

	


	$(".info_photo").click(function(){
 		$(".cr-image").attr('src', '');
		$("#upload-image-i img").attr('src', '');
		$("#images").val('');
		
	});


	$image_crop = $('#upload-image').croppie({
	enableExif: true,
	viewport: {
		width: 200,
		height: 200,
		type: 'square'
	},
	boundary: {
		width: 300,
		height: 300
	}
});
$('#images').on('change', function () {
	var reader = new FileReader();
	reader.onload = function (e) {
		$image_crop.croppie('bind', {
			url: e.target.result
		}).then(function(){
			console.log(Object.values($image_crop));
			console.log('jQuery bind complete');

		});			
	};
	reader.readAsDataURL(this.files[0]);
});
$('.cropped_image').on('click', function (ev) {


$('#please_wait').show();
	
       $("#upload-image-i img").attr('src', '');
	var   photo_name =	$(this).attr('data-name'); //define photo name like student / mother / father 
	var imguploadfor=document.getElementById("imgfor").value;
        var id = document.getElementById("adm_no").value;
      	
      	if (id == "") {
      		toastr.warning('You are logged out Please log in', 'Session Expired');
      		setTimeout(function() { window.location.href = "http://nkbpsdsis.mobilisesis.co.in/Users/index.php"; }, 1500);
      		return false;
      	}else
      	{
      		$image_crop.croppie('result', {
		type: 'canvas',
		size: 'viewport'
	}).then(function (response) {
		$.ajax({
			url: "upload2.php",
			type: "POST",
			data: {"image":response,'imguploadfor':imguploadfor,'adm':id},
			success: function (data) {
				html = '<img src="' + response + '" />';
				$("#upload-image-i").html(html);
				$('#please_wait').hide();
				var img = '../Admin/StudentManagement/StudentDocuments/' + data;
				if (imguploadfor == 'F') {
                                         $('#f79 img').attr('src','');
					var father  = $("#father_photo").val(data);
					toastr.success("Your Father photo has been uploaded ", "Success");
                                      		 $("#close_modal").show();
                                         		$('#f79').removeClass('btn-default');
                                         
 					 $('#f79 img').attr('src',img);

				}
				else if (imguploadfor == 'M') {
                                       $('#f80 img').attr('src','');
					var mother  = $("#mother_photo").val(data);

					toastr.success("Your Mother photo has been uploaded ", "Success");  
                                       		 $("#close_modal").show();
                                       		 $('#f80').removeClass('btn-default');
 					 $('#f80 img').attr('src',img);
                                      
				}
				else if (imguploadfor == 'S') {
 $('#f76 img ').attr('src','');
					var student  = $("#student_photo").val(data);
					toastr.success("Student photo has been uploaded ", "Success");
                                       		  $("#close_modal").show();
                                       		  $('#f76').removeClass('btn-default');
 					 $('#f76 img ').attr('src',img);
                                      
				}
				else if (imguploadfor == 'G') {
$('#f81 img ').attr('src','');
					var guardian  = $("#guardian_photo").val(data);
					toastr.success("Guardian photo has been uploaded ", "Success");
                                       		  $("#close_modal").show();
                                       		  $('#f81').removeClass('btn-default');
 					 $('#f81 img ').attr('src',img);
                                      
				}
				else if (imguploadfor == 'D') {
$('#f82 img').attr('src','');
					var driver  = $("#driver_photo").val(data);
					toastr.success("Driver photo has been uploaded ", "Success");
                                       		  $("#close_modal").show();
                                       		  $('#f82').removeClass('btn-default');
 					 $('#f82 img').attr('src',img);
                                      
				}
				else if (imguploadfor == 'E') {
$('#f83 img').attr('src','');
					var escort  = $("#escort_photo").val(data);
					toastr.success("Escort photo has been uploaded ", "Success");
                                       		  $("#close_modal").show();
                                       		  $('#f83').removeClass('btn-default');
 					 $('#f83 img').attr('src',img);
                                      
				}
				else{
					console.log("data  did not finalize");
				}
			}
		});
	});
      	}
	
});	

});//end of document
