<?php
/**
 * Database Setup Script - Direct Method
 * This will create the database and show status
 */

// Increase limits
set_time_limit(0);
ini_set('memory_limit', '512M');

$localhost_username = "root";
$localhost_password = ""; 
$database_name = "schoolerpbeta";
$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";

echo "<html><head><title>Database Setup</title>";
echo "<meta charset='UTF-8'>";
echo "<style>
    body{font-family:Arial;padding:20px;background:#f5f5f5;}
    .success{color:green;background:#d4edda;padding:10px;border-radius:5px;margin:10px 0;}
    .error{color:red;background:#f8d7da;padding:10px;border-radius:5px;margin:10px 0;}
    .warning{color:orange;background:#fff3cd;padding:10px;border-radius:5px;margin:10px 0;}
    .info{color:blue;background:#d1ecf1;padding:10px;border-radius:5px;margin:10px 0;}
    .box{border:2px solid #007bff;padding:15px;margin:20px 0;border-radius:5px;background:white;}
    pre{background:#f8f9fa;padding:10px;border-radius:5px;overflow-x:auto;}
</style>";
echo "</head><body>";
echo "<h1>🗄️ Database Setup for Localhost</h1>";
echo "<div class='info'><strong>Server:</strong> 127.0.0.1:3308 (as shown in phpMyAdmin)</div>";

// Step 1: Test Connection
echo "<div class='box'>";
echo "<h2>Step 1: Testing Connection</h2>";

// Try port 3308 first
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
$port_used = 3308;

if (!$conn) {
    echo "<p class='warning'>⚠️ Could not connect to port 3308, trying port 3306...</p>";
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
    $port_used = 3306;
}

if (!$conn) {
    die("<div class='error'>
        <h3>❌ Connection Failed!</h3>
        <p><strong>Error:</strong> " . mysqli_connect_error() . "</p>
        <p><strong>Please check:</strong></p>
        <ul>
            <li>Is MySQL running in XAMPP Control Panel?</li>
            <li>Is the port correct? (Your phpMyAdmin shows 3308)</li>
            <li>Try starting/restarting MySQL service in XAMPP</li>
        </ul>
    </div></body></html>");
}

echo "<p class='success'>✅ Connected to MySQL server on port $port_used</p>";
echo "</div>";

// Step 2: Check if database exists
echo "<div class='box'>";
echo "<h2>Step 2: Checking Database Status</h2>";

$check_db = mysqli_query($conn, "SHOW DATABASES LIKE '$database_name'");
if (mysqli_num_rows($check_db) > 0) {
    echo "<p class='info'>ℹ️ Database '$database_name' already exists</p>";
    mysqli_select_db($conn, $database_name);
    $tables = mysqli_query($conn, "SHOW TABLES");
    $table_count = mysqli_num_rows($tables);
    echo "<p class='info'>📊 Current tables in database: $table_count</p>";
    
    if ($table_count == 0) {
        echo "<p class='warning'>⚠️ Database exists but has no tables. Ready to import.</p>";
    } else {
        echo "<p class='warning'>⚠️ Database already has tables. Import will add/update data.</p>";
    }
} else {
    echo "<p class='info'>ℹ️ Database '$database_name' does not exist. Will create it now.</p>";
}

echo "</div>";

// Step 3: Create Database
echo "<div class='box'>";
echo "<h2>Step 3: Creating Database</h2>";

$create_db_query = "CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $create_db_query)) {
    echo "<p class='success'>✅ Database '$database_name' created/verified successfully!</p>";
    mysqli_select_db($conn, $database_name);
} else {
    die("<p class='error'>❌ Error creating database: " . mysqli_error($conn) . "</p>");
}

echo "</div>";

// Step 4: Check SQL File
echo "<div class='box'>";
echo "<h2>Step 4: Checking SQL File</h2>";

if (!file_exists($sql_file_path)) {
    die("<p class='error'>❌ SQL file not found at:<br><code>$sql_file_path</code><br><br>Please check the file path.</p>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);

echo "<p class='success'>✅ SQL file found</p>";
echo "<p><strong>Location:</strong> <code>$sql_file_path</code></p>";
echo "<p><strong>Size:</strong> " . number_format($file_size) . " bytes (" . $file_size_mb . " MB)</p>";
echo "</div>";

// Step 5: Import Options
echo "<div class='box'>";
echo "<h2>Step 5: Import Options</h2>";
echo "<p class='info'>Choose how you want to import:</p>";

echo "<h3>Option A: Use phpMyAdmin (Recommended for large files)</h3>";
echo "<ol>";
echo "<li>Refresh phpMyAdmin (press F5)</li>";
echo "<li>You should now see '<strong>$database_name</strong>' in the left sidebar</li>";
echo "<li>Click on '<strong>$database_name</strong>'</li>";
echo "<li>Go to the '<strong>Import</strong>' tab</li>";
echo "<li>Click '<strong>Choose File</strong>'</li>";
echo "<li>Select: <code>$sql_file_path</code></li>";
echo "<li>Click '<strong>Go</strong>' button at the bottom</li>";
echo "</ol>";

echo "<h3>Option B: Continue with PHP Import</h3>";
echo "<p>This will import directly via PHP (may take time for large files):</p>";

if (isset($_GET['import'])) {
    // Step 6: Import SQL File
    echo "<div class='box' style='border-color:#28a745;'>";
    echo "<h2>Step 6: Importing SQL File</h2>";
    echo "<p class='info'>⏳ Starting import... This may take several minutes. Please wait...</p>";
    echo "<div style='max-height:400px; overflow-y:auto; border:1px solid #ccc; padding:10px; background:#f8f9fa;'>";
    
    $handle = @fopen($sql_file_path, 'r');
    if (!$handle) {
        die("<p class='error'>❌ Could not open SQL file</p>");
    }
    
    $query_buffer = '';
    $executed = 0;
    $errors = 0;
    $line_num = 0;
    
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
        
        // Execute when we find semicolon (complete query)
        if (substr(rtrim($trimmed), -1) == ';') {
            $query = trim($query_buffer);
            $query_buffer = '';
            
            if (!empty($query) && strlen($query) > 5) {
                if (@mysqli_query($conn, $query)) {
                    $executed++;
                    if ($executed % 100 == 0) {
                        echo "<p class='success'>✓ Processed $executed queries...</p>";
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
                        strpos($err, 'Duplicate key') === false) {
                        echo "<p class='warning'>⚠ Line $line_num: " . htmlspecialchars(substr($err, 0, 150)) . "...</p>";
                    }
                }
            }
        }
    }
    
    fclose($handle);
    echo "</div>";
    
    // Verify tables
    $tables_check = mysqli_query($conn, "SHOW TABLES");
    $final_table_count = mysqli_num_rows($tables_check);
    
    echo "<hr>";
    echo "<h3>📊 Import Summary</h3>";
    echo "<p class='success'>✅ Queries executed: $executed</p>";
    if ($errors > 0) {
        echo "<p class='warning'>⚠ Warnings: $errors (most are normal)</p>";
    }
    echo "<p class='success'><strong>✅ Final table count: $final_table_count tables</strong></p>";
    
    echo "</div>";
} else {
    echo "<a href='?import=yes' style='display:inline-block;padding:15px 30px;background:#007bff;color:white;text-decoration:none;border-radius:5px;font-size:16px;margin:10px 0;'>🚀 Start PHP Import</a>";
}

echo "</div>";

mysqli_close($conn);

echo "<hr>";
echo "<div class='info'>";
echo "<h3>✅ Database Setup Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>Refresh phpMyAdmin to see the database</li>";
echo "<li>Or click on '<strong>$database_name</strong>' in phpMyAdmin left sidebar</li>";
echo "<li>Verify tables are imported correctly</li>";
echo "</ol>";
echo "</div>";

echo "</body></html>";
?>

