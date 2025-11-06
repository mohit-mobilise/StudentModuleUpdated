<?php 
include '../connection.php';
include '../AppConf.php';
session_start();
?>

<?php

       
	$sessionId=session_id();
	$_SESSION['sess_id']=$sessionId;
	//-----------documents--------------------------
	
		//$merchantTxnId = uniqid();
		$merchantTxnId = $_REQUEST["merchantTxnId"];
		mysqli_query($Con, "delete from fees_temp where TxnId='$merchantTxnId'");
		mysqli_query($Con, "delete from fees_transaction_temp where TxnId='$merchantTxnId'");
	
		$rsFY=mysqli_query($Con, "select `year` from `FYmaster` where `Status`='Active'");
				$rowFY=mysqli_fetch_row($rsFY);
				$FinancialYear = $rowFY[0];


	$AdmissionNo=$_REQUEST["hadmission"];
		
		$Quarter=$_REQUEST["hQuarter"];
		$LateFee=$_REQUEST["hLateFee"];
		$BounceCharges=$_REQUEST["hBounceCharges"];
		$OrderAmount=$_REQUEST["TotalFeeAmount"];
		//$OrderAmount=1;
		$FeeAmountWithoutLateFee=$_REQUEST["FeeAmountWithoutLateFee"];
		$FeeAmountWithLateFee=$_REQUEST["FeeAmountWithLateFee"];
		$TotalDelayDays=$_REQUEST["LateDays"];
	

				$rsFY=mysqli_query($Con, "select `year` from `FYmaster` where `Status`='Active'");
				$rowFY=mysqli_fetch_row($rsFY);
				$FinancialYear = $rowFY[0];


		

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
			break;
		}
		$LastName="NA";
		
		
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
			$PhoneNo=$rows[3];
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
			$PhoneNo=$rows[3];
			$LogoURL=$rows[4];
			$AccountNo=$rows[5];
			$AffiliationNo=$rows[6];
			$SchoolNo=$rows[7];
			$website=$rows[8];
			break;
		}

		$AdmissionNo=$_REQUEST["hadmission"];
			$Quarter=$_REQUEST["hQuarter"];
			$LateFee=$_REQUEST["hLateFee"];
			$BounceCharges=$_REQUEST["hBounceCharges"];
			$OrderAmount=$_REQUEST["TotalFeeAmount"];
			//$OrderAmount=1;
			$FeeAmountWithoutLateFee=$_REQUEST["FeeAmountWithoutLateFee"];
			$FeeAmountWithLateFee=$_REQUEST["FeeAmountWithLateFee"];
			$TotalDelayDays=$_REQUEST["LateDays"];
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
			$FatherName=$row[0];
			$Name=$row[1];
			$Class=$row[2];
			$RollNo=$row[3];
			$MotherName=$row[4];
			$Address=$row[5];
			$Email=$row[6];
			$Phoneno=$row[7];
			break;
		}
		$LastName="NA";


		
		
			
			//$AdmissionNo=$_REQUEST["sadmisison"];
				
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
					
				$PaymentMode="Online";
			
				$currentdate=date("Y-m-d");
				
			
				$NewReciptNo="";
			
				
				/*$sstr="select * from `fees` where `sadmission`='$AdmissionNo' and `quarter`='$Quarter' and `FinancialYear`='$FinancialYear'  and `cheque_status` !='Bounce' ";
				$rs = mysqli_query($Con, $sstr);
					if (mysqli_num_rows($rs)> 0)
					{
							$sstr="select BalanceAmt from `fees` where `sadmission`='$AdmissionNo' and `quarter`='$Quarter' and `FinancialYear`='$FinancialYear'  and `cheque_status` !='Bounce' ORDER BY datetime DESC";
							$rs = mysqli_query($Con, $sstr);
							$Balance=$mysqli_fetch_row($rs);
							$BalanceAmt=$Balance[0];
							if($BalanceAmt=0)
							{
							echo "<br><br><center><b>Fee already submitted for Admission Id:" . $AdmissionNo . ",Quarter:" . $Quarter . ",Financial Year:" . $FinancialYear;
							exit();
							}
							else
							{
						
							
							}
					}*/
				
				
				$currentDateTime=date("Y-m-d h:i:sa");
				
				$ssql="INSERT INTO `fees_temp` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`datetime`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`Remarks`,`FeesType`,`TxnId`,`cheque_bounce_amt`) VALUES('$AdmissionNo','$Name','$Class','$RollNo','$OrderAmount','$OrderAmount','$BalanceAmount','$Quarter','$currentdate','$currentdatetime','$FinancialYear','Paid','$NewReciptNo','$OrderAmount','$PDFFileName','$PaymentMode','$LateFee','$TotalDelayDays','$LateFee','$Remarks','Regular','$merchantTxnId','$BounceCharges')";
			
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));
			
				
					
				//$TotalFeeHead=$_REQUEST["hTotalFeeHead"];
			
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
					
					$rsFee=mysqli_query($Con, "select distinct `feeshead`,sum(`head_original_amount`),sum(`head_concession_amount`),sum(`amount`) from `fees_student` where `sadmission`='$AdmissionNo' and `feeshead`='$feeshead' and `Quarter` ='$Quarter' and `financialyear`='$FinancialYear' group by `feeshead`");
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
			
					$FeeHeadOrigAmount=$OriginalAmount;
			
					$FeeHeadDiscountAmt=$DiscountAmount;
			
					$FeeHeadAdhocConcession=0;
			
					$FeeHeadAfterDiscountValue=$amount;
			
					$HeadActualPaidAfterDiscountAmount=$amount;
			
					$HeadWiseBalanceAmount=0;
			
					$ssql1="INSERT INTO `fees_transaction_temp` (`sadmission`,`feeshead`,`headamount`,`DiscountAmount`,`AdhocDiscountAmt`,`finalamount`,`PaidAmount`,`TxnAmount`,`Balance`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`PreviousBalance`,`Remarks`,`ChequeNo`,`bankname`,`FinancialYear`,`ChequeDate`,`cheque_status`,`PaymentMode`,`sname`,`quarter`,`cheque_bounce_amt`,`TxnId`,`TxnStatus`,`FeeMonth`,`FeeYear`) VALUES ('$AdmissionNo','$FeeHead','$FeeHeadOrigAmount','$FeeHeadDiscountAmt','$FeeHeadAdhocConcession','$FeeHeadAfterDiscountValue','$HeadActualPaidAfterDiscountAmount','$HeadActualPaidAfterDiscountAmount','$HeadWiseBalanceAmount','$NewReciptNo','$currentdate','$LateFee','$TotalDelayDays','$AdjustedLateFee','$AdjustedLateDays','$PreviousBalance','$Remarks','$ChequeNo','$BankName','$FinancialYear','$ChequeDate','$CheckStatus','$PaymentMode','$Name','$Quarter','$BounceCharges','$merchantTxnId','Pending','$QuarterString','$FinancialYear')";
					
					mysqli_query($Con, $ssql1) or die(mysqli_error($Con));

				}//End of While Loop
				


	
		$SubmitStatus="successfull";
		//echo $SubmitStatus;
		//exit();
    
    
    /*-----------------------GENERATE PDF CODE AND SEND--------------------------------*/
    //$insertData= mysqli_query($Con, $sql);
    
    $PREFIX="R/2018/";
   	$_SESSION['sess_id']=$sessionId;
    
?>
