<?php require '../connection.php'; ?>
<?php require '../AppConf.php';

$source = $_REQUEST['source'];
$enc_admission = $_REQUEST['enc_emp'];
$random_string = $_REQUEST['random'];

/*==================================================
       School valid late fee allow or not
==================================================*/
$lateFeeCheckCalculation=0;
if($BaseURL == 'https://dpsnavimumbai.mobilisepro.com/'){
  $lateFeeCheckCalculation=1;
}


/*==================================================
       End  school valid late fee allow or not
==================================================*/

if($source == 'Mobile' || $source == 'mobile' || $source == 'Web' || $source == 'web') {
  $sadmission = base64_decode($enc_admission);
  $sadmission = trim(filter_var($sadmission, FILTER_SANITIZE_STRING),' ');

} else {
  echo "Invalid Source (XXXX1212::$source::75256265XXXX) !";exit();  
}


$rsStDetail=mysqli_query($Con, "select `sname`,`sclass`,`sfathername`,`MotherName`,`smobile`,`DiscontType`,`RouteForFees`,`MasterClass` ,`FinancialYear`,`FeeSubmissionType`, `Hostel` from `student_master` where `sadmission`='$sadmission'");

if(mysqli_num_rows($rsStDetail) == 0) {
  echo "Invalid Admission No !";exit();  
}

$rsToken=mysqli_query($Con, "SELECT `token` FROM `student_fee_token` WHERE `sadmission`='$sadmission' order by `srno` desc limit 1");
if(mysqli_num_rows($rsToken) == 0) {
  echo "Invalid Token, Please Login again  !";exit();  
}

$rowToken=mysqli_fetch_assoc($rsToken);
$token=$rowToken['token'];

if($token != $random_string) {
    echo "Token mismatch, Please Login again  !";exit();  
}


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
$Hostel=$rowSt[10];

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

$chkFeeTab = mysqli_query($Con, "SELECT `is_fees_access`,`is_hostel_fee_access` FROM user_authentication where `admission_no`='$sadmission'");
$rsFeeTab = mysqli_fetch_assoc($chkFeeTab);
$is_fees_access = $rsFeeTab['is_fees_access'];
$is_hostel_fee_access = $rsFeeTab['is_hostel_fee_access'];

$sqlCalChk = mysqli_query($Con, "SELECT `fee_month_show_monthly` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` limit 1 ");
$rsCalChk = mysqli_fetch_assoc($sqlCalChk);
$fee_month_show_monthly = $rsCalChk['fee_month_show_monthly'];

$sqlHstlCalChk = mysqli_query($Con, "SELECT `fee_month_show_monthly` FROM `hostel_Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` limit 1 ");
$rsHstlCalChk = mysqli_fetch_assoc($sqlHstlCalChk);
$hstl_fee_month_show_monthly = $rsHstlCalChk['fee_month_show_monthly'];

if($is_fees_access == '1') {
    $sqlMonth = '';
} else {
    $sqlMonth = mysqli_query($Con, "SELECT `Month`,`Quarter` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` ");
}

if($is_hostel_fee_access == '1') {
    $sqlHostelMonth = '';
} else {
    $sqlHostelMonth = mysqli_query($Con, "SELECT `Month`,`Quarter` FROM `hostel_Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `fee_pay_status`='1' ORDER by `Priority` ");
}


$sqlfeehead = mysqli_query($Con, "SELECT DISTINCT `a`.`head_id`,`b`.`FeesHeadName` FROM `fees_student` as `a` LEFT JOIN `Fees_Head` as `b` ON (`a`.`head_id`=`b`.`head_id`) WHERE `a`.`sadmission`='$sadmission' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`head_id`!='101' ORDER BY `b`.`head_priority`");
$sqlhostelfeehead = mysqli_query($Con, "SELECT DISTINCT `a`.`head_id`,`b`.`FeesHeadName` FROM `hostel_fees_student` as `a` LEFT JOIN `hostel_Fees_Head` as `b` ON (`a`.`head_id`=`b`.`head_id`) WHERE `a`.`sadmission`='$sadmission' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`head_id`!='101' ORDER BY `b`.`head_priority`");

$sqlfeehistory = mysqli_query($Con, "SELECT `receipt_no`,`cr_amnt`,DATE_FORMAT(`datetime`,'%d-%m-%Y') as `date`,`cheque_status`,`dr_amnt`,`ChequeBounceRemark` FROM `fees` WHERE `sadmission`='$sadmission' and `status`='1' and `FinancialYear`='$CurrentFY' and `cheque_status` in ('Clear','Bounce','Online') ");

$sqlhstlfeehistory = mysqli_query($Con, "SELECT `receipt_no`,`cr_amnt`,DATE_FORMAT(`datetime`,'%d-%m-%Y') as `date`,`cheque_status`,`dr_amnt`,`ChequeBounceRemark` FROM `hostel_fees` WHERE `sadmission`='$sadmission' and `status`='1' and `FinancialYear`='$CurrentFY' and `cheque_status` in ('Clear','Bounce','Online') ");

