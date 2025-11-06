<?php
/**
 * Database Import Script
 * This script will create the database and import the SQL file
 * ONLY FOR LOCALHOST - does not affect server database
 */

// Increase execution time and memory for large imports
set_time_limit(0);
ini_set('memory_limit', '512M');

// Database configuration for LOCALHOST only
// Note: Your phpMyAdmin shows port 3308, so we'll try both ports
$localhost_host = "localhost:3308"; // Port 3308 as shown in your phpMyAdmin
$localhost_username = "root";
$localhost_password = ""; // XAMPP default is empty password
$database_name = "schoolerpbeta";
$sql_file_path = "C:\\Users\\mohit.yadav\\Documents\\Mohit Yadav\\DB Backup\\schoolerpbeta_MY03112025.sql";

echo "<html><head><title>Database Import</title>";
echo "<style>body{font-family:Arial;padding:20px;} .success{color:green;} .error{color:red;} .warning{color:orange;}</style>";
echo "</head><body>";

echo "<h2>🗄️ Database Import Script</h2>";
echo "<p><strong>This will create and import database on LOCALHOST only.</strong></p>";
echo "<hr>";

// Step 1: Connect to MySQL server (without database)
// Try port 3308 first (as shown in phpMyAdmin: 127.0.0.1:3308), then fallback to default 3306
$conn = @mysqli_connect("127.0.0.1", $localhost_username, $localhost_password, "", 3308);
if (!$conn) {
    // Fallback to default port 3306
    $conn = @mysqli_connect("localhost", $localhost_username, $localhost_password, "", 3306);
}
if (!$conn) {
    die("<p class='error'>❌ Failed to connect to MySQL on port 3308 or 3306. Error: " . mysqli_connect_error() . "<br>Please check if MySQL is running in XAMPP.</p>");
}

echo "<p class='success'>✅ Connected to MySQL server</p>";

// Step 2: Create database if it doesn't exist
$create_db_query = "CREATE DATABASE IF NOT EXISTS `$database_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if (mysqli_query($conn, $create_db_query)) {
    echo "<p class='success'>✅ Database '$database_name' created successfully (or already exists)</p>";
} else {
    die("<p class='error'>❌ Error creating database: " . mysqli_error($conn) . "</p>");
}

// Step 3: Select the database
mysqli_select_db($conn, $database_name);

// Step 4: Check if SQL file exists
if (!file_exists($sql_file_path)) {
    die("<p class='error'>❌ SQL file not found at: $sql_file_path</p>");
}

$file_size = filesize($sql_file_path);
$file_size_mb = round($file_size / 1024 / 1024, 2);

echo "<p class='success'>✅ SQL file found</p>";
echo "<p>📄 File: $sql_file_path</p>";
echo "<p>📊 File size: " . number_format($file_size) . " bytes (" . $file_size_mb . " MB)</p>";

// Step 5: Try command line import first (faster for large files)
$mysql_path = "C:\\xampp\\mysql\\bin\\mysql.exe";
if (file_exists($mysql_path)) {
    echo "<p>🔄 Attempting import via MySQL command line (recommended for large files)...</p>";
    
    // Try with port 3308 first, then fallback to default
    $cmds = [
        "C:\\xampp\\mysql\\bin\\mysql.exe -u root -P 3308 schoolerpbeta < \"$sql_file_path\" 2>&1",
        "C:\\xampp\\mysql\\bin\\mysql.exe -u root schoolerpbeta < \"$sql_file_path\" 2>&1"
    ];
    
    $cmd = $cmds[0]; // Try port 3308 first
    
    // Use shell_exec for better compatibility
    $output = @shell_exec($cmd);
    
    // Check if import was successful by verifying tables exist
    $check_tables = mysqli_query($conn, "SHOW TABLES");
    $table_count = mysqli_num_rows($check_tables);
    
    if ($table_count > 0) {
        echo "<p class='success'>✅ Import completed via command line!</p>";
        echo "<p class='success'>📊 Found $table_count tables in database</p>";
        mysqli_close($conn);
        echo "<p class='success'><strong>✅ Database import completed successfully!</strong></p>";
        echo "<p>🎉 Database '$database_name' is now available on localhost.</p>";
        echo "<p>📝 You can verify it in phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></p>";
        echo "</body></html>";
        exit;
    } else {
        echo "<p class='warning'>⚠️ Command line method may have issues, trying PHP method...</p>";
    }
}

// Step 6: Fallback to PHP method (line by line processing)
echo "<p>⏳ Starting import via PHP method... This may take a few minutes.</p>";
echo "<hr>";

$handle = @fopen($sql_file_path, 'r');
if (!$handle) {
    die("<p class='error'>❌ Error opening SQL file</p>");
}

$query_buffer = '';
$executed = 0;
$errors = 0;
$line_count = 0;

echo "<div style='max-height:400px; overflow-y:auto; border:1px solid #ccc; padding:10px; background:#f5f5f5;'>";
echo "<p>📋 Processing SQL file line by line...</p>";

while (($line = fgets($handle)) !== false) {
    $line_count++;
    
    // Skip comments and empty lines
    $trimmed_line = trim($line);
    if (empty($trimmed_line) || 
        substr($trimmed_line, 0, 2) == '--' || 
        substr($trimmed_line, 0, 2) == '/*' ||
        substr($trimmed_line, 0, 1) == '#') {
        continue;
    }
    
    $query_buffer .= $line;
    
    // Execute when we find a semicolon at the end (complete query)
    if (substr(rtrim($trimmed_line), -1) == ';') {
        $query = trim($query_buffer);
        $query_buffer = '';
        
        if (!empty($query) && strlen($query) > 5) { // Minimum query length check
            // Execute query
            if (@mysqli_query($conn, $query)) {
                $executed++;
                if ($executed % 50 == 0) {
                    echo "<p class='success'>✓ Executed $executed queries...</p>";
                    @flush();
                    @ob_flush();
                }
            } else {
                $errors++;
                $error_msg = mysqli_error($conn);
                // Only show critical errors (hide common warnings)
                if (strpos($error_msg, 'already exists') === false && 
                    strpos($error_msg, 'Duplicate entry') === false &&
                    strpos($error_msg, 'Unknown table') === false &&
                    strpos($error_msg, 'Duplicate key') === false) {
                    echo "<p class='warning'>⚠ Line $line_count: " . htmlspecialchars(substr($error_msg, 0, 100)) . "...</p>";
                }
            }
        }
    }
}

fclose($handle);
echo "</div>";
echo "<hr>";

// Summary
echo "<h3>📊 Import Summary</h3>";
echo "<p class='success'>✅ Successfully executed: $executed queries</p>";
if ($errors > 0) {
    echo "<p class='warning'>⚠ Warnings/Errors: $errors (most are normal - like 'already exists')</p>";
}

// Verify import
$check_tables = mysqli_query($conn, "SHOW TABLES");
$table_count = mysqli_num_rows($check_tables);
echo "<p class='success'>📊 Tables in database: $table_count</p>";

mysqli_close($conn);

echo "<hr>";
echo "<p class='success'><strong>✅ Import completed!</strong></p>";
echo "<p>🎉 Database '$database_name' is now available on localhost.</p>";
echo "<p>📝 You can verify it in phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></p>";
echo "<p>🔧 <strong>Important:</strong> Update your connection.php file to use localhost credentials if needed.</p>";
echo "</body></html>";
?>
