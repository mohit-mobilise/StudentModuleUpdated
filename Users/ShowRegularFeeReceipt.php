<?php include '../connection.php';?>

<?php
// just insert html code into table

$receiptno = $_REQUEST["receiptno"];

//$rs=mysqli_query($Con, "select `ReceiptCode` from `fees_receipt_code1` where `ReceiptNo`='$receiptno' and `sadmissionno`='$sadmission'");
$rs=mysqli_query($Con, "select `ReceiptCode` from `fees_receipt_code` where `ReceiptNo`='$receiptno'");
		
	while($row=mysqli_fetch_row($rs))
	{
		$ReceiptCode=$row[0];
		break;
	}
	
		//echo $varhtml;
		//exit();
?>
<script language="javascript">
	
	function printDiv() 
	{

        //Get the HTML of div

        var divElements = document.getElementById("MasterDiv").innerHTML;

        //Get the HTML of whole page

        var oldPage = document.body.innerHTML;



        //Reset the page's HTML with div's HTML only

        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";



        //Print Page

        window.print();



        //Restore orignal HTML

        document.body.innerHTML = oldPage;

 	}
</script>


<html>
<head>

<!--<body onload="Javascript:fnRedirect();">-->
<body>
<input type="hidden" name="FileName" id="FileName" value="<?php echo $filename; ?>">
<div id="MasterDiv" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" >
<?php
//echo $varhtml . "<br><hr>" . $varhtml;
echo $ReceiptCode;
?>
</div>
<div id="divPrint">
	
	<p align="center">
	<font face="Cambria">
	<a href="Javascript:printDiv();">PRINT</a> 	|| <a href="http://dpsfsis.com/Users/MyFees.php">HOME</a>
</font> 
	
</div>
</body>
</head>
</html>