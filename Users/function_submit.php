<?php include '../connection.php';
include '../AppConf.php';
session_start();
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	
?>
<?php

function data()
{
   
 $sadmission=$_SESSION['userid'];
   $name=$_POST['name'];
   $stage=$_POST['stage'];
   $adm=$_POST['adm'];
   $transport_req=$_POST['transport_req'];
   $meal=$_POST['meal'];
   $Second=$_POST['Second'];
   $Second2=$_POST['Second2'];
   $Second3=$_POST['Second3'];
   $social_science=$_POST['social_science'];

   
  
   $rsChk=mysqli_query($Con, "select * from consent where `adm`='$sadmission'");
   if(mysqli_num_rows($rsChk)>0)
   {
   		$Msg="Already Submitted!";
   }
   else
   {
	   mysqli_query($Con, "INSERT INTO consent ( `name`, `stage`, `adm`, `transport_required`, `meal_from_school`, `second_language`, `social_science_other_than_global`, `second_language_2`,`second_language_3`) VALUES ('$name','$stage','$adm','$transport_req', '$meal','$Second' ,'$social_science','$Second2','$Second3' )");
	   $Msg="Submitted Successfully!";
	}
   echo json_encode(array('status'=>'true' , 'info'=>$Msg));


}
 data();
?>