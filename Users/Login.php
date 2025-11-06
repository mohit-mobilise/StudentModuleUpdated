<?php 
// Include security helpers
if (file_exists(__DIR__ . '/includes/security_helpers.php')) {
    require_once __DIR__ . '/includes/security_helpers.php';
} else {
    // Fallback: try root includes directory
    if (file_exists(__DIR__ . '/../includes/security_helpers.php')) {
        require_once __DIR__ . '/../includes/security_helpers.php';
    } else {
        die('Error: security_helpers.php not found. Please ensure the file exists in Users/includes/ or includes/ directory.');
    }
}

// Configure secure session
if (function_exists('configure_secure_session')) {
    configure_secure_session();
} else {
    // Fallback session configuration
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

include '../connection.php';
?>
<?php

// Function to get the client ip address
function get_client_ip_server() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']) && $_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && $_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']) && $_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
$currentdatetime=date('Y-m-d H:i:s');
$currentdate=date('Y-m-d');
$currenttime=date('H:i:s');
$ClientIPAddress=get_client_ip_server();

$useragent=$_SERVER['HTTP_USER_AGENT'] ?? '';

// Validate and sanitize input
$suser = validate_input($_REQUEST["txtUserId"] ?? '', 'string', 50);
$spassword = $_REQUEST["txtPassword"] ?? '';

