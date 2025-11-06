<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php
// Merchant key here as provided by Payu
$MERCHANT_KEY = "Q8elkr"; //Please change this value with live key for production
   $hash_string = '';
// Merchant Salt as provided by Payu
$SALT = "oTTleS1b"; //Please change this value with live salt for production

// End point - change to https://secure.payu.in for LIVE mode
//$PAYU_BASE_URL = "https://test.payu.in";
$PAYU_BASE_URL = "https://secure.payu.in";
$action = '';

$posted = array();
if(!empty($_POST)) {
    //print_r($_POST);
  foreach($_POST as $key => $value) {    
    $posted[$key] = $value; 
	
  }
}

$formError = 0;

if(empty($posted['txnid'])) {
   // Generate random transaction id
  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
} else {
  $txnid = $posted['txnid'];
}

	
if ($_REQUEST["txtAdmissionNo"]!= "")
{
		$AdmissionNo=$_REQUEST["txtAdmissionNo"];
		$Quarter=$_REQUEST["cboQuarter"];
		$SelectedMonth=$_REQUEST["cboMonth"];
		$LateFee=$_REQUEST["LateFee"];
		$BounceCharges=$_REQUEST["BounceCharges"];
		$OrderAmount=$_REQUEST["OrderAmount"];
		$FeeAmountWithoutLateFee=$_REQUEST["InstallmentWithoutLateFee"];
		$FeeAmountWithLateFee=$_REQUEST["OrderAmount"];
	
		$sqlStudentDetail = "select `sfathername`,`sname`,`sclass`,`srollno`,`MotherName`,`Address`,`smobile`,`email` from  `student_master` where `sadmission`='$AdmissionNo'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
		
		while($row = mysqli_fetch_row($rsStudentDetail))
		{
			$FatherName=$row[0];
			$Name=$row[1];
			$Class=$row[2];
			$RollNo=$row[3];
			$MotherName=$row[4];
			$Address=$row[5];
			$Email=$row[7];
			$Phoneno=$row[6];
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
		$LastName="NA";
		
		$rsCFY= mysqli_query($Con, "SELECT `year` FROM `FYmaster` where `Status`='Active'");
		$rowCurrentFy=mysqli_fetch_row($rsCFY);
		$FinancialYear=$rowCurrentFy[0];
		
		
	$sqlStudentDetail = "select `sfathername`,`sname`,`email`,`Address`,`smobile` from  `student_master` where `sadmission`='$AdmissionNo'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);

		while($rows = mysqli_fetch_row($rsStudentDetail))
		{
			$FatherName=$rows[0];
			$Paymentsname=$rows[1];
			$Paymentemail=$rows[2];
			$PaymentAddress=$rows[3];
			$Paymentsmobile=$rows[4];
			
			break;
		}
		$rsSchoolDetail = mysqli_query($Con, "select `PREFIX`,`SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`AccountNo`,`AffiliationNo`,`SchoolNo`,`website` from `SchoolConfig` where `SchoolId`='$SchoolId'");
		while($rows = mysqli_fetch_row($rsSchoolDetail))
		{
			$PREFIX=$rows[0];
			$SchoolName=$rows[1];
			$SchoolAddress=$rows[2];
			//$PhoneNo=$rows[3];
			$LogoURL=$rows[4];
			$AccountNo=$rows[5];
			$AffiliationNo=$rows[6];
			$SchoolNo=$rows[7];
			$website=$rows[8];
			break;
		}


	 	
	$sqlStudentDetail = "select `sfathername`,`sname`,`email`,`Address`,`smobile` from  `student_master` where `sadmission`='$AdmissionNo'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);

		while($rows = mysqli_fetch_row($rsStudentDetail))
		{
			$Paymentsname=$rows[1];
			$Paymentemail=$rows[2];
			$PaymentAddress=$rows[3];
			$Paymentsmobile=$rows[4];
			
			break;
		}
		$rsSchoolDetail = mysqli_query($Con, "select `PREFIX`,`SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`AccountNo`,`AffiliationNo`,`SchoolNo`,`website` from `SchoolConfig` where `SchoolId`='$SchoolId'");
		while($rows = mysqli_fetch_row($rsSchoolDetail))
		{
			$PREFIX=$rows[0];
			$SchoolName=$rows[1];
			$SchoolAddress=$rows[2];
			//$PhoneNo=$rows[3];
			$LogoURL=$rows[4];
			$AccountNo=$rows[5];
			$AffiliationNo=$rows[6];
			$SchoolNo=$rows[7];
			$website=$rows[8];
			break;
		}
		
		


	
			$sqlStudentDetail = "select `sfathername`,`sname`,`sclass`,`srollno`,`MotherName`,`Address`,`smobile`,`email` from  `student_master` where `sadmission`='$AdmissionNo'";
			$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
		
	  	$rsQuarterString=mysqli_query($Con, "select distinct `Month`,DATE_FORMAT(`FeesSubmissionLastDate`,'%d-%M-%Y') from `fees_master` where `Quarter`='$Quarter' and `financialyear`='$FinancialYear'");

			$QuarterString="";

			$reccnt=1;

			while($rowQS = mysqli_fetch_row($rsQuarterString))

			{

				if($reccnt==1)

				{

					$LastDtFeeDepositBank=$rowQS[1];

				}

				$QuarterString=$QuarterString.$rowQS[0];

				$reccnt=$reccnt+1;

			}


		while($row = mysqli_fetch_row($rsStudentDetail))
		{
			$Name=$row[1];
			$Class=$row[2];
			$RollNo=$row[3];
			$MotherName=$row[4];
			$Address=$row[5];
			$Email=$row[7];
			$Phoneno=$row[6];
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
		$LastName="NA";

			
					$rsSchoolDetail = mysqli_query($Con, "select `PREFIX`,`SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`AccountNo`,`AffiliationNo`,`SchoolNo`,`website` from `SchoolConfig` where `SchoolId`='$SchoolId'");
					while($rows = mysqli_fetch_row($rsSchoolDetail))
					{
						$PREFIX=$rows[0];
						$SchoolName=$rows[1];
						$SchoolAddress=$rows[2];
						//$PhoneNo=$rows[3];
						$LogoURL=$rows[4];
						$AccountNo=$rows[5];
						$AffiliationNo=$rows[6];
						$SchoolNo=$rows[7];
						$website=$rows[8];
						break;
					}
					
				$PaymentMode="Online";
			
				$currentdate=date("Y-m-d");
				
			
				$NewReciptNo="";
			
				
				$sstr="select * from `fees` where `sadmission`='$AdmissionNo' and `quarter`='$Quarter' and `FeeMonth`='$SelectedMonth' and `FinancialYear`='$FinancialYear'  and `cheque_status` !='Bounce'";
				$rs = mysqli_query($Con, $sstr);
					if (mysqli_num_rows($rs) > 0)
					{
						echo "<br><br><center><b>Fee already submitted for Admission Id:" . $AdmissionNo . ",Quarter:" . $Quarter . ",Financial Year:" . $FinancialYear;
						exit();
					}
				
				
				$currentDateTime=date("Y-m-d h:i:sa");
				
				$ssql="INSERT INTO `fees_temp` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`PayableAfterDiscount`,`amountpaid`,`BalanceAmt`,`PreviousBalance`,`quarter`,`date`,`datetime`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`Remarks`,`FeesType`,`TxnId`,`cheque_bounce_amt`,`TxnAmount`,`FeeMonth`) VALUES('$AdmissionNo','$Name','$Class','$RollNo','$FeeAmountWithLateFee','$OrderAmount','$OrderAmount','$BalanceAmount','$PreviousBalance','$Quarter','$currentdate','$currentdatetime','$FinancialYear','Paid','$NewReciptNo','$OrderAmount','$PDFFileName','$PaymentMode','$LateFee','$TotalDelayDays','$LateFee','$Remarks','Regular','$txnid','$BounceCharges','$OrderAmount','$SelectedMonth')";
			
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));
			
				$rsPreviousQuaterPaid=mysqli_query($Con, "select `quarter`,`BalanceAmt`,`receipt` from `fees` where `sadmission`='$AdmissionNo' and `cheque_status`!='Bounce' and `FinancialYear`='$FinancialYear' order by `datetime` desc");
				$rowB=mysqli_fetch_row($rsPreviousQuaterPaid);
				$PreviousQuartePaid=$rowB[0];
				$PreviousBalance=$rowB[1];
				$LastPaymentReceiptNo=$rowB[2];

					
				//$TotalFeeHead=$_REQUEST["hTotalFeeHead"];
			
				//echo "select distinct `feeshead` from `fees_student` where `sadmission`='$AdmissionNo' and `financialyear`='$FinancialYear' and quarter='$Quarter' ORDER BY `feeshead`";
				//exit();
				$rsFee1=mysqli_query($Con, "select distinct `feeshead` from `fees_student` where `sadmission`='$AdmissionNo' and `financialyear`='$FinancialYear' and quarter='$Quarter' ORDER BY `feeshead`");	
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
					$ssql="select `Balance` from `fees_transaction` where `feeshead`='$feeshead' and `ReceiptNo`='$LastPaymentReceiptNo' and `cheque_status`!='Bounce'";
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
					
					$rsFee=mysqli_query($Con, "select distinct `feeshead`,sum(`head_original_amount`),sum(`head_concession_amount`),sum(`amount`) from `fees_student` where `sadmission`='$AdmissionNo' and `feeshead`='$feeshead' and `Quarter` ='$Quarter' and `Month`='$SelectedMonth' and `financialyear`='$FinancialYear' group by `feeshead`");
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
			
				
			
					$FeeHead=$feeshead;
			
					$FeeHeadOrigAmount=$OriginalAmount+$FeeHeadPreviousBalance;
			
					$FeeHeadDiscountAmt=$DiscountAmount;
			
					$FeeHeadAdhocConcession=0;
			
					$FeeHeadAfterDiscountValue=$amount;
			
					$HeadActualPaidAfterDiscountAmount=$amount;
			
					$HeadWiseBalanceAmount=0;
			
					$ssql1="INSERT INTO `fees_transaction_temp` (`sadmission`,`feeshead`,`headamount`,`DiscountAmount`,`AdhocDiscountAmt`,`finalamount`,`PaidAmount`,`TxnAmount`,`Balance`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`PreviousBalance`,`Remarks`,`ChequeNo`,`bankname`,`FinancialYear`,`ChequeDate`,`cheque_status`,`PaymentMode`,`sname`,`quarter`,`cheque_bounce_amt`,`TxnId`,`TxnStatus`,`FeeMonth`,`FeeYear`) VALUES ('$AdmissionNo','$FeeHead','$FeeHeadOrigAmount','$FeeHeadDiscountAmt','$FeeHeadAdhocConcession','$FeeHeadAfterDiscountValue','$HeadActualPaidAfterDiscountAmount','$HeadActualPaidAfterDiscountAmount','$HeadWiseBalanceAmount','$NewReciptNo','$currentdate','$LateFee','$TotalDelayDays','$LateFee','$AdjustedLateDays','$PreviousBalance','$Remarks','$ChequeNo','$BankName','$FinancialYear','$ChequeDate','$CheckStatus','$PaymentMode','$Name','$Quarter','$BounceCharges','$txnid','Pending','$SelectedMonth','$FinancialYear')";
					
					mysqli_query($Con, $ssql1) or die(mysqli_error($Con));

				}//End of While Loop
				
		$SubmitStatus="successfull";
		//echo $SubmitStatus;
		//exit();
    


}
?>
<?php
	
