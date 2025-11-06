
<?php
session_start(); 
include '../../connection.php';?>
<?php include '../../AppConf.php';?>
<?php 
$loggedin_user=$_REQUEST['userid'];


if($loggedin_user=='' ){
$loggedin_user=$_SESSION['userid'];

$imei="";
$source="web";
}
else{
$imei=$_REQUEST['imei']; 
$source="mobile";


}

if($loggedin_user==''){
	 echo "<script>alert('Please Login again');
      window.history.go(-2);
    </script>"; 

}

$rsappId=mysqli_query($Con, "SELECT `app_id` FROM `emp_student_resonceform_mapping` WHERE  `vendor_id`='$loggedin_user' and `status`='Active '");
$rowAppId=mysqli_fetch_row($rsappId); 
$app_Id=$rowAppId[0];


$rsStudentDetail=mysqli_query($Con, "select `sname`,`DOB`,`Sex`,`sclass`,`LastSchool`,`Address`,`sfathername`,`FatherEducation`,`FatherOccupation`,`smobile`,`email` from `student_master` where `sadmission`='$loggedin_user'");
if(mysqli_num_rows($rsStudentDetail) == 0)
{
    $rsStudentDetail=mysqli_query($Con, "select `Name`,`DOB`,`Gender`,`Designation` from `employee_master` where `EmpId`='$loggedin_user'");
}

while($rowS=mysqli_fetch_row($rsStudentDetail))
{
	$Nsname=$rowS[0];
	$sclass=$rowS[3];
	

	
	break;
}



?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!DOCTYPE html>

<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Feedback Form</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="../assets/global/css/loader.css" rel="stylesheet" type="text/css">
<meta content="" name="description"/>
<meta content="" name="author"/>
<!--<link href="../assets/global/css/loader.css" rel="stylesheet" type="text/css">-->


<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<script src="../../assets/global/plugins/jquery.min.js" type="text/javascript"></script>

 <script type="text/javascript" src="../../assets/global/plugins/jquery.dataTables.min.js"></script>

<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/jquery.dataTables.min.css"/>
 <link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css" />
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/extensions/ColReorder/css/dataTables.colReorder.min.css" />
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
<link href="../../assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css" />
<link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css" />
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-toastr/toastr.min.css">
<link href="../../assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<style type="text/css">
  .portlet > .portlet-title {
    border-bottom: 1px solid #eee !important;
    margin-bottom: 10px;
}
</style>
<style>

   .myselectques {
  width: 500px;
  overflow: hidden;
  white-space: pre;
  text-overflow: ellipsis;
  -webkit-appearance: none;
  }

    a {
	color: #0254EB
    }
    a:visited {
    	color: #0254EB
    }
    a.morelink {
    	text-decoration:none;
    	outline: none;
    }
    .morecontent span {
    	display: none;
    }
    .comment {
    	width: 100%;
    	background-color: #f0f0f0;
    	margin: 10px;
    }
    .ins_text {
      margin-left: 40px;
    }

    .serv_fontsize{
      font-size: 14px;
    }

#vendor_aux_filter
{
  display: none;
}

#vendor_aux_info
{
  display: none;
}

.paginate_button
{
  display: none;
}
</style>
</head>

<body class="page-md page-header-fixed page-sidebar-closed-hide-logo page-sidebar-closed page-sidebar-closed">
<!--<div class="loading">Loading…</div>-->

<!-- BEGIN CONTAINER -->
<div class="container-fluid">


      	<p align="center">




	
													<div class="row">

														<div class="col-md-5 col-xs-6 col-sm-6" >
                                                         <p align="right">
                                                         <img src="<?php echo $SchoolLogo;?>" width="120" height="95">
														</div>
														<div class="col-md-7  col-xs-6 col-sm-6">
															<b><p><?php echo $SchoolName;?><br>
															<?php echo $SchoolAddress; ?><br>
															Phone No: <?php echo $SchoolPhoneNo; ?><br>
															Email Id: <?php echo $SchoolEmailId; ?>	
															
														   </p></b>
														   
														</div>

													</div>
													<div class="row">
													    <div class="col-sm-12"><p style="text-align:center"><b><BR><BR>
													             Date: <?php echo date("d-M-Y");?><br><br>
													             <span style="text-align:center"><?php echo $Nsname;?></span> /<span><?php echo $loggedin_user;?></span>/
													              <span  ><?php echo $sclass;?></span>
													    </b></p>
													    
													    </div>
													</div>










<br>
</p>


      <!-- -========================================================================================== -->
      <!-- BEGIN OF TAB PORTLET -->
      <div class="row">
        <div class="col-md-12">
          <div class="portlet box blue-hoki">
						<div class="portlet-title">
							<div class="caption">
								<!--<span class="caption-subject font-green-sharp bold uppercase">-->
							<i class="fa fa-gift"></i>Teacher's Question Feedback Form
							</div>
							
						</div>
          <!-- BEGIN Portlet PORTLET-->
          <div class="portlet light">
           
              <ul class="nav" >
                
                
             
                <li class="">
                  <a href="#portlet_tab8" >
                  </a>
                </li>   
                       
                
 
              </ul>

             <input type="hidden" name="user_id" id="user_id" value="<?php echo $loggedin_user; ?>">
             <input type="hidden" name="app_id" id="app_id" value="<?php echo $app_Id;?>">
             <input type="hidden" name="current_class" id="current_class" value="<?php echo $sclass;?>">

             
              

                <div id="portlet_tab8">
                  <?php
                  
                  
                  include  'update_tab_vend_question_master.php' ;
                   ?>
               

               
          


            
            
            </div>
            </div>
          </div>
        
        </div>
      </div>
      <!-- END OF TAB PORTLET -->
      <!-- -========================================================================================== -->
      <!-- END PAGE CONTENT-->
   
  <!-- END CONTENT -->
</div>
<div class="loading" >Loading&#8230;</div>
<!-- END CONTAINER -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="../../assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="../../assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
 <script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
    <script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
  <script src="../../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<script src="../../assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
  
<!-- END CORE PLUGINS -->
<script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>

<script type="text/javascript" src="vendor_reg_update.js"></script>

<script>
jQuery(window).load(function() {
  $(".checker").removeClass();
});
</script>

<script>
      jQuery(document).ready(function() {    
         Metronic.init(); // init metronic core components
Layout.init(); // init current layout
Demo.init(); // init demo features
      });
   </script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>