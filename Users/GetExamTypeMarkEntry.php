<?php include '../connection.php';?>
<?php
$class1=$_REQUEST["Class"];
$sqlex = "SELECT distinct `a`.`subject`, `a`.`subject` FROM `assignment` as `a`  WHERE `class`='$class1'";
$rs = mysqli_query($Con, $sqlex);
$sstr="";
while($row = mysqli_fetch_row($rs))
{
$sstr=$sstr.$row[0].",".$row[1].",";
}

echo  substr($sstr,0,strlen($sstr)-1);
?>