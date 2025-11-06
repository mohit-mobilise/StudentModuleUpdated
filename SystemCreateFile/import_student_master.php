<?php
/**
 * Import student_master Table from SQL Backup
 * Specifically extracts and imports the student_master table and its data
 */

set_time_limit(0);
ini_set('memory_limit', '512M');

$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";
$database_name = "schoolerpbeta";
$localhost_username = "root";
$localhost_password = "";

echo "<html><head><title>Import student_master Table</title>";
echo "<meta charset='UTF-8'>";
echo "<meta http-equiv='refresh' content='30'>"; // Auto-refresh every 30 seconds
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #28a745;}
    .error{color:red;background:#f8d7da;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #dc3545;}
    .info{color:#004085;background:#d1ecf1;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #17a2b8;}
    .warning{color:orange;background:#fff3cd;padding:15px;border-radius:5px;margin:10px 0;border-left:4px solid #ffc107;}
    h1{color:#007bff;}
    .box{border:2px solid #007bff;padding:20px;margin:20px 0;border-radius:5px;background:white;}
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
    .progress{background:#e9ecef;height:25px;border-radius:5px;overflow:hidden;margin:10px 0;}
    .progress-bar{background:#007bff;height:100%;text-align:center;color:white;line-height:25px;}
</style>";
echo "</head><body>";

echo "<h1>📦 Import student_master Table</h1>";
echo "<div class='info'><strong>SQL File:</strong> " . basename($sql_file_path) . "<br><strong>Database:</strong> $database_name<br><strong>Table:</strong> student_master</div>";

// Check if SQL file exists
if (!file_exists($sql_file_path)) {
    die("<div class='error'><h3>❌ SQL File Not Found</h3><p>File path: $sql_file_path</p><p>Please check the file path and try again.</p></div></body></html>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);
echo "<div class='info'><strong>File Size:</strong> $file_size_mb MB</div>";

// Connect to database
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}

if (!$conn) {
    die("<div class='error'><h3>❌ Connection Failed</h3><p>Error: " . mysqli_connect_error() . "</p><p>Please make sure MySQL is running in XAMPP.</p></div></body></html>");
}

mysqli_select_db($conn, $database_name);
echo "<div class='success'>✅ Connected to database '$database_name'</div>";

// Check if table already exists
$check = mysqli_query($conn, "SHOW TABLES LIKE 'student_master'");
if (mysqli_num_rows($check) > 0) {
    echo "<div class='warning'><h3>⚠️ Table Already Exists</h3>";
    echo "<p>The <code>student_master</code> table already exists in the database.</p>";
    
    $count_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `student_master`");
    $count_row = mysqli_fetch_assoc($count_query);
    $row_count = number_format($count_row['cnt']);
    
    echo "<p><strong>Current row count:</strong> $row_count</p>";
    echo "<p>If you want to recreate it, please drop the table first in phpMyAdmin or use: <code>DROP TABLE `student_master`;</code></p>";
    echo "</div>";
    echo "</body></html>";
    exit;
}

// Step 1: Extract CREATE TABLE statement
echo "<div class='box'><h2>Step 1: Extracting CREATE TABLE Statement</h2>";
echo "<div class='info'>📖 Reading SQL file to find CREATE TABLE statement for student_master...</div>";
flush();

$sql_file = fopen($sql_file_path, 'r');
if (!$sql_file) {
    die("<div class='error'><h3>❌ Cannot open SQL file</h3><p>Please check file permissions.</p></div></body></html>");
}

$create_table_sql = '';
$found_create = false;
$in_create_table = false;
$buffer = '';
$line_count = 0;

echo "<div class='info'>⏳ Searching for CREATE TABLE statement...</div>";
flush();

while (!feof($sql_file)) {
    $buffer .= fread($sql_file, 8192);
    
    while (($newline_pos = strpos($buffer, "\n")) !== false) {
        $line = substr($buffer, 0, $newline_pos);
        $buffer = substr($buffer, $newline_pos + 1);
        $line_count++;
        
        $line = trim($line);
        if (empty($line) || substr($line, 0, 2) == '--' || substr($line, 0, 1) == '#') {
            continue;
        }
        
        // Check for CREATE TABLE for student_master
        if (preg_match('/^CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`]?(student_master)[`]?/i', $line, $matches)) {
            $found_create = true;
            $in_create_table = true;
            $create_table_sql = $line . "\n";
            echo "<div class='info'>✅ Found CREATE TABLE statement (line $line_count)</div>";
            flush();
            continue;
        }
        
        if ($in_create_table) {
            $create_table_sql .= $line . "\n";
            
            // Check if CREATE TABLE is complete
            if (substr(rtrim($line), -1) == ';') {
                $in_create_table = false;
                echo "<div class='success'>✅ CREATE TABLE statement extracted successfully!</div>";
                break 2; // Exit both loops
            }
        }
        
        // Show progress
        if ($line_count % 50000 == 0) {
            echo "<div class='info'>📊 Processed $line_count lines...</div>";
            flush();
        }
    }
    
    // If we found and completed the CREATE TABLE, break
    if ($found_create && !$in_create_table) {
        break;
    }
}

fclose($sql_file);

if (!$found_create || empty($create_table_sql)) {
    die("<div class='error'><h3>❌ CREATE TABLE Not Found</h3><p>Could not find CREATE TABLE statement for student_master in the SQL file.</p><p>Please verify the SQL file contains this table.</p></div></body></html>");
}

echo "<div class='info'>📊 Total lines scanned: " . number_format($line_count) . "</div>";
echo "</div>";

// Step 2: Create the table
echo "<div class='box'><h2>Step 2: Creating Table</h2>";

// Clean up CREATE TABLE statement
$create_table_sql = preg_replace('/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS/i', 'CREATE TABLE', $create_table_sql);

echo "<div class='info'>🔧 Executing CREATE TABLE statement...</div>";
flush();

if (mysqli_query($conn, $create_table_sql)) {
    echo "<div class='success'>✅ Table <code>student_master</code> created successfully!</div>";
} else {
    die("<div class='error'><h3>❌ Error Creating Table</h3><p>Error: " . mysqli_error($conn) . "</p></div></body></html>");
}

echo "</div>";

// Step 3: Extract and insert data
echo "<div class='box'><h2>Step 3: Extracting and Inserting Data</h2>";
echo "<div class='info'>📖 Reading SQL file to extract INSERT statements...</div>";
flush();

$sql_file = fopen($sql_file_path, 'r');
$insert_statements = [];
$in_insert = false;
$current_insert = '';
$buffer = '';
$line_count = 0;
$insert_count = 0;

echo "<div class='info'>⏳ Searching for INSERT statements...</div>";
flush();

while (!feof($sql_file)) {
    $buffer .= fread($sql_file, 8192);
    
    while (($newline_pos = strpos($buffer, "\n")) !== false) {
        $line = substr($buffer, 0, $newline_pos);
        $buffer = substr($buffer, $newline_pos + 1);
        $line_count++;
        
        $line = trim($line);
        if (empty($line) || substr($line, 0, 2) == '--' || substr($line, 0, 1) == '#') {
            continue;
        }
        
        // Check for INSERT INTO student_master
        if (preg_match('/^INSERT\s+INTO\s+[`]?(student_master)[`]?\s+/i', $line, $matches)) {
            $in_insert = true;
            $current_insert = $line;
            continue;
        }
        
        if ($in_insert) {
            $current_insert .= "\n" . $line;
            
            // Check if INSERT is complete
            if (substr(rtrim($line), -1) == ';') {
                $insert_statements[] = $current_insert;
                $insert_count++;
                $current_insert = '';
                $in_insert = false;
                
                if ($insert_count % 100 == 0) {
                    echo "<div class='info'>📊 Found $insert_count INSERT statements so far...</div>";
                    flush();
                }
            }
        }
        
        // Show progress
        if ($line_count % 50000 == 0) {
            echo "<div class='info'>📊 Processed $line_count lines...</div>";
            flush();
        }
    }
}

fclose($sql_file);

$total_inserts = count($insert_statements);
echo "<div class='success'>✅ Found $total_inserts INSERT statement(s)</div>";
echo "</div>";

// Step 4: Insert data
if ($total_inserts > 0) {
    echo "<div class='box'><h2>Step 4: Inserting Data</h2>";
    echo "<div class='info'>💾 Inserting $total_inserts statement(s) into student_master...</div>";
    flush();
    
    $success_count = 0;
    $error_count = 0;
    $row_count = 0;
    
    foreach ($insert_statements as $index => $insert_sql) {
        if (mysqli_query($conn, $insert_sql)) {
            // Try to get affected rows
            $affected = mysqli_affected_rows($conn);
            if ($affected > 0) {
                $row_count += $affected;
            }
            $success_count++;
        } else {
            $error_count++;
            echo "<div class='warning'>⚠️ Error in INSERT statement " . ($index + 1) . ": " . mysqli_error($conn) . "</div>";
            flush();
            
            // Try splitting large INSERT statements
            if (stripos($insert_sql, 'VALUES') !== false) {
                // Try to execute individual rows
                preg_match_all('/\([^)]+\)/', $insert_sql, $matches);
                if (!empty($matches[0])) {
                    $base_insert = preg_replace('/VALUES.*/i', 'VALUES ', $insert_sql);
                    foreach ($matches[0] as $values) {
                        $single_insert = $base_insert . $values . ';';
                        mysqli_query($conn, $single_insert);
                    }
                }
            }
        }
        
        if (($index + 1) % 50 == 0) {
            echo "<div class='info'>📊 Processed " . ($index + 1) . " / $total_inserts statements... ($row_count rows inserted)</div>";
            flush();
        }
    }
    
    echo "<div class='success'><h3>✅ Data Insertion Complete!</h3>";
    echo "<p><strong>Successful statements:</strong> $success_count</p>";
    echo "<p><strong>Errors:</strong> $error_count</p>";
    echo "<p><strong>Estimated rows inserted:</strong> " . number_format($row_count) . "</p>";
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='warning'>⚠️ No INSERT statements found. Table created but empty.</div>";
}

// Final verification
echo "<div class='box'><h2>Final Verification</h2>";

$verify_query = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM `student_master`");
$verify_row = mysqli_fetch_assoc($verify_query);
$final_count = number_format($verify_row['cnt']);

echo "<div class='success'>";
echo "<h3>✅ Import Complete!</h3>";
echo "<p><strong>Table:</strong> <code>student_master</code></p>";
echo "<p><strong>Total rows:</strong> $final_count</p>";
echo "<p>You can now use the application without the 'Table doesn't exist' error.</p>";
echo "</div>";

mysqli_close($conn);
echo "</body></html>";
?>


