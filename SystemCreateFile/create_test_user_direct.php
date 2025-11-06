<?php
/**
 * Direct Test User Creation - Executes immediately
 * Creates TEST001 user with password test123 for login testing
 */

// Fix path for command line execution
$connection_path = __DIR__ . '/../connection.php';
if (!file_exists($connection_path)) {
    $connection_path = dirname(__DIR__) . '/connection.php';
}
include $connection_path;

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
    die("ERROR: The student_master table does not exist. Please run import_student_master.php first.\n");
}

echo "✓ Table student_master exists\n";

// Check if user already exists
$check_user = mysqli_query($Con, "SELECT `sadmission` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if (mysqli_num_rows($check_user) > 0) {
    echo "⚠ User TEST001 already exists. Updating...\n";
    
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
        echo "✅ User updated successfully!\n";
    } else {
        die("❌ Error updating user: " . mysqli_error($Con) . "\n");
    }
} else {
    echo "📝 Creating new test user...\n";
    
    // Get current date for registration
    $current_date = date('Y-m-d');
    
    // Try to get an active financial year (optional - table may not exist)
    $financial_year = '';
    try {
        $fy_query = mysqli_query($Con, "SELECT `year` FROM `FYmaster` WHERE `Status` = 'Active' LIMIT 1");
        if ($fy_query && mysqli_num_rows($fy_query) > 0) {
            $fy_row = mysqli_fetch_assoc($fy_query);
            $financial_year = $fy_row['year'];
        }
    } catch (Exception $e) {
        // FYmaster table doesn't exist, skip it
        $financial_year = '';
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
        echo "✅ Test user created successfully!\n";
    } else {
        die("❌ Error creating user: " . mysqli_error($Con) . "\nSQL: " . $insert_sql . "\n");
    }
}

// Verify the user
$verify_query = mysqli_query($Con, "SELECT `sadmission`, `sname`, `sclass`, `srollno`, `sfathername`, `erp_status`, `spassword` FROM `student_master` WHERE `sadmission` = '$admission_no'");
if ($verify_query && mysqli_num_rows($verify_query) > 0) {
    $verify_user = mysqli_fetch_assoc($verify_query);
    echo "\n✅ Verification:\n";
    echo "   Admission Number: " . $verify_user['sadmission'] . "\n";
    echo "   Name: " . $verify_user['sname'] . "\n";
    echo "   Class: " . $verify_user['sclass'] . "\n";
    echo "   Roll Number: " . $verify_user['srollno'] . "\n";
    echo "   Father Name: " . $verify_user['sfathername'] . "\n";
    echo "   ERP Status: " . $verify_user['erp_status'] . "\n";
    echo "   Password: " . $verify_user['spassword'] . "\n";
    echo "\n✅ Login Credentials:\n";
    echo "   User ID: TEST001\n";
    echo "   Password: test123\n";
    echo "\n✅ You can now login at: http://localhost/cursorai/Testing/studentportal/Users/Login.php\n";
} else {
    die("❌ Verification failed: User not found after creation.\n");
}
?>

