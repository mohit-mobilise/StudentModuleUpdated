<?php

session_start();
include '../connection.php';
include '../AppConf.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

$sadmission = $_SESSION['userid'] ?? '';

$check = mysqli_query($Con, "SELECT `sadmission` FROM `student_master` WHERE `sadmission` = '".mysqli_real_escape_string($Con, $sadmission)."'");
$count = mysqli_num_rows($check);

if ($count < 1 ) {
	echo '<script>
	toastr.warning("You have been Logged out ", "Session Expired");
	setTimeout(function() { window.location.href = "Login.php"; }, 1500);
	</script>';
	exit;
}



?>
<?php
$currentdate=date("d-m-Y");
?>
<script language="javascript">

var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png", ".pdf"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                toastr.error("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "), "Invalid File");
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
function upperCaseF(a)
{
    setTimeout(function()
    {
        a.value = a.value.toUpperCase();
    }, 1);

 
}


// function ValidateSubmit()
// {
	
// 	var totalRecords = document.getElementById("totalrec").value;
	
// 	for(i=0;i<totalRecords;i++)
// 	{
		
// 		if(document.getElementById("txtFieldManStatus_" + i).value== 1 )
// 		{
// 			if(document.getElementById("txtFieldValue_"+i).value =="")
// 			{
// 				alert("Please check the value in " + document.getElementById("txtFieldName_" + i).value + " which is mandatory");
// 				document.getElementById("txtFieldValue_"+i).style.borderColor="red";

// 				return;
// 			}
			
// 		}		
// 	}
	

// 	for (i=0;i<totalRecords;i++)
// 	{	
		
// 			if(document.getElementById("txtFieldName_"+i).value =="Sibling Studying in School")
// 			{
// 				SiblingCounter=i;
// 				//alert(SiblingCounter);
// 			}

// 			if(document.getElementById("txtFieldName_"+i).value =="Brother / Sister Adm No")
// 			{
// 				BroSisAdmnoCounter=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Brother / Sister Name")
// 			{
// 				BroSisNameCounter=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Brother / Sister Class")
// 			{
// 				BroSisClassCounter=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Age As On")
// 			{
// 				AgeAsOnCount=i;
// 				//document.getElementById("txtFieldValue_"+i).value="Please Wait";
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="DOB")
// 			{
// 				DOBCount=i;
// 				//document.getElementById("txtFieldValue_"+i).value="Please Wait";
// 			}
			
