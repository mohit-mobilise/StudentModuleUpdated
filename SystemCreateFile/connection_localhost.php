<?php
/**
 * LOCALHOST Database Connection File
 * This file is for local development/testing ONLY
 * The main connection.php remains for server use
 */

// Define LOCALHOST database credentials
// Note: Your phpMyAdmin shows port 3308
$host = "127.0.0.1"; // Use 127.0.0.1 for port specification
$port = 3308; // Port as shown in your phpMyAdmin
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
    die('Could not connect to LOCALHOST database: ' . mysqli_connect_error());
}

// School details (same as server)
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

