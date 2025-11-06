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



	$isSubmit=$_REQUEST["isSubmit"] ?? '';
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
        $ssqlRegi="SELECT max(CAST(replace(`query_id`,'Q','') AS SIGNED INTEGER)) FROM `parent_query` as `a`";	
			$rsRegiNo1= mysqli_query($Con, $ssqlRegi);
			if (mysqli_num_rows($rsRegiNo1) > 0)
			{
				while($row2 = mysqli_fetch_row($rsRegiNo1))
				{
							$NewQueryId=$row2[0]+1;
				}
			}
			else
			{
				$NewQueryId=1;
			}
			$NewQueryId="Q".$NewQueryId;
			
		if($subject1!=''){
		$ssql="INSERT INTO `parent_query` (`sadmission`,`sname`,`srollno`,`sclass`,`parentquery`,`query_type`,`query_date`, `Source`,`query_id`) VALUES ('$AdmissionId','$StudentName','$StudentRollNo','$class','$message','$subject1','$query_date' ,'ParentPortal', '$NewQueryId')";
		
		mysqli_query($Con, $ssql) or die(mysqli_error($Con));
		
		echo '<script>
		(function() {
			function showToast() {
				if (typeof toastr !== "undefined") {
					toastr.success("Query Sent Successfully", "Success");
				} else {
					setTimeout(showToast, 100);
				}
			}
			if (document.readyState === "loading") {
				document.addEventListener("DOMContentLoaded", showToast);
			} else {
				showToast();
			}
		})();
		</script>';
		
		}

	
		//$Msg="<center><b>Mail Sent Successfully";
		
	}


$ssql="SELECT `srno`,`sname` ,`srollno`, `sclass` ,`parentquery`,`queryresponse`,`datetime`,`query_type` FROM `parent_query` as `a` where a.`sadmission`= '$AdmissionId' order by datetime desc";
$rsQueryDetail= mysqli_query($Con, $ssql);
$num_rows=0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $SchoolName;?> ||My Query</title>
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
  <!-- Toastr JS (loaded early so it's available for inline scripts) -->
  <script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
  <!-- Toastr Configuration -->
  <script src="assets/js/toastr-config.js"></script>

</head>
<body>


<?php include 'Header/header_new.php';?>


<div class="page-wrapper chiller-theme">
    
    <?php include 'new_sidenav.php';?>
    
    <!-- Inline script to ensure sidebar is visible immediately (runs as soon as DOM element exists) -->
    <script>
    // Immediately set sidebar visible on wide screens (runs before jQuery)
    (function() {
        function showSidebar() {
            var pageWrapper = document.querySelector('.page-wrapper');
            if (pageWrapper && screen.width >= 576) {
                pageWrapper.classList.add('toggled');
            }
        }
        
        // Try immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showSidebar);
        } else {
            showSidebar();
        }
        
        // Also try on window load as backup
        window.addEventListener('load', showSidebar);
    })();
    </script>
    
<main class="page-content" style="margin-top:45px;">
   <div class="container-fluid page-border">
      <div class="row">
         <div class="col-6 col-md-6">
            <h4 class="m-t5"><i class="fas fa-edit"></i> My Query</h4>
         </div>
        <div class="col-6 col-md-6">
                 <button class="btn btn-primary" data-toggle="modal" data-target="#AddQuery">Add Query</button>
             </div>
 <div class="col-md-9 page-border" style="height:550px;overflow-y:scroll;">
     <div class="catgory-section bg-white">
             <?php
				while($row = mysqli_fetch_row($rsQueryDetail))
				{
					$srno=$row[0];
					$sname=$row[1];
					$sclass=$row[2];
					$srollno=$row[3];
					$parentquery=$row[4];
					$queryresponse=$row[5];
					$datetime=$row[6];
					$QueryType=$row[7];
					$num_rows=$num_rows+1;
			?>
             <a href="#" class="detail_list custom-jumbo">
               <span style="font-size:12px;margin-left:0px;">Query Type :<?php echo $QueryType;?> </span>
               <h6 class="m-t5">Query: <?php echo $parentquery;?> </h6>
               <h6>Response:<span style="color:green;font-size: 17px !important"><?php echo $queryresponse ;?></span> </h6>
               <?php
               $currentdate=date("d-m-Y") ;
               ?>
               <h6><i class="fas fa-calendar-alt"></i> <?php echo $datetime;?></h6>
               
           </a>
           <?php
				}
				?>
             
         </div>
         </div>
          <div class="col-md-3 page-border">
              <div class="catgory-section bg-white px-0" style="height:550px;overflow-y:scroll;">
              <h6 class="p-2 dps-border-bottom">Category</h6>
              
              
              <div class="row dps-border-bottom mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-blue-primary">
                              <img src="assets/img/ion_bus-outline.svg" alt="bus">
                          </div>
                          <h6>Transport</h6>
                      </div>
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-blue-primary">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                 <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-green">
                              <img src="assets/img/mingcute_book-5-line.svg" alt="bus">
                          </div>
                          <h6>Studies Related</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-green">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                  <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-red">
                              <img src="assets/img/user-icon.svg" alt="bus">
                          </div>
                          <h6>Admin</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-red">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                  <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-blue">
                              <img src="assets/img/fees.svg" alt="bus">
                          </div>
                          <h6>Fee</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-blue">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                  <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-yellow">
                              <img src="assets/img/report-card-d.svg" alt="bus">
                          </div>
                          <h6>Report Card</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-yellow">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                  <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-dark-red">
                              <img src="assets/img/seek-leave.svg" alt="bus">
                          </div>
                          <h6>Sick Leave</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-dark-red">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-red">
                              <img src="assets/img/user-icon.svg" alt="bus">
                          </div>
                          <h6>General Feedback</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-red">
                          <span>1</span>
                      </div>
                  </div>
              </div>
                <div class="row dps-border-bottom pt-2 mx-0">
                  <div class="col-lg-10 col-md-10">
                      <div class="query-card-left">
                          <div class="query-card dps-light-red">
                              <img src="assets/img/user-icon.svg" alt="bus">
                          </div>
                          <h6>General Query</h6>
                      </div>  
                  </div>
                  <div class="col-lg-2 col-md-2">
                      <div class="counter-data dps-light-red">
                          <span>1</span>
                      </div>
                  </div>
              </div>
              </div>
         </div>
     </div>
     
   </div>
   
   
   
   <!---------------------------query modal---------------->
   <!-- Modal -->
<div class="modal fade" id="AddQuery" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Query</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form name="frmEMail" id="frmEMail" method="post" class="form-horizontal">
                   <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
                   <h6> Student Name-<?php echo $StudentName;?> <br>Class <?php echo $class;?></h6>
                   <select class="form-control" name="cboSubject" id="cboSubject">
                      <option selected="" value="Studies Related">Studies Related</option>
                      <option value="Transport">Transport</option>
                      <option value="Admin">Admin</option>
                      <option value="Fee">Fee</option>
                      <option value="Report Card">Report Card</option>
                      <option value="Sick Leave">Sick Leave</option>
                      <option value="General Feedback">General Feedback</option>
                      <option value="General Query">General Query</option>
                   </select>
                   <textarea class="form-control m-t10" rows="8" placeholder="Type Query Here..." id="txtQuery" name="txtQuery"  style="height:200px !important;"></textarea>
           
              </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button class="btn btn-primary btn-sm float-right" onclick ="Javascript:ValidateQuery();" >Send Query</button>
      </div>
    </div>
  </div>
</div>
   <!-----------------------end query modal--------------------->
</main>
<!--end page contents-->
</div>
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