<?php
/**
 * Environment Variables Loader
 * Loads variables from .env file
 */
function load_env($env_file = '.env') {
    if (!file_exists($env_file)) {
        // .env file doesn't exist - this is okay, we'll use defaults
        // Log a warning but don't fail
        error_log("Warning: .env file not found at $env_file. Using default values. Please create .env file from .env.example for production.");
        return false;
    }
    
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if (preg_match('/^["\'](.*)["\']$/', $value, $matches)) {
                $value = $matches[1];
            }
            
            // Set environment variable if not already set
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
    
    return true;
}

// Load .env file - try multiple paths
// Since env_loader.php is in Users/includes/, we need to go up 2 levels to reach root
$env_paths = [
    __DIR__ . '/../../.env',  // From Users/includes/ to root
    dirname(dirname(__DIR__)) . '/.env',  // Alternative path to root
    __DIR__ . '/../.env',  // Fallback: Users/.env (if exists)
    '.env'  // Current working directory
];

$env_loaded = false;
foreach ($env_paths as $env_path) {
    if (file_exists($env_path)) {
        $env_loaded = load_env($env_path);
        if ($env_loaded) {
            break;
        }
    }
}

if (!$env_loaded) {
    // Log warning but don't fail - will use defaults
    error_log("Warning: .env file not found. Tried: " . implode(', ', $env_paths) . ". Using default values.");
}

