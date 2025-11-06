<?php 
session_start();

include '../connection.php';

	//$AdmissionId=$_REQUEST["sadmission"];	
		$StudentClass = $_SESSION['StudentClass'];
	$StudentRollNo = $_SESSION['StudentRollNo'];
	$AdmissionId=$_SESSION['userid'];
	
   $sql = "SELECT distinct `sname`,`sclass`,`srollno`,`sfathername`,`MasterClass` FROM `student_master` where `sadmission`='$AdmissionId'";
   $result = mysqli_query($Con, $sql);
				while($row = mysqli_fetch_row($result))
				{
   					$StudentName=$row[0];
   					$Class=$row[1];
   					$RollNo=$row[2];
   					$FatherName=$row[3];
   					$MasterClass=$row[4];
				}

$ssql="select `ExamName`,`ExamFee`,`WorkBook`,`TextBook` from `fees_exam_master` where `sclass`='$MasterClass' and `portal_status`='Active'";
$rs=mysqli_query($Con, $ssql);

?>

<script language="javascript">
function fnlCheckExamFee(cnt)
{
	var ctrlName="chkExamFee"+cnt;
	//alert(document.getElementById(ctrlName).checked);
	if(document.getElementById(ctrlName).checked==true)
	{
		var ctrlExamFee="hExamFees"+cnt;
		//var ctrlchkExamFee="chkExamFee"+cnt;
		document.getElementById(ctrlName).value="Yes";
		//alert(document.getElementById(ctrlExamFee).value);
		//return;
	}
	else
	{
		document.getElementById("chkWorkBook"+cnt).checked=false;
		document.getElementById("chkTextBook"+cnt).checked=false;
	}
	fnlCalculateTotal();
	
}
function fnlCheckWorkbookFee(cnt)
{
	var ctrlName="chkWorkBook"+cnt;
	if(document.getElementById("chkExamFee"+cnt).checked==false)
	{
		document.getElementById(ctrlName).checked=false;
		alert("Exam Fees selection is mandatory!");
		return;
	}
	if(document.getElementById(ctrlName).checked==true)
	{
		var ctrlWorkBookFee="hWorkBook"+cnt;
		//var ctrlchkExamFee="chkWorkBook"+cnt;
		document.getElementById(ctrlName).value="Yes";
		//alert(document.getElementById(ctrlWorkBookFee).value);
		//return;
	}
	else
	{
		
		document.getElementById(ctrlName).value="No";
	}
	fnlCalculateTotal();
}
/*function fnlCheckTextBookFee(cnt)
{
	var ctrlName="chkTextBook"+cnt;
	//alert(document.getElementById(ctrlName).checked);
	if(document.getElementById("chkExamFee"+cnt).checked==false)
	{
		document.getElementById(ctrlName).checked=false;
		alert("Exam Fees selection is mandatory!");
		return;
	}
	if(document.getElementById(ctrlName).checked==true)
	{
		var ctrlTextBookFee="hTextBook"+cnt;
		//var ctrlchkExamFee="chkTextBook"+cnt;
		document.getElementById(ctrlName).value="Yes";
		//alert(document.getElementById(ctrlTextBookFee).value);
		//return;
	}
	else
	{
		document.getElementById(ctrlName).value="No";
	}
	fnlCalculateTotal();

}*/
function fnlCalculateTotal()
{
	var TotalExam=document.getElementById("TotalExam").value;
	var TotalExamFee=0;
	var TotalWorkBookFee=0;
	var TotalTextBookFee=0;
	var GrandTotalFee=0;
	document.getElementById("hSelectedExam").value="";
	for($i=1;$i<=TotalExam;$i++)
	{
		var ctrlName="chkExamFee"+$i;
		var RowWiseTotal=0;
		//alert(document.getElementById(ctrlName).checked);
		if(document.getElementById(ctrlName).checked==true)
		{
			var ctrlExamFee="hExamFees"+$i;
			document.getElementById("hSelectedExam").value=document.getElementById("hSelectedExam").value + document.getElementById("hExamName"+$i).value + ",";
			//document.getElementById(ctrlName).value="Yes";
			TotalExamFee=TotalExamFee + parseInt(document.getElementById(ctrlExamFee).value);			
			RowWiseTotal=RowWiseTotal+ parseInt(document.getElementById(ctrlExamFee).value);
		}
		
		if(document.getElementById("chkWorkBook"+$i).checked==true)
		{
			//var ctrlExamFee="hWorkBook"+$i;
			//document.getElementById(ctrlName).value="Yes";
			TotalWorkBookFee=TotalWorkBookFee + parseInt(document.getElementById("hWorkBook"+$i).value);			
			RowWiseTotal=RowWiseTotal+ parseInt(document.getElementById("hWorkBook"+$i).value);
		}
		//if(document.getElementById("chkTextBook"+$i).checked==true)
		//{
			//var ctrlExamFee="hWorkBook"+$i;
			//document.getElementById(ctrlName).value="Yes";
			//TotalTextBookFee=TotalTextBookFee+ parseInt(document.getElementById("hTextBook"+$i).value);
			//RowWiseTotal=RowWiseTotal+ parseInt(document.getElementById("hTextBook"+$i).value);			
		//}
		if(RowWiseTotal>0)
		{
			document.getElementById("TDTotalAmt"+$i).innerHTML=RowWiseTotal;
		}
		else
		{
			document.getElementById("TDTotalAmt"+$i).innerHTML="";
		}
	}
	GrandTotalFee=parseInt(TotalExamFee)+ parseInt(TotalWorkBookFee);
	document.getElementById("TDGrandTotal").innerHTML =GrandTotalFee;
	//alert(TotalExamFee+"/"+TotalWorkBookFee+"/"+TotalTextBookFee);
}

