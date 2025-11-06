<?php
/**
 * Complete Database Import - Finish Remaining Tables
 * This script will complete the import of all remaining 117 tables
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

echo "<html><head><title>Complete Database Import</title>";
echo "<meta charset='UTF-8'>";
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

echo "<h1>🔄 Completing Database Import</h1>";
echo "<div class='info'><strong>Database:</strong> $database_name<br><strong>Task:</strong> Import remaining 117 tables</div>";

// Step 1: Verify SQL file
if (!file_exists($sql_file_path)) {
    die("<div class='error'><h3>❌ SQL File Not Found</h3><p>The file was not found at:<br><code>$sql_file_path</code></p></div></body></html>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);
echo "<div class='success'>✅ SQL file found (" . $file_size_mb . " MB)</div>";

// Step 2: Connect to MySQL
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p></div></body></html>");
}

mysqli_select_db($conn, $database_name);
echo "<div class='success'>✅ Connected to database</div>";

// Step 3: Check current tables
$tables_before = mysqli_query($conn, "SHOW TABLES");
$count_before = mysqli_num_rows($tables_before);
echo "<div class='info'>📊 Current tables: $count_before</div>";

// Step 4: Use command line import (most reliable for completing import)
$mysql_path = "C:\\xampp\\mysql\\bin\\mysql.exe";
if (file_exists($mysql_path)) {
    echo "<div class='progress'>";
    echo "<h2>🚀 Starting Command Line Import (Most Reliable Method)</h2>";
    echo "<p><span class='spinner'></span>Importing all remaining tables...</p>";
    echo "<p>This will complete the import of all 117 remaining tables.</p>";
    echo "<p><strong>Do NOT close this page!</strong> This may take 10-15 minutes.</p>";
    echo "</div>";
    
    // Flush output
    @flush();
    @ob_flush();
    
    // Run import command - redirect output to avoid blocking
    $cmd = "C:\\xampp\\mysql\\bin\\mysql.exe -u root -P 3308 schoolerpbeta < \"$sql_file_path\" 2>&1";
    
    // Use popen for non-blocking execution
    $handle = popen($cmd, 'r');
    if ($handle) {
        echo "<div style='max-height:400px;overflow-y:auto;border:1px solid #ccc;padding:15px;background:#f8f9fa;font-family:monospace;font-size:12px;'>";
        echo "<p>📥 Import process started...</p>";
        
        // Read output in real-time
        while (!feof($handle)) {
            $line = fgets($handle);
            if ($line) {
                echo "<p>" . htmlspecialchars($line) . "</p>";
                @flush();
                @ob_flush();
            }
        }
        
        pclose($handle);
        echo "</div>";
        
        // Wait a moment for completion
        sleep(3);
        
        // Verify import
        $tables_after = mysqli_query($conn, "SHOW TABLES");
        $count_after = mysqli_num_rows($tables_after);
        $added = $count_after - $count_before;
        
        echo "<hr>";
        echo "<div class='box' style='border-color:#28a745;'>";
        echo "<h2>📊 Import Results</h2>";
        echo "<div class='success'>";
        echo "<p><strong>✅ Tables Before:</strong> $count_before</p>";
        echo "<p><strong>✅ Tables After:</strong> $count_after</p>";
        echo "<p><strong>➕ Tables Added:</strong> $added</p>";
        echo "</div>";
        
        if ($added > 0) {
            echo "<div class='success' style='font-size:18px;padding:20px;margin-top:20px;'>";
            echo "<strong>✅ Import Completed!</strong><br>";
            echo "Successfully added $added tables to the database.";
            echo "</div>";
        } else {
            echo "<div class='warning' style='font-size:18px;padding:20px;margin-top:20px;'>";
            echo "<strong>⚠️ No New Tables Added</strong><br>";
            echo "The tables may already exist, or the import needs to be checked.";
            echo "</div>";
        }
        
        echo "</div>";
        
        mysqli_close($conn);
        
        echo "<hr>";
        echo "<div class='info'>";
        echo "<h3>✅ Next Steps</h3>";
        echo "<p><a href='check_database.php'><strong>Click here to verify all tables</strong></a></p>";
        echo "<p><a href='http://localhost/phpmyadmin' target='_blank'>Open phpMyAdmin</a></p>";
        echo "</div>";
        
        echo "</body></html>";
        exit;
    }
}

// Fallback: PHP line-by-line import with error handling
echo "<div class='warning'>⚠️ Command line method unavailable, using PHP method...</div>";

$handle = @fopen($sql_file_path, 'r');
if (!$handle) {
    die("<div class='error'>❌ Could not open SQL file</div></body></html>");
}

echo "<div class='progress'>";
echo "<p>📥 Processing SQL file line by line...</p>";
echo "<p>This will complete all remaining tables.</p>";
echo "</div>";

mysqli_autocommit($conn, FALSE);

$query_buffer = '';
$executed = 0;
$errors = 0;
$line_num = 0;
$tables_created = [];

echo "<div style='max-height:500px;overflow-y:auto;border:1px solid #ccc;padding:15px;background:#f8f9fa;font-family:monospace;font-size:12px;'>";

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
    
    // Execute when semicolon found
    if (substr(rtrim($trimmed), -1) == ';') {
        $query = trim($query_buffer);
        $query_buffer = '';
        
        if (!empty($query) && strlen($query) > 10) {
            // Check if this is a CREATE TABLE statement
            $is_create_table = (stripos($query, 'CREATE TABLE') !== false);
            $table_name = '';
            
            if ($is_create_table) {
                // Extract table name
                if (preg_match('/CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?(?:`?(\w+)`?|`([^`]+)`)/i', $query, $matches)) {
                    $table_name = isset($matches[2]) ? $matches[2] : $matches[1];
                }
            }
            
            // Check if table already exists (skip if it does to avoid errors)
            if ($is_create_table && !empty($table_name)) {
                $check = mysqli_query($conn, "SHOW TABLES LIKE '$table_name'");
                if (mysqli_num_rows($check) > 0) {
                    // Table exists, skip
                    continue;
                }
            }
            
            // Execute query
            if (@mysqli_query($conn, $query)) {
                $executed++;
                
                if ($is_create_table && !empty($table_name)) {
                    $tables_created[] = $table_name;
                }
                
                if ($executed % 50 == 0) {
                    echo "<p class='success'>✓ Processed $executed queries... (Created " . count($tables_created) . " tables so far)</p>";
                    @flush();
                    @ob_flush();
                }
            } else {
                $errors++;
                $err = mysqli_error($conn);
                // Only show non-duplicate errors
                if (strpos($err, 'already exists') === false && 
                    strpos($err, 'Duplicate entry') === false &&
                    strpos($err, 'Duplicate key') === false &&
                    strpos($err, 'Table doesn\'t exist') === false) {
                    if ($errors <= 10) { // Limit error output
                        echo "<p class='warning'>⚠ Line $line_num: " . htmlspecialchars(substr($err, 0, 150)) . "...</p>";
                    }
                }
            }
        }
    }
    
    if ($line_num % 10000 == 0) {
        @flush();
        @ob_flush();
    }
}

fclose($handle);
mysqli_commit($conn);
mysqli_autocommit($conn, TRUE);

echo "</div>";

// Final verification
$tables_after = mysqli_query($conn, "SHOW TABLES");
$count_after = mysqli_num_rows($tables_after);
$added = $count_after - $count_before;

echo "<hr>";
echo "<div class='box' style='border-color:#28a745;'>";
echo "<h2>📊 Final Results</h2>";
echo "<div class='success'>";
echo "<p><strong>✅ Queries Executed:</strong> " . number_format($executed) . "</p>";
echo "<p><strong>✅ Tables Before:</strong> $count_before</p>";
echo "<p><strong>✅ Tables After:</strong> $count_after</p>";
echo "<p><strong>➕ Tables Added:</strong> $added</p>";
echo "<p><strong>📋 New Tables Created:</strong> " . count($tables_created) . "</p>";
if ($errors > 0) {
    echo "<p class='warning'><strong>⚠ Warnings:</strong> $errors (most are normal)</p>";
}
echo "</div>";

if (count($tables_created) > 0 && count($tables_created) <= 20) {
    echo "<h3>📋 Newly Created Tables:</h3>";
    echo "<div style='max-height:200px;overflow-y:auto;'>";
    foreach ($tables_created as $table) {
        echo "<p>✓ $table</p>";
    }
    echo "</div>";
}

if ($added >= 100) {
    echo "<div class='success' style='font-size:20px;padding:25px;margin-top:20px;text-align:center;'>";
    echo "<strong>🎉 SUCCESS!</strong><br>";
    echo "All remaining tables have been imported!<br>";
    echo "Total tables in database: <strong>$count_after</strong>";
    echo "</div>";
} elseif ($added > 0) {
    echo "<div class='warning' style='font-size:18px;padding:20px;margin-top:20px;'>";
    echo "<strong>⚠️ Partial Import</strong><br>";
    echo "Only $added tables were added. The import may need to be completed.";
    echo "</div>";
}

echo "</div>";

mysqli_close($conn);

echo "<hr>";
echo "<div class='info'>";
echo "<h3>✅ Verification</h3>";
echo "<p><a href='check_database.php' style='font-size:18px;'><strong>📊 Check Complete Database Status</strong></a></p>";
echo "<p><a href='http://localhost/phpmyadmin' target='_blank'>🔗 Open phpMyAdmin</a></p>";
echo "</div>";

echo "</body></html>";
?>

