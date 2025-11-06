<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php 
if ($_REQUEST["txtName"] != "")
{
    $Name=$_REQUEST["txtName"];
	$MotherName=$_REQUEST["txtMotherName"];
	$FatherName=$_REQUEST["txtFatherName"];
	$Mobile=$_REQUEST["txtmobile"];
	$Email=$_REQUEST["txtEmail"];
	$OptionalSubject=$_REQUEST["cboSubject"];
	$Address=$_REQUEST["txtAddress"];
	$Class=$_REQUEST["cboClass"];
    $DOB=$_REQUEST["txtDOB"];
    $currentdatetime=Date("Y-m-d h:i:sa");
    $sadmission=$_SESSION['userid'];
    
    $cboAddSubject=$_REQUEST["cboAddSubject"];
     $cboskillSubject=$_REQUEST["cboskillSubject"];

    $rsChk=mysqli_query($Con, "select * from `StudentInfo_Class8` where `sadmission`='$sadmission' ");
if(mysqli_num_rows($rsChk)>0)
{ 


	    	mysqli_query($Con, "UPDATE `StudentInfo_Class8` SET `OptionalSubject`='$OptionalSubject',`cboAddSubject`='$cboAddSubject',`cboskillSubject`='$cboskillSubject' WHERE `sadmission`='$sadmission'");
	
}
else
{


		mysqli_query($Con, "INSERT INTO `StudentInfo_Class8`(`sadmission`, `sname`, `sclass`, `sfathername`, `smothername`, `smobile`, `email`, `OptionalSubject`, `Address`,`cboAddSubject`,`cboskillSubject`) VALUES('$sadmission','$Name','$Class','$FatherName','$MotherName','$Mobile','$Email','$OptionalSubject','$Address','$cboAddSubject','$cboskillSubject')");
				
	}
		
}
?>
<script language="javascript">
	function printDiv() 
	{

        //Get the HTML of div

        var divElements = document.getElementById("MasterDiv").innerHTML;

        //Get the HTML of whole page

        var oldPage = document.body.innerHTML;



        //Reset the page's HTML with div's HTML only

        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";



        //Print Page

        window.print();



        //Restore orignal HTML

        document.body.innerHTML = oldPage;

 	}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled 1</title>
</head>

<body>
<div id="MasterDiv">
<style>
<!--
.style4 {
	text-align: left;
}

.style4 {
	text-align: left;
}
.style5 {
	font-family: Cambria;
}
-->
</style>

	<p align="center">

	 <div class="style1" align="center"><font size="3"><strong>
	             <br>
	             <br>
	             <br>
	             <br>
	             Student Information has been Successfully Updated !
	             
	             Click <b><a href="Notices.php">here</a></b> to go back </strong></font></div>

</div>
</body>
<div id="divPrint">
	
	<p align="center">
	<font face="Cambria">
	
</font> 
</div>
</html>