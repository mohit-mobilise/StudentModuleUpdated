<?php
	include '../connection.php';
	include '../AppConf.php';
?>

<?php

     session_start();
$class=$_SESSION['StudentClass'];
$sadmission=$_SESSION['userid'];

 $refURL = $_SERVER['HTTP_REFERER'];

 $checkURl = $BaseURL.'view_article.php';


 $checkURl1 = $BaseURL.'Users/StudentInfo.php';


 if (($refURL == $checkURl) || ($refURL == $checkURl1)) 
 {

    $sadmission_nw=$_POST['new_std_frm_adm'];

	 $rsChk=mysqli_query($Con, "SELECT * from UpdatedStudentInfoData where `sadmission`='$sadmission_nw'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		echo ("<br><br><center><b>Your Request has been updated succesfully!<br>Click <a href='landing.php'>here</a> to go homepage");
   		exit();
   		
   }
	
	//$sadmission="10328";
$rsStudentDetail=mysqli_query($Con, "select `sname`,`DOB`,`PlaceOfBirth`,`Sex`,`sclass`,`LastSchool`,`ResidentialAddress`,`sfathername`,`FatherEducation`,`FatherOccupation`,`smobile`,`email`,`MotherName`,`MotherEducation` from `NewStudentAdmission` where `sadmission`='$sadmission_nw'");
	
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
		$sfathername=$rowS[7];
		$FatherEducation=$rowS[8];
		$FatherOccupation=$rowS[9];
		$smobile=$rowS[10];
		$email=$rowS[11];
		$MotherName=$rowS[12];
		$MotherEducation=$rowS[13];
		break;
	}

 	
 }
 else
 {
    


	if($sadmission== "")
	{
		echo "<br><br><center><b>You are not logged-in!<br>Please click <a href='http://dpsfsis.com/'>here</a> to login into parent portal!";
		exit();
	}

	 $rsChk=mysqli_query($Con, "SELECT * from UpdatedStudentInfoData where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		echo ("<br><br><center><b>Your Request has been updated succesfully!<br>Click <a href='landing_page.php'>here</a> to go homepage");
   		exit();
   		
   }
	
	//$sadmission="10328";
$rsStudentDetail=mysqli_query($Con, "select `sname`,`DOB`,`PlaceOfBirth`,`Sex`,`sclass`,`LastSchool`,`Address`,`Hostel`,`sfathername`,`FatherEducation`,`FatherOccupation`,`smobile`,`email`,`MotherName`,`MotherEducation`,`AadharNumber` from `student_master` where `sadmission`='$sadmission'");
	
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
		$AadharNumber=$rowS[15];
		break;
	}


  
 }

		
