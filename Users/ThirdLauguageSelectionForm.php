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
if(isset($_POST['submit']))
{
   $Consent=$_POST['D1'];
   $rsChk=mysqli_query($Con, "select * from StudentThirdLaugClass4 where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="<center><b>Already Submitted!";
   }
   else
   {
	   mysqli_query($Con, "INSERT INTO StudentThirdLaugClass4(`sadmission`, `sname`, `sclass`, `Stream`)VALUES('$sadmission','$sname','$StudentClass','$Consent')");
	   $Msg="<center><b>Submitted Successfully!";
	}
}
?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title> Stream Selection Form </title>
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
<form method="POST" method ="post" action="">
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
		<p align="center"><font face="Cambria"><b>Circular</b></font></p>
		<p align="center"><b><font face="Cambria" size="4">Third Language&nbsp; Selection Form</font></b></td>
	</tr>
	<tr>
		<td colspan="2" height="133">&nbsp;<p><font face="Cambria"><b>Dear 
		Parent</b></font></p>
		<div style="padding-top: 0px; border-top: 0px; color: rgb(51, 51, 51); font-family: &quot;Lucida Grande&quot;, Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);">
			<font face="Cambria" size="4">
			<br>
			Kindly indicate the 3rd language to be opted for by your ward next year i.e in class 
			V by selecting the right option.</font></div>
		<p class="MsoNormal">
		<font face="Cambria"><br>
		<b>PRINCIPAL</b></font></p>
		<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="21" width="516">&nbsp;</td>
		<td height="21" width="517">&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="516"><font face="Cambria"><b>I Select Third 
		Language</b></font></td>
		<td height="22" width="517">
		
			<p><font face="Cambria">
			<select size="1" name="D1" style="font-weight: 700">
				<option value="">Select One</option>
				<option value="German">German</option>
				<option value="French">French</option>
				<option value="Sanskrit">Sanskrit</option>
				
				</select></font></p>
		
		</td>
	</tr>
</table>
	<p align="center">
	<font face="Cambria">
	<input name="submit" type="submit" value="Submit" style="font-weight: 700" class="text-box" ></font></p>
</form>
<p align="center">&nbsp;</p>

</body>

</html>