<?php include '../connection.php';?>


<?php
session_start();

$StudentName = $_SESSION['StudentName'];
$StudentAdmission = $_SESSION['userid'];

// echo "$StudentAdmission";
// exit();



$StudentClass = $_SESSION['StudentClass'];
$StudentRollNo = $_SESSION['StudentRollNo'];


$class=$_SESSION['StudentClass'];

$ssql="SELECT  `sadmission`, `sname`, `sclass`, `srollno`, `bookid`, `bookname`, `bookauthor`, `booksubject`, `tilldate`, `returndate`, `fine`, `fine_discount`, `status`, `issue_date`, `IssuerType`, `FinancialYear` FROM `library_book_transaction` where `sadmission`='$StudentAdmission' ";
$reslt = mysqli_query($Con, $ssql);



$ssql1="SELECT count(*) FROM `library_book_transaction` where `sadmission`='$StudentAdmission' ";
$reslt1 = mysqli_query($Con, $ssql1);
$rows1 = mysqli_fetch_array($reslt1);

$total_issued_book=$rows1[0];


$ssql2="SELECT count(*) FROM `library_book_transaction` where `sadmission`='$StudentAdmission' and `status`='returned' ";
$reslt2 = mysqli_query($Con, $ssql2);
$rows2 = mysqli_fetch_array($reslt2);

$total_returned_book=$rows2[0];

$total_pending_book=$total_issued_book-$total_returned_book;

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
    <div class="page-content" style="width:100% !important;margin:0px !important">
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
                  <a href="landing.php" class="btn btn-default btn-circle" >
                  <i class="fa fa-share"></i>
                  <span class="hidden-480">
                  Back </span>
                  <i class="fa fa-angle-down"></i>
                  </a>
                  
                </div>
              </div>
            </div>
            <div class="portlet-body">
								<br>
								<a href="LibrarySearchBook_Student.php"><button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa  fa-plus"></i>Search Book In Library</button></a>
							 
                                <br>
                                <br>
                                <div class="row">
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat blue-madison">
						<div class="visual">
							<i class="fa fa-comments"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $total_issued_book;?>
							</div>
							<div class="desc">
								 Total Book Issued</div>
						</div>
						<a class="more" href="">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat red-intense">
						<div class="visual">
							<i class="fa fa-bar-chart-o"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $total_returned_book;?>					
								</div>
							<div class="desc">
								 Total Book Returned
							</div>
						</div>
						<a class="more" href="javascript:;">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
					<div class="dashboard-stat green-haze">
						<div class="visual">
							<i class="fa fa-shopping-cart"></i>
						</div>
						<div class="details">
							<div class="number">
								<?php echo $total_pending_book;?>							</div>
							<div class="desc">
								 Total Pending Books
							</div>
						</div>
						<a class="more" href="javascript:;">
						View more <i class="m-icon-swapright m-icon-white"></i>
						</a>
					</div>
				</div>
			
			</div>
			<div class="clearfix">
			</div>
			
			<div class="clearfix">
			</div>
                                <table class="table table-striped table-bordered table-hover" id="sample_2">
								<thead>

								<tr>
<th width="33"> S.No</th>

									<th>Admission No.</th>
									<th>Name</th>
									<th>Class</th>
									<th>Roll No.</th>
									<th>Book ID No.</th>
									<th>Book Name</th>
									<th>Author Name</th>
									<th>Book Subject</th>
									<th>Issue Date</th>
									<th>Issued till Date</th>
									<th>Return Date</th>
									<th>Fine Submitted</th>
									<th>Status</th>
								
								</tr>
								</thead>
								<tbody>
				<?php
				$num_rows=1;
				while($rows = mysqli_fetch_assoc($reslt))
				{    

					
					$num_rows=$num_rows+1;
			?>

					<tr>
						<td width="33"><?php echo $num_rows; ?></td>
						<td><?php echo $rows['sadmission']; ?></td>
	 				 	<td><?php echo $rows['sname']; ?></td>
	 				 	<td><?php echo $rows['sclass']; ?></td>
	 				 	<td><?php echo $rows['srollno']; ?></td>
	 				 	<td><?php echo $rows['bookid']; ?></td>
	 				 	<td><?php echo $rows['bookname']; ?></td>
	 				 	<td><?php echo $rows['bookauthor']; ?></td>
	 				 	<td><?php echo $rows['booksubject']; ?></td>
	 				 	<td><?php echo date('d/m/Y', strtotime($rows['issue_date'])); ?></td>
	 				 	<td><?php echo date('d/m/Y', strtotime($rows['tilldate'])); ?></td>
	 				 	<td><?php if ($rows['status']=="issued") { echo "Not return "; } else { echo date('d/m/Y', strtotime($rows['returndate'])); }   ?></td>
	 				 	<td><?php echo $rows['fine']; ?></td>
	 				 	<td><?php echo $rows['status']; ?></td>
	 				 

								</tr>
								
			<?php

			}

			?>

								</tbody>
								</table>
								
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
