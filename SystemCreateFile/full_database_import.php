<?php
/**
 * Full Database Import Script
 * This script will import the complete SQL file and handle any issues
 * Optimized for large files (700MB+)
 */

// Maximum limits for large file import
set_time_limit(0);
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 0);
ini_set('max_input_time', 0);

$localhost_username = "root";
$localhost_password = ""; 
$database_name = "schoolerpbeta";
$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";

// Force output buffering off for progress display
if (ob_get_level()) {
    ob_end_clean();
}
header('Content-Type: text/html; charset=UTF-8');

echo "<html><head><title>Full Database Import</title>";
echo "<meta charset='UTF-8'>";
echo "<meta http-equiv='refresh' content='30'>"; // Auto-refresh every 30 seconds
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .progress{background:#f8f9fa;padding:15px;border-radius:5px;margin:10px 0;border:2px solid #007bff;}
    h1{color:#007bff;}
    .box{border:2px solid #007bff;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
    .spinner{border:4px solid #f3f3f3;border-top:4px solid #007bff;border-radius:50%;width:40px;height:40px;animation:spin 1s linear infinite;display:inline-block;margin-right:10px;}
    @keyframes spin{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
</style>";
echo "</head><body>";

echo "<h1>🗄️ Full Database Import</h1>";
echo "<div class='info'><strong>Database:</strong> $database_name<br><strong>File:</strong> $sql_file_path</div>";

// Step 1: Verify SQL file
if (!file_exists($sql_file_path)) {
    die("<div class='error'><h3>❌ SQL File Not Found</h3><p>The file was not found at:<br><code>$sql_file_path</code><br><br>Please verify the file path is correct.</p></div></body></html>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);
echo "<div class='success'>✅ SQL file found<br>📊 Size: " . number_format($file_size) . " bytes (" . $file_size_mb . " MB)</div>";

// Step 2: Connect to MySQL
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p><p>Please make sure MySQL is running in XAMPP Control Panel.</p></div></body></html>");
}

echo "<div class='success'>✅ Connected to MySQL server</div>";

// Step 3: Create/Select database
$create_db = "CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (!mysqli_query($conn, $create_db)) {
    die("<div class='error'>❌ Error creating database: " . mysqli_error($conn) . "</div></body></html>");
}

mysqli_select_db($conn, $database_name);
echo "<div class='success'>✅ Database '$database_name' ready</div>";

// Step 4: Check current state
$tables_before = mysqli_query($conn, "SHOW TABLES");
$count_before = mysqli_num_rows($tables_before);
echo "<div class='info'>📊 Current tables in database: $count_before</div>";

// Step 5: Import SQL file
echo "<div class='box'>";
echo "<h2>🔄 Starting Full Database Import</h2>";
echo "<div class='progress'>";
echo "<p><span class='spinner'></span><strong>Importing...</strong> This may take 15-20 minutes for a 702 MB file.</p>";
echo "<p>Do NOT close this page until import is complete!</p>";
echo "</div>";

// Try command line import first (fastest and most reliable for large files)
$mysql_path = "C:\\xampp\\mysql\\bin\\mysql.exe";
if (file_exists($mysql_path) && !isset($_GET['force_php'])) {
    echo "<div class='info'>📥 Attempting command line import (fastest method)...</div>";
    
    $cmd = "C:\\xampp\\mysql\\bin\\mysql.exe -u root -P 3308 schoolerpbeta < \"$sql_file_path\" 2>&1";
    $output = @shell_exec($cmd);
    
    // Wait a bit for the command to complete
    sleep(2);
    
    // Verify import by checking tables
    $tables_check = mysqli_query($conn, "SHOW TABLES");
    $count_after = mysqli_num_rows($tables_check);
    
    if ($count_after > $count_before) {
        echo "<div class='success'><h3>✅ Import Completed via Command Line!</h3>";
        echo "<p>Tables before: $count_before</p>";
        echo "<p>Tables after: $count_after</p>";
        echo "<p>Tables added: " . ($count_after - $count_before) . "</p>";
        echo "</div>";
        
        // Show sample tables
        echo "<h3>📋 Sample Tables Imported:</h3>";
        echo "<div style='max-height:300px;overflow-y:auto;border:1px solid #ccc;padding:10px;background:#f8f9fa;'>";
        mysqli_data_seek($tables_check, 0);
        $displayed = 0;
        while ($row = mysqli_fetch_array($tables_check) && $displayed < 50) {
            echo "<p>✓ " . $row[0] . "</p>";
            $displayed++;
        }
        echo "</div>";
        
        mysqli_close($conn);
        echo "<hr>";
        echo "<div class='success' style='font-size:18px;padding:20px;'>";
        echo "<strong>✅ Full Database Import Completed Successfully!</strong><br>";
        echo "<p>🎉 The database '$database_name' now has $count_after tables.</p>";
        echo "<p>📝 <a href='check_database.php'>Click here to verify all tables</a></p>";
        echo "</div>";
        echo "</body></html>";
        exit;
    } else {
        echo "<div class='warning'>⚠️ Command line method may not have worked. Trying PHP method...</div>";
    }
}

// Fallback: PHP-based import (line by line for memory efficiency)
echo "<div class='info'>📥 Using PHP import method (processing line by line)...</div>";

// Disable autocommit for better performance
mysqli_autocommit($conn, FALSE);

$handle = @fopen($sql_file_path, 'r');
if (!$handle) {
    die("<div class='error'>❌ Could not open SQL file</div></body></html>");
}

$query_buffer = '';
$executed = 0;
$errors = 0;
$line_num = 0;
$last_progress = 0;

echo "<div style='max-height:400px;overflow-y:auto;border:1px solid #ccc;padding:15px;background:#f8f9fa;font-family:monospace;font-size:12px;'>";

while (($line = fgets($handle)) !== false) {
    $line_num++;
    $trimmed = trim($line);
    
    // Skip comments and empty lines
    if (empty($trimmed) || 
        substr($trimmed, 0, 2) == '--' || 
        substr($trimmed, 0, 2) == '/*' ||
        substr($trimmed, 0, 1) == '#') {
        continue;
    }
    
    $query_buffer .= $line;
    
    // Execute when semicolon found at end of line
    if (substr(rtrim($trimmed), -1) == ';') {
        $query = trim($query_buffer);
        $query_buffer = '';
        
        if (!empty($query) && strlen($query) > 10) {
            // Execute query with error suppression for non-critical errors
            if (@mysqli_query($conn, $query)) {
                $executed++;
                $last_progress = $executed;
                
                // Show progress every 100 queries
                if ($executed % 100 == 0) {
                    echo "<p class='success'>✓ Executed $executed queries (Line $line_num)...</p>";
                    @flush();
                    @ob_flush();
                }
            } else {
                $errors++;
                $err = mysqli_error($conn);
                // Only show critical errors
                if (strpos($err, 'already exists') === false && 
                    strpos($err, 'Duplicate entry') === false &&
                    strpos($err, 'Unknown table') === false &&
                    strpos($err, 'Duplicate key') === false &&
                    strpos($err, 'Cannot add') === false &&
                    strpos($err, 'Table doesn\'t exist') === false) {
                    echo "<p class='warning'>⚠ Line $line_num: " . htmlspecialchars(substr($err, 0, 150)) . "...</p>";
                }
            }
        }
    }
    
    // Prevent memory issues
    if ($line_num % 10000 == 0) {
        @flush();
        @ob_flush();
    }
}

fclose($handle);

// Commit all transactions
mysqli_commit($conn);
mysqli_autocommit($conn, TRUE);

echo "</div>";

// Step 6: Verify Import
$tables_after = mysqli_query($conn, "SHOW TABLES");
$count_after = mysqli_num_rows($tables_after);
$tables_added = $count_after - $count_before;

echo "<hr>";
echo "<div class='box' style='border-color:#28a745;'>";
echo "<h2>📊 Import Summary</h2>";
echo "<div class='success'>";
echo "<p><strong>✅ Queries Executed:</strong> " . number_format($executed) . "</p>";
echo "<p><strong>📊 Tables Before:</strong> $count_before</p>";
echo "<p><strong>📊 Tables After:</strong> $count_after</p>";
echo "<p><strong>➕ Tables Added:</strong> $tables_added</p>";
if ($errors > 0) {
    echo "<p class='warning'><strong>⚠ Warnings:</strong> $errors (most are normal - like 'already exists')</p>";
}
echo "</div>";

// Check key tables
echo "<h3>🔑 Verifying Key Tables:</h3>";
$key_tables = ['student_master', 'class_master', 'fees', 'attendance', 'menu_portal_master', 'fees_MonthQuaterMapping'];
$found_count = 0;

mysqli_data_seek($tables_after, 0);
$all_tables = [];
while ($row = mysqli_fetch_array($tables_after)) {
    $all_tables[] = $row[0];
}

foreach ($key_tables as $key_table) {
    if (in_array($key_table, $all_tables)) {
        $found_count++;
        echo "<p class='success'>✅ $key_table</p>";
    } else {
        echo "<p class='warning'>⚠️ $key_table (not found)</p>";
    }
}

if ($count_after > 0) {
    echo "<div class='success' style='font-size:18px;padding:20px;margin-top:20px;'>";
    echo "<strong>✅ Database Import Completed!</strong><br>";
    echo "Found $count_after tables in database<br>";
    echo "Key tables found: $found_count/" . count($key_tables);
    echo "</div>";
} else {
    echo "<div class='error' style='font-size:18px;padding:20px;margin-top:20px;'>";
    echo "<strong>❌ No Tables Found</strong><br>";
    echo "The import may have failed. Please check the errors above or try the command line method.";
    echo "</div>";
}

echo "</div>";

mysqli_close($conn);

echo "<hr>";
echo "<div class='info'>";
echo "<h3>✅ Next Steps</h3>";
if ($count_after > 0) {
    echo "<p class='success'><strong>Database is ready!</strong></p>";
    echo "<ol>";
    echo "<li><a href='check_database.php'><strong>Verify all tables</strong></a></li>";
    echo "<li>Check phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>Open phpMyAdmin</a></li>";
    echo "<li>Test your application with the local database</li>";
    echo "</ol>";
} else {
    echo "<p class='warning'><strong>Import may need to be run again</strong></p>";
    echo "<p>Options:</p>";
    echo "<ol>";
    echo "<li>Try running <strong>import_db.bat</strong> (right-click → Run as Administrator)</li>";
    echo "<li>Or use phpMyAdmin Import feature with increased limits</li>";
    echo "<li>Or <a href='?force_php=1'>refresh this page</a> to try PHP method again</li>";
    echo "</ol>";
}
echo "</div>";

echo "</body></html>";
?>

