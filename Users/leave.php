<?php include '../connection.php';?>
<?php include '../AppConf.php';?>
<?php
session_start();

// Check if user is logged in
if (empty($_SESSION['userid'])) {
    echo "<br><br><center><b>Due to security reasons your session has been expired!<br>Click <a href='Login.php'>here</a> to login again</b></center>";
    exit;
}

$StudentName = $_SESSION['StudentName'] ?? '';
$class = $_SESSION['StudentClass'] ?? '';
$StudentRollNo = $_SESSION['StudentRollNo'] ?? '';
$AdmissionId = $_SESSION['userid'] ?? '';



	$isSubmit = $_REQUEST["isSubmit"] ?? '';
	if($isSubmit=="yes")
	
	{
	    
	

		$subject1 = $_REQUEST["cboSubject"] ?? '';
		if ($subject1=="Studies Related")
		{
			$to1 = "principal@";
		}
		if ($subject1=="Transport")
		{
			$to1 = "principal@";
		}
		if ($subject1=="Admin")
		{
			$to1 = "@";
		}
		if ($subject1=="Fee")
		{
			$to1 = "accounts@";
		}
		if ($subject1=="Report Card")
		{
			$to1 = "principal@";
		}
		if ($subject1=="Sick Leave")
		{
			$to1 = "admin@";
		}		
			
		$subject = ($_REQUEST["cboSubject"] ?? '') . " ,Name:" . $StudentName . ",Class:" . $class . ",Roll No:" . $StudentRollNo;
		$message = $_REQUEST["txtQuery"] ?? '';
		$from = "query@";
		$headers = "From:" . $from;
		//mail($to1,$subject,$message,$headers);
		$query_date = date('Y-m-d');
        
		if($subject1!=''){
		$ssql="INSERT INTO `parent_query` (`sadmission`,`sname`,`srollno`,`sclass`,`parentquery`,`query_type`,`query_date`) VALUES ('$AdmissionId','$StudentName','$StudentRollNo','$class','$message','$subject1','$query_date')";
		
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));
		
		echo '<script>toastr.success("Query Sent Successfully", "Success");</script>';
		
		}

	
		//$Msg="<center><b>Mail Sent Successfully";
		
	}


$ssql="SELECT `srno`,`sname` ,`srollno`, `sclass` ,`parentquery`,`queryresponse`,`datetime`,`query_type` FROM `parent_query` as `a` where a.`sadmission`= '$AdmissionId' order by datetime desc";
$rsQueryDetail= mysqli_query($Con, $ssql);
$num_rows=0;

?>
<script>
    function ValidateQuery()
	{

		if (document.getElementById("txtQuery").value=="Type your query here :")
		{
			toastr.warning("Message is mandatory", "Validation Error");
			return;
	}
		document.getElementById("frmEMail").submit();
		
	}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SchoolName;?>Profile</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
 <!--<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>-->
<link href="../../assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <!-- Toastr CSS -->
  <link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-toastr/toastr.min.css">
  <!-- Toastr Custom CSS (fixes display issues) -->
  <link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
  <link rel="stylesheet" type="text/css" href="new-style.css">
  <link rel="stylesheet" type="text/css" href="../chart/Chart.min.css">

</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme">
    
    <?php include 'new_sidenav.php';?>
    
<main class="page-content" style="margin-top:45px;">
   <div class="container-fluid page-border">
      <div class="row">
         <div class="col-12 text-center bg-primary text-white">
            <h4 class="m-t5"><i class="fas fa-edit"></i> Leave</h4>
         </div>
         
         <div class="col-md-6 page-border">
             <div class="row">
                  <div class="col-md-12 form-group">
                    <label>Leave Type</label>
                    <input type="text" class="form-control" placeholder="Leave Type">
                  </div>
             </div>
              <div class="row">
                  <div class="col-md-6 form-group">
                    <label>Date From</label>
                    <input type="Date" class="form-control">
                  </div>
                  <div class="col-md-6 form-group">
                    <label>Date To</label>
                    <input type="date" class="form-control">
                  </div>
             </div>
             <div class="row">
                  <div class="col-md-12 form-group">
                    <label>Remark</label>
                    <textarea class="form-control" rows="8" placeholder="Type Query Here..." style="height:200px !important;"></textarea>
                  </div>
                  <div class="col-md-12 form-group">
                       <input type="file" class="form-control" style="height:45px !important;">
                 </div>
                  <div class="col-md-12 form-group">
                       <button class="btn btn-primary btn-sm float-right" onclick ="Javascript:ValidateQuery();" >Apply</button>
                 </div>
             </div>
     
            
         </div>
          
         <div class="col-md-6 page-border" style="height:550px;overflow-y:scroll;">
             
             <a href="#" class="detail_list custom-jumbo">
               <span style="font-size:12px;margin-left:0px;">Query Type </span>
               <h6 class="m-t5">Query: </h6>
               <h6>Response:</h6>
               <h6><i class="fas fa-calendar-alt"></i></h6>
               
           </a>
          
         </div>
         
        
   
         
          
   
      
            
        
      </div>
     
   </div>
</main>
<!--end page contents-->
</div>
<!-- Toastr JS -->
<script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
<!-- Toastr Configuration -->
<script src="assets/js/toastr-config.js"></script>
</body>
</html>





<!-- The Modal -->
  <div class="modal fade">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Attendance</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <p class="show_attendance"></p>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>