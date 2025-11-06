<?php
include '../connection.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>School Journel Articles</title>
<style type="text/css">
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

{       height:20px; 
        width: 100%; 
        margin:auto;        
        background-color:Blue;
        font-family: Calibri;
        font-weight:bold;
}
</style>
</head>

<body>
<form method="post">
<p>&nbsp;</p>
<table width="100%">
  <tr>
    <td align="center">
	<p align="left"><b><font face="Cambria">Covidvaccinecertificate</font><font size="3" face="Cambria"></p>
	<hr>
	<p align="left"><a href="javascript:history.back(1)">

<img height="28" src="../images/BackButton.png" width="70" style="float: right"></a></b></td>
  </tr>
</table> 
 

<div align="center">
<table border=1 style="width:100%; border-collapse:collapse" cellspacing="0" cellpadding="0">
   <tr>
   		<td height="22" align="center" style="width: 14%" bgcolor="#95C23D"><b><font face="Cambria">
		Serial No.</font></b></td>
   		<td height="22" align="center" bgcolor="#95C23D"><b><font face="Cambria">
		sadmission</font></b></td>
		<td height="22" align="center" bgcolor="#95C23D"><b><font face="Cambria">
		sname</font></b></td>
		<td height="22" align="center" bgcolor="#95C23D"><b><font face="Cambria">
		sclass</font></b></td>
		<td height="22" align="center" bgcolor="#95C23D"><b><font face="Cambria">
		srollno</font></b></td>
		<td height="22" align="center" bgcolor="#95C23D">Click</td>
		<td height="22" align="center" bgcolor="#95C23D"><b>
		<font face="Cambria">Cerificate</font></b></td>
		<td height="22" align="center" bgcolor="#95C23D"><b>
		<font face="Cambria">2</font></b></td>
		
   	</tr>
<?php
$result=mysqli_query($Con, "SELECT `srno`, `EmpId`, `EmpName`, `EmpType`,`Consent`,`attachment` FROM `SchoolReopenConsentJan` WHERE `Consent`='Yes' ");
   		
while($rs= mysqli_fetch_array($result))
{
?>
<tr>
	<td><font face="Cambria"><?php echo $rs["srno"]; ?></font></td>
    <td><font face="Cambria"><?php echo $rs["EmpId"];?></font></td>
	<td><font face="Cambria"><?php echo $rs["EmpName"];?></font></td>
	<td><font face="Cambria"><?php echo $rs["Consent"];?></font></td>
	<td><font face="Cambria"><a href="https://dpsfsis.com/Users/consent/<?php echo $rs["attachment"];?>" target="_blank"><?php echo $rs["attachment"];?></a></font></td>
	
</tr>
<?php   	 
}
?>
</table>

</div>


<div class="footer" align="center">
<div class="footer_contents" align="center">
<font color="#FFFFFF" face="Cambria" size="2">Powered by iSchool Technologies 
LLP</font></div>
</div>
</body>
</html> 