function GetStudentDetail()
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
			        	arr_row=rows.split(",");
			        	document.getElementById("txtStudentName").value=arr_row[0];
			        	document.getElementById("txtClass").value=arr_row[1];
			        	document.getElementById("txtFatherName").value=arr_row[3];
			        }
		      }
			
			var submiturl="GetStudentDetail.php?AdmissionId=" + document.getElementById("txtAdmissionNo").value;
			xmlHttp.open("GET", submiturl,true);
			xmlHttp.send(null);
	}
</script>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" />
	<meta name="author" content="gencyolcu" />

	<title>Examination Fees Collection</title>
	<!-- link calendar resources -->
	<link rel="stylesheet" type="text/css" href="tcal.css" />
	<script type="text/javascript" src="tcal.js"></script>
	
	<style type="text/css">
.style1 {
	font-family: Cambria;
}
.style2 {
	text-align: center;
}
.style3 {
	border-collapse: collapse;
	border-style: solid;
	border-width: 1px;
}
</style>
</head>

<body>
<p>&nbsp;</p>
<table width="100%">
<tr>
<td>
<font face="Cambria" style="font-size: 14pt; font-weight: 700">Examination Fees 
Collection</font></table>

<hr>
<p>&nbsp;</p>
<form name="frmExam" id="frmExam" method="post" action="ExamFeeReceiptForPayment.php">
<input type ="hidden" name ="hSelectedExam" id="hSelectedExam" value ="">
<table width="100%" cellpadding="0" cellspacing="0" class="style3" >
<form name="frmStudent" id="frmStudent" method="post" action="">
<tr>
	<td style="border-style: solid; border-width: 1px; width: 208px;"><b><font face="Cambria">Admn. No : </font></b></td>
	<td style="border-style: solid; border-width: 1px; width: 208px;">
	<input name="txtAdmissionNo" id="txtAdmissionNo" type="text" value="<?php echo $AdmissionId;?>"></td>
	<td style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; width: 208px;"><b><font face="Cambria">Student Name : </font></b></td>
	<td style="border-left-style: solid; border-left-width: 1px; border-right-style: solid; border-right-width: 1px; border-top-style: solid; border-top-width: 1px; width: 209px;">
	<input name="txtStudentName" id="txtStudentName" type="text" value="<?php echo $StudentName;?>"></td>
	<td style="border-style: solid; border-width: 1px; width: 209px;"><b><font face="Cambria">Class : </font></b></td>
	<td style="border-style: solid; border-width: 1px; width: 209px;">
	<input name="txtClass" id="txtClass" type="text" value="<?php echo $Class;?>"></td>
