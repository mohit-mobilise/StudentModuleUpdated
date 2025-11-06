<?php
// Load environment variables
require_once __DIR__ . '/Users/includes/env_loader.php';

// Get fee database credentials from environment variables
$host_fee = $_ENV['DB_HOST_FEE'] ?? getenv('DB_HOST_FEE') ?? 'localhost';
$username_fee = $_ENV['DB_USERNAME_FEE'] ?? getenv('DB_USERNAME_FEE') ?? 'schoolerpbeta_admin';
$password_fee = $_ENV['DB_PASSWORD_FEE'] ?? getenv('DB_PASSWORD_FEE') ?? '';
$dbname_fee = $_ENV['DB_NAME_FEE'] ?? getenv('DB_NAME_FEE') ?? 'schoolerpbeta';

if($chk_adm_no == 'R' || $chk_adm_no == 'E' || $chk_adm_no == 'V') {
    // Create a connection using mysqli (PHP 8.2 compatible)
    $Con = mysqli_connect($host_fee, $username_fee, $password_fee, $dbname_fee);

    // Check the connection
    if (!$Con) {
        error_log('Fee connection failed: ' . mysqli_connect_error());
        die('Could not connect to database. Please check your configuration.');
    }
} else {
    echo "Invalid Admission No";
    exit();
}


// Set UTF-8 character encoding and timezone
mysqli_set_charset($Con, "utf8");
date_default_timezone_set('Asia/Calcutta');

?>