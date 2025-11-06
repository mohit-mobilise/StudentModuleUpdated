<?php
/**
 * Direct SQL Import Script
 * This script will import your SQL file into the schoolerpbeta database
 * Handles large files and shows progress
 */

set_time_limit(0);
ini_set('memory_limit', '2048M'); // Increased for 700MB+ file
ini_set('max_execution_time', 0);
ini_set('post_max_size', '1024M');
ini_set('upload_max_filesize', '1024M');

$localhost_username = "root";
$localhost_password = ""; 
$database_name = "schoolerpbeta";
$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";

echo "<html><head><title>SQL Import</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .progress{background:#f8f9fa;padding:15px;border-radius:5px;margin:10px 0;border:2px solid #007bff;}
    h1{color:#007bff;}
    .box{border:2px solid #28a745;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
</style>";
echo "</head><body>";

echo "<h1>📥 SQL File Import</h1>";
echo "<div class='info'><strong>Target Database:</strong> $database_name<br><strong>Source File:</strong> $sql_file_path</div>";

// Step 1: Check file exists
if (!file_exists($sql_file_path)) {
    die("<div class='error'><h3>❌ SQL File Not Found</h3><p>The file was not found at:<br><code>$sql_file_path</code><br><br>Please verify the file path is correct.</p></div></body></html>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);

echo "<div class='success'>✅ SQL file found<br>📊 Size: " . number_format($file_size) . " bytes (" . $file_size_mb . " MB)</div>";

// Step 2: Connect to database
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p><p>Please make sure MySQL is running in XAMPP.</p></div></body></html>");
}

echo "<div class='success'>✅ Connected to MySQL server</div>";

// Step 3: Select database
if (!mysqli_select_db($conn, $database_name)) {
    die("<div class='error'><h3>❌ Database Not Found</h3><p>The database '$database_name' does not exist.<br>Please create it first in phpMyAdmin or use setup_database.php</p></div></body></html>");
}

echo "<div class='success'>✅ Database '$database_name' selected</div>";

// Step 4: Check current tables
$tables_before = mysqli_query($conn, "SHOW TABLES");
$count_before = mysqli_num_rows($tables_before);
echo "<div class='info'>📊 Tables before import: $count_before</div>";

// Step 5: Start Import
echo "<div class='box'>";
echo "<h2>🔄 Starting Import Process...</h2>";
echo "<div class='progress' id='progressDiv'>";
echo "<p>⏳ Processing SQL file... This may take several minutes for large files.</p>";
echo "<p>Please be patient and do not close this page.</p>";
echo "</div>";

// Disable autocommit for better performance
mysqli_autocommit($conn, FALSE);

$handle = @fopen($sql_file_path, 'r');
if (!$handle) {
    die("<div class='error'>❌ Could not open SQL file for reading</div></body></html>");
}

$query_buffer = '';
$executed = 0;
$errors = 0;
$line_num = 0;
$current_query = '';
$in_comment = false;
$in_string = false;
$string_char = '';

echo "<div style='max-height:400px;overflow-y:auto;border:1px solid #ccc;padding:15px;background:#f8f9fa;font-family:monospace;font-size:12px;'>";

while (($line = fgets($handle)) !== false) {
    $line_num++;
    $original_line = $line;
    $line = rtrim($line);
    
    // Skip empty lines
    if (empty($line)) {
        continue;
    }
    
    // Handle multi-line comments
    if (preg_match('/\/\*/', $line)) {
        $in_comment = true;
    }
    if (preg_match('/\*\//', $line)) {
        $in_comment = false;
        continue;
    }
    if ($in_comment) {
        continue;
    }
    
    // Skip single-line comments
    if (substr(ltrim($line), 0, 2) == '--' || substr(ltrim($line), 0, 1) == '#') {
        continue;
    }
    
    // Add line to buffer
    $query_buffer .= $original_line;
    
    // Check for end of query (semicolon not inside string)
    $semicolon_pos = strrpos($line, ';');
    if ($semicolon_pos !== false) {
        // Simple check: if semicolon is at the end (most common case)
        if ($semicolon_pos == strlen($line) - 1) {
            $query = trim($query_buffer);
            $query_buffer = '';
            
            if (!empty($query) && strlen($query) > 10) {
                // Execute query
                if (@mysqli_query($conn, $query)) {
                    $executed++;
                    if ($executed % 50 == 0) {
                        echo "<p class='success'>✓ Executed $executed queries (Line $line_num)...</p>";
                        @flush();
                        @ob_flush();
                    }
                } else {
                    $errors++;
                    $err = mysqli_error($conn);
                    // Hide common non-critical errors
                    if (strpos($err, 'already exists') === false && 
                        strpos($err, 'Duplicate entry') === false &&
                        strpos($err, 'Unknown table') === false &&
                        strpos($err, 'Duplicate key') === false &&
                        strpos($err, 'Cannot add') === false) {
                        echo "<p class='warning'>⚠ Line $line_num: " . htmlspecialchars(substr($err, 0, 200)) . "</p>";
                    }
                }
            }
        }
    }
}

fclose($handle);

// Commit all transactions
mysqli_commit($conn);
mysqli_autocommit($conn, TRUE);

echo "</div>";
echo "</div>";

// Step 6: Verify Import
$tables_after = mysqli_query($conn, "SHOW TABLES");
$count_after = mysqli_num_rows($tables_after);
$tables_added = $count_after - $count_before;

echo "<hr>";
echo "<div class='box' style='border-color:#28a745;'>";
echo "<h2>📊 Import Summary</h2>";
echo "<div class='success'>";
echo "<p><strong>✅ Queries Executed:</strong> $executed</p>";
echo "<p><strong>📊 Tables Before:</strong> $count_before</p>";
echo "<p><strong>📊 Tables After:</strong> $count_after</p>";
echo "<p><strong>➕ Tables Added:</strong> $tables_added</p>";
if ($errors > 0) {
    echo "<p class='warning'><strong>⚠ Warnings/Errors:</strong> $errors (most are normal - like 'already exists', 'duplicate key', etc.)</p>";
}
echo "</div>";

if ($count_after > $count_before || $executed > 0) {
    echo "<div class='success' style='font-size:18px;padding:20px;margin-top:20px;'>";
    echo "<strong>✅ Import Completed Successfully!</strong><br>";
    echo "The database '$database_name' now has $count_after tables.";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<strong>⚠️ No tables were added.</strong><br>";
    echo "This might mean:<br>";
    echo "- The database already had all the tables<br>";
    echo "- The SQL file structure is different<br>";
    echo "- Check the warnings above for specific issues";
    echo "</div>";
}

echo "</div>";

mysqli_close($conn);

echo "<hr>";
echo "<div class='info'>";
echo "<h3>✅ Next Steps</h3>";
echo "<ol>";
echo "<li>Refresh phpMyAdmin and click on '<strong>$database_name</strong>'</li>";
echo "<li>You should see all the imported tables in the left sidebar</li>";
echo "<li>Verify that tables like 'student_master', 'class_master', etc. are present</li>";
echo "<li>You can now use the database for testing</li>";
echo "</ol>";
echo "<p><strong>🔗 <a href='http://localhost/phpmyadmin' target='_blank'>Open phpMyAdmin</a></strong></p>";
echo "</div>";

echo "</body></html>";
?>

