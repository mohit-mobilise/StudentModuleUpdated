<?php 


include '../connection.php';

// `sname`,`DOB`,`Sex`,`MotherTongue`,`Nationality`,`sclass``permanent_address`,`Address`,`Location`,`TransportAvail`,`routeno`,`AadharNumber`,`Category`,`Religion` 


// `sfathername`,`FatherEducation`,`FatherQualificationDuration`,`FatherOccupation`,`FatherDesignation`,`FatherAnnualIncome`,`FatherCompanyName`,`FatherOfficeAddress`,`FatherOfficePhoneNo`,`FatherAdharCardNo`,`FamilyAnnualIncome`,`father_pan_card_no`

// `MotherName`,`MotherEducation`,`MotherQualificationDuration`,`MotherOccupatoin`,`MotherDesignation`,`MontherAnnualIncome`,`mother_pan_card_no`,`MotherCompanyName`,`MotherOfficeAddress`,`MotherOfficePhone`,`MotherAdharCardNo`

// `GuradianName`,`GuradianAge`,`GuradinaEducation`,`GuradianOccupation`,`GuradianDesignation`,`GuradianCompanyName`,`GuradianOfficialAddress`,`GuradianOfficialPhNo`

//`Sibling`,`Father_DPS_Alumni`,`Mother_DPS_Alumni`,`Single_Parent`,`Special_Needs`,`Staff`,`EWSCategory`,`OtherCategory`

// `RealBroSisAdmissionNo`,`RealBroSisName`,`RealBroSisClass`

//`AlumniFatherPassingClass`,`AlumniPassingYear`,`AlumniMotherPassingClass`,`AlumniMotherPassingYear`

//`PhoneNo`,`FatherMobileNo`,`MotherMobile`,`smobile``email`,`MotherEmail`


$total_record = $_POST['total_record'];
$column_data = '';
for ($i=0; $i <= $total_record ; $i++) { 
	$fields = "txtFieldValue_".$i;
	$field_data = $_POST[$fields];

	$column_data .= "'$field_data',";
}



$trimed_val = rtrim($column_data, ',');
$trimed = substr(trim($trimed_val), 0, -3);

$adm = $_POST['adm_no'];

$fatherPhoto = $_POST['father_photo'];
$motherPhoto= $_POST['mother_photo'];
$profilePhoto = $_POST['student_photo'];
$driverPhoto = $_POST['driver_photo'];
$guardianPhoto = $_POST['guardian_photo'];



date_default_timezone_set('Asia/Kolkata');
$current_date = date('Y-m-d');

$check1 = mysqli_query($Con, "SELECT `sadmission` FROM `form_field_data` WHERE `sadmission` = '$adm'");
$count1 = mysqli_num_rows($check1);

