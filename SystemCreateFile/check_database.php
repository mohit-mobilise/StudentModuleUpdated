<?php
/**
 * Database Verification Script
 * This script checks if all tables were imported successfully
 */

$localhost_username = "root";
$localhost_password = ""; 
$database_name = "schoolerpbeta";

echo "<html><head><title>Database Check</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    h1{color:#007bff;}
    .box{border:2px solid #007bff;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    table{border-collapse:collapse;width:100%;margin:20px 0;}
    th{background:#007bff;color:white;padding:12px;text-align:left;}
    td{padding:10px;border-bottom:1px solid #ddd;}
    tr:hover{background:#f5f5f5;}
    .table-count{font-size:24px;font-weight:bold;color:#28a745;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
</style>";
echo "</head><body>";

echo "<h1>🔍 Database Verification</h1>";
echo "<div class='info'><strong>Database:</strong> $database_name<br><strong>Server:</strong> localhost (Port 3308)</div>";

// Connect to database
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p><p>Please make sure MySQL is running in XAMPP.</p></div></body></html>");
}

echo "<div class='success'>✅ Connected to MySQL server</div>";

// Check if database exists
$db_check = mysqli_query($conn, "SHOW DATABASES LIKE '$database_name'");
if (mysqli_num_rows($db_check) == 0) {
    die("<div class='error'><h3>❌ Database Not Found</h3><p>The database '$database_name' does not exist.<br>Please create it first using setup_database.php or phpMyAdmin.</p></div></body></html>");
}

echo "<div class='success'>✅ Database '$database_name' exists</div>";

// Select database
mysqli_select_db($conn, $database_name);

// Get all tables
$tables_query = mysqli_query($conn, "SHOW TABLES");
$table_count = mysqli_num_rows($tables_query);

echo "<div class='box'>";
echo "<h2>📊 Database Status</h2>";
echo "<div class='table-count'>Total Tables: $table_count</div>";

if ($table_count == 0) {
    echo "<div class='warning'>";
    echo "<h3>⚠️ No Tables Found</h3>";
    echo "<p>The database exists but has no tables.</p>";
    echo "<p><strong>This means the SQL file has not been imported yet.</strong></p>";
    echo "<p>Please run the import script: <a href='import_sql_direct.php'>import_sql_direct.php</a></p>";
    echo "<p>Or use phpMyAdmin Import feature.</p>";
    echo "</div>";
} else {
    echo "<div class='success'>✅ Tables have been imported!</div>";
    
    // List all tables
    echo "<h3>📋 Tables in Database:</h3>";
    echo "<div style='max-height:500px;overflow-y:auto;border:1px solid #ccc;padding:10px;background:#f8f9fa;'>";
    echo "<table>";
    echo "<tr><th>#</th><th>Table Name</th><th>Rows</th><th>Size</th></tr>";
    
    $table_num = 0;
    while ($row = mysqli_fetch_array($tables_query)) {
        $table_num++;
        $table_name = $row[0];
        
        // Get row count
        $count_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `$table_name`");
        $count_row = mysqli_fetch_assoc($count_query);
        $row_count = number_format($count_row['cnt']);
        
        // Get table size
        $size_query = mysqli_query($conn, "
            SELECT 
                ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb 
            FROM information_schema.TABLES 
            WHERE table_schema = '$database_name' 
            AND table_name = '$table_name'
        ");
        $size_row = mysqli_fetch_assoc($size_query);
        $table_size = $size_row['size_mb'] ?? '0.00';
        
        echo "<tr>";
        echo "<td>$table_num</td>";
        echo "<td><strong>$table_name</strong></td>";
        echo "<td>$row_count</td>";
        echo "<td>" . number_format($table_size, 2) . " MB</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
    // Check for key tables
    echo "<h3>🔑 Key Tables Check:</h3>";
    $key_tables = ['student_master', 'class_master', 'fees', 'attendance', 'menu_portal_master'];
    $found_tables = [];
    $missing_tables = [];
    
    mysqli_data_seek($tables_query, 0);
    $all_tables = [];
    while ($row = mysqli_fetch_array($tables_query)) {
        $all_tables[] = $row[0];
    }
    
    foreach ($key_tables as $key_table) {
        if (in_array($key_table, $all_tables)) {
            $found_tables[] = $key_table;
            echo "<p class='success'>✅ $key_table</p>";
        } else {
            $missing_tables[] = $key_table;
            echo "<p class='warning'>⚠️ $key_table (not found)</p>";
        }
    }
    
    if (count($found_tables) == count($key_tables)) {
        echo "<div class='success' style='margin-top:20px;font-size:18px;padding:20px;'>";
        echo "<strong>✅ All Key Tables Present!</strong><br>";
        echo "The database import appears to be complete.";
        echo "</div>";
    } else {
        echo "<div class='warning' style='margin-top:20px;'>";
        echo "<strong>⚠️ Some Key Tables Missing</strong><br>";
        echo "The import may not be complete. You may need to run the import again.";
        echo "</div>";
    }
}

echo "</div>";

// Database size summary
$db_size_query = mysqli_query($conn, "
    SELECT 
        ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
    FROM information_schema.TABLES 
    WHERE table_schema = '$database_name'
");
$db_size_row = mysqli_fetch_assoc($db_size_query);
$total_size = $db_size_row['size_mb'] ?? '0.00';

echo "<div class='box' style='border-color:#28a745;'>";
echo "<h2>📊 Database Summary</h2>";
echo "<p><strong>Total Tables:</strong> $table_count</p>";
echo "<p><strong>Total Database Size:</strong> " . number_format($total_size, 2) . " MB</p>";
echo "</div>";

mysqli_close($conn);

echo "<hr>";
echo "<div class='info'>";
echo "<h3>📝 Next Steps</h3>";
if ($table_count == 0) {
    echo "<ol>";
    echo "<li>Run the import script: <a href='import_sql_direct.php'><strong>import_sql_direct.php</strong></a></li>";
    echo "<li>Or use phpMyAdmin Import feature</li>";
    echo "<li>Or run <strong>import_db.bat</strong> (right-click → Run as Administrator)</li>";
    echo "</ol>";
} else {
    echo "<p class='success'><strong>✅ Database is ready to use!</strong></p>";
    echo "<ol>";
    echo "<li>You can now test your application with this local database</li>";
    echo "<li>Update connection.php to use localhost credentials if needed</li>";
    echo "<li>Or use connection_localhost.php for localhost-specific connection</li>";
    echo "</ol>";
}
echo "<p><strong>🔗 <a href='http://localhost/phpmyadmin' target='_blank'>Open phpMyAdmin</a></strong></p>";
echo "</div>";

echo "</body></html>";
?>

