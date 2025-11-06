<?php
/**
 * MySQL Diagnostic Tool for XAMPP
 * This script helps identify why MySQL keeps shutting down
 */

echo "<h2>MySQL Diagnostic Report</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .error { color: red; }
    .warning { color: orange; }
    .info { color: blue; }
    pre { background: #f4f4f4; padding: 10px; border-radius: 5px; }
</style>";

echo "<h3>1. Port Status Check</h3>";
$ports = [3306, 3308];
foreach ($ports as $port) {
    $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);
    if ($connection) {
        echo "<p class='success'>✓ Port $port is OPEN and accepting connections</p>";
        fclose($connection);
    } else {
        echo "<p class='error'>✗ Port $port is CLOSED or blocked (Error: $errstr)</p>";
    }
}

echo "<h3>2. MySQL Connection Test</h3>";
// Test connection on port 3308
$host = "127.0.0.1";
$port = 3308;
$username = "root";
$password = "";
$dbname = "schoolerpbeta";

$Con = @mysqli_connect($host, $username, $password, $dbname, $port);
if (!$Con) {
    echo "<p class='error'>✗ Connection failed on port 3308: " . mysqli_connect_error() . "</p>";
    // Try port 3306
    $Con = @mysqli_connect($host, $username, $password, $dbname, 3306);
    if ($Con) {
        echo "<p class='success'>✓ Connection successful on port 3306</p>";
    } else {
        echo "<p class='error'>✗ Connection failed on port 3306: " . mysqli_connect_error() . "</p>";
    }
} else {
    echo "<p class='success'>✓ Connection successful on port 3308</p>";
    mysqli_close($Con);
}

echo "<h3>3. XAMPP MySQL Path Check</h3>";
$xamppPath = "C:\\xampp";
$mysqlPath = $xamppPath . "\\mysql";
$dataPath = $mysqlPath . "\\data";
$errorLogPath = $mysqlPath . "\\data\\mysql_error.log";
$myIniPath = $mysqlPath . "\\my.ini";

if (file_exists($mysqlPath)) {
    echo "<p class='success'>✓ MySQL directory found: $mysqlPath</p>";
} else {
    echo "<p class='error'>✗ MySQL directory not found: $mysqlPath</p>";
}

if (file_exists($dataPath)) {
    echo "<p class='success'>✓ Data directory found: $dataPath</p>";
} else {
    echo "<p class='error'>✗ Data directory not found: $dataPath</p>";
}

if (file_exists($errorLogPath)) {
    echo "<p class='info'>ℹ Error log found: $errorLogPath</p>";
    $logContent = file_get_contents($errorLogPath);
    $lastLines = array_slice(explode("\n", $logContent), -20);
    echo "<h4>Last 20 lines from MySQL error log:</h4>";
    echo "<pre>" . htmlspecialchars(implode("\n", $lastLines)) . "</pre>";
} else {
    echo "<p class='warning'>⚠ Error log not found: $errorLogPath</p>";
}

if (file_exists($myIniPath)) {
    echo "<p class='success'>✓ Configuration file found: $myIniPath</p>";
    $myIniContent = file_get_contents($myIniPath);
    // Check for common problematic settings
    if (strpos($myIniPath, 'innodb_buffer_pool_size') !== false) {
        echo "<p class='info'>ℹ InnoDB buffer pool size is configured</p>";
    }
} else {
    echo "<p class='warning'>⚠ Configuration file not found: $myIniPath</p>";
}

echo "<h3>4. Common Solutions</h3>";
echo "<ol>";
echo "<li><strong>Check XAMPP Control Panel:</strong> Make sure MySQL is actually running (green status)</li>";
echo "<li><strong>Check Port Conflicts:</strong> If port 3306 is in use, MySQL will fail to start. Check Task Manager for other MySQL services</li>";
echo "<li><strong>Check Error Log:</strong> The error log above should show the exact reason for shutdown</li>";
echo "<li><strong>Check my.ini:</strong> Ensure the configuration file is correct and not corrupted</li>";
echo "<li><strong>Check Windows Services:</strong> Make sure no other MySQL service is running (services.msc)</li>";
echo "<li><strong>Check Antivirus:</strong> Some antivirus software blocks MySQL</li>";
echo "<li><strong>Check Disk Space:</strong> Ensure you have enough disk space</li>";
echo "<li><strong>InnoDB Corruption:</strong> If InnoDB tables are corrupted, MySQL may crash</li>";
echo "</ol>";

echo "<h3>5. Quick Fix Commands (Run in Command Prompt as Administrator)</h3>";
echo "<pre>";
echo "# Stop any running MySQL service\n";
echo "net stop MySQL\n";
echo "net stop MySQL80\n";
echo "net stop MySQL57\n\n";
echo "# Or use XAMPP Control Panel to stop MySQL\n";
echo "# Then start it again from XAMPP Control Panel\n";
echo "</pre>";

echo "<h3>6. Next Steps</h3>";
echo "<ul>";
echo "<li>Check the error log above for specific error messages</li>";
echo "<li>Look for port conflicts or permission issues</li>";
echo "<li>Try restarting MySQL from XAMPP Control Panel</li>";
echo "<li>If problem persists, check Windows Event Viewer for system errors</li>";
echo "</ul>";

?>

