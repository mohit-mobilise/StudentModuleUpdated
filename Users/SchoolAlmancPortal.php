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
$num_rows=0;



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||School Almanac</title>
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
            <h4 class="m-t5"><i class="fas fa-gift"></i> School Almanac</h4>
         </div>
         <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12 page-border">
             
           <!--start-->
           <div class="">
    
              <!-- Nav tabs -->
              <!--<ul class="nav nav-tabs" role="tablist">-->
                <!--<li class="nav-item">-->
                <!--  <a class="nav-link active" data-toggle="tab" href="#home"> School Almanac Senior</a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--  <a class="nav-link" data-toggle="tab" href="#menu1">School Almanac Primary</a>-->
                <!--</li>-->
                <!--<li class="nav-item">-->
                <!--  <a class="nav-link" data-toggle="tab" href="#menu2">School Almanac Nursery</a>-->
                <!--</li>-->
              </ul>
            
              <!-- Tab panes -->
              <!--<div class="tab-content">-->
              <!--  <div id="home" class="container tab-pane active col-pn">-->
              <!--  <php-->
              <!--  $ssqls="SELECT  `url` FROM `School_Almanac` WHERE `head`='School Almanac Senior' and `financial_year`='2021'";-->
              <!--   $rSS= mysqli_query($Con, $ssqls);-->
              <!--   $rowS=mysqli_fetch_row($rSS);-->
              <!--   $urlS=$rowS[0];-->
                 
              <!--  ?>-->
              <!--   <iframe src="<?php echo $urlS ;?>" width="100%" height="500"></iframe>-->
              <!--  </div>-->
                
              <!--  <div id="menu1" class="container tab-pane fade col-pn">-->
                    
              <!--  <php-->
              <!--      $ssqlP="SELECT  `url` FROM `School_Almanac` WHERE `head`='School Almanac Primary' and `financial_year`='2021'";-->
              <!--      $rSP= mysqli_query($Con, $ssqlP);-->
              <!--      $rowP=mysqli_fetch_row($rSP);-->
              <!--      $urlP=$rowP[0];-->
                 
              <!--  ?>-->
                
              <!--    <iframe src="<?php echo $urlP ;?>" width="100%" height="500"></iframe>-->
              <!--  </div>-->
                
              <!--  <div id="menu2" class="container tab-pane fade col-pn">-->
              <!--       <php-->
              <!--      $ssqlN="SELECT  `url` FROM `School_Almanac` WHERE `head`='School Almanac Nursery' and `financial_year`='2021'";-->
              <!--      $rSN= mysqli_query($Con, $ssqlN);-->
              <!--      $rowN=mysqli_fetch_row($rSN);-->
              <!--      $urlN=$rowN[0];-->
                   
                 
              <!--  ?>-->
                
              <!--    <iframe src="<?php echo $urlN ;?>" width="100%" height="500">-->
                 
              <!--      </iframe>-->
              <!--  </div>-->
              <!--</div>-->
              
              
              <table class="table table-bordered">
    <thead>
      <tr>
        <th>SrNo.</th>
        <th>Almanac</th>
        <th>Financial Year</th>
        <th>Download</th>
      </tr>
    </thead>
    
    
    <tbody>
    
    <?php
             $query = "SELECT  * FROM `School_Almanac` ORDER BY `datetime` DESC";
             $query_result = mysqli_query($Con, $query);
             $sr = 1;
             
                    while($row = mysqli_fetch_assoc($query_result)){
            
        //    echo "<pre>"; print_r($row);
                       // $data[] = $row;
                    
                ?>
    
      <tr>
        <td><?php echo $sr; ?></td>
        <td><?php echo $row['head']; ?></td>
        <td><?php echo $row['financial_year']; ?></td>
        <td>
            <!--<?php echo $row['url']; ?>-->
            
            <a href="<?php echo $row['url']; ?>" download>
           <i class="fa fa-download" aria-hidden="true"></i>
           </a>
            
            </td>
     
      </tr>
     
     <?php 
     
     $sr++;
                    }
     ?>
      
    </tbody>
  </table>
        
        
        
            </div>
           <!--end-->
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