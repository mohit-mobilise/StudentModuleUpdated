<?php
/**
 * Database Connection Test Script
 * Run this to test your MySQL connection and find the correct password
 */

// Disable error handler for this test
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<!DOCTYPE html><html><head><title>Database Connection Test</title>";
echo "<style>body{font-family:Arial;padding:20px;max-width:1000px;margin:0 auto;}";
echo ".success{color:green;font-weight:bold;}";
echo ".error{color:red;font-weight:bold;}";
echo ".info{color:blue;}";
echo "table{border-collapse:collapse;width:100%;margin:20px 0;}";
echo "th,td{border:1px solid #ddd;padding:8px;text-align:left;}";
echo "th{background-color:#4CAF50;color:white;}";
echo "tr:nth-child(even){background-color:#f2f2f2;}</style></head><body>";

echo "<h2>MySQL Connection Test</h2>";
echo "<p class='info'>This script will test various connection methods to find the working configuration.</p>";

// Test different connection methods
$tests = [
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => '', 'desc' => '127.0.0.1:3306, Empty password'],
    ['host' => 'localhost', 'port' => null, 'user' => 'root', 'pass' => '', 'desc' => 'localhost (socket), Empty password'],
    ['host' => '127.0.0.1', 'port' => 3306, 'user' => 'root', 'pass' => 'root', 'desc' => '127.0.0.1:3306, Password: root'],
    ['host' => 'localhost', 'port' => null, 'user' => 'root', 'pass' => 'root', 'desc' => 'localhost (socket), Password: root'],
    ['host' => '127.0.0.1', 'port' => 3308, 'user' => 'root', 'pass' => '', 'desc' => '127.0.0.1:3308, Empty password'],
    ['host' => '127.0.0.1', 'port' => null, 'user' => 'root', 'pass' => '', 'desc' => '127.0.0.1 (no port), Empty password'],
];

$success = false;
$results = [];

echo "<table><tr><th>Test</th><th>Result</th><th>Details</th></tr>";

foreach ($tests as $test) {
    $result = ['desc' => $test['desc'], 'success' => false, 'error' => '', 'db_exists' => false];
    
    if ($test['port'] !== null) {
        $con = @mysqli_connect($test['host'], $test['user'], $test['pass'], '', $test['port']);
    } else {
        $con = @mysqli_connect($test['host'], $test['user'], $test['pass']);
    }
    
    if ($con) {
        $result['success'] = true;
        $result['config'] = [
            'host' => $test['host'],
            'port' => $test['port'] ?? 'default/socket',
            'user' => $test['user'],
            'pass' => $test['pass'] === '' ? '(empty)' : $test['pass']
        ];
        
        // Test database access
        if (mysqli_select_db($con, 'schoolerpbeta')) {
            $result['db_exists'] = true;
        }
        
        mysqli_close($con);
        $success = true;
        
        echo "<tr><td>{$test['desc']}</td>";
        echo "<td class='success'>✓ SUCCESS</td>";
        echo "<td>Host: {$test['host']}, Port: " . ($test['port'] ?? 'socket') . ", User: {$test['user']}, Password: " . ($test['pass'] === '' ? 'empty' : $test['pass']);
        echo "<br>Database 'schoolerpbeta': " . ($result['db_exists'] ? "<span class='success'>Exists</span>" : "<span style='color:orange;'>Does not exist (create it in phpMyAdmin)</span>") . "</td></tr>";
        
        $results[] = $result;
        break;
    } else {
        $result['error'] = mysqli_connect_error();
        echo "<tr><td>{$test['desc']}</td>";
        echo "<td class='error'>✗ FAILED</td>";
        echo "<td>" . htmlspecialchars($result['error']) . "</td></tr>";
    }
}

echo "</table>";

if ($success) {
    $working = $results[0];
    echo "<h3 class='success'>✓ Working Configuration Found!</h3>";
    echo "<p><strong>Use this in your .env file:</strong></p>";
    echo "<pre style='background:#e8f5e9;padding:15px;border-radius:5px;border-left:4px solid #4CAF50;'>";
    echo "DB_ENVIRONMENT=localhost\n";
    echo "DB_HOST_LOCAL=" . htmlspecialchars($working['config']['host']) . "\n";
    if ($working['config']['port'] !== 'default/socket') {
        echo "DB_PORT_LOCAL=" . htmlspecialchars($working['config']['port']) . "\n";
    } else {
        echo "DB_PORT_LOCAL=3306\n";
    }
    echo "DB_USERNAME_LOCAL=" . htmlspecialchars($working['config']['user']) . "\n";
    echo "DB_PASSWORD_LOCAL=" . ($working['config']['pass'] === '(empty)' ? '' : htmlspecialchars($working['config']['pass'])) . "\n";
    echo "DB_NAME_LOCAL=schoolerpbeta\n";
    echo "</pre>";
    
    if (!$working['db_exists']) {
        echo "<p class='error'><strong>⚠ Important:</strong> The database 'schoolerpbeta' does not exist. Create it in phpMyAdmin before using the application.</p>";
    }
}

if (!$success) {
    echo "<h3>All connection attempts failed. Please:</h3>";
    echo "<ol>";
    echo "<li>Check if MySQL is running in XAMPP Control Panel</li>";
    echo "<li>Check your MySQL root password in phpMyAdmin</li>";
    echo "<li>Create a .env file with the correct password</li>";
    echo "</ol>";
}
?>

