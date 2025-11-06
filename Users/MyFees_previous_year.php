
<?php include '../connection.php';


$rsCurent= mysqli_query($Con, "SELECT `year` FROM `FYmaster` where `Status`='Active'");
$rowCurrent=mysqli_fetch_row($rsCurent);
$CurrentYear=$rowCurrent[0];
$previousY=($CurrentYear-1);
$previousY="../connection_".$previousY.".php";
include "$previousY";

?>

<?php
session_start();

$StudentClass = $_SESSION['StudentClass'];
$StudentRollNo = $_SESSION['StudentRollNo'];
$sadmission=$_SESSION['userid'];


$rsStDetail=mysqli_query($Con, "select `sname`,`sclass`,`sfathername`,`MotherName`,`smobile`,`DiscontType`,`RouteForFees`,`MasterClass` ,`FinancialYear`,`FeeSubmissionType` from `student_master` where `sadmission`='$sadmission'");
	
$rowSt=mysqli_fetch_row($rsStDetail);

$sadmission1=$sadmission;
$sname=$rowSt[0];
$sclass=$rowSt[1];
$masterclass=$rowSt[7];
$sfathername=$rowSt[2];
$MotherName=$rowSt[3];
$smobile=$rowSt[4];
$DiscontType=$rowSt[5];
$RouteForFees=$rowSt[6];
$strAdmissionIdFYYear=$rowSt[8];
$FeeSubmissionType=$rowSt[9];

$rsCFY= mysqli_query($Con, "SELECT `year` FROM `FYmaster` where `Status`='Active'");
$rowCurrentFy=mysqli_fetch_row($rsCFY);
$CurrentFY=$rowCurrentFy[0];


if($CurrentFY==$strAdmissionIdFYYear)
{
$FeeCollectionFY=$CurrentFY;
}
elseif($CurrentFY<$strAdmissionIdFYYear)
{
$FeeCollectionFY=$strAdmissionIdFYYear;
}
else
{
$FeeCollectionFY=$CurrentFY;
}


$sqlCalChk = mysqli_query($Con, "SELECT `fee_month_show_monthly` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` limit 1 ");
$rsCalChk = mysqli_fetch_assoc($sqlCalChk);
$fee_month_show_monthly = $rsCalChk['fee_month_show_monthly'];

$sqlMonth = mysqli_query($Con, "SELECT `Month`,`Quarter` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` ");

$sqlfeehead = mysqli_query($Con, "SELECT DISTINCT `a`.`head_id`,`b`.`FeesHeadName` FROM `fees_student` as `a` LEFT JOIN `Fees_Head` as `b` ON (`a`.`head_id`=`b`.`head_id`) WHERE `a`.`sadmission`='$sadmission' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`head_id`!='101' ORDER BY `b`.`head_priority`");

$sqlfeehistory = mysqli_query($Con, "SELECT `receipt_no`,`cr_amnt`,DATE_FORMAT(`datetime`,'%d-%m-%Y') as `date`,`cheque_status`,`dr_amnt`,`ChequeBounceRemark` FROM `fees` WHERE `sadmission`='$sadmission' and `status`='1' and `FinancialYear`='$CurrentFY' and `cheque_status` in ('Clear','Bounce','Online') ");


function getBounceFee($class,$adm,$year)
{

  $sqlbouncefee = mysqli_query($Con, "SELECT `Bounce_charge` FROM `Fees_MonthQuaterMapping` WHERE  `Class`='$class' ");
  $rsbouncefee=mysqli_fetch_row($sqlbouncefee);
  $Bounce_charge=$rsbouncefee[0];
  
  $bounce_count = 0;
  $sqlchkstatus = mysqli_query($Con, "SELECT `cheque_status`  FROM `fees` WHERE `sadmission`='$adm'  ORDER BY `created_at` ");
  while ($rschkstatus = mysqli_fetch_assoc($sqlchkstatus)) {
    
    $cheque_status = '';
    $cheque_status = $rschkstatus['cheque_status'];

    if ($cheque_status == 'Bounce') {
        
        $bounce_count++;
    
    }

    if ($bounce_count  > 0 && $cheque_status == 'Clear') {
       
       $bounce_count=0;

    }

  
  }

  $bounce_amt = $Bounce_charge * $bounce_count;

  return $bounce_amt;

}