?>
<?php
if(isset($_POST['submit']))
{
     $sadmission=str_replace("'","&#39;",$_POST["sadmission"]);
     $sname=str_replace("'","&#39;",$_POST["sname"]);
     
     $Class_1=str_replace("'","&#39;",$_POST["sclass"]);
     
        $txtDOB=$_POST["txtDOB"];

        $txtDOB=date('Y-m-d',strtotime($txtDOB));
        $age=str_replace("'","&#39;",$_POST["txtAge"]);
           $gender=str_replace("'","&#39;",$_POST["gender"]);
           $bloodgroup=str_replace("'","&#39;",$_POST["bloodgroup"]);
           $Category=str_replace("'","&#39;",$_POST["Category"]);
           $Religion=str_replace("'","&#39;",$_POST["Religion"]);
           $routecode=str_replace("'","&#39;",$_POST["routecode"]);
           $sfathername=str_replace("'","&#39;",$_POST["sfathername"]);
           $FatherDesignation=str_replace("'","&#39;",$_POST["FatherDesignation"]);
           $FatherOfficeAddress=str_replace("'","&#39;",$_POST["FatherOfficeAddress"]);
           $FatherEmail=str_replace("'","&#39;",$_POST["FatherEmail"]);
           $FatherMobile=str_replace("'","&#39;",$_POST["FatherMobile"]);
           $MotherName=str_replace("'","&#39;",$_POST["MotherName"]);
           $MotherDesignation=str_replace("'","&#39;",$_POST["MotherDesignation"]);
           $MotherOfficeAddress=str_replace("'","&#39;",$_POST["MotherOfficeAddress"]);
           $MotherMobileNo=str_replace("'","&#39;",$_POST["MotherMobileNo"]);
           $MotherEmail=str_replace("'","&#39;",$_POST["MotherEmail"]);
           $Address=str_replace("'","&#39;",$_POST["Address"]);
           $MobileReceiveSMS=str_replace("'","&#39;",$_POST["MobileReceiveSMS"]);
           $EmailReceiveSMS=str_replace("'","&#39;",$_POST["EmailReceiveSMS"]);
           
           $SiblingsName=str_replace("'","&#39;",$_POST["SiblingsName"]);
           $SibilngsClass=str_replace("'","&#39;",$_POST["SibilngsClass"]);
           $StudentAadharNumber=str_replace("'","&#39;",$_POST["StudentAadharNumber"]);
           $FatherAadharNumber=str_replace("'","&#39;",$_POST["FatherAadharNumber"]);
           $MotherAadharNumber=str_replace("'","&#39;",$_POST["MotherAadharNumber"]);
           $illnes=str_replace("'","&#39;",$_POST["illness"]);
           $Surgery=str_replace("'","&#39;",$_POST["Surgery"]);
           $Allergies=str_replace("'","&#39;",$_POST["Allergies"]);
           $Polio=str_replace("'","&#39;",$_POST["Polio"]);
           $DPT=str_replace("'","&#39;",$_POST["DPT"]);
           $Measles=str_replace("'","&#39;",$_POST["Measles"]);
           $Tetanus=str_replace("'","&#39;",$_POST["Tetanus"]);
           $Hepatitis=str_replace("'","&#39;",$_POST["Hepatitis"]);
           $injections=str_replace("'","&#39;",$_POST["injections"]);
           $other=str_replace("'","&#39;",$_POST["other"]);
           $medication=str_replace("'","&#39;",$_POST["medication"]);
        //   $father_photo_holder = $_POST['father_photo'];
        //   $student_photo_holder = $_POST['student_photo'];
        //   $mother_photo_holder = $_POST['mother_photo'];
           
           
           // new code 
           
             $target_dir = "student_info_photo/";
             $father_photo_holde = $_FILES['father_photo']['name'];
             $student_photo_holde = $_FILES['student_photo']['name'];
             $mother_photo_holde = $_FILES['mother_photo']['name'];
             
             $imageFileType_f = strtolower(pathinfo($father_photo_holde,PATHINFO_EXTENSION));
             $imageFileType_s = strtolower(pathinfo($student_photo_holde,PATHINFO_EXTENSION));
             $imageFileType_m = strtolower(pathinfo($mother_photo_holde,PATHINFO_EXTENSION));
             
             $img_name_f = $sadmission."-F.".$imageFileType_f;
             $img_name_s = $sadmission."-S.".$imageFileType_s;
             $img_name_m = $sadmission."-M.".$imageFileType_m;
             
             $target_file_f = $target_dir .$img_name_f;
             $target_file_s = $target_dir .$img_name_s;
             $target_file_m = $target_dir .$img_name_m;
             
             move_uploaded_file($_FILES["father_photo"]["tmp_name"], $target_file_f); 
             
             move_uploaded_file($_FILES['student_photo']["tmp_name"], $target_file_s); 
             
             move_uploaded_file($_FILES["mother_photo"]["tmp_name"], $target_file_m); 

             $father_photo_holder = $img_name_f;
             $student_photo_holder = $img_name_s;
             $mother_photo_holder = $img_name_m;
             
           //end


           $father_income=$_POST["FatherAnnualIncome"];
           $mother_income=$_POST["MotherAnnualIncome"];
           $family_income=$_POST["FamilyAnnualIncome"];
           
           //$Foodalleries=$_POST["Foodalleries"];
           $Foodalleries = '';
           $fmdocname=$_POST["fmdocname"];
           $anyotherremarks=$_POST["anyotherremarks"]; 
           $Tetinjections=$_POST["Tetinjections"];
           
           
           
           // new code
           
           $vacc_covid_father=$_POST["vacc_covid_father"];
           $vacc_covid_mother=$_POST["vacc_covid_mother"];
           $vacc_covid_child=$_POST["vacc_covid_child"];
           $suff_covid_father=$_POST["suff_covid_father"];
           $suff_covid_mother=$_POST["suff_covid_mother"];
           $suff_covid_child=$_POST["suff_covid_child"];
           
           
           
 
   $rsChk=mysqli_query($Con, "SELECT * from UpdatedStudentInfoData where `sadmission`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="<center><b> Dear Student,We have already received your form!";
   		
   }
   else
   {
// 	  $ssqlValue="INSERT INTO `UpdatedStudentInfoData` (`sadmission`, `sname`, `sclass`,`DOB`, `Sex`, `bloodgroup`, `Category`, `Religion`, `routecode`, `sfathername`, `FatherDesignation`, `FatherOfficeAddress`, `FatherEmail`, `FatherMobile`, `MotherName`, `MotherDesignation`, `MotherOfficeAddress`, `MotherMobileNo`, `MotherEmail`, `Address`, `MobileReceiveSMS`, `SiblingsName`, `SibilngsClass`, `StudentAadharNumber`, `FatherAadharNumber`, `MotherAadharNumber`,`illness_past`, `surgery_past`, `allergies`, `polio`, `DPT`, `measles`, `tetanus`, `hepatitis_B`, `date_last_injections`, `any_other`, `regular_medication`,`age`,`FatherAnnualIncome`,`MotherAnnualIncome`,`FamilyAnnualIncome`,`student_photo`, `father_photo`, `mother_photo`,`Foodalleries`, `fmdocname`, `anyotherremarks`,`Tetinjections`) 

// 	             VALUES ('$sadmission','$sname','$Class_1','$txtDOB','$gender','$bloodgroup','$Category','$Religion','$routecode','$sfathername','$FatherDesignation','$FatherOfficeAddress','$FatherEmail','$FatherMobile','$MotherName','$MotherDesignation','$MotherOfficeAddress','$MotherMobileNo','$MotherEmail','$Address','$MobileReceiveSMS','$SiblingsName','$SibilngsClass','$StudentAadharNumber','$FatherAadharNumber','$MotherAadharNumber','$illnes','$Surgery','$Allergies','$Polio','$DPT','$Measles','$Tetanus','$Hepatitis','$injections','$other','$medication','$age','$father_income','$mother_income','$family_income','$student_photo_holder','$father_photo_holder','$mother_photo_holder','$Foodalleries','$fmdocname','$anyotherremarks','$Tetinjections')";
        
        $ssqlValue="INSERT INTO `UpdatedStudentInfoData` (`sadmission`, `sname`, `sclass`,`DOB`, `Sex`, `bloodgroup`, `Category`, `Religion`, `routecode`, `sfathername`, `FatherDesignation`, `FatherOfficeAddress`, `FatherEmail`, `FatherMobile`, `MotherName`, `MotherDesignation`, `MotherOfficeAddress`, `MotherMobileNo`, `MotherEmail`, `Address`, `MobileReceiveSMS`, `SiblingsName`, `SibilngsClass`, `StudentAadharNumber`, `FatherAadharNumber`, `MotherAadharNumber`,`illness_past`, `surgery_past`, `allergies`, `polio`, `DPT`, `measles`, `tetanus`, `hepatitis_B`, `date_last_injections`, `any_other`, `regular_medication`,`age`,`FatherAnnualIncome`,`MotherAnnualIncome`,`FamilyAnnualIncome`,`student_photo`, `father_photo`, `mother_photo`,`Foodalleries`, `fmdocname`, `anyotherremarks`,`Tetinjections`,`vacc_covid_father`,`vacc_covid_mother`,`vacc_covid_child`,`suff_covid_father`,`suff_covid_mother`,`suff_covid_child`,`email_receive_from_school`) 

	             VALUES ('$sadmission','$sname','$Class_1','$txtDOB','$gender','$bloodgroup','$Category','$Religion','$routecode','$sfathername','$FatherDesignation','$FatherOfficeAddress','$FatherEmail','$FatherMobile','$MotherName','$MotherDesignation','$MotherOfficeAddress','$MotherMobileNo','$MotherEmail','$Address','$MobileReceiveSMS','$SiblingsName','$SibilngsClass','$StudentAadharNumber','$FatherAadharNumber','$MotherAadharNumber','$illnes','$Surgery','$Allergies','$Polio','$DPT','$Measles','$Tetanus','$Hepatitis','$injections','$other','$medication','$age','$father_income','$mother_income','$family_income','$student_photo_holder','$father_photo_holder','$mother_photo_holder','$Foodalleries','$fmdocname','$anyotherremarks','$Tetinjections','$vacc_covid_father','$vacc_covid_mother','$vacc_covid_child','$suff_covid_father','$suff_covid_mother','$suff_covid_child','$EmailReceiveSMS')";

  
 mysqli_query($Con, $ssqlValue);
	
 $Msg="<center><b> Dear Student, Your Student Information Form  has been submitted successfully !<br>";
	}
}


