<?php 
session_start();
include '../connection.php';

$year=$_REQUEST["year"];
$sadmission=$_REQUEST["sadmission"];

$ssqlName="SELECT distinct `previous_masterclass`,`MasterClass` FROM `student_master`  Where `sadmission`='$sadmission'";
$rsDetail= mysqli_query($Con, $ssqlName);
$sstr="";

$ssqlCFY=mysqli_query($Con, "SELECT distinct `year` FROM `FYmaster` where `Status`='Active'");
$row = mysqli_fetch_row($ssqlCFY);
$CurrentFinancialYear = $row[0];
$previousYear=$CurrentFinancialYear-1;

while($row = mysqli_fetch_assoc($rsDetail))
{
  $previous_masterclass=$row['previous_masterclass'];
  $MasterClass=$row['MasterClass'];
  if ($year==$CurrentFinancialYear) {
  	$data=$MasterClass;
  }
    if ($year==$previousYear) {
  	$data=$previous_masterclass;
  }
  

$sstr=$sstr.$data.",";
}

//echo substr($sstr,strlen($sstr)-1);
echo  substr($sstr,0,strlen($sstr)-1);
?>