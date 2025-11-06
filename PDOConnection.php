<?php require 'connection.php'; 

// Set UTF-8 character encoding and timezone
date_default_timezone_set('Asia/Calcutta');

$charset = 'utf8mb4';
// Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
// PDO options for better security & error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throws exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetches data as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
    // echo "Connection successful";
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'message' => 'PDO Database connection failed.',
        'error' => $e->getMessage()
    ]);

    exit;
}



?>
