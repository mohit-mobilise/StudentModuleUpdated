<?php include '../connection.php';?>

<?php

session_start();

	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	$sadmission=$_REQUEST['txtAdmission'];

$rsStDetail=mysqli_query($Con, "select `sname`,`sclass`,`sfathername`,`MotherName`,`smobile`,`DiscontType`,`RouteForFees`,`MasterClass` ,`FinancialYear` from `student_master` where `sadmission`='$sadmission'");
	$rowSt=mysqli_fetch_row($rsStDetail);
	
	$sadmission1=$sadmission;
	$sname=$rowSt[0];
	$sclass=$rowSt[1];
	$MasterClass=$rowSt[7];
	$sfathername=$rowSt[2];
	$MotherName=$rowSt[3];
	$smobile=$rowSt[4];
	$DiscontType=$rowSt[5];
	$RouteForFees=$rowSt[6];
	$strAdmissionIdFYYear=$rowSt[8];

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

	//echo "select `quarter` from `fees` where `sadmission`='$sadmission' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY' order by `datetime` desc";
	//exit();
		$rsPreviousQuaterPaid=mysqli_query($Con, "select `quarter`,`receipt` from `fees` where `sadmission`='$sadmission' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY' order by `datetime` desc");
	$rowB=mysqli_fetch_row($rsPreviousQuaterPaid);
	$PreviousQuartePaid=$rowB[0];
	$LastPaymentReceiptNo=$rowB[1];
	
	
	
	if($PreviousQuartePaid=="")
	{
	$ListOfQuarter=Q1;
	}
	if(strpos($PreviousQuartePaid, 'Q1')!== false)
	{
	  $ListOfQuarter=Q2;
	}
	 if(strpos($PreviousQuartePaid, 'Q2') !== false)
	{
	  $ListOfQuarter=Q3;
	}
	 if(strpos($PreviousQuartePaid, 'Q3') !== false)
	{
	  $ListOfQuarter=Q4;
	}
 	
	$SelectedQuarter=$ListOfQuarter;

	
	
		$LateFee=0;
		$sstrQ="";
		if (strpos($SelectedQuarter, 'Q1') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q1' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2017-04-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
				
				$sstrQ=$sstrQ."Q1:".$TotalLateDays."/";	
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
		if (strpos($SelectedQuarter, 'Q2') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q2' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2017-07-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
				
				$sstrQ=$sstrQ."Q2:".$TotalLateDays."/";
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
		if (strpos($SelectedQuarter, 'Q3') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q3' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2017-10-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
					
				$sstrQ=$sstrQ."Q3:".$TotalLateDays."/";
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
		if (strpos($SelectedQuarter, 'Q4') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q4' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2018-01-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
					
				$sstrQ=$sstrQ."Q4:".$TotalLateDays."/";
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
				if (strpos($SelectedQuarter, 'Q5') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q5' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2018-01-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
					
				$sstrQ=$sstrQ."Q5:".$TotalLateDays."/";
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
				if (strpos($SelectedQuarter, 'Q6') !== false) 
			{
				$rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='Q6' and financialyear='$FeeCollectionFY' limit 1");
				$rowLastDt=mysqli_fetch_row($rsLastDt);
				$LastDate=$rowLastDt[0];
				//$LastDate="2018-01-15";
				$now = time(); // Current Date time
				$Dt1=$LastDate;
				$your_date = strtotime($Dt1);
				$datediff = $now - $your_date;
				if ($datediff > 0)
					$TotalLateDays= floor($datediff/(60*60*24));
				else
					$TotalLateDays= 0;
					
				$sstrQ=$sstrQ."Q6:".$TotalLateDays."/";
				$LateFee =$LateFee+ $TotalLateDays*5;
			}
	
	
	//*************Late Fee Calculation End here*********
	
		
		      $rsQ1Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` LIKE '%$ListOfQuarter%'  and `FinancialYear`='$FeeCollectionFY' and `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ1Bounce)>0)
			$FeeBounceInQ1="yes";
		$rsQ2Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` LIKE '%$ListOfQuarter%'  and `FinancialYear`='$FeeCollectionFY'and `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ2Bounce)>0)
			$FeeBounceInQ2="yes";
		$rsQ3Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` LIKE '%$ListOfQuarter%' and `FinancialYear`='$FeeCollectionFY'and  `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ3Bounce)>0)
			$FeeBounceInQ3="yes";
		$rsQ4Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and`quarter` LIKE '%$ListOfQuarter%' and `FinancialYear`='$FeeCollectionFY' and `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ4Bounce)>0)
		$FeeBounceInQ4="yes";
				$rsQ5Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and`quarter` LIKE '%$ListOfQuarter%' and `FinancialYear`='$FeeCollectionFY' and `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ5Bounce)>0)
			$FeeBounceInQ5="yes";
			$rsQ6Bounce=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and`quarter` LIKE '%$ListOfQuarter%' and `FinancialYear`='$FeeCollectionFY' and `cheque_status`='Bounce'");
		if(mysqli_num_rows($rsQ6Bounce)>0)
			$FeeBounceInQ6="yes";

		
	
	
	$rsFee1=mysqli_query($Con, "select distinct `feeshead` from `fees_student` where `sadmission`='$sadmission' and `financialyear`='$FeeCollectionFY' ORDER BY `feeshead` ");


