<?php include '../connection.php'; ?>
<?php include '../AppConf.php';?>

<?php
session_start();

// 	$StudentClass = $_SESSION['StudentClass'];
// 	$StudentRollNo = $_SESSION['StudentRollNo'];
// 	$AdmissionNo=$_SESSION['userid'];

$merchantTxnId = uniqid(); 

$AdmissionNo=$_REQUEST['misc_admission_no'];
	
if($AdmissionNo == '') {
	echo "Please try again!";
	exit();
}

$HeaderSrNo=$_REQUEST["hHeaderSrNo"];	
$currentdate=date("Y-m-d");

// 	$rsHeaderDetial=mysqli_query($Con, "select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,(SELECT `HeadType` FROM  `fees_misc_head` where `HeadName`=a.`HeadName` limit 1) as `HeadType`,`AnnouncementID` from `fees_misc_announce` as `a` where `sclass`='$StudentClass' and `srno`='$HeaderSrNo'");
$rsHeaderDetial=mysqli_query($Con, "select `srno`,`HeadName`,`HeadAmount`,`sclass`,`LastDate`,`Remarks`,(SELECT `HeadType` FROM  `fees_misc_head` where `HeadName`=a.`HeadName` limit 1) as `HeadType`,`AnnouncementID` from `fees_misc_announce` as `a` where `srno`='$HeaderSrNo'");

if(mysqli_num_rows($rsHeaderDetial) == 0) {
	echo "Invalid, Please try again!";
	exit();
}

while($row=mysqli_fetch_row($rsHeaderDetial))
{
	$HeadName=$row[1];
	$HeadAmount=$row[2];
	$StudentClass=$row[3];
	$LastDate=$row[4];
	$Remarks=$row[5];
	$HeadType=$row[6];
	$AnnouncementID=$row[7];
	break;
}

$ssqlmisc="select `MaxCount` as `MiscMaxCount` from `fees_misc_head` where `HeadCode`='$HeadName' ";
$rsmisc = mysqli_query($Con, $ssqlmisc);
$rowmisc=mysqli_fetch_assoc($rsmisc);
$MiscMaxCount = $rowmisc['MiscMaxCount'];

$rsMiscTtlChk=mysqli_query($Con, "select count(*) as `ttl_misc_fee_paid` from `fees_misc_collection` where `HeadName`='$HeadName' ");
$rowMiscTtlChk = mysqli_fetch_assoc($rsMiscTtlChk);
$ttl_misc_fee_paid = $rowMiscTtlChk['ttl_misc_fee_paid'];
	
if($MiscMaxCount <= $ttl_misc_fee_paid) {
	echo "Misc Fee Limit reached!";
	exit();
}
	
	
$sqlStudentDetail = "select `sfathername`,`sname`,`email`,`Address`,`smobile`,`srollno` from  `student_master` where `sadmission`='$AdmissionNo'";
$rsStudentDetail = mysqli_query($Con, $sqlStudentDetail);
if(mysqli_num_rows($rsStudentDetail) == 0) {
	echo "Invalid Admission No, Please try again!";
	exit();
}

while($row=mysqli_fetch_row($rsStudentDetail))
{
	$sfathername=$row[0];
	$sname=$row[1];
	$email=$row[2];
	$Address=$row[3];
	$smobile=$row[4];
	$StudentRollNo=$row[5];
	break;
}
	
$ssql="insert into `fees_misc_collection_tmp` (`date`,`AnnouncementID`,`HeadName`,`sadmissionno`,`sname`,`sclass`,`srollno`,`Amount`,`PaymentMode`,`TxnId`,`HeadType`) values ('$currentdate','$AnnouncementID','$HeadName','$AdmissionNo','$sname','$StudentClass','$StudentRollNo','$HeadAmount','3','$merchantTxnId','$HeadType')";
mysqli_query($Con, $ssql) or die(mysqli_error($Con));

$TxnOrderAmount=$HeadAmount;

?>

<html>
  <head>
    <meta http-equiv="Content-Language" content="en-us">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <title><?php echo $SchoolName ?></title>
  </head>
  <body>
    <center>
      <?php include('Crypto.php')?>
      <?php 
        error_reporting(0);

        $merchant_data='';
        $working_key='14AD1ABE4E1F13A8F38513B8024B1B2B';//Shared by CCAVENUES
        $access_code='AVOV71ME75BC74VOCB';//Shared by CCAVENUES
        $merchant_data='764451';
        $merchant_data=$merchant_data.'&txtName='.$sname.'&TotalFeeAmount='.$TxnOrderAmount;
        $merchant_data=$merchant_data.'&redirect_url='.$BaseURL.'Users/MiscFeeCollectionReceipt_New_CCA.php&cancel_url='.$BaseURL.'Users/MiscFeeCollectionReceipt_New_CCA.php&currency=INR';
        $merchant_data=$merchant_data.'&merchant_id=764451&order_id='.$merchantTxnId.'&amount='.$TxnOrderAmount.'&FatherName='.$sfathername;

        $encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

        // foreach ($_POST as $key => $value){
        //   $merchant_data.=$key.'='.$value.'&';
        // }

        // $merchant_data=str_replace('764451tid=&','',$merchant_data);
        // $encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.

      ?>

      <form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
        <?php
          echo "<input type=hidden name=encRequest value=$encrypted_data>";
          echo "<input type=hidden name=access_code value=$access_code>";
        ?>
      </form>
    </center>
    <script language='javascript'>
    document.redirect.submit();</script>
  </body>
</html>