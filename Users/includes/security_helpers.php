<?php
/**
 * Security Helper Functions
 * Provides secure functions for common security operations
 */

/**
 * Safe output function - Prevents XSS attacks
 * @param string $value The value to output
 * @param bool $double_encode Whether to double encode existing entities
 * @return string Escaped string safe for HTML output
 */
function safe_output($value, $double_encode = true) {
    if ($value === null || $value === '') {
        return '';
    }
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8', $double_encode);
}

/**
 * Safe output for HTML attributes
 * @param string $value The value for HTML attribute
 * @return string Escaped string safe for HTML attributes
 */
function safe_attr($value) {
    return safe_output($value);
}

/**
 * Safe output for JavaScript contexts
 * @param string $value The value for JavaScript
 * @return string Escaped string safe for JavaScript
 */
function safe_js($value) {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
}

/**
 * Validate and sanitize input
 * @param mixed $input The input to validate
 * @param string $type The type of validation (string, int, email, etc.)
 * @param int $max_length Maximum allowed length
 * @return mixed Sanitized input or false on failure
 */
function validate_input($input, $type = 'string', $max_length = 255) {
    if ($input === null || $input === '') {
        return '';
    }
    
    // Trim whitespace
    $input = trim($input);
    
    // Check length
    if (strlen($input) > $max_length) {
        return false;
    }
    
    switch ($type) {
        case 'string':
            return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        case 'int':
            return filter_var($input, FILTER_VALIDATE_INT);
        case 'email':
            return filter_var($input, FILTER_VALIDATE_EMAIL);
        case 'url':
            return filter_var($input, FILTER_VALIDATE_URL);
        case 'alphanumeric':
            return preg_match('/^[a-zA-Z0-9]+$/', $input) ? $input : false;
        default:
            return filter_var($input, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
}

/**
 * Generate CSRF token
 * @return string CSRF token
 */
function generate_csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * @param string $token The token to validate
 * @return bool True if token is valid
 */
function validate_csrf_token($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Secure session configuration
 */
function configure_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session cookie parameters
        session_set_cookie_params([
            'lifetime' => 3600, // 1 hour
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // Use HTTPS if available
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        session_start();
    }
}

/**
 * Regenerate session ID (call after login)
 */
function regenerate_session_id() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 * @param string $password Plain text password
 * @param string $hash Hashed password from database
 * @return bool True if password matches
 */
function verify_password($password, $hash) {
    // Handle both hashed and plain text passwords (for migration)
    if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
        // If it's a plain text password (old system), verify by comparison
        // This allows gradual migration
        if (strlen($hash) < 60) { // Hashed passwords are always 60+ characters
            return hash_equals($hash, $password);
        }
    }
    return password_verify($password, $hash);
}

/**
 * Generate secure random token
 * @param int $length Token length in bytes (default 32)
 * @return string Random token
 */
function generate_token($length = 32) {
    return bin2hex(random_bytes($length));
}

/**
 * Validate file upload
 * @param array $file $_FILES array element
 * @param array $allowed_types Allowed MIME types
 * @param int $max_size Maximum file size in bytes
 * @return array ['valid' => bool, 'error' => string]
 */
function validate_file_upload($file, $allowed_types = ['image/jpeg', 'image/png', 'image/gif'], $max_size = 5242880) {
    $result = ['valid' => false, 'error' => ''];
    
    if (!isset($file['error']) || is_array($file['error'])) {
        $result['error'] = 'Invalid file upload parameters';
        return $result;
    }
    
    // Check for upload errors
    switch ($file['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            $result['error'] = 'No file uploaded';
            return $result;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            $result['error'] = 'File size exceeds limit';
            return $result;
        default:
            $result['error'] = 'Unknown upload error';
            return $result;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $result['error'] = 'File size exceeds maximum allowed size';
        return $result;
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        $result['error'] = 'File type not allowed';
        return $result;
    }
    
    // Additional validation for images
    if (strpos($mime_type, 'image/') === 0) {
        $image_info = getimagesize($file['tmp_name']);
        if ($image_info === false) {
            $result['error'] = 'Invalid image file';
            return $result;
        }
    }
    
    $result['valid'] = true;
    return $result;
}

/**
 * Generate secure filename
 * @param string $original_filename Original filename
 * @param string $prefix Optional prefix
 * @return string Secure filename
 */
function generate_secure_filename($original_filename, $prefix = '') {
    $extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    $random_name = bin2hex(random_bytes(16));
    $filename = $prefix . $random_name . '.' . $extension;
    return $filename;
}

/**
 * Secure file upload handler
 * @param array $file $_FILES array element
 * @param string $upload_directory Target upload directory (relative to Users/)
 * @param string $prefix Optional filename prefix
 * @param array $allowed_extensions Optional allowed extensions (default: images)
 * @param int $max_size Maximum file size in bytes (default 5MB)
 * @return array ['success' => bool, 'filename' => string, 'error' => string]
 */
function secure_file_upload($file, $upload_directory, $prefix = '', $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'], $max_size = 5242880) {
    $result = ['success' => false, 'filename' => '', 'error' => ''];
    
    // Validate file upload
    if (!isset($file) || !isset($file['error'])) {
        $result['error'] = 'No file uploaded';
        return $result;
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        switch ($file['error']) {
            case UPLOAD_ERR_NO_FILE:
                $result['error'] = 'No file uploaded';
                break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $result['error'] = 'File size exceeds limit';
                break;
            default:
                $result['error'] = 'Upload error occurred';
                break;
        }
        return $result;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $result['error'] = 'File size exceeds maximum allowed size (' . ($max_size / 1048576) . 'MB)';
        return $result;
    }
    
    // Get file extension
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    // Validate file extension
    if (!in_array($file_extension, $allowed_extensions)) {
        $result['error'] = 'File type not allowed. Allowed types: ' . implode(', ', $allowed_extensions);
        return $result;
    }
    
    // Validate MIME type for images
    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($mime_type, $allowed_mime_types)) {
            $result['error'] = 'Invalid image file';
            return $result;
        }
        
        // Additional validation for images
        $image_info = @getimagesize($file['tmp_name']);
        if ($image_info === false) {
            $result['error'] = 'Invalid image file';
            return $result;
        }
    }
    
    // Generate secure filename
    $secure_filename = $prefix . bin2hex(random_bytes(16)) . '.' . $file_extension;
    
    // Validate and create upload directory
    $upload_path = rtrim($upload_directory, '/') . '/';
    if (!file_exists($upload_path)) {
        if (!mkdir($upload_path, 0755, true)) {
            $result['error'] = 'Failed to create upload directory';
            return $result;
        }
    }
    
    // Full target path
    $target_file = $upload_path . $secure_filename;
    
    // Validate path doesn't contain directory traversal
    $real_target_path = realpath(dirname($target_file));
    $real_upload_path = realpath($upload_path);
    
    if ($real_target_path === false || $real_upload_path === false || strpos($real_target_path, $real_upload_path) !== 0) {
        $result['error'] = 'Invalid upload path';
        return $result;
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        $result['success'] = true;
        $result['filename'] = $secure_filename;
    } else {
        $result['error'] = 'Failed to upload file';
    }
    
    return $result;
}

?>


