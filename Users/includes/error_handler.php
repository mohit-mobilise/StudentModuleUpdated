<?php
/**
 * Error Handler
 * Provides secure error handling to prevent information disclosure
 */

/**
 * Set up error handling
 */
function setup_error_handling() {
    // Check if we're in development mode (no .env file = development)
    $is_development = !file_exists(__DIR__ . '/../.env');
    
    // Log all errors
    ini_set('log_errors', 1);
    $log_dir = __DIR__ . '/../logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0755, true);
    }
    ini_set('error_log', $log_dir . '/error.log');
    
    // In development, show errors for debugging
    if ($is_development) {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        // Don't override error handlers in development
        return;
    }
    
    // In production, hide errors
    ini_set('display_errors', 0);
    error_reporting(E_ALL);
    
    // Set custom error handler
    set_error_handler('secure_error_handler');
    set_exception_handler('secure_exception_handler');
}

/**
 * Secure error handler
 */
function secure_error_handler($errno, $errstr, $errfile, $errline) {
    // Log the error with full details
    error_log("Error [$errno]: $errstr in $errfile on line $errline");
    
    // Don't display error details to users
    if (ini_get('display_errors')) {
        echo "An error occurred. Please try again later.";
    }
    
    return true; // Prevent default error handler
}

/**
 * Secure exception handler
 */
function secure_exception_handler($exception) {
    // Log the exception with full details
    error_log("Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    error_log("Stack trace: " . $exception->getTraceAsString());
    
    // Check if we're in development mode
    $is_development = !file_exists(__DIR__ . '/../.env');
    
    // In development, show the actual error
    if ($is_development) {
        echo "<h2>Error Details (Development Mode)</h2>";
        echo "<pre>" . htmlspecialchars($exception->getMessage()) . "\n\n";
        echo "File: " . htmlspecialchars($exception->getFile()) . "\n";
        echo "Line: " . htmlspecialchars($exception->getLine()) . "\n\n";
        echo "Stack Trace:\n" . htmlspecialchars($exception->getTraceAsString()) . "</pre>";
    } else {
        // In production, show generic message
        echo "An error occurred. Please try again later.";
    }
}

/**
 * Secure database error handler
 */
function secure_db_error($connection, $message = 'Database error') {
    $error = mysqli_error($connection);
    error_log("Database error: $error - $message");
    return "An error occurred while processing your request.";
}

// Initialize error handling (only if not already disabled via environment)
$enable_error_handler = $_ENV['ENABLE_ERROR_HANDLER'] ?? getenv('ENABLE_ERROR_HANDLER') ?? 'true';
if (strtolower($enable_error_handler) === 'true' || $enable_error_handler === '1') {
    setup_error_handling();
}

?>

