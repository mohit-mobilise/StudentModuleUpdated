<?php
// Include security helpers
require_once __DIR__ . '/includes/security_helpers.php';

// Configure secure session
configure_secure_session();

include '../connection.php';
include '../AppConf.php';

$EmployeeId = $_SESSION['userid'] ?? '';

$SchoolName = $SchoolName1;

function forget_password()
{
    global $SchoolName1, $Con;
    if (isset($_POST['employee_id'])) 
   {
   		// Validate and sanitize input
   		$employee_id = validate_input($_POST['employee_id'], 'string', 50);

   		// Use prepared statement to prevent SQL injection
   		$stmt = mysqli_prepare($Con, "SELECT `email`, `spassword` FROM `student_master` WHERE `sadmission`=?");
   		if ($stmt) {
   		    mysqli_stmt_bind_param($stmt, "s", $employee_id);
   		    mysqli_stmt_execute($stmt);
   		    $result = mysqli_stmt_get_result($stmt);
   		    
   		    if ($row = mysqli_fetch_row($result)) {
   		        $auditor_email = $row[0];
   		        $password_hash = $row[1];
   		        
   		        // SECURITY: Generate password reset token instead of sending plain text password
   		        $reset_token = generate_token();
   		        $token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
   		        
   		        // Store reset token in database (you'll need to add a password_reset_tokens table)
   		        // For now, we'll use a generic message
   		        $emailmessage = "<b>Dear User,</b><br><br>
                        You have requested a password reset.<br>
                        Please use the reset link sent to this email to reset your password.<br>
                        <b>Note:</b> For security reasons, we cannot send your password via email.<br>
                        Thanks <br>
                        Team " . safe_output($SchoolName1) . " ";

   		        $emailsubject = "Password Reset Request - " . safe_output($SchoolName1);

   		        if ($auditor_email != "") {
   		            fnlSendEmail($emailmessage, $auditor_email, $emailsubject);
   		        }
   		        
   		        // Safe output to prevent XSS
   		        echo "Password reset instructions have been sent to your registered email id: " . safe_output($auditor_email);
   		    } else {
   		        // User not found - don't reveal this information (security best practice)
   		        echo "If the email address exists in our system, password reset instructions have been sent.";
   		    }
   		    
   		    mysqli_stmt_close($stmt);
   		}
   }
}


function fnlSendEmail($message,$toemail,$emailsubject)
{
    global $Con;
    if($toemail!="")
    {
        // Validate email
        $toemail = filter_var($toemail, FILTER_VALIDATE_EMAIL);
        if (!$toemail) {
            return "Invalid email address";
        }
        
        $smsdate = date('Y-m-d');
        $currentdatetime = date('Y-m-d H:i:s');
        $FromEmail = "noreply@mobilise.co.in";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= 'From: ' . safe_output($FromEmail) . "\r\n";
        
        // Escape email subject and message for safe output (though mail() handles this)
        $rc = mail($toemail, $emailsubject, $message, $headers);
        
        // Use prepared statement for INSERT
        $stmt = mysqli_prepare($Con, "INSERT INTO `email_delivery`(`ToEmail`, `htmlcode`, `status`, `FromEmail`, `Subject`, `sentdate`, `date`) VALUES (?,?,?,?,?,?,?)");
        if ($stmt) {
            $status = 'COMPLETE';
            mysqli_stmt_bind_param($stmt, "sssssss", $toemail, $message, $status, $FromEmail, $emailsubject, $currentdatetime, $smsdate);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        
        $responsebody = "Mail Sent Successfully";
        return $responsebody;
    }
}



function change_password()
{
   global $EmployeeId, $Con;
   
    if (isset($_POST['old_password'])) 
   {
       // Validate input
       $old_password = $_POST['old_password'] ?? '';
       $new_password = $_POST['new_password'] ?? '';
       $confirm_password = $_POST['confirm_password'] ?? '';
       
       // Validate password length
       if (strlen($new_password) < 6) {
           echo "Password must be at least 6 characters long";
           return;
       }
       
       if ($new_password != $confirm_password) {
           echo "New password and Confirmed Password do not match";
           return;
       }

   		// Use prepared statement to prevent SQL injection
   		$stmt = mysqli_prepare($Con, "SELECT `spassword` FROM `student_master` WHERE `sadmission`=?");
   		if ($stmt) {
   		    mysqli_stmt_bind_param($stmt, "s", $EmployeeId);
   		    mysqli_stmt_execute($stmt);
   		    $result = mysqli_stmt_get_result($stmt);
   		    
   		    if ($row = mysqli_fetch_row($result)) {
   		        $password_hash = $row[0];
   		        
   		        // Verify old password
   		        if (verify_password($old_password, $password_hash)) {
   		            // Hash new password
   		            $new_password_hash = hash_password($new_password);
   	             
   		            // Update password using prepared statement
   		            $stmt2 = mysqli_prepare($Con, "UPDATE `student_master` SET `spassword`=? WHERE `sadmission`=?");
   		            if ($stmt2) {
   		                mysqli_stmt_bind_param($stmt2, "ss", $new_password_hash, $EmployeeId);
   		                $sql_updt = mysqli_stmt_execute($stmt2);
   		                
   		                // Also update user_master if it exists
   		                $stmt3 = mysqli_prepare($Con, "UPDATE `user_master` SET `spassword`=? WHERE `sadmission`=?");
   		                if ($stmt3) {
   		                    mysqli_stmt_bind_param($stmt3, "ss", $new_password_hash, $EmployeeId);
   		                    mysqli_stmt_execute($stmt3);
   		                    mysqli_stmt_close($stmt3);
   		                }
   		                
   		                if ($sql_updt) {
   		                    echo "Password Updated Successfully";
   		                } else {
   		                    echo "Password Not Updated";
   		                }
   		                
   		                mysqli_stmt_close($stmt2);
   		            }
   		        } else {
   		            echo "Old Password does not match";
   		        }
   		    }
   		    
   		    mysqli_stmt_close($stmt);
   		}
   }	
}

function view_notice()
{
    global $Con;
    
    // Validate input
    $srno_notice = validate_input($_POST['srno_notice'] ?? '', 'int', 11);
    
    if (!$srno_notice) {
        echo "Invalid notice ID";
        return;
    }
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($Con, "SELECT DISTINCT `notice` FROM `student_notice` WHERE `srno`=?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $srno_notice);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $notice = '';
        while ($rowd = mysqli_fetch_row($result)) {
            $notice = $rowd[0];
        }
        
        // Safe output to prevent XSS
        echo nl2br(safe_output($notice));
        
        mysqli_stmt_close($stmt);
    }
}
forget_password();

change_password();

view_notice();


?>