//Check Quarterwise paid status for sadmission
$rsQ1=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` like '%Q1%' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY'");
if(mysqli_num_rows($rsQ1)>0)
	$FeePaidInQ1="yes";
$rsQ2=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` like '%Q2%' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY'");
if(mysqli_num_rows($rsQ2)>0)
	$FeePaidInQ2="yes";
$rsQ3=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` like '%Q3%' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY'");
if(mysqli_num_rows($rsQ3)>0)
	$FeePaidInQ3="yes";
$rsQ4=mysqli_query($Con, "select * from `fees` where `sadmission`='$sadmission' and `quarter` like '%Q4%' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY'");
if(mysqli_num_rows($rsQ4)>0)
	$FeePaidInQ4="yes";
	

	$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear`,`datetime`,`finalamount`,`cheque_status` FROM `fees` where `sadmission`='$sadmission' and `FeesType`='Regular' and `FinancialYear`='$FeeCollectionFY' order by `datetime` desc ";
	$rsPaymentHistory = mysqli_query($Con, $ssql);
	

?>
<?php
	$recno=0;
	$HeadWiseAmountTotal=0;
	$FeeHeadPreviousBalance=0;
	$TotalFeeHeadPreviousBalance=0;
	while($rowFeeHead1=mysqli_fetch_row($rsFee1))
	{
			$OriginalAmount="";
			$DiscountAmount="";
			$amount="";
			$FeeHeadPreviousBalance="";
			
		$feeshead=$rowFeeHead1[0];
		$ssql="select `Balance` from `fees_transaction` where `feeshead`='$feeshead' and `sadmission`='$sadmission' and  `ReceiptNo`='$LastPaymentReceiptNo' and `cheque_status`!='Bounce' and `FinancialYear`='$FeeCollectionFY'";
		$rs2= mysqli_query($Con, $ssql);
		$rowBalance=mysqli_fetch_row($rs2);
		if(mysqli_num_rows($rs2)>0)
		{
			if($rowBalance[0]=="")
				$FeeHeadPreviousBalance=0;
			else
				$FeeHeadPreviousBalance=$rowBalance[0];
		}
		else
		{
			$FeeHeadPreviousBalance=0;
		}
		$TotalFeeHeadPreviousBalance=$TotalFeeHeadPreviousBalance+$FeeHeadPreviousBalance;
		//echo "select distinct `feeshead`,sum(`head_original_amount`),sum(`head_concession_amount`),sum(`amount`) from `fees_student` where `sadmission`='$sadmission' and `feeshead`='$feeshead' and `Quarter`='$SelectedQuarter' and `financialyear`='$FeeCollectionFY' group by `feeshead`"."<br>";
		$rsFee=mysqli_query($Con, "select distinct `feeshead`,sum(`head_original_amount`),sum(`head_concession_amount`),sum(`amount`) from `fees_student` where `sadmission`='$sadmission' and `feeshead`='$feeshead' and `Quarter`='$SelectedQuarter' and `financialyear`='$FeeCollectionFY' group by `feeshead`");
		while($rowFeeHead=mysqli_fetch_row($rsFee))
		{
			//$feeshead=$rowFeeHead[0];
			$OriginalAmount=$rowFeeHead[1];
			$DiscountAmount=$rowFeeHead[2];
			$amount	=$rowFeeHead[3];
		}
		$amount=$amount+$FeeHeadPreviousBalance;
		$HeadWiseAmountTotal=$HeadWiseAmountTotal+$amount;

		$recno=$recno+1;
	}	
	?>
	
