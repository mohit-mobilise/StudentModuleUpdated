<?php
session_start();
include '../AppConf.php';

$refURL = $_SERVER['HTTP_REFERER'];
$baseURL1 = $BaseURL."Users/MyFees_prev_year.php";

if($refURL == $baseURL1)
{
    include '../connection_2019.php';
}
else
{
    include '../connection.php';
}


?>
<?php
$ReceiptNo=$_REQUEST["Receipt"];

		$sqlReceiptDetail = "SELECT `srno`, `sadmission`, `sname`, `sclass`, `MasterClass`, `srollno`, `fees_amount`, `PayableAfterDiscount`, `AdjustedLateFee`, `AdjustedDelayDays`, `cheque_date`, `DiscountAmount`, `amountpaid`, `BalanceAmt`, `finalamount`, `quarter`, `FinancialYear`, `status`, `receipt`, `PreviousReceiptNo`,DATE_FORMAT(`date`,'%d-%m-%Y') as  `date`, `datetime`, `refundamount`, `refunddate`, `cancelamount`, `canceldate`, `ReceiptFileName`, `FeeReceiptCode`, `PaymentMode`, `chequeno`, `bankname`, `branch`, `cheque_bounce_amt`, `ActualLateFee`, `ActualDelayDays`, `Remarks`, `cheque_status`, `DebitHead`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `MICRCode`, `FeeAmountWithLateFee`, `PreviousBalance`, `FeesType`, `TransCode`, `cheque_bounce_date`,`FeeMonth` FROM `fees`  where `receipt`='$ReceiptNo'";
				$rsReceiptDetail = mysqli_query($Con, $sqlReceiptDetail);

		while($row1 = mysqli_fetch_row($rsReceiptDetail))
		{
                                 $srno=$row1[0];
			$sadmission=$row1[1];
			$sname=$row1[2];
			$sclass=$row1[3];
			$MasterClass=$row1[4];
			$srollno=$row1[5];
			$fees_amount=$row1[6];
			$PayableAfterDiscount=$row1[7];
			$AdjustedLateFee=$row1[8];
			$AdjustedDelayDays=$row1[9];
			$cheque_date=$row1[10];
			$DiscountAmount=$row1[11];
			$amountpaid=$row1[12];
			$BalanceAmt=$row1[13];
			$finalamount=$row1[14];
			$quarter=$row1[15];
			$FinancialYear=$row1[16];
			$status=$row1[17];
			$receipt=$row1[18];
			$PreviousReceiptNo=$row1[19];
			$date=$row1[20];
			$datetime=$row1[21];
			$refundamount=$row1[22];
			$refunddate=$row1[23];
			$cancelamount=$row1[24];
			$canceldate=$row1[25];
			$ReceiptFileName=$row1[26];
			$FeeReceiptCode=$row1[27];
			$PaymentMode=$row1[28];
			$chequeno=$row1[29];
			$bankname=$row1[30];
			$branch=$row1[31];
			$cheque_bounce_amt=$row1[32];
			$ActualLateFee=$row1[33];
			$ActualDelayDays=$row1[34];
			$Remarks=$row1[35];
			$cheque_status=$row1[36];
			$DebitHead=$row1[37];
			$TxnAmount=$row1[38];
			$TxnId=$row1[39];
			$TxnStatus=$row1[40];
			$PGTxnId=$row1[41];
			$MICRCode=$row1[42];
			$FeeAmountWithLateFee=$row1[43];
			$PreviousBalance=$row1[44];
			$FeesType=$row1[45];
			$TransCode=$row1[46];
			$cheque_bounce_date=$row1[47];
			$FeeMonth=$row1[48];
                                    break;

                   }
		$sqlStudentDetail = "select `sfathername`,`MotherName`,`smobile`,`DiscontType`,DATE_FORMAT(`DOB`,'%d-%m-%Y') as `DOB`,`Address`,`sname`,`sclass`,`srollno`,`MasterClass`,`FinancialYear` from  `student_master` where `sadmission`='$sadmission'";
		$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);

		while($rows = mysqli_fetch_row($rsStudentDetail))
		{
			$FatherName=$rows[0];
			$MotherName=$rows[1];
			$Mobile=$rows[2];
			$StDiscountType=$rows[3];
			if($rows[4]=="00-00-0000")
			$DOB="";
			else
			$DOB=$rows[4];
			$Address=$rows[5];
			$sname=$rows[6];
			$sclass=$rows[7];
			$MasterClass=$rows[9];
			$srollno=$rows[8];
			//$SchoolId=$rows[1];
			$StudentFinancialYear=$rows[10];

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
		

//-------------------- Previous Payment history----------------------------------------------------------
	$ssql = "SELECT `quarter`,`fees_amount`,`amountpaid`,`BalanceAmt`,`status`,`receipt`,date_format(`date`,'%d-%m-%Y') as `date`,`FinancialYear`,`finalamount` FROM `fees` where `sadmission`='$AdmissionNo' and `FeesType`='Regular' order by `quarter`,`FinancialYear` desc limit 2";
	$rs = mysqli_query($Con, $ssql);	
?>

<script language="javascript">


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
<body  >
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
	<table id="table_11" cellspacing="0" cellpadding="0" width="100%" class="style4">
		<tr>
			<td style="height: 13px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:solid; border-top-width:1px; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:solid; border-top-width:1px; border-bottom-style:none; border-bottom-width:medium"  colspan="7" class="style1" align="center">
			&nbsp;</td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:solid; border-top-width:1px; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
		</tr>
		<tr>
			<td style="height: 13px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			<font face="Cambria" class="style2" style="font-size: 10pt"><strong>
			<img src="../Admin/images/logo.png" width ="150" height="50"></strong></font></td>
			<td style="border-style:none; border-width:medium; height: 13px; "  colspan="7" class="style1" align="center">
			<b><font face="Cambria" style="font-size: 10pt">
			<?php echo $SchoolName; ?><br></font></b>
			<font face="Cambria">
			<span style="font-size: 10pt"><?php echo $SchoolAddress; ?></span></font></td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
		</tr>
		<tr>
			<td style="height: 13px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
			<td style="border-style:none; border-width:medium; height: 13px; "  colspan="7" class="style1" align="center">
			<font face="Cambria" class="style2"><strong>
			<span style="font-size: 10pt">CBSE Affiliation No : <?php echo $AffiliationNo; ?></span></strong></font></td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
		</tr>
		<tr>
			<td style="height: 13px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
			<td style="border-style:none; border-width:medium; height: 13px; "  colspan="7" class="style1" align="center">
			<font face="Cambria" class="style2"><strong>
			<span style="font-size: 10pt">School Recognition No. :<?php echo $Recognition; ?></span></strong></font></td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" class="style1" align="center">
			&nbsp;</td>
		</tr>
		<tr>
			
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px"  colspan="9" align="center">
			
			<font face="Cambria" style="font-size: 10pt; font-weight:700">Fees 
			Receipt for Session : <?php echo $FinancialYear; ?>(Month: <?php echo $FeeMonth;?>)</font></td>
			<td style="height: 13px; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" width="5">
			&nbsp;</td>
		</tr>
		<tr>
			<td style="height: 13px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px"  colspan="2">
			<font face="Cambria" size="1"><strong>Fees Receipt No. <?php echo $ReceiptNo; ?></strong>
			</font></td>
			<td style="height: 13px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px"  colspan="7">
			<p align="right">
			<font face="Cambria" style="font-weight: 700" size="1">Date: <?php echo $date; ?></font></td>
			
		</tr>
		<tr>
			<td style="width: 163px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<font face="Cambria"><b><span ><font size="1">Adm. No.
				</font></span></b></font></td>
			<td style="border-style:none; border-width:medium; width: 293px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font face="Cambria" size="1"><b>
				<?php echo $sadmission; ?></b></font></td>
			<td style="border-style:none; border-width:medium; width: 204px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font face="Cambria" size="1"><b><span >Name 
				</span></b></font></td>
			<td style="border-style:none; border-width:medium; width: 205px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="4" >
			<font face="Cambria" size="1">
			<?php echo $sname; ?></font></td>
			<td style="border-style:none; border-width:medium; width: 129px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font face="Cambria" size="1"><b><span >Father's Name</span></b></font></td>
			<td style="width: 294px;  padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-bottom-style:none; border-bottom-width:medium; border-top-style:none; border-top-width:medium" >
			<font face="Cambria" size="1">
			<?php echo $FatherName; ?></font></td>
		</tr><font face="Cambria" style="font-size: 8pt"></span
		</font>
		</font><font face="Cambria" style="font-size: 10pt">
		<tr>
			<td style="width: 163px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<font face="Cambria" size="1"><strong>Class</strong></font></td>
			<td style="border-style:none; border-width:medium; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" width="293"  >
			<font face="Cambria" size="1">
			<?php echo $sclass;?></font></td>
			<td style="border-style:none; border-width:medium; width: 204px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font style="font-weight: 700" face="Cambria" size="1">Date Of Birth</font></td>
			<td style="border-style:none; border-width:medium; width: 205px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="4" >
			<font face="Cambria" size="1">
			<?php echo $DOB;?></font></td>
			<td style="border-style:none; border-width:medium; width: 129px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font style="font-weight: 700" face="Cambria" size="1">Mother's Name</font></td>
			<td style="width: 294px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium"  >
			<font face="Cambria" size="1">
			<?php echo $MotherName; ?></font></td>
		</tr>
		<tr>
			<td style="width: 163px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<font style="font-weight: 700" face="Cambria" size="1">Address</font></td>
			<td style="border-style:none; border-width:medium; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" width="293"  >
			<font face="Cambria" size="1">
				<?php echo $Address;?></font></td>
			<td style="border-style:none; border-width:medium; width: 204px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font style="font-weight: 700" face="Cambria" size="1">Mobile No</font></td>
			<td style="border-style:none; border-width:medium; width: 205px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" colspan="4" >
			<font face="Cambria" size="1">
			<?php echo $Mobile; ?></font></td>
			<td style="border-style:none; border-width:medium; width: 129px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" >
			<font face="Cambria" size="1">
			<span style="font-weight: 700">Concession</span></td>
			<td style="width: 294px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium"  >
			<font style="font-weight: 700" face="Cambria" size="1">
				<?php echo $StDiscountType; ?></font></td>
		</tr>
		<tr>
			<td style="width: 163px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" >
			<font face="Cambria" size="1"><b>Mode Of Payment</b></font></td>
			<td style="padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" width="293"  >
			<font face="Cambria" size="1"><b><?php echo $PaymentMode; ?></b></font></td>
			<td style="width: 204px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" >
			<font face="Cambria" size="1"><strong>Cheque / D.D. No.</strong></font></td>
			<td style="width: 205px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="4" >
			<font face="Cambria" size="1">
			<?php echo $chequeno;?></font> &nbsp;&nbsp; <?php if($chequeno!="")?><font face="Cambria" size="1" style="<?php if($cheque_status=="Bounce"){ ?> color:red; <?php } else { ?>  color:green; <?php } ?>"><b> <?php {echo ($cheque_status);} ?> </b></font></td>
			<td style="width: 129px;padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" >
			<font face="Cambria" size="1"><strong><span >Bank Name</span></strong></font></td>
			<td style="width: 294px; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px"  >
			<font face="Cambria" size="1">
			<?php echo $bankname; ?></font></td>
		</tr>




				<tr>
					<td width="1947" style="border-right-style:solid; border-right-width:1px; border-bottom-style:solid; border-bottom-width:1px" colspan="9" >
					<p align="center">
					<font face="Cambria" style="font-size: 10pt; font-weight: 700; text-decoration: underline">
					Fees Collection Details</font></td>
				</tr>
		
				<tr>
					<td width="658" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="2" height="16" >
					<p align="center">
					<font face="Cambria" size="1">
					<span style="font-weight:700">Fee Head</span></font></td>
					<td width="204" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" height="17" >
					<font face="Cambria" size="1">
					<p align="center">
					<span style="font-weight:700">Original Amount</span></td>
					<td width="103" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" height="17" >
					<font face="Cambria" size="1" style="font-size: 10pt">
					<span style="font-weight:700; font-size:8pt">Concession</span></font></td>
					<td width="103" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" height="17" >
					<p align="center">
					<font face="Cambria" size="1" style="font-weight: 700">
					<span style="font-weight:700">
					Previous Balance </span></td>
					<td width="102" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" height="17" >
					<font size="1" face="Cambria"><span style="font-weight: 700">
					Adhoc Concession</span></font></td>
					</font>
					<td width="274" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" colspan="2" height="17" >
					<span style="font-weight:700">
					<font face="Cambria" size="1" style="font-size: 8pt; font-weight:700">
					<span>Amount After Concession&nbsp;</span></font></span></td>
					<td width="302" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-bottom-style:none; border-bottom-width:medium; border-top-style:solid; border-top-width:1px" height="17" >
					<span style="font-weight:700">
					<font face="Cambria" size="1" style="font-size: 8pt; font-weight:700">
					<span>Amount Paid</span></font></span></td>
				</tr>
				<font face="Cambria" style="font-size: 10pt">
				<?php
				$rsFeeTransDetails=mysqli_query($Con, "SELECT `srno`, `feeshead`, `headamount`, `DiscountAmount`, `finalamount`, `PaidAmount`, `sadmission`, `sname`, `ReceiptNo`, `ReceiptDate`, `ActualLateFee`, `ActualDelayDays`, `AdjustedLateFee`, `AdjustedDelayDays`, `FeeMonth`, `FeeYear`, `Remarks`, `bankname`, `ChequeNo`, `ChequeDate`, `cancelamount`, `canceldate`, `FinancialYear`, `cheque_bounce_amt`, `datetime`, `TxnAmount`, `TxnId`, `TxnStatus`, `PGTxnId`, `PBalanceReceiptNo`, `PreviousBalance`, `PaidBalanceAmt`, `CurrentBalance`, `FeeReceiptCode`, `quarter`, `AdhocDiscountAmt`, `Balance`, `cheque_status`, `PaymentMode` FROM `fees_transaction` WHERE `ReceiptNo`='$ReceiptNo'");				
				while($row2 = mysqli_fetch_row($rsFeeTransDetails))
						{
					$FeeTranssrno=$row2[0];
					$FeeTransfeeshead=$row2[1];
					$FeeTransheadamount=$row2[2];
					$FeeTransDiscountAmount=$row2[3];
					$FeeTransfinalamount=$row2[4];
					$FeeTransPaidAmount=$row2[5];
					$FeeTranssadmission=$row2[6];
					$FeeTranssname=$row2[7];
					$FeeTransReceiptNo=$row2[8];
					$FeeTransReceiptDate=$row2[9];
					$FeeTransActualLateFee=$row2[10];
					$FeeTransActualDelayDays=$row2[11];
					$FeeTransAdjustedLateFee=$row2[12];
					$FeeTransAdjustedDelayDays=$row2[13];
					$FeeTransFeeMonth=$row2[14];
					$FeeTransFeeYear=$row2[15];
					$FeeTransRemarks=$row2[16];
					$FeeTransbankname=$row2[17];
					$FeeTransChequeNo=$row2[18];
					$FeeTransChequeDate=$row2[19];
					$FeeTranscancelamount=$row2[20];
					$FeeTranscanceldate=$row2[21];
					$FeeTransFinancialYear=$row2[22];
					$FeeTranscheque_bounce_amt=$row2[23];
					$FeeTransdatetime=$row2[24];
					$FeeTransTxnAmount=$row2[25];
					$FeeTransTxnId=$row2[26];
					$FeeTransTxnStatus=$row2[27];
					$FeeTransPGTxnId=$row2[28];
					$FeeTransPBalanceReceiptNo=$row2[29];
					$FeeTransPreviousBalance=$row2[30];
					$FeeTransPaidBalanceAmt=$row2[31];
					$FeeTransCurrentBalance=$row2[32];
					$FeeTransFeeReceiptCode=$row2[33];
					$FeeTransquarter=$row2[34];
					$FeeTransAdhocDiscountAmt=$row2[35];
					$FeeTransBalance=$row2[36];
					$FeeTranscheque_status=$row2[37];
					$FeeTransPaymentMode=$row2[38];

										
				?>
				<tr>
					<td width="658" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="2" >
					<p align="left">
					<font face="Cambria" size="1">
					<span style="font-weight:700"><?php if($FeeTransfeeshead=="Tuition Fees*"){ echo "Tuition Fees";} else {echo $FeeTransfeeshead;}?></span></font></td>
					<td width="204" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt">
					<?php echo $FeeTransheadamount;?></font></td>
					<td width="103" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt">
					<?php echo $FeeTransDiscountAmount;?></font></td>
					<td width="103" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt"><?php echo $FeeTransBalance;?></font></td>
					<td width="204" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt">
					<?php echo $FeeTransAdhocDiscountAmt;?></font></td>

					
					<td width="274" align="center" style="border-style: none; border-width: medium; " colspan="2" >
					<font face="Cambria" size="1" style="font-size: 10pt">
					<span style="font-size:8pt"><?php echo $FeeTransfinalamount;?></span></font></td>

					
					<td width="302" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
					<span style="font-weight:700">
					<font face="Cambria" style="font-size: 8pt"><?php echo $FeeTransPaidAmount;?></font></span></td>
				</tr>
				<?php
					}
				?>
				<tr>
					<td width="1506" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="9" height="19" >
					<font face="Cambria" style="font-size: 10pt">
					<hr width ="98%"></font></td>
				</tr>
		<tr>
					<td width="658" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="2" >
					<p align="center">
					<font face="Cambria" size="1">
					<span style="font-weight:700">Total</span></font></td>
					<td width="204" align="center" style="border-style: none; border-width: medium; " >
					<b>
					<font face="Cambria" style="font-size: 8pt">
					<?php echo $TotalFeeHeadOrigAmount;?></font></b></td>
					<td width="103" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt">
					<?php echo $TotalFeeHeadDiscountAmt;?></font></td>
					
					<td width="103" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt"><b><?php echo $TotalFeeHeadPreviousBalance;?></b></font></td>
					
						<td width="103" align="center" style="border-style: none; border-width: medium; " >
					<font face="Cambria" style="font-size: 8pt"><b><?php echo $AdhocTotalDiscount;?></b></font></td>
					
						<td width="240" align="center" style="border-style: none; border-width: medium; " colspan="2" >
				<font face="Cambria" style="font-size: 8pt">
					<span style="font-weight:700">
					<font face="Cambria"><?php echo $TotalFeeHeadAfterDiscountValue;?></font></span></font></td>
					<td width="302" align="center" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
					<span style="font-weight:700">
					<font face="Cambria" style="font-size: 8pt"><?php echo $TotalHeadActualPaidAfterDiscountAmount;?></font></span></td>
				</tr>
				<tr>
					<td width="1506" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="9" >
					<font face="Cambria" style="font-size: 10pt">
					<hr width ="98%"></font></td>
				</tr>				
			
			</td>
		</tr>
		<?php
		if($cheque_bounce_amt>0)
		{
		?>
		<tr>
			<td  align="center" width="641" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="7">
			&nbsp;</td>
			<td  align="center" width="137" style="border-style: none; border-width: medium; " >
			<font size="1">Cheque Bounce Charges</font></td>
			<td  align="center" width="302" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
						<font face="Cambria" style="font-weight:700" size="1"><?php echo $cheque_bounce_amt;?></font></td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td  align="center" width="641" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="7">
			<font face="Cambria" size="1">
			&nbsp;</td>
			<td  align="center" width="137" style="border-style: none; border-width: medium; " >
			<font face="Cambria">
			<p align="right">
					<font face="Cambria" style="font-weight:700" size="1">Late 
					Fees</font></td>
			<td  align="center" width="302" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<font face="Cambria" style="font-weight:700" size="1"><?php echo $AdjustedLateFee;?></font></td>
		</tr>
		<!--
		<tr>
			<td  align="center" width="137" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium" >
			<font face="Cambria" size="1">
			<p align="right">
					<span style="font-weight:700">Adhoc Discount</span></td>
			<td  align="center" width="302" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<font face="Cambria" style="font-weight:700" size="1">
			<?php echo $AdhocTotalDiscount;?></font></td>
		</tr>
		-->
		<tr>
			<td  align="center" width="441" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" colspan="2" >
			&nbsp;</td>
			<td  align="center" width="324" style="border-style: none; border-width: medium; " colspan="5" >
			<p align="left">
			<font face="Cambria" size="1">
			<b>Remarks: </b></u><?php echo $Remarks; ?></font>
			</td>
			<td  align="center" width="137" style="border-style:none; border-width:medium; " >
			<font face="Cambria" size="1">
			<p align="right">
			<b>
			Total Fees Paid</b></td>
			<td  align="center" width="302" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:none; border-bottom-width:medium" >
			<span style="font-weight:700">
			<font face="Cambria" size="1">
			<?php echo $amountpaid; ?></font></span></td>
		</tr>
		<tr>
			<td  align="center" width="441" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="2" >
			&nbsp;</td>
			<td  align="center" width="324" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="5" >
			&nbsp;</td>
			<td  align="center" width="137" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" >
			<font face="Cambria">
			<p align="right">
			<font face="Cambria" style="font-weight: 700" size="1">Balance 
			Forward</font></td>
			<td  align="center" width="302" style="border-left-style:none; border-left-width:medium; border-right-style:solid; border-right-width:1px; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" >
			<span style="font-weight:700">
			<font face="Cambria" size="1">
			<?php echo $BalanceAmt; ?></font></span></td>
		</tr>
		
		
		<tr>
			<td  align="center" width="1204" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="9" >
			<font face="Cambria" style="font-size: 10pt">
	<p align="right"><font face="Cambria" size="1"><em>
		This receipt's validation is subjected to realistion of Cheque.</em></font></td>
		</tr>
		
		
		<tr>
			<td  align="center" width="441" style="border-left-style:solid; border-left-width:1px; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="2" >
			&nbsp;</td>
			<td  align="center" width="763" style="border-left-style:none; border-left-width:medium; border-right-style:none; border-right-width:medium; border-top-style:none; border-top-width:medium; border-bottom-style:solid; border-bottom-width:1px" colspan="7" >
			<font face="Cambria" size="1">
	<p align="right">
	<span style="font-family: Cambria; font-style: normal; font-variant: normal; font-weight: 700; letter-spacing: normal; line-height: normal; orphans: auto; text-align: -webkit-center; text-indent: 0px; text-transform: none; white-space: normal; widows: auto; word-spacing: 0px; -webkit-text-stroke-width: 0px; display: inline !important; float: none">
		this is computer generated Fee receipt no signature/stamp required </span></td>
	</tr>
</table>
	<font size="2">
	</b></font></font><font size="2"></u>
			</td>
		</tr>
		
		
	</table>
	</font>
</form>
</div>
<div id="divPrint">
	<p align="center">

	<font face="Cambria" style="font-size: 10pt">

	<a href="Javascript:printDiv();"><span >PRINT</span></a>
	<?php
	if($refURL != $baseURL1)
{
    ?>
    || <a href="FetchStudentDetail.php"><span >
	HOME</span></a>
    <?php
}
?>
	

	</font>

	</div>
</body>
</html>