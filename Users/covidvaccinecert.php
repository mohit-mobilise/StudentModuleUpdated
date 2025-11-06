<?php 
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

include '../connection.php';
include '../AppConf.php';

$sadmission = $_SESSION['userid'] ?? '';

// Use prepared statement to prevent SQL injection
$sadmission_clean = validate_input($sadmission, 'string', 50);
$stmt = mysqli_prepare($Con, "SELECT `srno`, `sadmission`,`sname`, `sclass`, `srollno` FROM `student_master` WHERE `sadmission`=?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $sadmission_clean);
    mysqli_stmt_execute($stmt);
    $rsEmpDetail = mysqli_stmt_get_result($stmt);
} else {
    error_log('Covid vaccine cert query error: ' . mysqli_error($Con));
    $rsEmpDetail = false;
}
	
	

	
	
	if($sadmission== "")
	{
	echo "<br><br><center><b>You are not logged-in!<br>Please click <a href='http://dpsfsis.com'>here</a> to login into Student portal!";
		exit();
	}


		if(($_REQUEST["isSubmit"] ?? '') == "yes") {
            // Use prepared statement to check if already submitted
            $stmt_chk = mysqli_prepare($Con, "SELECT * FROM Covidvaccinecertificate WHERE `sadmission`=?");
            if ($stmt_chk) {
                mysqli_stmt_bind_param($stmt_chk, "s", $sadmission_clean);
                mysqli_stmt_execute($stmt_chk);
                $rsChk = mysqli_stmt_get_result($stmt_chk);
                
                if(mysqli_num_rows($rsChk) > 0) {
                    echo "<center><b>Already Submitted!";
                    mysqli_stmt_close($stmt_chk);
                    exit();
                }
                mysqli_stmt_close($stmt_chk);
            }
            
            // Validate and sanitize inputs
            $sadmission_input = validate_input($_POST['txtEmpNo'] ?? '', 'string', 50);
            $sname = validate_input($_POST['txtEmpName'] ?? '', 'string', 100);
            $sclass = validate_input($_POST['txtEmpType'] ?? '', 'string', 20);
            $srollno = validate_input($_POST['txtDesig'] ?? '', 'string', 20);
            $Description = validate_input($_POST['txtDescription'] ?? '', 'string', 500);
            
            $t = time();
            $ArticleName = "";
            $ArticleName1 = "";
            
            // Handle first file upload with security validation
            if(isset($_FILES["F1"]) && $_FILES["F1"]["error"] == UPLOAD_ERR_OK) {
                $upload_result = validate_file_upload($_FILES["F1"], ['image/jpeg', 'image/png', 'image/jpg'], 5242880);
                
                if ($upload_result['valid']) {
                    $file_info = pathinfo($_FILES["F1"]["name"]);
                    $extension = strtolower($file_info['extension'] ?? '');
                    $safe_filename = generate_secure_filename($_FILES["F1"]["name"], "ArticleName" . $t . "_");
                    $target_file = "../Admin/Covidvaccinecertificate/" . $safe_filename;
                    
                    // Validate path doesn't contain directory traversal
                    $real_path = realpath(dirname($target_file));
                    $base_path = realpath('../Admin/Covidvaccinecertificate/');
                    if (strpos($real_path, $base_path) === 0) {
                        if(move_uploaded_file($_FILES["F1"]["tmp_name"], $target_file)) {
                            $ArticleName = $safe_filename;
                        }
                    }
                }
            }
            
            // Handle second file upload with security validation
            if(isset($_FILES["F2"]) && $_FILES["F2"]["error"] == UPLOAD_ERR_OK) {
                $upload_result = validate_file_upload($_FILES["F2"], ['image/jpeg', 'image/png', 'image/jpg'], 5242880);
                
                if ($upload_result['valid']) {
                    $file_info = pathinfo($_FILES["F2"]["name"]);
                    $extension = strtolower($file_info['extension'] ?? '');
                    $safe_filename = generate_secure_filename($_FILES["F2"]["name"], "ArticleName1" . $t . "_");
                    $target_file = "../Admin/Covidvaccinecertificate/" . $safe_filename;
                    
                    // Validate path doesn't contain directory traversal
                    $real_path = realpath(dirname($target_file));
                    $base_path = realpath('../Admin/Covidvaccinecertificate/');
                    if (strpos($real_path, $base_path) === 0) {
                        if(move_uploaded_file($_FILES["F2"]["tmp_name"], $target_file)) {
                            $ArticleName1 = $safe_filename;
                        }
                    }
                }
            }
            
            // Use prepared statement for INSERT
            $stmt_insert = mysqli_prepare($Con, "INSERT INTO `Covidvaccinecertificate`(`sadmission`, `sname`, `sclass`, `srollno`,`JournalDescription`,`JournalName`,`JournalFile2`) VALUES(?,?,?,?,?,?,?)");
            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, "sssssss", $sadmission_input, $sname, $sclass, $srollno, $Description, $ArticleName, $ArticleName1);
                mysqli_stmt_execute($stmt_insert);
                mysqli_stmt_close($stmt_insert);
                echo "<br><br><center><b>Article Uploaded Successfully!<br>Click <a href='javascript:window.close();'>here</a> to close window";
            }
		 }
		}
		      
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
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
				toastr.error("Your Browser Broke!", "Error");
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
<b><font face="Cambria">Covid vaccine certificate</font></b></td>
</tr>
<tr>
<td align="center">
<p align="left"><u><b><font face="Cambria">Instructions:</font></b></u></p>
<p align="left"><font face="Cambria">* </font><b><font face="Cambria">
certificate </font></b><font face="Cambria">must be uploaded in jpeg, 
jpg, png format only</font></p>
</td>
</tr>
<tr>
<td align="center">
&nbsp;</td>
</tr>
</table>
<table style="width: 100%" class="style1">
<form name="frmUpload" id="frmUpload" method="post" action="covidvaccinecert.php" enctype="multipart/form-data">
<input type="hidden" name="isSubmit" id="isSubmit" value="yes">
<?php
while($row1 = mysqli_fetch_row($rsEmpDetail))
		
		{
			
			
					$sname=$row1[2];
					$sclass=$row1[3];
					$srollno=$row1[4];
					
					
	?>
	<tr>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146">
			<p><b><font face="Cambria">Admission No.</font></b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146"><font face="Cambria">
			<input name="txtEmpNo" id="txtEmpNo"style="float: left" value="<?php echo $sadmission; ?>" class="text-box"/></font></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="146">
			<p><span class="style4"><b>Student</b></span><b><font face="Cambria"> Name</font></b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147"><font face="Cambria">
			<input name="txtEmpName" id="txtEmpName" style="float: left"/ value="<?php echo $sname; ?>" class="text-box"></font></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147">
			<font face="Cambria"><b>Class </b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147"><font face="Cambria">
			<input name="txtEmpType" id="txtEmpType" style="float: left"/ value="<?php echo $sclass; ?>" class="text-box"></font></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147">
			<b><font face="Cambria">Roll No</font></b></td>
		  <td align="center" style="border-style: solid; border-width: 1px" width="147"><font face="Cambria">
			<input name="txtDesig" id="txtMobile" style="float: left"/ value="<?php echo $srollno; ?>" class="text-box"></font></td>
		  
		
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
		<p style="text-align: left">Dear Parent<br>
		If your ward has taken the Covid vaccine , <u>but not from School on 
		January 6,2022</u>, kindly upload a scanned copy of the certificate on 
		the student portal by Sunday, January 9,2022. :</td>
		<td class="style2" colspan="2"><font face="Cambria">
			<input type="file" name="F1" size="20" style="float: left; font-weight: 700"  required /></font>(Max. Size 1MB)</td>
	</tr>
	<!--<tr>
		<td class="style2" colspan="6">
		<p style="text-align: left">2. Details and photographs of achievement in 
		sports, Music, Art or any other at District, State, National and 
		International level</td>
		<td class="style2" colspan="2">
		<p style="text-align: left"><font face="Cambria">
			<input type="file" name="F2" size="20" style="float: left; font-weight: 700" required ></font></td>
	</tr>
	<tr>
		<td class="style2" colspan="6">
		<p style="text-align: left">3. Contribution for the magazine in the form 
		of poems, articles, puzzles, short stories in English, Hindi, Sanskrit, 
		French, German; paintings, sketches along with a passport size 
		photograph in school uniform</td>
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

</body>

</html>