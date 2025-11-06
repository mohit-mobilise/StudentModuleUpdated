<?php include '../connection.php'; ?>
<?php include '../AppConf.php';?>

<?php
	session_start();
	
// 	$StudentClass = $_SESSION['StudentClass'];
// 	$StudentRollNo = $_SESSION['StudentRollNo'];
// 	$AdmissionNo=$_SESSION['userid'];
	
	
	$AdmissionNo=$_REQUEST['misc_admission_no'];
	
	if($AdmissionNo == '') {
	    echo "Please try again!";
	    exit();
	}
	
	$HeaderSrNo=$_REQUEST["hHeaderSrNo"];	
	$currentdate=date("Y-m-d");
	$merchantTxnId= substr(hash('sha256', mt_rand() . microtime()), 0, 20);

	
// 	$rsHeaderDetial=mysqli_query($Con, "select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,(SELECT `HeadType` FROM  `fees_misc_head` where `HeadName`=a.`HeadName` limit 1) as `HeadType`,`AnnouncementID` from `fees_misc_announce` as `a` where `sclass`='$StudentClass' and `srno`='$HeaderSrNo'");
	$rsHeaderDetial=mysqli_query($Con, "select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,(SELECT `HeadType` FROM  `fees_misc_head` where `HeadName`=a.`HeadName` limit 1) as `HeadType`,`AnnouncementID` from `fees_misc_announce` as `a` where `srno`='$HeaderSrNo'");
	
	if(mysqli_num_rows($rsHeaderDetial) == 0) {
	    echo "Invalid, Please try again!";
	    exit();
	}
	
	while($row=mysqli_fetch_row($rsHeaderDetial))
	{
		$HeadName=$row[1];
		$HeadAmount=$row[2];
		$StudentClass=$row[3];
		$LastDate=$row[4];
		$Remarks=$row[5];
		$HeadType=$row[6];
		$AnnouncementID=$row[7];
		break;
	}
	
    	
    $ssqlmisc="select `MaxCount` as `MiscMaxCount` from `fees_misc_head` where `HeadCode`='$HeadName' ";
    $rsmisc = mysqli_query($Con, $ssqlmisc);
    $rowmisc=mysqli_fetch_assoc($rsmisc);
    $MiscMaxCount = $rowmisc['MiscMaxCount'];

	$rsMiscTtlChk=mysqli_query($Con, "select count(*) as `ttl_misc_fee_paid` from `fees_misc_collection` where `HeadName`='$HeadName' ");
    $rowMiscTtlChk = mysqli_fetch_assoc($rsMiscTtlChk);
    $ttl_misc_fee_paid = $rowMiscTtlChk['ttl_misc_fee_paid'];
      
    if($MiscMaxCount <= $ttl_misc_fee_paid) {
        echo "Misc Fee Limit reached!";
	    exit();
    }
      
	 
	$sqlStudentDetail = "select `sfathername`,`sname`,`email`,`Address`,`smobile`,`srollno` from  `student_master` where `sadmission`='$AdmissionNo'";
	$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
	if(mysqli_num_rows($rsStudentDetail) == 0) {
	    echo "Invalid Admission No, Please try again!";
	    exit();
	}
	
	while($row=mysqli_fetch_row($rsStudentDetail))
	{
		$sfathername=$row[0];
		$sname=$row[1];
		$email=$row[2];
		$Address=$row[3];
		$smobile=$row[4];
		$StudentRollNo=$row[5];
		break;
	}
		
	$ssql="insert into `fees_misc_collection_tmp` (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`HeadType`) values ('$currentdate','$AnnouncementID','$HeadName','$AdmissionNo','$sname','$StudentClass','$StudentRollNo','$HeadAmount','3','$merchantTxnId','$HeadType')";
	mysqli_query($Con, $ssql) or die(mysqli_error($Con));


	$orderAmount=$HeadAmount;
	$SubmitStatus="successfull";

	$salt = $app_salt_key;
	$key = $app_merchant_key;
	$action = 'https://secure.payu.in/_payment';
	
	$phone=$smobile;
	$udf5=$AdmissionNo;
	
	$hash=hash('sha512', $key.'|'.$merchantTxnId.'|'.$orderAmount.'|'.$HeadName.'|'.$sname.'|'.$email.'|||||'.$udf5.'||||||'.$salt);
?>

<html>

<script type="text/javascript">  
function autoformsubmit()
{
	document.getElementById("Form1").submit();
}
</script>

<body onload="autoformsubmit()">
	<form action="<?php echo $action; ?>" id="Form1" method="post">
		<input type="hidden" id="udf5" name="udf5" value="<?php echo $udf5; ?>" />
		<input type="hidden" id="surl" name="surl" value="<?= $BaseURL; ?>Users/MiscFeeCollectionReceipt_New.php" />
		<input type="hidden" id="furl" name="furl" value="<?= $BaseURL; ?>Users/MiscFeeCollectionReceipt_New.php" />
		<input type="hidden" id="curl" name="curl" value="<?= $BaseURL; ?>Users/MiscFeeCollectionReceipt_New.php" />
		<input type="hidden" id="key" name="key" value="<?php echo $key; ?>" />
		<input type="hidden" id="txnid" name="txnid" value="<?php echo $merchantTxnId; ?>" />
		<input type="hidden" id="amount" name="amount" value="<?php echo $orderAmount; ?>" />
		<input type="hidden" id="productinfo" name="productinfo" value="<?php echo $HeadName; ?>" />
		<input type="hidden" id="firstname" name="firstname" value="<?php echo $sname;?>" />
		<input type="hidden" id="Lastname" name="Lastname" value="NA" />
		<input type="hidden" id="Zipcode" name="Zipcode" value="<?php echo "222135";?>" />
		<input type="hidden" id="email" name="email" value="<?php echo $email;?>" />
		<input type="hidden" id="phone" name="phone" value="<?php echo $phone;?>" />
		<input type="hidden" id="address1" name="address1" value="NA" />
		<input type="hidden" id="address2" name="address2" value="NA" />
		<input type="hidden" id="city" name="city" value="NA" />
		<input type="hidden" id="state" name="state" value="NA" />
		<input type="hidden" id="country" name="country" value="India" />
		<input type="hidden" id="hash" name="hash" value="<?php echo $hash; ?>" />
		<div class="style1">
			<font size="3"><strong>
				Please wait Payment is in progress</strong>
			</font>
		</div>
	</form>
</body>
</html>