<?php
	session_start();
	include '../connection.php';
	include '../AppConf.php';
?>
<?php
// switch ($_POST) {

// 	case isset($_POST['student_fee_submit']):
// 		student_fee_submit();
// 		break;
	
// 	default:
// 		break;
// }


// function student_fee_submit()
// {


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

   if ($_REQUEST["admission"]!= "")
	{
		$AdmissionNo=$_REQUEST["admission"];

		$OrderAmount=$_REQUEST["totalfeediffamt"];
		$FeeAmountWithLateFee=$_REQUEST["totalfeediffamt"];

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


		$PaymentMode="Online";
			
		$currentdate=date("Y-m-d");
					
		$NewReciptNo="";

        $TotalRecords=$_REQUEST["totalrec"];

		for ($i=0; $i <$TotalRecords; $i++) 
		{ 
          
        $monthtxnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
          
		$Ctrlfeediffamt= "feediffamt".$i;
		$Ctrlquarter= "quarter".$i;
		$Ctrlmonth= "month".$i;
		$CtrlLateFee= "LateFee".$i;
		$CtrlBounceCharges= "BounceCharges".$i;
		$CtrlInstallmentWithoutLateFee= "InstallmentWithoutLateFee".$i;
		
		$Ctrltotalfeeamt= "totalfeeamt".$i;

        $totalfeeamt = $_REQUEST[$Ctrltotalfeeamt];  

          $feediffamt = $_REQUEST[$Ctrlfeediffamt];  
          $Quarter = $_REQUEST[$Ctrlquarter];  
          $SelectedMonth = $_REQUEST[$Ctrlmonth];  
          $LateFee = $_REQUEST[$CtrlLateFee];  
          $BounceCharges = $_REQUEST[$CtrlBounceCharges];  
          $FeeAmountWithoutLateFee = $_REQUEST[$CtrlInstallmentWithoutLateFee];  
        
          // $Month .= $SelectedMonth.",";
         

          $totalLateFee += $LateFee;
          $totalBounceCharges += $BounceCharges;

    $sstr="select * from `fees` where `sadmission`='$AdmissionNo' and `quarter`='$Quarter' and `FeeMonth`='$SelectedMonth' and `FinancialYear`='$FinancialYear'  and `cheque_status` !='Bounce'";
	$rs = mysqli_query($Con, $sstr);
		if (mysqli_num_rows($rs) == 0)
		{
						
						$currentDateTime=date("Y-m-d h:i:sa");
				
				$ssql="INSERT INTO `fees_temp` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`PayableAfterDiscount`,`amountpaid`,`BalanceAmt`,`PreviousBalance`,`quarter`,`date`,`datetime`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`Remarks`,`FeesType`,`TxnId`,`cheque_bounce_amt`,`TxnAmount`,`FeeMonth`,`CommonTxnId`) VALUES('$AdmissionNo','$Name','$Class','$RollNo','$feediffamt','$feediffamt','$totalfeeamt','$BalanceAmount','$PreviousBalance','$Quarter','$currentdate','$currentdatetime','$FinancialYear','Paid','$NewReciptNo','$totalfeeamt','$PDFFileName','$PaymentMode','$LateFee','$TotalDelayDays','$LateFee','$Remarks','Regular','$monthtxnid','$BounceCharges','$totalfeeamt','$SelectedMonth','$txnid')";
			
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));


				$rsPreviousQuaterPaid=mysqli_query($Con, "select `quarter`,`BalanceAmt`,`receipt` from `fees` where `sadmission`='$AdmissionNo' and `cheque_status`!='Bounce' and `FinancialYear`='$FinancialYear' order by `datetime` desc");
				$rowB=mysqli_fetch_row($rsPreviousQuaterPaid);
				$PreviousQuartePaid=$rowB[0];
				$PreviousBalance=$rowB[1];
				$LastPaymentReceiptNo=$rowB[2];

				$totalPreviousBalance += $PreviousBalance;


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
					
					$MonthName = array(1 => 'April',2 => 'May',3 => 'June',4 => 'July',5 => 'August',6 =>'September',7 =>'October',8 =>'November',9 =>'December',10 => 'January',11 => 'February',12 => 'March' );
                    

			    	for ($j=1; $j <= count($MonthName) ; $j++) { 
			    		
			    		if ($MonthName[$j] == $SelectedMonth) 
			    		{
			    			$prevMonth = '';
			        		$prevMonth = $MonthName[$j-1];
			    		}
			    		
			    	}
			    	
					$ssql="select `Balance` from `fees_transaction` where `feeshead`='$feeshead' and `ReceiptNo`='$LastPaymentReceiptNo' and `cheque_status`!='Bounce' and `FeeMonth`='$prevMonth'";
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

					$totalFeeHead += $FeeHead;
			
					$FeeHeadOrigAmount=$OriginalAmount+$FeeHeadPreviousBalance;

					$totalFeeHeadOrigAmount += $FeeHeadOrigAmount;
			
					$FeeHeadDiscountAmt=$DiscountAmount;

					$totalFeeHeadDiscountAmt += $FeeHeadDiscountAmt;
			
					$FeeHeadAdhocConcession=0;

					$totalFeeHeadAdhocConcession += $FeeHeadAdhocConcession;

			
					$FeeHeadAfterDiscountValue=$amount;
					
					$totalFeeHeadAfterDiscountValue += $FeeHeadAfterDiscountValue;
			        
					$HeadActualPaidAfterDiscountAmount=$amount;

					$totalHeadActualPaidAfterDiscountAmount += $HeadActualPaidAfterDiscountAmount;
			
					$HeadWiseBalanceAmount=0;

					$totalHeadWiseBalanceAmount += $HeadWiseBalanceAmount;
			
					$ssql1="INSERT INTO `fees_transaction_temp` (`sadmission`,`feeshead`,`headamount`,`DiscountAmount`,`AdhocDiscountAmt`,`finalamount`,`PaidAmount`,`TxnAmount`,`Balance`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`PreviousBalance`,`Remarks`,`ChequeNo`,`bankname`,`FinancialYear`,`ChequeDate`,`cheque_status`,`PaymentMode`,`sname`,`quarter`,`cheque_bounce_amt`,`TxnId`,`TxnStatus`,`FeeMonth`,`FeeYear`,`CommonTxnId`) VALUES ('$AdmissionNo','$FeeHead','$FeeHeadOrigAmount','$FeeHeadDiscountAmt','$FeeHeadAdhocConcession','$FeeHeadAfterDiscountValue','$HeadActualPaidAfterDiscountAmount','$HeadActualPaidAfterDiscountAmount','$HeadWiseBalanceAmount','$NewReciptNo','$currentdate','$LateFee','$TotalDelayDays','$LateFee','$AdjustedLateDays','$PreviousBalance','$Remarks','$ChequeNo','$BankName','$FinancialYear','$ChequeDate','$CheckStatus','$PaymentMode','$Name','$Quarter','$BounceCharges','$monthtxnid','Pending','$SelectedMonth','$FinancialYear','$txnid')";
					
					mysqli_query($Con, $ssql1) or die(mysqli_error($Con));

				}//End of While Loop

		
		$SubmitStatus="successfull";


		}
      
		
		} //iteraion loop

 // $Month = trim($Month,',');

	// $ssqlTtl="INSERT INTO `fees_temp` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`PayableAfterDiscount`,`amountpaid`,`BalanceAmt`,`PreviousBalance`,`quarter`,`date`,`datetime`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`Remarks`,`FeesType`,`TxnId`,`cheque_bounce_amt`,`TxnAmount`,`FeeMonth`,`CommonTxnId`) VALUES('$AdmissionNo','$Name','$Class','$RollNo','$FeeAmountWithLateFee','$OrderAmount','$OrderAmount','$BalanceAmount','','$Quarter','$currentdate','$currentdatetime','$FinancialYear','Paid','$NewReciptNo','$OrderAmount','$PDFFileName','$PaymentMode','$totalLateFee','$TotalDelayDays','$totalLateFee','$Remarks','Regular','','$totalBounceCharges','$OrderAmount','$Month','$txnid')";
			
 //    mysqli_query($Con, $ssqlTtl) or die(mysqli_error($Con));

    
	// $ssql1Ttl="INSERT INTO `fees_transaction_temp` (`sadmission`,`feeshead`,`headamount`,`DiscountAmount`,`AdhocDiscountAmt`,`finalamount`,`PaidAmount`,`TxnAmount`,`Balance`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`PreviousBalance`,`Remarks`,`ChequeNo`,`bankname`,`FinancialYear`,`ChequeDate`,`cheque_status`,`PaymentMode`,`sname`,`quarter`,`cheque_bounce_amt`,`TxnId`,`TxnStatus`,`FeeMonth`,`FeeYear`) VALUES ('$AdmissionNo','$FeeHead','$totalFeeHeadOrigAmount','$totalFeeHeadDiscountAmt','$totalFeeHeadAdhocConcession','$totalFeeHeadAfterDiscountValue','$totalHeadActualPaidAfterDiscountAmount','$totalHeadActualPaidAfterDiscountAmount','$totalHeadWiseBalanceAmount','$NewReciptNo','$currentdate','$totalLateFee','$TotalDelayDays','$totalLateFee','$AdjustedLateDays','$totalPreviousBalance','$Remarks','$ChequeNo','$BankName','$FinancialYear','$ChequeDate','$CheckStatus','$PaymentMode','$Name','$Quarter','$totalBounceCharges','','Pending','$Month','$FinancialYear')";
	// mysqli_query($Con, $ssql1Ttl) or die(mysqli_error($Con));




	}  // if condition


// } //function
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
        <input type="hidden" name="surl" value="http://nkbpsis.in/Users/success_monthly_common_monthly.php"/> <!-- Success notification -->
	  <input type="hidden" name="furl" value="http://nkbpsis.in/Users/success_monthly_common_monthly.php"/> <!-- Failure notification -->
	  
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