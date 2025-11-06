<?php
include '../connection.php';
include '../AppConf.php';

session_start();

$sadmission=$_SESSION['userid'];
$StudentName = $_SESSION['StudentName'];
$StudentClass = $_SESSION['StudentClass'];
$StudentRollNo = $_SESSION['StudentRollNo'];
$class=$_SESSION['StudentClass'];



$ssql="SELECT `rollno`, `gender`, `admission`, `sname`, `english`, `french_sanskrit_hindi`, `math`, `science`, `social_science`, `it`, `add_hindi`, `total`, `percentage` , `ip`, `ped`, `PSYCHO`, `POL.SC`, `GEOGRAPHY`, `HINDI`, `EP` FROM `report_card_temp_12cbse`  where `admission`='$sadmission' ";

$rsquery= mysqli_query($Con, $ssql);
$num_rows=0;


?>
<!DOCTYPE html>
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $SchoolNameuser; ?>| ReportCard</title>
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

			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					
					<!-- Begin: life time stats -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="icon-present"></i>
								<span class="caption-subject font-green-sharp bold uppercase"></span>
								<span class="caption-helper">.</span>
							</div>
							<div class="actions">
								
								<div class="btn-group">
									<a class="btn btn-default btn-circle" href="javascript:;" data-toggle="dropdown">
									<i class="fa fa-share"></i>
									<span class="hidden-480">
									Tools </span>
									<i class="fa fa-angle-down"></i>
									</a>
									<ul class="dropdown-menu pull-right">
										<li>
											<a href="javascript:;">
											Export to Excel </a>
										</li>
										<li>
											<a href="javascript:;">
											Export to CSV </a>
										</li>
										<li>
											<a href="javascript:;">
											Export to XML </a>
										</li>
										
										
									</ul>
								</div>
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-container">
								
								<table class="table table-striped table-bordered table-hover" id="datatable_orders" width="90%" id="sample_1">
								<thead>
								<tr>
									<th colspan="14" style="text-align:center;"><?php echo $SchoolName;?> </th>
								</tr>	
								<tr>
									<th colspan="14" style="text-align:center;">Board Result for Class 12</th>
								</tr>

								
								<tr role="row" class="heading">
									<th width="5%" style="font-size:10px;">
										 S.No.</th>
									<th width="5%" style="font-size:10px;">
										 Adm</th>
									<th width="15%" style="font-size:10px;">
										 Name</th>
									<th width="10%" style="font-size:10px;">
										 Rollno</th>

									<th width="5%" style="font-size:10px;">
										 Gender</th>

									<th width="10%" style="font-size:10px;"> 
									 ENGLISH</th>
					
									<th width="10%" style="font-size:10px;">
									 MATH</th>

									<th width="10%" style="font-size:10px;">
									   PHYSICS</th>

									<th width="10%" style="font-size:10px;">
									 CHEM</th>	 

										<th width="10%" style="font-size:10px;">
									  C. SC</th>	 
									 
									 	<th width="10%" style="font-size:10px;">
									 ECO</th>	  

									 	<th width="10%" style="font-size:10px;">
									  BIO</th>
									 
									 <th width="10%" style="font-size:10px;">ACC</th>
									 
									 <th width="10%" style="font-size:10px;"> BST</th>
									 
									  <th width="10%" style="font-size:10px;">IP</th>
									  
									  <th width="10%" style="font-size:10px;">PED</th>
									  
									   <th width="10%" style="font-size:10px;">PSYCHO</th>

									
										<th width="10%" style="font-size:10px;">
									 POL.SC</th>	

									 	<th width="10%" style="font-size:10px;">
									 GEOGRAPHY</th>	

									 	<th width="10%" style="font-size:10px;">
									 HINDI</th>	

									 	<th width="10%" style="font-size:10px;">
									 EP</th>	


								</tr>
										<?php
			$record_count=1;
				while($rowHo = mysqli_fetch_row($rsquery))
				{

					

					$rollno=$rowHo[0];
					$gender=$rowHo[1];
					$admission=$rowHo[2];
					$sname=$rowHo[3];
					$english=$rowHo[4];
					$french_sanskrit_hindi=$rowHo[5];
					
					$math=$rowHo[6];
					$science=$rowHo[7];
					$social_science=$rowHo[8];
					
					$it=$rowHo[9];
					$add_hindi=$rowHo[10];
					$total=$rowHo[11];
					$percentage=$rowHo[12];
					$ip=$rowHo[13];
					$ped=$rowHo[14];
					$PSYCHO=$rowHo[15];
					$POL=$rowHo[16];
					$GEOGRAPHY=$rowHo[17];
					$HINDI=$rowHo[18];
					$EP=$rowHo[19];

					$num_rows=$num_rows+1;
			?>			
								<tr role="row" class="filter">
								<td width="33">
										 <?php echo $num_rows; ?></td>
									<td>
									 <?php echo $admission; ?></td>					
									 <td>
										<?php echo $sname; ?></td>							
										
                            	<td>
										 <?php echo $rollno; ?>&nbsp; </td>
										 <td>
										<?php echo $gender; ?></td>	

										<td>
					
										<?php echo $english; ?></td>	

										<td>
										<?php echo $french_sanskrit_hindi; ?></td>	
									
										<td>
										<?php echo $math; ?></td>
										
										<td>
										<?php echo $science; ?></td>
										<td>
										<?php echo $social_science; ?></td>
									
										<td>
										<?php echo $it; ?></td>
										
											<td>
										<?php echo $add_hindi; ?></td>

										<td>
										<?php echo $total; ?></td>	
										<td>
										<?php echo $percentage; ?></td>	
              

										<td>
										<?php echo $ip; ?></td>	
										<td>
										<?php echo $ped; ?></td>	
										<td>
										<?php echo $PSYCHO; ?></td>	
										<td>
										<?php echo $POL; ?></td>	
										<td>
										<?php echo $GEOGRAPHY; ?></td>	
											<td>
										<?php echo $HINDI; ?></td>	
											<td>
										<?php echo $EP; ?></td>	

									</tr>
									<?php
									}
									?>
								</thead>
								<tbody>
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

<script src="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>

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

        });
    </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>