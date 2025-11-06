<?php 
include '../connection.php';
include '../AppConf.php';

?>
<?php

session_start();
$EmployeeId=$_SESSION['userid'];

$rsEmpDetail=mysqli_query($Con, "SELECT `sadmission`, `sname`, `sclass` FROM `student_master` WHERE `sadmission`='$EmployeeId'");
	
	$rsChk=mysqli_query($Con, "select * from `Employee_26JANConsent` where `EmpId`='$EmployeeId'");
if(mysqli_num_rows($rsChk)>0)
{
	echo "<br><br><center><b>Already Submitted!";
	exit();
}

	
	
	if($EmployeeId== "")
	{
	echo "<br><br><center><b>You are not logged-in!<br>Please click <a href='http://dpsfsis.com/Admin/Login.php'>here</a> to login into Admin portal!";
		exit();
	}

	if($_REQUEST["isSubmit"]=="yes")
		{

			  
			  $sadmission=$_POST['txtEmpNo'];
			  $sname=$_POST['txtEmpName'];
			  $sclass=$_POST['txtEmpType'];
			  $srollno=$_POST['txtDesig'];
			  $Description=$_POST['txtDescription'];
			  $Consent=$_POST['txtConsent'];
			  
			
		      
		      mysqli_query($Con, "INSERT INTO `Employee_26JANConsent`(`EmpId`, `EmpName`, `EmpType`, `EmpDesig`,`Consent`,`Reason`) VALUES('$sadmission','$sname','$sclass','$srollno','$Consent','$Description')");
		      echo "<br><br><center><b>Consent Filled Successfully!<br>Click <a href='javascript:window.close();'>here</a> to close window";
		 }
		      
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<script language="javascript">
function Validate()
{
	
	
	if (document.getElementById("txtConsent").value.trim()=="Not Going")
	{
		alert("Name is mandatory");
		return;
	if(document.getElementById("txtLastName").value.trim() =="")
	{
		alert("Last name is mandatory");
		return;
	}
	}
	
</script>
<meta http-equiv="Content-Language" content="en-us">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Select File</title>

<script language="javascript">


function sname()
{
	document.getElementById("trWait").style.display="";
	document.getElementById("trWait").innerHTML ="Please Wait...";
	var itm;
	try
	{
		itm = new XMLHttpRequest();
	}
	catch(e)
	{
		try
		{
			itm = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
			itm = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
			
			
			
				alert("Your Browser Broke!");
				return false;
			}
		}
	}
	
	itm.onreadystatechange=function()
		      {
			      if(itm.readyState==4)
			        {
						var rows="";
			        	rows=new String(itm.responseText);
			        	arr_row=rows.split(",");
			        	document.getElementById('txtEmpName').value=arr_row[0];
						document.getElementById('txtEmpType').value=arr_row[1];       	 
						document.getElementById('txtMobile').value=arr_row[2];       	 
						document.getElementById("trWait").style.display="none";
						document.getElementById("trWait").innerHTML ="";						
			        }
		      }
			
			var submiturl="get_info2.php?c=" + document.getElementById('txtEmpNo').value;
			itm.open("GET", submiturl,true);
			itm.send(null);
}



</script>
	

<style type="text/css">
.style1 {
	border-collapse: collapse;
}
.style2 {
	text-align: right;
	font-family: Cambria;
}
.style3 {
	text-align: center;
}
.style4 {
	font-family: Cambria;
}
</style>
<link rel="stylesheet" type="text/css" href="../Admin/tcal.css" />
	
	<link rel="stylesheet" type="text/css" href="../css/style.css" />

</head>

<body>
<table width="100%">
<tr>
<td>
<h1 align="center"><b><font face="cambria">
<img src="<?php echo $SchoolLogo; ?>" height="80" width="360"></font></b></h1>
</td>
</tr>
<tr>
<td align="center">
<font face="cambria"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font>
</td>
</tr>
<tr>
<td align="center">
<font face="cambria"><b>Email Id: <?php echo $SchoolEmailId; ?></b></font>
</td>
</tr>
<tr>
<td align="center">
<u><b><font face="Cambria">Consent for 26 JANUARY 2022 Program</font></b></u></td>
</tr>
<tr>
<td align="center">
<p align="left">&nbsp;</p>
</td>
</tr>
<tr>
<td align="center">
&nbsp;</td>
</tr>
</table>
<table style="width: 100%" class="style1">
<form name="frmUpload" id="frmUpload" method="post" action="Employee26JanConsent.php" enctype="multipart/form-data">
<input type="hidden" name="isSubmit" id="isSubmit" value="yes">
<?php
while($row1 = mysqli_fetch_row($rsEmpDetail))
		
		{
			
			
					$sadmission=$row1[0];
	                $sname=$row1[1];
	                $sclass=$row1[2];
					
	?>
	<tr>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146">
			<p><b><font face="Cambria">Emp No.</font></b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146"><font face="Cambria">
			<input name="txtEmpNo" id="txtEmpNo"style="float: left" value="<?php echo $sadmission; ?>" class="text-box"/></font></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146">
			<p><b><span class="style4">Emp</span><font face="Cambria"> Name</font></b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147"><font face="Cambria">
			<input name="txtEmpName" id="txtEmpName" style="float: left"/ value="<?php echo $sname; ?>" class="text-box"></font></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147">
			<font face="Cambria"><b>Emp Department</b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147"><font face="Cambria">
			<input name="txtEmpType" id="txtEmpType" style="float: left"/ value="<?php echo $sclass; ?>" class="text-box"></font></td>
		
		
 </tr>
 
 <?php
 
 }
 ?>
	<tr>
		  <td align="center" colspan="8" >
			&nbsp;</td>
 	</tr>
	<tr>
		<td class="style2" colspan="8">&nbsp;</td>
	</tr>
	<tr>
		<td class="style2" colspan="6">
		<p style="text-align: left">I will be able to attend School for the 
		unfurling of the National Flag on January 26,2022 at 10 am</td>
		<td class="style2" colspan="2">







		<p style="text-align: left">







		<select size="1" name="txtConsent" id="txtConsent" class="text-box" required/>
		<option selected value="">Select One</option>
		<option value="Yes">Yes</option>
		<option value="No">No</option>
		
		</select></td>
	</tr>
	<tr>
		<td class="style2" colspan="6">
		&nbsp;</td>
		<td class="style2" colspan="2">
		&nbsp;</td>
	</tr>
	<!--<tr>
		<td class="style2" colspan="6">
		<p style="text-align: left">3. Please mention the reason (If not going)</td>
		<td class="style2" colspan="2">
		<p style="text-align: left"><font face="Cambria">
		<textarea rows="2" name="txtDescription" id="txtDescription" cols="20" style="font-weight: 700" class="text-box-address" required ></textarea></font></td>
	</tr>-->
	<tr>
		<td colspan="8" class="style3">
		&nbsp;</td>
	</tr>
	<tr>
		<td colspan="8" class="style3">
		

		<font face="Cambria">
		<input name="Submit1" type="submit" value="Submit" class="text-box"></font></td>
	</tr>
</form>
</table>

<p><font face="Cambria">&nbsp;&nbsp;&nbsp; </font></p>

</body>

</html>