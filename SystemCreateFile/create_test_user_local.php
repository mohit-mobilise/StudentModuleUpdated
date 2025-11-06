<?php
/**
 * Create Test User in Local Database
 * Creates TEST001 user with password test123 for login testing
 */

include '../connection.php';

echo "<html><head><title>Create Test User</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    h1{color:#007bff;}
    .box{border:2px solid #007bff;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
</style>";
echo "</head><body>";

echo "<h1>👤 Create Test User in Local Database</h1>";

// Test user credentials
$admission_no = 'TEST001';
$password = 'test123';
$student_name = 'Test User';
$student_class = '1';
$roll_no = '1';
$father_name = 'Test Father';
$erp_status = 'Active';

// Check if table exists
$check_table = mysqli_query($Con, "SHOW TABLES LIKE 'student_master'");
if (mysqli_num_rows($check_table) == 0) {
    die("<div class='error'><h3>❌ Table Not Found</h3><p>The <code>student_master</code> table does not exist in the database.</p><p>Please run the import script first: <a href='import_student_master.php'>import_student_master.php</a></p></div></body></html>");
}

echo "<div class='success'>✅ Table <code>student_master</code> exists</div>";

// Check if user already exists
$check_user = mysqli_query($Con, "SELECT `sadmission`, `sname`, `sclass` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_user) > 0) {
    $existing_user = mysqli_fetch_assoc($check_user);
    echo "<div class='warning'><h3>⚠️ User Already Exists</h3>";
    echo "<p><strong>Admission Number:</strong> " . htmlspecialchars($existing_user['sadmission']) . "</p>";
    echo "<p><strong>Name:</strong> " . htmlspecialchars($existing_user['sname']) . "</p>";
    echo "<p><strong>Class:</strong> " . htmlspecialchars($existing_user['sclass']) . "</p>";
    echo "<p>Updating user with new credentials...</p>";
    echo "</div>";
    
    // Update existing user
    $update_sql = "UPDATE `student_master` SET 
        `spassword` = '$password',
        `sname` = '$student_name',
        `sclass` = '$student_class',
        `srollno` = '$roll_no',
        `sfathername` = '$father_name',
        `erp_status` = '$erp_status',
        `suser` = '$admission_no'
        WHERE `sadmission` = '$admission_no'";
    
    if (mysqli_query($Con, $update_sql)) {
        echo "<div class='success'><h3>✅ User Updated Successfully!</h3>";
        echo "<p><strong>Admission Number:</strong> <code>$admission_no</code></p>";
        echo "<p><strong>Password:</strong> <code>$password</code></p>";
        echo "<p><strong>Name:</strong> $student_name</p>";
        echo "<p><strong>Class:</strong> $student_class</p>";
        echo "<p><strong>ERP Status:</strong> $erp_status</p>";
        echo "<p>You can now login with these credentials.</p>";
        echo "</div>";
    } else {
        echo "<div class='error'><h3>❌ Error Updating User</h3><p>Error: " . mysqli_error($Con) . "</p></div>";
    }
} else {
    // Insert new user
    echo "<div class='info'>📝 Creating new test user...</div>";
    
    // Get current date for registration
    $current_date = date('Y-m-d');
    
    // Try to get an active financial year
    $fy_query = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status` = 'Active' LIMIT 1");
    $financial_year = '';
    if ($fy_query && mysqli_num_rows($fy_query) > 0) {
        $fy_row = mysqli_fetch_assoc($fy_query);
        $financial_year = $fy_row['year'];
    }
    
    // Insert query with minimal required fields
    $insert_sql = "INSERT INTO `student_master` (
        `sadmission`,
        `suser`,
        `spassword`,
        `sname`,
        `sclass`,
        `srollno`,
        `sfathername`,
        `erp_status`,
        `status`,
        `DateOfAdmission`,
        `FinancialYear`
    ) VALUES (
        '$admission_no',
        '$admission_no',
        '$password',
        '$student_name',
        '$student_class',
        '$roll_no',
        '$father_name',
        '$erp_status',
        'Active',
        '$current_date',
        '$financial_year'
    )";
    
    if (mysqli_query($Con, $insert_sql)) {
        echo "<div class='success'><h3>✅ Test User Created Successfully!</h3>";
        echo "<p><strong>Admission Number:</strong> <code>$admission_no</code></p>";
        echo "<p><strong>Password:</strong> <code>$password</code></p>";
        echo "<p><strong>Name:</strong> $student_name</p>";
        echo "<p><strong>Class:</strong> $student_class</p>";
        echo "<p><strong>Roll Number:</strong> $roll_no</p>";
        echo "<p><strong>Father Name:</strong> $father_name</p>";
        echo "<p><strong>ERP Status:</strong> $erp_status</p>";
        echo "<p>You can now login with these credentials at: <a href='../Users/Login.php' target='_blank'>Login Page</a></p>";
        echo "</div>";
    } else {
        echo "<div class='error'><h3>❌ Error Creating User</h3><p>Error: " . mysqli_error($Con) . "</p>";
        echo "<p><strong>SQL Query:</strong> <code>" . htmlspecialchars($insert_sql) . "</code></p>";
        echo "</div>";
    }
}

// Verify the user
$verify_query = mysqli_query($Con, "SELECT `sadmission`, `sname`, `sclass`, `srollno`, `sfathername`, `erp_status` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if ($verify_query && mysqli_num_rows($verify_query) > 0) {
    $verify_user = mysqli_fetch_assoc($verify_query);
    echo "<div class='box'><h2>✅ Verification</h2>";
    echo "<p><strong>User verified in database:</strong></p>";
    echo "<ul>";
    echo "<li><strong>Admission Number:</strong> " . htmlspecialchars($verify_user['sadmission']) . "</li>";
    echo "<li><strong>Name:</strong> " . htmlspecialchars($verify_user['sname']) . "</li>";
    echo "<li><strong>Class:</strong> " . htmlspecialchars($verify_user['sclass']) . "</li>";
    echo "<li><strong>Roll Number:</strong> " . htmlspecialchars($verify_user['srollno']) . "</li>";
    echo "<li><strong>Father Name:</strong> " . htmlspecialchars($verify_user['sfathername']) . "</li>";
    echo "<li><strong>ERP Status:</strong> " . htmlspecialchars($verify_user['erp_status']) . "</li>";
    echo "</ul>";
    echo "<p><strong>Login Credentials:</strong></p>";
    echo "<ul>";
    echo "<li><strong>User ID (Admission Number):</strong> <code>$admission_no</code></li>";
    echo "<li><strong>Password:</strong> <code>$password</code></li>";
    echo "</ul>";
    echo "<p><a href='../Users/Login.php' target='_blank' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>Go to Login Page</a></p>";
    echo "</div>";
}

echo "</body></html>";
?>