// 			if(document.getElementById("txtFieldName_"+i).value =="Is Mother DPS Alumni")
// 			{
// 				MotherAlumniCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Mother Passout Year")
// 			{
// 				MotherPassoutYearCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Mother Passout Class")
// 			{
// 				MotherPassoutClassCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Mother School Branch")
// 			{
// 				MotherSchoolBranchCount=i;
// 			}
// 			///
// 			if(document.getElementById("txtFieldName_"+i).value =="Is Father DPS Alumni")
// 			{
// 				FatherAlumniCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Father Passout Year")
// 			{
// 				FatherPassoutYearCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Father Passout Class")
// 			{
// 				FatherPassoutClassCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Father School Branch")
// 			{
// 				FatherSchoolBranchCount=i;
// 			}
// 			if(document.getElementById("txtFieldName_"+i).value =="Class Applied For")
// 			{
// 				ClassAppliedForCount=i;
// 			}
// 	}
// 		//alert(MotherSchoolBranchCount);
// 		//alert(SiblingCounter);
// 		//alert(BroSisAdmnoCounter);
// 		if(document.getElementById("txtFieldValue_"+DOBCount).value=="")
// 		{
// 			alert("Date of Birth is mandatory!");
// 			return;
// 		}
// 		if(document.getElementById("txtFieldValue_"+AgeAsOnCount).value=="")
// 		{
// 			alert("Please enter DOB and click in 'As as On tex box' for calculating your age on 1st April 2018!");
// 			return;
// 		}
// 		if(document.getElementById("txtFieldValue_"+ClassAppliedForCount).value=="Nursery")
// 		{
// 			AgeAsOn=document.getElementById("txtFieldValue_"+AgeAsOnCount).value;
// 			AgeAsOn=AgeAsOn.replace(/years/g,"");
// 			AgeAsOn=AgeAsOn.replace(/month/g,"");
// 			AgeAsOn=AgeAsOn.replace(/days/g,"");
// 			arr_row=AgeAsOn.split(",");
// 			AgeYear=parseInt(arr_row[0]);
// 			AgeMonth=parseInt(arr_row[1]);
// 			AgeDays=parseInt(arr_row[2]);
// 			//alert(AgeYear);
// 			if(AgeYear<3 || AgeYear>3)
// 			{
// 				alert("For Class Nursery Age should be 3 Year to 4 Years.");
// 				return;
// 			}
// 		}
// 		if(document.getElementById("txtFieldValue_"+ClassAppliedForCount).value=="Prep")
// 		{
// 			AgeAsOn=document.getElementById("txtFieldValue_"+AgeAsOnCount).value;
// 			AgeAsOn=AgeAsOn.replace(/years/g,"");
// 			AgeAsOn=AgeAsOn.replace(/month/g,"");
// 			AgeAsOn=AgeAsOn.replace(/days/g,"");
// 			arr_row=AgeAsOn.split(",");
// 			AgeYear=parseInt(arr_row[0]);
// 			AgeMonth=parseInt(arr_row[1]);
// 			AgeDays=parseInt(arr_row[2]);
// 			//alert(AgeYear);
// 			if(AgeYear<4 || AgeYear>4)
// 			{
// 				alert("For Class Prep Age should be 4 Year to 5 Years.");
// 				return;
// 			}
// 		}
		
// 		//return;
// 		//alert(SiblingCounter);
// 		if(document.getElementById("txtFieldValue_"+MotherAlumniCount).value=="Yes")
// 		{
// 			if(document.getElementById("txtFieldValue_"+MotherPassoutYearCount).value=="" || document.getElementById("txtFieldValue_"+MotherPassoutClassCount).value=="" || document.getElementById("txtFieldValue_"+MotherSchoolBranchCount).value=="")
// 			{
// 				alert("Please fill mother alumni detail!");
// 				return;
// 			}
			
// 		}
// 		else
// 		{
// 			if(document.getElementById("txtFieldValue_"+MotherPassoutYearCount).value !="" || document.getElementById("txtFieldValue_"+MotherPassoutClassCount).value !="" || document.getElementById("txtFieldValue_"+MotherSchoolBranchCount).value !="")
// 			{
// 				alert("Please select mother alumni category as Yes!");
// 				return;
// 			}
// 		}
		
// 		if(document.getElementById("txtFieldValue_"+FatherAlumniCount).value=="Yes")
// 		{
// 			if(document.getElementById("txtFieldValue_"+FatherPassoutYearCount).value=="" || document.getElementById("txtFieldValue_"+FatherPassoutClassCount).value=="" || document.getElementById("txtFieldValue_"+FatherSchoolBranchCount).value=="")
// 			{
// 				alert("Please fill father alumni detail!");
// 				return;
// 			}
			
