<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php include('Crypto.php')?>

<?php
$workingKey='60AE8AB913B39007E378338895160BE7';		//Working Key should be provided here.
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
	//echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$txnstatus=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref=$information[1];
		if($i==8)	$status_message=$information[1];
		if($i==0)	$txnid=$information[1];
		if($i==10)	$Ammount=$information[1];
	}

echo $txnstatus."/".$txnid."/".$Ammount;
//exit();
 ?>	
<?php
if($txnstatus=="Success")
	{
		if ($txnid != "")
		{
			$rsChk=mysqli_query($Con, "select * from `fees` where `TxnId`='$txnid'");
			if(mysqli_num_rows($rsChk)>0)
			{
				echo "<br><br><center><b>Fee Alreaddy Submitted!";
				exit();
			}
			
				
				$pamount=explode(".",$Ammount);
	   	
	   		$rsderail=mysqli_query($Con, "SELECT `finalamount` from `fees_temp` where TxnId='$txnid'");
	   		$rowamt=mysqli_fetch_row($rsderail);
	   		$sentamount=$rowamt[0];
	   	//echo $sentamount."/".$pamount[0];
	   	//exit();	
		   	if($pamount[0]!=$sentamount)
		   	{
		   		  echo "<h4>Your Transaction ID for this transaction is ".$txnid.".</h4>";
	          		echo "<h4>Bad Request!! Amount Tempered.</h4>";
	          		exit();
	
			}	


			
			$ssql="UPDATE `fees_temp` SET `TxnStatus`='$txnstatus',`PGTxnId`='$pgtxnno' where `TxnId`='$txnid'";
			mysqli_query($Con, $ssql) or die(mysqli_error($Con));
			$ssql="UPDATE `fees_transaction_temp` SET `TxnStatus`='$txnstatus',`PGTxnId`='$pgtxnno' where `TxnId`='$txnid'";
			mysqli_query($Con, $ssql) or die(mysqli_error($Con));
			
			
			if($txnstatus !="Success")
			{
				echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees.php'>here</a> to restart the process!";
				exit();
			}
			
				$ssqlFY="SELECT distinct `financialyear`,`year` FROM `FYmaster` where `Status`='Active'";
            $rsFY= mysqli_query($Con, $ssqlFY);
            $row4=mysqli_fetch_row($rsFY);
	        $SelectedFinancialYear=$row4[0];

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
	
			$PDFFileName=$NewReciptNo . ".pdf";
				$ssql="UPDATE `fees_temp` SET `receipt`='$NewReciptNo' where `TxnId`='$txnid'";
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));
				$ssql="UPDATE `fees_transaction_temp` SET `ReceiptNo`='$NewReciptNo' where `TxnId`='$txnid'";
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));
		
		$rsQuarterString=mysqli_query($Con, "select distinct `Month`,DATE_FORMAT(`FeesSubmissionLastDate`,'%d-%M-%Y') from `fees_master` where `Quarter`='$quarter'");
						$QuarterString="";
						$reccnt=1;
						while($rowQS = mysqli_fetch_row($rsQuarterString))
						{
							if($reccnt==1)
							{
								//$LastDtFeeDepositBank="10-".$rowQS[0]."-2015";
								$LastDtFeeDepositBank=$rowQS[1];
							}
							$QuarterString=$QuarterString.$rowQS[0]."-";
							$reccnt=$reccnt+1;
						}
						$QuarterString=substr($QuarterString,0,strlen($QuarterString)-1);
						
							$FeeFor=date("M", strtotime($LastDtFeeDepositBank));
						

		
		$rsSchoolDetail = mysqli_query($Con, "select `PREFIX`,`SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`AccountNo`,`AffiliationNo`,`SchoolNo`,`website` from `SchoolConfig` where `SchoolId`='$SchoolId' LIMIT 1");
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
		
		
			$currentdate=date("Y-m-d");
			$currentmonth=date("M");
			$currentYear=date("Y");
					
					
					
				$ssql="INSERT INTO `fees` (`sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`AdjustedLateFee`,`DiscountAmount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`chequeno`,`bankname`,`branch`,`cheque_date`,`cheque_status`,`DebitHead`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`) select `sadmission` ,`sname` ,`sclass`,`srollno`,`fees_amount`,`AdjustedLateFee`,`DiscountAmount`,`amountpaid`,`BalanceAmt`,`quarter`,`date`,`FinancialYear`,`status`,`receipt`,`finalamount`,`ReceiptFileName`,`PaymentMode`,`chequeno`,`bankname`,`branch`,`cheque_date`,`cheque_status`,`DebitHead`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus` from `fees_temp` where `TxnId`='$txnid'";
				mysqli_query($Con, $ssql) or die(mysqli_error($Con));

				$ssql1="INSERT INTO `fees_transaction` (`feeshead`,`headamount`,`sadmission`,`sname`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`DiscountAmount`,`Remarks`,`FinancialYear`,`finalamount`,`FeeMonth`,`FeeYear`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`quarter`,`PaidAmount`) select `feeshead`,`headamount`,`sadmission`,`sname`,`ReceiptNo`,`ReceiptDate`,`ActualLateFee`,`ActualDelayDays`,`AdjustedLateFee`,`AdjustedDelayDays`,`DiscountAmount`,`Remarks`,`FinancialYear`,`finalamount`,'$FeeFor',`FeeYear`,`TxnAmount`,`TxnId`,`PGTxnId`,`TxnStatus`,`quarter`,`PaidAmount` from `fees_transaction_temp` where `TxnId`='$txnid'";
				mysqli_query($Con, $ssql1) or die(mysqli_error($Con));
									
			$rsDetail=mysqli_query($Con, "select `receipt`,`sadmission`,`date`,`sname`,(select `sfathername` from `student_master` where `sadmission`=a.`sadmission`) as `sfathername`,`sclass`,`srollno`,`PaymentMode`,`chequeno`,`bankname`,`quarter`,`AdjustedLateFee`,`DiscountAmount`,`BalanceAmt`,`amountpaid`,`amountpaid`,`BalanceAmt` as `BalanceForward`,`FinancialYear` from `fees` as `a` where `TxnId`='$txnid'");
			$rsFeeHeadDeteail=mysqli_query($Con, "select `feeshead`,`headamount` from `fees_transaction` where `TxnId`='$txnid'");
			
			while($rowDetail = mysqli_fetch_row($rsDetail))
			{
				$Rreceipt=$rowDetail[0];
				$sadmission=$rowDetail[1];
				$date=$rowDetail[2];
				$sname=$rowDetail[3];
				$sfathername=$rowDetail[4];
				$sclass=$rowDetail[5];
				$srollno=$rowDetail[6];
				$PaymentMode=$rowDetail[7];
				$chequeno=$rowDetail[8];
				$bankname=$rowDetail[9];
				$quarter=$rowDetail[10];
				$AdjustedLateFee=$rowDetail[11];
				$DiscountAmount=$rowDetail[12];
				$BalanceAmt=$rowDetail[13];
				$amountpaid=$rowDetail[14];
				$amountpaid=$rowDetail[15];
				$BalanceForward=$rowDetail[16];
				$FinancialYear=$rowDetail[17];
				break;
			}
			$sqlStudentDetail = "select `sfathername` from  `student_master` where `sadmission`='$sadmission'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);

		while($rows = mysqli_fetch_row($rsStudentDetail))
		{
			$FatherName=$rows[0];
			//$SchoolId=$rows[1];
			break;
		}
				
			
					
			//-------------------- Previous Payment history----------------------------------------------------------
				$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear` FROM `fees` where `sadmission`='$sadmission' order by `FinancialYear`,`quarter` desc limit 2";
				$rs = mysqli_query($Con, $ssql);
	}
	else
	{
		echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees.php'>here</a> to restart the process!";
		exit();	}	

}
else
	{
		echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees.php'>here</a> to restart the process!";
		exit();	
	}	


//Previous Payment History***
$ssqlFeePaymentDetail="SELECT `srno`,`sadmission`, `sname`, `sclass`, `srollno`, `fees_amount`, `PayableAfterDiscount`, `ActualLateFee`, `AdjustedLateFee`, `cheque_bounce_amt`, `finalamount`, `amountpaid`, `BalanceAmt`, `quarter`, `FinancialYear`, `status`, `receipt`, `date`, `datetime`, `refundamount`, `refunddate`, `cancelamount`, `canceldate`, `ReceiptFileName`, `FeeReceiptCode`, `PaymentMode`, `chequeno`, `cheque_date`, `bankname`, `cheque_status`, `ActualDelayDays`, `AdjustedDelayDays`, `Remarks`, `FeesType`, `SendToBank`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId` FROM `fees` WHERE   `sadmission`='$sadmission'";
$FeePaymentDetail=mysqli_query($Con, $ssqlFeePaymentDetail);

