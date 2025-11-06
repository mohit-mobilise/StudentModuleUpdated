<?php include '../connection.php';?>


<?php
session_start();

$StudentName = $_SESSION['StudentName'];
$StudentAdmission = $_SESSION['userid'];

// echo "$StudentAdmission";
// exit();



$StudentClass = $_SESSION['StudentClass'];
$StudentRollNo = $_SESSION['StudentRollNo'];

if(isset($_POST['book_search']))
{

		$id = $_POST['search_custom_all'];
		$column = $_POST['search_by_option'];

		// echo "SELECT * FROM  `library_book_master` WHERE `$column` = '$id' ORDER BY  `srno` DESC  ";
		// exit();
		$all_book = mysqli_query($Con, "SELECT * FROM  `library_book_master` WHERE `$column` LIKE '%$id%' ORDER BY  `srno` DESC  ");
                         $count = mysqli_num_rows($all_book);


}

?>


<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.2
Version: 3.7.0
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $SchoolNameuser; ?>| Homework</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>


<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>


<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>

<link href="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/pages/css/profile.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/pages/css/tasks.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datepicker/css/datepicker3.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css"/>



<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="../../assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>



<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<!-- DOC: Apply "page-header-fixed-mobile" and "page-footer-fixed-mobile" class to body element to force fixed header or footer in mobile devices -->
<!-- DOC: Apply "page-sidebar-closed" class to the body and "page-sidebar-menu-closed" class to the sidebar menu element to hide the sidebar by default -->
<!-- DOC: Apply "page-sidebar-hide" class to the body to make the sidebar completely hidden on toggle -->
<!-- DOC: Apply "page-sidebar-closed-hide-logo" class to the body element to make the logo hidden on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-hide" class to body element to completely hide the sidebar on sidebar toggle -->
<!-- DOC: Apply "page-sidebar-fixed" class to have fixed sidebar -->
<!-- DOC: Apply "page-footer-fixed" class to the body element to have fixed footer -->
<!-- DOC: Apply "page-sidebar-reversed" class to put the sidebar on the right side -->
<!-- DOC: Apply "page-full-width" class to the body element to have full width page without the sidebar menu -->
<body class="page-md page-header-fixed page-sidebar-closed-hide-logo ">
<!-- BEGIN HEADER -->
<div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
  <!-- BEGIN HEADER INNER -->
  
    <?php include 'header.php'; ?>

  <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <!-- BEGIN SIDEBAR -->
  
      <?php

  //include 'side_menu.php';

  ?>

  <!-- END SIDEBAR -->
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content" style="width:100% !important; margin:0px">
      <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
      
      <?php

  include 'StyleCustomizer.php';

  ?>

      <!-- END PAGE BREADCRUMB -->
      <!-- END PAGE HEADER-->
      <!-- BEGIN PAGE CONTENT-->
      <div class="row">
        <div class="col-md-12">
          
          <!-- Begin: life time stats -->
          <div class="portlet light">
            <div class="portlet-title">
              <div class="caption">
                <i class="fa fa-calendar"></i>
                <span class="caption-subject font-green-sharp bold uppercase">My Book history</span>
                <span class="caption-helper">.</span>
              </div>
              <div class="btn-set pull-right">
                                    

                              
                            </div>
              <div class="actions">
                
                <div class="btn-group">
                  <a href="landing.php" class="btn btn-default btn-circle"  >
                  <i class="fa fa-share"></i>
                  <span class="hidden-480">
                  Back </span>
                  <i class="fa fa-angle-down"></i>
                  </a>
               
                </div>
              </div>
            </div>
            <div class="portlet-body">
							

							<form name="frmStudentMaster" id="frmStudentMaster" method="post" action="#"  class="horizontal-form">
	
	<div class="form-body">
		<div class="row">
		
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Type</label>
					<select class="form-control" id="search_by_option" name="search_by_option"  >
						<option value="select">--Select--</option>
						<option value="Subject">Search By Subject</option>
	
    					<option value="BookName">Book Name</option>
    					
					</select>	
				</div>
				
			</div>
				<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Search By </label>
					<input type="text" class="form-control"  id="search_custom_all" name="search_custom_all" placeholder="">
					<div id="search_add_suggesstion-box" style="list-style: none;text-decoration: none;cursor: pointer;"></div>
				</div>
			</div>
		</div><!--end of row-->

		
			

		<div class="row" style="margin-bottom: 8px;">
			<div class="col-md-offset-5">
				<input name="book_search" id="book_search" type="submit" value="Submit" class="btn green">
			</div>
		</div>

	
	</div><!--end of form-body-->



</form>

							 
                                <br>
                            <div class="table-responsive">		
							<table class="table table-striped table-bordered table-hover" id="sample_1">

								<thead>

								<tr>
									<th width="33"> S.No</th>
									<th>Book Availabilty</th>
									
									<th>Accession No</th>
									<th>Author Name</th>
									<th>Author 2</th>
									<th>Subject</th>

									<th>Book Name</th>
									
									<th>Publisher</th>
									<th>Year of publication</th>
								
									<th>status</th>


								

								</tr>

								</thead>

								<tbody id="search_table_body">
<?php

if(isset($_POST['book_search']))
{



if($count > 0)
{

$i = 1;
	   			while ($rows = mysqli_fetch_array($all_book)) 
	   			{

	   				$AccessionNo=$rows['BookCode'];

	   				$all_book1 = mysqli_query($Con, "SELECT `status` FROM  `library_book_transaction` WHERE  `bookid` ='$AccessionNo' ORDER BY  `srno` DESC  ");
                                                          $count1 = mysqli_fetch_array($all_book1);

                                                          $book_available=$count1['status'];
                                                          if ($book_available=="issued") {
                                                          	$book_available="Not Available";
                                                          	$lablecolor="label-danger";
                                                          }
                                                          else
                                                          {
                                                          	$book_available="Available";
                                                          	$lablecolor="label-success";


                                                          }

	   				echo "<tr>";
	   				?>

	 				    <td><?php echo $i++; ?></td>
	 				 
	 				            <td> <label class="label <?=$lablecolor;?>"><?=$book_available;?></label></td>
	 				 	<td><?php echo $rows['BookCode']; ?></td>
	 				 	<td><?php echo $rows['Author']; ?></td>
	 				 	<td><?php echo $rows['Author2']; ?></td>
	 				 	<td><?php echo $rows['Subject']; ?></td>

	 				 	<td><?php echo $rows['BookName']; ?></td>
	 				 	
	 				 	<td><?php echo $rows['Publisher']; ?></td>
	 				 	<td><?php echo $rows['year_of_publication']; ?></td> 				 	
	 				 	<td><?php echo $rows['status']; ?></td> 


	    				<?php   
	    				echo "</tr>"; 
	    			}

}//end of if count  > 0



}

?>





								 </tbody>
								</table>

								</div>	
								
							</div>
          </div>
          <!-- End: life time stats -->
        </div>
      </div>
      <!-- END PAGE CONTENT-->
    </div>
  </div>
  <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->

<?php include 'footer.php'; ?>

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 

<![endif]-->
<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>



<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../../assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>


<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/components-pickers.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="../../assets/global/scripts/datatable.js"></script>
<script src="../../assets/admin/pages/scripts/profile.js" type="text/javascript"></script>

<script src="../../assets/admin/pages/scripts/ecommerce-orders.js"></script>


<!-- END PAGE LEVEL SCRIPTS -->
<script>
        jQuery(document).ready(function() {    
           Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
           //EcommerceOrders.init();
           Profile.init(); // init page demo
           ComponentsPickers.init();

        });
    </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
