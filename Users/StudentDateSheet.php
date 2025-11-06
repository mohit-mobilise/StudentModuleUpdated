<?php 
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

require '../connection.php';
require '../AppConf.php';

$StudentId = $_SESSION['userid'] ?? '';
$StudentClass = $_SESSION['StudentClass'] ?? '';
$currentdate = date("Y-m-d");

if(empty($StudentClass)) {
    $StudentClass = validate_input($_REQUEST["cboClass"] ?? '', 'string', 20);
    
    // If still empty, get from database
    if (empty($StudentClass) && !empty($StudentId)) {
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($Con, "SELECT `sclass` FROM `student_master` WHERE `sadmission`=? LIMIT 1");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $StudentId);
            mysqli_stmt_execute($stmt);
            $classResult = mysqli_stmt_get_result($stmt);
            if ($classResult && mysqli_num_rows($classResult) > 0) {
                $classRow = mysqli_fetch_assoc($classResult);
                $StudentClass = $classRow['sclass'] ?? '';
            }
            mysqli_stmt_close($stmt);
        }
    }
		
		// Final fallback to '10' if still empty
		if (empty($StudentClass)) {
			$StudentClass = '10';
		}
	}
	
	$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
	$ssqlClass="SELECT distinct `class` FROM `class_master`";
	$rsClass= mysqli_query($Con, $ssqlClass);
	