// 		}
// 		else
// 		{
// 			if(document.getElementById("txtFieldValue_"+FatherPassoutYearCount).value !="" || document.getElementById("txtFieldValue_"+FatherPassoutClassCount).value !="" || document.getElementById("txtFieldValue_"+FatherSchoolBranchCount).value !="")
// 			{
// 				alert("Please select father alumni category as Yes!");
// 				//document.getElementById("txtFieldValue_"+FatherAlumniCount).focus();
// 				return;
// 			}
// 		}	
// 		//alert(SiblingCounter);
// 		if(document.getElementById("txtFieldValue_"+SiblingCounter).value=="Yes")
// 			{
// 					/*
// 			     	document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).disabled=false;
// 			     	document.getElementById("txtFieldValue_"+BroSisNameCounter).disabled=false;
// 			     	document.getElementById("txtFieldValue_"+BroSisClassCounter).disabled=false;
// 			     	*/
// 			     	if(document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).value=="" || document.getElementById("txtFieldValue_"+BroSisNameCounter).value=="" || document.getElementById("txtFieldValue_"+BroSisClassCounter).value=="")
// 			     	{
// 			     		alert("In case of Sibling Yes, Brother  Sister details are mandatory!");
// 			     		return;
// 			     	}
// 			}
// 			else
// 			{
// 					/*
// 					document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).disabled=true;
// 			     	document.getElementById("txtFieldValue_"+BroSisNameCounter).disabled=true;
// 			     	document.getElementById("txtFieldValue_"+BroSisClassCounter).disabled=true;
// 			     	*/
// 			     	if(document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).value!="" || document.getElementById("txtFieldValue_"+BroSisNameCounter).value!="" || document.getElementById("txtFieldValue_"+BroSisClassCounter).value !="")
// 			     	{
// 			     		alert("Please select Sibling Category as Yes!");
// 			     		return;
// 			     	}
// 			}

// 			var student_addhar_no = document.getElementById('txtFieldValue_14').value;
// 			var father_addhar_no = document.getElementById('txtFieldValue_29').value;
// 			var mother_addhar_no = document.getElementById('txtFieldValue_41').value;
// 			var father_mobile_no = document.getElementById('txtFieldValue_28').value;
// 			var father_mobile_no2 = document.getElementById('txtFieldValue_68').value;
// 			var mother_mobile_no = document.getElementById('txtFieldValue_40').value;
// 			var mother_mobile_no2 = document.getElementById('txtFieldValue_69').value;
// 			var emergancy_mobile_no = document.getElementById('txtFieldValue_70').value;
// 			var student_photo = document.getElementById('student_photo').value;
// 			var mother_photo = document.getElementById('mother_photo').value;
// 			var father_photo = document.getElementById('father_photo').value;
// 			var guardian_photo = document.getElementById('guardian_photo').value;
// 			var driver_photo = document.getElementById('driver_photo').value;
// 			var escort_photo = document.getElementById('escort_photo').value;
			
// 			// var guardian_mobile_no = document.getElementById('txtFieldValue_49').value;
			
// 			if (student_photo == "") {
// 				alert('Please Upload Student photo');
// 				return;
// 			}
// 			if (mother_photo == "") {
// 				alert('Please Upload Mother photo');
// 				return;
// 			}
// 			if (father_photo == "") {
// 				alert('Please Upload Father photo');
// 				return;
// 			}
// 			if (guardian_photo == "") {
// 				alert('Please Upload Guardian photo');
// 				return;
// 			}
// 			if (driver_photo == "") {
// 				alert('Please Upload Driver photo');
// 				return;
// 			}
// 			if (escort_photo == "") {
// 				alert('Please Upload Escort photo');
// 				return;
// 			}
			
// 			if (student_addhar_no.length < 12) {
// 				alert('Please enter proper  Student aadhar card no. And Should be minimum 12 digits');
// 				return;
// 			}
// 			if (father_addhar_no.length < 12 ) {
// 				alert('Please enter proper  Father aadhar card no. And Should be minimum 12 digits');
// 				return;
// 			}
// 			if (mother_addhar_no.length < 12 ) {
// 				alert('Please enter proper  Mother  aadhar card no. And Should be minimum 12 digits');
// 				return;
// 			}
// 			if (father_mobile_no.length < 10  || father_mobile_no2.length < 10) {
// 				alert('Please enter proper  Father contact no. And Should be minimum 10 digits');
// 				return;
// 			}

// 			if (mother_mobile_no.length < 10 || mother_mobile_no2.length < 10) {
// 				alert('Please enter proper  Mother contact no. And Should be minimum 10 digits');
// 				return;
// 			}
// 			if (emergancy_mobile_no.length < 10 ) {
// 				alert('Please enter proper  Emergency contact no. And Should be minimum 10 digits');
// 				return;
// 			}
			
