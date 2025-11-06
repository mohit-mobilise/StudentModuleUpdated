<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php
$status=$_POST["status"];
$firstname=$_POST["firstname"];
$amount=$_POST["amount"]; //Please use the amount value from database
$txnid=$_POST["txnid"];
$posted_hash=$_POST["hash"];
$key=$_POST["key"];
$productinfo=$_POST["productinfo"];
$email=$_POST["email"];
$udf1=$_POST["udf1"];

$salt="oTTleS1b"; //Please change the value with the live salt for production environment


//Validating the reverse hash
If (isset($_POST["additionalCharges"])) 
{
       $additionalCharges=$_POST["additionalCharges"];
        $retHashSeq = $additionalCharges.'|'.$salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;
        
                  }
	else {	  

        $retHashSeq = $salt.'|'.$status.'||||||||||'.$udf1.'|'.$email.'|'.$firstname.'|'.$productinfo.'|'.$amount.'|'.$txnid.'|'.$key;

         }
		 $hash = hash("sha512", $retHashSeq);
		 
       if ($hash != $posted_hash || $status !="success") 
       {
	       echo "Transaction has been tampered. Please try again";
	        exit();

		}
	  
	   else 
	   {
	  
        
			              $rsChk=mysqli_query($Con, "select * from `fees` where `CommonTxnId`='$txnid'");
						if(mysqli_num_rows($rsChk)>0)
						{
							echo "<br><br><center><b>Fee Already Submitted!";
							exit();
						}
						
					
			
						
						$ssql="UPDATE `fees_temp` SET `TxnStatus`='$status',`PGTxnId`='$pgtxnno' where `CommonTxnId`='$txnid'";
						mysqli_query($Con, $ssql) or die(mysqli_error($Con));
						$ssql="UPDATE `fees_transaction_temp` SET `TxnStatus`='$status',`PGTxnId`='$pgtxnno' where `CommonTxnId`='$txnid'";
						mysqli_query($Con, $ssql) or die(mysqli_error($Con));
						
						
						if($status !="success")
						{
							echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees_common_monthly.php'>here</a> to restart the process!";
							exit();
						}
						
							$ssqlFY="SELECT distinct `financialyear`,`year` FROM `FYmaster` where `Status`='Active'";
			            $rsFY= mysqli_query($Con, $ssqlFY);
			            $row4=mysqli_fetch_row($rsFY);
				        $SelectedFinancialYear=$row4[0];

			        $sqlmutlipletxnid = mysqli_query($Con, "SELECT `TxnId` FROM `fees_temp` WHERE `CommonTxnId`='$txnid' order by `srno`");
			        while ($rsmutlipletxnid = mysqli_fetch_assoc($sqlmutlipletxnid)) 
			        {
			        	$mutlipletxnid='';
			        	$mutlipletxnid = $rsmutlipletxnid['TxnId'];
			        
						
						$rsReceiptNo=mysqli_query($Con, "select MAX(CAST(REPLACE(x.`receipt`,'TF/".$SelectedFinancialYear."/','') as UNSIGNED))+1 from (SELECT distinct `receipt` FROM `fees`) as `x`");
						if (mysqli_num_rows($rsReceiptNo) > 0)
						{
							while($rowRcpt = mysqli_fetch_row($rsReceiptNo))
							{
								if($rowRcpt[0]=="")
									$NewReciptNo='TF/'.$SelectedFinancialYear.'/1';
								else
									$NewReciptNo='TF/'.$SelectedFinancialYear.'/'.$rowRcpt[0];
								break;
							}
						}
						else
						{
							$NewReciptNo='TF/'.$SelectedFinancialYear.'/1';
						}	
				
					
							$ssql="UPDATE `fees_temp` SET `receipt`='$NewReciptNo' where `TxnId`='$mutlipletxnid'";
							mysqli_query($Con, $ssql) or die(mysqli_error($Con));

							$ssql1="UPDATE `fees_transaction_temp` SET `ReceiptNo`='$NewReciptNo' where `TxnId`='$mutlipletxnid'";
							mysqli_query($Con, $ssql1) or die(mysqli_error($Con));


						$ssqlF="INSERT INTO `fees` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`AdjustedLateFee`,`DiscountAmount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`chequeno`,`bankname`,`branch`,`cheque_date`,`cheque_status`,`DebitHead`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`ActualLateFee`,`cheque_bounce_amt`,`FeeMonth`,`CommonTxnId`) select `sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`AdjustedLateFee`,`DiscountAmount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`chequeno`,`bankname`,`branch`,`cheque_date`,`cheque_status`,`DebitHead`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`ActualLateFee`,`cheque_bounce_amt`,`FeeMonth`,`CommonTxnId` from `fees_temp` where `TxnId`='$mutlipletxnid' ";
							mysqli_query($Con, $ssqlF) or die(mysqli_error($Con));
			
							$ssqlF1="INSERT INTO `fees_transaction` (`feeshead`,`headamount`,`sadmission`,`sname`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`DiscountAmount`,`Remarks`,`FinancialYear`,`finalamount`,`FeeMonth`,`FeeYear`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`quarter`,`PaidAmount`,`PaymentMode`,`cheque_bounce_amt`,`CommonTxnId`) select `feeshead`,`headamount`,`sadmission`,`sname`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`DiscountAmount`,`Remarks`,`FinancialYear`,`finalamount`,`FeeMonth`,`FeeYear`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`quarter`,`PaidAmount`,`PaymentMode`,`cheque_bounce_amt`,`CommonTxnId` from `fees_transaction_temp` where `TxnId`='$mutlipletxnid'";
							mysqli_query($Con, $ssqlF1) or die(mysqli_error($Con));	
				    
				    }//multiple txnid

					
		echo "<!DOCTYPE html><html><head><title>Payment Success</title>";
		echo "<link rel='stylesheet' type='text/css' href='../assets/global/plugins/bootstrap-toastr/toastr.min.css'>";
		echo "<link rel='stylesheet' type='text/css' href='assets/css/toastr-custom.css'>";
		echo "<script src='../assets/global/plugins/bootstrap-toastr/toastr.min.js'></script>";
		echo "<script src='assets/js/toastr-config.js'></script>";
		echo "</head><body>";
		echo "<script>toastr.success('Your Fees has been Submitted Successfully', 'Success'); setTimeout(function() { window.location.href='MyFees_common_monthly.php'; }, 1500);</script>";
		echo "</body></html>";		
			
								
			}
		


?>
