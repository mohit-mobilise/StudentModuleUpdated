<?php
session_start();
// $_SESSION['url'] = $_SERVER['PHP_SELF'];
 include '../../connection.php';

if(isset($_POST['show_all_booK_master']))
{

$all_book = mysqli_query($Con, "SELECT `Library`,`Class`,`BookCode`,`Subject`,`SubjectSubcategory`,`BookName`,`SubTitle`,`Author`,`Author2`,`Publisher`,`Vendor`,`DonatedBook`,`Booktype`,`Langugage`,`BillNo`,`PurchaseOrderNo`,`status`,`year_of_publication`,`class_no`,`book_no`, `EditionNo`, `Remarks`, `Price`, `Pages`,`Volume` FROM  `library_book_master`");

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
<title>Session Plan</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta http-equiv="Content-type" content="text/html; charset=utf-8">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/extensions/Scroller/css/dataTables.scroller.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css"/>
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="../../assets/global/css/components.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../assets/admin/layout/css/themes/darkblue.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
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
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<?php include 'header.php'; ?>
<!-- END HEADER -->
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	<?php
	include 'side_menu.php';
	?>
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
				<?php
	include 'StyleCustomizer.php';
	?>
<!-- END STYLE CUSTOMIZER -->
			<!-- BEGIN PAGE HEADER-->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<!---PASTE THE CODE THAT YOU WAN TO SHOW HERE-->
			<div class="portlet box green-haze">
							<div class="portlet-title">
								<div class="caption">
									<i class="fa fa-globe"></i>Library Book Search 
								Management
								</div>
								<div class="tools">
									<a href="javascript:;" class="reload">
									</a>
									<a href="javascript:;" class="remove">
									</a>
								</div>
							</div>
							

							<div class="portlet-body">
						<div class="margin-bottom-5">

														<a href="AcquisitionBookMaster.php"><button class="btn btn-sm yellow filter-submit margin-bottom"><i class="fa  fa-plus"></i> 
														Add New</button></a>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
<button type="submit" name="show_all_booK_master" class="btn btn-sm yellow filter-submit margin-bottom pull-right"><i class="fa  fa-plus"></i> 
Show All</button>
</form>
													</div>
<!-- form -->
<form name="frmStudentMaster" id="frmStudentMaster" method="post" action="#"  class="horizontal-form">
	
	<div class="form-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Search By </label>
					<input type="text" class="form-control" name="" id="search_custom_all" placeholder="">
					<div id="search_add_suggesstion-box" style="list-style: none;text-decoration: none;cursor: pointer;"></div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label class="control-label">Type</label>
					<select class="form-control" name="" id="search_by_option" onchange="getval(this);" >
						<option value="select">--Select--</option>
						<option value="BookCode">Accession No.</option>
						<option value="Publisher">Publisher </option>
						<option value="BillNo">Bill No.</option>
    					<!--<option value="Class">Class</option>-->
    					<option value="Subject">Search By Subject</option>
    					<option value="Author">Author</option>
    					<option value="SubTitle">Book Title (Book Name)</option>
    					<option value="Langugage">Language</option>
    				
					
					</select>	
				</div>
				
			</div>
		</div><!--end of row-->

		
			

		<div class="row" style="margin-bottom: 8px;">
			<div class="col-md-offset-5">
				<input name="book_search_btn" id="book_search_btn" type="button" value="Submit" class="btn green">
			</div>
		</div>

	
	</div><!--end of form-body-->



</form>

<!-- end of form -->
													
	
								<div class="table-responsive">		
							<table class="table table-striped table-bordered table-hover" id="sample_1">

								<thead>

								<tr>
									<th width="33"> S.No</th>
									<th>Library</th>
									<th>Class</th>
									<th>Accession No</th>
									<th>Author Name</th>
									<th>Author 2</th>
									<th>Book Name</th>
									<th>Volume</th>
									<th>Publisher</th>
									<th>Year of publication</th>
									<th>Pages</th>
									<th>Source</th>
									<th>Class No</th>
									<th>Book No</th>
									<th>Price</th>

									<th>Subject</th>
									<th>category</th>
									<th>SubTitle</th>
									<th>DonatedBook</th>
									<th>Booktype</th>
									<th>Langugage</th>
									<th>BillNo</th>
									<th>PurchaseOrderNo</th>
									<th>Edition no</th>
									<th>Remarks</th>

									<th>status</th>


								

								</tr>

								</thead>

								<tbody id="search_table_body">
<?php

if(isset($_POST['show_all_booK_master']))
{



if($count > 0)
{

$i = 1;
	   			while ($rows = mysqli_fetch_array($all_book)) 
	   			{
	   				echo "<tr>";
	   				?>

	 				    <td><?php echo $i++; ?></td>
	 				 	<td><?php echo $rows['Library']; ?></td>
	 				 	<td><?php echo $rows['Class']; ?></td>
	 				 	<td><?php echo $rows['BookCode']; ?></td>
	 				 	<td><?php echo $rows['Author']; ?></td>
	 				 	<td><?php echo $rows['Author2']; ?></td>
	 				 	<td><?php echo $rows['BookName']; ?></td>
	 				 	<td><?php echo $rows['Volume']; ?></td>
	 				 	<td><?php echo $rows['Publisher']; ?></td>
	 				 	<td><?php echo $rows['year_of_publication']; ?></td>
	 				 	<td><?php echo $rows['Pages']; ?></td>
	 				 	<td><?php echo $rows['Vendor']; ?></td>
	 				 	<td><?php echo $rows['class_no']; ?></td>
	 				 	<td><?php echo $rows['book_no']; ?></td>
	 				 	<td><?php echo $rows['Price']; ?></td>

	 				 	<td><?php echo $rows['Subject']; ?></td>
	 				 	<td><?php echo $rows['SubjectSubcategory']; ?></td>
	 				 	<td><?php echo $rows['SubTitle']; ?></td>
	 				 	<td><?php echo $rows['DonatedBook']; ?></td>
	 				 	<td><?php echo $rows['Booktype']; ?></td>
	 				 	<td><?php echo $rows['Langugage']; ?></td>
	 				 	<td><?php echo $rows['BillNo']; ?></td> 
	 				 	<td><?php echo $rows['PurchaseOrderNo']; ?></td>
	 				 	<td><?php echo $rows['EditionNo']; ?></td>
	 				 	<td><?php echo $rows['Remarks']; ?></td>
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
								
							<!-- responsive -->
								<div id="edit" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h4 class="modal-title">Return book</h4>
								</div>
<form action="#" method="post" id="return_book_form" class="horizontal-form">
	<div class="modal-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<input  class="form-control" id="return_books_id" type="hidden">
					
					<label  class="control-label">Fine</label>
					
					<input class="form-control" id="return_book_fine" type="text">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label  class="control-label">Return date</label>
					
					<div class="input-icon">
						<i class="fa fa-calendar"></i>
						<input id="return_date_calender" class="form-control date-picker"  name="txtFromDate" type="text" value="" data-date="" data-date-format="yyyy-mm-dd" data-date-viewmode="years"/>
					</div>
				</div>	
			</div>
			</div><!--end of row-->
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-default">
			Close</button>
			<button type="button" id="return_book_submit_btn" class="btn blue" >
			Save changes</button>
		</div>
	</form>
											
							</div>
							
							<!-- add new rout -->
								<div id="addnew" class="modal fade" tabindex="-1" data-width="760">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
									<h4 class="modal-title">Add New Route</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-6">
											<form action="Editroute1.php" id="form_add_new" method="post" onsubmit="return validate()">
											<h5>Route no</h5>
											<p>
												<input type="text" name="a_route_no" id="a_route_no" class="form-control" placeholder="Route no">
											</p>
											
											<h5>Bus no</h5>
											<p>
												<input type="text" name="a_bus_no" id="a_bus_no" class="form-control" placeholder="Bus no">
											</p>
											<h5>Timing</h5>
											<p>
												<input type="text" name="a_time" id="a_time" class="form-control" placeholder="time">
											</p>
											<h5>Driver name</h5>
											<p>
												<input type="text" name="a_driver_name" id="a_driver_name" class="form-control" placeholder="Driver name">
											</p>
											<h5>Driver mobile</h5>
											<p>
												<input type="text" name="a_driver_no" id="a_driver_no" class="form-control" placeholder="Driver mobile">
											</p>
											<h5>Date time</h5>
											<p>
												<div class="input-icon">
															<i class="fa fa-calendar"></i>
															<input class="form-control date-picker"  name="a_date_time" id="txtFromDate"  type="text" value="" data-date="" data-date-format="yyyy-mm-dd" data-date-viewmode="years"/>

														</div>
											</p>
										</div>
										<div class="col-md-6">
											<h5>Route charge</h5>
											<p>
												<input type="text" name="a_route_charge" id="a_route_charge" class="form-control" placeholder="Route charge">
											</p>
											<h5>Route detail</h5>
											<p>
												<input type="text" name="a_route_detail" id="a_route_detail" class="form-control" placeholder="Route detail">
											</p>
											<h5>Financial year</h5>
											<p>
												<input type="text" name="a_financial_year" id="a_financial_year" class="form-control" placeholder="Financial year">
											</p>
											<h5>User id</h5>
											<p>
												<input type="text" name="a_user_id" id="a_user_id" class="form-control" placeholder="User id">
											</p>
											<h5>Password</h5>
											<p>
												<input type="password" name="a_password" id="a_password" class="form-control" placeholder="password">
											</p>
										
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default">
									Close</button>
									<button type="submit" name="sumbit_add_new" class="btn blue">
									Save changes</button>
								</div>
							</form>
							</div>

							
														<!-- static -->
							
							<div id="static2" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" data-attention-animation="false">
								<div class="modal-body">
									<p>
										 Would you like to delete this entry?
									</p>
								</div>
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default">
									Cancel</button>
									<button type="button" data-dismiss="modal" class="btn blue">
									Yes</button>
								</div>
							</div>


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
<script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="../../assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script>
<script src="../../assets/admin/layout/scripts/demo.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/table-advanced.js"></script>

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="../../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/extensions/Scroller/js/dataTables.scroller.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="library_ajax_jquery.js"></script>

<script>
jQuery(document).ready(function() {       
   // initiate layout and plugins
   Metronic.init(); // init metronic core components
Layout.init(); // init current layout
QuickSidebar.init(); // init quick sidebar
Demo.init(); // init demo features
  TableAdvanced.init();

});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>