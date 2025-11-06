<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php
$AdmissionNo=$_REQUEST["txtAdmissionNo"];
$merchantTxnId = uniqid();
		$sqlStudentDetail = "select `sfathername` from  `student_master` where `sadmission`='$AdmissionNo'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);

		while($rows = mysqli_fetch_row($rsStudentDetail))
		{
			$FatherName=$rows[0];
			//$SchoolId=$rows[1];
			break;
		}
		$rsSchoolDetail = mysqli_query($Con, "select `PREFIX`,`SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`AccountNo`,`AffiliationNo`,`SchoolNo`,`website` from `SchoolConfig` where `SchoolId`='$SchoolId'");
		while($rows = mysqli_fetch_row($rsSchoolDetail))
		{
			$PREFIX=$rows[0];
			$SchoolName=$rows[1];
			$SchoolAddress=$rows[2];
			$PhoneNo=$rows[3];
			$LogoURL=$rows[4];
			$AccountNo=$rows[5];
			$AffiliationNo=$rows[6];
			$SchoolNo=$rows[7];
			$website=$rows[8];
			break;
		}
		
$Name=$_REQUEST["txtName"];

$Class=$_REQUEST["txtClass"];

$RollNo=$_REQUEST["txtRollNo"];

$PaymentMode="Online";

$DiscountType=$_REQUEST["cboTuitionFeeDiscountType"];

if ($PaymentMode != "Cash")
{
	$ChequeNo= $_REQUEST["txtChequeNo"];
	//$BankName= $_REQUEST["txtBank"];
	$BankName= $_REQUEST["cboBank"];
	$ChequeDate=$_REQUEST["txtChequeDate"];
	
	$arr=explode('/',$ChequeDate);
	$ChequeDate= $arr[2] . "-" . $arr[0] . "-" . $arr[1];
	$CheckStatus="Pending Clearance";
}

//$FinancialYear = $_REQUEST["txtFinancialYear"];
$FinancialYear = $_REQUEST["cboFinancialYear"];

$Quarter=$_REQUEST["cboQuarter"];

$TuitionFee=$_REQUEST["txtTuition"];

$HostelFee=$_REQUEST["txtHostel"];

$AnnualFee=$_REQUEST["txtAnnualFee"];

$TransportFees=$_REQUEST["txtTransportFees"];

$LateFee=$_REQUEST["txtLateFee"];

$LateDays=$_REQUEST["txtLateDays"];

$AdjustedLateFee=$_REQUEST["txtAdjustedLateFee"];

$AdjustedLateDays=$_REQUEST["txtAdjustedLateDays"];

$currentdate=date("Y-m-d");

if ($_REQUEST["txtPreviousBalance"] == "")
{
	$PreviousBalance=0;
}
else
{
	$PreviousBalance=$_REQUEST["txtPreviousBalance"];
}

if ($_REQUEST["txtTuitionFeeDiscount"] == "")
{
	$Discount = 0;
}
else
{
	$Discount = $_REQUEST["txtTuitionFeeDiscount"];
}
if ($_REQUEST["txtHostelFeeDiscount"] == "")
{
	$HostelDiscount = 0;
}
else
{
	$HostelDiscount = $_REQUEST["txtHostelFeeDiscount"];
}
$Discount=$Discount+$HostelDiscount;

$DiscountReason = $_REQUEST["cboDiscountReason"];

$Remarks=$_REQUEST["txtRemarks"];

$Total=$_REQUEST["txtTotal"];

$PayingAmount = $_REQUEST["txtTotalAmtPaying"];

$BalanceAmount = $Total - $PayingAmount;

$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
            $rsFY= mysqli_query($Con, $ssqlFY);
            $row4=mysqli_fetch_row($rsFY);
	        $CurrentFinancialYear=$row4[1];
           	$Year=$row4[0];	