if ($suser != "")
{
   // Use prepared statement to prevent SQL injection
   $stmt = mysqli_prepare($Con, "SELECT `suser`,`spassword`,`sname`,`sclass`,`srollno`,`sfathername`,`erp_status` FROM `student_master` WHERE `sadmission`=?");
   if ($stmt) {
       mysqli_stmt_bind_param($stmt, "s", $suser);
       mysqli_stmt_execute($stmt);
       $result = mysqli_stmt_get_result($stmt);
       $num_rows = mysqli_num_rows($result);
       
       if ($num_rows > 0) {
           $row = mysqli_fetch_row($result);
           $password_hash = $row[1];
           $StudentName = $row[2];
           $StudentClass = $row[3];
           $StudentRollNo = $row[4];
           $StudentFatherName = $row[5];
           $erp_status = $row[6];
           
           // Verify password (handles both hashed and plain text for migration)
           $password_valid = verify_password($spassword, $password_hash);
           
           if ($password_valid) {
               if ($erp_status == 'InActive') {
                   echo '<script>
                   (function() {
                       function showToast() {
                           if (typeof toastr !== "undefined") {
                               toastr.warning("Your userid is currently inactive", "Warning");
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
               } else {
                   // Set session variables with safe output
                   $_SESSION['userid'] = $suser;
                   $_SESSION['StudentName'] = $StudentName;
                   $_SESSION['StudentClass'] = $StudentClass;
                   $_SESSION['StudentRollNo'] = $StudentRollNo;
                   $_SESSION['StudentFatherName'] = $StudentFatherName;
                   $_SESSION['erp_status'] = $erp_status;
                   
                   // Regenerate session ID to prevent session fixation
                   regenerate_session_id();
                   
                   header('location:../Users/landing.php');
                   die();
               }
           } else {
               echo '<script>
               (function() {
                   function showToast() {
                       if (typeof toastr !== "undefined") {
                           toastr.error("Password does not match ! Please Try Again", "Error");
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
       } else {
           echo '<script>
           (function() {
               function showToast() {
                   if (typeof toastr !== "undefined") {
                       toastr.error("User Does Not Exist ! Please Try Again", "Error");
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
       
       mysqli_stmt_close($stmt);
   } else {
       // Error in prepared statement
       error_log("Login prepared statement error: " . mysqli_error($Con));
       echo '<script>
       (function() {
           function showToast() {
               if (typeof toastr !== "undefined") {
                   toastr.error("System error. Please try again later.", "Error");
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
}
// Password change request - SECURITY: This should use tokens, not send plain text passwords
if(($_REQUEST["isSubmitChange"] ?? '') =="ChangePwd" && ($_REQUEST["txtcuser"] ?? '') !="")
{
   $ChangeUserId = validate_input($_REQUEST["txtcuser"] ?? '', 'string', 50);
   
   // Use prepared statement to prevent SQL injection
   $stmt = mysqli_prepare($Con, "SELECT `sname`,`smobile`,`spassword` FROM `student_master` WHERE `sadmission`=?");
   if ($stmt) {
       mysqli_stmt_bind_param($stmt, "s", $ChangeUserId);
       mysqli_stmt_execute($stmt);
       $result = mysqli_stmt_get_result($stmt);
       
       if ($rowE = mysqli_fetch_row($result)) {
           $Name = $rowE[0];
           $MobileNo = $rowE[1];
           $Pwd = $rowE[2];
           $currentdate = Date('Y-m-d');
           
           // SECURITY WARNING: Sending plain text password via SMS is insecure
           // TODO: Implement password reset token system instead
           $Msg = "Your password reset request has been received. Please use the reset link sent to your registered email.";
           
           // Use prepared statement for INSERT
           $stmt2 = mysqli_prepare($Con, "INSERT INTO `sms_logs` (`sname`, `smstype`, `mobileno`, `message`, `sentdate`) VALUES (?,?,?,?,?)");
           if ($stmt2) {
               $sms_type = "Password SMS";
               mysqli_stmt_bind_param($stmt2, "sssss", $Name, $sms_type, $MobileNo, $Msg, $currentdate);
               mysqli_stmt_execute($stmt2);
               mysqli_stmt_close($stmt2);
           }
       }
       
       mysqli_stmt_close($stmt);
   }
}
?>


<script language="javascript">
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ''); };

function Validate()
{
    var userId = document.getElementById("txtUserId").value.trim();
    var password = document.getElementById("txtPassword").value.trim();
    
    if(userId == "")
    {
        toastr.warning("Please Enter User ID", "Validation Error");
        document.getElementById("txtUserId").focus();
        return false;
    }
    
    if(password == "")
    {
        toastr.warning("Please Enter Password", "Validation Error");
        document.getElementById("txtPassword").focus();
        return false;
    }
    
    return true;
}

function ValidatePwd()
{
if(document.getElementById("txtcpass").value!=document.getElementById("txtcrpass").value)
   {
      toastr.error("Password does not match", "Error");
      return;
   }


}


function ValidateChangePwd()
{

if(document.getElementById("txtcuser").value.trim()=="")
   {
      toastr.warning("Please Enter User Id", "Validation Error");
      return;
   }
   if(document.getElementById("txtcpass").value.trim()=="")
   {
      toastr.warning("Please Enter Password", "Validation Error");
      return;
   }
   if(document.getElementById("txtcrpass").value.trim()=="")
   {
      toastr.warning("Please Retype the Password", "Validation Error");
      return;
   }


   document.getElementById("Changepwd").submit();


}
</script>


<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Page</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
  <!-- Toastr CSS -->
  <link rel="stylesheet" type="text/css" href="../assets/global/plugins/bootstrap-toastr/toastr.min.css">
  <!-- Toastr Custom CSS (fixes display issues) -->
  <link rel="stylesheet" type="text/css" href="assets/css/toastr-custom.css">
  <!--<link rel="stylesheet" type="text/css" href="new-style.css">-->
 <link rel="stylesheet" type="text/css" href="assets/css/dps-users-style.css">
 <link rel="stylesheet" type="text/css" href="assets/css/open-sans.css">
  <!-- Toastr JS (loaded early so it's available for PHP echo scripts) -->
  <script src="../assets/global/plugins/bootstrap-toastr/toastr.min.js"></script>
  <!-- Toastr Configuration -->
  <script src="assets/js/toastr-config.js"></script>
</head>  
<body>



  <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-6 left-container h-auto left-side-bg h-100">
                <div class="image_login text-center d-none d-lg-block h-100">
                    <div class="log-image"></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 right-content-box bg-white right-container">
                <div class="login-body-text">
                 <div class="text-center mt-5 mb-3">
                        <img src="assets/image/logo_new.svg" alt="school logo" style="max-width: 300px;">
                    </div>
                         <div class="paper paper-color text-center">
                           <h2>Welcome to ERP Portal <br>
                           <!--<i class="fa fa-graduation-cap" aria-hidden="true"></i>-->
                           </h2>
                           <!--<h4><?php echo $SchoolName2;?></h4>-->
                        </div>
                     <form name="frmLogin" id="frmLogin" method="post" class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return Validate();">
                     <input type="hidden" name="isSubmit" id="isSubmit" value="yes">
                     
                     <div class="form-group">
                         <label for="txtUserId">User ID / Admission Number</label>
                         <input type="text" class="form-control" id="txtUserId" name="txtUserId" placeholder="Enter User ID" required>
                     </div>
                     
                     <div class="form-group">
                         <label for="txtPassword">Password</label>
                         <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Enter Password" required>
                     </div>
                     
                    <div class="form-group text-center mb-2 mt-3">
                        <button class="btn btn-primary account-btn mt-3 w-100" type="submit"> 
                        Login</button>
                        <!--<button style="border:none;background-color:white" class="my-3"><a data-toggle="modal" href="#edit_forget_modal"><i class="edit_exam_btn_data" data-edit_srno="<?php echo $srno ;?>" value="<?php echo $srno ;?>"></i>-->
                        <!--      Forgot password ?-->
                        <!--  </a></button>-->
                     </div>
                    <div class="account-footer" >
                        <center>
                          <a href="https://apps.apple.com/us/app/dps-rkp-teacher/id6742093768" target="blank"><img src="assets/image/apple.svg" width="50px" height="40px"></a> 
                          <a href="https://play.google.com/store/apps/details?id=com.rkpdpsteacher.mbl" target="blank"><img src="assets/image/android.png" width="50px" height="40px">
                          <a href="https://dpsrkp.net/" target="blank"><img src="assets/image/website.png" width="50px" height="40px" ></a>
                          <a href="https://www.youtube.com/channel/UCEDJt1jGmDt_BT8W_Ei5Ebg/videos" target="blank"> <img src="assets/image/youtube.png" width="50px" height="40px" ></a><br>
                         
                          </center>
                      </div>
                     
                 </form>
                 </div>
                 <div class="footer-text">
                  <p class="mobilise-power-by mb-0"><stront>© 2024 All rights reserved . Powered by</stront></p>
                   <a href="https://mobilise.co.in/" target="_blank"><img src="assets/image/mobilise-power.png" width="100px" alt="Mobilise App"></a>
                 </div>      
             </div>
               
        </div>
    </div> 
 </body>
</html>

<div class="modal fade " id="edit_forget_modal"  role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span aria-hidden="true">&times;</span></button>
                          <h5 class="modal-title">Forget Password </h5>
                        </div>
                        <!-- BEGIN FORM-->
                        <form action="" id="" class="horizontal-form" name='' method="post" enctype="multipart/form-data">
                           
                        <div class="modal-body">

                            <div class="row">
                            <div class="col-md-12">
                           <p>
                              
                           <input class="form-control" type="hidden" id="edit_srno" name="edit_srno">
                           </p>
                           <p>
                          <h5>UserId </h5>
                            <input type="text" name="employee_id" id="employee_id" class="form-control">
                          </p>

                          
                       </div>
                              </div>
                        </div><!--end of modal-body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary"  name="submit_forget_password" id="submit_forget_password"  >Send </button>
                            <button type="button" class="btn btn-outlined" data-dismiss="modal" >Close</button>
                        </div>
                    </form>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
      </div>
   </div>
<script>
$(document).ready(function(){
$('#submit_forget_password').click(function(){
   var employee_id=$('#employee_id').val();
   $.ajax({
               type:'POST',
               url:'submit_forget_password_users.php',
               data:{employee_id:employee_id},
               success:function(response){
                  alert(response);
                  location.reload(true);
       

               }
        });

 });
 }); 
</script>