if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"]=="yes") {
    // Validate and sanitize inputs
    $date_from = validate_input($_REQUEST["date_from"] ?? '', 'string', 20);
    $date_to = validate_input($_REQUEST["date_to"] ?? '', 'string', 20);
    $StudentClass_clean = validate_input($StudentClass ?? '', 'string', 20);
    
    if (!empty($date_from) && !empty($date_to)) {
        // Use prepared statement to prevent SQL injection
        $stmt = mysqli_prepare($Con, "SELECT `examtype`,`notice`,`status`,DATE_FORMAT(`NoticeDate`,'%d-%m-%Y') AS `NoticeDate`,DATE_FORMAT(`NoticeEndDate`,'%d-%m-%Y') AS `NoticeEndDate`,`noticefilename` FROM `student_datesheet` WHERE `status`='Active' AND `NoticeDate`>=? AND `NoticeEndDate`<=? AND `sclass`=? ORDER BY `datetime` DESC");
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $date_from, $date_to, $StudentClass_clean);
            mysqli_stmt_execute($stmt);
            $reslt = mysqli_stmt_get_result($stmt);
        } else {
            error_log('StudentDateSheet query error: ' . mysqli_error($Con));
            $reslt = false;
        }
    } else {
        // No date filter - use prepared statement
        $stmt = mysqli_prepare($Con, "SELECT `examtype`,`notice`,`status`,DATE_FORMAT(`NoticeDate`,'%d-%m-%Y') AS `NoticeDate`,DATE_FORMAT(`NoticeEndDate`,'%d-%m-%Y') AS `NoticeEndDate`,`noticefilename` FROM `student_datesheet` WHERE `status`='Active' ORDER BY `datetime` DESC");
        if ($stmt) {
            mysqli_stmt_execute($stmt);
            $reslt = mysqli_stmt_get_result($stmt);
        } else {
            $reslt = false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> ||Datesheet</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
 <style>
     table.table.table-row-fixed.StudentDateSheet p {
    margin-top: 0;
    margin-bottom: 0rem;
}
 </style>
</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme ">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:50px;">
          <div class="container-fluid page-border">
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4 class="m-t5"><i class="fas fa-file-alt"></i> Datesheet </h4>
                </div>
               <div class="col">
                  <!-- search panal --> 
                  <div class="card flex-fill add-mrf-card bg-g-light">
                     <div class="card-body card-padding-bottom p10">
                    <form name="frmStudentMaster" id="frmStudentMaster" method="post" action="StudentDateSheet.php"> 
                         
                         <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
	
                        <div class="row">
                           <div class="col-xl-3 col-lg-3 col-md-12">
                              <div class="form-group">
                                 <label>Date From</label>
                                  <input type="date"  name="date_from" class="form-control">
                              </div>
                           </div>
                           <div class="col-xl-3 col-lg-3 col-md-12">
                              <div class="form-group">
                                 <label>Date To</label>
                                 <input type="date" name="date_to" class="form-control">
                              </div>
                           </div>
                           
                           <div class="col-xl-3 col-lg-3 col-md-12 mt-1">
                              <div class="form-group mt-4">
                                 <button class="btn btn-primary" type="submit" onclick ="Javascript:Validate2();"> Search</button>
                                 <button type="button" class="btn btn-secondary"> Reset</button>
                              </div>
                           </div>
                        </div>
                    </form>    
                     </div>
                  </div>
               </div>
            </div>
            <!--end first row -->
            <div class="row m-t10">
                <div class="col-md-4">
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
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th width="5%">
										 Srno</th>
                                        

								

									<th width="10%">
										 Uploaded Date</th>
									<!--<th width="10%">-->
									<!--	 Finish Date</th>-->
										 
									<th width="10%">
                                         Exam Type</th>

                                    <th width="10%">
                                         Notice</th>	 
										 									
									
								
									<th width="10%">
										 Actions
									</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                                	<?php
                              $srno = 0; // Initialize serial number counter
                              
                              if (isset($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"]=="yes" && isset($reslt))
                              {  
                            		while($rowa = mysqli_fetch_assoc($reslt))
                            	   {
                            	   		

                                        
                            	   		$examtype=$rowa['examtype'];
                            	   		$notice=$rowa['notice'];
                            	   		$NoticeDate=$rowa['NoticeDate'];
                            	   //		$NoticeEndDate=$rowa['NoticeEndDate'];
                            	   		$noticefilename=$rowa['noticefilename'];
                            	   		$srno=$srno+1;
                            	?>
                                <tr>
                                    
                                    <td width="33">
										 <?php echo $srno; ?></td>
										 
									<td>
										 <?php echo $NoticeDate; ?></td>

                                    <!--<td>-->
                                    <!--     <?php echo $NoticeEndDate; ?></td>-->
                                         
                                    <td>
										 <?php echo $examtype; ?>&nbsp; </td>
									<td>
										 <?php echo $notice; ?></td>
										 
										      
																	
										 	     
								    <td class="text-center">
								        <?php
								        if($noticefilename!='')
								        {
								        ?>
								        
										  	<a href="<?php echo $noticefilename; ?>" target="_blank" ><button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button></a></td>
										   
										<?php
								        }
								        ?>
								
                                </tr>
                                	<?php
                            	   }    	
								}
                               
                               else
                               {
                                $srno = 0; // Initialize serial number counter
                                
                                $ssql="select `examtype`,`notice`,`status`,date_format(`NoticeDate`,'%d-%m-%Y') as `NoticeDate`,date_format(`NoticeEndDate`,'%d-%m-%Y') as `NoticeEndDate`,`noticefilename` from  `student_datesheet` where `status`='Active' and `sclass`='$StudentClass'   order by `NoticeDate` ASC LIMIT 10";
                                $reslt=mysqli_query($Con, $ssql);
                                
                                if ($reslt && mysqli_num_rows($reslt) > 0) {
                                    while($rowa = mysqli_fetch_assoc($reslt))
                            	   {
                            	   		

                                        
                            	   		$examtype=$rowa['examtype'];
                            	   		$notice=$rowa['notice'];
                            	   		$NoticeDate=$rowa['NoticeDate'];
                            	   //		$NoticeEndDate=$rowa['NoticeEndDate'];
                            	   		$noticefilename=$rowa['noticefilename'];
                            	   		$srno=$srno+1;
                            	?>
                                <tr>
                                    
                                    <td width="33">
										 <?php echo $srno; ?></td>
										 
									<td>
										 <?php echo $NoticeDate; ?></td>

                                    <!--<td>-->
                                    <!--     <?php echo $NoticeEndDate; ?></td>-->
                                         
                                    <td>
										 <?php echo $examtype; ?>&nbsp; </td>
									<td>
										 <?php echo $notice; ?></td>
										 
										      
																	
										 	     
								    <td class="text-center">
								        <?php
								        if($noticefilename!='')
								        {
								        ?>
								        
										  	<a href="<?php echo $noticefilename; ?>" target="_blank" ><button class="btn btn-sm btn-primary"><i class="fa fa-download"></i></button></a></td>
										   
										<?php
								        }
								        ?>
								
                                </tr>   
                                   
                                <?php
                            	   }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No exam dates found.</td></tr>";
                                }
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