<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php
session_start();
$class=$_SESSION['StudentClass'];
$sadmission=$_SESSION['userid'];

	if($sadmission== "")
	{
		echo "<br><br><center><b>You are not logged-in!<br>Please click <a href='http://dpsfsis.com/'>here</a> to login into parent portal!";
		exit();
	}

$rsChk=mysqli_query($Con, "select * from `StudentInfo_Class8` where `sadmission`='$sadmission'");
if(mysqli_num_rows($rsChk)>0)
{
	echo "<br><br><center><b>Already Submitted!";
	exit();
}
//$sadmission="10328";
$rsStudentDetail=mysqli_query($Con, "select `sname`,`DOB`,`PlaceOfBirth`,`Sex`,`sclass`,`LastSchool`,`Address`,`Hostel`,`sfathername`,`FatherEducation`,`FatherOccupation`,`smobile`,`email`,`MotherName`,`MotherEducation` from `student_master` where `sadmission`='$sadmission'");
while($rowS=mysqli_fetch_row($rsStudentDetail))
{
	$sname=$rowS[0];
	
	$DOB=$rowS[1];
	$arr=explode('-',$DOB);
	$DOB= $arr[1] . "/" . $arr[2] . "/" . $arr[0];
	
	$PlaceOfBirth=$rowS[2];
	$Sex=$rowS[3];
	$sclass=$rowS[4];
	$LastSchool=$rowS[5];
	$Address=$rowS[6];
	$Hostel=$rowS[7];
	$sfathername=$rowS[8];
	$FatherEducation=$rowS[9];
	$FatherOccupation=$rowS[10];
	$smobile=$rowS[11];
	$email=$rowS[12];
	$MotherName=$rowS[13];
	$MotherEducation=$rowS[14];
	
	break;
}

$ssqlClass="SELECT distinct `MasterClass` FROM `class_master`";
$rsClass= mysqli_query($Con, $ssqlClass);
{
}

