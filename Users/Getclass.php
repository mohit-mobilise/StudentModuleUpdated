<?php include '../connection.php';?>
<?php
session_start();
$year=$_REQUEST["year"];
$sadmission=$_REQUEST["sadmission"];



	$ssqlName="SELECT distinct `previous_sclass`,`sclass` FROM `student_master`  Where `sadmission`='$sadmission'";
		// echo $ssqlName;
		// exit();
$rsDetail= mysqli_query($Con, $ssqlName);
$sstr="";

while($row = mysqli_fetch_assoc($rsDetail))
{
  $previous_masterclass=$row['previous_sclass'];
  $MasterClass=$row['sclass'];
  if ($year=="2021") {
  	$data=$MasterClass;
  }
    if ($year=="2019") {
  	$data=$previous_masterclass;
  }
  

$sstr=$sstr.$data.",";
}

//echo substr($sstr,strlen($sstr)-1);
echo  substr($sstr,0,strlen($sstr)-1);
?>