function fnlLateFee($month,$class,$adm,$year)
{



  $ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
  $rsFY= mysqli_query($Con, $ssqlFY);

  $row4=mysqli_fetch_row($rsFY);

  $CurrentFinancialYear=$row4[0];

    

  
    $sqllatefee = mysqli_query($Con, "SELECT `last_fees_start_date`,`LastFee_date_1`, `LastFee_date_2`, `LastFee_date_3`,`Late_fees`, `Late_fees_1`, `Late_fees_2`, `Late_fees_3` FROM `Fees_MonthQuaterMapping` WHERE  `Class`='$class' and `Month`='$month' and `status`='1' ");
    while ($rslatefee = mysqli_fetch_row($sqllatefee)) 
    {
       $Dt1 = $rslatefee[0];  
       $Days_difference1 = $rslatefee[1]; 
       $Days_difference2 = $rslatefee[2]; 
       $Days_difference3 = $rslatefee[3]; 
       $Late_fees = $rslatefee[4];  
       $LateFee2 = $rslatefee[5]; 
       $LateFee3 = $rslatefee[6]; 
       $LateFee4 = $rslatefee[7]; 
    }
             
    $LateFee=0;

    // $rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='$quarter' and `Month`='$month' and  financialyear='$year' limit 1");
    // $rowLastDt=mysqli_fetch_row($rsLastDt);
    // $LastDate=$rowLastDt[0];
    
    // $now = time();

    // $now = strtotime('2021-09-11');

    $now = strtotime(Date('Y-m-d'));

    // $Dt1=$LastDate;
    $your_date = strtotime($Dt1);
    $datediff = $now - $your_date;
    if ($datediff > 0)
      $TotalLateDays= floor($datediff/(60*60*24));
    else
      $TotalLateDays= 0;
    
    $LateFee =$LateFee+ $TotalLateDays*$Late_fees;


    $todaydate = Date('Y-m-d');

    $sqlfeechk = mysqli_query($Con, "SELECT `amount` FROM `fees_latefee_adjust` WHERE `sadmission`='$adm' and `financialyear`='$year' and `date`='$todaydate' and `Month`='$month' ");
    $rsfeechk = mysqli_fetch_assoc($sqlfeechk);
    $fee_late = $rsfeechk['amount'];
    
    if ($LateFee > 0) {
      
      if ($LateFee > $fee_late) {
      
        $LateFee = $LateFee - $fee_late;
      }
      else {

        $LateFee = 0;
      }


    }
    


  return $LateFee;

}


$bounce_fee = getBounceFee($masterclass,$sadmission,$CurrentFY);


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
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title><?php echo $SchoolNameuser; ?>| My Fees</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>

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


<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>

<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="../../assets/global/css/components-md.css" id="style_components" rel="stylesheet" type="text/css"/>
<link href="../../assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="../../assets/admin/layout4/css/themes/light.css" rel="stylesheet" type="text/css"/>
<link href="../../assets/admin/layout4/css/custom.css" rel="stylesheet" type="text/css"/>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>

<style type="text/css">
	.textalgn
	{
		text-align: right;
	}