</tr>
<tr >
	<td colspan="6" style="border-style: solid; border-width: 1px">&nbsp;</td>
	
</tr>
<tr >
	<td colspan="2" style="border-style: solid; border-width: 1px"><b><font face="Cambria">Father's/Mother's Name : </font></b></td>
	<td colspan="2" style="border-style: solid; border-width: 1px">
	<input name="txtFatherName" id="txtFatherName" type="text" value="<?php echo $FatherName;?>"></td>
	<td style="border-style: solid; border-width: 1px; width: 324px;" colspan="2">
	&nbsp;</td>
	
</tr>
</form>
</table>
<br>
<br>

<table>
<tr>
<td>
<p>
</p>
</td></tr></table>

<table width="100%" border="1" style="border-collapse: collapse">
<tr>
	<th width="45"><font face="Cambria">S.No</font></th>
	<th width="237"><font face="Cambria">Name of exam</font></th>
	<th width="308"><font face="Cambria">Exam Fee [ ]</font></th>
	<th width="309"><font face="Cambria">Work-Book (Optional) [ ]</font></th>
	<!---<th width="246"><font face="Cambria">Text Book (Optional) [ ]</font></th>--->
	<th width="175"><font face="Cambria">Amount To be Paid</font></th>
</tr>
<?php
$cnt=1;
while($row=mysqli_fetch_row($rs))
{
	$ExamName=$row[0];
	$ExamFee=$row[1];
	$WorkBook=$row[2];
	$TextBook=$row[3];
?>
<input type="hidden" name="hExamName<?php echo $cnt;?>" id="hExamName<?php echo $cnt;?>" value="<?php echo $ExamName;?>">
<input type="hidden" name="hExamFees<?php echo $cnt;?>" id="hExamFees<?php echo $cnt;?>" value="<?php echo $ExamFee;?>">
<input type="hidden" name="hWorkBook<?php echo $cnt;?>" id="hWorkBook<?php echo $cnt;?>" value="<?php echo $WorkBook;?>">
<input type="hidden" name="hTextBook<?php echo $cnt;?>" id="hTextBook<?php echo $cnt;?>" value="<?php echo $TextBook;?>">

<tr>
	<td width="45"><b><font face="Cambria"><?php echo $cnt;?>.</font></b></td>
	<td width="237"><b><font face="Cambria"><?php echo $ExamName;?></font></b></td>
	<td align="center" width="308"><input type="checkbox" name="chkExamFee<?php echo $cnt;?>" id="chkExamFee<?php echo $cnt;?>" value="No" onclick="javascript:fnlCheckExamFee('<?php echo $cnt;?>');"></td>
	<td align="center" width="309"><input type="checkbox" name="chkWorkBook<?php echo $cnt;?>" id="chkWorkBook<?php echo $cnt;?>" value="No" onclick="javascript:fnlCheckWorkbookFee('<?php echo $cnt;?>');"></td>
	<!---<td align="center" width="246"><input type="checkbox" name="chkTextBook<?php echo $cnt;?>" id="chkTextBook<?php echo $cnt;?>" value="No" onclick="javascript:fnlCheckTextBookFee('<?php echo $cnt;?>');"></td>--->
	<td align="center" width="175" class="style1" id="TDTotalAmt<?php echo $cnt;?>"></td>
</tr>
<?php
$cnt=$cnt+1;
}
?>
<input type="hidden" name="TotalExam" id="TotalExam" value="<?php echo $cnt-1;?>">
<tr>
	
	<td colspan="4" align="right"><b><font face="Cambria">GRAND TOTAL : </font></b></td>
	
	<td class="style2" id="TDGrandTotal"></td>
	
</tr>
</table>
<p>&nbsp;</p>
	<p align="center">
	<input type="button" value="Submit" name="B1" style="font-weight: 700" onclick="javascript:validate();"></p>
</form>
</body>
</html>

<script language="javascript">
function validate()
{
	fnlCalculateTotal();
	
	//alert(document.getElementById("TotalExam").value);
	document.getElementById("frmExam").submit();	
}
</script>