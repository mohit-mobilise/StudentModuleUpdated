<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='index.php'>here</a> to login again</b></center>";
    exit;
}

$sadmission = $_SESSION['userid'] ?? '';
$class = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';

$rsroutno=mysqli_query($Con, "select distinct `route_1` , `route_2`, `pick_up_stoppage`,`drop_stoppage` from student_transport_detail where `sadmission`='$sadmission'");
$rowroutno=mysqli_fetch_row($rsroutno);

// Initialize variables with default values to prevent null array access warnings
$route_1 = '';
$route_2 = '';
$pickup_stopage = '';
$drop_stopage = '';
$sendingroutno = '';

// Check if query returned results before accessing array
if ($rowroutno && is_array($rowroutno) && count($rowroutno) > 0) {
    $route_1 = $rowroutno[0] ?? '';
    $route_2 = $rowroutno[1] ?? '';
    $pickup_stopage = $rowroutno[2] ?? '';
    $drop_stopage = $rowroutno[3] ?? '';
    $sendingroutno = $rowroutno[0] ?? '';
}

$strroutno="'".str_replace(",","','",$route_1)."'";
$strroutno2="'".str_replace(",","','",$route_2)."'";

// For route_2, pickup_stopage1 should be drop_stoppage
$pickup_stopage1 = $drop_stopage;

$ssql="select distinct `routeno`,`bus_no`,`timing`,`driver_name`,`driver_mobile`,`UserId` as `GPSUserId`,`Password` as `GPSPassword`,'https://nkbpsis.in/GPS/TrackMyBus.php' as `GPSURL`,`in_bus_timing`,`out_bus_timing`, `datetime`, `routecharges`, `route_details`, `financialyear`,`route_slab`, `attendant_name`, `attendant_mobile`, `teacher_name`, `teacher_mobile` from `RouteMaster` as `a` where `routeno` in (".$strroutno.")";
$result = mysqli_query($Con, $ssql);
$row = $result ? mysqli_fetch_assoc($result) : null;

// Initialize variables with default values
$route_no = '';
$bus_no = '';
$timing = '';
$driver_name = '';
$driver_mobile = '';
$UserId = '';
$Password = '';
$attendant_name = '';
$attendant_mobile = '';
$teacher_name = '';
$teacher_mobile = '';
$pickup = '';
$pickout = '';

