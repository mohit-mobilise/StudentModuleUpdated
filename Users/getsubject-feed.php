<?php 
session_start();
include '../connection.php';
$class=$_REQUEST["Class"];
$ExamType=$_REQUEST["TestType"];
$EmpId=$_SESSION['userid'];
$IndicatorType=$_REQUEST["IndicatorType"];
$category=$_REQUEST["category"];
$subcategory=$_REQUEST["subcategory"];

$ssql="SELECT distinct indicator_subcat FROM `hcp_class_indicator_mapping` where exam_type='$ExamType' and sclass='$class' and r_type='hcp' and indicator_type='$category'";
$rsDetail= mysqli_query($Con, $ssql);
$sstr="";
while($row = mysqli_fetch_row($rsDetail))
{
$sstr=$sstr.$row[0].",".$row[0].",";
}
echo  substr($sstr,0,strlen($sstr)-1);
exit; 


?>