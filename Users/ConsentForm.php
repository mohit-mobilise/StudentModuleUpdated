<?php
session_start();
include '../connection.php';
include '../AppConf.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentClass = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$sadmission = $_SESSION['userid'] ?? '';
	
	$rs=mysqli_query($Con, "select `sname`,`sclass`,`srollno`, `mode_of_transport` from `student_master` where `sadmission`='$sadmission'");
	$row=mysqli_fetch_row($rs);
	$sname=$row[0];
	$StudentClass=$row[1];
	$StudentRollNo=$row[2];
	$mode_of_transport=$row[3];
	
	if($sadmission == "")
	{
		echo "<br><br><center><b>Session Expired!<br>click <a href='https://erp.dpsfsie.org//'>here</a> to login again!";
		exit();
	}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title> Consent Form</title>
<link href="../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!-- Toastr CSS -->
<link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-toastr/toastr.min.css">
<!-- Toastr Custom CSS (fixes display issues) -->
<link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
</head>

<body>
<font face="Cambria">

</font>
<form method="POST" method ="post" action="">
<br><br>    
<table width="100%" style="border:none" >
	<tr>
		<td colspan="2" height="19" style="border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><img src="<?php echo $SchoolLogo; ?>" height="45px" width="300px"></font></td>
	</tr>
	<!--<tr>-->
	<!--	<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">-->
	<!--	<p align="center"><font face="Cambria"><b><?php //echo $SchoolAddress; ?></b></font></td>-->
	<!--</tr>-->
	<!--<tr>-->
	<!--	<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">-->
	<!--	<p align="center"><font face="Cambria"><b>Phone No: <?php //echo $SchoolPhoneNo; ?></b></font></td>-->
	<!--</tr>-->
	<!--<tr>-->
	<!--	<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">-->
	<!--	<p align="center"><font face="Cambria"><b>Email Id: <?php //echo $SchoolEmailId; ?></b></font></td>-->
	<!--</tr>-->
	<!--<tr>-->
	<!--	<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium">-->
	<!--	<p align="center">&nbsp;</td>-->
	<!--</tr>-->


	</tr>
	
</table>

<p style="margin-left:200px">Dear Parent<BR>
        Greetings !!<br>
			Congratulations on being a part of the School of International Education ,DPS Faridabad.<BR>
			Before stepping into the session 2022-23 and for smooth planning, you are requested to click <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" >here</button>
 to provide details of your ward by April 10,2022.<BR>

		Principal <BR>
		DPS Faridabad<br>
		
</form>
<p align="center">&nbsp;</p>

