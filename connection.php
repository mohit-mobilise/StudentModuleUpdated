<?php
/**
 * Database Connection File
 * Uses environment variables for secure credential management
 */

// Load environment variables
require_once __DIR__ . '/Users/includes/env_loader.php';

// Load error handler for secure error handling (if enabled in .env)
$enable_error_handler = $_ENV['ENABLE_ERROR_HANDLER'] ?? getenv('ENABLE_ERROR_HANDLER') ?? 'true';
if (strtolower($enable_error_handler) === 'true' || $enable_error_handler === '1') {
    require_once __DIR__ . '/Users/includes/error_handler.php';
}

// Add security HTTP headers
require_once __DIR__ . '/Users/includes/security_headers.php';

// Get database environment (server or localhost)
// Default to 'server' (live/production) if .env doesn't exist
$env_file_exists = file_exists(__DIR__ . '/.env');

$db_environment = $_ENV['DB_ENVIRONMENT'] ?? getenv('DB_ENVIRONMENT') ?? 'server';

// ============================================
// DATABASE SETTINGS FROM ENVIRONMENT VARIABLES
// ============================================
if ($db_environment === 'server') {
    // Live/Production Server database settings
    // Live credentials - can be overridden via .env file if needed
    $host = $_ENV['DB_HOST_SERVER'] ?? getenv('DB_HOST_SERVER') ?? '10.26.1.196';
    $username = $_ENV['DB_USERNAME_SERVER'] ?? getenv('DB_USERNAME_SERVER') ?? 'schoolerp';
    $password = $_ENV['DB_PASSWORD_SERVER'] ?? getenv('DB_PASSWORD_SERVER') ?? '6kA2BdIBZ8QcL6y49Dgk';
    $dbname = $_ENV['DB_NAME_SERVER'] ?? getenv('DB_NAME_SERVER') ?? 'schoolerpbeta';
    
    $Con = mysqli_connect($host, $username, $password, $dbname);
    if (!$Con) {
        $error = mysqli_connect_error();
        error_log('Database connection failed: ' . $error);
        
        // Provide helpful error message
        if (!file_exists(__DIR__ . '/.env')) {
            die('<!DOCTYPE html><html><head><title>Configuration Required</title></head><body style="font-family: Arial; padding: 20px;"><h2>Configuration Required</h2><p>The application requires a .env file for database configuration.</p><p><strong>Steps to fix:</strong></p><ol><li>Create a file named <code>.env</code> in the root directory</li><li>Add your database credentials (see ENV_SETUP_INSTRUCTIONS.md for template)</li><li>Or use default localhost settings (for XAMPP):<br><code>DB_ENVIRONMENT=localhost</code></li></ol><p><strong>Current Error:</strong> ' . htmlspecialchars($error) . '</p></body></html>');
        } else {
            die('<!DOCTYPE html><html><head><title>Database Connection Error</title></head><body style="font-family: Arial; padding: 20px;"><h2>Database Connection Failed</h2><p>Could not connect to database. Please check your .env configuration.</p><p><strong>Error:</strong> ' . htmlspecialchars($error) . '</p></body></html>');
        }
    }
} else {
    // Localhost database settings (default for XAMPP)
    // Use explicit defaults when .env doesn't exist
    $host = (!empty($_ENV['DB_HOST_LOCAL']) ? $_ENV['DB_HOST_LOCAL'] : (!empty(getenv('DB_HOST_LOCAL')) ? getenv('DB_HOST_LOCAL') : '127.0.0.1'));
    $port = (!empty($_ENV['DB_PORT_LOCAL']) ? (int)$_ENV['DB_PORT_LOCAL'] : ((getenv('DB_PORT_LOCAL') !== false && getenv('DB_PORT_LOCAL') !== '') ? (int)getenv('DB_PORT_LOCAL') : 3306));
    $username = (!empty($_ENV['DB_USERNAME_LOCAL']) ? $_ENV['DB_USERNAME_LOCAL'] : (!empty(getenv('DB_USERNAME_LOCAL')) ? getenv('DB_USERNAME_LOCAL') : 'root'));
    $password = (isset($_ENV['DB_PASSWORD_LOCAL']) ? $_ENV['DB_PASSWORD_LOCAL'] : (getenv('DB_PASSWORD_LOCAL') !== false ? getenv('DB_PASSWORD_LOCAL') : ''));
    $dbname = (!empty($_ENV['DB_NAME_LOCAL']) ? $_ENV['DB_NAME_LOCAL'] : (!empty(getenv('DB_NAME_LOCAL')) ? getenv('DB_NAME_LOCAL') : 'schoolerpbeta'));
    
    // Validate all required parameters are set
    if (empty($host) || empty($username) || empty($dbname)) {
        die('<!DOCTYPE html><html><head><title>Configuration Error</title></head><body style="font-family: Arial; padding: 20px;"><h2>Database Configuration Error</h2><p>Required database parameters are missing.</p><p>Host: ' . htmlspecialchars($host ?: 'EMPTY') . '<br>Username: ' . htmlspecialchars($username ?: 'EMPTY') . '<br>Database: ' . htmlspecialchars($dbname ?: 'EMPTY') . '</p><p>Please create a .env file with proper database credentials.</p></body></html>');
    }
    
    // Comprehensive connection strategy - try multiple methods
    $Con = null;
    $connection_attempts = [];
    
    // Strategy 1: Try with provided credentials and port
    $Con = @mysqli_connect($host, $username, $password, $dbname, $port);
    $connection_attempts[] = "Host: $host, Port: $port, User: $username, Password: " . (empty($password) ? 'empty' : 'set');
    
    // Strategy 2: If password is empty, try common XAMPP passwords
    if (!$Con && empty($password)) {
        $common_passwords = ['', 'root'];
        foreach ($common_passwords as $try_password) {
            $Con = @mysqli_connect($host, $username, $try_password, $dbname, $port);
            if ($Con) {
                $connection_attempts[] = "SUCCESS: Host: $host, Port: $port, User: $username, Password: " . (empty($try_password) ? 'empty' : $try_password);
                break;
            }
        }
    }
    
    // Strategy 3: Try localhost without port (uses socket)
    if (!$Con) {
        $Con = @mysqli_connect("localhost", $username, $password, $dbname);
        $connection_attempts[] = "Host: localhost (socket), User: $username, Password: " . (empty($password) ? 'empty' : 'set');
    }
    
    // Strategy 4: Try localhost with empty password
    if (!$Con && empty($password)) {
        $Con = @mysqli_connect("localhost", $username, '', $dbname);
        $connection_attempts[] = "Host: localhost (socket), User: $username, Password: empty";
    }
    
    // Strategy 5: Try 127.0.0.1 without port
    if (!$Con) {
        $Con = @mysqli_connect("127.0.0.1", $username, $password, $dbname);
        $connection_attempts[] = "Host: 127.0.0.1 (no port), User: $username, Password: " . (empty($password) ? 'empty' : 'set');
    }
    
    // Strategy 6: Try connecting without database first, then select it
    if (!$Con) {
        $temp_con = @mysqli_connect($host, $username, $password, '', $port);
        if ($temp_con) {
            if (mysqli_select_db($temp_con, $dbname)) {
                $Con = $temp_con;
                $connection_attempts[] = "SUCCESS: Connected without DB, then selected $dbname";
            } else {
                mysqli_close($temp_con);
            }
        }
    }
    
    // Strategy 7: Try localhost without database
    if (!$Con) {
        $temp_con = @mysqli_connect("localhost", $username, $password);
        if ($temp_con) {
            if (mysqli_select_db($temp_con, $dbname)) {
                $Con = $temp_con;
                $connection_attempts[] = "SUCCESS: Connected to localhost without DB, then selected $dbname";
            } else {
                mysqli_close($temp_con);
            }
        }
    }
    
    if (!$Con) {
        $error = mysqli_connect_error();
        $last_error = error_get_last();
        error_log('Database connection failed after all attempts: ' . $error);
        error_log('Connection attempts: ' . implode(' | ', $connection_attempts));
        
        // Provide comprehensive error message with troubleshooting steps
        $error_msg = '<!DOCTYPE html><html><head><title>Database Connection Error</title>';
        $error_msg .= '<style>body{font-family:Arial;padding:20px;max-width:900px;margin:0 auto;line-height:1.6;}';
        $error_msg .= 'code{background:#f4f4f4;padding:2px 6px;border-radius:3px;font-family:Consolas,monospace;}';
        $error_msg .= 'pre{background:#f4f4f4;padding:15px;border-radius:5px;overflow-x:auto;border-left:4px solid #007bff;}';
        $error_msg .= '.success{color:#28a745;font-weight:bold;}';
        $error_msg .= '.error{color:#dc3545;font-weight:bold;}';
        $error_msg .= '.warning{color:#ffc107;font-weight:bold;}';
        $error_msg .= 'ul li{margin:5px 0;}</style></head><body>';
        
        $error_msg .= '<h2>Database Connection Failed</h2>';
        $error_msg .= '<p class="error"><strong>Error:</strong> ' . htmlspecialchars($error ?: 'Unknown error') . '</p>';
        
        $error_msg .= '<h3>Connection Attempts Made:</h3>';
        $error_msg .= '<ul>';
        foreach ($connection_attempts as $attempt) {
            $error_msg .= '<li>' . htmlspecialchars($attempt) . '</li>';
        }
        $error_msg .= '</ul>';
        
        $error_msg .= '<h3>Troubleshooting Steps:</h3>';
        $error_msg .= '<ol>';
        
        // Step 1: Check MySQL Service
        $error_msg .= '<li><strong>Check if MySQL is running:</strong><br>';
        $error_msg .= 'Open XAMPP Control Panel and ensure MySQL service is started (green "Running" status).<br>';
        $error_msg .= 'If not running, click "Start" next to MySQL.</li>';
        
        // Step 2: Database exists
        $error_msg .= '<li><strong>Check if database exists:</strong><br>';
        $error_msg .= 'Open phpMyAdmin (<a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a>) and verify:<br>';
        $error_msg .= '<ul><li>The database <code>' . htmlspecialchars($dbname) . '</code> exists</li>';
        $error_msg .= '<li>If it doesn\'t exist, create it: <code>CREATE DATABASE ' . htmlspecialchars($dbname) . ';</code></li></ul></li>';
        
        // Step 3: Password issue
        if (strpos($error, 'Access denied') !== false || strpos(strtolower($error), 'password') !== false) {
            $error_msg .= '<li class="error"><strong>Password Authentication Issue:</strong><br>';
            $error_msg .= 'Your MySQL root user requires a password. Solutions:<br>';
            $error_msg .= '<ul>';
            $error_msg .= '<li><strong>Option A - Find your password:</strong><br>';
            $error_msg .= 'Try accessing phpMyAdmin with different passwords (empty, "root", or check your XAMPP documentation)</li>';
            $error_msg .= '<li><strong>Option B - Reset password:</strong><br>';
            $error_msg .= 'In XAMPP Control Panel, click "Stop" on MySQL, then "Start" again. Some XAMPP versions reset to no password.</li>';
            $error_msg .= '<li><strong>Option C - Set password in .env:</strong><br>';
            $error_msg .= 'Create a <code>.env</code> file with: <code>DB_PASSWORD_LOCAL=your_actual_password</code></li>';
            $error_msg .= '</ul></li>';
        }
        
        // Step 4: Create .env file
        $error_msg .= '<li><strong>Create .env configuration file:</strong><br>';
        $error_msg .= 'Create a file named <code>.env</code> in the root directory:<br>';
        $error_msg .= '<code style="display:block;margin:10px 0;">C:\\xampp\\htdocs\\cursorai\\Testing\\studentportal\\.env</code>';
        $error_msg .= '<pre>DB_ENVIRONMENT=localhost
    DB_HOST_LOCAL=127.0.0.1
    DB_PORT_LOCAL=3306
    DB_USERNAME_LOCAL=root
    DB_PASSWORD_LOCAL=your_password_here
    DB_NAME_LOCAL=' . htmlspecialchars($dbname) . '</pre>';
        $error_msg .= '<p><strong>Replace <code>your_password_here</code> with your actual MySQL root password.</strong></p>';
        $error_msg .= '<p>If MySQL has no password, use: <code>DB_PASSWORD_LOCAL=</code> (empty value)</p></li>';
        
        // Step 5: Test connection
        $error_msg .= '<li><strong>Test your connection:</strong><br>';
        $error_msg .= 'Visit <a href="test_db_connection.php" target="_blank">test_db_connection.php</a> to test different connection configurations.</li>';
        
        // Step 6: Check permissions
        $error_msg .= '<li><strong>Check MySQL user permissions:</strong><br>';
        $error_msg .= 'In phpMyAdmin, go to "User Accounts" tab and verify:<br>';
        $error_msg .= '<ul><li>User <code>root@localhost</code> exists</li>';
        $error_msg .= '<li>User has "ALL PRIVILEGES" on the database</li></ul></li>';
        
        // Step 7: Alternative connection methods
        $error_msg .= '<li><strong>Try alternative connection:</strong><br>';
        $error_msg .= 'If using a different MySQL port or socket, update your <code>.env</code> file accordingly.</li>';
        
        $error_msg .= '</ol>';
        
        $error_msg .= '<h3>Quick Fix Commands:</h3>';
        $error_msg .= '<p>If you have access to MySQL command line, try:</p>';
        $error_msg .= '<pre># Connect to MySQL
    mysql -u root -p

    # Create database if it doesn\'t exist
    CREATE DATABASE IF NOT EXISTS ' . htmlspecialchars($dbname) . ';

    # Grant permissions (if needed)
    GRANT ALL PRIVILEGES ON ' . htmlspecialchars($dbname) . '.* TO \'root\'@\'localhost\';
    FLUSH PRIVILEGES;</pre>';
        
        $error_msg .= '<p><em><strong>Note:</strong> After fixing the issue, refresh this page to test the connection again.</em></p>';
        $error_msg .= '</body></html>';
        
        die($error_msg);
    }
    
    // Connection successful - verify database access
    if (!mysqli_select_db($Con, $dbname)) {
        // Database doesn't exist - create it
        $create_db_query = "CREATE DATABASE IF NOT EXISTS `" . mysqli_real_escape_string($Con, $dbname) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        if (mysqli_query($Con, $create_db_query)) {
            mysqli_select_db($Con, $dbname);
        } else {
            error_log('Warning: Could not create database: ' . mysqli_error($Con));
        }
    }
}

