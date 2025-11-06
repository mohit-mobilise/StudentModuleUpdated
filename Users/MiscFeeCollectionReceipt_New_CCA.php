
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

		$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
        $rsFY= mysqli_query($Con, $ssqlFY);
        $row4=mysqli_fetch_row($rsFY);
        $CurrentFinancialYear=$row4[1];
       	$Year=$row4[0];

		$currentdate=date("d-m-Y");
		$rstxnDetail=mysqli_query($Con, "select `sadmissionno`,`sname`,`sclass`,`srollno` from `fees_misc_collection_tmp` where `TxnId`='$txnid'");
		while($row=mysqli_fetch_row($rstxnDetail))
		{
			$sadmissionno=$row[0];
			$sname=$row[1];
			$sclass=$row[2];
			$srollno=$row[3];
			break;
		}

		$ssql="UPDATE `fees_misc_collection_tmp` SET `TxnStatus`='$txnstatus',`PGTxnId`='$trackid' where `TxnId`='$txnid'";
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));

		$rsCnt=mysqli_query($Con, "SELECT max(CONVERT(replace(`FeeReceipt`,'MR/".$CurrentFinancialYear."/',''),UNSIGNED INTEGER)) as `cnt` FROM `fees_misc_collection`");
			if (mysqli_num_rows($rsCnt) > 0)
			{
				while($rowCnt = mysqli_fetch_row($rsCnt))
				{
					if($rowCnt[0]=="")
					{
					$NewSrNo="1";
					}
					else
					{
						$NewSrNo=$rowCnt[0]+1;
					}
					break;
				}
			}
			else
			{
				$NewSrNo="1";
			}
			
			$ReceiptNo="MR/".$CurrentFinancialYear."/".$NewSrNo;
			mysqli_query($Con, "UPDATE `fees_misc_collection_tmp` SET `FeeReceipt`='$ReceiptNo' where `TxnId`='$txnid'") or die(mysqli_error($Con));
			$rsChk= mysqli_query($Con, "select * from `fees_misc_collection` where `TxnId`='$txnid'");
			if (mysqli_num_rows($rsChk) == 0)
			{
				mysqli_query($Con, "insert into fees_misc_collection (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,`financialyear`) select `date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,'$Year' from `fees_misc_collection_tmp` where `TxnId`='$txnid'") or die(mysqli_error($Con));
			}
			
			echo "<script>alert('Your Fees has been Submitted Successfully');window.location.href='MyFees.php';</script>";   
    
    } else {
      echo "<br><br><center><b>Txn Id error!";
      exit();
    }

  }	
  else
  {	
	$ssql="UPDATE `fees_misc_collection_tmp` SET `TxnStatus`='$order_status',`PGTxnId`='$tracking_id' where `TxnId`='$txnid'";
	mysqli_query($Con, $ssql) or die(mysqli_error($Con));
		
    echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly submit the the Fees again<br><br>Click <a href='MyFees.php'>here</a> to restart the Fee Payment process!";
    exit();
  }

?>