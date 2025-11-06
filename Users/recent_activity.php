<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../connection.php';
    $StudentClass = $_SESSION['StudentClass'];
    
    $StudentRollNo = $_SESSION['StudentRollNo'];
    $sadmission=$_SESSION['userid'];
     
     if($sadmission == ""  )
    {
        echo "<br><br><center><b>Session Expired!<br>click <a href='https://dpsfsis.com'>here</a> to login again!";
        exit();
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
                  <h4 class="m-t5"><i class="fas fa-file-alt"></i> Recent Activity</h4>
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
                                    <th>Activity Description</th>
                                    <th>Goto Link</th>

                                </tr>
                            </thead>
                            <tbody>
                                        <?php    
                                
                              $sql=mysqli_query($Con, "select `MasterClass` from `class_master` where `class`='$StudentClass'");
                                $row=mysqli_fetch_row($sql);
                                $masterclass_name=$row[0];
                                
                                $query2=mysqli_query($Con, "SELECT  `menu_name`,`menu_link` FROM `menu_portal_master` WHERE `status`='Active'  AND `MasterClass`= $masterclass_name");


                                   while($Scholar_ncc=mysqli_fetch_array($query2)){

                                    $menu_name=$Scholar_ncc['menu_name'];
                                    $menu_link=$Scholar_ncc['menu_link'];
                                    //$certi_name=$Scholar_ncc['certi_name'];

                                ?>
                      


                      

                                 <tr>
                                    
                                    <td style="padding-top:18px;"><?php echo $menu_name; ?></td>
                                  
                                     <td>
                                        <a href="../Users/<?php echo $menu_link;?>" target="_blank" class="btn default btn-xs green" ><button type="button" class="btn btn-success">Click Here</button>
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