// ============================================
// LOCALHOST DATABASE SETTINGS (COMMENTED OUT)
// ============================================
/*
$host = "127.0.0.1"; // Use 127.0.0.1 for port specification
$port = 3308; // Port as shown in your phpMyAdmin (try 3308 first, then 3306)
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (empty)
$dbname = "schoolerpbeta"; // Database name

// Create a connection using mysqli (PHP 8.2 compatible) with port 3308
$Con = mysqli_connect($host, $username, $password, $dbname, $port);

// If port 3308 fails, try default port 3306
if (!$Con) {
    $Con = mysqli_connect("localhost", $username, $password, $dbname, 3306);
}

// Check the connection
if (!$Con) {
    die('Could not connect to LOCALHOST database: ' . mysqli_connect_error() . '<br>Please make sure MySQL is running in XAMPP Control Panel.');
}
*/

// School details
$SchoolName = "N. K. Bagrodia Public School";
$SchoolName2 = "N. K. Bagrodia Public School Rohini";
$SchoolIncidentMailId = "incident@nkbpsis.in";
$PrincipalMailId = "principal@nkbpsis.in";
$AccountsMailId = "accounts@nkbpsis.in";

// ============================================
// URL SETTINGS FROM ENVIRONMENT VARIABLES
// ============================================
$BaseURL = $_ENV['BASE_URL'] ?? getenv('BASE_URL') ?? "http://localhost/cursorai/Testing/studentportal/";
$imageBaseURl = $_ENV['IMAGE_BASE_URL'] ?? getenv('IMAGE_BASE_URL') ?? "http://localhost/cursorai/Testing/studentportal/";

if (!defined('imgUrl'))
{
    define('imgUrl',$imageBaseURl);
}

// Set UTF-8 character encoding and timezone
mysqli_set_charset($Con, "utf8");
date_default_timezone_set('Asia/Calcutta');
?>
