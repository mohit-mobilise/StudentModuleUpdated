<?php include '../connection.php';?>
<?php include '../AppConf.php';?>

<?php

			$merchant_id='APIMER';
			$merchant_sub_id='DPSNCR';
			$sign_key='3flGtdY4ya7vWJYDmUCdJYOx8Tw7T6fJ';
			$encryption_key = hash('sha256','DADF6DE6BE60D3894B81E20EC375433B',true);
			$encryption_iv = 'jdFjduzrhXcewq8t';

  $responsejson = $_REQUEST['resjson'];
  logmessages("txninit response received : ".$responsejson);
  $decodedVal = json_decode($responsejson);
	$retData = $decodedVal->data;
	//$res = pkcs5_unpad(mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$encryption_key,base64_decode($retData), MCRYPT_MODE_CBC, $encryption_iv));
	$res = pkcs5_unpad(openssl_decrypt ( base64_decode($retData) , 'AES-256-CBC' , $encryption_key ,OPENSSL_RAW_DATA | OPENSSL_NO_PADDING, $encryption_iv)); //php 7.1 and above
	logmessages(" txninit service response after decryption: ".$res);
	$decodedData = json_decode($res);
  $errorcd = $decodedData->errorcd;
   if($errorcd != NULL)
	{
      echo("Server error ! Please contact your website Administrator! Error Code : ".$errorcd);
  }
else
  {
     $trackid = $decodedData->trackid;  //ensure to save in db
     $txnstatus = $decodedData->txnstatus; //ensure to save in db
     $merchanttxnid = $decodedData->merchanttxnid;
     if($trackid!=NULL)
     {
        if($txnstatus=="SUCCESS")
        {
        	$txnid=$merchanttxnid;

			$ssqlFY="SELECT distinct `year`,`financialyear`,`Status` FROM `FYmaster` where `Status`='Active'";
            $rsFY= mysqli_query($Con, $ssqlFY);
            $row4=mysqli_fetch_row($rsFY);
	        $CurrentFinancialYear=$row4[1];
           	$Year=$row4[0];	
		    
		
		$currentdate=date("d-m-Y");
		$rstxnDetail=mysqli_query($Con, "select `sadmissionno`,`sname`,`sclass`,`srollno` from `fees_misc_collection_tmp_dr` where `TxnId`='$txnid'");
		while($row=mysqli_fetch_row($rstxnDetail))
		{
			$sadmissionno=$row[0];
			$sname=$row[1];
			$sclass=$row[2];
			$srollno=$row[3];
			break;
		}
		
		$ssql="UPDATE `fees_misc_collection_tmp_dr` SET `TxnStatus`='$txnstatus',`PGTxnId`='$trackid' where `TxnId`='$txnid'";
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));
	
			
		
			
			$rsCnt=mysqli_query($Con, "SELECT max(CONVERT(replace(`FeeReceipt`,'FRD/".$CurrentFinancialYear."/',''),UNSIGNED INTEGER)) as `cnt` FROM `fees_misc_collection_dr`");
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
			
			$ReceiptNo="FRD/".$CurrentFinancialYear."/".$NewSrNo;
			
			$rsChk= mysqli_query($Con, "select * from `fees_misc_collection_dr` where `TxnId`='$txnid'");
			if (mysqli_num_rows($rsChk) == 0)
			{
				mysqli_query($Con, "UPDATE `fees_misc_collection_tmp_dr` SET `FeeReceipt`='$ReceiptNo' where `TxnId`='$txnid'") or die(mysqli_error($Con));

				mysqli_query($Con, "insert into fees_misc_collection_dr (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,`financialyear`,`late_fee`,`total_amount`) select `date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`TxnStatus`,`PGTxnId`,`FeeReceipt`,`HeadType`,'$Year',`late_fee`,`total_amount` from `fees_misc_collection_tmp_dr` where `TxnId`='$txnid'") or die(mysqli_error($Con));
			}
			
			 $rsHead=mysqli_query($Con, "SELECT `HeadName`,`total_amount`,`FeeReceipt` FROM  `fees_misc_collection_dr` where `TxnId`='$txnid'");
			   while($rowH=mysqli_fetch_row($rsHead))
			   {
			   		$HeadName=$rowH[0];
			   		$amount=$rowH[1];
			   		$FeeReceipt=$rowH[2];
			   		break;
			   }

			
}
 else if($txnstatus=="FAILURE")
        {
			echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='https://dpsfsis.com'>here</a> to restart the process!";
	        exit();

        }
        else if($txnstatus=="AWAITED")
        {
			echo "<br><br><center>Your Transaction was not successfully completed!<br>You are requested to kindly payment again<br><br>Click <a href='https://dpsfsis.com'>here</a> to restart the process!";
	        exit();
        }
     }
  }
        
function logmessages($msg)
{
		error_log("\n".(new DateTime())->format("d:m:y h:i:s")." ".$msg,3,"/var/log/testclient/infolog.log");
}

function pkcs5_pad ($text, $blocksize) 
{ 
    $pad = $blocksize - (strlen($text) % $blocksize); 
    return $text . str_repeat(chr($pad), $pad); 
} 

function pkcs5_unpad($text) 
{ 
    $pad = ord($text{strlen($text)-1}); 
    if ($pad > strlen($text)) return false; 
    if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false; 
    return substr($text, 0, -1 * $pad); 
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

function CreatePDF() 
{
		
        //Get the HTML of div

        var divElements = document.getElementById("MasterDiv").innerHTML;

        //Get the HTML of whole page

        var oldPage = document.body.innerHTML;



        //Reset the page's HTML with div's HTML only

        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";

		document.frmPDF.htmlcode.value = "<html><head><title></title></head><body>" + divElements + "</body>";

		//document.frmPDF.txtprintdiv.value =document.getElementById("divPrint").innerHTML;
		//alert(document.frmPDF.htmlcode.value);
		//document.frmPDF.submit;
		document.getElementById("frmPDF").submit();
		//document.all("frmPDF").submit();
		return;
		//alert(document.getElementById("htmlcode").value);		 
 
        //Print Page
		
        //window.print();
        var FileLocation="http://emeraldsis.com/Admin/StudentManagement/CreateMiscPDF.php?htmlcode=" + escape(document.body.innerHTML);
		window.location.assign(FileLocation);
		return;


        //Restore orignal HTML

        //document.body.innerHTML = oldPage;

 }

function CreatePDF1() 
{
		var divElements = document.getElementById("MasterDiv").innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;
        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body></html>";

		
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
						alert(rows);
			        	//document.getElementById("txtAnnualFeeDiscount").value=rows;
						//alert(rows);														
			        }

		      }

			var submiturl="CreatePDF.php?htmlcode=" + escape(document.body.innerHTML) + "&receiptno=" + document.getElementById("receiptno").value;
			xmlHttp.open("POST", submiturl,true);
			xmlHttp.send(null);
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
	(Previous Dues Fees Receipt)</font></b></p>
	
	
	<p align="center"><b>Receipt No.<?php echo $FeeReceipt; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;Date:<?php echo date("d-m-Y"); ?></b></p>
	
										

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

			<?php
                 
               $sqlheadname = mysqli_query($Con, "SELECT `HeadName` FROM `fees_misc_head_dr` WHERE `HeadCode`='$HeadName'");
			   $rsheadname=mysqli_fetch_assoc($sqlheadname);
			   $head_name = $rsheadname['HeadName'];

				 echo $head_name; ?>

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
	<a href="Javascript:printDiv();">PRINT</a> || <a href="landing_page.php">HOME</a> 	
</font> 
	
</div>
</body>

</html>