// 			// if (guardian_mobile_no.length < 10 ) {
// 			// 	alert('Please enter proper  Guardian contact no. And Should be minimum 10 digits');
// 			// 	return;
// 			// }			

// 	document.getElementById("frmStudentReg").submit();
// }
function CalculateAgeInQC() 
{
	var totalRecords = document.getElementById("totalrec").value;
	
	//totalRecords=10;
	//alert(totalRecords);
	for (i=0;i<totalRecords;i++)
	{	
		if(document.getElementById("txtFieldManStatus_" + i).value=="1")
		{
			if(document.getElementById("txtFieldName_"+i).value =="DOB")
			{
				DobCounter=i;
				if(document.getElementById("txtFieldValue_"+i).value=="")
			     {
			     	alert("Please enter Date of Birth!");
			     	return;
			     }
			}
			
			
			if(document.getElementById("txtFieldName_"+i).value =="Age As On")
			{
				AgeAsOnCount=i;
				document.getElementById("txtFieldValue_"+i).value="Please Wait";
			}		
		}		
	}
		/*
		if(document.getElementById("txtFieldValue_"+SiblingCounter).value=="Yes")
			{
			     	document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).disabled=false;
			     	document.getElementById("txtFieldValue_"+BroSisNameCounter).disabled=false;
			     	document.getElementById("txtFieldValue_"+BroSisClassCounter).disabled=false;
			}
			else
			{
					document.getElementById("txtFieldValue_"+BroSisAdmnoCounter).disabled=true;
			     	document.getElementById("txtFieldValue_"+BroSisNameCounter).disabled=true;
			     	document.getElementById("txtFieldValue_"+BroSisClassCounter).disabled=true;
			}
		*/
	//alert(AgeAsOnCount);

     //document.getElementById("txtFieldValue_4").value="Please Wait";
     try
		    {    
				// Firefox, Opera 8.0+, Safari    
				xmlHttp=new XMLHttpRequest();
			}
		  catch (e)
		    {    // Internet Explorer    
				try
			      {      
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				  }
			    catch (e)
			      {      
					  try
				        { 
							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
				      catch (e)
				        {        
							alert("Your browser does not support AJAX!");        
							return false;        
						}      
				  }    
			 } 
			 xmlHttp.onreadystatechange=function()
		      {
			      if(xmlHttp.readyState==4)
			        {
						var rows="";
			        	rows=new String(xmlHttp.responseText);
						document.getElementById("txtFieldValue_"+AgeAsOnCount).value=rows;
			        }
		      };

			var submiturl="CalculateAge.php?DateOfBirth=" + document.getElementById("txtFieldValue_"+DobCounter).value;
			xmlHttp.open("GET", submiturl,true);
			xmlHttp.send(null);
}
</script>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.7.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Information</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<!-- END PAGE LEVEL SCRIPTS -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/clockface/css/clockface.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>
<!-- BEGIN THEME STYLES -->
<link href="../../assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="croppie/croppie.css">
<!-- Toastr CSS -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-toastr/toastr.min.css">
<!-- Toastr Custom CSS (fixes display issues) -->
<link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style type="text/css">
	.id_card_photo > img
	{
		color: black;
		/*background-image:url('user.png');
		-webkit-background-image:url('user.png');
		-o-background-image:url('user.png');
		-moz-background-image:url('user.png');*/
		border: none;
		height: 80px;
		width: 80px; 
		 background-size: 100%; /* To fill the dimensions of container (button), or */
                         background-size: 80px auto; /* to specify dimensions explicitly */
		background-repeat: no-repeat;
	
</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-md page-header-fixed page-sidebar-closed-hide-logo ">
<!-- BEGIN HEADER -->
<!--<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
	 BEGIN HEADER INNER -->
	
	<!-- END HEADER INNER 
</div>-->
<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<!--<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<!--<div class="page-sidebar-wrapper">
		<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
		<!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
		<!---<div class="page-sidebar md-shadow-z-2-i  navbar-collapse collapse">
			<!-- BEGIN SIDEBAR MENU -->
			<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
			<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
			<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
			<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
			<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
			<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
			
			<!-- END SIDEBAR MENU 
		</div>
	</div>-->
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT
	<div class="page-content-wrapper"> -->
		<div class="page-content">
		<br>
			<p align="center">
<img src="../Admin/images/logo.png" alt="logo" class="logo-default"/ height="86" width="353" >

<br>
<br>
<b><?php echo $SchoolAddress; ?></b>
<br>

<b>Phone No: <?php echo $SchoolPhoneNo; ?></b>
<br>
<b>Email Id: <?php echo $SchoolEmailId; ?></b>

<br>
<h4 align="center"><b>INFORMATION FORM (Session <?php echo date('Y'); ?> - <?php echo date('y') + 1; ?>)</b></h4>
</p>
			
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN PAGE HEADER-->
			<!-- BEGIN PAGE HEAD -->
			<div class="page-head">
				<!-- BEGIN PAGE TITLE -->
					<div class="page-title">
			



				</div>
				<!-- END PAGE TITLE -->
				<!-- BEGIN PAGE TOOLBAR -->
				
				<!-- END PAGE TOOLBAR -->
			</div>
			<!-- END PAGE HEAD -->
			<!-- BEGIN PAGE BREADCRUMB -->
			<!---<ul class="page-breadcrumb breadcrumb">
				<li>
					<a href="index.html">Home</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Form Stuff</a>
					<i class="fa fa-circle"></i>
				</li>
				<li>
					<a href="#">Form Layouts</a>
				</li>
			</ul>-->
			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->

	<!-- Modal -->
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Upload <span id="photo_name"></span></h4>
      </div>
      <div class="modal-body">

        <div class="row">
	  		<div class="col-md-4 text-center">
				<div id="upload-image" style="height:400px;"></div>
	  		</div>
	  		<div class="col-md-4">
				<strong>Select Image:</strong>
				<br/>
				<input type="file" id="images">
				<input type ="hidden" name ="imgfor" id="imgfor" value ="">
				<br/>
				<button class="btn btn-success cropped_image" id="crop_image" type="button" data-name="">Upload Image</button>
<!-- <div style="width:100%;height:100px;"></div> -->
<p align="center" id="please_wait" style="display:none;"><img src="upload/ajax_loader.gif"><br><span>Please wait while your photo is being uploaded </span></p>
	  		</div>			
	  		<div class="col-md-4 crop_preview" align="center">
				<div id="upload-image-i"></div><br>
				<button type="button" class="btn green" id="close_modal">Submit & Upload</button>
	  		</div>
	  	</div> 
<div class="row">
                 <ul style="color:red">
<li>Passport size photo to be uploaded. DO NOT UPLOAD SELF CLICKED PHOTOS.</li>
<li>80% face coverage is required.</li>
<li>The photograph can be cropped and adjusted</li>
<li>Maximum Photo size allowed is : 256KB</li></ul>
                 </div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
        
      </div>
    </div>
  </div>
</div>

<!-- end of modal -->
	

			<div class="row">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom tabbable-noborder  tabbable-reversed">
						<ul class="nav nav-tabs">
							<?php
							$cnt=0;
							$rsTab=mysqli_query($Con, "SELECT distinct `field_category` from `form_fields` ORDER BY CAST(`category_priority` AS UNSIGNED INT)");
							while($rowT=mysqli_fetch_row($rsTab))
							{
								$TabName=$rowT[0];
								
							?>
							<li <?php if($cnt=="0") { ?>class="active" <?php } ?>>
								<a href="#tab_<?php echo $cnt;?>" data-toggle="tab">
								<?php echo $TabName;?> </a>
							</li>
							<?php
							$cnt=$cnt+1;
							}
							?>
							<li >
								<a href="#tab_note" data-toggle="tab">
								Declaration </a>
							</li>

							
						</ul>
						<form onsubmit="return validate_std_form();" method="post" name="frmStudentReg" id="frmStudentReg" action="new_submit_std_form2.php" class="form-horizontal" enctype="multipart/form-data">

			<input type="hidden" name="adm_no" id="adm_no" value="<?php echo $sadmission; ?>">
<?php 


$checks = mysqli_query($Con, "SELECT `sadmission` FROM `form_field_data` WHERE `sadmission` = '$sadmission'");
$counts = mysqli_num_rows($checks);
if($counts > 0)
{
$fetch_photo = mysqli_query($Con, "SELECT `ProfilePhoto`,`MotherPhoto`,`FatherPhoto`, `GuardianPhoto`, `DriverPhoto`,`escortPhoto` FROM `form_field_data` WHERE `sadmission`='$sadmission' ");
}
else
{
    $fetch_photo = mysqli_query($Con, "SELECT `ProfilePhoto`,`MotherPhoto`,`FatherPhoto`, `GuardianPhoto`, `DriverPhoto`,`escortPhoto` FROM `student_master` WHERE `sadmission`='$sadmission' ");
}

$data_fetch = mysqli_fetch_assoc($fetch_photo);

?>
	<input type="hidden" class="form-control" name="student_photo" id="student_photo" value="<?php echo $data_fetch['ProfilePhoto'];?>">
	<input type="hidden" class="form-control" name="mother_photo" id="mother_photo" value="<?php echo $data_fetch['MotherPhoto'];?>">
	<input type="hidden" class="form-control" name="father_photo" id="father_photo" value="<?php echo $data_fetch['FatherPhoto'];?>">
	<input type="hidden" class="form-control" name="guardian_photo" id="guardian_photo" value="<?php echo $data_fetch['GuardianPhoto'];?>">
	<input type="hidden" class="form-control" name="driver_photo" id="driver_photo" value="<?php echo $data_fetch['DriverPhoto'];?>">
	<input type="hidden" class="form-control" name="escort_photo" id="escort_photo" value="<?php echo $data_fetch['escortPhoto'];?>">
						<div class="tab-content">
							<?php
							mysqli_data_seek($rsTab, 0);
							$recno=0;
							$srno=0;
                                                        $total_row = '';
							while($rowT=mysqli_fetch_row($rsTab))
							{
                                                          $total_row = $srno;
								$TabName=$rowT[0];
							?>
							<div <?php if($recno=="0") { ?> class="tab-pane active" <?php } else {?> class="tab-pane" <?php } ?> id="tab_<?php echo $recno;?>">
								<div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gift"></i><?php echo $TabName;?>
										</div>
									
									</div>
									<div class="portlet-body form">
										<!-- BEGIN FORM-->
											<div class="form-body">
												<h3 class="form-section">  </h3>
												<div class="row">
												<?php 
													$rsTabField=mysqli_query($Con, "SELECT distinct `field_name`,`field_type`,`field_option_ref_id`,`field_read_status`,`field_mandatory_status`,`field_id`,`db_col_name`,`field_placeholder`,`field_id`,`field_status` from `form_fields` where `field_category`='$TabName' and `field_status`='1' AND  `student_master_info` = 1 ORDER BY CAST(`category_priority` AS UNSIGNED INT),CAST(`field_name_priority` AS UNSIGNED INT)");
													//$srno=0;
													while($rowF=mysqli_fetch_row($rsTabField))
													{
														$FieldName=$rowF[0];
														$FieldType=$rowF[1];
														$FieldOptionId=$rowF[2];
														$FieldReadStatus=$rowF[3];
														$FieldMandatoryStatus=$rowF[4];
														$FieldId=$rowF[5];
														$db_col_name=$rowF[6];
														$field_placeholder=$rowF[7];
														$field_id = $rowF[8];
														$field_status = $rowF[9];

														$check1 = mysqli_query($Con, "SELECT `sadmission` FROM `form_field_data` WHERE `sadmission` = '$sadmission'");
														$count1 = mysqli_num_rows($check1);

														if ($count1 > 0 ) {
															$rsValue=mysqli_query($Con, "SELECT  ".$db_col_name." from `form_field_data` where   ".$db_col_name."= ".$db_col_name." and `sadmission`='$sadmission' ");
														}
														else
														{
															$rsValue=mysqli_query($Con, "SELECT  ".$db_col_name." from `student_master` where   ".$db_col_name."= ".$db_col_name." and `sadmission`='$sadmission' ");
														}

														
														$rowValue=mysqli_fetch_row($rsValue);
														$DataValue=$rowValue[0];
												?>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-md-3">
															<?php echo $FieldName; if($FieldMandatoryStatus=="1") echo "*";?></label>
															<div class="col-md-9">
															<?php
															if($FieldType=="Date")
															{
															?>
															
															<div class="input-group input-medium date date-picker" data-date="" data-date-format="yyyy-mm-dd" data-date-viewmode="years">
															<input type="text"   name="txtFieldValue_<?php echo $srno;?>" id="<?php echo $field_id;?>"  class="form-control" readonly value="<?php echo htmlspecialchars($DataValue ?? '', ENT_QUOTES, 'UTF-8');?>">
															<span class="input-group-btn">
															<button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
															</span>
															</div>
															<?php
															}
															else if($FieldType=="text" || $FieldType=="file" )
															{
															?>
															
															<input type="<?php echo htmlspecialchars($FieldType ?? '', ENT_QUOTES, 'UTF-8');?>" class="form-control" name="txtFieldValue_<?php echo $srno;?>" id="<?php echo $field_id;?>" value="<?php echo htmlspecialchars($DataValue ?? '', ENT_QUOTES, 'UTF-8');?>"onchange="ValidateSingleInput(this);" placeholder="<?php echo htmlspecialchars($field_placeholder ?? '', ENT_QUOTES, 'UTF-8');?>" <?php if($FieldName=="Age As On") { ?> onclick="javascript:CalculateAgeInQC(<?php echo $srno;?>);"  <?php } ?>  <?php if($FieldType=="text"){?> onkeyup="upperCaseF(this)" <?php } ?> <?php if($FieldType=="text" && $FieldReadStatus=="Yes"){?> readonly="true" <?php } ?>  >
															
															<?php
															}
															else if($FieldType=="button" && $field_status == 1)
															{
															?>
															
															<button type="<?php echo htmlspecialchars($FieldType ?? '', ENT_QUOTES, 'UTF-8');?>" value="<?php echo htmlspecialchars($FieldName ?? '', ENT_QUOTES, 'UTF-8'); ?>"  class="btn  info_photo id_card_photo " name="txtFieldValue_<?php echo $srno;?>" id="<?php echo $field_id;?>" onchange="ValidateSingleInput(this);" placeholder="<?php echo htmlspecialchars($field_placeholder ?? '', ENT_QUOTES, 'UTF-8');?>" <?php if($FieldName=="Age As On") { ?> onclick="javascript:CalculateAgeInQC(<?php echo $srno;?>);"  <?php } ?>  <?php if($FieldType=="text"){?> onkeyup="upperCaseF(this)" <?php } ?> <?php if($FieldType=="text" && $FieldReadStatus=="Yes"){?> readonly="true" <?php } ?>  ><img src="../Admin/StudentManagement/StudentDocuments/<?php echo htmlspecialchars(basename($DataValue ?? ''), ENT_QUOTES, 'UTF-8');?>" alt="" id=""></button>
															
														<?php
															}
															
															else
															{


															?>
	
														
															<select name="txtFieldValue_<?php echo $srno;?>" id="<?php echo $field_id;?>"	class="form-control">
															<option value="">Select One</option>
															<?php 
															$rsOption=mysqli_query($Con, "SELECT `options`,`options_value`,`option_score` from `form_fields_option` where `ref_id`='$FieldOptionId' and `options_status`='1' ORDER BY CAST(`option_priority` AS UNSIGNED INT)");
															while($rowOP=mysqli_fetch_row($rsOption))
															{
																$OptionName=$rowOP[0];
																$OptionValue=$rowOP[1];
																$OptionScore=$rowOP[2];
															?>
															<option value="<?php echo htmlspecialchars($OptionValue ?? '', ENT_QUOTES, 'UTF-8');?>" <?php if($DataValue==$OptionValue){?> selected <?php  }
															?>><?php echo htmlspecialchars($OptionName ?? '', ENT_QUOTES, 'UTF-8');?></option>
															<?php
															}
															?>
															</select>

															<?php

															
															}
															?>
															</div>
														</div>
													</div>
												
													<?php
													$srno=$srno+1;
													}
													?>
												
													
													
												</div>
												
											</div>
											
										
										<!-- END FORM-->
									</div>
								</div>
								
							</div>
							<?php
							$recno=$recno+1;
							}
							?>
							<div class="tab-pane" id="tab_note">
								<div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gift"></i>Declaration
										</div>
										
									</div>
									<div class="portlet-body form">
									<div class="form-body">
										<p>
										Date :<?php echo $currentdate;?>
										
										</font></p>
										<div class="form-actions">
<div class="row">
 <div class="col-md-12">
   <p><strong>I hereby confirm that the information provided by me in this form true the best of my knowledge</strong></p>
<input type="hidden" value="<?php echo $total_row; ?>" name="total_record" id="total_record">
 </div>
</div>
														<div class="row">
															<div class="col-md-6">
																<div class="row">
																<input type="hidden" name="totalrec" id="totalrec" value="<?php echo $srno; ?>">
																	<div class="col-md-offset-12 col-md-12">
																		<button type="submit"  class="btn red">
																		Submit</button>
																		<!--<button type="button" class="btn default">
																		Cancel</button>-->
																	</div>
																</div>
															</div>
															<div class="col-md-6">
															</div>
														</div>
														</div>
										</div><!-- BEGIN FORM-->
										
										<!-- END FORM-->
									</div>
								</div>
								
							</div>
							
						</div>
						</form>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
	<!---</div>-->
	<!-- END CONTENT 
</div>-->
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="page-footer">
	<div class="page-footer-inner">
<b>		 Powered by | Mobilise App Lab LLP.</b>
	</div>
	<div class="scroll-to-top">
		<i class="icon-arrow-up"></i>
	</div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../../assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../../assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>

<script type="text/javascript" src="../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-markdown/lib/markdown.js"></script>


<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/form-samples.js"></script>

<script src="../../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/table-advanced.js"></script>
<script src="../../assets/admin/pages/scripts/form-validation.js"></script>

<script type="text/javascript" src="sweetalert.min.js"></script>
<script type="text/javascript" src="croppie/croppie.js"></script>
<script type="text/javascript" src="upload2.js"></script>
<script type="text/javascript" src="student_form_jquery.js"></script>
<!-- Toastr JS -->
<script src="../../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>


<script type="text/javascript">
	$(document).ready(function(){
		$("#f79").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="F";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});
$("#f80").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="M";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});
$("#f81").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="G";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});
$("#f82").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="D";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});
$("#f76").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="S";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});
$("#f83").click(function(){
$('#myModal').modal('show');
document.getElementById("imgfor").value="E";
var btn_val = $(this).val();
$('#photo_name').html(btn_val);
});




	var html = '';
  
  for(var i = 2; i<=14;i++)
    {
      html += '<option value="R'+ i +'">R'+ i +'</option>';
    }
  $('#f84').append(html);

	});

</script>

<!-- END PAGE LEVEL SCRIPTS -->
<script>
jQuery(document).ready(function() {    
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
   FormSamples.init();
   QuickSidebar.init(); // init quick sidebar
      FormValidation.init();

});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>