function getBounceFee($class,$adm,$year)
{

  // $sqlbouncefee = mysqli_query($Con, "SELECT `Bounce_charge` FROM `Fees_MonthQuaterMapping` WHERE  `Class`='$class' ");
  // $rsbouncefee=mysqli_fetch_row($sqlbouncefee);
  // $Bounce_charge=$rsbouncefee[0];
  
  // $bounce_count = 0;
  // $sqlchkstatus = mysqli_query($Con, "SELECT `cheque_status`  FROM `fees` WHERE `sadmission`='$adm'  ORDER BY `created_at` ");
  // while ($rschkstatus = mysqli_fetch_assoc($sqlchkstatus)) {
    
  //   $cheque_status = '';
  //   $cheque_status = $rschkstatus['cheque_status'];

  //   if ($cheque_status == 'Bounce') {
        
  //       $bounce_count++;
    
  //   }

  //   if ($bounce_count  > 0 && ($cheque_status == 'Clear' || $cheque_status == 'Online')) {
       
  //      $bounce_count=0;

  //   }

  
  // }

  // $bounce_amt = $Bounce_charge * $bounce_count;

  // return $bounce_amt;


  $admission = $adm;

  $sqlfyear = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status`='Active'");
  $rsfyear = mysqli_fetch_assoc($sqlfyear);
  $currentyear = $rsfyear['year'];

  $total_bouncecharge = 0;
  $sqlBounceCharge = mysqli_query($Con, "SELECT sum(`bounce_charge`) as `bounce_charge` FROM `fee_bounce_student_data` WHERE `sadmission`='$admission' and `status`='0' and `FinancialYear`='$currentyear' and `fee_type`='Regular Fee' ");
  if(mysqli_num_rows($sqlBounceCharge)) {
    $rsBounceCharge = mysqli_fetch_assoc($sqlBounceCharge);
    $total_bouncecharge = $rsBounceCharge['bounce_charge'] ? $rsBounceCharge['bounce_charge'] : 0;
  }
  
  return $total_bouncecharge;

}


function getHostelBounceFee($class,$adm,$year)
{

  // $sqlbouncefee = mysqli_query($Con, "SELECT `Bounce_charge` FROM `hostel_Fees_MonthQuaterMapping` WHERE  `Class`='$class' ");
  // $rsbouncefee=mysqli_fetch_row($sqlbouncefee);
  // $Bounce_charge=$rsbouncefee[0];
  
  // $bounce_count = 0;
  // $sqlchkstatus = mysqli_query($Con, "SELECT `cheque_status`  FROM `hostel_fees` WHERE `sadmission`='$adm'  ORDER BY `created_at` ");
  // while ($rschkstatus = mysqli_fetch_assoc($sqlchkstatus)) {
    
  //   $cheque_status = '';
  //   $cheque_status = $rschkstatus['cheque_status'];

  //   if ($cheque_status == 'Bounce') {
        
  //       $bounce_count++;
    
  //   }

  //   if ($bounce_count  > 0 && ($cheque_status == 'Clear' || $cheque_status == 'Online')) {
       
  //      $bounce_count=0;

  //   }

  
  // }

  // $bounce_amt = $Bounce_charge * $bounce_count;

  // return $bounce_amt;


  $admission = $adm;

  $sqlfyear = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status`='Active'");
  $rsfyear = mysqli_fetch_assoc($sqlfyear);
  $currentyear = $rsfyear['year'];

  $total_bouncecharge = 0;
  $sqlBounceCharge = mysqli_query($Con, "SELECT sum(`bounce_charge`) as `bounce_charge` FROM `fee_bounce_student_data` WHERE `sadmission`='$admission' and `status`='0' and `FinancialYear`='$currentyear' and `fee_type`='Hostel Fee' ");
  if(mysqli_num_rows($sqlBounceCharge)) {
    $rsBounceCharge = mysqli_fetch_assoc($sqlBounceCharge);
    $total_bouncecharge = $rsBounceCharge['bounce_charge'] ? $rsBounceCharge['bounce_charge'] : 0;
  }
  
  return $total_bouncecharge;

}


function fnlLateFee($month,$class,$adm,$year,$lateFeeCheckCalculation=0,$DiscontType)
{

 global $lateFeeCalculationType;

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
    $today_date = date('Y-m-d');

    if($lateFeeCalculationType == 'daywise') {
        
        $perDayFee = $Late_fees;
        $dueDate = $Dt1; 

        // Convert dates to timestamps
        $dueTimestamp = strtotime($dueDate);
        $currentTimestamp = strtotime($today_date);
    
        // Calculate the difference in seconds
        $diffInSeconds = $currentTimestamp - $dueTimestamp;
    
     /*==================================================
         school valid late fee allow or not
==================================================*/
      if($lateFeeCheckCalculation==1 && $DiscontType==1){
        
            return 0;
      }else{
        if ($diffInSeconds <= 0) {
              $LateFee=0;
              return $LateFee;
            }
      }

/*==================================================
       End  school valid late fee allow or not
==================================================*/
        // Convert seconds to days
        $daysLate = floor($diffInSeconds / (60 * 60 * 24));
    
        // Calculate total late fee
        $LateFee = $daysLate * $perDayFee;

    } else {
        
        // // $rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from fees_master where Quarter='$quarter' and `Month`='$month' and  financialyear='$year' limit 1");
        // // $rowLastDt=mysqli_fetch_row($rsLastDt);
        // // $LastDate=$rowLastDt[0];
        
        // // $now = time();
    
        // // $now = strtotime('2021-09-11');
    
        // $now = strtotime(Date('Y-m-d'));
    
        // // $Dt1=$LastDate;
        // $your_date = strtotime($Dt1);
        // $datediff = $now - $your_date;
        // if ($datediff > 0)
        //   $TotalLateDays= floor($datediff/(60*60*24));
        // else
        //   $TotalLateDays= 0;
        
        // $LateFee =$LateFee+ $TotalLateDays*$Late_fees;
    
        if($today_date > $Days_difference3) {
          $LateFee = $LateFee4;
    
        } else if ($today_date > $Days_difference2){
          $LateFee = $LateFee3;
    
        } else if ($today_date > $Days_difference1){
          $LateFee = $LateFee2;
    
        } else if ($today_date > $Dt1){
          $LateFee = $Late_fees;
    
        }
    
        // $todaydate = Date('Y-m-d');
    
        // $sqlfeechk = mysqli_query($Con, "SELECT `amount` FROM `fees_latefee_adjust` WHERE `sadmission`='$adm' and `financialyear`='$year' and `date`='$todaydate' and `Month`='$month' ");
        // $rsfeechk = mysqli_fetch_assoc($sqlfeechk);
        // $fee_late = $rsfeechk['amount'];
        
        // if ($LateFee > 0) {
          
        //   if ($LateFee > $fee_late) {
          
        //     $LateFee = $LateFee - $fee_late;
        //   }
        //   else {
    
        //     $LateFee = 0;
        //   }
    
    
        // }
        
    }
    
/*==================================================
         school valid late fee allow or not
==================================================*/
    
    if($lateFeeCheckCalculation==1 && $DiscontType==1)
    {
      return 0;    
    }else{
      return $LateFee;     
    }
/*==================================================
       End  school valid late fee allow or not
==================================================*/

}


