<?php
/**
 * Table Comparison and Synchronization Script
 * Compares tables in SQL backup file with localhost database
 * Creates missing tables with their data
 */

set_time_limit(0);
ini_set('memory_limit', '512M');

$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";
$database_name = "schoolerpbeta";
$localhost_username = "root";
$localhost_password = "";

echo "<html><head><title>Table Comparison & Sync</title>";
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
    code{background:#f8f9fa;padding:5px 10px;border-radius:3px;font-family:monospace;}
    .progress{background:#e9ecef;height:25px;border-radius:5px;overflow:hidden;margin:10px 0;}
    .progress-bar{background:#007bff;height:100%;text-align:center;color:white;line-height:25px;}
</style>";
echo "</head><body>";

echo "<h1>🔍 Table Comparison & Synchronization</h1>";
echo "<div class='info'><strong>SQL File:</strong> " . basename($sql_file_path) . "<br><strong>Database:</strong> $database_name<br><strong>Server:</strong> localhost</div>";

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

echo "<div class='success'>✅ Connected to MySQL server</div>";

// Check if database exists
$db_check = mysqli_query($conn, "SHOW DATABASES LIKE '$database_name'");
if (mysqli_num_rows($db_check) == 0) {
    // Create database
    if (mysqli_query($conn, "CREATE DATABASE `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        echo "<div class='success'>✅ Database '$database_name' created</div>";
    } else {
        die("<div class='error'><h3>❌ Error creating database</h3><p>" . mysqli_error($conn) . "</p></div></body></html>");
    }
}

mysqli_select_db($conn, $database_name);
echo "<div class='success'>✅ Using database '$database_name'</div>";

// Get existing tables in database
echo "<div class='box'><h2>Step 1: Getting Existing Tables</h2>";
$existing_tables_query = mysqli_query($conn, "SHOW TABLES");
$existing_tables = [];
while ($row = mysqli_fetch_array($existing_tables_query)) {
    $existing_tables[] = $row[0];
}
$existing_count = count($existing_tables);
echo "<div class='info'>📊 Tables currently in database: <strong>$existing_count</strong></div>";
echo "</div>";

// Step 2: Parse SQL file to extract table names
echo "<div class='box'><h2>Step 2: Parsing SQL File</h2>";
echo "<div class='info'>📖 Reading SQL file... This may take a while for large files.</div>";

$sql_file = fopen($sql_file_path, 'r');
if (!$sql_file) {
    die("<div class='error'><h3>❌ Cannot open SQL file</h3><p>Please check file permissions.</p></div></body></html>");
}

$expected_tables = [];
$table_definitions = [];
$current_table = null;
$current_statement = '';
$in_create_table = false;
$buffer = '';
$line_count = 0;

echo "<div class='info'>⏳ Parsing SQL file (this may take a few minutes)...</div>";
flush();

// Read file in chunks to handle large files
while (!feof($sql_file)) {
    $buffer .= fread($sql_file, 8192); // Read 8KB at a time
    
    // Process complete lines
    while (($newline_pos = strpos($buffer, "\n")) !== false) {
        $line = substr($buffer, 0, $newline_pos);
        $buffer = substr($buffer, $newline_pos + 1);
        $line_count++;
        
        // Remove comments and empty lines
        $line = trim($line);
        if (empty($line) || substr($line, 0, 2) == '--' || substr($line, 0, 1) == '#') {
            continue;
        }
        
        // Check for CREATE TABLE
        if (preg_match('/^CREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?[`]?([a-zA-Z0-9_]+)[`]?/i', $line, $matches)) {
            $table_name = $matches[1];
            $expected_tables[] = $table_name;
            $current_table = $table_name;
            $in_create_table = true;
            $current_statement = $line . "\n";
            
            if (!isset($table_definitions[$table_name])) {
                $table_definitions[$table_name] = [
                    'create' => '',
                    'inserts' => []
                ];
            }
            continue;
        }
        
        // If we're in a CREATE TABLE statement
        if ($in_create_table && $current_table) {
            $current_statement .= $line . "\n";
            
            // Check if CREATE TABLE is complete (ends with ;)
            if (substr(rtrim($line), -1) == ';') {
                $table_definitions[$current_table]['create'] = $current_statement;
                $current_statement = '';
                $in_create_table = false;
                $current_table = null;
            }
            continue;
        }
        
        // Check for INSERT statements
        if (preg_match('/^INSERT\s+INTO\s+[`]?([a-zA-Z0-9_]+)[`]?/i', $line, $matches)) {
            $table_name = $matches[1];
            if (!isset($table_definitions[$table_name])) {
                $table_definitions[$table_name] = [
                    'create' => '',
                    'inserts' => []
                ];
            }
            
            // Collect INSERT statement (may span multiple lines)
            $insert_line = $line;
            while (substr(rtrim($line), -1) != ';' && !feof($sql_file)) {
                $next_line = fgets($sql_file);
                $line_count++;
                $insert_line .= "\n" . trim($next_line);
                $line = trim($next_line);
            }
            
            if (!empty($insert_line)) {
                $table_definitions[$table_name]['inserts'][] = $insert_line;
            }
            continue;
        }
    }
    
    // Show progress every 10000 lines
    if ($line_count % 10000 == 0) {
        echo "<div class='info'>📊 Processed $line_count lines... Found " . count($expected_tables) . " tables so far</div>";
        flush();
    }
}

fclose($sql_file);

$expected_count = count($expected_tables);
$unique_tables = array_unique($expected_tables);
$unique_count = count($unique_tables);

echo "<div class='success'>✅ SQL file parsed!</div>";
echo "<div class='info'>📊 Total lines processed: <strong>" . number_format($line_count) . "</strong></div>";
echo "<div class='info'>📋 Tables found in SQL file: <strong>$unique_count</strong></div>";
echo "</div>";

// Step 3: Compare tables
echo "<div class='box'><h2>Step 3: Comparing Tables</h2>";

$missing_tables = array_diff($unique_tables, $existing_tables);
$missing_count = count($missing_tables);

if ($missing_count == 0) {
    echo "<div class='success'>✅ All tables exist in localhost database!</div>";
    echo "<div class='info'>📊 All $unique_count tables from SQL file are present in the database.</div>";
} else {
    echo "<div class='warning'>⚠️ Found <strong>$missing_count</strong> missing tables</div>";
    echo "<div class='info'>📋 Missing tables:</div>";
    echo "<ul>";
    foreach ($missing_tables as $table) {
        echo "<li><code>$table</code></li>";
    }
    echo "</ul>";
}

echo "</div>";

// Step 4: Create missing tables with data
if ($missing_count > 0) {
    echo "<div class='box'><h2>Step 4: Creating Missing Tables</h2>";
    echo "<div class='info'>🔧 Creating $missing_count missing tables with their data...</div>";
    
    $created = 0;
    $errors = 0;
    
    foreach ($missing_tables as $table_name) {
        echo "<div class='info'>📦 Processing table: <code>$table_name</code></div>";
        flush();
        
        // Create table
        if (isset($table_definitions[$table_name]['create']) && !empty($table_definitions[$table_name]['create'])) {
            $create_sql = $table_definitions[$table_name]['create'];
            
            // Remove IF NOT EXISTS if present (we want to create it)
            $create_sql = preg_replace('/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS/i', 'CREATE TABLE', $create_sql);
            
            if (mysqli_query($conn, $create_sql)) {
                echo "<div class='success'>✅ Created table: <code>$table_name</code></div>";
                $created++;
                
                // Insert data
                if (isset($table_definitions[$table_name]['inserts']) && count($table_definitions[$table_name]['inserts']) > 0) {
                    $insert_count = 0;
                    foreach ($table_definitions[$table_name]['inserts'] as $insert_sql) {
                        // Execute INSERT in batches if it's a large multi-row INSERT
                        if (mysqli_query($conn, $insert_sql)) {
                            $insert_count++;
                        } else {
                            echo "<div class='warning'>⚠️ Error inserting data into <code>$table_name</code>: " . mysqli_error($conn) . "</div>";
                            // Try to execute as individual statements if batch failed
                            $statements = explode(';', $insert_sql);
                            foreach ($statements as $stmt) {
                                $stmt = trim($stmt);
                                if (!empty($stmt)) {
                                    mysqli_query($conn, $stmt . ';');
                                }
                            }
                        }
                    }
                    echo "<div class='info'>✅ Inserted data into <code>$table_name</code> ($insert_count statements)</div>";
                } else {
                    echo "<div class='info'>ℹ️ Table <code>$table_name</code> created (no data to insert)</div>";
                }
            } else {
                echo "<div class='error'>❌ Error creating table <code>$table_name</code>: " . mysqli_error($conn) . "</div>";
                $errors++;
            }
        } else {
            echo "<div class='warning'>⚠️ No CREATE TABLE statement found for <code>$table_name</code></div>";
        }
        
        flush();
    }
    
    echo "<div class='success'><h3>✅ Synchronization Complete!</h3>";
    echo "<p><strong>Created:</strong> $created tables</p>";
    if ($errors > 0) {
        echo "<p><strong>Errors:</strong> $errors</p>";
    }
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='box'><h2>Step 4: Verification</h2>";
    echo "<div class='success'>✅ No missing tables found. Database is synchronized!</div>";
    echo "</div>";
}

// Final verification
echo "<div class='box'><h2>Final Verification</h2>";
$final_tables_query = mysqli_query($conn, "SHOW TABLES");
$final_tables = [];
while ($row = mysqli_fetch_array($final_tables_query)) {
    $final_tables[] = $row[0];
}
$final_count = count($final_tables);

echo "<div class='success'>📊 Total tables in database: <strong>$final_count</strong></div>";
echo "<div class='info'>📋 Expected tables from SQL file: <strong>$unique_count</strong></div>";

if ($final_count >= $unique_count) {
    echo "<div class='success'>✅ Database synchronization complete! All tables are present.</div>";
} else {
    echo "<div class='warning'>⚠️ Some tables may still be missing. Please check manually.</div>";
}

mysqli_close($conn);
echo "</body></html>";
?>