</style>
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
<!--<div id="myModal" class="reveal" data-reveal></div>-->
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
	
	<?php include 'side_menu.php'; ?>

	<!-- END SIDEBAR -->

   
  <div id="challan_modal" class="modal in animated shake" tabindex="-1" data-backdrop="static" data-keyboard="false" aria-hidden="false" style="margin-top: -121px !important;">
                                   
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Generate Challan</h4>
        </div>
        <div class="modal-body">
            <input type="hidden" name="challan_adm_no" id="challan_adm_no">
            <input type="hidden" name="challan_class" id="challan_class">

           <div class="row">
               <div class="col-md-2">
                   Year
               </div>
               <div class="col-md-10">
                    <select size="1" name="challan_year" id="challan_year">
                      <option value="<?= $CurrentFY; ?>"><?= $CurrentFY;?></option>
                    </select>
               </div>
           </div>
           <br>
           <div class="row">
               <div class="col-md-2">
                   Month
               </div>
               <div class="col-md-10">
                   <table>
                    <?php
                       while($row1 = mysqli_fetch_assoc($sqlMonth))
                        {
                          $Month1=$row1['Month'];       
                    ?>
               
              <tr>
                <td><input type="checkbox" class="selectmonthchallan" value="<?= $Month1; ?>"></td>
                <td><?= $Month1; ?></td>
              </tr>       
            
          <?php
             }
          ?>
                
                    
                   </table>
               </div>
           </div>

        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal">Close</button>
            <button type="button" name="btngeneratechallan" id="btngeneratechallan" class="submit">
			Generate</button>
        </div>