?>



<script language="javascript">



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



			        	



			        	document.getElementById("txtTuition").value=TutionFee;



			        	document.getElementById("txtTransportFees").value=TransportFee;



			        	document.getElementById("txtPreviousBalance").value=BalanceAmt;



			        	document.getElementById("txtLateDays").value =LateDays;



			        	//alert("TutionFee:" + TutionFee + ",Transport Fee:" + TransportFee + ",Balance Amt:" + BalanceAmt);



			        	//document.getElementById("txtStudentName").value=rows;



			        	



			        	//ReloadWithSubject();



						//alert(rows);														



			        }



		      }



			



			var submiturl="GetFeeDetail.php?Quarter=" + document.getElementById("cboQuarter").value + "&Class=" + document.getElementById("txtClass").value + "&sadmission=" + document.getElementById("txtAdmissionNo").value;



			xmlHttp.open("GET", submiturl,true);



			xmlHttp.send(null);







}







function printDiv() 

{

        //Get the HTML of div

        var divElements = document.getElementById("MasterDiv").innerHTML;

        //Get the HTML of whole page

        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only

        document.body.innerHTML = "<html><head><title></title></head><body>" + 

          divElements + "</body>";

        //Print Page

        window.print();

        //Restore orignal HTML

        document.body.innerHTML = oldPage;

 }



