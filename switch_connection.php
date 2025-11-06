<?php
// Load environment variables
require_once __DIR__ . '/Users/includes/env_loader.php';

// Get database credentials from environment variables
$host = $_ENV['DB_HOST_SWITCH'] ?? getenv('DB_HOST_SWITCH') ?? '10.26.1.4';
$username = $_ENV['DB_USERNAME_SWITCH'] ?? getenv('DB_USERNAME_SWITCH') ?? 'dpsrkp_staging';
$password = $_ENV['DB_PASSWORD_SWITCH'] ?? getenv('DB_PASSWORD_SWITCH') ?? '';
$dbname = $_ENV['DB_NAME_SWITCH'] ?? getenv('DB_NAME_SWITCH') ?? 'dpsnavimumbai';

// Create a connection using mysqli (PHP 8.2 compatible)
$Con = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$Con) {
    error_log('Switch connection failed: ' . mysqli_connect_error());
    die('Could not connect to database. Please check your configuration.');
}

// School details
$SchoolName = "N. K. Bagrodia Public School";
$SchoolName2 = "N. K. Bagrodia Public School Rohini";
$SchoolIncidentMailId = "incident@nkbpsis.in";
$PrincipalMailId = "principal@nkbpsis.in";
$AccountsMailId = "accounts@nkbpsis.in";
$BaseURL = "https://schoolerpbeta.mobilisepro.com/";
$imageBaseURl="https://schoolerpbeta.mobilisepro.com/";
if (!defined('imgUrl'))
{
    define('imgUrl',$imageBaseURl);
}


// Set UTF-8 character encoding and timezone
mysqli_set_charset($Con, "utf8");
date_default_timezone_set('Asia/Calcutta');
?>
