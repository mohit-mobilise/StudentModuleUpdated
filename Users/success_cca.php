
<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php include('Crypto.php');?>

 <?php

	$workingKey='14AD1ABE4E1F13A8F38513B8024B1B2B';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	//echo $encResponse;
	//exit();
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	//echo $rcvdString;
	//exit();
	$order_status="";
	$OrderId="";
	$Ammount="";
	$txnid="";
	$tracking_id="";
	$bank_ref="";
	$status_message="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref=$information[1];
		if($i==8)	$status_message=$information[1];
		if($i==0)	$txnid=$information[1];
		if($i==10)	$Ammount=$information[1];
	}

  // echo $order_status."/".$txnid."/".$Ammount;

  $postData = json_encode($_POST);
  mysqli_query($Con, "INSERT INTO `fees_pymnt_gtway_log`(`TxnId`, `data`) VALUES ('$txnid','$postData')");

  if($order_status=="Success")
  {

    if ($txnid != "")
    {   

      $rsChk=mysqli_query($Con, "select * from `fees` where `TxnId`='$txnid'");
      if(mysqli_num_rows($rsChk)>0)
      {
        echo "<br><br><center><b>Fee Already Submitted!";
        exit();
      }

      $ssql="UPDATE `fees_temp` SET `TxnStatus`='$order_status',`PGTxnId`='$pgtxnno' where `TxnId`='$txnid'";
      mysqli_query($Con, $ssql) or die(mysqli_error($Con));


      $sqlreceipt = mysqli_query($Con, "SELECT max(CAST(replace(`receipt_no`,'TF','') AS SIGNED INTEGER)) as `receipt` FROM `fees`");
      $rsreceipt = mysqli_fetch_assoc($sqlreceipt);
      $receipt_id = $rsreceipt['receipt'];

      if ($receipt_id != '') {

        $receipt_no = $receipt_id + 1;
      
      } else {
          
        $receipt_no = 1;
      }

      $receipt = 'TF'.$receipt_no; 


      $ssql="UPDATE `fees_temp` SET `receipt_no`='$receipt' where `TxnId`='$txnid'";
      mysqli_query($Con, $ssql) or die(mysqli_error($Con));

      $ssql1="UPDATE `fees_transaction_temp` SET `receipt_no`='$receipt' where `TxnId`='$txnid'";
      mysqli_query($Con, $ssql1) or die(mysqli_error($Con));


      $ssqlF="INSERT INTO `fees`(`sadmission`, `cr_amnt`, `dr_amnt`, `Late_fees`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `cheque_date`, `chequeno`, `bankname`, `branch`, `MICRCode`, `TransCode`, `cheque_bounce_date`, `cheque_bounce_amt`, `ChequeBounceRemark`, `Remarks`, `cheque_status`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `SendToBank`, `student_remark`, `datetime`, `status`, `created_at`, `create_by`, `updated_at`, `updated_by`, `school_bank`, `system_remark`) SELECT `sadmission`, `cr_amnt`, `dr_amnt`, `Late_fees`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `cheque_date`, `chequeno`, `bankname`, `branch`, `MICRCode`, `TransCode`, `cheque_bounce_date`, `cheque_bounce_amt`, `ChequeBounceRemark`, `Remarks`, `cheque_status`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `SendToBank`, `student_remark`, `datetime`, `status`, `created_at`, `create_by`, `updated_at`, `updated_by`, `school_bank`, `system_remark` FROM `fees_temp` where `TxnId`='$txnid'";
      mysqli_query($Con, $ssqlF) or die(mysqli_error($Con));

      $ssqlF1="INSERT INTO `fees_transaction`(`sadmission`, `receipt_no`, `head_id`, `cr_amnt`, `dr_amnt`, `Month`, `fy`,`TxnId`, `InputSource`, `status`, `trash`, `created_at`, `created_by`, `updated_at`, `updated_by`)  SELECT  `sadmission`, `receipt_no`, `head_id`, `cr_amnt`, `dr_amnt`, `Month`, `fy`,`TxnId`, `InputSource`, `status`, `trash`, `created_at`, `created_by`, `updated_at`, `updated_by` FROM `fees_transaction_temp` where `TxnId`='$txnid'";
      mysqli_query($Con, $ssqlF1) or die(mysqli_error($Con));
                
      echo "<!DOCTYPE html><html><head><title>Payment Success</title>";
      echo "<link rel='stylesheet' type='text/css' href='../assets/global/plugins/bootstrap-toastr/toastr.min.css'>";
      echo "<link rel='stylesheet' type='text/css' href='assets/css/toastr-custom.css'>";
      echo "<script src='../assets/global/plugins/bootstrap-toastr/toastr.min.js'></script>";
      echo "<script src='assets/js/toastr-config.js'></script>";
      echo "</head><body>";
      echo "<script>toastr.success('Your Fees has been Submitted Successfully', 'Success'); setTimeout(function() { window.location.href='MyFees.php'; }, 1500);</script>";
      echo "</body></html>"; 
    
    } else {
      echo "<br><br><center><b>Txn Id error!";
      exit();
    }

  }	
  else
  {	
    echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly submit the the Fees again<br><br>Click <a href='MyFees.php'>here</a> to restart the Fee Payment process!";
    exit();
  }

?>