function CreatePDF() 

{

       //Get the HTML of div

        var divElements = document.getElementById("MasterDiv").innerHTML;

        //Get the HTML of whole page

        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only

        //document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";

		//document.frmPDF.htmlcode.value = "<html><head><title></title></head><body>" + divElements + "</body>";

		document.frmPDF.htmlcode.value = divElements;

		//alert(document.frmPDF.htmlcode.value);

		//document.frmPDF.submit;

		document.getElementById("frmPDF").submit();

		//document.all("frmPDF").submit();

		return;

		//alert(document.getElementById("htmlcode").value);		 

        //Print Page

        //window.print();

        //var FileLocation="http://emeraldsis.com/Admin/Fees/CreatePDF.php?htmlcode=" + escape(document.body.innerHTML);

		//window.location.assign(FileLocation);

		//return;

}

 



</script>







<html>















<head>







<meta http-equiv="Content-Language" content="en-us">







<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">







<title>Fees Reciept Generation</title>







<!-- link calendar resources -->







	<link rel="stylesheet" type="text/css" href="tcal.css" />







	<script type="text/javascript" src="tcal.js"></script>



</head>

<body >

<div id="MasterDiv">
<style type="text/css">
.style1 {
	text-align: center;
}
.style2 {
	font-size: 12pt;
}
.style3 {
	text-align: right;
}
.style4 {
	border-collapse: collapse;
}
</style>
<form name="frmFees" id="frmFees" method="post" action="FeesPayment.php">
	<div style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
		<table id="table_11" cellspacing="0" cellpadding="0" width="100%" class="style4">
			<tr>
				<td style="border-style:none; border-width:medium; height: 13px"  colspan="10" class="style1" align="center">
				<font face="Cambria" class="style2"><strong><?php echo $SchoolName; ?><br><?php echo $SchoolAddress; ?></strong></font></td>
			</tr>
			<tr>
				<td style="border-style:solid; border-width:1px; width: 100px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><strong>Fees Receipt No. </strong>
				</font></td>
				<td style="border-style:solid; border-width:1px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="3" >
				<font face="Cambria" size="2">
				<?php echo $NewReciptNo; ?></font></td>
				<td style="border-style:solid; border-width:1px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="2" >
				&nbsp;</td>
				<td style="border-style:solid; border-width:1px; height: 25px; width: 100px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px">
				<b>
				<font face="Cambria" size="2">Receipt Date</font></b></td>
				<td style="border-style:solid; border-width:1px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="3" >
				<font face="Cambria" size="2"><strong>&nbsp;<?php echo date("d-m-Y"); ?></strong></font></td>
			</tr>
			<tr>
				<td style="border-style:solid; border-width:1px; width: 100px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria"><b><span ><font style="font-size: 10pt">Adm No.
				</font></span></b></font>
				<span style="font-family: Cambria; font-weight: 700; " >
				<font style="font-size: 10pt">:</font></span></td>
				<td style="width: 138px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b>



		<?php echo $sadmission; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b><span >Name 
				</span></b></font></td>
				<td style="width: 138px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b>



		<?php echo $sname; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b><span >Father's Name</span></b></font></td>
				<td style="width: 145px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<?php echo $sfathername; ?></td>
				<td style="height: 25px; width: 100px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px">
				<p ><font face="Cambria" style="font-size: 10pt"><strong>Class</strong></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b>



		<?php echo $sclass; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<span style="font-family: Cambria; font-weight: 700; " >
				<font style="font-size: 10pt">Roll No</font></span></td>
				<td style="height: 25px; width: 100px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" style="font-size: 10pt"><b>



		<?php echo $RollNo; ?></b></font></td>
			</tr>
			<tr>
				<td style="border-style:solid; border-width:1px; width: 100px; height: 25px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<b>
				<font face="Cambria" size="2">Mode Of Payment</font></b></td>
				<td style="width: 138px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" size="2">
				<b>
				<?php echo "Online"; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<p align="center"><b>
				<font face="Cambria" size="2">Cheque /DD #/ Txn Id</font></b></td>
				<td style="width: 138px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
						<font face="Cambria" size="2">
						<b>
						<?php echo $txnid; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<b>
				<font face="Cambria" size="2">Cheque Date</font></b></td>
				<td style="width: 145px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				&nbsp;</td>
				<td style="height: 25px; width: 100px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px">
				<b>
				<font face="Cambria" size="2">Bank name</font></b></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				<font face="Cambria" size="2">
						<b>
						<?php echo $BankName; ?></b></font></td>
				<td style="width: 100px; height: 25px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				&nbsp;</td>
				<td style="height: 25px; width: 100px; border-left-style:solid; border-left-width:1px; border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
				&nbsp;</td>
			</tr>
		</table><font face="Cambria" style="font-size: 10pt">

	



	

	

	

	</span>





	

	

	

		</font>
		</div>
