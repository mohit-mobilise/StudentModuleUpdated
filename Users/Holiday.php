<?php require '../connection.php'; ?>
<?php require '../AppConf.php';
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
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $SchoolName;?> ||Holiday</title>
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
    
    <!-- Inline script to ensure sidebar is visible immediately (runs as soon as DOM element exists) -->
    <script>
    // Immediately set sidebar visible on wide screens (runs before jQuery)
    (function() {
        function showSidebar() {
            var pageWrapper = document.querySelector('.page-wrapper');
            if (pageWrapper && screen.width >= 576) {
                pageWrapper.classList.add('toggled');
            }
        }
        
        // Try immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSidebar);
        } else {
            showSidebar();
        }
        
        // Also try on window load as backup
        window.addEventListener('load', showSidebar);
    })();
    </script>
    
<main class="page-content" style="margin-top:45px;">
   <div class="container-fluid">
      <div class="row">
         <div class="col-12 text-center bg-primary text-white">
            <h4 class="m-t5"><i class="fas fa-calendar-check"></i> Holiday</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border">
             <div class="table-responsive">
               <table class="table customTab">
                  <thead>
                     <tr class="bg-primary text-white">
                        
                        <th>S.No.</th>
                        <th style="width:160px;">Date&nbsp;&nbsp;&nbsp;</th>
                        <th>Class</th>
                     
                        <th>Holiday</th>
                        
                        
                     </tr>
                  </thead>
                  <tbody>
                    <?php
                    $srno=1;
                    $sql=mysqli_query($Con, "SELECT  `Holiday`, DATE_FORMAT(`HolidayDate`,'%d-%m-%Y') AS `HolidayDate`, `Class`  FROM `school_holidays` where `status`='Active'");
                    while($row=mysqli_fetch_row($sql))
                    {
                    
                    $holiday=$row[0];
                    $holidaydate=$row[1];
                    $classname=$row[2];
                    
                 //   $date=date_create("2013-03-15");
                 $date=date_create($holidaydate);
                  $date_formate = date_format($date,"j-M-y");
                  
                    
                    ?>
                     <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $date_formate; ?></td>
                            <!--<td><?php echo $holidaydate; ?></td>-->
                        <td><?php echo $classname;?></td>
                      
                        <td><?php echo $holiday;?></td>
                      
                        
                     </tr>
                     <?php
                     $srno=$srno+1;    
                    }
                    ?>
                  </tbody>
               </table>
            </div>
           
           
         </div>
      </div>
     
   </div>
</main>
<!--end page contents-->
</div>
</body>
</html>



