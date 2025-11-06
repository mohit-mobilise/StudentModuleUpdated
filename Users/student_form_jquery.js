$(document).ready(function(){

	$(":text").on('keyup',function(){
		var string = $(this).val();
		var trim_value = string.replace(/[&\/\\#,+()$~%'":*?<>{}]/g, '');
		$(this).val(trim_value);

	});

	$('#f88').on('keyup',function(){
		var pan = $(this).val();//father
		 if (pan.length > 10) {
			var count = pan.length - 10;
			var trim_value = pan.toString().substr(0, pan.toString().length - count);
			     swal("Father's detail","Please enter proper Pan Card No.",'error');
			$(this).val(trim_value);
			console.log(pan.length);
			return false;
		}
		else
		{
			return true;
		}
	});
	
	$('#f88').focusout(function(){
      	var pan = $(this).val();
      if(pan.length < 10)
        {
          
          swal("Father's detail","Please enter proper Pan Card No.",'error');
          return false;
         
        }
      else
        {
          return true;
         
				}
    });


$('#f89').on('keyup',function(){
		var pan = $(this).val();//mother
		 if (pan.length > 10) {
			var count = pan.length - 10;
			var trim_value = pan.toString().substr(0, pan.toString().length - count);
			  swal("Mother's detail","Please enter proper Pan Card No.",'error');
			$(this).val(trim_value);
			console.log(pan.length);
			return false;
		}
		else
		{
			return true;
		}
	});

        	$('#f89').focusout(function(){
      	var pan = $(this).val();
      if(pan.length < 10)
        {
          
          swal("Mother's detail","Please enter proper Pan Card No.",'error');
          return false;
         
        }
      else
        {
          return true;
         
				}
    });
        
//occupation homaker

	$('#f25').on('change', function(){
      	var occupation = $(this).val();
      if(occupation == 'Homemaker')
        {
          $('#f26').attr('readonly',true);
          $('#f28').attr('readonly',true);
          $('#f29').attr('readonly',true);
        }
      else
        {
           $('#f26').attr('readonly',false);
          $('#f28').attr('readonly',false);
          $('#f29').attr('readonly',false);
				}
    });

	$('#f37').on('change', function(){
      	var occupation = $(this).val();
      if(occupation == 'Homemaker')
        {
          $('#f38').attr('readonly',true);
          $('#f40').attr('readonly',true);
          $('#f41').attr('readonly',true);
        }
      else
        {
           $('#f38').attr('readonly',false);
          $('#f40').attr('readonly',false);
          $('#f41').attr('readonly',false);
				}
    });





//family almuni validation
 $('#f53').on('change',function(){
    var is_father_almuni  = $(this).val();
  if(is_father_almuni == 'Yes')
    {
       $('#f63').prop('disabled',false);
      $('#f64').attr('readonly',false);
      $('#f65').prop('disabled',false);
     
    }else if(is_father_almuni == 'No')
      {
          $('#f63').prop('disabled',true);
      $('#f64').attr('readonly',true);
      $('#f65').prop('disabled',true);
        
        
      }
  });
  
  
  $('#f54').on('change',function(){
    var is_mother_almuni  = $(this).val();
  if(is_mother_almuni == 'Yes')
    {
       $('#f66').prop('disabled',false);
      $('#f67').attr('readonly',false);
      $('#f68').prop('disabled',false);
     
    }else if(is_mother_almuni == 'No')
      {
          $('#f66').prop('disabled',true);
      $('#f67').attr('readonly',true);
      $('#f68').prop('disabled',true);
        
        
      }
  });

//family almuni validation ends


//sibling detail validation 

$('#f52').on('change',function(){
    var sibling_status  = $(this).val();
  if(sibling_status == 'Yes')
    {
       $('#f56').attr('readonly',false);
      $('#f58').attr('readonly',false);
      $('#f60').prop('disabled',false);
     
    }else if(sibling_status == 'No')
      {
           $('#f56').attr('readonly',true);
      $('#f58').attr('readonly',true);
      $('#f60').prop('disabled',true);
        
        
      }
  });

//sibling detail validation ends here

	// student detail 

	$('#f16').on('keyup',function(){
		var std_addhar_no = $(this).val();
		if (isNaN(std_addhar_no)) {
			swal("Student's detail",std_addhar_no + " is not a number please enter proper Aadhar No.",'error');
			$(this).val('');
			return false;
			
		}else if (std_addhar_no.length > 12) {
			var count = std_addhar_no.length - 12;
			var trim_value = std_addhar_no.toString().substr(0, std_addhar_no.toString().length - count);
			var trim_value2 = trim_value.replace(/[ ]/g, '');
		       
			swal('Student Aadhar No.','You can enter only 12 digits','error');
			$(this).val(trim_value2);
			console.log(std_addhar_no.length);
			return false;
		}
		else
		{
			return true;
		}
	});
	
	$('#f84').prop('disabled',true);
	$('#f84').val('');
	$('#f13').on('change', function(){
      	var transport = $(this).val();
      if(transport == 'Bus')
        {
          $('#f84').prop('disabled',false);
         
        }
      else
        {
            	$('#f84').val('');
           
         $('#f84').prop('disabled',true);
				}
    });

	// student detail

	//****************father detail***************

	// aadhar validate
	$('#f31').on('keyup',function(){
		var father_aadhar = $(this).val();
		if (isNaN(father_aadhar)) {
			swal("Father's detail",father_aadhar + " is not a number please enter proper Aadhar No.",'error');
			$(this).val('');
			return false;
			
		}else if (father_aadhar.length > 12) {
			var count = father_aadhar.length - 12;
			var trim_value = father_aadhar.toString().substr(0, father_aadhar.toString().length - count);
			swal("Father's detail",'You can enter only 12 digits','error');
			$(this).val(trim_value);
			console.log(father_aadhar.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});
	// end aadhar validate

	//contact validate
	$('#f30').on('keyup',function(){
		var father_contact = $(this).val();
		if (isNaN(father_contact)) {
			swal("Father's detail",father_contact + " is not a Mobile number please enter proper Mobile No.",'error');
			$(this).val('');
			return false;
			
		}else if (father_contact.length > 10) {
			var count = father_contact.length - 10;
			var trim_value = father_contact.toString().substr(0, father_contact.toString().length - count);
			swal("Father's detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(father_contact.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});
	//end contact validate

	// ***********************father detail************************



	//******************mother detail***************


	// aadhar validate
	$('#f43').on('keyup',function(){
		var mother_aadhar = $(this).val();
		if (isNaN(mother_aadhar)) {
			swal("Mother's detail",mother_aadhar + " is not a number please enter proper Aadhar No.",'error');
			$(this).val('');
			
		}else if (mother_aadhar.length > 12) {
			var count = mother_aadhar.length - 12;
			var trim_value = mother_aadhar.toString().substr(0, mother_aadhar.toString().length - count);
			swal("Mother's detail",'You can enter only 12 digits','error');
			$(this).val(trim_value);
			console.log(mother_aadhar.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});
	// end aadhar validate

	//contact validate
	$('#f42').on('keyup',function(){
		var mother_contact = $(this).val();
		if (isNaN(mother_contact)) {
			swal("Mother's detail",mother_contact + " is not a Mobile number please enter proper Mobile No.",'error');
			$(this).val('');
			
		}else if (mother_contact.length > 10) {
			var count = mother_contact.length - 10;
			var trim_value = mother_contact.toString().substr(0, mother_contact.toString().length - count);
			swal("Mother's detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(mother_contact.length);
			
		}
		else
		{
			return true;
		}
	});
	//end contact validate


	//*******************mother detail**********



	//**************Guardian detail****************

	//contact validate
	$('#f51').on('keyup',function(){
		var guardian_contact = $(this).val();
		if (isNaN(guardian_contact)) {
			swal("Guardian's detail",guardian_contact + " is not a Mobile number please enter proper Mobile No.",'error');
			$(this).val('');
			
		}else if (guardian_contact.length > 10) {
			var count = guardian_contact.length - 10;
			var trim_value = guardian_contact.toString().substr(0, guardian_contact.toString().length - count);
			swal("Guardian's detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(guardian_contact.length);
			
		}
		else
		{
			return true;
		}
	});
	//end contact validate


	//**************Guardian detail****************



	//**** FAMILY ALUMUNI DETAIL**********

	//father passout year validate
	$('#f64').on('keyup',function(){
		var father_passout_year = $(this).val();
		if (isNaN(father_passout_year)) {
			swal("Family alumni detail",father_passout_year + " is not a Year please enter proper Year",'error');
			$(this).val('');
			
		}else if (father_passout_year.length > 4) {
			var count = father_passout_year.length - 4;
			var trim_value = father_passout_year.toString().substr(0, father_passout_year.toString().length - count);
			swal("Family almuni detail",'You can enter only 4 digits','error');
			$(this).val(trim_value);
			console.log(father_passout_year.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end  father passout year validate


	//mother passout year validate
	$('#f67').on('keyup',function(){
		var mother_passout_year = $(this).val();
		if (isNaN(mother_passout_year)) {
			swal("Family alumni detail",mother_passout_year + " is not a Year please enter proper Year",'error');
			$(this).val('');
			return false;
			
		}else if (mother_passout_year.length > 4) {
			var count = mother_passout_year.length - 4;
			var trim_value = mother_passout_year.toString().substr(0, mother_passout_year.toString().length - count);
			swal("Family alumni detail",'You can enter only 4 digits','error');
			$(this).val(trim_value);
			console.log(mother_passout_year.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end mother  passout year validate

	//**** FAMILY ALUMUNI DETAIL**********


	//**********contact detail************



	//home landline no validate
	$('#f69').on('keyup',function(){
		var home_no = $(this).val();
		if (isNaN(home_no)) {
			swal("Contact detail",home_no + " is not a Mobile no. please enter proper Mobile no.",'error');
			$(this).val('');
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end home landline no validate


	//Father mobile no. validate
	$('#f70').on('keyup',function(){
		var father_mobile_no = $(this).val();
		if (isNaN(father_mobile_no)) {
			swal("Father's detail",father_mobile_no + " is not a Mobile no. please enter proper Mobile no.",'error');
			$(this).val('');
			return false;
			
		}else if (father_mobile_no.length > 10) {
			var count = father_mobile_no.length - 10;
			var trim_value = father_mobile_no.toString().substr(0, father_mobile_no.toString().length - count);
			swal("Father's detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(father_mobile_no.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end Father mobile no. validate


	//Mother mobile no. validate
	$('#f71').on('keyup',function(){
		var mother_mobile_no = $(this).val();
		if (isNaN(mother_mobile_no)) {
			swal("Mother's detail",mother_mobile_no + " is not a Mobile no. please enter proper Mobile no.",'error');
			$(this).val('');
			return false;
			
		}else if (mother_mobile_no.length > 10) {
			var count = mother_mobile_no.length - 10;
			var trim_value = mother_mobile_no.toString().substr(0, mother_mobile_no.toString().length - count);
			swal("Mother's detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(mother_mobile_no.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end mother mobile no. validate


	//Emergency mobile no. validate
	$('#f72').on('keyup',function(){
		var emergency_mobile_no = $(this).val();
		if (isNaN(emergency_mobile_no)) {
			swal("Contact detail",emergency_mobile_no + " is not a Mobile no. please enter proper Mobile no.",'error');
			$(this).val('');
			return false;
			
		}else if (emergency_mobile_no.length > 10) {
			var count = emergency_mobile_no.length - 10;
			var trim_value = emergency_mobile_no.toString().substr(0, emergency_mobile_no.toString().length - count);
			swal("Contact detail",'You can enter only 10 digits','error');
			$(this).val(trim_value);
			console.log(emergency_mobile_no.length);
			return false;
			
		}
		else
		{
			return true;
		}
	});

	//end emergency mobile no. validate


	//**********contact detail************
});


var father_email = document.getElementById('f73');
var mother_email = document.getElementById('f75');
father_email.addEventListener("focusout", function(){
    var validate = father_email.value;
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (reg.test(validate) == false) 
        {
            swal("Father's email",'Invalid Email Address','error');
            return false;
        }

        return true;
});


mother_email.addEventListener("focusout", function(){
    var validate = mother_email.value;
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (reg.test(validate) == false) 
        {
            swal("Mother's email",'Invalid Email Address','error');
            return false;
        }

        return true;
});


function validate_std_form()
{
	
			var student_photo = document.getElementById('student_photo').value;
			var mother_photo = document.getElementById('mother_photo').value;
			var father_photo = document.getElementById('father_photo').value;
			 var guardian_photo = document.getElementById('guardian_photo').value;
			var driver_photo = document.getElementById('driver_photo').value;
			// var escort_photo = document.getElementById('escort_photo').value;
			
			// var guardian_mobile_no = document.getElementById('f51').value;
			
			
			
			// ***end mother detail***
			 if (student_photo == "") {
				swal("Document upload",'Please Upload Student photo','error');
				return false;
			}
			else if (mother_photo == "") {
				swal("Document upload",'Please Upload Mother photo','error');
				return false;
			}
			else if (father_photo == "") {
				swal("Document upload",'Please Upload Father photo','error');
				return false;
			}
			else if (guardian_photo == "") {
				toastr.warning('Please Upload Guardian photo', 'Validation Error');
				return false;
			}
			 //else if (driver_photo == "") {
			//	alert('Please Upload Driver photo');
			//	return false;
			 //}
			// else if (escort_photo == "") {
			// 	alert('Please Upload Escort photo');
			// 	return false;
			// }
			
			
			else
			{
				return true;
			}
}