<div class="modal fade" id="exampleModal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm"  style="width:500px;">
    <div class="modal-content">
      <div class="modal-header">
          <i class="fa fa-gift"></i>&nbsp;&nbsp;
     
       
      </div>
      <form method="POST" name="form_dta">
      <div class="modal-body">
        <div class="row">
           <div class="col-sm-6">
           		<label>Child's Name :</label>
           		<input type="text" class="form-control" name="name" id="name" value="<?php echo $sname;?>" readonly>
           </div>

            <div class="col-sm-6">
           		<label>Stage  :</label>
           		<input type="text" class="form-control" name="stage" id="stage" value="<?php echo $StudentClass;?>" readonly>
           </div>

        </div>


         <div class="row">
           <div class="col-sm-6">
           		<label>Admission No  :</label>
           		<input type="text" class="form-control" name="adm" id="adm" value="<?php echo $sadmission;?>" readonly>
           </div>

            <div class="col-sm-6">
           		<label>Transport Required  :</label>
           		<!--<input type="text" class="form-control" name="transport_req" id="transport_req" value="<?php echo $mode_of_transport;?>">-->
                <select  class="form-control" name="transport_req" id="transport_req">
           		    <option value=""> Please Select One</option>
           			<option value="Yes">Yes</option>
           			<option value="No">No</option>
           		</select>	
           </div>

        </div>

        <div class="row">
           <div class="col-sm-6">
           		<label>Meals From School  :</label>
           		<select  class="form-control" name="meal" id="meal">
           		    <option value=""> Please Select One</option>
           			<option value="Yes">Yes</option>
           			<option value="No">No</option>
           		</select>
           		
           </div>
           <div class="row">
           <div class="col-sm-6">
           		<label><a href="Meal details.pdf" target=_blank>Click here for Meal details</a></label>
           		
           </div>
           
           

            <div class="col-sm-6">
           		<label><!--Social Science other than Global Perspective-->  <!-- (To be filled by children from stage 5 onwards only )  :</label>
           		<!--<select  class="form-control" name="social_science" id="social_science">
           		    <option value=""> Please Select One</option>
           			<option value="Yes">Yes</option>
           			<option value="No">No</option>
           		</select>	
        
           		<!--<label>Preference 1 : Second Language other than English ( To be filled by children from stage 4 onwards only ) :</label>-->
           		
           </div>

        </div>
        

        <div class="row">
            <div class="col-sm-12">
                <label>Second Language other than English ( To be filled by children from stage 5 onwards only ) :</label>
            </div>
        </div> 
        
        <div class="row">
            
            <div class="col-sm-6">
           		<!--<label>preference 3 : Second Language other than English ( To be filled by children from stage 4 onwards only ) :</label>-->
           	    <label>Preference 1 :</label>
           	</div>
            
            <div class="col-sm-6">
           
           	    <select class="form-control" name="Second" id="Second">
           		    <option value=""> Please Select One</option>
           			<option value="Hindi">Hindi</option>
           			<option value="Sanskrit">Sanskrit</option>
           			<option value="German">German</option>
           			<option value="French">French</option>
           			<option value="Mandarin">Mandarin</option>
           			<option value="Spanish">Spanish</option>
           			
           		</select>
           		
            </div>
           
           

          

        </div>
        
        <div class="row">
             <div class="col-sm-6">
           		<!--<label>  : Second Language other than English ( To be filled by children from stage 4 onwards only ) :</label>-->
           		<label>Preference 2 :</label>
           	</div>
           	<div class="col-sm-6">
           		<select class="form-control" name="Second3" id="Second3">
           		    <option value=""> Please Select One</option>
           			<option value="Hindi">Hindi</option>
           			<option value="Sanskrit">Sanskrit</option>
           			<option value="German">German</option>
           			<option value="French">French</option>
           			<option value="Mandarin">Mandarin</option>
           			<option value="Spanish">Spanish</option>
           			
           			

           		</select>
           </div>
        </div>
           <div class="row">
             <div class="col-sm-6">
           		<!--<label>preference 3 : Second Language other than English ( To be filled by children from stage 4 onwards only ) :</label>-->
           	    <label>Preference 3 :</label>
           	</div>	
           	<div class="col-sm-6">	
           		<select class="form-control" name="Second2" id="Second2">
           		    <option value=""> Please Select One</option>
           			<option value="Hindi">Hindi</option>
           			<option value="Sanskrit">Sanskrit</option>
           			<option value="German">German</option>
           			<option value="French">French</option>
           			<option value="Mandarin">Mandarin</option>
           			<option value="Spanish">Spanish</option>
           			
           			

           		</select>
           </div>
        </div>

        

      </div> <!-- modal body -->

      <div class="modal-footer">
      	 <!-- <input type="Submit"  name="submit_data" class="btn btn-secondary" data-dismiss="modal" id="submit" value="Submit"> -->
      	<button type="button" class="btn btn-primary" data-dismiss="modal" id="submit_data">Submit</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
     </form> 
    </div>
  </div>
</div>
<script src="../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script>
$('#submit_data').click(function(){
var name=$('#name').val();
var stage=$('#stage').val();
var adm=$('#adm').val();
var transport_req=$('#transport_req').val();
var meal=$('#meal').val();
var Second=$('#Second').val();
var Second2=$('#Second2').val();
var Second3=$('#Second3').val();
var social_science=$('#social_science').val();

  


if(name=="")
{
   toastr.warning(" Name is mandatory!", "Validation Error");
  return false;
}
else if(stage=="")
{
  toastr.warning("Stage is mandatory!", "Validation Error");
  return false;
}

else if(adm=="")
{
  toastr.warning("Admission no. is mandatory!", "Validation Error");
  return false;
}

else if(transport_req=="")
{
  toastr.warning("Transport is mandatory!", "Validation Error");
  return false;
}

else if(meal=="")
{
  toastr.warning("Meal is mandatory!", "Validation Error");
  return false;
}

//else if(Second=="")
//{
  //alert("Second Language other than English is mandatory!");
  //return true;
//}
//else if(Second2=="")
//{
//  alert("Second Language other than English is mandatory!");
//  return true;
//}
//else if(Second3=="")
//{
//  alert("Second Language other than English is mandatory!");
//  return true;
//}
else if(social_science=="")
{
  alert("Social Science other than Global Perspective is mandatory!");
  return false;
}


else
{
 $( ".loading" ).show();
 
$.ajax(
{
url:'function_submit.php',
type:'post',
data:{
	   'name':name,
      'stage':stage,
      'adm':adm,
      'transport_req':transport_req,
      'meal':meal,
      'Second':Second,
      'Second2':Second2,
       'Second3':Second3,
      'social_science':social_science
      },
dataType:'JSON',
success:function(response)
{
	 $( ".loading" ).hide();
 alert(response.info);
 location.reload(true);
}

});

}
});
</script>
<!-- Toastr JS -->
<script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>
</body>

</html>

