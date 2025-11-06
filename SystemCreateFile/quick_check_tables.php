<?php
/**
 * Quick Table Check Script
 * Quickly checks for missing critical tables and reports status
 */

$database_name = "schoolerpbeta";
$localhost_username = "root";
$localhost_password = "";

echo "<html><head><title>Quick Table Check</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    h1{color:#007bff;}
    table{border-collapse:collapse;width:100%;margin:20px 0;}
    th{background:#007bff;color:white;padding:12px;text-align:left;}
    td{padding:10px;border-bottom:1px solid #ddd;}
</style>";
echo "</head><body>";

echo "<h1>🔍 Quick Table Status Check</h1>";

// Connect to database
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p></div></body></html>");
}

mysqli_select_db($conn, $database_name);

// Critical tables that must exist
$critical_tables = [
    'student_master',
    'class_master',
    'user_authentication',
    'menu_portal_master',
    'attendance',
    'homework_master',
    'fees',
    'exam_marks',
    'FYmaster'
];

echo "<h2>Critical Tables Status</h2>";
echo "<table>";
echo "<tr><th>Table Name</th><th>Status</th><th>Row Count</th></tr>";

$missing_tables = [];

foreach ($critical_tables as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    
    if (mysqli_num_rows($check) > 0) {
        $count_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `$table`");
        $count_row = mysqli_fetch_assoc($count_query);
        $row_count = number_format($count_row['cnt']);
        echo "<tr><td><code>$table</code></td><td class='success'>✅ EXISTS</td><td>$row_count rows</td></tr>";
    } else {
        echo "<tr><td><code>$table</code></td><td class='error'>❌ MISSING</td><td>-</td></tr>";
        $missing_tables[] = $table;
    }
}

echo "</table>";

if (count($missing_tables) > 0) {
    echo "<div class='error'>";
    echo "<h3>⚠️ Missing Tables Found!</h3>";
    echo "<p>The following critical tables are missing:</p>";
    echo "<ul>";
    foreach ($missing_tables as $table) {
        echo "<li><code>$table</code></li>";
    }
    echo "</ul>";
    echo "<p><strong>Solution:</strong> Run the synchronization script to create missing tables:</p>";
    echo "<p><a href='compare_and_sync_tables.php' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;'>Run Table Synchronization</a></p>";
    echo "</div>";
} else {
    echo "<div class='success'>";
    echo "<h3>✅ All Critical Tables Exist</h3>";
    echo "<p>All critical tables are present in the database.</p>";
    echo "</div>";
}

// Get total table count
$tables_query = mysqli_query($conn, "SHOW TABLES");
$total_tables = mysqli_num_rows($tables_query);

echo "<div class='info'>";
echo "<h3>📊 Database Summary</h3>";
echo "<p><strong>Total Tables:</strong> $total_tables</p>";
echo "<p><strong>Critical Tables:</strong> " . count($critical_tables) . "</p>";
echo "<p><strong>Missing Tables:</strong> " . count($missing_tables) . "</p>";
echo "</div>";

mysqli_close($conn);
echo "</body></html>";
?>