</div>

	<!-- BEGIN CONTENT -->
	<div class="page-content-wrapper">
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			
			<?php include 'StyleCustomizer.php'; ?>

			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					
					<!-- Begin: life time stats -->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-rupee"></i>
								<span class="caption-subject font-green-sharp bold uppercase">My Fees</span>
								<span class="caption-helper"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <span class="caption-subject font-black-sharp bold uppercase">View Fee Challan: <?= $FeeSubmissionType1; ?>&nbsp;&nbsp;<input type="button" value="Challan" onclick="create_challan('<?= $sadmission; ?>','<?= $masterclass; ?>')"></span>
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
        
        <input type="hidden" name="calculation_show" id="calculation_show" value="<?= $fee_month_show_monthly; ?>">
        <input type="hidden" name="bounce_fee" id="bounce_fee" value="<?= $bounce_fee; ?>">
      
      <div class="row">
          <div class="col-md-6">

            <h4>Payment</h4>
                

	    	<form name="frmFeesMonthly" id="frmFeesMonthly" method="post" action="FeePaymentPayU.php" >
			<input type="hidden" name="admission_no" id="admission_no" value="<?= $sadmission; ?>">
			
			<div class="table-container">
								
			<div class="table-responsive">
								
				<table class="table table-striped table-bordered table-hover">
	             <thead>
	             	<tr>
	             		<th>&nbsp;</th>
	             		<th>Month</th>
	             	    <th>Total Dr.</th>
                        <th>Total Cr.</th>
                        <th>Total Bl.</th>
                        <th>Late Fee</th>
	             	</tr>
	             </thead>
	             <tbody>
                    
                    <?php

                        $total_debitamt = 0;
                        $total_creditamt = 0;
                        $total_balanceamt = 0;
                        $total_latefee = 0;

                        $cnt =1;
                        mysql_data_seek($sqlMonth, 0);
                        while ($rsMonth = mysqli_fetch_assoc($sqlMonth)) {
                            
                            $Month = $rsMonth['Month'];
                            $Quarter = $rsMonth['Quarter'];

                            

                        	$total_dr_amnt = 0;
                            $total_cr_amnt = 0;
                            $total_balance = 0;
                            $latefee = 0;


                            $sqlfindmonth = mysqli_query($Con, "SELECT * FROM `fees_transaction` WHERE `sadmission`='$sadmission'  and `Month`='$Month' and `fy`='$CurrentFY' and `status`='1'");
                            
                            $sqlfindmonth = mysqli_query($Con, "SELECT * FROM `fees_transaction` as `a` LEFT JOIN `fees` as `b` on (`a`.`sadmission`= `b`.`sadmission` and `a`.`receipt_no`= `b`.`receipt_no`) WHERE `a`.`sadmission`='$sadmission'  and `a`.`Month`='$Month' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`cheque_status` in ('Clear','Online') ");

				            if (mysqli_num_rows($sqlfindmonth)>0) {
				                $latefee = 0;
				            }
				            else {
				                $latefee = fnlLateFee($Month,$masterclass,$sadmission,$CurrentFY);
				            }


				            while ($rsfeehead = mysqli_fetch_assoc($sqlfeehead)) {

                            $head_id = $rsfeehead['head_id'];
                    
                            $sqlamt = mysqli_query($Con, "SELECT `dr_amnt`,`cr_amnt`,`pre_dr_amnt`,`pre_cr_amnt` FROM `fees_student` WHERE `sadmission`='$sadmission' and `Month`='$Month' and `fy`='$CurrentFY' and `head_id`='$head_id' and `status`='1' ");

                            $sqlcramt = mysqli_query($Con, "SELECT SUM(`cr_amnt`) as `cr_amnt`,SUM(`dr_amnt`) as `dr_amnt` FROM `fees_transaction` WHERE `sadmission`='$sadmission' and `head_id`='$head_id' and `Month`='$Month' and `fy`='$CurrentFY' and `status`='1' ");
                            
                            $dr_amnt = ''; 
                            $cr_amnt = '';
                            $balance = '';

                            $debit_amnt = '';
                            $credit_amnt = '';
                            $pre_dr_amnt = '';
                            $pre_cr_amnt = '';

                            $pay_credit_amnt = '';
                            $pay_debit_amnt = '';

                            $rsamt = mysqli_fetch_assoc($sqlamt);
                            $debit_amnt = $rsamt['dr_amnt']; 
                            $credit_amnt = $rsamt['cr_amnt']; 
                            $pre_dr_amnt = $rsamt['pre_dr_amnt']; 
                            $pre_cr_amnt = $rsamt['pre_cr_amnt']; 

                            $dr_amnt = ($debit_amnt + $pre_dr_amnt) - ($credit_amnt + $pre_cr_amnt);

                            $rscramt = mysqli_fetch_assoc($sqlcramt);
                            $pay_credit_amnt = $rscramt['cr_amnt']; 
                            $pay_debit_amnt = $rscramt['dr_amnt']; 

                            $cr_amnt = $pay_credit_amnt - $pay_debit_amnt;

                            $balance = $dr_amnt - $cr_amnt;

                            $total_dr_amnt += $dr_amnt;
                            $total_cr_amnt += $cr_amnt;
                            $total_balance += $balance;

                         
                            } 




                    ?>

                    <tr>
                    	 <td style="width: 10px;">
                    	 	<?php 
	                    	 	if ($total_balance > 0) {
	                    	?>
                            
                            <input type="checkbox" class="getamt" name="mycheckbox[]" id="<?= "chkcheckbox_".$cnt; ?>" value="<?= $cnt; ?>" <?php if($fee_month_show_monthly == '1' || $fee_month_show_monthly == 1) { ?> checked <?php } ?> >

                            <input type="hidden" name="<?= "chkfeestatus_".$cnt; ?>" id="<?= "chkfeestatus_".$cnt; ?>" value="notpaid"  >

	                    	<?php 		
	                    	 	}
	                    	 	else {
                            ?>
                            <input type="hidden" name="<?= "chkfeestatus_".$cnt; ?>" id="<?= "chkfeestatus_".$cnt; ?>" value="paid" >

                            <?php
	                    	 	}
	                    	?> 	            	 	
                    	 </td>
                         
                  <input type="hidden" name="<?= "month_".$cnt; ?>" id="<?= "month_".$cnt; ?>" value="<?= $Month; ?>" >
                  <input type="hidden" name="<?= "debit_".$cnt; ?>" id="<?= "debit_".$cnt; ?>" value="<?= $total_dr_amnt; ?>" >
                  <input type="hidden" name="<?= "credit_".$cnt; ?>" id="<?= "credit_".$cnt; ?>" value="<?= $total_cr_amnt; ?>" >
                  <input type="hidden" name="<?= "balance_".$cnt; ?>" id="<?= "balance_".$cnt; ?>" value="<?= $total_balance; ?>">
                  <input type="hidden" name="<?= "latefee_".$cnt; ?>" id="<?= "latefee_".$cnt; ?>" value="<?= $latefee; ?>"  >
                  <input type="hidden" name="<?= "bounce_".$cnt; ?>" id="<?= "bounce_".$cnt; ?>" value="0"  >
                    
                             
                    	<th><?= $Month; ?>
                    	 &nbsp;<i class="fa fa-paper-plane" style="color:blue;" title="View <?= $Month; ?> Challan" onclick='monthwisechallan("<?= $sadmission; ?>","<?= $masterclass; ?>","<?= $CurrentFY; ?>","<?= $Month; ?>")'></i>
                    	</th>
                    	 <td><?= $total_dr_amnt; ?></td>
                    	 <td><?= $total_cr_amnt; ?></td>
                    	 <td><?= $total_balance; ?></td>
                    	 <td><?= $latefee; ?></td>
                    </tr>
                    
                    <?php    

                       $total_debitamt += $total_dr_amnt;
                       $total_creditamt += $total_cr_amnt;
                       $total_balanceamt += $total_balance;
                       $total_latefee += $latefee;

                       mysql_data_seek($sqlfeehead, 0); 

                       $cnt++;

                       }

                    ?>

                    <tr>
                    	<td>&nbsp;</td>
                    	<th>Grand Total</th>
                    	<th><?= number_format($total_debitamt,2); ?></th>
                    	<th><?= number_format($total_creditamt,2); ?></th>
                    	<th><?= number_format($total_balanceamt,2); ?></th>
                    	<th><?= number_format($total_latefee,2); ?></th>
                    </tr>
                           	
	             </tbody>
	            </table>

	            <div class="row">
	            	<div class="col-md-8">
                  &nbsp;
                  <h4>Bounce Charge : &nbsp;

                  <?= $bounce_fee; ?>

                  </h4>

	            		<!-- <h4>Payment Mode: <?= $FeeSubmissionType; ?></h4> -->
	            	</div>
	            	<div class="col-md-4">
	            		Pay <i class="fa fa-rupee"></i> <input type="button" onclick="validatefees();" name="total_amount" id="total_amount" value="0" class="btn btn-danger">
	            	</div>
	            </div>

			</div>
			
			</div>
		</form>


      </div>

      <div class="col-md-6">

        <h4>Payment history</h4>

        <div class="table-container">
                
          <div class="table-responsive">
                
            <table class="table table-striped table-bordered table-hover">
              <thead>
              <tr>
                  <th>Month</th>
                  <th>Receipt#</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Date</th>
              </tr>
              </thead>
              <tbody>
              
              <?php
                while ($rsfeehistory = mysqli_fetch_assoc($sqlfeehistory)) 
                {

                  $paid_amount = '';
                  $paid_debit_amnt = '';

                  $receipt_no = $rsfeehistory['receipt_no'];
                  $paid_amount = $rsfeehistory['cr_amnt'];
                  $feedate = $rsfeehistory['date'];
                  $cheque_status = $rsfeehistory['cheque_status'];
                  $paid_debit_amnt = $rsfeehistory['dr_amnt'];
                  $remark = $rsfeehistory['ChequeBounceRemark'];

                
                  // $sqlmonth_his = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `fees_transaction` WHERE `receipt_no`='$receipt_no' and `status`='1' and `fy`='$CurrentFY' ");
                  
                  // $monthname = '';  
                  // while ($rsmonth_his = mysqli_fetch_assoc($sqlmonth_his)) {

                  //   $month_name = $rsmonth_his['Month'];

                  //   $monthname .= $month_name.",";
                    
                  // }

                  // $month_h = trim($monthname,",");

                  $sqlmonth_his = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `fees_transaction` WHERE `receipt_no`='$receipt_no' and `status`='1' and `fy`='$CurrentFY' ORDER BY `srno` limit 1 ");

                  $rsmonth_his = mysqli_fetch_assoc($sqlmonth_his);
                  $month_h = $rsmonth_his['Month'];

                  $sqlmonth_his1 = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `fees_transaction` WHERE `receipt_no`='$receipt_no' and `status`='1' and `fy`='$CurrentFY' ORDER BY `srno` desc limit 1 ");
                  
                  $rsmonth_his1 = mysqli_fetch_assoc($sqlmonth_his1);
                  $month_h1 = $rsmonth_his1['Month'];

                  if ($month_h == $month_h1) {
                      
                      $month_his = $month_h;
                  }
                  else
                  {
                      $month_his = $month_h." to ".$month_h1;
                  }


                  if ($paid_amount != '') {
                     
                    $amt = number_format($paid_amount,2);   
                  }
                  else {
                    $amt = number_format($paid_debit_amnt,2);   
                  }
                  
                  

                ?>

                <tr>
                  <td><?= $month_his; ?></td>
                  <td><?= $receipt_no; ?>&nbsp; <i class="fa fa-print" onclick="open_fee_receipt('<?= $sadmission; ?>','<?= $receipt_no; ?>')"></i></td>
                  <td><?= $amt; ?></td>
                  <td><?= $cheque_status; ?></td>
                  <td><?= $feedate; ?></td>  
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

    <div class="row">
      <div class="col-md-12">
        <h2 style="color:red;"><font face="Cambria">*Please wait for 48 hrs before making next transaction, if fees amount is already deducted from your Credit Card/Debit Card/Net Banking or any other payment mode...</font></h2>

        <br>
        <p><b><i><font face="Cambria"> <a href="privacy.php">Click here to read Terms & Conditions and Privacy Policy </a> for online fees payment</font></i></b></p>
        <p><b><i><font face="Cambria">- Please call at School Reception for further details</font></i></b></p>
        <br>
        <!--<a href="#"><button class="btn btn-danger" style="height:50px;">Click here to Download the Previous Fees Certificate</button></a>-->
      </div>
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
<script src="../../assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>


<script src="../../assets/global/plugins/bootstrap-pwstrength/pwstrength-bootstrap.min.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript"></script>
<script src="../../assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/ui-extended-modals.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="../../assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/layout.js" type="text/javascript"></script>
<script src="../../assets/admin/layout4/scripts/demo.js" type="text/javascript"></script>
<script src="../../assets/global/scripts/datatable.js"></script>
<script src="../../assets/admin/pages/scripts/profile.js" type="text/javascript"></script>
<script src="../../assets/admin/pages/scripts/ecommerce-orders.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script src="myfees.js" type="text/javascript"></script>

<script type="text/javascript">	
	jQuery(window).load(function() {
        $(".checker").removeClass();
    });
</script>

<script type="text/javascript">
// $(document).ready(function () {
//     //Disable cut copy paste
//     $('body').bind('cut copy paste', function (e) {
//         e.preventDefault();
//     });
   
//     //Disable mouse right click
//     $("body").on("contextmenu",function(e){
//         return false;
//     });
// });
</script>

<script>
    jQuery(document).ready(function() {    
       Metronic.init(); // init metronic core components
       Layout.init(); // init current layout
       Demo.init(); // init demo features
       UIExtendedModals.init();
       //EcommerceOrders.init();
       Profile.init(); // init page demo

    });
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>

<form method="post" name="frmfeereceipt" id="frmfeereceipt" target="_blank" action="../Admin/fee_ledger/fee_receipt_previous_year.php">
  <input type="hidden" name="receipt_no" id="receipt_no">
  <input type="hidden" name="adm_no" id="adm_no">
</form>


<form method="post" name="frmdisplaychallan" id="frmdisplaychallan" target="_blank" action="../Admin/fee_ledger/DisplayClassWiseChallan_monthly.php">
  <input type="hidden" name="master_class" id="master_class">
  <input type="hidden" name="sadm_no" id="sadm_no">
  <input type="hidden" name="year" id="year">
  <input type="hidden" name="month" id="month">
</form>