//$ssqlRoute="SELECT distinct `routeno` FROM `RouteMaster`";
//	$rsRoute= mysql_query($ssqlRoute, $Con);

?>

<html>

<head>


<script language="javascript">
function upperCaseF(a)
{
    setTimeout(function()
    {
        a.value = a.value.toUpperCase();
    }
    , 1);

 
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Student Information Form </title>

<style>
p.uppercase {
    text-transform: uppercase;
}

p.lowercase {
    text-transform: lowercase;
}

p.capitalize {
    text-transform: capitalize;
}
</style>


<style>
input[type=text], select {
    width: 50%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 25%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

</style>
<script language="javascript">	
// function CalculateAgeInQC() 
// {
//      if(document.getElementById("txtDOB").value=="")
//      {
//      	alert("Please enter Date of Birth!");
//      	return;
//      }
//      document.getElementById("txtAge").value="Please Wait";
//      try
// 		    {    
// 				// Firefox, Opera 8.0+, Safari    
// 				xmlHttp=new XMLHttpRequest();
// 			}
// 		  catch (e)
// 		    {    // Internet Explorer    
// 				try
// 			      {      
// 					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
// 				  }
// 			    catch (e)
// 			      {      
// 					  try
// 				        { 
// 							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
// 						}
// 				      catch (e)
// 				        {        
// 							alert("Your browser does not support AJAX!");        
// 							return false;        
// 						}      
// 				  }    
// 			 } 
// 			 xmlHttp.onreadystatechange=function()
// 		      {
// 			      if(xmlHttp.readyState==4)
// 			        {
// 						var rows="";
// 			        	rows=new String(xmlHttp.responseText);
// 						document.getElementById("txtAge").value=rows;
// 			        	//arr_row=rows.split(",");

// 			        	//document.getElementById("txtAdmissionFees").value=arr_row[4];
// 						//document.getElementById("txtTotal").value=arr_row[4];
// 			        	//document.getElementById("txtSecurityFeesAmount").value=arr_row[5];
// 						//CalculatTotal();
// 						//alert(rows);														
// 			        }
// 		      }
// 			var submiturl="CalculateAge.php?DateOfBirth=" + document.getElementById("txtDOB").value;
// 			xmlHttp.open("GET", submiturl,true);
// 			xmlHttp.send(null);
			
			
// }
</script>
<body>

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
<form method="POST" method ="post" action="" enctype="multipart/form-data">
<table border="1" width="100%" style="border-collapse: collapse" height="305">
	<tr>
		<td colspan="3" height="19" style="border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><img src="<?php echo $SchoolLogo; ?>" height="100px" width="400px"></font></td>
	</tr>
	<tr>
		<td colspan="3" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b><?php echo $SchoolAddress; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="3" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Phone No: <?php echo $SchoolPhoneNo; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="3" height="19" style="border-top-style: none; border-top-width: medium; border-bottom-style: none; border-bottom-width: medium">
		<p align="center"><font face="Cambria"><b>Email Id:info@dpsfsie.org <?php //echo $SchoolEmailId; ?></b></font></td>
	</tr>
	<tr>
		<td colspan="3" height="19" style="border-top-style: none; border-top-width: medium">
		<p align="center">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3" height="57">
		<p align="center">
		<font size="5"><span style="font-family: Cambria; font-weight: 700">
		Student Information Form</span><span style="font-family: 'Cambria'; font-weight: 700; ">
		(2022-2023)</span></font><font face="Cambria" size="4">
		</font>
		<p align="left" align="justify">&nbsp;</p>
		</td>
	</tr>
	
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">1.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Admission No.# : </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input onkeyup="upperCaseF(this)"  id="fname" name="sadmission" id="sadmission" type="text" readonly="readonly" value="<?php if($sadmission_nw != '') {echo $sadmission_nw;} else { echo $sadmission;}?>">
		</font> </td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">2.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Name of the student #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text  name="sname" onkeyup="upperCaseF(this)" placeholder="Studnet Name"  id="fname" value="<?php echo $sname;?>" readonly> 
		</font> </td>
	</tr>
	
	

	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<?php

            $cdate = '2022-03-31';

            $bday = new DateTime($DOB); // Your date of birth
            $today = new Datetime($cdate);
            $diff = $today->diff($bday);
            
              $year = $diff->y;
              $month = $diff->m;
              $day = $diff->d;

            // printf('%d years, %d month, %d days', $diff->y, $diff->m, $diff->d);
            //printf("\n");
            //exit();
    ?>
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">3.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Date of Birth #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input onkeyup="upperCaseF(this)"  id="fname" name="txtDOB" id="txtDOB"   type="text" readonly="readonly" value="<?php echo $DOB;?>"> 
	    <input onkeyup="upperCaseF(this)"  id="txtAge" name="txtAge"  type="text" readonly="readonly" value="<?php echo $year." year,"." ".$month." month,"." ".$day." day"?>"> 
		</font> </td>
	</tr>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">4.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Stage #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="sclass" onkeyup="upperCaseF(this)"   id="fname" value="<?php echo $sclass;?>" readonly> 
		</font> </td>
	</tr>
	
		</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">5.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Gender #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="gender" onkeyup="upperCaseF(this)"   id="fname" value="<?php echo $Sex;?>" readonly> 
		</font> </td>
	</tr>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">6.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Blood Group*: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"> 
		<select name="bloodgroup" onkeyup="upperCaseF(this)" placeholder="Blood Group"  id="bloodgroup" required>
		<option value="">Select One</option>
		<option value="O+">O+</option>
		<option value="O-">O-</option>
		<option value="A+">A+</option>
		<option value="A-">A-</option>
		<option value="B+">B+</option>
		<option value="B-">B-</option>
		<option value="AB+">AB+</option>
		<option value="AB-">AB-</option>
		
		</select>
		</font> </td>
	</tr>

		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">7.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Caste* ( General/ SC/ ST/ OBC 
		): </font></b></td>
		<td height="22"><font face="Cambria"> 
		<select name="Category" onkeyup="upperCaseF(this)" placeholder="Category"  id="Category" required>
		<option value="">Select One</option>
		<option value="General">General</option>
		<option value="SC">SC</option>
		<option value="ST">ST</option>
		<option value="OBC">OBC</option>
		
		</select>
		</font> </td>
			<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">8.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Religion* :</font></b></td>
		<td height="22"><font face="Cambria"> 
		<select name="Religion" onkeyup="upperCaseF(this)" placeholder="Religion"  id="Religion" required>
		<option value="">Select One</option>
		<option value="Hindu">Hindu</option>
		<option value="Sikh">Sikh</option>
		<option value="Jain">Jain</option>
		<option value="Christrian">Christrian</option>
		<option value="Muslim">Muslim</option>
		<!--<option value="Kashmiri">Kashmiri</option>-->
		<option value="Buddhist">Buddhist</option>
		<!--<option value="Indian">Indian</option>-->
		
		</select>
		</font> </td>
			<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
<!--
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">9.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Bus No.* :</font></b></td>
		<td height="22" width="31%"><font face="Cambria"> 
		
		<select name="routecode" id="routecode" required>

		<option selected="" value="">Select One</option>
		-->

		<?php

		while($row1 = mysqli_fetch_row($rsRoute))

		{

					$Route1=$row1[0];

		?>

		<option value="<?php echo $Route1; ?>" <?php if ($Route1==$Route) { ?> selected="selected" <?php } ?>><?php echo $Route1; ?></option>

		<?php

		}



		?>
<!--
		</select><br> * LIST OF ROUTE CLICK HERE (Own vehicle applicable if residing with in 0.5Km of school): <a href="" target=_blank>Route List</a></font> 
-->			
</font>
			
</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">9.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Father's&nbsp; Name #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="sfathername" onkeyup="upperCaseF(this)" placeholder="Father Name"  id="fname" value="<?php echo $sfathername;?>" readonly> 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">10.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's Designation :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="FatherDesignation" onkeyup="upperCaseF(this)"  value=" "  id="fname" required>&nbsp;&nbsp; </font></td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		    
			<p>&nbsp;</td></tr>


				
				<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">11.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's Office Address* :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="FatherOfficeAddress" onkeyup="upperCaseF(this)" placeholder=" Father Office Address "   id="fname" required></font></td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">12.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's&nbsp; Mobile No.*&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="FatherMobile" onkeyup="upperCaseF(this)" placeholder="Father Mobile No"  id="fname" required> 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">13.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's E-mail ID*&nbsp;&nbsp; :
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="FatherEmail"  placeholder="Your Email ID.."  id="fname" required> 
		</font> </td>
	</tr>
	
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<tr>
				
		<td height="22" width="5%" align="center"><b><font face="Cambria">14.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's Annual Income* :</font></b></td>
		<td height="22" width="63%" >
			<select  name="FatherAnnualIncome" id="FatherAnnualIncome" required>
				<option value="">Select One</option>
				<option value="20 Lacs and Above">20 Lacs and Above</option>
				<option value="15 Lacs to 20 Lacs">15 Lacs to 20 Lacs</option>
				<option value="10 Lacs to 15 Lacs">10 Lacs to 15 Lacs</option>
				<option value="6 Lacs to 10 Lacs">6 Lacs to 10 Lacs</option>
				<option value="4 Lacs to 6 Lacs">4 Lacs to 6 Lacs</option>
				<option value="2 Lacs to 4 Lacs">2 Lacs to 4 Lacs</option>
				<option value="Below 2 Lacs">Below 2 Lacs</option>
				<option value="NA">NA</option>
			</select>
		</td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td></tr>



				
	

	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">15.</font></b></td>
		<td height="22" ><b><font face="Cambria"> &nbsp;Mother's&nbsp; Name #: </font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherName" onkeyup="upperCaseF(this)" placeholder="MotherName"  id="fname" value="<?php echo $MotherName;?>" readonly> 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">16.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's Designation :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherDesignation" onkeyup="upperCaseF(this)" value=" "  id="fname" required ></font></td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	
	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">17.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's Office Address :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherOfficeAddress" onkeyup="upperCaseF(this)" placeholder=" Mother Office Address "  id="fname">&nbsp;&nbsp; 
		</font></td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">18.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's&nbsp; Mobile No*.&nbsp; 
		: 
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherMobileNo" onkeyup="upperCaseF(this)" placeholder="Mother Mobile No"  id="fname" required >
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</p></td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">19.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's E-mail ID&nbsp;&nbsp; :
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherEmail"  placeholder="Your Email ID.."  id="fname" > 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	



					<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">20.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's Annual Income :</font></b></td>
		<td height="22" width="63%" >
			<select  name="MotherAnnualIncome" id="MotherAnnualIncome" >
				<option value="">Select One</option>
				<option value="20 Lacs and Above">20 Lacs and Above</option>
				<option value="15 Lacs to 20 Lacs">15 Lacs to 20 Lacs</option>
				<option value="10 Lacs to 15 Lacs">10 Lacs to 15 Lacs</option>
				<option value="6 Lacs to 10 Lacs">6 Lacs to 10 Lacs</option>
				<option value="4 Lacs to 6 Lacs">4 Lacs to 6 Lacs</option>
				<option value="2 Lacs to 4 Lacs">2 Lacs to 4 Lacs</option>
				<option value="Below 2 Lacs">Below 2 Lacs</option>
				<option value="NA">NA</option>
			</select>
		</td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td></tr>
		

<!--<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">4.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Class taught : </font>
		</b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="classtaught" onkeyup="upperCaseF(this)" required> 
		</font> </td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>-->

	
	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">21.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Family Total Income* :</font></b></td>
		<td height="22" width="63%" >
			<select  name="FamilyAnnualIncome" id="FamilyAnnualIncome" required>
				<option value="">Select One</option>
				<option value="20 Lacs and Above">20 Lacs and Above</option>
				<option value="15 Lacs to 20 Lacs">15 Lacs to 20 Lacs</option>
				<option value="10 Lacs to 15 Lacs">10 Lacs to 15 Lacs</option>
				<option value="6 Lacs to 10 Lacs">6 Lacs to 10 Lacs</option>
				<option value="4 Lacs to 6 Lacs">4 Lacs to 6 Lacs</option>
				<option value="2 Lacs to 4 Lacs">2 Lacs to 4 Lacs</option>
				<option value="Below 2 Lacs">Below 2 Lacs</option>
				<option value="0_">0_</option>
			</select>
		</td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td></tr>

<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">22.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Residential Address* :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
		<font face="Cambria"><textarea rows="3" cols="30" name="Address"  onkeyup="upperCaseF(this)"   id="fname"  value="<?php echo $Address;?>"    required></textarea>
		</font>
		</font> </td>
	</tr>
<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">23.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mobile No. to receive SMS*&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MobileReceiveSMS" onkeyup="upperCaseF(this)" placeholder="Mobile No"  id="fname" required> 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

<tr>
    
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">24.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Email Id to receive mail from school*&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="EmailReceiveSMS"  placeholder="Email Id"  id="EmailReceiveSMS" required> 
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

<tr>
        
		<td height="22" width="5%" align="center"><b><font face="Cambria">25.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Sibling's Name(Studying in DPS Faridabad):</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
		<input type=text name="SiblingsName"  placeholder="Sibling's Name"  id="fname" onkeyup="upperCaseF(this)"> 
		</font>  
			
		</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">26.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Sibling's Class :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="SibilngsClass" onkeyup="upperCaseF(this)" placeholder="Sibling's Class"  id="fname" >
		</font> </td>
	</tr>


	
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>
	<tr>
        
		<td height="22" width="5%" align="center"><b><font face="Cambria">27.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Sibling's Name(Studying in School of International Education, DPS Faridabad):</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
		<input type=text name="SiblingsName"  placeholder="Sibling's Name"  id="fname" onkeyup="upperCaseF(this)"> 
		</font>  
			
		</td>
	</tr>
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

	<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">28.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Sibling's Stage :</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="SibilngsClass" onkeyup="upperCaseF(this)" placeholder="Sibling's Class"  id="fname" >
		</font> </td>
	</tr>


	
	<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>




<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">29.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Student's Aadhar Card No&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="StudentAadharNumber" onkeyup="upperCaseF(this)" placeholder="Student aadhar card"  id="fname">
		</font> </td>
	</tr>
	

		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">30.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father's Aadhar Card No&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="FatherAadharNumber" onkeyup="upperCaseF(this)" placeholder="Father aadhar card"  id="fname" >
		</font> </td>
	</tr>
		<tr>
		<td height="22" width="5%" align="center">&nbsp;</td>
		<td height="22" width="31%">
		
			<p>&nbsp;</td>
	</tr>

<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">31.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother's Aadhar Card No&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria"><input type=text name="MotherAadharNumber" onkeyup="upperCaseF(this)" placeholder="Mother aadhar card.."  id="fname" > 
		</font> </td>
</tr>

<!-- photo -->
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">32.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Student Photo* (Size of img less then 100 KB)&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
			<input type="hidden" name="student_photo_holder" id="student_photo_holder" value="">
			
			<!--<input type="file" class="photo_upload" name="student_photo" placeholder="Please select student photo"  id="student_photo"  data-per="student_photo_percent" data-which="student"  data-holder="student_photo_holder" required> -->
			
			<input type="file"  name="student_photo" placeholder="Please select student photo"  id="student_photo"  required> 
			
			<span id="student_photo_percent">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
		</font> </td>
</tr>

<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">33.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Father Photo* (Size of img less then 100 KB)&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
			<input type="hidden" name="father_photo_holder" id="father_photo_holder" value="">
			<input type="file"  name="father_photo" placeholder="Please select father photo"  id="father_photo" required>  
			<!--<input type="file" class="photo_upload" data-which="father" name="father_photo" placeholder="Please select father photo"  id="father_photo" data-per="father_photo_percent" data-holder="father_photo_holder" required>  -->

			<span id="father_photo_percent">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
		</font> </td>
</tr>
<tr>
		<td height="22" width="5%" align="center"><b><font face="Cambria">34.</font></b></td>
		<td height="22" ><b><font face="Cambria">&nbsp;Mother Photo* (Size of img less then 100 KB)&nbsp; 
		:
		</font></b></td>
		<td height="22" width="63%" ><font face="Cambria">
			<input type="hidden" name="mother_photo_holder" id="mother_photo_holder" value="">
			<!--<input type="file" class="photo_upload" data-which="mother" name="mother_photo" placeholder="Please select Mother photo"  id="mother_photo" data-per="mother_photo_percent"  data-holder="mother_photo_holder"  required> -->
			
			<input type="file"  name="mother_photo" placeholder="Please select Mother photo"  id="mother_photo"  required> 
			
			<span id="mother_photo_percent">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
		</font> </td>
</tr>
<!-- end photo -->

	

	<tr>
		
		<td height="22" width="37%" colspan=3 align=right>&nbsp;</td>
	</tr>
<tr>
	<td colspan="3" height="22" width="5%" align="center"><b>MEDICAL CARD OF THE CHILD</b></td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">1.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Medical Issue(Write NA if not applicable)&nbsp;* 
		:
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50" type=text name="illness"  id="illness" required> </textarea> 
		</font> </td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">2.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Surgery undergone in the past. If any, specify:&nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50" type=text name="Surgery"    id="Surgery" ></textarea> 
		</font> </td>
</tr>	
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">3.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Allergies, if any (Including Food Allergies):&nbsp; </font></b></td>
	<td height="22" width="5%" ><font face="Cambria"><textarea rows="4" cols="50"  type=text name="Allergies"    id="Allergies" ></textarea> 
		</font> </td>
</tr>
<!--<tr>-->
<!--	<td height="22" width="5%" align="center"><b><font face="Cambria">4.</font></b></td>-->
<!--	<td height="22" ><b><font face="Cambria">&nbsp;Food allergies that may require meal modification, if any (Relevant for -->
<!--	Nursery &amp; Prep) </font></b></td>-->
	
<!--	<td height="22" width="5%" ><font face="Cambria"><textarea rows="4" cols="50"  type=text name="Foodalleries"    id="Foodalleries" ></textarea> -->
<!--		</font> </td>-->
<!--</tr>-->


<tr>
	<td colspan="3" height="22" width="5%"><b><font face="Cambria">4. Immunizations :</font></b></td>

</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">I.</font></b></td>
	<td  height="22" width="5%"><b><font face="Cambria">Polio :</font></b></td>
	<td height="22" width="5%" ><font face="Cambria">

		<select name="Polio" id="Polio" >
		 	<option value="Yes">Yes</option>
		 	<option value="No">No</option>
	    </select> 
		</font> </td>

</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">II.</font></b></td>
	<td height="22" width="5%"><b><font face="Cambria">DPT :</font></b></td>
	<td height="22" width="5%" ><font face="Cambria">

		<select name="DPT" id="DPT">
		 	<option value="Yes">Yes</option>
		 	<option value="No">No</option>
	    </select> 
		</font> </td>

</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">III.</font></b></td>
	<td height="22" width="5%"><b><font face="Cambria">Measles :</font></b></td>
	<td height="22" width="5%" ><font face="Cambria">

		<select name="Measles" id="Measles">
		 	<option value="Yes">Yes</option>
		 	<option value="No">No</option>
	    </select> 
		</font> </td>

</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">IV.</font></b></td>
	<td  height="22" width="5%"><b><font face="Cambria"> (a)Tetanus :</font></b></td>
	<td height="22" width="5%" ><font face="Cambria">

		<select name="Tetanus" id="Tetanus">
		 	<option value="Yes">Yes</option>
		 	<option value="No">No</option>
	    </select> 
		</font> </td>

</tr>

<tr>
	<td height="22" width="5%" align="center">&nbsp;</td>
	<td height="22" ><b><font face="Cambria">&nbsp;(b)with date of last injection: &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><input  type="date" name="Tetinjections"    id="Tetinjections" >
		</font> </td>
</tr>


<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">V.</font></b></td>
	<td  height="22" width="5%"><b><font face="Cambria"> &nbsp;(a) Hepatitis B :</font></b></td>
	<td height="22" width="5%" ><font face="Cambria">

		<select name="Hepatitis" id="Hepatitis">
		 	<option value="Yes">Yes</option>
		 	<option value="No">No</option>
	    </select> 
		</font> </td>

</tr>
<tr>
	<td height="22" width="5%" align="center">&nbsp;</td>
	<td height="22" ><b><font face="Cambria">&nbsp;(b)with date of last injection: &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><input  type="date" name="injections"    id="injections" >
		</font> </td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">VI</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;(vi) Any other: (Hepatitis A, Chickenpox): &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50"  type=text name="other"   id="other" ></textarea> 
		</font> </td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">5.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Any other illness for which child
 is on regular medication(Write NA if not applicable) :  &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50" type=text name="medication"    id="medication" ></textarea> 
		</font> </td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">6.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Family Doctor's Name & Number :  &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50" type=text name="fmdocname"    id="fmdocname" ></textarea> 
		</font> </td>
</tr>
<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">7.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Any other remark :  &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria"><textarea rows="4" cols="50" type=text name="anyotherremarks"    id="anyotherremarks" ></textarea> 
		</font> </td>
</tr>

<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">8.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Have you suffered from Covid in last 2 years* :  &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria">
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Father&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="suff_covid_father" name ="suff_covid_father" required>
	    <option value= "">Select One ...</option>
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    </select>
	    <br/>
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Mother&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="suff_covid_mother" name ="suff_covid_mother" required>
	     <option value= "">Select One ...</option>
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    </select>
	    <br/>
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Child&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="suff_covid_child" name ="suff_covid_child" required>
	    <option value= "">Select One ...</option>     
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    </select>
	    <br/>
		</font> </td>
</tr>

<tr>
	<td height="22" width="5%" align="center"><b><font face="Cambria">9.</font></b></td>
	<td height="22" ><b><font face="Cambria">&nbsp;Have you been vaccinated against Covid*(Both Doses) :  &nbsp; 
		
		</font></b></td>
	<td height="22" width="63%" ><font face="Cambria">
	    
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Father&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="vacc_covid_father" name ="vacc_covid_father" required>
	    <option value= "">Select One ...</option>
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    
	    </select>  
	    <br/>
	    
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Mother&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="vacc_covid_mother" name ="vacc_covid_mother" required>
	    <option value= "">Select One ...</option>
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    </select>  
	    <br/>
	    
	    <b><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;Child&nbsp;&nbsp;&nbsp;&nbsp;</font></b>
	    <select id="vacc_covid_child" name ="vacc_covid_child" required>
	    <option value= "">Select One ...</option>
	    <option value= "Yes">Yes</option>
	    <option value= "No">No</option>
	    <option value= "NA">NA</option>
	    </select>  
	    <br/>
	    
	    
		</font> </td>
</tr>
	
</table>






        <p align="LEFT">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *<font face="Cambria"><b>
	 We hereby confirm that the above information is correct and no further 
		change(s) will be required.</b></font></p>
		 <p align="LEFT">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *<font face="Cambria"><b>
	 The fields marked * are mandatory.</b></font></p>



	<p align="center">
	<font face="Cambria">
	<input name="submit" type="submit" value="Submit" style="font-weight: 350" class="text-box" ></font></p>
</form>
<p align="center">&nbsp;</p>
</form>
<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.photo_upload').change(function(){
			var types = $(this).attr('data-which');
		var percent = $(this).attr('data-per');
		var holder = $(this).attr('data-holder');

		percent = $('#'+percent);
	if (types == '') {
		alert('please reload the page again');
		$(this).val(null);
		return false;
	}else{
     var confirm_alert = confirm('Are you sure you want to upload this photo');
    if (confirm_alert) {
    var count = '';
	var files = $(this)[0].files[0];
	var reader = new FileReader();
	  reader.onloadend = function() {
var formData = new FormData();
formData.append('image', reader.result);
formData.append('type', types);
formData.append('upload_image', 'upload_image');

	  $.ajax({
	  	url:'upload_image.php',
	  	xhr: function() {
			        var xhr = $.ajaxSettings.xhr();
			        xhr.upload.onprogress = function(e) {
		        	count = Math.floor(e.loaded / e.total *100) + '%';
		        	percent.html(count);
			        };
			        return xhr;
			    },
	  	type:'post',
	  	cache: false,
	    contentType: false,
	    processData: false,
	  	data:formData,
	  	dataType:"JSON",
	  	success:function(response){
	  		 if (response.status == true) {
	  		 	$('#'+holder).val(response.extra.img);
	  		 }
	  		 alert(response.info);
	  		 
	  	}//end of success function

	  });//end of ajax
	  }//end of onloaded function
	  reader.readAsDataURL(files);
     	}//if confirm
     	else{
     		$(this).val(null);
     	}
			}//end of else
});//end if onchange function	


	});//end of ready
</script>
</body>

</html>