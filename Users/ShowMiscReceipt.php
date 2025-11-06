<?php
session_start();
include '../connection.php';
include '../AppConf.php';

$receipt   = $_REQUEST['receipt_no'];
$admission = $_REQUEST['adm_no'];
// $headname = $_REQUEST['headname'];

$rss=mysqli_query($Con, "SELECT * from `fees_misc_collection` where `sadmissionno`='$admission' and `FeeReceipt`='$receipt' ");
while($row2 = mysqli_fetch_assoc($rss))
{         
	$Sname=$row2['sname'];
	$sclass=$row2['sclass'];
	$TxnAmount=$row2['Amount'];	
	$receipt_no=$row2['FeeReceipt'];
	$HeadName=$row2['HeadName'];
	$PaymentMode=$row2['PaymentMode'];
	$receipt_date=date('d-m-Y',strtotime($row2['date'])); 
	$TxnId=$row2['TxnId']; 
	$amt=$row2['Amount']; 
	break;						
}

$rsHeadDtl= mysqli_query($Con, "select * from `fees_misc_head` where `HeadCode`='$HeadName'");
$rowHeadDtl = mysqli_fetch_assoc($rsHeadDtl);
$HeadNameDesc = $rowHeadDtl['HeadName'];

    $rsStDetail=mysqli_query($Con, "SELECT `a`.`sname`,`a`.`sadmission`,`a`.`sclass`,`a`.`MasterClass`,`a`.`sfathername`,`a`.`MotherName`,`a`.`smobile`,`a`.`Address`,`a`.`AlternateMobile`,`a`.`DiscontType`,`a`.`RouteForFees`,`a`.`routeno`,`c`.`BusRouteName`,`d`.`StopName`,`e`.`slab_name` FROM `student_master` as `a` 
                         LEFT JOIN `Transport_Bus_Routes` as `c` on (`a`.`routeno`=`c`.`srno`)
                         LEFT JOIN `Transport_Stop_List` as `d` on (`a`.`routecode`=`d`.`StopId`)
                         LEFT JOIN `Transport_Bus_Route_Slabs` as `e` on (`a`.`RouteForFees`=`e`.`slab_id`) 
                         WHERE `a`.`sadmission`='$admission' ");

if (mysqli_num_rows($rsStDetail) == 0) {
	
	
	$rsStDetail=mysqli_query($Con, "SELECT `a`.`sname`,`a`.`sadmission`,`a`.`sclass`,`a`.`MasterClass`,`a`.`sfathername`,`a`.`MotherName`,`a`.`smobile`,`a`.`Address`,`a`.`AlternateMobile`,`a`.`DiscontType`,`a`.`RouteForFees`,`a`.`routeno`,`c`.`BusRouteName`,`d`.`StopName`,`e`.`slab_name` FROM `Almuni` as `a` 
	                        LEFT JOIN `Transport_Bus_Routes` as `c` on (`a`.`routeno`=`c`.`srno`)
                            LEFT JOIN `Transport_Stop_List` as `d` on (`a`.`routecode`=`d`.`StopId`)
                            LEFT JOIN `Transport_Bus_Route_Slabs` as `e` on (`a`.`RouteForFees`=`e`.`slab_id`) 
                            WHERE `a`.`sadmission`='$admission' ");
    
}

$rowSt=mysqli_fetch_assoc($rsStDetail);

$sname = $rowSt['sname'];  
$sadmission = $rowSt['sadmission'];  
$sclass = $rowSt['sclass'];  
$MasterClass = $rowSt['MasterClass'];  
$sfathername = $rowSt['sfathername'];  
$MotherName = $rowSt['MotherName'];  
$smobile = $rowSt['smobile'];  
$Address = $rowSt['Address'];  
$AlternateMobile = $rowSt['AlternateMobile'];  
$DiscontType = $rowSt['DiscontType'];  
$RouteForFees = $rowSt['RouteForFees'];  
$routeno = $rowSt['routeno'];  
$StopName = $rowSt['StopName'];
$BusRouteName = $rowSt['BusRouteName'];
$slab_name = $rowSt['slab_name'];

$rsSchConfig = mysqli_query($Con, "SELECT `SchoolName`,`SchoolAddress`,`PhoneNo`,`LogoURL`,`School_Eamil`,`website`,`SchoolNo`,`AffiliationNo` FROM `SchoolConfig` WHERE `Class`='$MasterClass' ");
$rowSch=mysqli_fetch_assoc($rsSchConfig);
$SchoolName = $rowSch['SchoolName'];  
$SchoolAddress = $rowSch['SchoolAddress'];  
$PhoneNo = $rowSch['PhoneNo'];  
$LogoURL = $rowSch['LogoURL'];  
$School_Eamil = $rowSch['School_Eamil'];
$website = $rowSch['website'];
$SchoolNo = $rowSch['SchoolNo'];
$AffiliationNo = $rowSch['AffiliationNo'];

$sqlfyear = mysqli_query($Con, "SELECT `financialyear`,`year` FROM `FYmaster` WHERE `Status`='Active'");
$rsfyear = mysqli_fetch_assoc($sqlfyear);
$financialyear = $rsfyear['financialyear'];
$currentyear = $rsfyear['year'];

$rsPaymentmode = mysqli_query($Con, "SELECT `payment_mode` FROM `fee_payment_mode` WHERE `paymode_id`='$PaymentMode' ");
$rsPaymode=mysqli_fetch_assoc($rsPaymentmode);
$payment_mode = $rsPaymode['payment_mode'];

$display_amt =  convertToIndianCurrency($amt);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Misc Fee Receipt</title>

<style>
*
{
	margin:0 auto;
	padding:0;
	font-family:arial;
	font-size:14px;
	box-sizing: border-box;
}
table
{
	border-collapse: collapse;
	width:100%;
}
table td,th, h1,p
{
	padding:3px 5px 3px 5px;
}
.border-c
{
	 border-collapse: collapse;
	 border:1px solid black;
	 width:100%;
}
.cell td,th
{
	border:1px solid black;

}

.fee-page
{
	width:800px;
	height:auto;
	display: flex;

}
.fee-col,.fee-sec-b
{
	flex:1;
	border:1px solid black;

}
.fee-sec
{
	width:100%;
	display: flex;
	
}
.flx
{
	flex: 3;
}
.flx1
{
	flex:5;
}

.bg-c1
{
	background-color:#cecaca;
}
.h1
{
	height:100px;
}
.m2
{
	margin-top:25px;
}
.m1
{
	margin-top:10px;
}
.img-rad
{
	border-radius:10px;
	width:200px;
	height:50px;
	border:1px solid black;
}

.bn
{
	border:none !important;
}
.pn
{
	padding:0px 0px 0px 0px !important;
}
.min-h
{
	min-height:100px;
}
.head
{
	font-size:18px;
}
.ls
{
	letter-spacing: 5px;
}
.bln
{
	border-left:0px !important;
	border-right:0px !important;
}
.leftalign
{
	text-align: left;
}
.rightalign
{
	text-align: right;
}

@media print{
#divprint{
    display:none;
}
}
</style>

</head>
<body>



<div class="fee-page">

	<div class="fee-col">
		<h1 class="head" style="margin-left:60px;"><?= $SchoolName; ?></h1>
		<div class="fee-sec">
			<div class="fee-sec-b bn"><img src="<?= $LogoURL; ?>" width="60" height="60"></div>
			<div class="fee-sec-b flx1 bn">
				<p><?= $SchoolAddress; ?></p>
				<p>Affiliated to CBSE Board School No: <?= $AffiliationNo; ?></p>
				<p>Contact No: <?= $PhoneNo; ?></p>
				<p><?= $website." || ".$School_Eamil; ?></p>
			</div>
		</div>
		<div class="fee-sec bg-c1 border-c bln">
			<h1 class="ls">MISC FEE RECEIPT </h1>
		</div>
		<div class="fee-sec">
			<table>
				<tr>
					<td>Parent's Name</td>
					<td><?= "Mr. ".$sfathername." / Mrs.".$MotherName; ?></td>
				</tr>
				<tr>
					<td>Name</td>
					<td><?= $sname; ?></td>
				</tr>
				<tr>
					<td>Adm No</td>
					<td><?= $sadmission; ?></td>
				</tr>
				<tr>
					<td>Class</td>
					<td><?= $sclass; ?></td>
				</tr>
				<tr>
					<td>Mobile No</td>
					<td><?= $smobile." , ".$AlternateMobile; ?></td>
				</tr>
				<tr>
					<td>Address</td>
					<td><?= $Address; ?></td>
				</tr>
				<tr>
					<td>Student Category/Bus Route/Bus Stop/Bus Slab</td>
					<td><?= $DiscontType." / ".$BusRouteName." / ".$StopName." / ".$slab_name; ?></td>
				</tr>
			</table>
		</div>
		<div class="fee-sec bg-c1 border-c bln">
			<h1>Pay Mode Information </h1>
		</div>
		<div class="fee-sec">
			<table>
				<tr>
					<td>Pay Mode</td>
					<td><?= $payment_mode; ?></td>
					<td>Date</td>
					<td><?php if($PaymentId=='1'){echo $cheque_date ;} else {echo $receipt_date;}?></td>
				</tr>
				<tr>
					<td>Bank</td>
					<td colspan="2"><?= $bankname; ?></td>
					<td><?= $TxnId; ?></td>
				</tr>
				<tr class="bg-c1">
					<td colspan="3"><b>Total</b></td>
					<td><b><?= number_format($amt,2); ?></b></td>
				</tr>
				<tr height="100">
					<td colspan="4" align="right">
					    <img  class="img-rad"> 
					</td>
				</tr>
				
			</table>
		</div>

	</div>

	<!--second columns-->
	<div class="fee-col">
		<div class="fee-sec bg-c1 border-c bln">
			<p>Session <b><?= $financialyear; ?></b></p>
		</div>
		<div class="fee-sec">
			<table class=" m2">
				<tr>
					<td>Receipt Date:</td>
					<td><?= $receipt_date; ?></td>
					<td>Receipt No</td>
					<td><?= $receipt_no; ?></td>
				</tr>
			</table>
		</div>
		<div class="fee-sec m1 min-h pn">
			<table class="cell border-c">
				<tr class="bg-c1">
					<th>SR.NO</th>
					<th>Description</th>
					<th>Paid</th>
				</tr>

				<?php
                  $cnt = 1;
                ?>

                <tr align="center">
					<td class="leftalign"><?= $cnt; ?></td>
					<td class="leftalign"><?= $HeadNameDesc; ?></td>
					<td class="rightalign"><?= number_format($amt,2); ?></td>
				</tr>
				
				<tr class="bg-c1">
					<th colspan="2">Total</th>
					<th><?= number_format($amt,2); ?></th>
				</tr>
			</table>
		</div>
			<table class="cell">
				<tr>
                    <td width="130">Late Fee : <?= number_format($Late_fees,2); ?></td>
					<td align="right">Bounce Charge : <?= number_format($cheque_bounce_amt,2); ?></td>
				</tr>
				<tr>
					<td width="130">Amount Received</td>
					<td align="right"><?= number_format($amt,2); ?></td>
				</tr>
				<tr>
					<td>In Word:</td>
					<td><?= $display_amt; ?></td>

				</tr>
				<tr>
					<td colspan="2"><?= $payment_mode; ?> Fee Paid</td>
				</tr>
				<tr>
					<td colspan="2"><p>For any queries, Kindly call at : Ph: <?=$PhoneNo; ?> or drop an email at <?=$School_Eamil; ?><br><br>
				</tr>
			</table>
	</div>
	</div>
<br><br>
<div id="divprint" style="text-align:center">
<input type="button" name ="btnPrint"  class="center1" value ="Print" style="font-size: 30px;background-color: darkgreen;color: white;" class="btn btn-success" onclick ="Javascript:window.print();"></p>
</div>
	
</body>
</html>

<?php

function convertToIndianCurrency($number) {
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10])  : '';
    return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
}

?>