$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster`";
$rsFY= mysqli_query($Con, $ssqlFY);
$rsEducation=mysqli_query($Con, "select distinct `Qualification` from `NewStudentRegistrationQualificationMaster` order by `Qualification`");
$rsEducation1=mysqli_query($Con, "select distinct `Qualification` from `NewStudentRegistrationQualificationMaster` order by `Qualification`");

$rsSchooListFather=mysqli_query($Con, "select distinct `SchoolName` from `NewStudentRegistrationSchoolList` order by `SchoolName`");
$rsSchooListMother=mysqli_query($Con, "select distinct `SchoolName` from `NewStudentRegistrationSchoolList` order by `SchoolName`");

$rsLocation=mysqli_query($Con, "select distinct `Sector` from `NewStudentRegistrationDistanceMaster` order by `Sector`");

$currentdate=date("d-m-Y");

	$ssqlRoute="SELECT distinct `routeno` FROM `RouteMaster`";
	$rsRoute= mysqli_query($Con, $ssqlRoute);
	
	

$ssqlDiscount="SELECT distinct `head` FROM `discounttable` where `discounttype`='tuitionfees'";
$rsDiscount= mysqli_query($Con, $ssqlDiscount);

$sstr="SELECT distinct `head` FROM `discounttable` where `discounttype`='admissionfees'";
$rsAdmissionFeeDiscount= mysqli_query($Con, $sstr);

$sstr="SELECT distinct `head` FROM `discounttable` where `discounttype`='annualcharges'";
$rsAnnualFeeDiscount= mysqli_query($Con, $sstr);

$rsSchool = mysqli_query($Con, "select distinct `SchoolId`,`SchoolName` from `SchoolConfig`");
$ssqlClass="SELECT distinct `class` FROM `class_master`";
$rsClass= mysqli_query($Con, $ssqlClass);

?>
<script language="javascript">
function Validate1()
{
	
	if(document.getElementById("txtName").value=="")
	{
		alert("Please enter Name!");
		return;
	}
	if(document.getElementById("txtMotherName").value=="")
	{
		alert("Please enter MotherName!");
		return;
	}
	if(document.getElementById("txtFatherName").value=="")
	{
		alert("Please enter Father's Name!");
		return;
	}
	if(document.getElementById("txtmobile").value=="")
	{
		alert("Please enter Mobile!");
		return;
	}
	if(document.getElementById("txtEmail").value=="")
	{
		alert("Please enter Email!");
		return;
	}
	if(document.getElementById("cboClass").value=="")
	{
		alert("Please enter Class !");
		return;
	}
	if(document.getElementById("txtAddress").value=="")
	{
		alert("Please enter Address!");
		return;
	}
	if(document.getElementById("txtDOB").value=="")
	{
		alert("Please enter DOB!");
		return;
	}
  if(document.getElementById("cboSubject").value=="")
	{
		alert("Please Select Optional Subject!");
		return;
	}

	document.getElementById("frmMaths").submit();


}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Optional Selection</title>
<link rel="stylesheet" type="text/css" href="../Admin/css/style.css" />
<style type="text/css">
.style1 {
	text-align: center;
}
.style2 {
	border-collapse: collapse;
	border-width: 0px;
}
.style3 {
	text-align: left;
}
</style>
</head>
<body>
<div id="logo">
<p align="center">
<img src="../Admin/images/logo.png" height="90px" width="400px" />
<br><font face="cambria" size="4" color=#FFFFFF><b><?php echo $SchoolAddress1; ?><br><br></b></font>
</p>
<h1 align=center>
<font face="Cambria" style="font-size: 16pt; text-decoration: underline"><span style="font-weight: 400">
<br></span></font>
<font face="Cambria" style="font-size: 16pt">SECOND LANGUAGE FORM</font><font face="Cambria" style="font-size: 16pt; "><br>&nbsp;</font></h1> 
<form name="frmMaths" id="frmMaths" method="post" action="SubmitStudentInfo.php" enctype="multipart/form-data">
<table border="1" width="100%" style="border-width:0px; border-collapse: collapse; ">
	<tr>
		<td style="border-left:medium none #C0C0C0; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="39%">Name</td>
		<td style="border-style: none; border-width: medium" width="22%"><b>
                <input type="text" name="txtName" class="text-box" id="txtName" value="<?php echo $sname;?>" ></b></td>
		<td style="border-style: none; border-width: medium" width="17%">DOB</td>
		<td style="border-right:medium none #808080; border-left-style:none; border-left-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="21%"><b>
                <input id="txtDOB" type="text" name="txtDOB" class="text-box" value="<?php echo $DOB;?>" /></b></td>
	</tr>
	<tr>
		<td style="border-left:medium none #C0C0C0; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="39%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="22%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="17%">&nbsp;</td>
		<td style="border-right:medium none #808080; border-left-style:none; border-left-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left:medium none #C0C0C0; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="39%">Class</td>
		<td style="border-style: none; border-width: medium" width="22%"><b>
                <input type="text" name="cboClass" class="text-box" id="cboClass" value="<?php echo $sclass;?>" ></b></td>
		<td style="border-style: none; border-width: medium" width="17%">Mobile No</td>
		<td style="border-right:medium none #808080; border-left-style:none; border-left-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" width="21%"><b>
                <input id="txtmobile" type="text" name="txtmobile" class="text-box" value="<?php echo $smobile;?>"/></b></td>
	</tr>
	<tr>
		<td style="border-left:medium none #C0C0C0; border-bottom-style: none; border-bottom-width: medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium" width="39%">&nbsp;</td>
		<td style="border-style:none; border-width:medium; " width="22%">&nbsp;</td>
		<td style="border-style:none; border-width:medium; " width="17%">&nbsp;</td>
		<td style="border-right:medium none #808080; border-bottom-style: none; border-bottom-width: medium; border-left-style:none; border-left-width:medium; border-top-style:none; border-top-width:medium" width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-style:none; border-width:medium; " width="39%">Father's Name</td>
		<td style="border-style:none; border-width:medium; " width="22%"><b><input id="txtFatherName" class="text-box" type="text" name="txtFatherName" value="<?php echo $sfathername;?>" /></b></td>
		<td style="border-style:none; border-width:medium; " width="17%">Email Id</td>
		<td style="border-style:none; border-width:medium; " width="21%"><b>
                <input id="txtEmail" type="text" name="txtEmail" class="text-box" value="<?php echo $email;?>"/></b></td>
	</tr>
	<tr>
		<td style="border-style:none; border-width:medium; " width="39%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="22%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="17%">&nbsp;</td>
		<td style="border-style:none; border-width:medium; " width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-style:none; border-width:medium; " width="39%">Mother's Name</td>
		<td style="border-style: none; border-width: medium" width="22%"><b><input id="txtMotherName" type="text" name="txtMotherName" class="text-box" value="<?php echo $MotherName;?>" /></b></td>
		<td style="border-style: none; border-width: medium" width="17%">Address</td>
		<td style="border-style:none; border-width:medium; " width="21%"><b>
                <input id="txtAddress" type="text" name="txtAddress" class="text-box" value="<?php echo $Address;?>"></b></td>
	</tr>
	<tr>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="39%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="22%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="17%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="39%">
		Second Language (Compulsory Subject)</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="22%"><b>
               <select size="1" name="cboSubject" id="cboSubject" class="text-box" required >
		<option selected value="">Select One</option>
		<option value="Hindi Course-B">Hindi Course-B</option> 
		<option value="Sanskrit">Sanskrit</option>
		<option value="French">French</option>
		<option value="German">German</option>
		<!--<option value="Manipuri">Manipuri</option>-->
		
		

		</select></b></td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="17%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="21%">&nbsp;</td>
			</tr>
			
			
				<tr>
		<td style="border-style:none; border-width:medium; " width="39%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="22%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="17%">&nbsp;</td>
		<td style="border-style:none; border-width:medium; " width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td style="border-style:none; border-width:medium; " width="39%">* Note 
		that Computer Application, Information Technology and Artificial 
		Intelligence cannot be chosen together under Additional 
		Language/Subject and Skill Subject.</td>
		<td style="border-style: none; border-width: medium" width="22%">&nbsp;</td>
		<td style="border-style: none; border-width: medium" width="17%">&nbsp;</td>
		<td style="border-style:none; border-width:medium; " width="21%">&nbsp;</td>
	</tr>
			
		<tr>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="39%">
		Additional Language / Subject (Optional )</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="22%"><b>
               <select size="1" name="cboAddSubject" id="cboAddSubject" class="text-box" >
		<option selected value="">Select One</option>
		<option value="Hindi Course-A">Hindi Course-A</option>
		<option value="Hindi Course-B">Hindi Course-B</option>
		<option value="Bengali">Bengali</option>
			<option value="Punjabi">Punjabi</option>
		<!--<option value="Manipuri">Manipuri</option>-->
		<option value="French">French</option>
		<option value="German">German</option>
		<option value="Russian">Russian</option>
		<option value="Nepali">Nepali</option>
		<option value="Japanese">Japanese</option>
		<option value="Spanish">Spanish</option>
		<option value="Sanskrit">Sanskrit</option>
		<option value="Hindustani Music (Vocal)">Hindustani Music (Vocal)</option>
		<option value="Hindustani Music Melodious Instrument">Hindustani Music Melodious Instrument</option>
		<option value="Hindustani Music Percussion Instrument">Hindustani Music Percussion Instrument</option>
		<option value="Painting">Painting</option>
		<option value="Home Science">Home Science</option>
		<option value="NCC">NCC</option>
		<option value="* Computer Applications">* Computer Applications</option>
		<option value="Elements of Business">Elements of Business</option>
		<option value="Elements of Book Keeping & Accountancy">Elements of Book Keeping & Accountancy</option>
		<option value="Not Applicable">Not Applicable</option>
		
		

		</select></b></td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="17%">
		Skill Subject (Optional)</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="21%"><b>
               <select size="1" name="cboskillSubject" id="cboskillSubject" class="text-box" >
		<option selected value="">Select One</option>
		<option value="Retailing">Retailing</option> 
		<option value="* Information Technology">* Information Technology</option>
		<option value="Introduction to Financial Markets">Introduction to Financial Markets</option>
		<option value="Introduction to Tourism">Introduction to Tourism</option>
		<option value="Beauty & Wellness">Beauty & Wellness</option>
		<option value="Agriculture">Agriculture</option>
		<option value="Front Office Operations">Front Office Operations</option>
		<option value="Banking and Insurance">Banking and Insurance</option>
		<option value="* Artificial Intelligence">* Artificial Intelligence</option>
		<option value="Physical Activity Trainer">Physical Activity Trainer</option>
		<option value="Data Science">Data Science</option>
		<option value="Not Applicable">Not Applicable</option>
		
		

		</select></b></td>
			</tr>	
			
	<tr>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" width="39%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" width="22%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" width="17%">&nbsp;</td>
		<td style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" width="21%">&nbsp;</td>
	</tr>
	<tr>
		<td align="center" colspan="4" style="border-style:none; border-width:medium; ">
				<input type="button" name="btnSubmit" class="text-box" value="Submit" onclick="Validate1();"></td>
	</tr>
</table>
</div>

</form>
</body>