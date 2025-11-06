<?php include '../connection.php';?>
<?php include '../AppConf.php';?>

<?php
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"]; //Please use the amount value from database
$txnid=$_REQUEST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$udf1=$_POST["udf1"];

$salt= $hostel_app_salt_key; //Please change the value with the live salt for production environment


//Validating the reverse hash
      if (isset($_POST["additionalCharges"])) 
      {
             $additionalCharges=$_POST["additionalCharges"];
              $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
              
       }
      else {    

        $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }

     $hash = hash("sha512", $retHashSeq);

echo $status."/".$txnid."/".$amount;
//exit();

            $postData = json_encode($_POST);
            mysqli_query($Con, "INSERT INTO `fees_pymnt_gtway_log`(`TxnId`, `data`) VALUES ('$txnid','$postData')");


            $rsChk=mysqli_query($Con, "select * from `hostel_fees` where `TxnId`='$txnid'");
            if(mysqli_num_rows($rsChk)>0)
            {
              echo "<br><br><center><b>Fee Already Submitted!";
              exit();
            }
            
          
      
            
            $ssql="UPDATE `hostel_fees_temp` SET `TxnStatus`='$status',`PGTxnId`='$pgtxnno' where `TxnId`='$txnid'";
            mysqli_query($Con, $ssql) or die(mysqli_error($Con));
            
            
            if($status !="success")
            {
              echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees.php'>here</a> to restart the process!";
              exit();
            }


          $sqlreceipt = mysqli_query($Con, "SELECT max(CAST(replace(`receipt_no`,'TF','') AS SIGNED INTEGER)) as `receipt` FROM `hostel_fees`");
          $rsreceipt = mysqli_fetch_assoc($sqlreceipt);
          $receipt_id = $rsreceipt['receipt'];

            if ($receipt_id != '') {

              $receipt_no = $receipt_id + 1;
            }
            else {
               
               $receipt_no = 1;
            }

            $receipt = 'TF'.$receipt_no; 
            

              $ssql="UPDATE `hostel_fees_temp` SET `receipt_no`='$receipt' where `TxnId`='$txnid'";
              mysqli_query($Con, $ssql) or die(mysqli_error($Con));

              $ssql1="UPDATE `hostel_fees_transaction_temp` SET `receipt_no`='$receipt' where `TxnId`='$txnid'";
              mysqli_query($Con, $ssql1) or die(mysqli_error($Con));


              $ssqlF="INSERT INTO `hostel_fees`(`sadmission`, `cr_amnt`, `dr_amnt`, `Late_fees`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `cheque_date`, `chequeno`, `bankname`, `branch`, `MICRCode`, `TransCode`, `cheque_bounce_date`, `cheque_bounce_amt`, `ChequeBounceRemark`, `Remarks`, `cheque_status`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `SendToBank`, `student_remark`, `datetime`, `status`, `created_at`, `create_by`, `updated_at`, `updated_by`, `school_bank`, `system_remark`) SELECT `sadmission`, `cr_amnt`, `dr_amnt`, `Late_fees`, `FinancialYear`, `quarter`, `FeeMonth`, `receipt_date`, `receipt_no`, `PaymentMode`, `cheque_date`, `chequeno`, `bankname`, `branch`, `MICRCode`, `TransCode`, `cheque_bounce_date`, `cheque_bounce_amt`, `ChequeBounceRemark`, `Remarks`, `cheque_status`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `SendToBank`, `student_remark`, `datetime`, `status`, `created_at`, `create_by`, `updated_at`, `updated_by`, `school_bank`, `system_remark` FROM `hostel_fees_temp` where `TxnId`='$txnid'";
              mysqli_query($Con, $ssqlF) or die(mysqli_error($Con));
      
              $ssqlF1="INSERT INTO `hostel_fees_transaction`(`sadmission`, `receipt_no`, `head_id`, `cr_amnt`, `dr_amnt`, `Month`, `fy`,`TxnId`, `InputSource`, `status`, `trash`, `created_at`, `created_by`, `updated_at`, `updated_by`)  SELECT  `sadmission`, `receipt_no`, `head_id`, `cr_amnt`, `dr_amnt`, `Month`, `fy`,`TxnId`, `InputSource`, `status`, `trash`, `created_at`, `created_by`, `updated_at`, `updated_by` FROM `hostel_fees_transaction_temp` where `TxnId`='$txnid'";
              mysqli_query($Con, $ssqlF1) or die(mysqli_error($Con));
            

          
          echo "<!DOCTYPE html><html><head><title>Payment Success</title>";
          echo "<link rel='stylesheet' type='text/css' href='../assets/global/plugins/bootstrap-toastr/toastr.min.css'>";
          echo "<link rel='stylesheet' type='text/css' href='assets/css/toastr-custom.css'>";
          echo "<script src='../assets/global/plugins/bootstrap-toastr/toastr.min.js'></script>";
          echo "<script src='assets/js/toastr-config.js'></script>";
          echo "</head><body>";
          echo "<script>toastr.success('Your Fees has been Submitted Successfully', 'Success'); setTimeout(function() { window.location.href='MyFees.php'; }, 1500);</script>";
          echo "</body></html>";   
      
                
      // }
    


?>
