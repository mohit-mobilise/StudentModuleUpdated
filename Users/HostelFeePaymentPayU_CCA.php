<?php include '../connection.php'; ?>
<?php include '../AppConf.php';?>

<?php
session_start();

$merchantTxnId = uniqid(); 


$MonthArray = array(1 => 'April',2 => 'May',3 => 'June',4 => 'July',5 => 'August',6 =>'September',7 =>'October',8 =>'November',9 =>'December',10 => 'January',11 => 'February',12 => 'March' );
  

function getBounceFee($class,$adm,$year)
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


function fnlLateFee($month,$class,$adm,$year)
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
            
        // // $rsLastDt=mysqli_query($Con, "select FeesSubmissionLastDate from hostel_fees_master where Quarter='$quarter' and `Month`='$month' and  financialyear='$year' limit 1");
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
    
        // $sqlfeechk = mysqli_query($Con, "SELECT `amount` FROM `hostel_fees_latefee_adjust` WHERE `sadmission`='$adm' and `financialyear`='$year' and `date`='$todaydate' and `Month`='$month' ");
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



if ($_POST["hstl_admission_no"]!= "")
{
  $sadmission = $_POST['hstl_admission_no'];

  $rsStDetail=mysqli_query($Con, "select `sname`,`sclass`,`sfathername`,`MotherName`,`smobile`,`DiscontType`,`RouteForFees`,`MasterClass` ,`FinancialYear`,`FeeSubmissionType` from `student_master` where `sadmission`='$sadmission'");
  $rowSt=mysqli_fetch_assoc($rsStDetail);
  $masterclass=$rowSt['MasterClass'];
 $student_sclass=$rowSt['sclass'];
	$payment_mode = '3';
	$receipt_date = Date('Y-m-d');
	$cheque_date = ''; 
	$school_bank = ''; 
	$micr_no = ''; 
	$trans_code = ''; 
	$bank_name = ''; 
	$branch_name = '';

  $sqlfyear = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status`='Active'");
  $rsfyear = mysqli_fetch_assoc($sqlfyear);
  $currentyear = $rsfyear['year'];

	$bounce_charge = getBounceFee($masterclass,$sadmission,$currentyear);

	$logged_user = $sadmission;

  if ($currentyear == '') {

    echo "<script>alert('Financial Year does not exist');window.history.back(-1);</script>";exit();
  }
  else if ($sadmission == '') {

    echo "<script>alert('Session timeout please login again');window.history.back(-1);</script>";exit();
  }


	$sqlfeehead = mysqli_query($Con, "SELECT DISTINCT `a`.`head_id`,`b`.`FeesHeadName` FROM `hostel_fees_student` as `a` LEFT JOIN `hostel_Fees_Head` as `b` ON (`a`.`head_id`=`b`.`head_id`) WHERE `a`.`sadmission`='$sadmission' and `a`.`fy`='$currentyear' and `a`.`status`='1' and `b`.`head_id`!='101' ORDER BY `b`.`head_priority`");  

  $total_balanceamt = 0;
  $total_latefee = 0;

  // if(sizeof($_POST['hstlmycheckbox'])%3!=0)
  if(sizeof($_POST['hstlmycheckbox']) == 0 )
  {
    echo "<script>alert('Dear parent please refresh your app. Please logout from your portal and re-login to continue.');window.history.back(-1);</script>";exit();
  }

	for ($i=0; $i < sizeof($_POST['hstlmycheckbox']); $i++) { 

    $month_id = $_POST['hstlmycheckbox'][$i];

    $month_name = $MonthArray[$month_id];

  
    $total_balance = 0;
    $latefee = 0;

    ////$sqlfindmonth = mysqli_query($Con, "SELECT * FROM `hostel_fees_transaction` WHERE `sadmission`='$sadmission'  and `Month`='$month_name' and `fy`='$currentyear' and `status`='1'");
      
    //$sqlfindmonth = mysqli_query($Con, "SELECT * FROM `hostel_fees_transaction` as `a` LEFT JOIN `hostel_fees` as `b` on (`a`.`sadmission`= `b`.`sadmission` and `a`.`receipt_no`= `b`.`receipt_no`) WHERE `a`.`sadmission`='$sadmission'  and `a`.`Month`='$month_name' and `a`.`fy`='$currentyear' and `a`.`status`='1' and `b`.`cheque_status` in ('Clear','Online') ");

    //     if (mysqli_num_rows($sqlfindmonth)>0) {
    //         $latefee = 0;
    //     }
    //     else {
    //         $latefee = fnlLateFee($month_name,$masterclass,$sadmission,$currentyear);
            
    //     } 

    $latefee = fnlLateFee($month_name,$masterclass,$sadmission,$currentyear);

    while ($rsfeehead = mysqli_fetch_assoc($sqlfeehead)) {

        $head_id = $rsfeehead['head_id'];

        $sqlamt = mysqli_query($Con, "SELECT `dr_amnt`,`cr_amnt`,`pre_dr_amnt`,`pre_cr_amnt` FROM `hostel_fees_student` WHERE `sadmission`='$sadmission' and `Month`='$month_name' and `fy`='$currentyear' and `head_id`='$head_id' and `status`='1' ");

        $sqlcramt = mysqli_query($Con, "SELECT SUM(`cr_amnt`) as `cr_amnt`,SUM(`dr_amnt`) as `dr_amnt` FROM `hostel_fees_transaction` WHERE `sadmission`='$sadmission' and `head_id`='$head_id' and `Month`='$month_name' and `fy`='$currentyear' and `status`='1' ");
        
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

      
        $total_balance += $balance;
    } 


    if ($total_balance == 0) {
      $latefee = 0;
    }
    if($total_balance < 7150) {
      $latefee = 0;
    }
 
    mysql_data_seek($sqlfeehead, 0); 


    $total_balanceamt += $total_balance;
    $total_latefee += $latefee;
	}

	$amount   = $total_balanceamt;
	$late_fee = $total_latefee; 

  if ($amount == '' ) {   
    echo "<script>alert('Please select month');window.history.back(-1);</script>";
  }

  $TxnOrderAmount = $amount + $bounce_charge + $late_fee;
  // echo  $TxnOrderAmount;
  //   echo $amount." / ".$bounce_charge." / ".$late_fee;

  $sqlStudentDetail = "select `sfathername`,`sname`,`sclass`,`srollno`,`MotherName`,`Address`,`smobile`,`email` from  `student_master` where `sadmission`='$sadmission'";
  $rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
  
  while($rowstd = mysqli_fetch_assoc($rsStudentDetail))
  {
    $Name=$rowstd['sname'];
    $Email=$rowstd['email'];
    $Phoneno=$rowstd['smobile'];
    $FatherName=$rowstd['sfathername'];
     $Address=$rowstd['Address'];
    if($Phoneno=="")
    {
      $Phoneno="NA";
    }
    if($Email=="")
    {
      $Email="NA";
    }

    break;
  }
   

  $receipt = '';

  mysqli_query($Con, "INSERT INTO `hostel_fees_temp`(`sadmission`, `cr_amnt`, `Late_fees`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `cheque_date`, `school_bank`, `bankname`, `branch`, `MICRCode`, `TransCode`, `cheque_bounce_amt`, `TxnId`, `create_by`,`cheque_status`) VALUES ('$sadmission','$amount','$late_fee','$currentyear','','','$receipt_date','$receipt','$payment_mode','$cheque_date','$school_bank','$bank_name','$branch_name','$micr_no','$trans_code','$bounce_charge','$merchantTxnId','$logged_user','Online')");

	for ($i=0; $i < sizeof($_POST['hstlmycheckbox']); $i++) 
  {

    $month_id1 = $_POST['hstlmycheckbox'][$i];

    $month_name1 = $MonthArray[$month_id1];

    mysql_data_seek($sqlfeehead, 0);
	  while ($rsfeehead = mysqli_fetch_assoc($sqlfeehead)) 
    {
      $head_id = $rsfeehead['head_id'];
      $FeesHeadName = $rsfeehead['FeesHeadName'];

      $sqlamt = mysqli_query($Con, "SELECT `dr_amnt`,`cr_amnt`,`pre_dr_amnt`,`pre_cr_amnt` FROM `hostel_fees_student` WHERE `sadmission`='$sadmission' and `Month`='$month_name1' and `fy`='$currentyear' and `head_id`='$head_id' and `status`='1' ");

      $sqlcramt = mysqli_query($Con, "SELECT SUM(`cr_amnt`) as `cr_amnt`,SUM(`dr_amnt`) as `dr_amnt` FROM `hostel_fees_transaction` WHERE `sadmission`='$sadmission' and `head_id`='$head_id' and `Month`='$month_name1' and `fy`='$currentyear' and `status`='1' ");
      
      $dr_amnt1 = 0; 
      $cr_amnt1 = 0;
      $balance1 = 0;

      $debit_amnt1 = 0;
      $credit_amnt1 = 0;
      $pre_dr_amnt1 = 0;
      $pre_cr_amnt1 = 0;

      $pay_credit_amnt1 = 0;
      $pay_debit_amnt1 = 0;

      $rsamt = mysqli_fetch_assoc($sqlamt);
      $debit_amnt1 = $rsamt['dr_amnt']; 
      $credit_amnt1 = $rsamt['cr_amnt']; 
      $pre_dr_amnt1 = $rsamt['pre_dr_amnt']; 
      $pre_cr_amnt1 = $rsamt['pre_cr_amnt']; 

      $dr_amnt1 = ($debit_amnt1 + $pre_dr_amnt1) - ($credit_amnt1 + $pre_cr_amnt1);

      $rscramt = mysqli_fetch_assoc($sqlcramt);
      $pay_credit_amnt1 = $rscramt['cr_amnt']; 
      $pay_debit_amnt1 = $rscramt['dr_amnt']; 

      $cr_amnt1 = $pay_credit_amnt1 - $pay_debit_amnt1;
      
      $balance1 = $dr_amnt1 - $cr_amnt1;
      if ($balance1 > 0 && $amount != 0) 
      {
        if ($amount >= $balance1) 
        {
          $cr_amnt1 = $balance1;
          $amount = $amount - $balance1;
          $balance1 = 0;
        }
        else 
        {
          $cr_amnt1 = $amount;
          $balance1 = $balance1 - $amount;
          $amount = 0;
        }

        mysqli_query($Con, "INSERT INTO `hostel_fees_transaction_temp`(`sadmission`, `receipt_no`, `head_id`, `cr_amnt`, `Month`, `fy`, `InputSource`, `created_by`,`TxnId`) VALUES ('$sadmission','$receipt','$head_id','$cr_amnt1','$month_name1','$currentyear','Internal','$logged_user','$merchantTxnId')");
      }

    }
    
    
  }

}

?>

<html>
  <head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?php echo $SchoolName ?></title>
  </head>
  <body>
    <center>
      <?php include('Crypto.php')?>
      <?php 
        error_reporting(0);

        $merchant_data='';
        $working_key='14AD1ABE4E1F13A8F38513B8024B1B2B';//Shared by CCAVENUES
        $access_code='AVOV71ME75BC74VOCB';//Shared by CCAVENUES
        $merchant_data='764451';
        // $merchant_data=$merchant_data.'&txtName='.$Name.'&TotalFeeAmount='.$TxnOrderAmount;
        // $merchant_data=$merchant_data.'&redirect_url='.$BaseURL.'Users/success_cca.php&cancel_url='.$BaseURL.'Users/success_cca.php&currency=INR';
        // $merchant_data=$merchant_data.'&merchant_id=764451&order_id='.$merchantTxnId.'&amount='.$TxnOrderAmount.'&FatherName='.$FatherName;

        // $encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.
        
$data = [
    'merchant_id' => $merchant_data,
    'order_id' => $merchantTxnId,
    'currency' => "INR",
    'amount' => $TxnOrderAmount,
    'redirect_url' => $BaseURL.'Users/success_cca.php',
    'cancel_url' => $BaseURL.'Users/success_cca.php',
    'language' => "EN",
    'billing_name' => ucwords(strtolower($Name.'_'.$student_sclass)),
    'billing_email' => $Email,
    'billing_tel' => $Phoneno,
    'billing_address' => $Address,
    'billing_country' => 'India',
    'merchant_param1' =>ucwords(strtolower($Name)),
    'merchant_param2' =>$Email,
    'merchant_param3' =>$Phoneno,
    'merchant_param4' =>$sadmission,
];

$merchant_data = "";
foreach ($data as $key => $value) {
    $merchant_data .= $key . '=' . urlencode($value) . '&';
}

$encrypted_data = encrypt($merchant_data, $working_key);
        // foreach ($_POST as $key => $value){
        //   $merchant_data.=$key.'='.$value.'&';
        // }

        // $merchant_data=str_replace('764451tid=&','',$merchant_data);
        // $encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

      ?>

      <form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
        <?php
          echo "<input type=hidden name=encRequest value=$encrypted_data>";
          echo "<input type=hidden name=access_code value=$access_code>";
        ?>
      </form>
    </center>
    <script language='javascript'>
    document.redirect.submit();</script>
  </body>
</html>