<?php
session_start();
include '../connection.php';
include '../AppConf.php';
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	
	$sadmission=$_SESSION['userid'];
	$rs=mysqli_query($Con, "select `sname`,`sclass`,`srollno` from `student_master` where `sadmission`='$sadmission'");
	$row=mysqli_fetch_row($rs);
	$sname=$row[0];
	$StudentClass=$row[1];
	$StudentRollNo=$row[2];
	
	if($sadmission == "")
	{
		echo "<br><br><center><b>Session Expired!<br>click <a href='http://dpsfsis.com/'>here</a> to login again!";
		exit();
	}
?>
<?php
if($_REQUEST['IsSubmit']=="yes")
{
   $Consent=$_POST['txtConsent'];
   
   $AccompanyingParent=$_POST['txtOtherParent'];
    $AccompanyingReason=$_POST['txtReason'];
   
   $rsChk=mysqli_query($Con, "select * from SchoolReopenConsentForm where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="<center><b>Already Submitted!";
   }
   else
   {
       
	   mysqli_query($Con, "INSERT INTO SchoolReopenConsentForm(`sadmission`, `sname`, `sclass`, `Consent`,`Consent2`,`Reason`)VALUES('$sadmission','$sname','$StudentClass','$Consent','$AccompanyingParent','$AccompanyingReason')");
	   $Msg="<center><b>Submitted Successfully!";
	}
}
?>
<script type="text/javascript">
function ValidateConsent()
{
	if(document.getElementById("txtConsent").value=="No")
	{
	    document.getElementById("txtReason").disabled=false;
	    
	    //	document.getElementById("txtOtherParent").disabled=false;
		//document.getElementById("txtReason").readOnly=true;
		

		
	}
	else
	{
	    document.getElementById("txtReason").disabled=true;
// 		document.getElementById("txtOtherParent").disabled=true;
// 		document.getElementById("txtReason").readOnly=true;
// 		document.getElementById("txtOtherParent").value="";
// 		document.getElementById("txtReason").value="";
	}	


}

function ValidateSubmit()
{
	if(document.getElementById("txtConsent").value=="No")
	{
	    if(document.getElementById("txtReason").value=="")
		{
			alert("Please provide the reason accompanying");
			return;
		}
		
	}
	
	if(document.getElementById("txtConsent").value=="")
	{
		alert("Please select the consent");
		return;

	}
	document.getElementById("frmConsent").submit();

	
}

</script>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>SchoolReopenConsentForm</title>

</head>

<body>
<font face="Cambria">
<?php
if($Msg !='')
{
	echo "<p>".$Msg."</p>";
	exit();
}
?>
</font>
<form method="POST" name="frmConsent" id="frmConsent" action="">
<input type="hidden" name="IsSubmit" id="IsSubmit" value="yes">
<table border="1" width="100%" style="border-collapse: collapse" height="305">
	<tr>
		<td colspan="2" height="19" style="border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><img src="<?php echo $SchoolLogo; ?>" height="100px" width="400px"></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b><?php echo $SchoolAddress; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Email Id: <?php echo $SchoolEmailId; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="19" style="border-top-style: none; border-top-width: medium">
		<p align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" height="57">
		<p align="center"><font face="Cambria"><b>Consent Form</b></font></td>
	</tr>
	<tr>
		<td colspan="2" height="133">&nbsp;<p><font face="Cambria"><b>Dear 
		Parent</b><br>
		<br>
		School has started offline school for classes IX to XII from January 31,2022.</font>&nbsp; If you wish to send your ward to school, kindly 
		indicate your consent on the student portal by 9:00 pm&nbsp; January&nbsp; 
		27, 2022.&nbsp;&nbsp;&nbsp; <select size="1" name="txtConsent" id="txtConsent" onchange="ValidateConsent();" style="font-weight: 700">
			<option selected value="">Select One</option>
		<option value="Yes">Yes</option>
		<option value="No">No</option>
		
			<p>   <font face="Cambria">
			</select> .</p>
		</td>
	</tr>
	<tr>
		
		<td height="22" width="1729">
		
			<p><font face="Cambria">
			<!--<select size="1" name="txtOtherParent" id="txtOtherParent" style="font-weight: 700" disabled="true">
			<option selected value="">Select One</option>
		<option value="Elder Sister">Elder Sister</option>
		<option value="Aunt">Aunt</option>
		<option value="Grandmother">Grandmother</option>
		<option value="No one">No one</option>
		
			<p>   <font face="Cambria">
			</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
			<b>Reason&nbsp;&nbsp;for not Sending&nbsp;</b></font></font><b>(Please specify</b><font face="Cambria"><b>)&nbsp;</b>&nbsp;&nbsp;
			<input type=text  name="txtReason" id="txtReason" size=50  tyle="font-weight: 700" >&nbsp; 
			.</font></p>
		
		</td>
	</tr>
	<tr>
		<td colspan="2">
		<p>&nbsp;</p>
		</td>
</table>
	<p align="center">
	<font face="Cambria">
	<button type="button"  style="font-weight: 700" class="text-box" onclick="ValidateSubmit();">Submit</button></font></p>
</form>
<p align="center">&nbsp;</p>

</body>

</html>