// Check if query returned results before accessing array
if ($row && is_array($row)) {
    $route_no = $row['routeno'] ?? '';
    $bus_no = $row['bus_no'] ?? '';
    $timing = $row['timing'] ?? '';
    $driver_name = $row['driver_name'] ?? '';
    $driver_mobile = $row['driver_mobile'] ?? '';
    $UserId = $row['GPSUserId'] ?? '';
    $Password = $row['GPSPassword'] ?? '';
    $attendant_name = $row['attendant_name'] ?? '';
    $attendant_mobile = $row['attendant_mobile'] ?? '';
    $teacher_name = $row['teacher_name'] ?? '';
    $teacher_mobile = $row['teacher_mobile'] ?? '';
    $pickup = $row['in_bus_timing'] ?? '';
    $pickout = $row['out_bus_timing'] ?? '';
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||My Transport</title>
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
   <div class="container-fluid page-border">
       <div class="row">
         <div class="col-12 text-center bg-primary text-white">
            <h4 class="m-t5"><i class="fas fa-bus-alt"></i> My Transport</h4>
         </div>
        </div>
        <div class="row" style="margin-top:20px">
     
                  <div class="col-md-6">
                      <div class="border border-rad bg-white">
                        <div class="bg-primary border-rad text-white p-2">
                        <h5><i class="fas fa-user"></i> <?php echo $route_no."-".$bus_no;?></h5>
                        </div>
                        
                        <table class="table">
                            <tr>
                                <th>Driver / Helper Name</th>
                                <td> <?php echo $driver_name;?></td>
                            </tr>
                            <tr>
                                <th> Driver / Helper Mobile</th>
                                <td> <?php echo $driver_mobile;?></td>
                            </tr>
                             <tr>
                                <th>Attendant Name</th>
                                <td> <?php echo $attendant_name;?></td>
                            </tr>
                            <tr>
                                <th> Attendant Mobile</th>
                                <td> <?php echo $attendant_mobile;?></td>
                            </tr>
                             <tr>
                                <th>Teacher Name</th>
                                <td><?php echo $teacher_name;?></td>
                            </tr>
                            <tr>
                                <th>Teacher Mobile</th>
                                <td><?php echo $teacher_mobile;?></td>
                            </tr>
                            <tr>
                                <th>Pick Up</th>
                                <td><?php echo $pickup_stopage;?></td>
                            </tr>
                            <tr>
                                <th> In Time </th>
                                <td><?php echo $pickup;?></td>
                            </tr>
                            <tr>
                                <th>GPS UserId</th>
                                <td><?php echo $UserId;?></td>
                            </tr>
                            <tr>
                                <th>GPS Password</th>
                                <td> <?php echo $Password;?></td>
                            </tr>
                            
                        </table>

                    </div>
                    <!--end first block -->
                    </div>
                    <div class="col-md-6">
                        <div class="bg-primary border-rad text-white">
                       
                        <?php
                        $ssql1="select distinct `routeno`,`bus_no`,`timing`,`driver_name`,`driver_mobile`,`UserId` as `GPSUserId`,`Password` as `GPSPassword`,'https://nkbpsis.in/GPS/TrackMyBus.php' as `GPSURL`,`in_bus_timing`,`out_bus_timing`, `datetime`, `routecharges`, `route_details`, `financialyear`,`route_slab`, `attendant_name`, `attendant_mobile`, `teacher_name`, `teacher_mobile` from `RouteMaster` as `a` where `routeno` in (".$strroutno2.")";
                            $result1 = mysqli_query($Con, $ssql1);
                            $row1 = $result1 ? mysqli_fetch_assoc($result1) : null;
                            
                            // Initialize variables with default values
                            $route_no1 = '';
                            $bus_no1 = '';
                            $timing1 = '';
                            $driver_name1 = '';
                            $driver_mobile1 = '';
                            $UserId1 = '';
                            $Password1 = '';
                            $attendant_name1 = '';
                            $attendant_mobile1 = '';
                            $teacher_name1 = '';
                            $teacher_mobile1 = '';
                            $pickup1 = '';
                            $pickout1 = '';
                            
                            // Check if query returned results before accessing array
                            if ($row1 && is_array($row1)) {
                                $route_no1 = $row1['routeno'] ?? '';
                                $bus_no1 = $row1['bus_no'] ?? '';
                                $timing1 = $row1['timing'] ?? '';
                                $driver_name1 = $row1['driver_name'] ?? '';
                                $driver_mobile1 = $row1['driver_mobile'] ?? '';
                                $UserId1 = $row1['GPSUserId'] ?? '';
                                $Password1 = $row1['GPSPassword'] ?? '';
                                $attendant_name1 = $row1['attendant_name'] ?? '';
                                $attendant_mobile1 = $row1['attendant_mobile'] ?? '';
                                $teacher_name1 = $row1['teacher_name'] ?? '';
                                $teacher_mobile1 = $row1['teacher_mobile'] ?? '';
                                $pickup1 = $row1['in_bus_timing'] ?? '';
                                $pickout1 = $row1['out_bus_timing'] ?? '';
                            }
                        ?>

                         <div class="bg-primary border-rad text-white p-2">
                        <h5><i class="fas fa-user"></i> <?php echo $route_no1."-".$bus_no1;?></h5>
                        </div>
                        
                        <table class="table">
                            <tr>
                                <th>Driver / Helper Name</th>
                                <td> <?php echo $driver_name1;?></td>
                            </tr>
                            <tr>
                                <th> Driver / Helper Mobile</th>
                                <td> <?php echo $driver_mobile1;?></td>
                            </tr>
                             <tr>
                                <th>Attendant Name</th>
                                <td> <?php echo $attendant_name1;?></td>
                            </tr>
                            <tr>
                                <th> Attendant Mobile</th>
                                <td> <?php echo $attendant_mobile1;?></td>
                            </tr>
                             <tr>
                                <th>Teacher Name</th>
                                <td><?php echo $teacher_name1;?></td>
                            </tr>
                            <tr>
                                <th>Teacher Mobile</th>
                                <td><?php echo $teacher_mobile1;?></td>
                            </tr>
                            <tr>
                                <th>Pick Up</th>
                                <td><?php echo $pickup_stopage1;?></td>
                            </tr>
                            <tr>
                                <th> In Time </th>
                                <td><?php echo $pickup1;?></td>
                            </tr>
                            <tr>
                                <th>GPS UserId</th>
                                <td><?php echo $UserId1;?></td>
                            </tr>
                            <tr>
                                <th>GPS Password</th>
                                <td> <?php echo $Password1;?></td>
                            </tr>
                            
                        </table>
                        
                        
                        
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