function fnlHostelLateFee($month,$class,$adm,$year)
{
     global $lateFeeCalculationType;
     
  $ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
  $rsFY= mysqli_query($Con, $ssqlFY);
  $row4=mysqli_fetch_row($rsFY);
  $CurrentFinancialYear=$row4[0];

  $sqllatefee = mysqli_query($Con, "SELECT `last_fees_start_date`,`LastFee_date_1`, `LastFee_date_2`, `LastFee_date_3`,`Late_fees`, `Late_fees_1`, `Late_fees_2`, `Late_fees_3` FROM `hostel_Fees_MonthQuaterMapping` WHERE  `Class`='$class' and `Month`='$month' and `status`='1' ");
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
    $today_date = date('Y-m-d');
    
    if($lateFeeCalculationType == 'daywise') {
        
        $perDayFee = $Late_fees;
        $dueDate = $Dt1; 

        // Convert dates to timestamps
        $dueTimestamp = strtotime($dueDate);
        $currentTimestamp = strtotime($today_date);
    
        // Calculate the difference in seconds
        $diffInSeconds = $currentTimestamp - $dueTimestamp;
    
        // If current date is before or same as due date, no fee
        if ($diffInSeconds <= 0) {
            $LateFee=0;
            return $LateFee;
        }
     
        // Convert seconds to days
        $daysLate = floor($diffInSeconds / (60 * 60 * 24));
    
        // Calculate total late fee
        $LateFee = $daysLate * $perDayFee;

    } else {
        
      // $now = strtotime(Date('Y-m-d'));

      // $your_date = strtotime($Dt1);
      // $datediff = $now - $your_date;
      // if ($datediff > 0)
      //   $TotalLateDays= floor($datediff/(60*60*24));
      // else
      //   $TotalLateDays= 0;
      
      // $LateFee =$LateFee+ $TotalLateDays*$Late_fees;
    
    
    
        if($today_date > $Days_difference3) {
          $LateFee = $LateFee4;
    
        } else if ($today_date > $Days_difference2){
          $LateFee = $LateFee3;
    
        } else if ($today_date > $Days_difference1){
          $LateFee = $LateFee2;
    
        } else if ($today_date > $Dt1){
          $LateFee = $Late_fees;
    
        }
    
        
      // $todaydate = Date('Y-m-d');
    
      // $sqlfeechk = mysqli_query($Con, "SELECT `amount` FROM `fees_latefee_adjust` WHERE `sadmission`='$adm' and `financialyear`='$year' and `date`='$todaydate' and `Month`='$month' ");
      // $rsfeechk = mysqli_fetch_assoc($sqlfeechk);
      // $fee_late = $rsfeechk['amount'];
      
      // if ($LateFee > 0) {
      //   if ($LateFee > $fee_late) {
      //     $LateFee = $LateFee - $fee_late;
      //   }
      //   else {
      //     $LateFee = 0;
      //   }
      // }
     
     
    }

    
  return $LateFee;
}


$bounce_fee = getBounceFee($masterclass,$sadmission,$CurrentFY);

$hstl_bounce_fee = getHostelBounceFee($masterclass,$sadmission,$CurrentFY);

$today_date = date('Y-m-d');

$ssqlmisc="select `a`.`srno`,`a`.`HeadName`,`a`.`HeadAmount`,`a`.`sclass`,`a`.`LastDate`,`a`.`Remarks`,`a`.`Status`,`a`.`AnnouncementID`,`b`.`HeadName` as `head_name_desc`, `b`.`MaxCount` as `MiscMaxCount` from `fees_misc_announce` as `a` left join `fees_misc_head` as `b` on (`a`.`HeadName`=`b`.`HeadCode`) where  `a`.`sadmission`='$sadmission' and `a`.`financialyear`='$CurrentFY'";

$rsmisc = mysqli_query($Con, $ssqlmisc);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?> ||MyFees</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="new-style.css">
  
 <link rel="stylesheet" type="text/css" href="assets/css/dps-users-style.css">
 <link rel="stylesheet" type="text/css" href="assets/css/open-sans.css">
  <style>
      .table tr td, .table tr th {
                padding: 5px 4px !important;
                height: 35px!important;
                font-size: 13px !important;
                vertical-align: middle;
                min-width: 30px!important;
      }
         .table tr td {
                padding: 5px 4px !important;
                height: 35px!important;
                font-size: 13px !important;
                vertical-align: middle;
                min-width: 30px!important;
      }
      .btn-primary {
            background-color: #283897 !important;
            border-color: #283897 !important;
            color: #ffffff;
            font-size: 13px;
            padding: 4px 9px !important;
        }
        i.fa.fa-paper-plane {
                font-size: 8px;
            }
  </style>
</head>
<body>


<!--<//?php include 'Header/header_new.php';?>-->


<div class="page-wrapper chiller-theme ">
    
    <!--<//?php include 'new_sidenav.php';?>-->
    