</form>

<table class="CSSTableGenerator" border="1" style="align:center; width: 100%; border-collapse:collapse">

		
<tr>			
		

		<td style="height: 29px;" class="style13" colspan="14">

		<p style="text-align: center"><b>
		<font face="Cambria" style="font-size: 10pt">Fees Receipt Details</font></b></td>

			</tr>
		
<tr>			
		

		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<strong>Adm #</strong></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Name</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Class</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<strong>Receipt #</strong></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Fees Amt</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Late Fees</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Bounce Amt</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Total Amount Paid</b></font></td>


		<td style="height: 16px; width: 100px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Fees Inst Paid</b></font></td>


		<td style="height: 16px; width: 101px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Payment Mode</b></font></td>


		<td style="height: 16px; width: 101px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<b>Txn Id / Chq No</b></font></td>


		<td style="height: 16px; width: 101px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<strong>Chq Status</strong></font></td>


		<td style="height: 16px; width: 101px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<strong>Payment Date</strong></font></td>


		<td style="height: 16px; width: 101px; text-align:center" class="style12">

		<font face="Cambria" size="2">

		<strong>Financial Year</strong></font></td>


			</tr>
<?php
while($row = mysqli_fetch_row($FeePaymentDetail))
	{
				
				$Admission=$row[1];
				$Name=$row[2];
				$Class=$row[3];
                $fees_amount=$row[5];
                $InstallmentAmount=$row[6];
                $late_fees=$row[7];
                $bounce_amount=$row[9];
                $final_amount=$row[10];
                $amountpaid=$row[11];
				$PaymentMode=$row[25];
				$chequeno=$row[26];
				$cheque_status=$row[29];
				$receipt=$row[16];
				$date=$row[17];
				$FinancialYear=$row[14];		
				$AdjustedLateFee=$row[9];		
				$chequestatus=$row[29];
				$txn_id=$row[36];	
					
?>
<tr>			
		

		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $Admission; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $Name; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $Class; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $receipt; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<p style="text-align: center"><font face="Cambria" size="2"><?php echo $fees_amount; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<p style="text-align: center"><font face="Cambria" size="2"><?php echo $late_fees; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<p style="text-align: center"><font face="Cambria" size="2"><?php echo $bounce_amount; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $amountpaid; ?></font></td>


		<td style="width: 100px; height: 20px;" class="style12">

		<font face="Cambria" size="2">

		<?php echo $InstallmentAmount; ?></font></td>


	
		<td style="width: 100px; height: 20px;" class="style12">

		<p style="text-align: center"><font face="Cambria" size="2"><?php echo $PaymentMode; ?></font></td>


		


		<td style="width: 101px; height: 20px;" class="style17">

		<font face="Cambria" size="2">

		<?php echo $txn_id; ?></font></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<font face="Cambria" size="2">

		<?php echo $chequestatus; ?></font></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<font face="Cambria" size="2">

		<?php echo $date; ?></font></td>


		<td style="width: 101px; height: 20px;" class="style17">

		<font face="Cambria" size="2">

		<?php echo $FinancialYear; ?></font></td>


			</tr>
<?php
}
?>

