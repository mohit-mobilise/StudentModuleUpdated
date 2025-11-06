<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

include '../connection.php';
include '../AppConf.php';

$EmpId = $_SESSION['userid'] ?? '';
if($EmpId == "")
{
	echo "<br><br><center><b>Your Session has been expired<br>Please click <a href='index.php'>here</a> to login again";
	exit();
}

$ssqlCFY="SELECT distinct `year`,`financialyear` FROM `FYmaster` where `Status`='Active'";
$rsCFY= mysqli_query($Con, $ssqlCFY);
$row = mysqli_fetch_row($rsCFY);
$CurrentFinancialYear = $row[0];
$CurrentFinancialYearName=$row[1];					

$currentdate = date("Y-m-d");

// Validate and sanitize all inputs
$StudentAdmission = validate_input($_REQUEST['txtsadmission'] ?? '', 'string', 50);
$StudentName = validate_input($_REQUEST['txtsname'] ?? '', 'string', 100);
$Class = validate_input($_REQUEST['txtSelectedClass'] ?? '', 'string', 20);
$SelectedExamType = validate_input($_REQUEST['txtSelectedTestType'] ?? '', 'string', 100);
$SelectedIndicator = validate_input($_REQUEST['txtSelectedIndicator'] ?? '', 'string', 100);
$SelectedSubIndicator = validate_input($_REQUEST['txtSubIndicator'] ?? '', 'string', 100);
$totalrow = validate_input($_REQUEST['totalrow'] ?? '', 'int', 11);

// Use prepared statement to get exam code
$stmt = mysqli_prepare($Con, "SELECT exam_code FROM exam_type WHERE examtype=? AND status='Active'");
$exa_code = '';
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $SelectedExamType);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($fetchrow = mysqli_fetch_array($result)) {
        $exa_code = $fetchrow[0];
    }
    mysqli_stmt_close($stmt);
}

for($i=1;$i<=$totalrow;$i++){
    // Validate and sanitize inputs
    $ind_desc = validate_input($_REQUEST['Indicator'.$i] ?? '', 'string', 500);
    $input_data = validate_input($_POST['cboIndicatorGrade'.$i] ?? '', 'string', 500);
    $trimdata = trim($input_data);

    if(($_POST['cboIndicatorGrade'.$i.$i] ?? '') != 'checkbox'){
        if($trimdata == ''){
            continue;
        }
    }

    $otherspeify = validate_input($_REQUEST['otherspeify'] ?? '', 'string', 500);

    if($input_data == 'Other subject areas:specify:'){
        if(empty($otherspeify)){
            continue;
        }
    } else if(stripos($input_data, 'other') !== false){
        // Keep otherspeify as is
    } else {
        $otherspeify = '';
    }

    // Use prepared statement to check if record exists
    $stmt_check = mysqli_prepare($Con, "SELECT * FROM `exam_indicator_entry` WHERE `sadmission`=? AND `exam_type`=? AND indicator_desc=? AND subindicator=? AND indicatortype=? AND sclass=? AND FinancialYear=?");
    if ($stmt_check) {
        mysqli_stmt_bind_param($stmt_check, "sssssss", $StudentAdmission, $SelectedExamType, $ind_desc, $SelectedSubIndicator, $SelectedIndicator, $Class, $CurrentFinancialYear);
        mysqli_stmt_execute($stmt_check);
        $rsCheck = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($rsCheck) == 0) {
            // INSERT using prepared statement
            $stmt_insert = mysqli_prepare($Con, "INSERT INTO `exam_indicator_entry`(`sadmission`, `sname`, `sclass`, `exam_type`, `grade`, `entrydate`, `UploadedBy`, `FinancialYear`, `indicatortype`, `subindicator`, `indicator_desc`, `exam_code`, `anyother`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            if ($stmt_insert) {
                mysqli_stmt_bind_param($stmt_insert, "sssssssssssss", $StudentAdmission, $StudentName, $Class, $SelectedExamType, $input_data, $currentdate, $EmpId, $CurrentFinancialYear, $SelectedIndicator, $SelectedSubIndicator, $ind_desc, $exa_code, $otherspeify);
                $output = mysqli_stmt_execute($stmt_insert);
                $msg = "Indicator have been uploaded successfully";
                mysqli_stmt_close($stmt_insert);
            }
        } else {
            // UPDATE using prepared statement
            $stmt_update = mysqli_prepare($Con, "UPDATE `exam_indicator_entry` SET `grade`=?, `sname`=?, `sclass`=?, `exam_type`=?, `FinancialYear`=?, `indicatortype`=?, `subindicator`=?, `indicator_desc`=?, `anyother`=? WHERE `sadmission`=? AND `exam_type`=? AND indicator_desc=? AND subindicator=? AND indicatortype=? AND sclass=? AND FinancialYear=?");
            if ($stmt_update) {
                mysqli_stmt_bind_param($stmt_update, "sssssssssssssss", $input_data, $StudentName, $Class, $SelectedExamType, $CurrentFinancialYear, $SelectedIndicator, $SelectedSubIndicator, $ind_desc, $otherspeify, $StudentAdmission, $SelectedExamType, $ind_desc, $SelectedSubIndicator, $SelectedIndicator, $Class, $CurrentFinancialYear);
                $output = mysqli_stmt_execute($stmt_update);
                $msg = "Indicator have been updated successfully";
                mysqli_stmt_close($stmt_update);
            }
        }
        
        mysqli_stmt_close($stmt_check);
    }
}

if($output){
    echo json_encode(['status'=>'success','msg'=>$msg]);
}else{
    echo json_encode(['status'=>'failed','msg'=>$msg]);
}

	

?>