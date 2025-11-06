<?php include '../connection.php';?>
<?php include '../AppConf.php';?>

<?php

	$salt = $app_salt_key;
	$postdata = $_POST;
	
    if (isset($postdata ['key']))
    {
    	$key				=   $postdata['key'];
    	$txnid 				= 	$postdata['txnid'];
        $amount      		= 	$postdata['amount'];
    	$productInfo  		= 	$postdata['productinfo'];
    	$firstname    		= 	$postdata['firstname'];
    	$email        		=	$postdata['email'];
    	$udf5				=   $postdata['udf5'];	
    	$status				= 	$postdata['status'];
    	$resphash			= 	$postdata['hash'];
    	$trackid            = 	$postdata['mihpayid'];
    	//Calculate response hash to verify	
    	$keyString 	  		=  	$key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
    	$keyArray 	  		= 	explode("|",$keyString);
    	$reverseKeyArray 	= 	array_reverse($keyArray);
    	$reverseKeyString	=	implode("|",$reverseKeyArray);
    	$CalcHashString 	= 	strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString)); //hash without additionalcharges
    	
    	//check for presence of additionalcharges parameter in response.
    	$additionalCharges  = 	"";
    	
    	If (isset($postdata["additionalCharges"])) {
           $additionalCharges=$postdata["additionalCharges"];
    	   //hash with additionalcharges
    	   $CalcHashString 	= 	strtolower(hash('sha512', $additionalCharges.'|'.$salt.'|'.$status.'|'.$reverseKeyString));
    	}
    
    }
    else exit(0);
//////////////////////////////////////////////////////  
  
	$postData = json_encode($_POST);
	mysqli_query($Con, "INSERT INTO `fees_pymnt_gtway_log`(`TxnId`, `data`) VALUES ('$txnid','$postData')");

  $txnstatus = $status; //ensure to save in db
if($status !="success")
{
	
		$ssql="UPDATE `fees_misc_collection_tmp` SET `TxnStatus`='$txnstatus',`PGTxnId`='$trackid' where `TxnId`='$txnid'";
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));
		
		echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='MyFees.php'>here</a> to restart the process!";
	
	    exit();
}
else if ($status == 'success')
  {
     //$trackid = $decodedData->trackid;  //ensure to save in db
    
        
        $merchanttxnid = $txnid;

    
    	$txnid=$merchanttxnid;

		$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
        $rsFY= mysqli_query($Con, $ssqlFY);
        $row4=mysqli_fetch_row($rsFY);
        $CurrentFinancialYear=$row4[1];
       	$Year=$row4[0];	
		    
		
		$currentdate=date("d-m-Y");
		$rstxnDetail=mysqli_query($Con, "select `sadmissionno`,`sname`,`sclass`,`srollno` from `fees_misc_collection_tmp` where `TxnId`='$txnid'");
		while($row=mysqli_fetch_row($rstxnDetail))
		{
			$sadmissionno=$row[0];
			$sname=$row[1];
			$sclass=$row[2];
			$srollno=$row[3];
			break;
		}
		
		$ssql="UPDATE `fees_misc_collection_tmp` SET `TxnStatus`='$txnstatus',`PGTxnId`='$trackid' where `TxnId`='$txnid'";
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));
	
			
		
			
			$rsCnt=mysqli_query($Con, "SELECT max(CONVERT(replace(`FeeReceipt`,'MR/".$CurrentFinancialYear."/',''),UNSIGNED INTEGER)) as `cnt` FROM `fees_misc_collection`");
			if (mysqli_num_rows($rsCnt) > 0)
			{
				while($rowCnt = mysqli_fetch_row($rsCnt))
				{
					if($rowCnt[0]=="")
					{
					$NewSrNo="1";
					}
					else
					{
						$NewSrNo=$rowCnt[0]+1;
					}
					break;
				}
			}
			else
			{
				$NewSrNo="1";
			}
			
			$ReceiptNo="MR/".$CurrentFinancialYear."/".$NewSrNo;
			mysqli_query($Con, "UPDATE `fees_misc_collection_tmp` SET `FeeReceipt`='$ReceiptNo' where `TxnId`='$txnid'") or die(mysqli_error($Con));
			$rsChk= mysqli_query($Con, "select * from `fees_misc_collection` where `TxnId`='$txnid'");
			if (mysqli_num_rows($rsChk) == 0)
			{
				mysqli_query($Con, "insert into fees_misc_collection (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,`financialyear`) select `date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,'$Year' from `fees_misc_collection_tmp` where `TxnId`='$txnid'") or die(mysqli_error($Con));
			}
			
			echo "<script>alert('Your Fees has been Submitted Successfully');window.location.href='MyFees.php';</script>";   
			
			 $rsHead=mysqli_query($Con, "SELECT `HeadName`,`Amount` FROM  `fees_misc_collection` where `TxnId`='$txnid'");
			   while($rowH=mysqli_fetch_row($rsHead))
			   {
			   		$HeadName=$rowH[0];
			   		$amount=$rowH[1];
			   		break;
			   }
}
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
</script>

