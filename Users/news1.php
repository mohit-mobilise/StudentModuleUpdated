

<?php include '../connection.php';?>
<?php
session_start();
	$StudentClass = $_SESSION['StudentClass'];
	$class = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
  $sadmission=$_SESSION['userid'];

$pr_contracted_breakup = attendance_graph($Con,$sadmission);


function  attendance_graph($Con,$sadmission)
{
    $class = $_SESSION['StudentClass'];
    $sadmission=$_SESSION['userid'];

    
	$queryItem = mysqli_query($Con, "SELECT distinct DATE_FORMAT(`attendancedate`,'%M-%Y') as `monthly_attendance`,count(CASE WHEN `attendance` IN ('P','A')THEN 1 END ) as `total_attendance`,count(CASE WHEN `attendance`='P'THEN 1 END ) as `present_attendance`,count(CASE WHEN `attendance`='A'THEN 1 END ) as `absent_attendance`,count(CASE WHEN `attendance`='L'THEN 1 END ) as `leave_attendance`, DATE_FORMAT(`attendancedate`,'%Y-%m') as `full_monthly_attendance`  FROM `attendance` where DATE_FORMAT(`attendancedate`,'%M-%Y') !='NULL' and `sclass`='$class' and `sadmission`='$sadmission'  group by DATE_FORMAT(`attendancedate`,'%M-%Y') ORDER BY DATE_FORMAT(`attendancedate`,'%Y'),DATE_FORMAT(`attendancedate`,'%m')");
	
	
	
	$rowItem = mysqli_fetch_row($queryItem);
	$attendancedt = $rowItem[0];
	$totalworkingdays=$rowItem[1];
	$present=$rowItem[2];
	$absent=$rowItem[3];
	
	
	$data = array(array('name'=>'Total Working Days','total'=>$totalworkingdays),array('name'=>'Present','total'=>$present), array('name'=>'Absent','total'=>$absent));
	
	return $data;
}



$ssql="SELECT distinct DATE_FORMAT(`attendancedate`,'%M-%Y') as `monthly_attendance`,count(CASE WHEN `attendance` IN ('P','A')THEN 1 END ) as `total_attendance`,count(CASE WHEN `attendance`='P'THEN 1 END ) as `present_attendance`,count(CASE WHEN `attendance`='A'THEN 1 END ) as `absent_attendance`,count(CASE WHEN `attendance`='L'THEN 1 END ) as `leave_attendance`, DATE_FORMAT(`attendancedate`,'%Y-%m') as `full_monthly_attendance`  FROM `attendance` where DATE_FORMAT(`attendancedate`,'%M-%Y') !='NULL' and `sclass`='$class' and `sadmission`='$sadmission'  group by DATE_FORMAT(`attendancedate`,'%M-%Y') ORDER BY DATE_FORMAT(`attendancedate`,'%Y'),DATE_FORMAT(`attendancedate`,'%m')";

$rs= mysqli_query($Con, $ssql);

$num_rows=0;



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
  <link rel="stylesheet" type="text/css" href="../chart/Chart.min.css">

</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:45px;">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 text-center bg-primary text-white">
            <h4 class="m-t5"><i class="far fa-newspaper"></i> SCHOOL NEWS</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border">
             <h1>News</h1>
           
         </div>
      </div>
     
   </div>
</main>
<!--end page contents-->
</div>
</body>
</html>



