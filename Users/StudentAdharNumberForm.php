<?php
session_start();
include '../connection.php';
include '../AppConf.php';
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	
	$sadmission=$_SESSION['userid'];
	$rs=mysqli_query($Con, "select `sname`,`sclass`,`srollno`,`StudentAadharNumber` from `student_master` where `sadmission`='$sadmission'");
	$row=mysqli_fetch_row($rs);
	$sname=$row[0];
	$StudentClass=$row[1];
	$StudentRollNo=$row[2];
	$StudentAadharNumber=$row[3];
        
           
	
	if($sadmission == "")
	{
		echo "<br><br><center><b>Session Expired!<br>click <a href='http://nkbpsdsis.mobilisesis.co.in/'>here</a> to login again!";
		exit();
	}
	
	   if($StudentAadharNumber!="")
	   {
	   
	   echo "<br><br><center><b>Dear Parent, <br>Your wards's Aadhar Card has been already uploaded and the Aadhar No is -   ".$StudentAadharNumber."<br>click <a href='landing.php'>here</a>  to go back!";
		exit();

	   }
	
	
?>
<?php
if($_REQUEST["isSubmit"]=="yes")
{
           $extension = end(explode(".", $_FILES["F1"]["name"]));
		      $AadharPhoto="";
		      
		  
		      if($_FILES["F1"]["name"] !="")
		      {
		      	$AadharPhoto="AadharPhoto".$t.$_FILES["F1"]["name"];
		      
			    
			   
		      }	
		      move_uploaded_file($_FILES["F1"]["tmp_name"],"AadharImages/AadharPhoto".$t.$_FILES["F1"]["name"]);
		      
   $StudentAadharNo=$_POST['txtAadharNo'];
    $FatherAadharNo=$_POST['txtAadharNofather'];
     $MotherAadharNo=$_POST['txtAadharNomother'];
 
			mysqli_query($Con, "update `IDCardInformation` set `StudentAadharCardNo`='$StudentAadharNo',`FatherAadharCardNo`='$FatherAadharNo',`MotherAadharCardNo`='$MotherAadharNo' where `sadmission`='$sadmission'");
	      
		mysqli_query($Con, "update `student_master` set `StudentAadharNumber`='$StudentAadharNo',`FatherAadharNumber`='$FatherAadharNo',`MotherAadharNumber`='$MotherAadharNo' where `sadmission`='$sadmission'");

	   $Msg="<center><b>Submitted Successfully!";
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Student Aadhar Number Form</title>
</head>
<script language="javascript">
 function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      
var _validFileExtensions = [".jpg", ".jpeg", ".bmp", ".gif", ".png"];    
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
                alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}
function Validate()
{

if(document.getElementById("chkAadhar").checked==true)
	{
		if(document.getElementById("txtAadharNo").value=="")
		{
			alert("Aadhar Number is mandatory!");
			return;
		}
	}
       	document.getElementById("frmAadhar").submit();

}
function fnlChkCategory(ctrlname)
{
	//alert(document.getElementById("chkAadhar").checked);
	//return;
	if(document.getElementById("chkAadhar").checked==true)
	{
		//alert("Checked");
		//alert(document.getElementById("chkAadhar").checked);
		//alert("Aadhar is available!");
			//document.getElementById("hAadhar").value="Yes";
			document.getElementById("txtAadharNo").readOnly = false;
			document.getElementById("txtAadharNofather").readOnly = false;
			document.getElementById("txtAadharNomother").readOnly = false;
			//document.getElementById("F1").readOnly=false;
			//return;
	}	
	else
	{
			//alert("Aadhar is available!");
			//document.getElementById("hAadhar").value="No";
			//document.getElementById("txtAadharNo").value="";
			//document.getElementById("F1").value="";
			document.getElementById("txtAadharNo").value="";
			document.getElementById("txtAadharNo").readOnly = true;
				document.getElementById("txtAadharNofather").readOnly = true;
			document.getElementById("txtAadharNomother").readOnly = true;

			
			//document.getElementById("F1").readOnly=true;
			//document.getElementById("txtAadharNo").readOnly="true";
			
			//return;
	}
}
	</script>
	