<html>



<head>

<meta http-equiv="Content-Language" content="en-us">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">

<title><?php echo $SchoolName ?> </title>

</head>

<body >

<div id="MasterDiv" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" >
<style type="text/css">
.style4 {
	text-align: left;
}
.style5 {
	font-size: 13pt;
	font-weight: bold;
}
</style>

	<p align="center">

	<font face="Cambria">

	<img border="0" src="../Admin/images/logo.png"  height="81" width="288"></font></p>
	<p align="center" ><font face="Cambria"><span class="style5"><?php echo $SchoolAddress; ?></span></font><br><b><font face="Cambria">
	(MISC. Fees Receipt)</font></b></p>
	
	
	<p align="center"><b>Receipt No.<?php echo $ReceiptNo; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Date:<?php echo date("d-m-Y"); ?></b></p>
	
										

	<div align="center">

		<table cellpadding="0" style="padding: 1px 4px; border-style: dotted; border-width: 1px; width: 672px; border-collapse:collapse; " >

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px">

				<font face="Cambria">Student Name</font></td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">

				<font face="Cambria">

				<?php echo $sname; ?>

				</font></td>

			</tr>

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px" >

				<font face="Cambria">Admission No</font></td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">

				<font face="Cambria">

				<?php echo $sadmissionno; ?>

				</font></td>

			</tr>

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px" >

				<font face="Cambria">Student Class</font></td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">

				<font face="Cambria">

				<?php echo $sclass; ?>

				</font></td>

			</tr>

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px" >

				<font face="Cambria"> Fees Type</font></td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">

				<font face="Cambria">

				Misc.-<?php echo $HeadName; ?>

				</font></td>

			</tr>

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px" >

								<font face="Cambria"> Payment Mode</font></td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">
				<font face="Cambria">
				Online
				</font>
				</td>

			</tr>

			<?php
			if ($ChequeNo !="")
			{
			?>
			<?php
			}
			?>

			<tr>

				<td align="left" style="border-style: dotted; border-width: 1px; width: 335px" >

								Fees Paid</td>

				<td style="border-style: dotted; border-width: 1px; width: 335px" class="style4">

				<font face="Cambria">

				<?php echo $amount; ?>

				</font></td>

			</tr>

			</table>
</div>
	

	<p align="center"><font face="Cambria">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>&nbsp;&nbsp;Fees 
	In-charge</strong></font></p>
	<p align="center"><font face="Cambria">This is an electronically generated receipt and does not require any signature.</font></p>
	
<form name="frmPDF" id="frmPDF" method="post" action="StorePDFMiscFee.php">
		<input type="hidden" name="htmlcode" id="htmlcode" value="tesing">
		<input type="hidden" name="txtpdffilename" id="txtpdffilename" value="<?php echo $PDFFileName; ?>">
		<input type="hidden" name="receiptno" id="receiptno" value="<?php echo $ReceiptNo;?>">
		<input type="hidden" name="txtprintdiv" id="txtprintdiv" value="">
</form>	
</div>
	
<div id="divPrint">
	
	<p align="center">
	<font face="Cambria">
	<a href="Javascript:printDiv();">PRINT</a> || <a href="MyFees.php">HOME</a> 	
</font> 
	
</div>
</body>

</html>