<main class="page-content" style="margin-top:0px;"> 
          <div class="container-fluid"> 
            <div class="row">
                <div class="col-12 text-center bg-primary text-white">
                  <h4><i class="fas fa-file-alt"></i> Student Fees</h4>
                </div>
            </div>
        
           	
			<?php include 'StyleCustomizer.php'; ?>

			<!-- END PAGE BREADCRUMB -->
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					
					<!-- Begin: life time stats -->
					<div class="portlet light">
						<!--<div class="portlet-title">-->
						<!--	<div class="caption">-->
						<!--		<i class="fa fa-rupee"></i>-->
						<!--		<span class="caption-subject font-green-sharp bold uppercase">My Fees</span>-->
						<!--		<span class="caption-helper"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
      <!--         <span class="caption-subject font-black-sharp bold uppercase">View Challan: <//?= $FeeSubmissionType1; ?>&nbsp;&nbsp;<input type="button" value="Challan" onclick="create_challan('<?= $sadmission; ?>','<?= $masterclass; ?>')"></span>-->
			   <!--<a href="../Admin/fee_ledger/Student_Incometax_Certificate.php?admission_num=<//?php echo $sadmission;?>" target="_blank"><button type="button">View Income tax certificate</button></a>-->
			
						<!--	</div>-->
						<!--	<div class="actions">	-->
						<!--		<div class="btn-group">-->
						<!--			<a href="landing.php" class="btn btn-default btn-circle" >-->
						<!--			<i class="fa fa-share"></i>-->
									
						<!--			<span class="hidden-480">-->
						<!--			 Back  </span>-->
						<!--			<i class="fa fa-angle-down"></i>-->
						<!--			</a>-->
									
									
						<!--		</div>-->
						<!--	</div>-->

						<!--</div>-->

	    <div class="portlet-body">
	        
	        <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="fees-tab" data-toggle="tab" href="#fees" role="tab" aria-controls="fees" aria-selected="true">Regular Fee</a>
                  </li>
                 
                  <?php 
                    if($Hostel == 'Yes') {
                  ?>

                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="hostel-tab" data-toggle="tab" href="#hostel" role="tab" aria-controls="hostel" aria-selected="false">Hostel Fee</a>
                  </li>

                  <?php
                    }
                  ?>  
                  
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" id="miscellaneous-tab" data-toggle="tab" href="#miscellaneous" role="tab" aria-controls="miscellaneous" aria-selected="false">Miscellaneous Fee
                     </a>
                  </li>
                </ul>
                <div class="tab-content fee-online-challan" id="myTabContent">
                  <div class="tab-pane fade show active" id="fees" role="tabpanel" aria-labelledby="fees-tab">
                      
                       <?php
                        if($is_fees_access == '1') {
                        ?>
                            <h4>Please contact to school administrator.</h4>
                      <?php
                        }
                      ?>   
                      
                      <!---------------------------first tab code--------------------------->
                      
                        <div class="col-12 col-pn mt-3 mb-4">
                  <div class="caption-fee-button text-right">
								<!-- <i class="fa fa-rupee"></i> -->
								<!-- <span class="caption-subject font-green-sharp bold uppercase">My Fees</span> -->
								<span class="caption-helper"></span>
               <span class="caption-subject font-black-sharp bold uppercase"><input type="button" value="View Fee Bill" class="btn btn-primary ml-2" onclick="create_challan('<?= $sadmission; ?>','<?= $masterclass; ?>')"></span>
              <!--  <a href="../Admin/fee_ledger/Student_Incometax_Certificate.php?admission_num=<?php echo $sadmission;?>" target="_blank">-->
              <!--<button  class="btn btn-primary" type="button">View Income tax certificate</button></a>-->
        
							</div>
               </div>
          <input type="hidden" name="calculation_show" id="calculation_show" value="<?= $fee_month_show_monthly; ?>">
      <input type="hidden" name="bounce_fee" id="bounce_fee" value="<?= $bounce_fee; ?>">
      
      <div class="row">
          <div class="col-md-6">
              <div class="dps-card-section">
            <div class="">
                  <div class="card-title">
           <h5>Payment</h5></div>
            </div>
                
            
            <?php
            if($PaymentGateway == 'payu') {
          ?>

              <form name="frmFeesMonthly" id="frmFeesMonthly" method="post" action="FeePaymentPayU.php" >

          <?php
            } else if($PaymentGateway == 'ccavenue') {
          ?>

              <form name="frmFeesMonthly" id="frmFeesMonthly" method="post" action="FeePaymentPayU_CCA.php" >

          <?php
            } else {
              echo "Payment Gateway Not found, Please contact to system administrator";
              exit();
            }
          ?>
          
              
            <input type="hidden" name="admission_no" id="admission_no" value="<?= $sadmission; ?>">
            <input type="hidden" name="Fee_Submission_Type" id="Fee_Submission_Type" value="<?= $FeeSubmissionType; ?>">
			
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


                //             //$sqlfindmonth = mysqli_query($Con, "SELECT * FROM `hostel_fees_transaction` WHERE `sadmission`='$sadmission'  and `Month`='$Month' and `fy`='$CurrentFY' and `status`='1'");
                //             $sqlfindmonth = mysqli_query($Con, "SELECT * FROM `fees_transaction` as `a` LEFT JOIN `fees` as `b` on (`a`.`sadmission`= `b`.`sadmission` and `a`.`receipt_no`= `b`.`receipt_no`) WHERE `a`.`sadmission`='$sadmission'  and `a`.`Month`='$Month' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`cheque_status` in ('Clear','Online')");

				            // if (mysqli_num_rows($sqlfindmonth)>0) {
				            //     $latefee = 0;
				            // }
				            // else {
				            //     $latefee = fnlLateFee($Month,$masterclass,$sadmission,$CurrentFY);
				            // } 
				            
				            
				            $latefee = fnlLateFee($Month,$masterclass,$sadmission,$CurrentFY);


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


                        if ($total_balance == 0) {
                           $latefee = 0;
                        }
                        
                            /*==============================================
                            Fee change 
                            =============================================*/
                            if($lateFeeCheckCalculation==1 && $DiscontType==1)
                            {
                              if($total_balance < 7150) {
                                $latefee = 0;
                              }
                            }
                            /*==============================================
                            End Fee change    before code                   
                            
                            if($total_balance < 7150) {
                                $latefee = 0;
                              }
                            =============================================*/
                        
                        $sqlCalChk = mysqli_query($Con, "SELECT `fee_month_show_monthly` FROM `Fees_MonthQuaterMapping` WHERE `Class`='$masterclass' and `Month`='$Month'  and `fee_pay_status`='1' ORDER by `Priority` limit 1 ");
                        $rsCalChk = mysqli_fetch_assoc($sqlCalChk);
                        $is_fee_month_show = $rsCalChk['fee_month_show_monthly'];

                    ?>

                    <tr>
                    	 <td style="width: 10px;">
                    	 	<?php 
	                    	 	if ($total_balance > 0) {
	                    	?>
                            
                            <input type="checkbox" class="getamt" name="mycheckbox[]" onchnage="selectMonth('<?= $Month; ?>')" id="<?= "chkcheckbox_".$cnt; ?>" value="<?= $cnt; ?>" <?php if($is_fee_month_show == '1' || $is_fee_month_show == 1) { ?> checked <?php } ?> >

                            <input type="hidden" name="<?= "chkfeestatus_".$cnt; ?>" id="<?= "chkfeestatus_".$cnt; ?>" value="notpaid"  >

	                    	<?php 		
	                    	 	}
	                    	 	else {
                            ?>
                            Paid
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
                    	 <!--&nbsp;<i class="fa fa-paper-plane" style="color:#283897;cursor: pointer;" title="View <?= $Month; ?> Challan" onclick='monthwisechallan("<?= $sadmission; ?>","<?= $masterclass; ?>","<?= $CurrentFY; ?>","<?= $Month; ?>")'></i>-->
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
                  <div class=""> <h3>Bounce Charge : &nbsp;

                  <?= $bounce_fee; ?>

                  </h3></div>
	            		<!-- <h4>Payment Mode: <?= $FeeSubmissionType; ?></h4> -->
	            	</div>
	            	<div class="col-md-4">
                   <!-- /*==================================================
       End  school valid late fee allow or not
==================================================*/ -->
                <textarea  hidden id="monthIdShow"></textarea>
<!-- 
                /*==================================================
       End  school valid late fee allow or not
==================================================*/ -->
	            		Pay <i class="fa fa-rupee"></i> <input type="button" onclick="validatefees();" name="total_amount" id="total_amount" value="0" class="btn btn-primary">
                  <span class="text-small" onclick='showFeeInfo(<?php echo json_encode(array_unique($headIdArray)); ?>)'><i class="fa fa-info-circle" aria-hidden="true"></i></span>
	            	</div>
	            </div>

			</div>
			  
			</div>
		</form>

</div>
      </div>

      <div class="col-md-6">
           <div class="dps-card-section">
<div class="">
    <div class="card-title">
        <h5>Payment history</h5>
        </div>
        </div>

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
    
    </div>
                      
                  </div>
                        <!--------------------------end -first tab code--------------------------->


                        <!---------------------------2nd tab code--------------------------->

        <div class="tab-pane fade" id="hostel" role="tabpanel" aria-labelledby="hostel-tab">
              
                <?php
                        if($is_hostel_fee_access == '1') {
                        ?>
                            <h4>Please contact to school administrator.</h4>
                      <?php
                        }
                      ?> 
                      
          <div class="col-12 col-pn mt-3 mb-4">
            <div class="caption-fee-button text-right">
              <!-- <i class="fa fa-rupee"></i> -->
              <!-- <span class="caption-subject font-green-sharp bold uppercase">My Fees</span> -->
              <span class="caption-helper"></span>
              <span class="caption-subject font-black-sharp bold uppercase">
                <input type="button" value="View Fee Bill" class="btn btn-primary ml-2" onclick="create_hostel_challan('<?= $sadmission; ?>','<?= $masterclass; ?>')">
              </span>
            </div>
          </div>

          <input type="hidden" name="hstl_calculation_show" id="hstl_calculation_show" value="<?= $hstl_fee_month_show_monthly; ?>">
          <input type="hidden" name="hstl_bounce_fee" id="hstl_bounce_fee" value="<?= $hstl_bounce_fee; ?>">
      
          <div class="row">
            <div class="col-md-6">
              <div class="dps-card-section">
                <div class="">
                  <div class="card-title">
                    <h5>Payment</h5>
                  </div>
                </div>
                
                 
                <?php
                  if($PaymentGateway == 'payu') {
                ?>

                    <form name="hstlfrmFeesMonthly" id="hstlfrmFeesMonthly" method="post" action="HostelFeePaymentPayU.php" >

                <?php
                  } else if($PaymentGateway == 'ccavenue') {
                ?>

                    <form name="hstlfrmFeesMonthly" id="hstlfrmFeesMonthly" method="post" action="HostelFeePaymentPayU_CCA.php" >

                <?php
                  } else {
                    echo "Payment Gateway Not found, Please contact to system administrator";
                    exit();
                  }
                ?> 
                
                  <input type="hidden" name="hstl_admission_no" id="hstl_admission_no" value="<?= $sadmission; ?>">
			
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

                              $hstl_total_debitamt = 0;
                              $hstl_total_creditamt = 0;
                              $hstl_total_balanceamt = 0;
                              $hstl_total_latefee = 0;

                              $hstlcnt =1;
                              mysql_data_seek($sqlHostelMonth, 0);
                              while ($rsMonth = mysqli_fetch_assoc($sqlHostelMonth)) {
                                  
                                $Month = $rsMonth['Month'];
                                $Quarter = $rsMonth['Quarter'];

                                $hstl_total_dr_amnt = 0;
                                $hstl_total_cr_amnt = 0;
                                $hstl_total_balance = 0;
                                $hstl_latefee = 0;

                                // $sqlfindmonth = mysqli_query($Con, "SELECT * FROM `hostel_fees_transaction` as `a` LEFT JOIN `hostel_fees` as `b` on (`a`.`sadmission`= `b`.`sadmission` and `a`.`receipt_no`= `b`.`receipt_no`) WHERE `a`.`sadmission`='$sadmission'  and `a`.`Month`='$Month' and `a`.`fy`='$CurrentFY' and `a`.`status`='1' and `b`.`cheque_status` in ('Clear','Online')");

                                // if (mysqli_num_rows($sqlfindmonth)>0) {
                                //   $hstl_latefee = 0;
                                // }
                                // else {
                                //   $hstl_latefee = fnlHostelLateFee($Month,$masterclass,$sadmission,$CurrentFY);
                                // }

                                $hstl_latefee = fnlHostelLateFee($Month,$masterclass,$sadmission,$CurrentFY);

                                while ($rshostelfeehead = mysqli_fetch_assoc($sqlhostelfeehead)) {

                                  $head_id = $rshostelfeehead['head_id'];
                          
                                  $sqlamt = mysqli_query($Con, "SELECT `dr_amnt`,`cr_amnt`,`pre_dr_amnt`,`pre_cr_amnt` FROM `hostel_fees_student` WHERE `sadmission`='$sadmission' and `Month`='$Month' and `fy`='$CurrentFY' and `head_id`='$head_id' and `status`='1' ");

                                  $sqlcramt = mysqli_query($Con, "SELECT SUM(`cr_amnt`) as `cr_amnt`,SUM(`dr_amnt`) as `dr_amnt` FROM `hostel_fees_transaction` WHERE `sadmission`='$sadmission' and `head_id`='$head_id' and `Month`='$Month' and `fy`='$CurrentFY' and `status`='1' ");
                                  
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

                                  $hstl_total_dr_amnt += $dr_amnt;
                                  $hstl_total_cr_amnt += $cr_amnt;
                                  $hstl_total_balance += $balance;
                
                                } 

                                if ($hstl_total_balance == 0) {
                                  $hstl_latefee = 0;
                                }
                                if($hstl_total_balance < 7150) {
                                  $hstl_latefee = 0;
                                }

                          ?>

                            <tr>
                              <td style="width: 10px;">
                              <?php 
                                if ($hstl_total_balance > 0) {
                              ?>
                                  
                                  <input type="checkbox" class="hstlgetamt" name="hstlmycheckbox[]" id="<?= "hstlchkcheckbox_".$hstlcnt; ?>" value="<?= $hstlcnt; ?>" <?php if($hstl_fee_month_show_monthly == '1' || $hstl_fee_month_show_monthly == 1) { ?> checked <?php } ?> >

                                  <input type="hidden" name="<?= "hstlchkfeestatus_".$hstlcnt; ?>" id="<?= "hstlchkfeestatus_".$hstlcnt; ?>" value="notpaid"  >

                              <?php 		
                                }
                                else {
                                  ?>
                                  Paid
                                  <input type="hidden" name="<?= "hstlchkfeestatus_".$hstlcnt; ?>" id="<?= "hstlchkfeestatus_".$hstlcnt; ?>" value="paid" >

                                  <?php
                                }
                              ?> 	            	 	
                              </td>
                                    
                              <input type="hidden" name="<?= "hstlmonth_".$hstlcnt; ?>" id="<?= "hstlmonth_".$hstlcnt; ?>" value="<?= $Month; ?>" >
                              <input type="hidden" name="<?= "hstldebit_".$hstlcnt; ?>" id="<?= "hstldebit_".$hstlcnt; ?>" value="<?= $hstl_total_dr_amnt; ?>" >
                              <input type="hidden" name="<?= "hstlcredit_".$hstlcnt; ?>" id="<?= "hstlcredit_".$hstlcnt; ?>" value="<?= $hstl_total_cr_amnt; ?>" >
                              <input type="hidden" name="<?= "hstlbalance_".$hstlcnt; ?>" id="<?= "hstlbalance_".$hstlcnt; ?>" value="<?= $hstl_total_balance; ?>">
                              <input type="hidden" name="<?= "hstllatefee_".$hstlcnt; ?>" id="<?= "hstllatefee_".$hstlcnt; ?>" value="<?= $hstl_latefee; ?>"  >
                              <input type="hidden" name="<?= "hstlbounce_".$hstlcnt; ?>" id="<?= "hstlbounce_".$hstlcnt; ?>" value="0"  >
                                
                                        
                              <th><?= $Month; ?>
                                <!--&nbsp;<i class="fa fa-paper-plane" style="color:#283897; cursor: pointer;" title="View <?= $Month; ?> Challan" onclick='hostelmonthwisechallan("<?= $sadmission; ?>","<?= $masterclass; ?>","<?= $CurrentFY; ?>","<?= $Month; ?>")'></i>-->
                              </th>
                                <td><?= $hstl_total_dr_amnt; ?></td>
                                <td><?= $hstl_total_cr_amnt; ?></td>
                                <td><?= $hstl_total_balance; ?></td>
                                <td><?= $hstl_latefee; ?></td>
                            </tr>
                                
                          <?php    

                            $hstl_total_debitamt += $hstl_total_dr_amnt;
                            $hstl_total_creditamt += $hstl_total_cr_amnt;
                            $hstl_total_balanceamt += $hstl_total_balance;
                            $hstl_total_latefee += $hstl_latefee;

                            mysql_data_seek($sqlhostelfeehead, 0); 

                            $hstlcnt++;

                            }

                          ?>

                            <tr>
                              <td>&nbsp;</td>
                              <th>Grand Total</th>
                              <th><?= number_format($hstl_total_debitamt,2); ?></th>
                              <th><?= number_format($hstl_total_creditamt,2); ?></th>
                              <th><?= number_format($hstl_total_balanceamt,2); ?></th>
                              <th><?= number_format($hstl_total_latefee,2); ?></th>
                            </tr>
                                        
                        </tbody>

                      </table>

                      <div class="row">
                        <div class="col-md-8">
                          &nbsp;
                          <div class=""> 
                            <h3>Bounce Charge : &nbsp; <?= $hstl_bounce_fee; ?></h3>
                          </div>
                        </div>
                        <div class="col-md-4">
                          Pay <i class="fa fa-rupee"></i> <input type="button" onclick="hostelvalidatefees();" name="hstl_total_amount" id="hstl_total_amount" value="0" class="btn btn-primary">
                        </div>
                      </div>

                    </div>
                  </div>
		            </form>

              </div>
            </div>

            <div class="col-md-6">
              <div class="dps-card-section">
                <div class="">
                  <div class="card-title">
                    <h5>Payment history</h5>
                  </div>
                </div>

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
                        while ($rshstlfeehistory = mysqli_fetch_assoc($sqlhstlfeehistory)) 
                        {
                          $paid_amount = '';
                          $paid_debit_amnt = '';

                          $receipt_no = $rshstlfeehistory['receipt_no'];
                          $paid_amount = $rshstlfeehistory['cr_amnt'];
                          $feedate = $rshstlfeehistory['date'];
                          $cheque_status = $rshstlfeehistory['cheque_status'];
                          $paid_debit_amnt = $rshstlfeehistory['dr_amnt'];
                          $remark = $rshstlfeehistory['ChequeBounceRemark'];


                          $sqlmonth_his = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `hostel_fees_transaction` WHERE `receipt_no`='$receipt_no' and `status`='1' and `fy`='$CurrentFY' ORDER BY `srno` limit 1 ");
                          $rsmonth_his = mysqli_fetch_assoc($sqlmonth_his);
                          $month_h = $rsmonth_his['Month'];

                          $sqlmonth_his1 = mysqli_query($Con, "SELECT DISTINCT `Month` FROM `hostel_fees_transaction` WHERE `receipt_no`='$receipt_no' and `status`='1' and `fy`='$CurrentFY' ORDER BY `srno` desc limit 1 ");
                          $rsmonth_his1 = mysqli_fetch_assoc($sqlmonth_his1);
                          $month_h1 = $rsmonth_his1['Month'];

                          if ($month_h == $month_h1) 
                          {
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
                          <td><?= $receipt_no; ?>&nbsp; <i class="fa fa-print" onclick="hostel_open_fee_receipt('<?= $sadmission; ?>','<?= $receipt_no; ?>')"></i></td>
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
    
          </div>
                      
        </div>
        
                  <!---------------------------end 2nd tab code--------------------------->


                  <div class="tab-pane fade" id="miscellaneous" role="tabpanel" aria-labelledby="miscellaneous-tab">
                        <!---------------------------third tab code--------------------------->
                          <div class="col-12 col-pn mt-3 mb-4">
                  <div class="caption-fee-button text-right">
								<!-- <i class="fa fa-rupee"></i> -->
								<!-- <span class="caption-subject font-green-sharp bold uppercase">My Fees</span> -->
								<span class="caption-helper"></span>
							</div>
               </div>
                        <div class="div-table">

      <!--<table class="table table-row-fixed">-->
      	<div class="table-responsive">
            <table class="table" style="border:1px solid #ddd;">

        <thead>
          <tr class="bg-primary text-white">
            <th>Sr.No</th>
            <th>Fees Head</th>
            <th>Amount</th>
            <th>Last Submit Date</th>
            <th>Status</th>
          </tr>
        </thead>

        <?php
        $rowcount=1;

        while($row=mysqli_fetch_row($rsmisc))
        {

          $HeaderSrNo=$row[0];
          $HeadName=$row[1];
          $HeadAmount=$row[2];
          $sclass=$row[3];
          $LastDate=$row[4];
          $Remarks=$row[5];
          $Status=$row[6];
          $AnnouncementId=$row[7];
          $HeadNameDesc=$row[8];
          $MiscMaxCount=$row[9];

          $rsChk=mysqli_query($Con, "select * from `fees_misc_collection` where `sadmissionno`='$sadmission' and `HeadName`='$HeadName' and `AnnouncementID`='$AnnouncementId'");
          $rowMisc = mysqli_fetch_assoc($rsChk);
          $MiscFeeReceipt = $rowMisc['FeeReceipt'];
          
          $rsMiscTtlChk=mysqli_query($Con, "select count(*) as `ttl_misc_fee_paid` from `fees_misc_collection` where `HeadName`='$HeadName' ");
          $rowMiscTtlChk = mysqli_fetch_assoc($rsMiscTtlChk);
          $ttl_misc_fee_paid = $rowMiscTtlChk['ttl_misc_fee_paid'];
          
          if (mysqli_num_rows($rsChk) == 0)
          {
                if($MiscMaxCount <= $ttl_misc_fee_paid) {
                    continue;
                }

                if($LastDate < $today_date ) {
                  continue;
                }

          }
          
        ?>

        <tbody>

        <tr>
          <td class="style8" style="width: 196px" align="center"><?php echo $rowcount;?>.</td>
          <td class="style8" style="width: 196px" align="center"><?php echo $HeadNameDesc."<br> Remark: ".$Remarks;?></td>
          <td class="style8" style="width: 196px" align="center"><?php echo $HeadAmount;?></td>
          <td class="style8" style="width: 196px" align="center"><?php echo $LastDate;?></td>
          <td class="style8" style="width: 325px" align="center">

          <?php
          if (mysqli_num_rows($rsChk) == 0)
          {

            if($Status=="1")
            {
          ?>
          
                <?php
                  if($PaymentGateway == 'payu') {
                ?>

                    <form align="center" method="post" action="FeesSubmit_New.php" >

                <?php
                  } else if($PaymentGateway == 'ccavenue') {
                ?>

                    <form align="center" method="post" action="FeesSubmit_New_CCA.php" >

                <?php
                  } else {
                    echo "Payment Gateway Not found, Please contact to system administrator";
                    exit();
                  }
                ?> 
                  
                <input type="hidden" id="hHeaderSrNo" name="hHeaderSrNo" value="<?php echo $HeaderSrNo;?>">
                <input type="hidden" name="misc_admission_no" id="misc_admission_no" value="<?= $sadmission; ?>">
                <input type="Submit" value="Pay <?php echo $HeadAmount;?>" class="btn btn-danger"/>
              </form>

            <?php
            }

          }
          else
          {
          ?>

            <input type="button" name="btnPrintReceipt" id="btnPrintReceipt" value="Print Receipt" class="btn red" onclick="javascript:fnlShowReceipt('<?php echo $HeadName;?>','<?php echo $sadmission;?>','<?php echo $MiscFeeReceipt;?>');">

          <?php
          }
          ?>

          </td>

        </tr>

        </tbody>

      <?php
          $rowcount=$rowcount+1;
        }
      ?>  
      </table>
      </div>
    </div>
                          <!---------------------------end third tab code--------------------------->
                  </div>
                </div>
	        
	        <div class="instrcution-card">
                <div class="row">
                  <div class="col-md-12 mt-3 ">
                    <h6 style="color:red;"><font face="Cambria">*Please wait for 48 hrs before making next transaction, if fees amount is already deducted from your Credit Card/Debit Card/Net Banking or any other payment mode...</font></6>
            
                    <br><br>
                    <p><b><i><font face="Cambria"> <a href="privacy.php">Click here to read Terms & Conditions and Privacy Policy </a> for online fees payment</font></i></b></p>
                    <p><b><i><font face="Cambria">- Please call at School Reception for further details</font></i></b></p>
                    <br>
                    <!-- <a href="MyFees_previous_year.php"><button class="btn btn-primary">Click here to View the Previous Fees Receipt/Certificate </button></a> -->
                  </div>
                </div>
             </div>

    

		</div>
					</div>
					<!-- End: life time stats -->
				</div>
			</div>
			<!-- END PAGE CONTENT-->
	
	<!-- END CONTENT -->
</div>
          </div>
      
        </main>
<!--end page contents-->
</div>
<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="challan_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header justify-content-between">
        <h5 class="modal-title" id="exampleModalLabel">Generate Challan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <div class="modal-body">
       <input type="hidden" name="challan_adm_no" id="challan_adm_no">
       <input type="hidden" name="challan_class" id="challan_class">
       <input type="hidden" name="challan_idtnfy" id="challan_idtnfy">

           <div class="row">
               <div class="col-md-2">
                   Year
               </div>
               <div class="col-md-10">
                    <select size="1" name="challan_year" id="challan_year" class="form-control">
                      <option value="<?= $CurrentFY; ?>"><?= $CurrentFY;?></option>
                    </select>
               </div>
           </div>
            <div class="row mt-3">
               <div class="col-md-2">
                   Month
               </div>
               <div class="col-md-10">
               <table>
                  <?php
                       mysql_data_seek($sqlMonth, 0);
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
           <br>
          <!-- <div class="row">-->
          <!--     <div class="col-md-2">-->
          <!--         Month-->
          <!--     </div>-->
          <!--     <div class="col-md-10">-->
          <!--         <table>-->
          <!--          <//?php-->
          <!--             while($row1 = mysqli_fetch_assoc($sqlMonth))-->
          <!--              {-->
          <!--                $Month1=$row1['Month'];       -->
          <!--          ?>-->
               
          <!--    <tr>-->
          <!--      <td><input type="checkbox" class="selectmonthchallan" value="<?= $Month1; ?>"></td>-->
          <!--      <td><?//= $Month1; ?></td>-->
          <!--    </tr>       -->
            
          <!--<//?//php-->
          <!--   }-->
          <!--//?>-->
                
                    
          <!--         </table>-->
          <!--     </div>-->
          <!-- </div>-->

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="btngeneratechallan" id="btngeneratechallan" class="submit">Generate</button>
      </div>
    </div>
  </div>
</div>
                </div>
  <!-- /*==================================================
       End  school valid late fee allow or not
==================================================*/ -->
<!-- Modal -->
<div class="modal fade" id="FeeInformation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static" >
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header justify-content-between">
        <h5 class="modal-title" id="exampleModalLabel">Fee Information</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
       <div class="modal-body">
           <div class="row" >
                <span id="headID"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
  
<!-- 
/*==================================================
       End  school valid late fee allow or not
==================================================*/ -->

                </body>

<?php
    mysqli_query($Con, "Delete FROM `student_fee_token` WHERE `sadmission`='$sadmission'");
?>

</html>

<script src="myfees.js" type="text/javascript"></script>

<script type="text/javascript">	
	jQuery(window).load(function() {
        $(".checker").removeClass();
    });
</script>

<script>

    function fnlShowReceipt(headname,admissionid,receipt_no)
    {
      var myWindow = window.open("ShowMiscReceipt.php?headname=" + escape(headname) + "&adm_no=" + escape(admissionid) + "&receipt_no=" + escape(receipt_no),"MsgWindow","width=700,height=700");
      return;
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


<form method="post" name="frmfeereceipt" id="frmfeereceipt" target="_blank">
  <input type="hidden" name="receipt_no" id="receipt_no">
  <input type="hidden" name="adm_no" id="adm_no">
</form>


<form method="post" name="frmdisplaychallan" id="frmdisplaychallan" target="_blank" >
  <input type="hidden" name="master_class" id="master_class">
  <input type="hidden" name="sadm_no" id="sadm_no">
  <input type="hidden" name="year" id="year">
  <input type="hidden" name="month" id="month">
</form>
<!-- /*==================================================
       End  school valid late fee allow or not
==================================================*/ -->
<script>
function selectMonth(month){
  console.log(month);
}


  function showFeeInfo(json)
  {
    $('#FeeInformation').modal('show'); 

    $.ajax({
        url: 'feeUserHeadInfo_ajax_call.php',
        type: 'POST',
        data: {
            // head_ids: json,
            sadm_no:'<?php echo $sadmission;?>',
            master_class:'<?= $masterclass; ?>',
            monthIdShow:$("#monthIdShow").val(),
            year:'<?=$CurrentFY;?>'

        },
        dataType: 'html',
        success: function(response) {
            console.log('Success:', response);
               $("#headID").html(response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', error);
        }
    });

  }
</script>

<!-- /*==================================================
       End  school valid late fee allow or not
==================================================*/ -->

<script>

function getCheckedGetAmtValues() {
    var checkedBoxes = document.querySelectorAll('.getamt:checked');
    var values = Array.from(checkedBoxes).map(cb => cb.value);
    console.log("Checked Values:", values);
    $("#monthIdShow").val(values);
    // Aap yahan values ko kisi aur function mein bhi bhej sakte ho
}

// Page load ke time pe check karo
window.addEventListener('load', function () {
    getCheckedGetAmtValues();
});

// Har checkbox par event listener lagao (live behavior ke liye)
document.addEventListener('change', function (e) {
    if (e.target && e.target.classList.contains('getamt')) {
        getCheckedGetAmtValues();
    }
});
</script>