<?php
	$ChequeBounceYesNo="";
	if($FeeBounceInQ1=="yes" || $FeeBounceInQ2=="yes" || $FeeBounceInQ3=="yes"|| $FeeBounceInQ4=="yes")
	{
	
	    $ChequeBounceCharges=100;
	    $ChequeBounceYesNo="yes";
	  }  
	
	?>

<?php
$FeeAmountWithLateFee=$HeadWiseAmountTotal+$LateFee;

$OrderAmount=$HeadWiseAmountTotal+$ChequeBounceCharges+$LateFee;
//$OrderAmount="1";
$Cnt=1;

?>


<script type="text/javascript">
$(document).ready(function () {
    //Disable cut copy paste
    $('body').bind('cut copy paste', function (e) {
        e.preventDefault();
    });
   
    //Disable mouse right click
    $("body").on("contextmenu",function(e){
        return false;
    });
});

function ShowReceipt(receiptno)
{
	var myWindow = window.open("ShowReceipt.php?Receipt=" + receiptno ,"","width=700,height=650");	
}

</script>
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


<!-- END HEADER -->
<!-- BEGIN CONTAINER -->
<div class="page-container">
	<!-- BEGIN SIDEBAR -->
	
		
	<!-- END SIDEBAR -->
	<!-- BEGIN CONTENT -->
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
								<i class="fa fa-rupee"></i>
								<span class="caption-subject font-green-sharp bold uppercase">My Fees</span>
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
						
			<?php
		      
		   $ssqlq = "SELECT `quarter` FROM `fees` where `sadmission`='$sadmission' and `quarter` LIKE '%Q4%' and `cheque_status` !='Bounce' order by `datetime` desc ";
					$rsq = mysqli_query($Con, $ssqlq);

				while($rowq = mysqli_fetch_row($rsq))
					{
					     $quarterq =$rowq[0];
					     
					     $quarterq1 = end(explode(",", $quarterq));
					     // echo $quarterq1;
					}

					if($quarterq1 != "Q4")
					{
					
		?>
						<div class="portlet-body">
							<div class="table-container">
								
								<div class="table-responsive">

								<table class="table table-striped table-bordered table-hover">

								<thead>

								<tr>

									<th>

										 Student Detail</th>



								<!---	<th>

										 Pay Fees Online</th>--->

									<th>

										 Receipt</th>

								

								</tr>

								</thead>

								<tbody>
								
								<tr>

									<td>
 Admission No-:<?php echo $sadmission; ?>
									<br>Student Name:-<br> 
									<?php echo $sname; ?>
									<br>
									Quarter:- <?php echo $SelectedQuarter;?><br>
									
						Late Fees:-  <?php echo $LateFee;?><br>
						 	 </td>

							
	

									<td>
									
								

							
		
	<form name="frmFees" id="frmFees" method="post" action="FeePaymentPayU.php">
							<input type="hidden" name="txtAdmissionNo1" id="txtAdmissionNo1" value="<?php echo $sadmission;?>">
							<input type="hidden" name="txtQuarter<?php echo $Cnt;?>" id="txtQuarter<?php echo $Cnt;?>" value="<?php echo $SelectedQuarter;?>">
							<a onclick="Javascript:ValidateChallan('<?php echo $Cnt;?>');"class="btn btn-circle btn-default">
								<i class="fa icon-docs"></i>
								<span class="hidden-480">
								View Quarter Wise Fees Detail</span>
								</a>
								
								   <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
								             <input type="hidden" id="cboQuarter" name="cboQuarter" value="<?php echo $SelectedQuarter;?>">
								             <input type ="hidden" id="InstallmentWithoutLateFee" name="InstallmentWithoutLateFee" value="<?php echo $HeadWiseAmountTotal;?>">
								             <input type ="hidden" id="LateFee" name="LateFee" value="<?php echo $LateFee;?>">
								             <input type="hidden" id="InstallmentAmount" name="InstallmentAmount" value="<?php echo $HeadWiseAmountTotal;?>">
								             <input type="hidden" id="BounceCharges" name="BounceCharges" value="<?php echo $ChequeBounceCharges;?>">
								              <input type="hidden" id="OrderAmount" name="OrderAmount" value="<?php echo $OrderAmount;?>">
								             <input type="Submit" value="Pay <?php echo $OrderAmount;?>" id="btn1" class="btn red" />
								             
								             

								</form>
				</td>



								</tr>
							


								

								

								

			<?php

$Cnt=$Cnt+1;

?>





								</tbody>

								</table>
								
								<?php

								} //hide for q4

							?>
							
								<table class="table table-striped table-bordered table-hover" >
	<tr>
		<td style="border-style:solid; border-width:1px; height: 16px; " colspan="3" align="center" >
		<b><font face="Cambria" size="2">Fee Payment History</font></b></td>
	</tr>

		
	<tr>
		<th style="border-style:solid; border-width:1px; height: 16px; width: 122px" align="center" >
		<font face="Cambria" style="font-size: 10pt"><strong>Fee Month</strong></font></th>
		<th style="border-style:solid; border-width:1px; height: 16px; width: 123px" class="style1" align="center" >
		<font face="Cambria" style="font-size: 10pt"><strong>Receipt #</strong></font></th>
		<th style="border-style:solid; border-width:1px; height: 16px; width: 123px" align="center" >
		<font face="Cambria"><strong><font style="font-size: 10pt">Print Receipt</font></strong></font></th>
	</tr>

		