$hash = '';
// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
if(empty($posted['hash']) && sizeof($posted) > 0) {
  if(
               
		 empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
         
  ) 
  {
    $formError = 1;
  } 
  else 
  {
    
	$hashVarsSeq = explode('|', $hashSequence); 		
	foreach($hashVarsSeq as $hash_var) 
	{
      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
      $hash_string .= '|';
    }

    $hash_string .= $SALT;
	

    $hash = strtolower(hash('sha512', $hash_string));
     //echo $hash_string;
  		//exit();
    $action = $PAYU_BASE_URL . '/_payment';
  }
  } 
  elseif(!empty($posted['hash'])) 
  {
  $hash = $posted['hash'];
 
  $action = $PAYU_BASE_URL . '/_payment';
  }

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



<head>

<meta http-equiv="Content-Language" content="en-us">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title><?php echo $SchoolName ?> </title>

<style type="text/css">
.style1 {
	text-align: center;
	font-family: Cambria;
}
</style>
<script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      //if(hash == '') {
        //return;
      //}
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
</head>

<body onload="submitPayuForm()">
  <h2>PayU Form</h2>
    <br/>
    <?php if($formError) { ?>
      <span style="color:red">Please fill all mandatory fields.</span>
      <br/>
      <br/>
    <?php } ?>

