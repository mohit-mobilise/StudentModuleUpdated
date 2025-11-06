<?php
// Load environment variables
require_once __DIR__ . '/Users/includes/env_loader.php';

function connection_dpseok() {
    // Get credentials from environment variables
    $host = $_ENV['DB_HOST_MULTI'] ?? getenv('DB_HOST_MULTI') ?? '10.26.1.4';
    $username = $_ENV['DB_USERNAME_MULTI'] ?? getenv('DB_USERNAME_MULTI') ?? 'dpsrkp_staging';
    $password = $_ENV['DB_PASSWORD_MULTI'] ?? getenv('DB_PASSWORD_MULTI') ?? '';
    $dbname = $_ENV['DB_NAME_DPSEOK'] ?? getenv('DB_NAME_DPSEOK') ?? 'dpseok';
    
    // Create a connection using mysqli (PHP 8.2 compatible)
    $conn = mysqli_connect($host, $username, $password, $dbname);
    
    // Check connection
    if (!$conn) {
        error_log('DPSEOK connection failed: ' . mysqli_connect_error());
        die('Could not connect to database. Please check your configuration.');
    }

    // Set UTF-8 character encoding and timezone
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Calcutta');
    return $conn;
}


function connection_dpsrkp() {
    // Get credentials from environment variables
    $host = $_ENV['DB_HOST_MULTI'] ?? getenv('DB_HOST_MULTI') ?? '10.26.1.4';
    $username = $_ENV['DB_USERNAME_MULTI'] ?? getenv('DB_USERNAME_MULTI') ?? 'dpsrkp_staging';
    $password = $_ENV['DB_PASSWORD_MULTI'] ?? getenv('DB_PASSWORD_MULTI') ?? '';
    $dbname = $_ENV['DB_NAME_DPSRKP'] ?? getenv('DB_NAME_DPSRKP') ?? 'dpsrkp';
    
    // Create a connection using mysqli (PHP 8.2 compatible)
    $conn = mysqli_connect($host, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        error_log('DPSRKP connection failed: ' . mysqli_connect_error());
        die('Could not connect to database. Please check your configuration.');
    }

    // Set UTF-8 character encoding and timezone
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Calcutta');

    return $conn;
}


function connection_dpsVV() {
    // Get credentials from environment variables
    $host = $_ENV['DB_HOST_MULTI'] ?? getenv('DB_HOST_MULTI') ?? '10.26.1.4';
    $username = $_ENV['DB_USERNAME_MULTI'] ?? getenv('DB_USERNAME_MULTI') ?? 'dpsrkp_staging';
    $password = $_ENV['DB_PASSWORD_MULTI'] ?? getenv('DB_PASSWORD_MULTI') ?? '';
    $dbname = $_ENV['DB_NAME_DPSVV'] ?? getenv('DB_NAME_DPSVV') ?? 'dpsvv';

    // Create a connection using mysqli (PHP 8.2 compatible)
    $conn = mysqli_connect($host, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        error_log('DPSVV connection failed: ' . mysqli_connect_error());
        die('Could not connect to database. Please check your configuration.');
    }

    // Set UTF-8 character encoding and timezone
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Calcutta');

    return $conn;
}



function connection_dpshrms() {
    // Get credentials from environment variables
    $host = $_ENV['DB_HOST_MULTI'] ?? getenv('DB_HOST_MULTI') ?? '10.26.1.4';
    $username = $_ENV['DB_USERNAME_MULTI'] ?? getenv('DB_USERNAME_MULTI') ?? 'dpsrkp_staging';
    $password = $_ENV['DB_PASSWORD_MULTI'] ?? getenv('DB_PASSWORD_MULTI') ?? '';
    $dbname = $_ENV['DB_NAME_DPSHRMS'] ?? getenv('DB_NAME_DPSHRMS') ?? 'dpshrms';

    // Create a connection using mysqli (PHP 8.2 compatible)
    $conn = mysqli_connect($host, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        error_log('DPSHRMS connection failed: ' . mysqli_connect_error());
        die('Could not connect to database. Please check your configuration.');
    }

    // Set UTF-8 character encoding and timezone
    mysqli_set_charset($conn, "utf8");
    date_default_timezone_set('Asia/Calcutta');

    return $conn;
}






?>
