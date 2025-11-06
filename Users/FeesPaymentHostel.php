<?php
session_start();
include '../connection.php';
include '../AppConf.php';
?>
<?php
$ssqlClass="SELECT distinct `class` FROM `class_master`";
$rsClass= mysqli_query($Con, $ssqlClass);

$ssqlFY="SELECT distinct `year`,`financialyear` FROM `FYmaster`";
$rsFY= mysqli_query($Con, $ssqlFY);

$ssqlCFY="SELECT distinct `year`,`financialyear` FROM `FYmaster` where `Status`='Active'";
$rsCFY= mysqli_query($Con, $ssqlCFY);

	while($row = mysqli_fetch_row($rsCFY))
	{
		$CurrentFinancialYear = $row[0];
		$CurrentFinancialYearName=$row[1];					
	}

$sstr="SELECT distinct `head` FROM `discounttable` where `discounttype`='tuitionfees'";
$rsDiscount= mysqli_query($Con, $sstr);

$ssql="select distinct `bank_name` from `bank_master` where status='1'";
$rsBank	= mysqli_query($Con, $ssql);

if ($_REQUEST["txtAdmissionNo"] != "")
{
		$AdmissionNo=$_REQUEST["txtAdmissionNo"];
		$sqlStudentDetail = "SELECT `sname` , `sclass` , `srollno`,`FinancialYear`,`previous_sclass`,`Hostel`,`DiscontType` FROM `student_master` where  `sadmission`='$AdmissionNo'";
		
		
		$rs = mysqli_query($Con, $sqlStudentDetail);
		if (mysqli_num_rows($rs) > 0)
		{
			while($row = mysqli_fetch_row($rs))
					{
						$sname=$row[0];
						$class=$row[1];					
						$RollNo=$row[2];
						$FinancialYear=$row[3];
						$previous_sclass=$row[4];
						$Hostel=$row[5];
						$StudentDiscountType=$row[6];
					}
			if ($FinancialYear == "")
			{$FinancialYear="2014";}
		}
		else
		{
			$sqlStudentDtl = "SELECT `sname` , `sclass` , `srollno`,`FinancialYear` FROM `NewStudentAdmission` where  `sadmission`='$AdmissionNo'";
			$rs1 = mysqli_query($Con, $sqlStudentDtl);
			if (mysqli_num_rows($rs1) > 0)
			{
				while($row = mysqli_fetch_row($rs1))
						{
							$sname=$row[0];
							$class=$row[1];					
							$RollNo=$row[2];
							$FinancialYear=$row[3];					
						}	
			}
		}
		
		if($Hostel !="Yes")
		{
			echo "<br><br><center><b>Student is not eligible for hostel fees!";
			exit();
		}
		
	$ssqlClass="SELECT distinct `MasterClass` FROM `class_master` where `class`='$class'";
	
	$rsMClass= mysqli_query($Con, $ssqlClass);
	while($rowM=mysqli_fetch_row($rsMClass))
	{
		$MasterClass=$rowM[0];
		break;
	}

		
		$StudentAdmissionFinancialYear=$FinancialYear;
		
		$sqlFy = "SELECT `financialyear` FROM `FYmaster` where  `year`='$FinancialYear'";
		
		$rs1 = mysqli_query($Con, $sqlFy);
		if (mysqli_num_rows($rs1) > 0)
		{
			while($row = mysqli_fetch_row($rs1))
					{
						$FinancialYearName=$row[0];
					}
		}
		
		
		//**FEE TRANSACTION HISTORY FOR CURRENT FINANCIAL YEAR*****
			
			If ($StudentAdmissionFinancialYear < $CurrentFinancialYear)
			{
				$StudentType="Old Student";
				$QryStudentType="old";
			}
			else
			{
				$StudentType="New Student";
				$QryStudentType="new";
			}
			
		
		$sqlQ1 = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime` FROM `fees` where  `sadmission`='$AdmissionNo' and `quarter`='Q1' and `FinancialYear`='$CurrentFinancialYear' and `FeesType`='Hostel'";
		$rsQ1 = mysqli_query($Con, $sqlQ1);
		while($row = mysqli_fetch_row($rsQ1))
				{
					$Q1fees_amount=$row[0];
					$Q1amountpaid=$row[1];					
					$Q1quarter=$row[2];	
					$Q1status=$row[3];
					$Q1Receipt=$row[4];		
					$Q1DateTime=$row[5];		
				}
				
		$sqlQ2 = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime` FROM `fees` where  `sadmission`='$AdmissionNo' and `quarter`='Q2'  and `FinancialYear`='$CurrentFinancialYear' and `FeesType`='Hostel'";
		$rsQ2 = mysqli_query($Con, $sqlQ2);
		while($row = mysqli_fetch_row($rsQ2))
				{
					$Q2fees_amount=$row[0];
					$Q2amountpaid=$row[1];					
					$Q2quarter=$row[2];	
					$Q2status=$row[3];		
					$Q2Receipt=$row[4];		
					$Q2DateTime=$row[5];		
				}
		
		$sqlQ3 = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime` FROM `fees` where  `sadmission`='$AdmissionNo' and `quarter`='Q3'  and `FinancialYear`='$CurrentFinancialYear' and `FeesType`='Hostel'";
		$rsQ3 = mysqli_query($Con, $sqlQ3);
		while($row = mysqli_fetch_row($rsQ3))
				{
					$Q3fees_amount=$row[0];
					$Q3amountpaid=$row[1];					
					$Q3quarter=$row[2];	
					$Q3status=$row[3];		
					$Q3Receipt=$row[4];		
					$Q3DateTime=$row[5];		
				}
		
		$sqlQ4 = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime` FROM `fees` where  `sadmission`='$AdmissionNo' and `quarter`='Q4'  and `FinancialYear`='$CurrentFinancialYear' and `FeesType`='Hostel'";
		$rsQ4 = mysqli_query($Con, $sqlQ4);
		while($row = mysqli_fetch_row($rsQ4))
				{
					$Q4fees_amount=$row[0];
					$Q4amountpaid=$row[1];					
					$Q4quarter=$row[2];	
					$Q4status=$row[3];		
					$Q4Receipt=$row[4];		
					$Q4DateTime=$row[5];		
				}

//------------------Check the Student is submitted fee for first time-------------------
	$ssql="select * from `fees` where `sadmission`='$AdmissionNo'";
	$rsChk = mysqli_query($Con, $ssql);
	if (mysqli_num_rows($rsChk) == 0)
	{
		$FirstTimeFeePaying = "yes";
	}
	else
	{$FirstTimeFeePaying = "no";}
//-------------------
	

			$sql = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime`,`FinancialYear`,`BalanceAmt` FROM `fees` where  `sadmission`='$AdmissionNo' and `FinancialYear`='$CurrentFinancialYear' and `FeesType`='Hostel' ORDER BY `quarter` DESC";
			
			$rs1 = mysqli_query($Con, $sql);
			
			
		//If Student is Regular ant not paid in current financial year********
			$SearchFeeInLastYear="no";
			if (mysqli_num_rows($rs1) == 0 && $StudentType == "Old Student")
			{
				
							$SearchFeeInLastYear="yes";
							//COMMENTED BECAUSE LAST YEAR FEE HAVE BEEN ADJUSTED
							//$FeeSubmissionFinancialYear=$CurrentFinancialYear-1;
							$sql = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime`,`FinancialYear`,`BalanceAmt` FROM `fees` where  `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType`='Hostel' ORDER BY `quarter` DESC";				
							$rs = mysqli_query($Con, $sql);
							if (mysqli_num_rows($rs) > 0)
							{
								while($row = mysqli_fetch_row($rs))
								{
									$LastPaymentReceiptNo = $row[4];
									$LastBalanceAmount = $row[7];
									$LastFeeSubmitQuarter = $row[2];
									
									if($LastFeeSubmitQuarter=="Q1")
									{
										$FeeSubmissionQuarter="Q2";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q2")
									{
										$FeeSubmissionQuarter="Q3";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q3")
									{
										$FeeSubmissionQuarter="Q4";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q4")
									{
										$FeeSubmissionQuarter="Q1";
										$FeeSubmissionFinancialYear=$CurrentFinancialYear;
										$SearchFeeInLastYear="no";
									}
									break;
								}
							}
							else
							{
								$FeeSubmissionQuarter="Q1";
								$FeeSubmissionFinancialYear=$CurrentFinancialYear;
							}
			}
			elseif (mysqli_num_rows($rs1) > 0 && $StudentType == "Old Student")
			{
				//Student is Regular and paid in current financialyear ******
				while($row = mysqli_fetch_row($rs1))
					{
						$LastPaymentReceiptNo = $row[4];
						$LastBalanceAmount = $row[7];
						$LastFeeSubmitQuarter = $row[2];
						
						if($LastFeeSubmitQuarter=="Q1")
						{
							$FeeSubmissionQuarter="Q2";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q2")
						{
							$FeeSubmissionQuarter="Q3";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q3")
						{
							$FeeSubmissionQuarter="Q4";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q4")
						{
							$FeeSubmissionFinancialYear=$CurrentFinancialYear+1;
							
							$sql = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime`,`FinancialYear`,`BalanceAmt` FROM `fees` where  `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType`='Hostel' ORDER BY `quarter` DESC";				
							$rs = mysqli_query($Con, $sql);
							if (mysqli_num_rows($rs) > 0)
							{
								while($row = mysqli_fetch_row($rs))
								{
									$LastPaymentReceiptNo = $row[4];
									$LastBalanceAmount = $row[7];
									$LastFeeSubmitQuarter = $row[2];
									
									if($LastFeeSubmitQuarter=="Q1")
									{
										$FeeSubmissionQuarter="Q2";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q2")
									{
										$FeeSubmissionQuarter="Q3";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q3")
									{
										$FeeSubmissionQuarter="Q4";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									break;
								}
							}
							else
							{						
								$FeeSubmissionQuarter="Q1";
							}
							
						}
						else
						{}
						break;
					}								
			}
			elseif (mysqli_num_rows($rs1) == 0 && $StudentType == "New Student")
			{
				//Student is New Admission and not paid in current financialyear ******
						$LastPaymentReceiptNo = "";
						$LastBalanceAmount = 0;
						$LastFeeSubmitQuarter = "";
						$FeeSubmissionQuarter="Q1";
						$FeeSubmissionFinancialYear=$CurrentFinancialYear;
				//Check Balance in Admission Fees********
							$sql = "SELECT `BalanceAmt` FROM `AdmissionFees` where  `sadmission`='$AdmissionNo'";				
							$rsBalance = mysqli_query($Con, $sql);
							if (mysqli_num_rows($rsBalance) > 0)
							{
								while($row = mysqli_fetch_row($rsBalance))
								{
									$LastBalanceAmount=$row[0];
									break;
								}
							}
						
			}
			elseif (mysqli_num_rows($rs1) > 0 && $StudentType == "New Student")
			{
				//Student is New Admission and paid in current financialyear ******
					while($row = mysqli_fetch_row($rs1))
					{
						$LastPaymentReceiptNo = $row[4];
						$LastBalanceAmount = $row[7];
						$LastFeeSubmitQuarter = $row[2];
						
						if($LastFeeSubmitQuarter=="Q1")
						{
							$FeeSubmissionQuarter="Q2";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q2")
						{
							$FeeSubmissionQuarter="Q3";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q3")
						{
							$FeeSubmissionQuarter="Q4";
							$FeeSubmissionFinancialYear=$CurrentFinancialYear;
						}
						elseif ($LastFeeSubmitQuarter=="Q4")
						{
							$FeeSubmissionFinancialYear=$CurrentFinancialYear+1;
							$sql = "SELECT `fees_amount` , `amountpaid` , `quarter`,`status`,`receipt`,`datetime`,`FinancialYear`,`BalanceAmt` FROM `fees` where  `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType`='Hostel' ORDER BY `quarter` DESC";				
							$rs = mysqli_query($Con, $sql);
							if (mysqli_num_rows($rs) > 0)
							{
								while($row = mysqli_fetch_row($rs))
								{
									$LastPaymentReceiptNo = $row[4];
									$LastBalanceAmount = $row[7];
									$LastFeeSubmitQuarter = $row[2];
									
									if($LastFeeSubmitQuarter=="Q1")
									{
										$FeeSubmissionQuarter="Q2";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q2")
									{
										$FeeSubmissionQuarter="Q3";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									elseif ($LastFeeSubmitQuarter=="Q3")
									{
										$FeeSubmissionQuarter="Q4";
										//$FeeSubmissionFinancialYear=$CurrentFinancialYear;
									}
									break;
								}
							}
							else
							{
								$FeeSubmissionQuarter="Q1";
								$FeeSubmissionFinancialYear=$CurrentFinancialYear;
								
							}
							
							
						}
						else
						{}
						break;
					}
			}
		
		//Calculate the total balance amount paid from fee_transaction
			if ($LastPaymentReceiptNo !="")
			{
				$ssql="select sum(`PaidBalanceAmt`) from `fees_transaction` where `ReceiptNo`='$LastPaymentReceiptNo'";
				$rs2= mysqli_query($Con, $ssql);
				if (mysqli_num_rows($rs2) > 0)
				{
					while($row = mysqli_fetch_row($rs2))
					{
						$TotalPaidBalanceAmt = $row[0];
						break;
					}
				}
				else
				{
					$TotalPaidBalanceAmt = 0;
				}
			}
			if ($LastBalanceAmount != 0)
			{
				$LastBalanceAmount = $LastBalanceAmount - $TotalPaidBalanceAmt;
			}
		//--------	
		
		if ($StudentType=="New Student")
		{
			//$ssql="select sum(`amount`) from `fees_master_Q4` where `class`='$MasterClass' and `financialyear`='$FeeSubmissionFinancialYear' and feeshead !='hostelfees'";
			$ssql="select sum(`amount`) from `fees_student` where `sadmission`='$AdmissionNo' and `financialyear`='$FeeSubmissionFinancialYear' and `FeesType` ='Hostel'";
		}
		else
		{
			//$ssql="select sum(`amount`) from `fees_master_Q4` where `class`='$MasterClass' and `financialyear`='$FeeSubmissionFinancialYear' and feeshead !='hostelfees' and `StudentType`='old'";
			$ssql="select sum(`amount`) from `fees_student` where `sadmission`='$AdmissionNo' and `financialyear`='$FeeSubmissionFinancialYear' and `FeesType` ='Hostel'";
		}
		
		$rsYearlyAmount=mysqli_query($Con, $ssql);
		while($rowA=mysqli_fetch_row($rsYearlyAmount))
		{
			$YearlyTotalFee=$rowA[0];
			break;
		}
		//total paid amount
		$ssql = "SELECT sum(`TutionFee`) FROM `fees_transaction` where `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear'";
		$rsS = mysqli_query($Con, $ssql);
		while($rowS=mysqli_fetch_row($rsS))
		{
			$FeePaidAmount=$rowS[0];
			break;
		}
	
			
			//------------------------Tution Fee,Transport Fee,Annual Charges--------------------------------------------------------
		$rsAdvanceAmt=mysqli_query($Con, "sum(`amount`) FROM `fees_student` where `sadmission`='$AdmissionNo' and `financialyear`='$FeeSubmissionFinancialYear'  and `feeshead`='Advances'");
		while($rowAdvance=mysqli_fetch_row($rsAdvanceAmt))
		{
			$FeePaidAdvance=$rowAdvance[0];
			break;
		}
		
		$ssql="";
		if($SearchFeeInLastYear=="yes")
		{
			if($FeeSubmissionQuarter !="Q4")
			{
				$ssql = "SELECT distinct `amount` FROM `fees_master` where `class`='$previous_sclass' and `quarter`='$FeeSubmissionQuarter' and `feeshead`='tuitionfees' and `financialyear`='$FeeSubmissionFinancialYear' and `StudentType`='$QryStudentType'";
			}
			else
			{
				$TutionFee=$YearlyTotalFee-$FeePaidAmount;
			}	
		}
		else
		{
			if($FeeSubmissionQuarter !="Q4")
			{
				$ssql = "SELECT distinct `amount` FROM `fees_master` where `class`='$MasterClass' and `quarter`='$FeeSubmissionQuarter' and `feeshead`='tuitionfees' and `financialyear`='$FeeSubmissionFinancialYear' and `StudentType`='$QryStudentType'";
				
			}
			else
			{
				$TutionFee=$YearlyTotalFee-$FeePaidAmount-$FeePaidAdvance;
			}	
		
		}			
		
		
		
		if($ssql !="")
		{
			$rstution = mysqli_query($Con, $ssql);
			while($row = mysqli_fetch_row($rstution))
			{
				$TutionFee=$row[0];							
			}
			
				if(($YearlyTotalFee-$FeePaidAmount-$FeePaidAdvance) < $TutionFee)
				{
					$TutionFee=($YearlyTotalFee-$FeePaidAmount-$FeePaidAdvance);
				}					
		}

		
		//Hostel Fee Calculation
		$HostelCharge=0;
		//////HOSTEL CHARGE IS COMMENTED DUE TO IT IS SEPERATLY SUBMITTED
		 
		 if ($Hostel=="Yes")
		 {
			if($FeeSubmissionQuarter =="Q1" || $FeeSubmissionQuarter =="Q2" || $FeeSubmissionQuarter =="Q3")
			{
				$rsTotalHostelFee=mysqli_query($Con, "select sum(`amount`) From `fees_student` where `FeesType`='Hostel' and `sadmission`='$AdmissionNo'");
				while($row = mysqli_fetch_row($rsTotalHostelFee))
				{
					$YearlyTotalHostelAmt=$row[0];
					break;
				}
				
				$rsHostelPaidAmt=mysqli_query($Con, "select sum(`amountpaid`) from `fees` where `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType` ='Hostel'");
				while($row = mysqli_fetch_row($rsHostelPaidAmt))
				{
					$PaidHostelAmount=$row[0];
					break;
				}
				$rsHostelAdvance=mysqli_query($Con, "select `amount` from `fees_student` where `feeshead`='HOSTEL AMOUNT CREDIT' and `sadmission`='$AdmissionNo'");
				while($rowH = mysqli_fetch_row($rsHostelAdvance))
				{
					$HostelAmountAdvance=$rowH[0];
					break;
				}
				$PaidHostelAmount=$PaidHostelAmount+$HostelAmountAdvance;
				//echo $YearlyTotalHostelAmt."/".$PaidHostelAmount."/".$HostelAmountAdvance;
				//exit();
				
				$rsHostelFee= mysqli_query($Con, "select `amount` From `fees_master` where `feeshead`='hostelfees' and `quarter`='$FeeSubmissionQuarter'");
				while($row = mysqli_fetch_row($rsHostelFee))
				{
					$HostelCharge=$row[0];
					break;
				}
				if($HostelCharge>($YearlyTotalHostelAmt-$PaidHostelAmount))
				{
					$HostelCharge=($YearlyTotalHostelAmt-$PaidHostelAmount);
				}
				if(($YearlyTotalHostelAmt-$PaidHostelAmount) < 0)
				{
					$HostelCharge=0;
				}
				
			}
			if($FeeSubmissionQuarter =="Q4")
			{
				$rsTotalHostelFee=mysqli_query($Con, "select sum(`amount`) From `fees_student` where `FeesType`='Hostel' and `sadmission`='$AdmissionNo'");
				while($row = mysqli_fetch_row($rsTotalHostelFee))
				{
					$YearlyTotalHostelAmt=$row[0];
					break;
				}
				$rsHostelPaidAmt=mysqli_query($Con, "select sum(`amountpaid`)-sum(`ActualLateFee`) from `fees` where `sadmission`='$AdmissionNo' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
				while($row = mysqli_fetch_row($rsHostelPaidAmt))
				{
					$PaidHostelAmount=$row[0];
					break;
				}
				$rsHostelAdvance=mysqli_query($Con, "select `amount` from `fees_student` where `feeshead`='HOSTEL AMOUNT CREDIT' and `sadmission`='$AdmissionNo'");
				while($rowH = mysqli_fetch_row($rsHostelAdvance))
				{
					$HostelAmountAdvance=$rowH[0];
					break;
				}
				$PaidHostelAmount=$PaidHostelAmount+$HostelAmountAdvance;
				$HostelCharge=$YearlyTotalHostelAmt-$PaidHostelAmount;
				//echo $YearlyTotalHostelAmt."/".$PaidHostelAmount;
				//exit();
				$ssql="select y.*,(`YearlyHostelPayable`-`HostelFeePaid`-`AdjustedAmtInHostelQ4`) as `HostelPayableInQ4` from
				(
				select x.`sadmission`,x.`YearlyPayable`,x.`YearlyRegularPayable`,x.`RegularFeePaid`,(x.`YearlyRegularPayable`-x.`RegularFeePaid`) as `RegularPayableInQ4`,x.`YearlyHostelPayable`,x.`HostelFeePaid`,x.`Advances`,x.`ConcessionAmt`,IF(`RegularFeePaid`>`YearlyRegularPayable`,(`RegularFeePaid`-`YearlyRegularPayable`),0) as `AdjustedAmtInHostelQ4` from
				(
				SELECT a.`sadmission`,a.`amount` as `YearlyPayable`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `FeesType`='Regular') as `YearlyRegularPayable`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `feeshead` in ('FEES FIRST INSTALLMENT','FEES SECOND INSTALLMENT','FEES THIRD INSTALLMENT')) as `RegularFeePaid`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `FeesType`='Hostel') as `YearlyHostelPayable`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `feeshead` in ('HOSTEL FIRST INSTALLMENT','HOSTEL SECOND INSTALLMENT','HOSTEL Third Installment')) as `HostelFeePaid`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `feeshead`='Advances') as `Advances`,
				(select sum(`amount`) from `fees_student` where `sadmission`=a.`sadmission` and `FeesType` !='Hostel' and `feeshead`='TOTAL CONCESSION AMOUNT') as `ConcessionAmt`
				FROM `fees_student` as `a` WHERE `sadmission`='$AdmissionNo' and `feeshead`='TOTAL BILL AMOUNT'
				) as `x`
				) as `y`";
				$rsQ3RegularBalanceAmt=mysqli_query($Con, $ssql);
				$rowR=mysqli_fetch_row($rsQ3RegularBalanceAmt);
				if($rowR[9] !="")
				$HostelCharge=$HostelCharge-$rowR[9];
				
			}
		}
		
		/////////
		
		
		if($SearchFeeInLastYear=="yes")
		{
			$ssql = "SELECT distinct `amount` FROM `fees_master` where `class`='$previous_sclass' and `feeshead`='annualcharges'  and `financialyear`='$FeeSubmissionFinancialYear'";
		}
		else
		{
			$ssql = "SELECT distinct `amount` FROM `fees_master` where `class`='$class' and `feeshead`='annualcharges'  and `financialyear`='$FeeSubmissionFinancialYear'";
		}	
		$rsAnnual = mysqli_query($Con, $ssql);

		while($row1 = mysqli_fetch_row($rsAnnual))
				{
					$AnnualFee = $row1[0];										
				}
		
		//ADDED ON 28-APR-2015*********************
		if($StudentType=="New Student" && $FeeSubmissionQuarter=="Q1")
		{
			$AnnualFee=0;
		}


		$ssql = "SELECT distinct `routecharges`,`routeno` FROM `RouteMaster` where `routeno`=(SELECT `routeno` FROM `student_master` WHERE `sadmission`='$AdmissionNo') and `financialyear`='$FeeSubmissionFinancialYear'";

		$rsTransport = mysqli_query($Con, $ssql);

		if (mysqli_num_rows($rsTransport) > 0)
		{
			while($row1 = mysqli_fetch_row($rsTransport))
				{
					$TransportFee=$row1[0];	
					$TransportFee = floor($TransportFee);
					$RoutNo=$row1[1];				
				}
		}
		else
		{
			$ssql = "SELECT distinct `routecharges`,`routeno` FROM `RouteMaster` where `routeno`=(SELECT `routeno` FROM `NewStudentAdmission` WHERE `sadmission`='$AdmissionNo') and `financialyear`='$FeeSubmissionFinancialYear'";

			$rsTransport1 = mysqli_query($Con, $ssql);

			while($row2 = mysqli_fetch_row($rsTransport1))
				{

					$TransportFee=$row2[0];	
					$TransportFee=floor($TransportFee);
					$RoutNo=$row2[1];				

				}
		}

//-----------------------End of Tuetion Fee,Annual Fee,Transport Fee-----------------------------------------------------			
//---------------------Late Fee Calculation----------------------------------------------------------



	$now = time(); // Current Date time
	if ($FeeSubmissionQuarter=="Q1")
	{
		$FeeSubmissionYear=$FeeSubmissionFinancialYear;
		$Dt1 = $FeeSubmissionYear . "-Apr-" . "18";
		$Dt1 = date('Y-m-d', strtotime($Dt1));
		
	}
	elseif ($FeeSubmissionQuarter=="Q2")
	{
		$FeeSubmissionYear=$FeeSubmissionFinancialYear;
		$Dt1 = $FeeSubmissionYear . "-Jul-" . "14";
		$Dt1 = date('Y-m-d', strtotime($Dt1));
	}
	elseif ($FeeSubmissionQuarter=="Q3")
	{
		$FeeSubmissionYear=$FeeSubmissionFinancialYear;
		//$Dt1 = $FeeSubmissionYear . "-Oct-" . "10";
		$Dt1 = $FeeSubmissionYear . "-Oct-" . "14";
		$Dt1 = date('Y-m-d', strtotime($Dt1));
	}
	elseif ($FeeSubmissionQuarter=="Q4")
	{
		$FeeSubmissionYear=$FeeSubmissionFinancialYear+1;
		$Dt1 = $FeeSubmissionYear . "-Jan-" . "14";
		$Dt1 = date('Y-m-d', strtotime($Dt1));
	}
	
	
	
	$your_date = strtotime($Dt1);
	$datediff = $now - $your_date;
	if ($datediff > 0)
		$LateDays = floor($datediff/(60*60*24));
	else
		$LateDays = 0;
	
	$LateFee = 0;
	if($LateDays > 0 && $LateDays <=10)
	{
		$LateFee = 100;
	}
	if($LateDays >10)
	{
		$LateFee = 500;
	}
	
	$TotalPaybleAmount=$HostelCharge+$LateFee+$LastBalanceAmount;
	
//---------------------End of Late Fee Calculation-------------------------------------------------------	
//Calculate Security Charges ans Lab Charges
	$SecurityFee=0;
	$LabFee=0;
	if ($StudentType=="Old Student")
	{
		$rsSecurity = mysqli_query($Con, "select `amount` from `fees_master` where `class`='$class' and `feeshead`='Security Fees'  and `financialyear`='$FeeSubmissionFinancialYear'");
		while($row = mysqli_fetch_row($rsSecurity))
				{
					$SecurityFee = $row[0];					
					break;
				}
				
		$rsLab = mysqli_query($Con, "select `amount` from `fees_master` where `class`='$class' and `feeshead`='Lab Charges'  and `financialyear`='$FeeSubmissionFinancialYear'");
		while($row = mysqli_fetch_row($rsLab))
				{
					$LabFee = $row[0];					
					break;
				}
	}
	
/////		

//-------------------- Previous Payment history----------------------------------------------------------
	$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear` FROM `fees` where `sadmission`='$AdmissionNo' and `FeesType`='Hostel' order by `quarter`,`FinancialYear` desc";
	$rs = mysqli_query($Con, $ssql);				
}
?>

<script language="javascript">
function Validate(SubmitType)
{
	
	if (document.getElementById("txtTotal").value =="")
	{
		alert("Toatal payable amount is blank !");
		return;
	}
	if (document.getElementById("txtTotalAmtPaying").value=="")
	{
		alert("Total Fee paid is mandatory!");
		return;
	}
	
	if(document.getElementById("cboPaymentMode").value=="Cheque")
	{
		if(document.getElementById("txtChequeDate").value=="" || document.getElementById("txtChequeNo").value=="" || document.getElementById("cboBank").value=="")
		{
			alert("In case of payment mode is cheque then cheque date,cheque no and bank name is mandatory!");
			return;
		}
	}
	
	document.getElementById("frmFees").action="FeesHostelSubmitUser.php";
	
	
	
	if (SubmitType=="Preview")
	{
		document.getElementById("frmFees").target = "_blank";
		document.getElementById("SubmitType").value="Preview";	
	}
	if (SubmitType=="Final")
	{
		//alert("Okey");
		document.getElementById("frmFees").target = "_self";
		document.getElementById("SubmitType").value="Final";
	}
	
	if(parseInt(document.getElementById("txtTotalAmtPaying").value) > parseInt(document.getElementById("txtTotal").value))
	{
		alert("Paid amount can not be more then payable amount! Plase check");
		return;
	}
	
	document.getElementById("frmFees").submit();
	
}

function Validate1()
{
	//alert("Hello");
	if (document.getElementById("txtAdmissionNo").value=="")
	{
		alert("Please enter student addmission id");
		return;
	}
	document.getElementById("frmFees").submit();
	
}

function GetFeeDetail()
{
	if (document.getElementById("cboFinancialYear").value == "Select One")
	{
		alert ("Plese select financial year!");
		document.getElementById("cboQuarter").value = "Select One";
		return;
	}
	//alert (document.getElementById("StudentAdmissionfinancialyear").value);
	//return;
	//document.getElementById("FeeSubmissionFinancialYear").value = document.getElementById("cboFinancialYear").vlaue;
	
	if (document.getElementById("cboQuarter").value =="Q1")
	{		
		if (document.getElementById("Q1Fee").value !="")
		{
			alert ("Fee for Quarter Q1 is already paid !");
			document.getElementById("cboQuarter").value="Select One";
			return;
		}
		//alert (document.getElementById("StudentAdmissionfinancialyear").value);
		//alert(document.getElementById("CurrentFinancialYear").value);
		//return;
		if (parseInt(document.getElementById("StudentAdmissionfinancialyear").value) <= parseInt(document.getElementById("CurrentFinancialYear").value))
		{
			//alert("Okey");
			document.getElementById("trAnnualFee").style.display = "";
		}
		
		
	}
	if (document.getElementById("cboQuarter").value =="Q2")
	{
		document.getElementById("txtAnnualFee").value="";
		document.getElementById("trAnnualFee").style.display = "none";
		
		if (document.getElementById("Q2Fee").value !="")
		{
			alert ("Fee for Quarter Q2 is already paid !");
			document.getElementById("cboQuarter").value="Select One";
			return;
		}
	}
	if (document.getElementById("cboQuarter").value =="Q3")
	{
		document.getElementById("txtAnnualFee").value="";
		document.getElementById("trAnnualFee").style.display = "none";
		
		if (document.getElementById("Q3Fee").value !="")
		{
			alert ("Fee for Quarter Q3 is already paid !");
			document.getElementById("cboQuarter").value="Select One";
			return;
		}
	}
	if (document.getElementById("cboQuarter").value =="Q4")
	{
		document.getElementById("txtAnnualFee").value="";
		document.getElementById("trAnnualFee").style.display = "none";
		
		if (document.getElementById("Q4Fee").value !="")
		{
			alert ("Fee for Quarter Q4 is already paid !");
			document.getElementById("cboQuarter").value="Select One";
			return;
		}
	}
	
try
		    {    
				// Firefox, Opera 8.0+, Safari    
				xmlHttp=new XMLHttpRequest();
			}
		  catch (e)
		    {    // Internet Explorer    
				try
			      {      
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				  }
			    catch (e)
			      {      
					  try
				        { 
							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
				      catch (e)
				        {        
							alert("Your browser does not support AJAX!");        
							return false;        
						}      
				  }    
			 } 
			 xmlHttp.onreadystatechange=function()
		      {
			      if(xmlHttp.readyState==4)
			        {
						var rows="";
			        	rows=new String(xmlHttp.responseText);
			        	//alert(rows);
			        	var arrStr=rows.split(",");
			        	var TutionFee=arrStr[0];
			        	var TransportFee=arrStr[1];
			        	var BalanceAmt=arrStr[2];
			        	var LateDays=arrStr[3];
			        	var RoutNo=arrStr[4];
			        	
			        	
			        	if (parseInt(document.getElementById("StudentAdmissionfinancialyear").value) <= parseInt(document.getElementById("CurrentFinancialYear").value))
						{document.getElementById("txtAnnualFee").value = arrStr[5];}
						else
						{document.getElementById("txtAnnualFee").value="";}
			        	
			        	document.getElementById("txtTuition").value=TutionFee;
			        	document.getElementById("txtTransportFees").value=TransportFee;
			        	document.getElementById("txtPreviousBalance").value=BalanceAmt;
			        	document.getElementById("txtLateDays").value =LateDays;
			        	document.getElementById("currentrouteno").value=RoutNo;
			        	document.getElementById("tdRouteNo").innerHTML ="<strong>Current Route: " + RoutNo + "</strong>";
			        	document.getElementById("tdChangeRoute").innerHTML ="<strong><a href='Javascript:ChangeRoute();'>Change Route</a></strong>";
			        	
			        	if (LateDays !="")
			        	{
			        		document.getElementById("txtLateFee").value=10*LateDays;
			        		document.getElementById("txtAdjustedLateFee").value=10*LateDays;
			        		
			        	}
			        	document.getElementById("txtAdjustedLateDays").value =LateDays;
			        	CalculatTotal();
			        	//alert("TutionFee:" + TutionFee + ",Transport Fee:" + TransportFee + ",Balance Amt:" + BalanceAmt);
			        	//document.getElementById("txtStudentName").value=rows;
			        	
			        	//ReloadWithSubject();
						//alert(rows);														
			        }
		      }
			
			var submiturl="GetFeeDetail.php?Quarter=" + document.getElementById("cboQuarter").value + "&Class=" + document.getElementById("txtClass").value + "&sadmission=" + document.getElementById("txtAdmissionNo").value + "&FinancialYear=" + document.getElementById("cboFinancialYear").value;

			xmlHttp.open("GET", submiturl,true);
			xmlHttp.send(null);

}

function CalculateLateFee()
{
	if(isNaN(document.getElementById("txtAdjustedLateDays").value)==true)
	{
		document.getElementById("txtAdjustedLateFee").value=0;
	}
	else
	{
		document.getElementById("txtAdjustedLateFee").value=10*document.getElementById("txtAdjustedLateDays").value;
	}
	CalculatTotal();
}

function CalculatTotal()
{
	
	if (isNaN(document.getElementById("txtTuition").value)== "true" || document.getElementById("txtTuition").value=="")
	{
		TutionFee=0;
	}
	else
	{
		TutionFee=document.getElementById("txtTuition").value;
	}
	
	/*
	if (isNaN(document.getElementById("txtHostel").value)==true || document.getElementById("txtHostel").value=="")
	{
		HostelFees=0;
	}
	else
	{
		HostelFees=document.getElementById("txtHostel").value;
	}
	
	if (isNaN(document.getElementById("txtTransportFees").value)==true || document.getElementById("txtTransportFees").value=="")
	{
		TransportFees=0;
	}
	else
	{
		TransportFees=document.getElementById("txtTransportFees").value;
	}
	*/
	if (isNaN(document.getElementById("txtAdjustedLateFee").value)==true || document.getElementById("txtAdjustedLateFee").value=="")
	{
		AdjustedLateFee=0;
	}
	else
	{
		AdjustedLateFee=document.getElementById("txtAdjustedLateFee").value;
	}
	if (isNaN(document.getElementById("txtPreviousBalance").value)==true || document.getElementById("txtPreviousBalance").value=="")
	{
		PreviousBalance=0;
	}
	else
	{
		PreviousBalance=document.getElementById("txtPreviousBalance").value;
	}
	
	/*
	if (document.getElementById("isAnnualChargApply").value == "yes")
	{
		if(isNaN(document.getElementById("txtAnnualFee").value)==true || document.getElementById("txtAnnualFee").value=="")
		{
			AnnualCharges=0;
		}
		else
		{
			AnnualCharges=parseInt(document.getElementById("txtAnnualFee").value);
		}
	}
	else
	{AnnualCharges=0;}
	*/
	TuitionFeeDiscount=0;
	/*
	if (isNaN(document.getElementById("txtTuitionFeeDiscount").value)==true || document.getElementById("txtTuitionFeeDiscount").value =="")
	{
		TuitionFeeDiscount=0;
	}
	else
	{
		TuitionFeeDiscount=parseInt(document.getElementById("txtTuitionFeeDiscount").value);
	}
	*/
	HostelFees=0;
	HostelFeeDiscount=0;
	/*
	if (isNaN(document.getElementById("txtHostelFeeDiscount").value)==true || document.getElementById("txtHostelFeeDiscount").value =="")
	{
		HostelFeeDiscount=0;
	}
	else
	{
		HostelFeeDiscount=parseInt(document.getElementById("txtHostelFeeDiscount").value);
	}
	*/
	
	//SecurityCharges=document.getElementById("txtSecurityCharge").value;
	//LabCharges=document.getElementById("txtLabCharge").value;
	
		//TotalAmt1=parseInt(TutionFee)+parseInt(TransportFees)+parseInt(AdjustedLateFee)+parseInt(PreviousBalance)+parseInt(AnnualCharges)+parseInt(SecurityCharges)+parseInt(LabCharges)-parseInt(TuitionFeeDiscount);
		TotalAmt1=parseInt(TutionFee)+parseInt(AdjustedLateFee)+parseInt(PreviousBalance)+parseInt(HostelFees)-parseInt(TuitionFeeDiscount)-parseInt(HostelFeeDiscount);
		
	
	//TotalAmt=parseInt(TutionFee) + parseInt(TransportFees) + parseInt(AdjustedLateFee) + parseInt(PreviousBalance) +parseInt(AnnualCharges)+parseInt(SecurityCharges)+parseInt(LabCharges) - parseInt(TuitionFeeDiscount);
	TotalAmt=parseInt(TutionFee) + parseInt(AdjustedLateFee) + parseInt(PreviousBalance) +parseInt(HostelFees) - parseInt(TuitionFeeDiscount)-parseInt(HostelFeeDiscount);
	if (TotalAmt<0)
	{
		alert("Tution Fee discount can not be more then total fee payable!");
		//document.getElementById("txtTuitionFeeDiscount").value="";
		CalculatTotal();
		return;
		
	}
	document.getElementById("txtTotal").value=parseInt(TotalAmt);
	document.getElementById("txtTotalAmtPaying").value=parseInt(TotalAmt);
	
}

function fnlPaymentMode()
{
	if (document.getElementById("cboPaymentMode").value!="Cash")
	{
		document.getElementById("txtChequeNo").readOnly=false;
		//document.getElementById("txtBank").readOnly=false;
		document.getElementById("cboBank").disabled=false;
		document.getElementById("txtChequeDate").disabled=false;
	}
	else
	{
		document.getElementById("txtChequeNo").value="";
		//document.getElementById("txtBank").value=""
		document.getElementById("cboBank").value="Select One";
		document.getElementById("cboBank").disabled=true;
		document.getElementById("txtChequeDate").value="";
		document.getElementById("txtChequeDate").disabled=true;
		
		document.getElementById("txtChequeNo").readOnly=true;
		//document.getElementById("txtBank").readOnly=true;
	}
}
function ChangeRoute()
{
	var myWindow = window.open("EditRouteNo.php?sadmission=" + document.getElementById("txtAdmissionNo").value + "&currentroute=" + document.getElementById("currentrouteno").value ,"","width=700,height=650");
}

function ShowReceipt(receiptno)
{
	var myWindow = window.open("ShowReceipt.php?Receipt=" + receiptno ,"","width=700,height=650");	
}

function CheckFinancialYear()
{
	if(parseInt(document.getElementById("txtFinancialYear").value) > parseInt(document.getElementById("cboFinancialYear").value))
	{
		//alert("Student admission year is " + document.getElementById("txtFinancialYear").value + " \n Fees for financial year " + document.getElementById("cboFinancialYear").value + " can not be selected!");
		//document.getElementById("cboFinancialYear").value = "Select One";
		document.getElementById("cboFinancialYear").value = document.getElementById("txtFinancialYear").value;
		return;
	}
}


function fnlTuitionFeeDiscount()
{
		if(document.getElementById("hStudentDiscountType").value == "")
		{
			alert("This student is not eligible for fees discount! \r To apply any discount please edit discount type in student master");
			document.getElementById("cboTuitionFeeDiscountType").value="Select One";
			return;
		}
		if (document.getElementById("cboTuitionFeeDiscountType").value == "Others")
		{
			document.getElementById("txtTuitionFeeDiscount").readOnly=false;
			document.getElementById("txtTuitionFeeDiscount").value="";
			return;
		}
		   try
		    {    
				// Firefox, Opera 8.0+, Safari    
				xmlHttp=new XMLHttpRequest();
			}
		  catch (e)
		    {    // Internet Explorer    
				try
			      {      
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				  }
			    catch (e)
			      {      
					  try
				        { 
							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
				      catch (e)
				        {        
							alert("Your browser does not support AJAX!");        
							return false;        
						}      
				  }    
			 } 
			 xmlHttp.onreadystatechange=function()
		      {
			      if(xmlHttp.readyState==4)
			        {
						var rows="";
			        	rows=new String(xmlHttp.responseText);
			        	
			        	if (document.getElementById("txtTuition").value=="")
			        	{
			        		document.getElementById("txtTuitionFeeDiscount").value ="";
			        	}
			        	else
			        	{
			        		document.getElementById("txtTuitionFeeDiscount").value = (parseInt(document.getElementById("txtTuition").value) * rows)/100;
			        	}
			        	fnlHostelFeeDiscount();
						//CalculatTotal();
						//alert(rows);														
			        }
		      }
		      

			var submiturl="GetFeeDiscount.php?discounttype=" + document.getElementById("cboTuitionFeeDiscountType").value + "&financialyear=" + document.getElementById("cboFinancialYear").value + "&discountreason=tuitionfees";
			xmlHttp.open("GET", submiturl,true);
			xmlHttp.send(null);
}

function fnlHostelFeeDiscount()
{
		if(document.getElementById("hStudentDiscountType").value == "")
		{
			alert("This student is not eligible for fees discount!");
			document.getElementById("cboTuitionFeeDiscountType").value="Select One";
			return;
		}
		
		if (document.getElementById("cboTuitionFeeDiscountType").value == "Others")
		{
			document.getElementById("txtHostelFeeDiscount").readOnly=false;
			document.getElementById("txtHostelFeeDiscount").value="";
			return;
		}
		   try
		    {    
				// Firefox, Opera 8.0+, Safari    
				xmlHttp=new XMLHttpRequest();
			}
		  catch (e)
		    {    // Internet Explorer    
				try
			      {      
					xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
				  }
			    catch (e)
			      {      
					  try
				        { 
							xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
						}
				      catch (e)
				        {        
							alert("Your browser does not support AJAX!");        
							return false;        
						}      
				  }    
			 } 
			 xmlHttp.onreadystatechange=function()
		      {
			      if(xmlHttp.readyState==4)
			        {
						var rows="";
			        	rows=new String(xmlHttp.responseText);
			        	
			        	if (document.getElementById("txtHostel").value=="")
			        	{
			        		document.getElementById("txtHostelFeeDiscount").value ="";
			        	}
			        	else
			        	{
			        		document.getElementById("txtHostelFeeDiscount").value = (parseInt(document.getElementById("txtHostel").value) * rows)/100;
			        	}
			        	
						CalculatTotal();
						//alert(rows);														
			        }
		      }
		      

			var submiturl="GetFeeDiscount.php?discounttype=" + document.getElementById("cboTuitionFeeDiscountType").value + "&financialyear=" + document.getElementById("cboFinancialYear").value + "&discountreason=hostelfees";
			xmlHttp.open("GET", submiturl,true);
			xmlHttp.send(null);
}

function CalculateBalance()
{
	Balance=0;
	
	if (document.getElementById("txtTotal").value != "")
	{
		Total=document.getElementById("txtTotal").value;
	}
	
	if (isNaN(document.getElementById("txtTotalAmtPaying").value)==true || document.getElementById("txtTotalAmtPaying").value=="")
	{AmountPaying=0;}
	else
	{
		if (parseInt(document.getElementById("txtTotalAmtPaying").value) > parseInt(document.getElementById("txtTotal").value))
		{
			alert ("Total Amount paid cant not be greater then Payable Amount!");
			document.getElementById("txtTotalAmtPaying").value="";
			document.getElementById("txtBalance").value="";
			return;
		}
		AmountPaying = document.getElementById("txtTotalAmtPaying").value;
	}
	
	document.getElementById("txtBalance").value = Total - AmountPaying;
}
</script>

<html>



<head>

<meta http-equiv="Content-Language" content="en-us">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<!--<link rel="stylesheet" type="text/css" href="../css/style.css" />-->

<title>Fees Collection</title>

<!-- link calendar resources -->

	<link rel="stylesheet" type="text/css" href="tcal.css" />

	<script type="text/javascript" src="tcal.js"></script>

	

<style type="text/css">

.auto-style1 {
	font-size: 11pt;
	font-family: Cambria;
}
.auto-style2 {
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style5 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}

.auto-style12 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
	text-decoration: underline;
	text-align: center;
}

.auto-style14 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style15 {
	font-size: 11pt;
	color: #822203;
	font-weight: bold;
	font-family: Cambria;
}
.auto-style17 {
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
}
.auto-style18 {
	margin-left: 0px;
	font-family: Cambria;
	font-size: 11pt;
	color: #CC0033;
}

.auto-style20 {
	border-collapse: collapse;
	border-style: solid;
	border-width: 0px;
	font-size: medium;
}

.auto-style22 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style23 {
	border-style: none;
	border-width: medium;
}
.auto-style24 {
	border-style: none;
	border-width: medium;
	text-align: left;
}
.auto-style25 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
}

.auto-style26 {
	border-style: none;
	border-width: medium;
	text-align: center;
}
.auto-style27 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
}
.auto-style28 {
	font-size: 11pt;
	font-weight: normal;
	font-family: Cambria;
}
.auto-style30 {
	font-family: Cambria;
	font-weight: normal;
	font-size: 11pt;
	color: #000000;
}
.auto-style33 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
	text-decoration: underline;
}

.auto-style34 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: 700;
	color: #000000;
	font-size: 11pt;
}

.auto-style35 {
	font-size: 11pt;
	font-family: Cambria;
	color: #CC0033;
}

.auto-style3 {
	font-family: Cambria;
	color: #CD222B;
}
.auto-style6 {
	
	font-family: Cambria;
	color: #CD222B;
}

.auto-style36 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
}
.auto-style37 {
	font-family: Cambria;
}
.auto-style38 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style39 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style40 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.auto-style41 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
}

.style1 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.style2 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
}
.style4 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
}
.style5 {
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
}
.style6 {
	font-family: Cambria;
	color: #000000;
}
.style7 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
}
.style8 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: 700;
	font-size: 11pt;
}
.style9 {
	color: #000000;
}
.style10 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
	text-decoration: underline;
	text-align: center;
}
.style12 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-size: 11pt;
	color: #000000;
}
.style13 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	text-decoration: underline;
}

.style14 {
	border-style: solid;
	border-width: 1px;
}

.style16 {
	border-style: none;
	border-width: medium;
	text-align: left;
	font-family: Cambria;
	font-weight: normal;
	font-size: 11pt;
	color: #000000;
}

.style17 {
	border-style: none;
	border-width: medium;
	text-align: center;
	font-family: Cambria;
	font-size: 11pt;
}

.style18 {
	border-style: none;
	border-width: medium;
	text-align: right;
	font-family: Cambria;
	font-weight: 700;
	font-size: 11pt;
}


.footer {

    height:20px; 
    width: 100%; 
    background-image: none;
    background-repeat: repeat;
    background-attachment: scroll;
    background-position: 0% 0%;
    position: fixed;
    bottom: 2pt;
    left: 0pt;

}   

.footer_contents 

{

        height:20px; 
        width: 100%; 
        margin:auto;        
        background-color:Blue;
        font-family: Cambria;
        font-weight:bold;

}
.style20 {
	border-style: none;
	border-width: medium;
	text-align: right;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #000000;
}
.style22 {
	border-style: none;
	border-width: medium;
	font-family: Cambria;
	font-weight: bold;
	font-size: 11pt;
	color: #FF0000;
}
.style23 {
	text-align: center;
}
</style>

</head>



<body onload="Javascript:CalculatTotal();">

<p>&nbsp;</p>

<table width="100%" cellspacing="0" bordercolor="#000000" id="table_10" class="auto-style20" style="height: 70px">

	

	
	<tr>
		<td class="auto-style14">
		<div class="style23">
		<span class="style6">Fees Payment</span></div>
		<hr class="auto-style3" style="height: -15px">
<p class="auto-style6" style="height: 7px"><a href="javascript:history.back(1)">
<img height="30" src="../images/BackButton.png" width="70" style="float: right"></a></p>
				
				</table>


	
	<form name="frmFees" id="frmFees" method="post" action="FeesPayment.php" target="_self">
	<input type="hidden" name="Q1Fee" id="Q1Fee" value="<?php echo $Q1fees_amount; ?>" class="auto-style37">
	<input type="hidden" name="Q2Fee" id="Q2Fee" value="<?php echo $Q2fees_amount; ?>" class="auto-style37">
	<input type="hidden" name="Q3Fee" id="Q3Fee" value="<?php echo $Q3fees_amount; ?>" class="auto-style37">
	<input type="hidden" name="Q4Fee" id="Q4Fee" value="<?php echo $Q4fees_amount; ?>" class="auto-style37">
	<input type="hidden" name="SubmitType" id="SubmitType" value="" class="auto-style37">
	<input type="hidden" name="currentrouteno" id="currentrouteno" value="<?php echo $RoutNo; ?>">
	<input type="hidden" name="CurrentFinancialYear" id="CurrentFinancialYear" value="<?php echo $CurrentFinancialYear; ?>">
	<input type="hidden" name="StudentAdmissionfinancialyear" id="StudentAdmissionfinancialyear" value="<?php echo $FinancialYear; ?>">
	<input type="hidden" name="FeeSubmissionFinancialYear" id="FeeSubmissionFinancialYear" value="">
	<input type="hidden" name="txtAdmissionNo" id="txtAdmissionNo" value="<?php echo $_REQUEST["txtAdmissionNo"]; ?>">
	<input type="hidden" name="hStudentDiscountType" id="hStudentDiscountType" value="<?php echo $StudentDiscountType;?>">
	
	<table border="1px" width="100%">
	
	
		<!--
		<tr>
		
		<td style="width: 281px; height: 29px;" class="auto-style23">

		<span class="style5">Student Admission No. </span>
		<span style="font-weight: 700; " class="auto-style1">:</span></td>

		<td style="width: 157px; height: 29px;" class="auto-style23">

		<input type="text" name="txtAdmissionNo" id="txtAdmissionNo" size="15" style="width: 151px;" class="auto-style1" value="<?php echo $_REQUEST["txtAdmissionNo"]; ?>"></td>

		<td style="width: 157px; height: 29px;" class="auto-style26">



		<input name="btnGo" type="button" value="Fill Detail" onclick="Javascript:Validate1();" class="auto-style1" style="width: 82px"></td>
	</tr>
	-->
	
	<tr>
	
	
	
		<td style="width: 281px; height: 52px;" class="auto-style23">

		<span class="style5">Student Name</span><span class="auto-style1">
		</span>

		</td>

		<td style="width: 157px; height: 52px;" class="auto-style23">

		<input name="txtName" id="txtName" type="text" class="text-box" value="<?php echo $sname;?>" readonly="readonly" ></td>
	
		
		
		
	
		<td style="width: 157px; height: 52px;" class="auto-style41">

		&nbsp;</td>
	
		
		
		
	
		<td style="width: 179px; height: 52px;" class="style4">

		Class</td>

		<td style="height: 52px;" class="auto-style23">

		<input name="txtClass" id="txtClass" type="text" class="text-box"value="<?php echo $class;?>" readonly="readonly"></td>
		
		
	
		<td style="width: 191px; height: 52px;" class="auto-style26">

		<span style="font-weight: 700; " class="auto-style1">
		Roll No</span><span class="auto-style1">
		</span>

		</td>
		
		

		<td style="height: 52px;" class="auto-style23">

		<input name="txtRollNo" id="txtRollNo" type="text"  value="<?php echo $RollNo; ?>" readonly="readonly" class="text-box"></td>
		<br class="auto-style1">
		
		<br class="auto-style1">
		</tr>
		
		
		</table>
		<br class="auto-style37">


	
	
	
	<table style="height: 45px" width="100%" class="style14">
		
		
	<tr>
	
	
	
		<td style="width: 154px; height: 39px;" class="style2">

		&nbsp;</td>

	
	
		<td style="height: 39px;" class="auto-style22">

		<select name="cboPaymentMode" id="cboPaymentMode" class="text-box" onchange="Javascript:fnlPaymentMode();" style="display: none;">
		<option value="Online" selected="selected" >Online</option>
		<option value="Cheque">Cheque</option>
		<option value="Demand Draft">Demand Draft</option>
		</select></td>

		
		
		
	
		<td style="height: 39px; width: 167px" class="style20">

		&nbsp;</td>

		
	
		
	
		<td style="height: 39px; width: 167px" class="style1">

		<input name="txtChequeDate" id="txtChequeDate" class="tcal" type="text" style="display: none;"></td>
	
		<td style="height: 39px; width: 167px" class="style1">

		&nbsp;</td>

		
	
		<td style="width: 99px; height: 39px;" class="auto-style25">

		<strong>

		<input name="txtChequeNo" id="txtChequeNo" type="text" class="text-box" style="display: none;"></strong></td>
		
		

		<td style="width: 155px; height: 39px;" class="auto-style26">

		&nbsp;</td>
		
		<td style="height: 39px;" class="auto-style22">
		
		<!--
		<input name="txtBank" id="txtBank" type="text" style="width: 97px" readonly="readonly" class="auto-style37">
		-->
		<select name="cboBank" id="cboBank" class="text-box" style="display: none;">
		<option value="" selected="selected" >Select One</option>
		<?php
		while($row = mysqli_fetch_row($rsBank))
		{
			$Bankname=$row[0];
		?>						
		<option value="<?php echo $Bankname;?>"><?php echo $Bankname;?></option>
		<?php
		}
		?>
		</select>
		</td>

		</tr>
		
		
		</table>
		<br class="auto-style37">
	
		
	
<table width="100%" class="style14">

<tr>			
		

		<td style="height: 29px;" class="style10" colspan="5">

		<strong>Fees Heads</strong></td>

			</tr>
		
<tr>			
		

		<td style="width: 36%; height: 38px" class="style2">

		<blockquote>

		Financial Year</blockquote>
		</td>


		<td class="auto-style22" style="height: 38px" colspan="2">

		<input type="hidden" name="txtFinancialYear" id="txtFinancialYear" value="<?php echo $FeeSubmissionFinancialYear; ?>">

		<select name="cboFinancialYear" id="cboFinancialYear" onchange="javascript:CheckFinancialYear();" style="height: 22px">
				<option selected="" value="Select One">Select One</option>
				<?php
				while($rowFY = mysqli_fetch_row($rsFY))
				{
							$Year=$rowFY[0];
							$FYear=$rowFY[1];
				?>
				<option value="<?php echo $Year; ?>" <?php if($FeeSubmissionFinancialYear==$Year) { echo "selected"; } ?>><?php echo $FYear; ?></option>
				<?php 
				}
				?>
				</select>
		<span class="style9">
		</span>
		</td>


		<td class="style22" style="height: 38px" colspan="2">
		<?php 
		if ($StudentDiscountType != "")
		{
		echo "This student is eligible for discount!";
		}
		?>
		
		</td>
		

			</tr>
		
<tr>			
		

		<td style="width: 36%; height: 38px" class="style2">

		<blockquote>

		Fees Payment for Quarter</blockquote>
		</td>


		<td class="auto-style22" style="height: 38px" colspan="4">
		<!--
		<select name="cboQuarter" id="cboQuarter" style="width: 156px" onchange="GetFeeDetail();" class="auto-style1" >
		<option selected="" value="Select One">Select One</option>
		<option value="Q1">Quarter 1 [April - June]</option>
		<option value="Q2">Quarter 2 [July - September]</option>
		<option value="Q3">Quarter 3 [October - December]</option>
		<option value="Q4">Quarter 4 [ January - March]</option>
		</select>
		-->
		<input type="text" name="cboQuarter" id="cboQuarter" class="text-box" value="<?php echo $FeeSubmissionQuarter; ?>" readonly="readonly">
		</td>

			</tr>
		
<tr>			
		

		<td style="width: 36%; " class="style2">

		<blockquote>

		Hostel Fees</blockquote>
		</td>


		<td style="width: 62%; " class="auto-style22" colspan="4">

		<input name="txtTuition" id="txtTuition" type="text" class="text-box" readonly="readonly" value="<?php echo $HostelCharge; ?>" ></td>

			</tr>
<!--		
<tr>			
		

		<td style="width: 36%; height: 37px" class="style2">

		<blockquote>

		Fees Discount Type</blockquote>
		</td>


		<td style="width: 24%; height: 37px" class="auto-style22">



				<select name="cboTuitionFeeDiscountType" id="cboTuitionFeeDiscountType" class="text-box" onchange="Javascript:fnlTuitionFeeDiscount();">
				<option selected="" value="Select One">Select One</option>
				<?php
				while($row = mysqli_fetch_row($rsDiscount))
				{
				?>
				<option value="<?php echo $row[0];?>"><?php echo $row[0];?></option>
				<?php
				}
				?>
				
				</select></td>


		<td style="width: 19%; height: 37px; text-align:left" class="style15" colspan="2">

		<strong>Tuition Fees Discount</strong></td>


		<td style="width: 19%; height: 37px" class="auto-style22">

		<input name="txtTuitionFeeDiscount" id="txtTuitionFeeDiscount" type="text" class="text-box" readonly="readonly" onkeyup="Javascript:CalculatTotal();"></td>

			</tr>
-->			
		
		<?php
			if ($FeeSubmissionQuarter == "Q1" && $StudentType = "Old Student")
			{
				$AnnualChargApply="yes";
		?>
		<!--
		<tr id="trAnnualFee">

		<td style="width: 36%; height: 36px" class="style2">

		<blockquote>

		Annual Charges</blockquote>
		</td>

		<td style="width: 24%; height: 36px" class="auto-style23">

		<input name="txtAnnualFee" id="txtAnnualFee" type="text" class="text-box" value="<?php echo $AnnualFee; ?>"></td>

			

		

		
		<td style="width: 19%; height: 36px" class="auto-style23" id="tdRouteNo" colspan="2" align="left">
		 
		 &nbsp;</td>
		
		<td style="width: 19%; height: 36px" class="auto-style36" id="tdChangeRoute">
		
		
		</td>

		</tr>
		-->
		<?php
		}
		else
		{
			$AnnualChargApply="no";
		}
		?>
		<input type="hidden" name="isAnnualChargApply" id="isAnnualChargApply" value="<?php echo $AnnualChargApply; ?>">		

		<!--
		<tr id="trAnnualFee">

		<td style="width: 36%; height: 36px" class="style2">

		<blockquote>

		Security Charges</blockquote>
		</td>

		<td style="width: 24%; height: 36px" class="auto-style23">

		<input name="txtSecurityCharge" id="txtSecurityCharge" type="text" value="<?php echo $SecurityFee;?>"></td>

			

		

		
		<td style="width: 19%; height: 36px" class="style21" id="tdRouteNo" colspan="2" align="left">
		 
		 <strong>Lab Charges</strong></td>
		
		<td style="width: 19%; height: 36px" class="auto-style36" id="tdChangeRoute">
		
		
		<input name="txtLabCharge" id="txtLabCharge" type="text" value="<?php echo $LabFee;?>"></td>

		</tr>
		

		<tr>

		<td style="width: 36%; height: 36px" class="style2">

		<blockquote>

		Transport Fees</blockquote>
		</td>

		<td style="width: 24%; height: 36px" class="auto-style23">



		<input name="txtTransportFees" id="txtTransportFees" type="text" class="text-box" readonly="readonly" value="<?php echo $TransportFee; ?>"></td>

			

		

		
		<td style="width: 19%; height: 36px; text-align:left" class="style19" id="tdRouteNo" colspan="2">
		 
		 
		 <strong><span class="auto-style1">Current Route:(<?php echo $RoutNo; ?>)</span></strong></td>

		
		<td style="width: 19%; height: 36px" class="auto-style36" id="tdChangeRoute">
		<strong><a href='Javascript:ChangeRoute();'>Change Route</a></strong>
		
		</td>
		
		</tr>
		-->
<!--		
<tr>
		<td style="width: 36%; height: 37px" class="style8">

		<blockquote>

		Hostel Charge</blockquote>
		</td>

		<td style="width: 24%; height: 37px" class="auto-style24">



		<input name="txtHostel" id="txtHostel" type="text" class="text-box" value="<?php echo $HostelCharge;?>" readonly="readonly"></td>

		<td style="width: 19%; height: 37px; text-align:left" class="style18" colspan="2">


		</td>

		<td style="width: 19%; height: 37px" class="auto-style24">
			</td>
	</tr>
-->		
<tr>
		<td style="width: 36%; height: 37px" class="style8">

		<blockquote>

		Late Fees Charge</blockquote>
		</td>

		<td style="width: 24%; height: 37px" class="auto-style24">



		<input name="txtLateFee" id="txtLateFee" type="text" class="text-box" readonly="readonly" value="<?php echo $LateFee; ?>" ></td>

		<td style="width: 19%; height: 37px; text-align:left" class="style18" colspan="2">



		Actual Delay
		Days:</td>

		<td style="width: 19%; height: 37px" class="auto-style24">

			<input name="txtLateDays" id="txtLateDays" type="text" readonly="readonly" class="text-box" value="<?php echo $LateDays; ?>"><span class="auto-style37">
			</span>
		</td>

	</tr>
		
<tr>
		<td style="width: 36%; height: 37px" class="style8">

		<blockquote>

		Late Fees Charges to be paid</blockquote>
		</td>

		<td style="width: 24%; height: 37px" class="auto-style24">



		<input name="txtAdjustedLateFee" id="txtAdjustedLateFee" type="text" class="text-box" value="<?php echo $LateFee; ?>" readonly="readonly"></td>

		<td style="width: 19%; height: 37px; text-align:left" class="style18" colspan="2">



		Adjusted Delay
		Days:</td>

		<td style="width: 19%; height: 37px" class="auto-style24">

			<input name="txtAdjustedLateDays" id="txtAdjustedLateDays" type="text" onkeyup="Javascript:CalculateLateFee();" class="text-box" value="<?php echo $LateDays; ?>" readonly="readonly"><span class="auto-style37">
			</span>
		</td>

	</tr>
	

	<?php 

	if ($Message1 !="")

	{

	?>

	<?php 

	}

	?>

	

<tr>
		<td style="width: 36%; height: 33px" class="style8">

		<blockquote>

		Previous Balance</blockquote>
		</td>

		<td style="width: 62%; height: 33px" class="auto-style24" colspan="4">



		<input name="txtPreviousBalance" id="txtPreviousBalance" type="text" class="text-box" <?php if($FirstTimeFeePaying == "no") { ?> readonly="readonly" <?php } ?> onkeyup="Javascript:CalculatTotal();" value="<?php echo $LastBalanceAmount; ?>"></td>

	</tr>

	

	<tr>

		<td style="height: 29px; width: 36%;" class="style7">

		<blockquote>

		Remarks</blockquote>
		</td>

		<td style="height: 29px;" class="auto-style14" colspan="4">



		<textarea name="txtRemarks" id="txtRemarks" rows="2" class="text-box-address"></textarea></td>

	</tr>

	

	<tr>

		<td style="height: 29px; width: 36%;" class="style4">

		Total Fees Payable</td>

		<td style="height: 29px;" class="auto-style14" colspan="4">



		<input name="txtTotal" id="txtTotal" type="text" class="text-box" readonly="readonly" value="<?php echo $TotalPaybleAmount;?>"></td>

	</tr>

	

	<tr>

		<td style="height: 29px; width: 36%;" class="style4">

		Total Fees Paid</td>

		<td style="height: 29px;" class="auto-style14" colspan="2">



		<input name="txtTotalAmtPaying" id="txtTotalAmtPaying" type="text" class="text-box" onkeyup="Javascript:CalculateBalance();" value="<?php echo $TotalPaybleAmount;?>" readonly="readonly" ></td>

		<td style="height: 29px; width: 15%;" class="style16">



		<strong>Balance</strong></td>

		<td style="height: 29px;" class="auto-style14">



		<input name="txtBalance" id="txtBalance" type="text" class="text-box" value="0" readonly="readonly"></td>

	</tr>

	<tr>

		<td style="height: 29px;" colspan="5" class="auto-style5">

		<strong>

		<input name="BtnSubmit" type="button" value="Preview" onclick="Javascript:Validate('Preview');" class="text-box">
		<input name="BtnSubmit" type="button" value="Submit" onclick="Javascript:Validate('Final');" class="text-box"><span class="auto-style37">
		</span>
		</strong></td>

	</tr>

		

</table>
		<span class="auto-style37">
<!--
</form>
-->
		</span>

	
		<br class="auto-style1">

<table class="CSSTableGenerator" style="align:center; width: 100%;">

<tr>			
		

		<td style="height: 29px;" class="style13" colspan="9">

		Payment History</td>

			</tr>
		
<tr>			
		

		<td style="height: 16px; width: 100px;" class="style12">

		<strong>Fee Payment Quarter</strong></td>


		<td style="height: 16px; width: 100px;" class="style12">

		<strong>Receipt #</strong></td>


		<td style="height: 16px; width: 100px;" class="style12">

		<strong>Fee Payable</strong></td>


		<td style="height: 16px; width: 100px;" class="style12">

		<strong>Fee Paid</strong></td>


		<td style="height: 16px; width: 100px;" class="style12">

		<strong>Balance</strong></td>


		<td style="height: 16px; width: 101px;" class="style12">

		<strong>Payment Status</strong></td>


		<td style="height: 16px; width: 101px;" class="style12">

		<strong>Fees Payment Date</strong></td>


		<td style="height: 16px; width: 101px;" class="style12">

		<strong>Financial Year</strong></td>


		<td style="height: 16px; width: 101px;" class="style12">

		<strong>Print Reciept</strong></td>

			</tr>
<?php
while($row = mysqli_fetch_row($rs))
	{
					
					$quarter=$row[0];
					$fees_amount=$row[1];
					$amountpaid=$row[2];
					$BalanceAmt=$row[3];
					$status=$row[4];
					$receipt=$row[5];
					$date=$row[6];
					$FinancialYear=$row[7];				
?>
		
<tr>			
		

		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $quarter; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $receipt; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $fees_amount; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $amountpaid; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $BalanceAmt; ?></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<?php echo $status; ?></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<?php echo $date; ?></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<?php echo $FinancialYear; ?></td>


		<td style="width: 101px; height: 20px;" class="auto-style5">


			<input name="PrintQ1Receipt" type="button" value="Print Reciept" class="text-box" onclick="Javascript:ShowReceipt('<?php echo $receipt; ?>');"><span class="style6">
			</span>
		</td>

			</tr>

<?php
	$sqlPB = "SELECT `PBalanceReceiptNo`,`PreviousBalance`,`PaidBalanceAmt`,`CurrentBalance`,date_format(`ReceiptDate`,'%d-%m-%y') FROM `fees_transaction` where  `ReceiptNo`='$receipt' and `PBalanceReceiptNo` !=''";
	$rsPB = mysqli_query($Con, $sqlPB);
				if (mysqli_num_rows($rsPB) > 0)
				{
					while($rowPB = mysqli_fetch_row($rsPB))
					{						
						$BalanceReceiptNo=$rowPB[0];
						$PayableBalanceAmt=$rowPB[1];
						$PaidBalanceAmt=$rowPB[2];
						$OutstandingAmt=$rowPB[3];
						$ReceiptDate=$rowPB[4];
					
?>
<tr>			
		<td style="width: 100px; height: 20px;" class="style12">
		</td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $BalanceReceiptNo; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">
		<?php echo $PayableBalanceAmt; ?>
		</td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $PaidBalanceAmt; ?></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<?php echo $OutstandingAmt; ?></td>


		<td style="width: 101px; height: 20px;" class="style17">

		</td>


		<td style="width: 101px; height: 20px;" class="style17">

		<?php echo $ReceiptDate; ?></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<?php echo $FinancialYear; ?></td>


		<td style="width: 101px; height: 20px;" class="auto-style5">


			<input name="PrintQ1Receipt"  type="button" value="Print Reciept" class="text-box" onclick="Javascript:ShowReceipt('<?php echo $BalanceReceiptNo; ?>');"><span class="style6">
			</span>
		</td>

			</tr>			
	<?php 
			}
		}
	?>		
<?php
}
?>		
			

</table>	
</form>


<div class="footer" align="center">

    <div class="footer_contents" align="center">

		<font color="#FFFFFF" face="Cambria" size="2">Powered by Eduworld Technologies LLP</font></div>

</div>
</body>



</html>