<h2 align="center"><font color="#FF0000" style="font-size: 16pt">Please click 
&quot;Proceed&quot; button to make the payment online</font></h2>
	<form action="<?php echo $action; ?>" method="post" name="payuForm">
      <input type="hidden" name="key" value="<?php echo $MERCHANT_KEY; ?>" />
      <input type="hidden" name="hash"  value="<?php echo $hash; ?>"/>
      <input type="hidden" name="txnid" value="<?php echo $txnid; ?>" />
        <input type="hidden" name="surl" value="http://nkbpsis.in/Users/success_monthly.php"/> <!-- Success notification -->
	  <input type="hidden" name="furl" value="http://nkbpsis.in/Users/success_monthly.php"/> <!-- Failure notification -->
	  
      <input type="hidden" name="amount"  value="<?php echo (empty($posted['amount'])) ? $OrderAmount : $posted['amount'] ?>" />
     <!--<input type="hidden" name="amount" value="10.00" />-->
	  <input type="hidden" name="productinfo" value="School Fee" />
	
	 <!--- <input type="hidden" name="service_provider" value="payu_paisa" size="64" />-->
	<!--- <input name="pg" type="hidden" value="<?php echo (empty($posted['pg'])) ? '' : $posted['pg']; ?>" />-->
	  <!---    <input type="hidden" name="udf1" value="<?php echo (empty($posted['udf1'])) ? $SchoolID : $posted['udf1']; ?>" >-->
	    <input type="hidden" name="udf1" value="<?php echo (empty($posted['udf1'])) ? $AdmissionNo : $posted['udf1']; ?>" />
      <div align="center">
      <table style="border-collapse: collapse" border="1" bordercolor="#000000" width="60%" height="292">
        <tr>
          <td><b><font face="Cambria">First Name:</font></b></td>
          <td><input name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? $Name : $posted['firstname']; ?>"  readonly/></td>
       <td><b><font face="Cambria">Email: </font></b> </td>
          <td><input name="email" id="email" value="<?php echo (empty($posted['email'])) ? $Email : $posted['email']; ?>"  readonly/></td>
        </tr>
        
        
        <tr>
          
          <td><b><font face="Cambria">Phone: </font></b> </td>
          <td><input name="phone" value="<?php echo (empty($posted['phone'])) ? $Phoneno : $posted['phone']; ?>" readonly /></td>
          <td><b><font face="Cambria">Order Amount: </font></b> </td>
          <td colspan="3"><b>Rs. <?php echo (empty($posted['amount'])) ? $OrderAmount : $posted['amount'] ?></b></td>
        </tr>
        <tr>
          
        </tr>
        <tr>
         
            <td colspan="4">
              <?php if(!$hash) { ?>

			<p align="center">
			<input type="submit" value="Proceed" style="font-weight: 700" />
			 <?php } ?>
</td>
         
        </tr>
      </table>
    	</div>
    </form>	
	
</body>

</html>