if ($_REQUEST["SubmitType"]=="Final")
{
	//$sqlRcpt = "SELECT MAX(`srno`) +1 FROM  `fees`";
		//$sqlRcpt = "select max(x.`srno`) +1 from (SELECT max(CONVERT(replace(`receipt`,'HR/2015-2016/',''),UNSIGNED INTEGER)) as `srno` FROM `fees`) as `x`";
		$sqlRcpt = "select max(x.`srno`) +1 from (SELECT max(CONVERT(replace(`receipt`,'HR/".$CurrentFinancialYear."/',''),UNSIGNED INTEGER)) as `srno` FROM `fees`) as `x`";
		$rsRcpt = mysqli_query($Con, $sqlRcpt);

		
	
		if ($Quarter=="Q1")
		{
			$Quarter1="Paid";
			$Quarter1Reciept=$NewReciptNo;
			$Quarter1PaymentDate=date("d-m-Y");
		}
		else
		{
			$Quarter1=$_REQUEST["txtQuarter1"];
			$Quarter1Reciept=$_REQUEST["txtQuarter1Reciept"];
			$Quarter1PaymentDate=$_REQUEST["txtQuarter1PaymentDate"];
		}
		
		
		if ($Quarter=="Q2")
		{
			$Quarter2="Paid";
			$Quarter2Reciept=$NewReciptNo;
			$Quarter2PaymentDate=date("d-m-Y");
		}
		else
		{
			$Quarter2=$_REQUEST["txtQuarter2"];
			$Quarter2Reciept=$_REQUEST["txtQuarter2Reciept"];
			$Quarter2PaymentDate=$_REQUEST["txtQuarter2PaymentDate"];
		}
		
		
		if ($Quarter=="Q3")
		{			
			$Quarter3="Paid";
			$Quarter3Reciept=$NewReciptNo;
			$Quarter3PaymentDate=date("d-m-Y");
		}
		else
		{
			$Quarter3=$_REQUEST["txtQuarter3"];
			$Quarter3Reciept=$_REQUEST["txtQuarter3Reciept"];
			$Quarter3PaymentDate=$_REQUEST["txtQuarter3PaymentDate"];
		}
		
		if ($Quarter=="Q4")
		{			
			$Quarter3="Paid";
			$Quarter3Reciept=$NewReciptNo;
			$Quarter3PaymentDate=date("d-m-Y");
			
			$Quarter4="Paid";
			$Quarter4Reciept=$NewReciptNo;
			$Quarter4PaymentDate=date("d-m-Y");
		}
		else
		{
			$Quarter4=$_REQUEST["txtQuarter4"];
			$Quarter4Reciept=$_REQUEST["txtQuarter4Reciept"];
			$Quarter4PaymentDate=$_REQUEST["txtQuarter4PaymentDate"];
		}
	
	
	
	$sstr="select * from `fees` where `sadmission`='$AdmissionNo' and `quarter`='$Quarter' and `FinancialYear`='$FinancialYear' and `FeesType`='Hostel'";
	$rs = mysqli_query($Con, $sstr);
		if (mysqli_num_rows($rs) > 0)
		{
			echo "<br><br><center><b>Fee already submitted for Admission Id:" . $AdmissionNo . ",Quarter:" . $Quarter . ",Financial Year:" . $FinancialYear;
			exit();
		}
	
	
	$ssql="INSERT INTO `fees_temp` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`chequeno`,`bankname`,`cheque_date`,`cheque_status`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`Remarks`,`FeesType`,`TxnAmount`,`TxnId`) VALUES('$AdmissionNo','$Name','$Class','$RollNo','$Total','$PayingAmount','$BalanceAmount','$Quarter','$currentdate','$FinancialYear','Paid','$NewReciptNo','$PayingAmount','$PDFFileName','$PaymentMode','$ChequeNo','$BankName','$ChequeDate','$CheckStatus','$LateFee','$LateDays','$AdjustedLateFee','$AdjustedLateDays','$Remarks','Hostel','$PayingAmount','$merchantTxnId')";
	mysqli_query($Con, $ssql) or die(mysqli_error($Con));
	
			if($Quarter=="Q1")
			{
				$FeeHead="HOSTEL FIRST INSTALLMENT";
			}
			if($Quarter=="Q2")
			{
				$FeeHead="HOSTEL SECOND INSTALLMENT";
			}
			if($Quarter=="Q3")
			{
				$FeeHead="HOSTEL THIRD INSTALLMENT";
			}
			if($Quarter=="Q4")
			{
				$FeeHead="HOSTEL FOURTH INSTALLMENT";
			}
			
			$ssql2="INSERT INTO `fees_student` (`sadmission`,`class`,`Name`,`feeshead`,`amount`,`financialyear`,`FeesType`) VALUES ('$AdmissionNo','$Class','$Name','$FeeHead','$PayingAmount','$FinancialYear','Hostel')";
			//mysqli_query($Con, $ssql2) or die(mysqli_error($Con));

	
	$ssql1="INSERT INTO `fees_transaction` (`sadmission`,`ReceiptNo`,`ReceiptDate`,`TutionFee`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`PreviousBalance`,`CurrentBalance`,`Remarks`,`chequeno`,`bankname`,`FinancialYear`,`cheque_date`,`cheque_status`,`PaymentMode`) VALUES ('$AdmissionNo','$NewReciptNo','$currentdate','$TuitionFee','$LateFee','$LateDays','$AdjustedLateFee','$AdjustedLateDays','$PreviousBalance','$BalanceAmount','$Remarks','$ChequeNo','$BankName','$FinancialYear','$ChequeDate','$CheckStatus','$PaymentMode')";
	//mysqli_query($Con, $ssql1) or die(mysqli_error($Con));
}

//-------------------- Previous Payment history----------------------------------------------------------
	$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear` FROM `fees` where `sadmission`='$AdmissionNo' and `FeesType`='Hostel' order by `quarter`,`FinancialYear` desc limit 4";
	$rs = mysqli_query($Con, $ssql);
	
	$orderAmount=$PayingAmount;
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
			 <input type="hidden" id="firstName" name="firstName" value="<?php echo $Name;?>" />
			 <input type="hidden" id="lastName" name="lastName" value="." />
			 <input type="hidden" id="Name" name="Name" value="<?php echo $Name;?>" />
             <input type="hidden" name="returnUrl" value="http://dpsfsis.com/Users/FeesReceiptHostel.php" />
             <!--<input type="hidden" name="notifyUrl" value="http://dpsfsis.com/AdmissionFeeNotifyResponse.php" />-->
             <input type="hidden" id="secSignature" name="secSignature" value="<?php echo $securitySignature; ?>" />
			 <input type="hidden" name="customParams[0].name" value="AdminNo" /> 
			 <input type="hidden" name="customParams[0].value" value="NA" />			 		
			 	             
	             <!--<input type="Submit" value="Pay Now"/>-->
	             Please wait Payment is in progress</strong></font></div>
			</form>
</body>
</html>





