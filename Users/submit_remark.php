<?php require '../connection.php'; ?>
<?php require '../AppConf.php';?>
<?php
session_start(); 

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$remark1 = $_REQUEST["remark1"] ?? '';
$remark2 = $_REQUEST["remark2"] ?? '';

$sqlf=mysqli_query($Con, "SELECT  `year` FROM `FYmaster` WHERE `Status`='Active'");
$row=mysqli_fetch_row($sqlf);
$year=$row[0];
$sql_data=mysqli_query($Con, "SELECT `srno`, `sadmission`, `sclass`, `remarks`, `exam_type`, `financialyear`, `systemdatetime`, `submittedby`, `remark2`, `remark3`, `remark4`, `type` FROM `exam_remark` WHERE `sadmission`='$StudentId' AND `type`='student' and `financialyear`='$year' and `exam_type`='SEM2'");
if(mysqli_num_rows($sql_data)==0)
{
  
   $sqinsert= mysqli_query($Con, "Insert into exam_remark(`sadmission`, `sclass`, `remarks`, `exam_type`, `financialyear`, `remark2`,`type`)values('$StudentId','$StudentClass','$remark1','SEM2','$year' ,'$remark2','student')");
   $message="Data Inserted Successfully";
    
}
else
{
   
   $sqinsert= mysqli_query($Con, "Update `exam_remark` SET `remarks`='$remark1'  , `remark2`='$remark2' WHERE `sadmission`='$StudentId' AND `type`='student' and `financialyear`='$year' and `exam_type`='SEM2'");    
     $message="Data Updated Successfully";
}
   echo "<!DOCTYPE html><html><head><title>Success</title>";
   echo "<link rel='stylesheet' type='text/css' href='../assets/global/plugins/bootstrap-toastr/toastr.min.css'>";
   echo "<link rel='stylesheet' type='text/css' href='assets/css/toastr-custom.css'>";
   echo "<script src='../assets/global/plugins/bootstrap-toastr/toastr.min.js'></script>";
   echo "<script src='assets/js/toastr-config.js'></script>";
   echo "</head><body>";
   echo "<script type='text/javascript'>toastr.success('$message', 'Success'); setTimeout(function() { window.location.href = 'https://dpsfsis.com/Users/StudentRemark.php'; }, 1500);</script>";
   echo "</body></html>";


    
?>	