<body>
<font face="Cambria" size="4">
<?php
if($Msg !='')
{
	echo "<p>".$Msg."</p>";
	exit();
}
?>
</font>
<form  method ="post" name="frmAadhar" id="frmAadhar" action="StudentAdharNumberForm.php" enctype="multipart/form-data">
<font size="4" face="Cambria">
<input type="hidden" name="chkAadhar1" id="chkAadhar1" value="No">
<input type="hidden" name="isSubmit" id="isSubmit" value="yes">

		
</font>

		
<table border="1" width="100%" style="border-collapse: collapse" height="305">
	<tr>
		<td colspan="4" height="19" style="border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria" size="4"><img src="<?php echo $SchoolLogo; ?>" height="100px" width="400px"></font></td>
	</tr>
	<tr>
		<td colspan="4" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria" size="4"><b><?php echo $SchoolAddress; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="4" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria" size="4"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="4" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria" size="4"><b>Email Id: <?php echo $SchoolEmailId; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="4" height="19" style="border-top-style: none; border-top-width: medium">
		<p align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" height="57">
		<p align="center"><font face="Cambria" size="4">Aadhar Card Number</font></td>
	</tr>
	<tr>
		<td colspan="4" height="133">&nbsp;<p class="MsoNormal">
		<font face="Cambria" size="4"><span style="line-height: 115%">Dear 
		Parent</span></font></p>
		<p class="MsoNormal"><font face="Cambria" size="4">
		<span style="line-height: 115%">As a statutory requirement from the 
		Education Department of Delhi, you are required to provide the Aadhar 
		Card details of your ward by clicking on the link available on the 
		student portal. </span></font></p>
		<p>&nbsp;</p>
		<p><font face="Cambria" size="4"><b>PRINCIPAL</b></font></p>
		<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="21" width="516" colspan="2"><font size="4" face="Cambria">Select if you have Aadhar Card&nbsp;&nbsp;&nbsp;&nbsp; <input name="chkAadhar" id="chkAadhar" type="checkbox" value="1" onclick="javascript:fnlChkCategory('chkAadhar');"></font></td>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="258"><font face="Cambria" size="4">Student Aadhar Number</font></td>
		<td height="22" width="258"><font size="4" face="Cambria">
			<input name="txtAadharNo" id="txtAadharNo" type="text" maxlength="12"  placeholder="Enter child Aadhar No." readonly  onkeypress="return isNumberKey(event)"></font></td>
		<td height="22" width="259">
		
			<font face="Cambria" size="4">Father Aadhar Number</font></td>
		<td height="22" width="258">
		
		<font size="4" face="Cambria">
			<input name="txtAadharNofather" id="txtAadharNofather" type="text"  maxlength="12" placeholder="Enter father Aadhar No." readonly  onkeypress="return isNumberKey(event)"></font></td>
	</tr>
	<tr>
		<td height="22" width="258">&nbsp;</td>
		<td height="22" width="258">&nbsp;</td>
		<td height="22" width="259">
		
			&nbsp;</td>
		<td height="22" width="258">
		
		&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="259">
		
			<font face="Cambria" size="4">Mother Aadhar Number</font></td>
		<td height="22" width="258">
		
		<font size="4" face="Cambria">
			<input name="txtAadharNomother" id="txtAadharNomother" type="text" maxlength="12"  placeholder="Enter mother Aadhar No." readonly  onkeypress="return isNumberKey(event)"></font></td>
		<td height="22" width="259">
		
			&nbsp;</td>
		<td height="22" width="258">
		
		&nbsp;</td>
	</tr>
</table>
	<p align="center">


		<font size="4" face="Cambria">
		<input name="BtnSubmit" type="button" value="Submit" onclick="Validate();" style="font-weight: 700" class="text-box"></font></p>
</form>
<p align="center">&nbsp;</p>

</body>

</html>