<?php

	$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear`,`datetime`,`finalamount`,`cheque_status` FROM `fees` where `sadmission`='$sadmission'  order by `datetime` desc ";
	$rs = mysqli_query($Con, $ssql);

while($row = mysqli_fetch_row($rs))
	{
					
					$quarter=$row[0];
					$fees_amount=$row[1];
					$amountpaid=$row[2];
					$BalanceAmt=$row[3];
					$status=$row[4];
					$receipt=$row[5];
					$date=$row[6];
					$FinancialYear=$row[7];
					$FinalAmount=$row[9];
					$ChequeStatus=$row[10];				
?>
	<tr>
		<td style="border-style:solid; border-width:1px; height: 16px; width: 122px" align="center" >
		<font face="Cambria" style="font-size: 10pt">
		<?php echo $quarter;?>
		</font></td>
		<td style="border-style:solid; border-width:1px; height: 16px; width: 123px" align="center" >
		<font face="Cambria" style="font-size: 10pt">
<?php echo $receipt; ?>
		</font>
		</td>
		<td style="border-style:solid; border-width:1px; height: 16px; width: 123px">
		<p align="center"><font face="Cambria">
		<input name="PrintQ1Receipt" type="button" value="Print Reciept" class="btn yellow" onclick="Javascript:ShowReceipt('<?php echo $receipt; ?>');"><font size="2">
		</font></font>
		</td>
	</tr>
	
	
<?php
}
?>
</table>
<div id="myModal" class="reveal" data-reveal></div>
								

								<br>

							
								

<h2 style="color:red;"><font face="Cambria">*Please wait for 48 hrs before making next transaction, if fees amount is already deducted from your Credit Card/Debit Card/Net Banking or any other payment mode...</font></h2>


	

	<br>


<p><b><i><font face="Cambria"> <a href="privacy.php">Click here to read Terms & Conditions and Privacy Policy </a> for online fees payment</a></font></i></b><p><b><i><font face="Cambria">- Please 

call at School Reception for further details</font></i></b>

	

							</div>
							</div>
						</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
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

<script language="javascript">

function ValidateChallan(cnt)
{
	if (document.getElementById("txtAdmissionNo1").value=="")
	{
		alert("Please enter student addmission id");
		return;
	}
	
	
	var seladmno=document.getElementById("txtAdmissionNo1").value;
	var ctrlQuarter="txtQuarter" + cnt;
	var selquarter=document.getElementById(ctrlQuarter).value;

	$.get("../Admin/Fees/DisplayClassWiseChallan.php?txtAdmissionNo=" + seladmno + "&txtQuarter=" + selquarter,function(data) 
	{
		//alert(data);
  		document.getElementById("myModal").innerHTML =data;
  		
  		//$("#myModal").html(data).foundation("open");
  		//$('#myModal').html(data).modal('show');
  		
  		
	});
	//var myWindow = window.open("../Admin/Fees/DisplayClassWiseChallan_Hold.php?txtAdmissionNo=" + seladmno + "&txtQuarter=" + selquarter ,"","width=1400px,height=800px");
	
	//window.showModalDialog("../Admin/Fees/DisplayClassWiseChallan_Hold.php?txtAdmissionNo=" + seladmno + "&txtQuarter=" + selquarter);
	//document.getElementById("txtAdmissionNo").value=seladmno;
	//document.getElementById("txtQuarter").value=selquarter;
	//document.getElementById("frmTest").submit();
}
function fnlSubmit()
{
	hQuarter=document.getElementById("hQuarter").value;
	hLateFee=document.getElementById("hLateFee").value;
	hBounceCharges=document.getElementById("hBounceCharges").value;
	hadmission=document.getElementById("hadmission").value;
	TotalFeeAmount=document.getElementById("TotalFeeAmount").value;
	FeeAmountWithoutLateFee=document.getElementById("FeeAmountWithoutLateFee").value;
	FeeAmountWithLateFee=document.getElementById("FeeAmountWithLateFee").value;
	LateDays=document.getElementById("LateDays").value;
	currency=document.getElementById("currency").value;
	merchant_id=document.getElementById("merchant_id").value;
	order_id=document.getElementById("order_id").value;
	amount=document.getElementById("amount").value;
	txtAdmissionNo1=document.getElementById("txtAdmissionNo1").value;
	merchantTxnId=document.getElementById("merchantTxnId").value;

	$.get("ICICPayment1.php?hQuarter=" + hQuarter + "&hLateFee=" + hLateFee + "&hBounceCharges=" + hBounceCharges + "&hadmission=" + hadmission + "&TotalFeeAmount=" + TotalFeeAmount + "&FeeAmountWithoutLateFee=" + FeeAmountWithoutLateFee + "&FeeAmountWithLateFee=" + FeeAmountWithLateFee + "&LateDays=" + LateDays + "&currency=" + currency + "&merchant_id=" + merchant_id + "&order_id=" + order_id + "&amount=" + amount + "&txtAdmissionNo1=" + txtAdmissionNo1 + "&merchantTxnId=" + merchantTxnId,function(data) 
	{
		//alert(data);
		if(data="successfull")
		{
			document.getElementById("frmFees").submit();
		}
		else
		{
			alert(data);
			return;
		}
  		//document.getElementById("myModal").innerHTML =data;
  		
  		//$("#myModal").html(data).foundation("open");
  		//$('#myModal').html(data).modal('show');
  		
  		
	});
}
</script>
<form name ="frmTest" id ="frmTest" method ="post" action ="../Admin/Fees/DisplayClassWiseChallan.php" target ="_blank">
<input type ="hidden" name ="txtAdmissionNo" id="txtAdmissionNo" value ="">
<input type ="hidden" name ="txtQuarter" id="txtQuarter" value ="">

</form>