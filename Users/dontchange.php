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
   <div class="container-fluid page-border">
      <div class="row">
         <div class="col-12 text-center bg-primary text-white">
            <h4 class="m-t5"><i class="fas fa-rupee-sign"></i> MyFees</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-6 col-xl-6 page-border">
            <h6><i class="fas fa-rupee-sign"></i> Payment</h6>
            <div class="table-responsive">
               <table class="table customTab">
                  <thead>
                     <tr class="bg-primary text-white">
                        <th>All</th>
                        <th>Month</th>
                        <th>Total Dr.</th>
                        <th>Total Cr.</th>
                        <th>Total Bl.</th>
                        <th>Late Fee</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td><input type="checkbox"></td>
                        <td>col-1</td>
                        <td>col-2</td>
                        <td>col-3</td>
                        <td>col-4</td>
                        <td>col-5</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-6 col-xl-6 page-border">
            <h6><i class="fas fa-history"></i> Payment History</h6>
            <div class="table-responsive">
               <table class="table customTab">
                  <thead>
                     <tr class="bg-primary text-white">
                        <th>Month</th>
                        <th>Receipt#</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td>col-1</td>
                        <td><i class="fas fa-print"></i> col-2</td>
                        <td>col-3</td>
                        <td>col-4</td>
                        <td>col-5</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-6 col-xl-6 page-border m-t30">
            <h6 class="bg-primary text-white p-10"><i class="fas fa-info-circle"></i> Beneficiary Detail</h6>
            <div class="table-responsive">
               <table class="table customTab">
                  <tr>
                     <th>Beneficiary A/C No</th>
                     <td>...</td>
                  </tr>
                  <tr>
                     <th>Bank Name</th>
                     <td>...</td>
                  </tr>
                  <tr>
                     <th>Beneficiary Name</th>
                     <td>...</td>
                  </tr>
                  <tr>
                     <th>IFSC Code</th>
                     <td>...</td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <div class="row">
          <div class="col-12">
              <h4 class="text-danger font-italic m-t30">*Please wait for 48 hrs before making next transaction, if fees amount is already deducted from your Credit Card/Debit Card/Net Banking or any other payment mode...</h4>
          </div>
          <div class="col-12">
              <p class="m-t10"><a href="#" class="font-italic"><u>Click here to read Terms & Conditions and Privacy Policy</u></a> for online fees payment</p>
              <p class="font-italic ">- Please call at School Reception for further details</p>
              <button class="btn btn-primary">Click here to Download the Previous Fees Certificate</button>
          </div>
      </div>
   </div>
</main>
<!--end page contents-->
</div>
</body>
</html>





<!-- The Modal -->
  <div class="modal fade editIcon">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Attendance</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <p class="show_attendance"></p>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>