</table>
<table width="100%" cellpadding="0" style="border-collapse: collapse" >
	
		
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
	<?php
		}
	}
	?>
<?php
}
?>
		

	



	<tr>
		<td>
		<p align="right"><font face="Cambria" style="font-size: 10pt"><em>
		For any queries, Kindly call at 

		: 

		</span>



		</em></font><em><font style="font-size: 10pt">
		<span style="font-family: Cambria; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none"><?php echo $SchoolPhoneNo; ?> or drop an email at

		</span></font>
		<span style="color: rgb(204, 51, 0); font-family: Cambria; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none; font-style:normal">
		<span >
		<font style="font-size: 10pt"><?php echo $AccountsEmailId; ?></font></span></a></span></em><span style="font-family: Cambria; font-size: 10pt; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none">
		<br>(Fees Incharge)</span></td>
	</tr>
</table>
<form name="frmPDF" id="frmPDF" method="post" action="StorePDF.php">
	<span style="font-size: 11pt">
	<input type="hidden" name="htmlcode" id="htmlcode" value="tesing">
	<input type="hidden" name="txtpdffilename" id="txtpdffilename" value="<?php echo $PDFFileName; ?>">
	<input type="hidden" name="receiptno" id="receiptno" value="<?php echo $NewReciptNo;?>">
	</span>
</form>	
</div>


<div id="divPrint">

	<p align="center">



	<font face="Cambria" style="font-size: 10pt">



	<a href="Javascript:printDiv();"><span >PRINT</span></a> || <a href="MyFees.php"><span >HOME</span></a>



	</font>



	</div>

</body>

</html>
