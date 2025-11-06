<?php
// Start session and include necessary files
session_start();
require '../connection.php';
require '../AppConf.php';

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? ''; // Use StudentClass from session
$currentdate = date("Y-m-d");

// If StudentClass is not set in session, use the one from the request
if (empty($StudentClass)) {
    $StudentClass = $_REQUEST["cboClass"] ?? '';
    
    // If still empty, get from database
    if (empty($StudentClass) && !empty($StudentId)) {
        $classQuery = "SELECT `sclass` FROM `student_master` WHERE `sadmission` = '$StudentId' LIMIT 1";
        $classResult = mysqli_query($Con, $classQuery);
        if ($classResult && mysqli_num_rows($classResult) > 0) {
            $classRow = mysqli_fetch_assoc($classResult);
            $StudentClass = $classRow['sclass'] ?? '';
        }
    }
    
    // Final fallback to '10' if still empty
    if (empty($StudentClass)) {
        $StudentClass = '10';
    }
}

// Database query to fetch student information collection details for the given class
$ssql = "SELECT `sclass`, `parameter`, `description`, `URL`, `Status` FROM `StudentInfoCollectionMaster` WHERE `sclass`='$StudentClass' AND `Status` = 'Active' ORDER BY `SrNo` ASC";

// Execute the query
$rs = mysqli_query($Con, $ssql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $SchoolName;?> ||School Information Collection</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<?php include 'Header/header_new.php'; ?>

<div class="page-wrapper chiller-theme">
    <?php include 'new_sidenav.php'; ?>
    
    <main class="page-content" style="margin-top:50px;">
        <div class="container-fluid page-border"> 
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                    <h4><i class="fas fa-bus"></i> School Information Collection</h4>
                </div>
            </div>
            <!-- end first row -->
            <div class="row m-t10">
                <div class="col-12 col-pn">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="sample_2">
                            <thead>
                                <tr>
                                    <th width="33">S.No</th>
                                    <th>Parameter</th>
                                    <th>Description</th>
                                    <th>Link For Submission</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $srno = 1;
                                // Loop through the fetched data and display it in the table
                                if ($rs && mysqli_num_rows($rs) > 0) {
                                    while ($row = mysqli_fetch_row($rs)) {
                                        $CLass = $row[0];
                                        $Parameter = $row[1];
                                        $Description = $row[2];
                                        $URL = $row[3];
                                        $Status = $row[4];
                                        ?>
                                    <tr>
                                        <td width="33"><?php echo $srno; ?></td>
                                        <td><?php echo $Parameter; ?></td>
                                        <td><?php echo $Description; ?></td>
                                        <td><a href="<?php echo $URL; ?>"><button type="button" class="btn btn-primary">Go Ahead</button></a></td>
                                    </tr>
                                    <?php
                                        $srno++;
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No information collection forms available at this time.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- end page content -->
</div>
</body>
</html>


<script>

    function Validate2()
    {
    	document.getElementById("frmStudentMaster").submit();
    }
    $(".sidebar-dropdown > a").click(function() {
    $(".sidebar-submenu").slideUp(200);
    if (
      $(this)
        .parent()
        .hasClass("active")
    ) {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .parent()
        .removeClass("active");
    } else {
      $(".sidebar-dropdown").removeClass("active");
      $(this)
        .next(".sidebar-submenu")
        .slideDown(200);
      $(this)
        .parent()
        .addClass("active");
    }
  });
  
  $("#close-sidebar").click(function() {
    $(".page-wrapper").removeClass("toggled");
  });
  $("#show-sidebar").click(function() {
    $(".page-wrapper").addClass("toggled");
  });
  
   window.onload=function(){
      var x=screen.width;
      if(x>=576)
      {
         $(".page-wrapper").addClass("toggled");
      }
  }
  
  // Sidebar visibility fix - ensure sidebar is visible on page load
  (function() {
      function showSidebar() {
          var pageWrapper = document.querySelector('.page-wrapper');
          if (pageWrapper && screen.width >= 576) {
              pageWrapper.classList.add('toggled');
          }
      }
      
      // Run immediately
      showSidebar();
      
      // Also run on DOMContentLoaded and window.load as fallbacks
      if (document.readyState === 'loading') {
          document.addEventListener('DOMContentLoaded', showSidebar);
      }
      window.addEventListener('load', showSidebar);
  })();
  
</script>