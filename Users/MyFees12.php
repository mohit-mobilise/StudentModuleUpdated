<?php
session_start();
include '../connection.php';
	$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	$sadmission=$_SESSION['userid'];
	if($sadmission == "")
	{
		echo "<br><br><center><b>Session Expired!<br>click <a href='http://dpsfsis.com/'>here</a> to login again!";
		exit();
	}
//************

$sql = "SELECT `FinancialYear`,`Hostel` FROM `student_master` as `a` where `sadmission`='$sadmission'"; 
$rsSt = mysqli_query($Con, $sql);
while($rowSt=mysqli_fetch_row($rsSt))
{
$FinancialYear=$rowSt[0];
$StudentHostel=$rowSt[1];
break;
}
if($FinancialYear<$CurrentFinancialYear)
	{
			$StudentType="OldStudent";
			$QStudentType="old";
		}
		else
		{
			$StudentType="NewStudent";
			$QStudentType="new";
		}


$rsFeeHeadAmt=mysqli_query($Con, "SELECT distinct `feeshead`,sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` !='Hostel' and `FeesType` !='' and `feeshead` not in ('COMPUTERFEES','SMART CLASS','SCIENCE FEES','ANNUAL CHARGES') group by `feeshead`");
		$rsAnnual=mysqli_query($Con, "SELECT distinct `feeshead`,sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` !='Hostel' and `FeesType` !='' and `feeshead` in ('COMPUTERFEES','SMART CLASS','SCIENCE FEES','ANNUAL CHARGES') group by `feeshead`");
		$AnnualCharges=0;
		while($rowAnnual=mysqli_fetch_row($rsAnnual))
		{
			$AnnualCharges=$AnnualCharges+$rowAnnual[1];
		}
		
		//$rsYearlyTotalFee=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` !='Hostel' and `feeshead`='Total Bill Amount'");
		$rsYearlyTotalFee=mysqli_query($Con, "SELECT sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` in ('Regular')");
		$rsYearlyTotalHostelFee=mysqli_query($Con, "SELECT sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` ='Hostel'");
		$rsYearlyConcessionAmt=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType` !='Hostel' and (`feeshead`='Total Concession Amount' or `feeshead`='TOTAL CONCESSION AMOUNT')");
		
		while($rowConcession=mysqli_fetch_row($rsYearlyConcessionAmt))
		{
			$TotalConcessionAmt=$rowConcession[0];
		}
		
		$YearlyTotalHostelAmount=0;
		while($rowTotalHostel=mysqli_fetch_row($rsYearlyTotalHostelFee))
		{
			$YearlyTotalHostelAmount=$rowTotalHostel[0];
			break;
		}
		
		
		$rsAdvanceAmt=mysqli_query($Con, "select sum(`amount`) from `fees_student` where `sadmission`='$sadmission' and `feeshead`='Advances'");
		while($rowAdvance=mysqli_fetch_row($rsAdvanceAmt))
		{
			$FeeAmtPaid=$rowAdvance[0];
			break;
		}
		
		
		//$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaid;

		
		while($rowTotalFee=mysqli_fetch_row($rsYearlyTotalFee))
		{
			$YearlyTotalFeeAmount=$rowTotalFee[0];
			break;
		}
		//$YearlyTotalFeeAmount=$YearlyTotalFeeAmount-$TotalConcessionAmt-$YearlyTotalHostelAmount;
		
		echo $YearlyTotalFeeAmount."/".$TotalConcessionAmt."/".$FeeAmtPaid;
		exit();
		$YearlyTotalFeeAmount=$YearlyTotalFeeAmount-$TotalConcessionAmt-$FeeAmtPaid;
		
		
		
		$FeeAmtPaidQ1=0;
		$FeeAmtPaidQ2=0;
		$FeeAmtPaidQ3=0;
		$FeeAmtPaidQ4=0;
		$ActualReceivedAmtQ1=0;
		$ActualReceivedAmtQ2=0;
		$ActualReceivedAmtQ3=0;
		$ActualReceivedAmtQ4=0;
		
		$HostelFeeAmtPaidQ1=0;
		$HostelFeeAmtPaidQ2=0;
		$HostelFeeAmtPaidQ3=0;
		$HostelFeeAmtPaidQ4=0;
		$HostelActualReceivedAmtQ1=0;
		$HostelActualReceivedAmtQ2=0;
		$HostelActualReceivedAmtQ3=0;
		$HostelActualReceivedAmtQ4=0;

		$rsCheckQ1=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='Fees First Installment'");
			if (mysqli_num_rows($rsCheckQ1) == 0)
			{
				//$rsFeePaidQ1=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `class`='$MasterClass' and `quarter`='Q1' and `StudentType`='$QStudentType' and `feeshead`='TUITION FEES'");
				if($FeeAmtPaid > 0)
				{
					$FeeAmtPaidQ1=0;
				}
				else
				{
					$rsFeePaidQ1=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q1' and `feeshead`='TUITION FEES'");
					while($rowFPQ1=mysqli_fetch_row($rsFeePaidQ1))
					{
						$FeeAmtPaidQ1=$rowFPQ1[0];
						if($FeeAmtPaidQ1>($YearlyTotalFeeAmount-$TotalAmountPaid))
						{
							$FeeAmtPaidQ1=$YearlyTotalFeeAmount-$TotalAmountPaid;						
						}
						$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ1;
						break;
					}
				}
				
			}
			else
			{
				while($rowFPQ1=mysqli_fetch_row($rsCheckQ1))
				{
				
					$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date` from `fees` where `sadmission`='$sadmission' and `quarter`='Q1' and `FeesType` !='Hostel'");
					while($rowRcpt=mysqli_fetch_row($rsRcptNo))
					{
						$ReceiptNoQ1=$rowRcpt[0];
						$ReceiptDateQ1=$rowRcpt[1];
						break;
					}
					
					//$ReceiptDateQ1=$rowFPQ1[1];
					$FeeAmtPaidQ1=$rowFPQ1[0];
					$ActualReceivedAmtQ1=$rowFPQ1[0];
					$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ1;
					
					break;
				}
			}
			
			
		
		//CHECK FEE SUBMISSION IN FEE TABLE FOR QUARTER Q2 IF FOUND THEN SHOW RECEIPT DETAIL OTHER WISE TO PAID AMOUNT WILL BE SHOWN//////////////////
			
			
			$rsCheckQ2=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='Fees Second Installment'");
			if (mysqli_num_rows($rsCheckQ2) == 0)
			{
				$rsFeePaidQ2=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q2' and `feeshead`='TUITION FEES'");
				while($rowFPQ2=mysqli_fetch_row($rsFeePaidQ2))
				{
					$FeeAmtPaidQ2=$rowFPQ2[0];
					if($FeeAmtPaidQ2>($YearlyTotalFeeAmount-$TotalAmountPaid))
					{
						$FeeAmtPaidQ2=$YearlyTotalFeeAmount-$TotalAmountPaid;																		
					}
					$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ2;
					break;
				}
			}
			else
			{
				
				while($rowFPQ2=mysqli_fetch_row($rsCheckQ2))
				{
					
					
					$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date`  from `fees` where `sadmission`='$sadmission' and `quarter`='Q2'  and `FeesType` !='Hostel'");
					while($rowRcpt=mysqli_fetch_row($rsRcptNo))
					{
						$ReceiptNoQ2=$rowRcpt[0];
						$ReceiptDateQ2=$rowRcpt[1];
						break;
					}
					//$ReceiptNoQ2=$rowFPQ2[0];
					//$ReceiptDateQ2=$rowFPQ2[1];
					$ActualReceivedAmtQ2=$rowFPQ2[0];
					$FeeAmtPaidQ2=$rowFPQ2[0];
					$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ2;
					break;
				}	
			}
		//CHECK FEE SUBMISSION IN FEE TABLE FOR QUARTER Q3 IF FOUND THEN SHOW RECEIPT DETAIL OTHER WISE TO PAID AMOUNT WILL BE SHOWN//////////////////
		
			$rsCheckQ3=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='Fees Third Installment'");
			if (mysqli_num_rows($rsCheckQ3) == 0)
			{
				$rsFeePaidQ3=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q3' and `feeshead`='TUITION FEES'");
					
				while($rowFPQ3=mysqli_fetch_row($rsFeePaidQ3))
				{
					$FeeAmtPaidQ3=$rowFPQ3[0];
					//echo $YearlyTotalFeeAmount."/".$TotalAmountPaid."/".$FeeAmtPaidQ1."/".$FeeAmtPaidQ2;
					//exit();
					if($FeeAmtPaidQ3>($YearlyTotalFeeAmount-$TotalAmountPaid))
					{
						$FeeAmtPaidQ3=$YearlyTotalFeeAmount-$TotalAmountPaid;																		
					}
					$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ3;
					break;
				}
			}
			else
			{
				while($rowFPQ3=mysqli_fetch_row($rsCheckQ3))
				{
					$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date`,`AdjustedLateFee` from `fees` where `sadmission`='$sadmission' and `quarter`='Q3'  and `FeesType` !='Hostel'");
					while($rowRcpt=mysqli_fetch_row($rsRcptNo))
					{
						$ReceiptNoQ3=$rowRcpt[0];
						$ReceiptDateQ3=$rowRcpt[1];
						$AdjustedLateFeeQ3=$rowRcpt[2];
						break;
					}
					
					
					//$ReceiptNoQ3=$rowFPQ3[0];
					//$ReceiptDateQ3=$rowFPQ3[1];
					$ActualReceivedAmtQ3=$rowFPQ3[0];
					//$FeeAmtPaidQ3=$rowFPQ3[0]-$AdjustedLateFeeQ3;
					$FeeAmtPaidQ3=$rowFPQ3[0];
					$TotalAmountPaid=$TotalAmountPaid+$FeeAmtPaidQ3;
					break;
				}	
			}
		
		
		//CHECK FEE SUBMISSION IN FEE TABLE FOR QUAERTER Q4
			$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date` from `fees` where `sadmission`='$sadmission' and `quarter`='Q4' and `FeesType` !='Hostel'");
					while($rowRcpt=mysqli_fetch_row($rsRcptNo))
					{
						$ReceiptNoQ4=$rowRcpt[0];
						$ReceiptDateQ4=$rowRcpt[1];
						break;
					}
					//echo $ReceiptNoQ4;
					//exit();
					
			$rsCheckQ4=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='FEES FOURTH INSTALLMENT'");
			if (mysqli_num_rows($rsCheckQ4) > 0)
			{
				while($rowRcpt=mysqli_fetch_row($rsCheckQ4))
				{
					$ActualReceivedAmtQ4=$rowRcpt[0];
					break;
				}
			}				

		
		//********
		
		///HOSTEL AMOUNT CALCULATION**************************
		
							$rsYrTotalHostelAmt=mysqli_query($Con, "SELECT sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType`='Hostel'");
							while($rowYr=mysqli_fetch_row($rsYrTotalHostelAmt))
							{
								$ToalHostelAmtPayableYearly=$rowYr[0];
								break;
							}
							
							$rsHostelAdvance=mysqli_query($Con, "select `amount` from `fees_student` where `feeshead`='HOSTEL AMOUNT CREDIT' and `sadmission`='$sadmission'");
							while($rowH = mysqli_fetch_row($rsHostelAdvance))
							{
								$HostelAmountAdvance=$rowH[0];
								break;
							}
							//echo $HostelAmountAdvance;
							//exit();
							$ToalHostelAmtPayableYearly=$ToalHostelAmtPayableYearly-$HostelAmountAdvance;
							$TotalHostelAmountPaid=$HostelAmountAdvance;;
							
						$rsHostelPaidAmt=mysqli_query($Con, "select sum(`amountpaid`) from `fees` where `sadmission`='$sadmission' and `FinancialYear`='$FeeSubmissionFinancialYear' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
									while($row = mysqli_fetch_row($rsHostelPaidAmt))
									{
										$PaidHostelAmount=$row[0];
										break;
									}
					
						$rsCheckQ1=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='HOSTEL FIRST INSTALLMENT'");
								if (mysqli_num_rows($rsCheckQ1) == 0)
								{	
										$rsFeePaidQ1=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q1' and `feeshead`='hostelfees'");
										while($rowFPQ1=mysqli_fetch_row($rsFeePaidQ1))
										{
											$HostelFeeAmtPaidQ1=$rowFPQ1[0];
											if($HostelFeeAmtPaidQ1>($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) && ($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) > 0)
											{
												$HostelFeeAmtPaidQ1=$ToalHostelAmtPayableYearly-$TotalHostelAmountPaid;																		
											}
											if(($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) < 0)
											{
												$HostelFeeAmtPaidQ1=0;
											}
											$TotalHostelAmountPaid=$TotalHostelAmountPaid+$HostelFeeAmtPaidQ1;
											break;
										}
									
								}
								else
								{
									while($rowFPQ1=mysqli_fetch_row($rsCheckQ1))
									{
										$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date` from `fees` where `sadmission`='$sadmission' and `quarter`='Q1' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
										while($rowRcpt=mysqli_fetch_row($rsRcptNo))
										{
											$HostelReceiptNoQ1=$rowRcpt[0];
											$HostelReceiptDateQ1=$rowRcpt[1];
											break;
										}
										
										//$ReceiptDateQ1=$rowFPQ1[1];
										$HostelFeeAmtPaidQ1=$rowFPQ1[0];
										$HostelActualReceivedAmtQ1=$rowFPQ1[0];
										$TotalHostelAmountPaid=$TotalHostelAmountPaid+$HostelFeeAmtPaidQ1;
										
										break;
									}
								}
								
							
							//CHECK FEE SUBMISSION IN FEE TABLE FOR QUARTER Q2 IF FOUND THEN SHOW RECEIPT DETAIL OTHER WISE TO PAID AMOUNT WILL BE SHOWN//////////////////
							
								$rsCheckQ2=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='HOSTEL SECOND INSTALLMENT'");
								if (mysqli_num_rows($rsCheckQ2) == 0)
								{
									$rsFeePaidQ2=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q2' and `feeshead`='hostelfees'");
									while($rowFPQ2=mysqli_fetch_row($rsFeePaidQ2))
									{
										$HostelFeeAmtPaidQ2=$rowFPQ2[0];
										
										if($HostelFeeAmtPaidQ2>($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid)  && ($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) > 0)
											{
												$HostelFeeAmtPaidQ2=$ToalHostelAmtPayableYearly-$TotalHostelAmountPaid;																		
											}
										if(($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) < 0)
										{
											$HostelFeeAmtPaidQ2=0;
										}
										$TotalHostelAmountPaid=$TotalHostelAmountPaid+$HostelFeeAmtPaidQ2;
										break;
									}
								}
								else
								{
									while($rowFPQ2=mysqli_fetch_row($rsCheckQ2))
									{
										$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date`  from `fees` where `sadmission`='$sadmission' and `quarter`='Q2' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
										while($rowRcpt=mysqli_fetch_row($rsRcptNo))
										{
											$HostelReceiptNoQ2=$rowRcpt[0];
											$HostelReceiptDateQ2=$rowRcpt[1];
											break;
										}
										
										$HostelActualReceivedAmtQ2=$rowFPQ2[0];
										$HostelFeeAmtPaidQ2=$rowFPQ2[0];
										$TotalHostelAmountPaid=$TotalHostelAmountPaid+$HostelFeeAmtPaidQ2;
										break;
									}	
								}
							//CHECK FEE SUBMISSION IN FEE TABLE FOR QUARTER Q3 IF FOUND THEN SHOW RECEIPT DETAIL OTHER WISE TO PAID AMOUNT WILL BE SHOWN//////////////////
							//echo "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='HOSTEL THIRD INSTALLMENT'";
							//exit();
							
								$rsCheckQ3=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='HOSTEL THIRD INSTALLMENT'");
								if (mysqli_num_rows($rsCheckQ3) == 0)
								{
									$rsFeePaidQ3=mysqli_query($Con, "SELECT `amount` FROM `fees_master` WHERE `quarter`='Q3' and `feeshead`='hostelfees'");
										
									while($rowFPQ3=mysqli_fetch_row($rsFeePaidQ3))
									{
										$HostelFeeAmtPaidQ3=$rowFPQ3[0];
										if($HostelFeeAmtPaidQ3>($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid)  && ($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) > 0)
											{
												$HostelFeeAmtPaidQ3=$ToalHostelAmtPayableYearly-$TotalHostelAmountPaid;																		
											}
										if(($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) < 0)
										{
											$HostelFeeAmtPaidQ3=0;
										}
										$TotalHostelAmountPaid=$TotalHostelAmountPaid+$FeeAmtPaidQ3;
										break;
									}
								}
								else
								{
									while($rowFPQ3=mysqli_fetch_row($rsCheckQ3))
									{
										$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date` from `fees` where `sadmission`='$sadmission' and `quarter`='Q3' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
										while($rowRcpt=mysqli_fetch_row($rsRcptNo))
										{
											$HostelReceiptNoQ3=$rowRcpt[0];
											$HostelReceiptDateQ3=$rowRcpt[1];
											break;
										}
										
										$HostelActualReceivedAmtQ3=$rowFPQ3[0];
										$HostelFeeAmtPaidQ3=$rowFPQ3[0];
										$TotalHostelAmountPaid=$TotalHostelAmountPaid+$HostelFeeAmtPaidQ3;
										break;
									}	
								}
							
							
							//CHECK FEE SUBMISSION IN FEE TABLE FOR QUAERTER Q4
								$rsRcptNo=mysqli_query($Con, "select `receipt`,DATE_FORMAT(`date`,'%d-%m-%Y') as `date` from `fees` where `sadmission`='$sadmission' and `quarter`='Q4' and `FeesType` ='Hostel'");
										while($rowRcpt=mysqli_fetch_row($rsRcptNo))
										{
											$HostelReceiptNoQ4=$rowRcpt[0];
											$HostelReceiptDateQ4=$rowRcpt[1];
											break;
										}
										//echo $ReceiptNoQ4;
										//exit();
										
								$rsCheckQ4=mysqli_query($Con, "SELECT `amount` FROM `fees_student` WHERE `sadmission`='$sadmission' and `feeshead`='HOSTEL FORTH INSTALLMENT'");
								if (mysqli_num_rows($rsCheckQ4) > 0)
								{
									while($rowRcpt=mysqli_fetch_row($rsCheckQ4))
									{
										$HostelActualReceivedAmtQ4=$rowRcpt[0];
										break;
									}
								}
								$rsHostelPaidAmt=mysqli_query($Con, "select sum(`amountpaid`)-sum(`ActualLateFee`) from `fees` where `sadmission`='$sadmission' and `FeesType` ='Hostel' and cheque_status !='Bounce'");
								while($row = mysqli_fetch_row($rsHostelPaidAmt))
								{
									$TotalHostelAmountPaid=$row[0];
									break;
								}

								if(($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid)>0)
								{
									$HostelFeeAmtPaidQ4=$ToalHostelAmtPayableYearly-$TotalHostelAmountPaid;
								}
								if(($ToalHostelAmtPayableYearly-$TotalHostelAmountPaid) < 0)
								{
									$HostelFeeAmtPaidQ4=0;
								}
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
									FROM `fees_student` as `a` WHERE `sadmission`='$sadmission' and `feeshead`='TOTAL BILL AMOUNT'
									) as `x`
									) as `y`";
									$rsQ3RegularBalanceAmt=mysqli_query($Con, $ssql);
									$rowR=mysqli_fetch_row($rsQ3RegularBalanceAmt);
									if($rowR[9] !="")
									$HostelFeeAmtPaidQ4=$HostelFeeAmtPaidQ4-$rowR[9];
									
							$rsBalance=mysqli_query($Con, "select SUM(`BalanceAmt`) from `fees` where `sadmission`='$sadmission' and `FeesType`='Hostel'");
							$rowH=mysqli_fetch_row($rsBalance);
							$PreviousBalanceAmt=$rowH[0];
							$HostelFeeAmtPaidQ4=$HostelFeeAmtPaidQ4+$PreviousBalanceAmt;
							//echo $ToalHostelAmtPayableYearly."/".$TotalHostelAmountPaid."/".$HostelFeeAmtPaidQ4;
							//exit();
		
		
		///////////HOSTEL AMOUNT CALCULATION END**************
		
		
		
		//CHECK FEE SUBMISSION IN FEE TABLE FOR QUARTER Q4 IF FOUND THEN SHOW RECEIPT DETAIL OTHER WISE TO PAID AMOUNT WILL BE SHOWN//////////////////
		
		$rsTotalHostelAmt=mysqli_query($Con, "SELECT sum(`amount`) FROM `fees_student` WHERE `sadmission`='$sadmission' and `FeesType`='Hostel'");
		while($rowFPQ4=mysqli_fetch_row($rsTotalHostelAmt))
		{
			$ToalHostelAmtPayable=$rowFPQ4[0];
			break;
		}
		



			
		$rsAdvanceAmt=mysqli_query($Con, "select sum(`amount`) from `fees_student` where `sadmission`='$sadmission' and `feeshead`='Advances'");
		while($rowAdvance=mysqli_fetch_row($rsAdvanceAmt))
		{
			$AdvanceFeeAmtPaid=$rowAdvance[0];
			break;
		}

			  //$FeeAmtPaidQ4=($YearlyTotalFeeAmount-($FeeAmtPaidQ1+$FeeAmtPaidQ2+$FeeAmtPaidQ3+$AdvanceFeeAmtPaid));	
			  $FeeAmtPaidQ4=($YearlyTotalFeeAmount-($FeeAmtPaidQ1+$FeeAmtPaidQ2+$FeeAmtPaidQ3));	
			echo $YearlyTotalFeeAmount."/".$FeeAmtPaidQ1."/".$FeeAmtPaidQ2."/".$FeeAmtPaidQ3;
			exit();
			
			$ShowStaffChild="";
			if($DiscontType=="Staff Child")
			{
				$ShowStaffChild=" (".$DiscontType.")";
			}

	$FeeSubmissionQuarter="Q1";
	$AmountToBePaid=0;
	$FeeHeadName="";
	if($ReceiptNoQ1 =="")
	{
		$FeeSubmissionQuarter="Q1";
		$AmountToBePaid=$FeeAmtPaidQ1;
		$FeeHeadName="FEES FIRST INSTALLMENT";
	}
	else
	{
		if($ReceiptNoQ2 =="")
		{
			$FeeSubmissionQuarter="Q2";	
			$AmountToBePaid=$FeeAmtPaidQ2;
			$FeeHeadName="FEES SECOND INSTALLMENT";
		}
		else
		{
			if($ReceiptNoQ3 =="")
			{
				$FeeSubmissionQuarter="Q3";	
				$AmountToBePaid=$FeeAmtPaidQ3;
				$FeeHeadName="FEES THIRD INSTALLMENT";
			}
			else
			{
				$FeeSubmissionQuarter="Q4";	
				$AmountToBePaid=$FeeAmtPaidQ4;
				$FeeHeadName="FEES FOURTH INSTALLMENT";
			}		
		}		
	}


//************
$ssql="select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,`Status` from `fees_misc_announce` where `sclass`='$StudentClass' and `sadmission`='$sadmission'";
//echo $ssql;
//exit();
$rs = mysqli_query($Con, $ssql);
?>
<script language="javascript">
function fnlShowReceipt(headname,admissionid)
{
	var myWindow = window.open("ShowMiscReceipt.php?headername=" + escape(headname) + "&sadmission=" + escape(admissionid),"MsgWindow","width=700,height=700");
	return;
}
</script>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<title>Daily Classwork and Homework</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" href="layout/styles/layout.css" type="text/css" />



<script type="text/javascript" src="layout/scripts/jquery.min.js"></script>

<script type="text/javascript" src="layout/scripts/jquery.slidepanel.setup.js"></script>

<style>

<!--

.auto-style32 {



	border-color: #000000;



	border-width: 0px;



	border-collapse: collapse;



	font-family: Cambria;



}



.auto-style35 {



	border-style: solid;



	border-width: 1px;



	font-family: Cambria;



	text-align: center;



}











.style8 {



	border-style: solid;



	border-width: 1px;



	font-family: Cambria;



}







.auto-style1 {

	border-width: 1px;

	color: #000000;

	font-family: Cambria;

	font-size: 15px;

}



.auto-style2 {

	border-width: 1px;

	font-family: Cambria;

	font-size: 15px;

	font-style: normal;

	text-decoration: none;

	color: #000000;

}



.auto-style3 {

	color: #000000;

}

.style9 {
	border-width: 0px;
}

.style10 {
	text-align: center;
	font-family: Cambria;
}

.style11 {
	text-align: left;
	font-family: Cambria;
}

-->

</style>

</head>

<body>
<!-- ####################################################################################################### -->
<table width="100%" style="border-width: 0px"> 
<tr>
<td style="border-style: none; border-width: medium">

<div class="wrapper col1">

  <div id="header">

    <div id="logo">

      <h1><img src="../../Admin/images/logo.png" height="76" width="300" ></img></h1>
    </div>
    <div id="topnav">
      <ul>

        <li class="active"><a href="#">Home</a></li>

        <li><a href="Notices.php">Events and Notices</a></li>

        <li><a href="News.php">News</a></li>

		<li><a href="logoff.php">Logout</li>

        <li class="last"></li>

      </ul>

    </div>

    <br class="clear" />

  </div>

</div>

</div>


  
    

<!-- ####################################################################################################### -->



<div class="wrapper col2">

  <div id="breadcrumb">

    <ul>

      <li class="first">You Are Here</li>

      <li>�</li>

      <li><a href="index.php">Home</a></li>

      <li>�</li>

		<li class="current"><a href="#">School News</a></li>

    </ul>

  </div>

</div>





<!-- ######################################Div for News ################################################################# -->




<div class="wrapper col6">

  <div id="breadcrumb">

   

    <font size="3" face="cambria"><b><marquee> Welcome to School Information System ! </b></marquee></font>

    

  </div>

</div>



</td>



</tr>



</table>



<table width="100%" border="0">

			<tr>

				<td>

				

	 <div id="column">

      <div class="subnav">

        <h2><b><font face="Cambria" style="font-size: 14pt" color="#2A2B2F"><span lang="en-us">Navigation Section</span></font></b></h2>

        <ul>

          <h4><li><font face="Cambria" style="font-size: 12pt"><a href="Classwork.php">Classwork and Homework</a></font></li>
          
                   <li><font face="Cambria" style="font-size: 12pt"><a href="Attendance.php">Attendance</a></font></li>
                     <li><font face="Cambria" style="font-size: 12pt"><a href="MyFees.php">My Fees</a></li>

             <li><font face="Cambria" style="font-size: 12pt"><a href="Holiday.php">Holidays</a>

          </font>

          </li>

          <li><font face="Cambria" style="font-size: 12pt"><a href="Notices.php">Notices</a></font></li>

           <li><font face="Cambria" style="font-size: 12pt"><a href="ReportCard.php">Report Card</a></font></li>

            <li><font face="Cambria" style="font-size: 12pt"><a href="DateSheet.php">Datesheet</a></font></li>

           <li><font face="Cambria" style="font-size: 12pt"><a href="Timetable.php">Timetable</a></font></li>

			  <li><font face="Cambria" style="font-size: 12pt"><a href="SessionPlan.php">Session Plan</a></font></li>



             <li><font face="Cambria" style="font-size: 12pt"><a href="Assignment.php">Assignment</a></font></li>

              <li><font face="Cambria" style="font-size: 12pt"><a href="Directory.php">School Directory</a></font></li>

               <li><font face="Cambria" style="font-size: 12pt"><a href="Transport.php">Transport Details</a></font></li>

                <li><font face="Cambria" style="font-size: 12pt"><a href="SendQuery.php">Send A Query</a></font></li>

                <li><font face="Cambria" style="font-size: 12pt"><a href="../Gallery/index.php">Photo Gallery</a></li>

                <li><font face="Cambria" style="font-size: 12pt"><a href="MyProfile.php">My Profile</a></font></li>
                <li><font face="Cambria" style="font-size: 12pt"><a href="MyFees.php">My Fees</a></font></li>

            	</font>

            </h3>

          </li>

        </ul>

      </div>

    </div>

    </td>

    

    

<!-- #########################################Navigation TD Close ############################################################## -->    



<!-- #########################################Content TD Open ############################################################## -->    
				<td>
<div>

  <div>
    <div style="overflow: scroll; width: 1050px;">



	<p><u><b><font face="Cambria" color="#009900">Student Fees Details and 
	Payment</font></b></u></p>
	<p><b><font face="Cambria" color="#009900">Tuition Fees Fees 
	Payment</font></b></p>
	<table border="1" width="100%" style="border-collapse: collapse">
		<tr>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Sr No</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Fees Head</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Amount</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Quarter</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Status</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Last Date</font></b></td>
		</tr>
		<tr>
			<td height="32" class="style10">1.</td>
			<td height="32" class="style11">FEES FIRST INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ1 =="")
			{
				//echo $AmountToBePaid;
				echo $FeeAmtPaidQ1;
			}
			else
			{
				echo $ActualReceivedAmtQ1;
			}
			?>
			</td>
			<td height="32" class="style10">Q1</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ1 =="" && $FeeAmtPaidQ1>0)
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentUser.php" target="_blank" onsubmit="javascript:document.getElementById('btn1').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $FeeAmtPaidQ1;?>" id="btn1" />
			</form>
			<?php
			}
			else
			{
			
				//echo "$ReceiptNoQ1";
				echo "<a href='ShowRegularFeeReceipt.php?receiptno=".$ReceiptNoQ1."' target='_blank'>".$ReceiptNoQ1."</a>";
			}
			?>
			</td>
			<td height="32" class="style10">&nbsp;</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			2</td>
			<td height="32" class="style11">
						FEES SECOND INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ2 =="")
			{
				//echo $AmountToBePaid;
				echo $FeeAmtPaidQ2;
			}
			else
			{
				echo $ActualReceivedAmtQ2;
			}
			?>
			</td>
			<td height="32" class="style10">
			Q2</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ2 =="" && $FeeAmtPaidQ2>0)
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentUser.php" target="_blank" onsubmit="javascript:document.getElementById('btn2').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $FeeAmtPaidQ2;?>" id="btn2" onkeypress="javascript:this.disabled=true;"/>
			</form>
			<?php
			}
			else
			{
				//echo "$ReceiptNoQ2";
				echo "<a href='ShowRegularFeeReceipt.php?receiptno=".$ReceiptNoQ2."' target='_blank'>".$ReceiptNoQ2."</a>";
			}
			?>
			</td>
			<td height="32" class="style10">
			22-Jul-2015</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			3</td>
			<td height="32" class="style11">
						FEES THIRD INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ3 =="")
			{
				//echo $AmountToBePaid;
				echo $FeeAmtPaidQ3;
			}
			else
			{
				echo $ActualReceivedAmtQ3;
			}
			?>
			</td>
			<td height="32" class="style10">
			Q3</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ3 =="" && $FeeAmtPaidQ3>0)
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentUser.php" target="_blank" onsubmit="javascript:document.getElementById('btn3').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $FeeAmtPaidQ3;?>" id="btn3" />
			</form>
			<?php
			}
			else
			{
				//echo "$ReceiptNoQ3";
				echo "<a href='ShowRegularFeeReceipt.php?receiptno=".$ReceiptNoQ3."' target='_blank'>".$ReceiptNoQ3."</a>";
			}
			?>
			</td>
			<td height="32" class="style10">
			14-Oct-2015</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			4</td>
			<td height="32" class="style11">
						FEES FOURTH INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ4 =="")
			{
				//echo $AmountToBePaid;
				echo $FeeAmtPaidQ4;
			}
			else
			{
				echo $ActualReceivedAmtQ4;
			}
			?>
			</td>
			<td height="32" class="style10">
			Q4</td>
			<td height="32" class="style10">
			<?php
			if($ReceiptNoQ4 =="" && $FeeAmtPaidQ4>0)
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentUser.php" target="_blank" onsubmit="javascript:document.getElementById('btn4').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $FeeAmtPaidQ4;?>" id="btn4" onkeypress="javascript:this.disabled=true;"/>
			</form>
			<?php
			}
			else
			{
				//echo "$ReceiptNoQ4";
				echo "<a href='ShowRegularFeeReceipt.php?receiptno=".$ReceiptNoQ4."' target='_blank'>".$ReceiptNoQ4."</a>";
			}
			?>
			</td>
			<td height="32" class="style10">
			13-Jan-2016</td>
		</tr>
	</table>
	<?php
	if($StudentHostel=="Yes")
	{
	?>
	<p>&nbsp;</p>
	<p><b><font face="Cambria" color=#009900>Hostel Fees Payment</font></b></p>
	<table border="1" width="100%" style="border-collapse: collapse">
		<tr>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Sr No</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Fees Head</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Amount</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Quarter</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Status</font></b></td>
			<td bgcolor="#006400" align="center"><b>
			<font face="Cambria" color="#FFFFFF">Last Date</font></b></td>
		</tr>
		<tr>
			<td height="32" class="style10">1.</td>
			<td height="32" class="style11">HOSTEL FIRST INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
				echo $HostelFeeAmtPaidQ1;			
			?>
			</td>
			<td height="32" class="style10">Q1</td>
			<td height="32" class="style10">
			<?php
			if($HostelReceiptNoQ1=="")
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentHostel.php" target="_blank" onsubmit="javascript:document.getElementById('hbtn1').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $HostelFeeAmtPaidQ1;?>" id="hbtn1"/>
			</form>
			<?php
			}
			else
			{
				echo "Print $HostelReceiptNoQ1";
			}
			?>
			</td>
			<td height="32" class="style10">&nbsp;</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			2</td>
			<td height="32" class="style11">
						HOSTEL SECOND INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
				echo $HostelFeeAmtPaidQ2;
			?>
			</td>
			<td height="32" class="style10">
			Q2</td>
			<td height="32" class="style10">
			<?php
			if($HostelReceiptNoQ2=="")
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentHostel.php" target="_blank" onsubmit="javascript:document.getElementById('hbtn2').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $HostelFeeAmtPaidQ2;?>" id="hbtn2"/>
			</form>
			<?php
			}
			else
			{
				echo "Print $HostelReceiptNoQ2";
			}
			?>
			</td>
			<td height="32" class="style10">
			22-Jul-2015</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			3</td>
			<td height="32" class="style11">
						HOSTEL THIRD INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
				echo $HostelFeeAmtPaidQ3;
			?>
			</td>
			<td height="32" class="style10">
			Q3</td>
			<td height="32" class="style10">
			<?php
			if($HostelReceiptNoQ3=="")
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentHostel.php" target="_blank" onsubmit="javascript:document.getElementById('hbtn3').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $HostelFeeAmtPaidQ3;?>" id="hbtn3"/>
			</form>
			<?php
			}
			else
			{
				echo "Print $HostelReceiptNoQ3";
			}
			?>
			</td>
			<td height="32" class="style10">
			14-Oct-2015</td>
		</tr>
		<tr>
			<td height="32" class="style10">
			4</td>
			<td height="32" class="style11">
						HOSTEL FOURTH INSTALLMENT</td>
			<td height="32" class="style10">
			<?php
				echo $HostelFeeAmtPaidQ4;			
			?>
			</td>
			<td height="32" class="style10">
			Q4</td>
			<td height="32" class="style10">
			<?php
			if($HostelReceiptNoQ4=="")
			{
				//echo "Pay $AmountToBePaid";
			?>
			<form align="center" method="post" action="FeesPaymentHostel.php" target="_blank" onsubmit="javascript:document.getElementById('hbtn4').disabled=true;">
	             <input type="hidden" id="txtAdmissionNo" name="txtAdmissionNo" value="<?php echo $sadmission;?>">
	             <input type="Submit" value="Pay <?php echo $HostelFeeAmtPaidQ4;?>" id="hbtn4"/>
			</form>
			<?php
			}
			else
			{
				echo "Print $HostelReceiptNoQ4";
			}
			?>
			</td>
			<td height="32" class="style10">
			13-Jan-2016</td>
		</tr>
	</table>
	<?php
	}//End of If Condition for Student Hostel is Yes
	?>
	<p>&nbsp;</p>
	<p><b><font face="Cambria" color=#009900>Miscellaneous Fees Payment</font></b></p>
	
	<table class="style9">
	<tr>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Sr.No</font></b></td>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Head Name</font></b></td>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Amount</font></b></td>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Last Date</font></b></td>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Remarks</font></b></td>
			<td class="style8" style="width: 196px" align="center" bgcolor="#006400">
			<b><font color="#FFFFFF">Payment</font></b></td>
	</tr>
	<?php
	$rowcount=1;
	while($row=mysqli_fetch_row($rs))
	{
		$HeaderSrNo=$row[0];
		$HeadName=$row[1];
		$HeadAmount=$row[2];
		$sclass=$row[3];
		$LastDate=$row[4];
		$Remarks=$row[5];
		$Status=$row[6];
		$rsChk=mysqli_query($Con, "select * from `fees_misc_collection` where `sadmissionno`='$sadmission' and `HeadName`='$HeadName'");
		
	?>
	<tr>
			<td class="style8" style="width: 196px" align="center"><?php echo $rowcount;?>.</td>
			<td class="style8" style="width: 196px" align="center"><?php echo $HeadName;?></td>
			<td class="style8" style="width: 196px" align="center"><?php echo $HeadAmount;?></td>
			<td class="style8" style="width: 196px" align="center"><?php echo $LastDate;?></td>
			<td class="style8" style="width: 196px" align="center"><?php echo $Remarks;?></td>
			<td class="style8" style="width: 196px" align="center">
			<?php
			if (mysqli_num_rows($rsChk) == 0)
			{
				if($Status=="1")
				{
			?>
			<form align="center" method="post" action="FeesSubmit.php" target="_blank">
	             <input type="hidden" id="hHeaderSrNo" name="hHeaderSrNo" value="<?php echo $HeaderSrNo;?>">
	             <input type="Submit" value="Pay <?php echo $HeadAmount;?>"/>
			</form>
			<?php
				}
			}
			else
			{
			?>
			<input type="button" name="btnPrintReceipt" id="btnPrintReceipt" value="Print Receipt" onclick="javascript:fnlShowReceipt('<?php echo $HeadName;?>','<?php echo $sadmission;?>');">
			<?php
			}
			?>
			</td>
	</tr>
	<?php
	$rowcount=$rowcount+1;
	}
	?>
	</table>
	
	<p>&nbsp;</p>
	<p><u><b><font face="Cambria">Notes and Instructions:</font></b></u></p>
	<p><font face="Cambria"><i><b>- Total Fees Amount displayed does not include 
	the late fees charges (If applicable)</b></i></font></p>
<p><b><i><font face="Cambria">- Total Fees Amount displayed does not include 
previous Balance / Advances (if applicable)</font></i></b></p>
<p><b><i><font face="Cambria">- Please read online payment guide for any 
clarification --&gt; <a href="DPS fees payment guide.pptx">Click here to download 
Online Payment Guide</a></font></i></b><p><b><i><font face="Cambria">- Please 
write to us at <a href="mailto:support@eduworldtech.com">
support@eduworldtech.com</a> for any clarifications or kindly call at School 
Reception for further details</font></i></b></div>
		</td>
</tr>



</table>



<div class="wrapper col5">

  <div id="copyright" style="width: 100%; height: 58px">

    

    <p align="center">Powered By Eduworld Technologies LLP |   <a target="_blank" href="http://www.eduworldtech.com" title="Eduworld Technologies LLP">

	Education ERP Platform</a></p>

    <br class="clear" />

  </div>

</div>

</body>

</html>