if ($count1 > 0 ) {
	// echo '<script>
	// alert("Your Information have already been submitted");
	// window.location.href = "landing_page.php";
	// </script>';
	$delete = mysqli_query($Con, "DELETE FROM  `form_field_data` WHERE  `sadmission` =  '$adm'");
	if ($delete) {


$q = "INSERT INTO `form_field_data`(`sadmission`,`form_submit_date`,`sname`,`DOB`,`Sex`,`MotherTongue`,`Nationality`,`sclass`,`permanent_address`,`Address`,`Location`,`TransportAvail`,`routeno`,`AadharNumber`,`Category`,`Religion`,`sfathername`,`FatherEducation`,`FatherOccupation`,`FatherDesignation`,`FatherAnnualIncome`,`FatherCompanyName`,`FatherOfficeAddress`,`FatherOfficePhoneNo`,`FatherAdharCardNo`,`FamilyAnnualIncome`,`father_pan_card_no`,`MotherName`,`MotherEducation`,`MotherOccupatoin`,`MotherDesignation`,`MontherAnnualIncome`,`mother_pan_card_no`,`MotherCompanyName`,`MotherOfficeAddress`,`MotherOfficePhone`,`MotherAdharCardNo`,`GuradianName`,`GuradianAge`,`GuradinaEducation`,`GuradianOccupation`,`GuradianDesignation`,`GuradianCompanyName`,`GuradianOfficialAddress`,`GuradianOfficialPhNo`,`Sibling`,`Father_DPS_Alumni`,`Mother_DPS_Alumni`,`Single_Parent`,`Special_Needs`,`Staff`,`EWSCategory`,`OtherCategory`,`RealBroSisAdmissionNo`,`RealBroSisName`,`RealBroSisClass`,`AlumniFatherPassingClass`,`AlumniPassingYear`,`AlumniMotherPassingClass`,`AlumniMotherPassingYear`,`PhoneNo`,`FatherMobileNo`,`MotherMobile`,`smobile`,`email`,`MotherEmail`,`license_no`,`driver_aadhar_no`,`driver_name`,`van_no`,`driver_address`,`FatherPhoto`,`MotherPhoto`,`ProfilePhoto`,`DriverPhoto`,`GuardianPhoto`) VALUES ('$adm','$current_date',$trimed,'$fatherPhoto','$motherPhoto','$profilePhoto','$driverPhoto','$guardianPhoto')"; 



		$run = mysqli_query($Con, $q);//query end

		if ($run) {
			echo '<script>
			alert("Your Information Has Been Renewed");
		           window.location.href = "landing.php";
			</script>';
		}else
		{
			echo '<script>
			alert("Your Information Did Not Renewed");
		window.location.href = "student_form.php";
			</script>';

			
		}
		
	}//$delete data 
}//check data avalil or not
else
{
	// if data not available then create new entry 

$q = "INSERT INTO `form_field_data`(`sadmission`,`form_submit_date`,`sname`,`DOB`,`Sex`,`MotherTongue`,`Nationality`,`sclass`,`permanent_address`,`Address`,`Location`,`TransportAvail`,`routeno`,`AadharNumber`,`Category`,`Religion`,`sfathername`,`FatherEducation`,`FatherOccupation`,`FatherDesignation`,`FatherAnnualIncome`,`FatherCompanyName`,`FatherOfficeAddress`,`FatherOfficePhoneNo`,`FatherAdharCardNo`,`FamilyAnnualIncome`,`father_pan_card_no`,`MotherName`,`MotherEducation`,`MotherOccupatoin`,`MotherDesignation`,`MontherAnnualIncome`,`mother_pan_card_no`,`MotherCompanyName`,`MotherOfficeAddress`,`MotherOfficePhone`,`MotherAdharCardNo`,`GuradianName`,`GuradianAge`,`GuradinaEducation`,`GuradianOccupation`,`GuradianDesignation`,`GuradianCompanyName`,`GuradianOfficialAddress`,`GuradianOfficialPhNo`,`Sibling`,`Father_DPS_Alumni`,`Mother_DPS_Alumni`,`Single_Parent`,`Special_Needs`,`Staff`,`EWSCategory`,`OtherCategory`,`RealBroSisAdmissionNo`,`RealBroSisName`,`RealBroSisClass`,`AlumniFatherPassingClass`,`AlumniPassingYear`,`AlumniMotherPassingClass`,`AlumniMotherPassingYear`,`PhoneNo`,`FatherMobileNo`,`MotherMobile`,`smobile`,`email`,`MotherEmail`,`license_no`,`driver_aadhar_no`,`driver_name`,`van_no`,`driver_address`,`FatherPhoto`,`MotherPhoto`,`ProfilePhoto`,`DriverPhoto`,`GuardianPhoto`) VALUES ('$adm','$current_date',$trimed,'$fatherPhoto','$motherPhoto','$profilePhoto','$driverPhoto','$guardianPhoto')"; 


		$run = mysqli_query($Con, $q);//query end

		if ($run) {
			echo '<script>
			alert("Your Information Has Been submitted");
		           window.location.href = "landing.php";
			</script>';
		}else
		{
			echo '<script>
			alert("Your Information Did Not submitted");
		window.location.href = "student_form.php";
			</script>';

		
		}

}//else








 ?>