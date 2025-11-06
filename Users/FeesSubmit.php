<?php include '../connection.php';?>
<?php
	session_start();
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	$AdmissionNo=$_SESSION['userid'];
	
	$HeaderSrNo=$_REQUEST["hHeaderSrNo"];	
	$currentdate=date("Y-m-d");
	$merchantTxnId = uniqid(); 
	
	
	
	
	$rsHeaderDetial=mysqli_query($Con, "select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,(SELECT `HeadType` FROM  `fees_misc_head` where `HeadName`=a.`HeadName`) as `HeadType`,`AnnouncementID` from `fees_misc_announce` as `a` where `sclass`='$StudentClass' and `srno`='$HeaderSrNo'");
	while($row=mysqli_fetch_row($rsHeaderDetial))
	{
		$HeadName=$row[1];
		$HeadAmount=$row[2];
		$sclass=$row[3];
		$LastDate=$row[4];
		$Remarks=$row[5];
		$HeadType=$row[6];
		$AnnouncementID=$row[7];
		break;
	}
	
		$sqlStudentDetail = "select `sfathername`,`sname`,`email`,`Address`,`smobile` from  `student_master` where `sadmission`='$AdmissionNo'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
		while($row=mysqli_fetch_row($rsStudentDetail))
		{
			$sfathername=$row[0];
			$sname=$row[1];
			$email=$row[2];
			$Address=$row[3];
			$smobile=$row[4];
			break;
		}
		

		
		$ssql="insert into `fees_misc_collection_tmp` (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`HeadType`) values ('$currentdate','$AnnouncementID','$HeadName','$AdmissionNo','$sname','$StudentClass','$StudentRollNo','$HeadAmount','Online','$merchantTxnId','$HeadType')";
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));


//-------------------- Previous Payment history----------------------------------------------------------
	$orderAmount=$HeadAmount;
	//$orderAmount=1;
	$SubmitStatus="successfull";
			
			set_include_path('../lib'.PATH_SEPARATOR.get_include_path());
             //Need to replace the last part of URL("your-vanityUrlPart") with your Testing/Live URL
             $formPostUrl = "https://www.citruspay.com/totalsoft";	
             
             //Need to change with your Secret Key
             $secret_key = "ac3d61806bd38e9dd0c3b3a8d42082143b5ba3a9";
             
             //Need to change with your Vanity URL Key from the citrus panel
             $vanityUrl = "totalsoft";
					

             //Need to change with your Order Amount
             
             $currency = "INR";
             $data= $vanityUrl.$orderAmount.$merchantTxnId.$currency;
             $securitySignature = hash_hmac('sha1', $data, $secret_key);
             
	
?>

<script language="javascript">
	function fnlsubmitform()
	{
		if(document.getElementById("SubmitStatus").value=="successfull")
		{
			document.getElementById("frmPayment").submit();
		}
	}
</script>

<html>
<head></head>
<body onload="Javascript:fnlsubmitform();">



			<form name="frmPayment" id="frmPayment" align="center" method="post" action="<?php echo $formPostUrl; ?>">
			 <div class="style1">
				<font size="3"><strong>
			 <input type="hidden" name="SubmitStatus" id="SubmitStatus" value="<?php echo $SubmitStatus;?>">
	         <input type="hidden" id="merchantTxnId" name="merchantTxnId" value="<?php echo $merchantTxnId; ?>" />
             <input type="hidden" id="orderAmount" name="orderAmount" value="<?php echo $orderAmount; ?>" />
             <input type="hidden" id="currency" name="currency" value="<?php echo $currency; ?>" />
			 <input type="hidden" id="firstName" name="firstName" value="<?php echo $sname;?>" />
			 <input type="hidden" id="lastName" name="lastName" value="." />
			 <input type="hidden" id="Name" name="Name" value="<?php echo $sname;?>" />
             <input type="hidden" name="returnUrl" value="http://dpsfsis.com/Users/MiscFeeCollectionReceipt.php" />
             <!--<input type="hidden" name="notifyUrl" value="http://dpsfsis.com/AdmissionFeeNotifyResponse.php" />-->
             <input type="hidden" id="secSignature" name="secSignature" value="<?php echo $securitySignature; ?>" />
			 <input type="hidden" name="customParams[0].name" value="AdminNo" /> 
			 <input type="hidden" name="customParams[0].value" value="NA" />			 		
			 	             
	             <!--<input type="Submit" value="Pay Now"/>-->
	             Please wait Payment is in progress</strong></font></div>
			</form>
</body>
</html>





