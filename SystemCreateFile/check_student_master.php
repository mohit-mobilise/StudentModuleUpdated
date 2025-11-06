<?php
/**
 * Quick Check for student_master table
 * Verifies if the critical student_master table exists
 */

$database_name = "schoolerpbeta";
$localhost_username = "root";
$localhost_password = "";

header('Content-Type: text/html; charset=UTF-8');

echo "<html><head><title>Table Check - student_master</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    h1{color:#007bff;}
    .btn{background:#007bff;color:white;padding:12px 24px;text-decoration:none;border-radius:5px;display:inline-block;margin:10px 5px;}
    .btn:hover{background:#0056b3;}
</style>";
echo "</head><body>";

echo "<h1>🔍 Checking student_master Table</h1>";

// Connect to database
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p></div></body></html>");
}

mysqli_select_db($conn, $database_name);

// Check if student_master exists
$check = mysqli_query($conn, "SHOW TABLES LIKE 'student_master'");

if (mysqli_num_rows($check) > 0) {
    echo "<div class='success'>";
    echo "<h3>✅ student_master Table EXISTS</h3>";
    
    $count_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `student_master`");
    $count_row = mysqli_fetch_assoc($count_query);
    $row_count = number_format($count_row['cnt']);
    
    echo "<p><strong>Row Count:</strong> $row_count</p>";
    echo "<p>The table is present and working correctly.</p>";
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h3>❌ student_master Table MISSING</h3>";
    echo "<p>The <code>student_master</code> table does not exist in your database.</p>";
    echo "<p><strong>This is why you're seeing the error on Attendance.php</strong></p>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h3>🔧 Solution</h3>";
    echo "<p>You need to import/create the missing tables from your SQL backup file.</p>";
    echo "<p><strong>Option 1: Run Table Synchronization (Recommended)</strong></p>";
    echo "<p>This will automatically find and create all missing tables from your backup file:</p>";
    echo "<a href='compare_and_sync_tables.php' class='btn'>🚀 Run Table Synchronization</a>";
    echo "</div>";
    
    // Check total tables
    $tables_query = mysqli_query($conn, "SHOW TABLES");
    $total_tables = mysqli_num_rows($tables_query);
    
    echo "<div class='info'>";
    echo "<p><strong>Current Database Status:</strong></p>";
    echo "<p>Total tables in database: <strong>$total_tables</strong></p>";
    if ($total_tables == 0) {
        echo "<p class='error'>⚠️ Database is empty! You need to import the SQL file.</p>";
    }
    echo "</div>";
}

// List some other critical tables
echo "<div class='info'>";
echo "<h3>📋 Checking Other Critical Tables</h3>";

$critical_tables = ['class_master', 'user_authentication', 'menu_portal_master', 'attendance', 'FYmaster'];
echo "<ul>";
foreach ($critical_tables as $table) {
    $check_table = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check_table) > 0) {
        echo "<li><code>$table</code> - ✅ EXISTS</li>";
    } else {
        echo "<li><code>$table</code> - ❌ MISSING</li>";
    }
}
echo "</ul>";
echo "</div>";

mysqli_close($conn);
echo "</body></html>";
?>


