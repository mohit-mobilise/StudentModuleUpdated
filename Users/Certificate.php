<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection.php';

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

$StudentClass = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$sadmission = $_SESSION['userid'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
 
</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme ">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:50px;">
          <div class="container-fluid page-border">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4 class="m-t5"><i class="fas fa-file-alt"></i>Certificate</h4>
                </div>
              
            </div>
            <!--end first row -->
            <div class="row m-t10">
                <div class="col-md-4 col-pn">
                    <div class="form-inline">
                    <label for="itemperpage">Items Per Page &nbsp;</label>
                    <select name="itemperpage" id="itemperpage" class="form-control ml-10">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    </select>
                    </div>
                    </div>
                <div class="col-12 col-pn">
                    <div class="table-responsive">
                        <table class="table table-row-fixed">
                            <thead>
                                 <tr class="bg-primary text-white">
                                    <th>Certificate Id</th>
                                    <th>Certificate Name</th>
                                    
                                    <th>View Certificate</th>

                                </tr>
                            </thead>
                            <tbody>
                                        <?php    
                                
                              
                                
                                $query2=mysqli_query($Con, "SELECT  `csm`.`form_id` , `csm`.`page_url` , `cm`.`certi_name` FROM `cetificate_student_mapping` as `csm` LEFT JOIN  `certi_master` as `cm` ON(`csm`.`form_id`=`cm`.`certi_id`)  where `csm`.`sadmission`='$sadmission' and  `cm`.`status`='1'");


                                   while($Scholar_ncc=mysqli_fetch_array($query2)){

                                    $form_id=$Scholar_ncc['form_id'];
                                    $page_url=$Scholar_ncc['page_url'];
                                    $certi_name=$Scholar_ncc['certi_name'];

                                ?>
                      


                      

                                 <tr>
                                    
                                    <td><?php echo $form_id; ?></td>
                                     <td><?php echo $certi_name; ?>&nbsp; </td>
                                     <td>
                                        <a href="../Users/<?php echo $page_url;?>" target="_blank" class="btn default btn-xs blue" >View
                                         </a>

                                    </td>

                                </tr>
                                <?php
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
  
  
</script>