<?php include '../connection.php';?>
<?php include '../AppConf.php';?>

<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$StudentClass = $_SESSION['StudentClass'] ?? '';
$class = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$sadmission = $_SESSION['userid'] ?? '';
?>
<?php
$NewsId=$_REQUEST["txtCircularId"] ?? '';
// Initialize variables
$SrNo = '';
$datetime = '';
$notice = '';
$noticetitle = '';

$ssql="select `srno`,DATE_FORMAT(`NoticeDate`,'%d-%b-%Y') as `datetime`,`notice`,`noticetitle` from `student_notice` where  `srno`='$NewsId'";
mysqli_set_charset($Con, 'utf8');
$rs= mysqli_query($Con, $ssql);
if ($rs && mysqli_num_rows($rs) > 0) {
    while($row = mysqli_fetch_row($rs))
    {
        $SrNo=$row[0];
        $datetime=$row[1];
        $notice=$row[2] ?? '';
        $noticetitle=$row[3] ?? '';
    }
}
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
            <h4 class="m-t5"><i class="fas fa-calendar-alt"></i> VIEW CIRCULAR  </h4>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border">
           <div class="row">
               <div class="col-12">
                   <h5 style="text-align:center"><?php echo $noticetitle; ?></h5>
                   <h5 style="font-size:15px;text-align:center;"><i class="far fa-calendar-alt"></i><?php echo $datetime; ?></h5>
                   <h4 style="text-align:center;font-size:15px;font-weight:600;margin-top:20px;"><?php echo $SchoolName;?></h4>
                   <?php if (isset($SchoolAddress2) && !empty($SchoolAddress2)): ?>
                   <h4 style="text-align:center;font-size:15px;font-weight:600;"><?php echo $SchoolAddress2;?></h4>
                   <?php endif; ?>
                   
                   <?php if (!empty($notice)): ?>
                   <div style="margin-top:50px;padding:20px;background-color:#f8f9fa;border-radius:5px;">
                       <p style="text-align:left;line-height:1.6;"><b><?php echo nl2br(htmlspecialchars($notice));?></b></p>
                   </div>
                   <?php else: ?>
                   <div style="margin-top:50px;padding:20px;background-color:#fff3cd;border-radius:5px;border:1px solid #ffc107;">
                       <p style="text-align:center;color:#856404;"><i class="fas fa-info-circle"></i> No additional details available for this circular.</p>
                   </div>
                   <?php endif; ?>
                 
            
            </div>
           </div>
           
           
           
         </div>
      </div>
     
   </div>
</main>
<!--end page contents-->
</div>
</body>
</html>



