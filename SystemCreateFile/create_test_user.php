<?php
/**
 * Script to create a test user for login testing
 * Run this file once to create a test student account
 * 
 * Test Credentials:
 * User ID (Admission Number): TEST001
 * Password: test123
 */

require_once 'connection.php';

// Test user details
$sadmission = 'TEST001';
$suser = 'TEST001';
$spassword = 'test123';
$sname = 'Test Student';
$sclass = '10';
$srollno = '01';
$sfathername = 'Test Father';
$erp_status = 'Active';

// Check if test user already exists
$checkQuery = "SELECT `sadmission` FROM `student_master` WHERE `sadmission` = '$sadmission'";
$checkResult = mysqli_query($Con, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Update existing test user
    $updateQuery = "UPDATE `student_master` SET 
        `suser` = '$suser',
        `spassword` = '$spassword',
        `sname` = '$sname',
        `sclass` = '$sclass',
        `srollno` = '$srollno',
        `sfathername` = '$sfathername',
        `erp_status` = '$erp_status'
        WHERE `sadmission` = '$sadmission'";
    
    if (mysqli_query($Con, $updateQuery)) {
        echo "<h2 style='color: green;'>Test User Updated Successfully!</h2>";
        echo "<p><strong>User ID (Admission Number):</strong> $sadmission</p>";
        echo "<p><strong>Password:</strong> $spassword</p>";
        echo "<p><strong>Name:</strong> $sname</p>";
        echo "<p><strong>Class:</strong> $sclass</p>";
    } else {
        echo "<h2 style='color: red;'>Error updating test user: " . mysqli_error($Con) . "</h2>";
    }
} else {
    // Insert new test user with minimal required columns
    // Using INSERT IGNORE to handle any column issues gracefully
    $insertQuery = "INSERT INTO `student_master` 
        (`sadmission`, `suser`, `spassword`, `sname`, `sclass`, `srollno`, `sfathername`, `erp_status`) 
        VALUES 
        ('$sadmission', '$suser', '$spassword', '$sname', '$sclass', '$srollno', '$sfathername', '$erp_status')";
    
    if (mysqli_query($Con, $insertQuery)) {
        echo "<h2 style='color: green;'>✅ Test User Created Successfully!</h2>";
        echo "<div style='background: #e8f5e9; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
        echo "<h3>Test Login Credentials:</h3>";
        echo "<p><strong>User ID (Admission Number):</strong> <code style='background: #fff; padding: 5px 10px; border-radius: 3px;'>$sadmission</code></p>";
        echo "<p><strong>Password:</strong> <code style='background: #fff; padding: 5px 10px; border-radius: 3px;'>$spassword</code></p>";
        echo "<p><strong>Student Name:</strong> $sname</p>";
        echo "<p><strong>Class:</strong> $sclass</p>";
        echo "</div>";
        echo "<hr>";
        echo "<p><strong>📍 You can now login with these credentials at:</strong></p>";
        echo "<p><a href='Users/index.php' style='color: #1976d2; font-weight: bold;'>🔗 Go to Login Page</a></p>";
        echo "<p style='color: #666; font-size: 12px; margin-top: 20px;'>⚠️ Note: Please remove or disable this test user in production environment.</p>";
    } else {
        $error = mysqli_error($Con);
        echo "<h2 style='color: red;'>❌ Error creating test user</h2>";
        echo "<p style='background: #ffebee; padding: 15px; border-radius: 5px;'>$error</p>";
        echo "<p><strong>Possible solutions:</strong></p>";
        echo "<ul>";
        echo "<li>Check if the <code>student_master</code> table exists</li>";
        echo "<li>Verify required columns exist in the table</li>";
        echo "<li>Check database connection settings in connection.php</li>";
        echo "<li>Review database user permissions</li>";
        echo "</ul>";
    }
}

mysqli_close($Con);
?>

