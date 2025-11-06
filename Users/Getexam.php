<?php include '../connection.php';?>
<?php
session_start();

$Class=$_REQUEST["class"];
$fyear=$_REQUEST["fyear"];

$sqlMasterClass = mysqli_query($Con, "SELECT `MasterClass` FROM `class_master` WHERE `class`='$Class'");
$rsMasterClass = mysqli_fetch_row($sqlMasterClass);
$MasterClass = $rsMasterClass[0];


	// $ssqlName="SELECT distinct `exam_type` FROM `exam_report_card`  Where `master_class`='$MasterClass' and `fyear`='$fyear' and `status`='Active'";

$ssqlName="SELECT distinct `exam_type` FROM `exam_report_card`  Where `master_class`='$MasterClass' and `fyear`='$fyear' and `status`='Active'";
		// echo $ssqlName;
		// exit();
$rsDetail= mysqli_query($Con, $ssqlName);
$sstr="";

while($row = mysqli_fetch_assoc($rsDetail))
{
  $exam_type=$row['exam_type'];
$sstr=$sstr.$exam_type.",";
}
//echo substr($sstr,strlen($sstr)-1);
echo  substr($sstr,0,strlen($sstr)-1);
?>