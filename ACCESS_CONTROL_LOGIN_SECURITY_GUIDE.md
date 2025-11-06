# Security and Access Control Audit Guide
## Student Portal Application

**Generated:** November 6, 2025  
**Focus:** Authentication, Authorization, Session Management, Login Security

---

## Table of Contents

1. [Security and Access Control](#1-security-and-access-control)
2. [Login Screen Security](#2-login-screen-security)
3. [Role-Based Access Control (RBAC)](#3-role-based-access-control)
4. [Session Management](#4-session-management)
5. [Password Security](#5-password-security)
6. [Two-Factor Authentication (2FA)](#6-two-factor-authentication-2fa)
7. [Automated Security Testing](#7-automated-security-testing)

---

## 1. Security and Access Control

### 1.1 Authentication Mechanisms

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Login Process:**
   - Try login with valid credentials
   - Try login with invalid credentials
   - Check if error messages are generic (don't reveal if user exists)

2. **Test Session Handling:**
   - Login successfully
   - Check if session is created
   - Verify session data stored securely

3. **Test Logout:**
   - Click logout
   - Verify session is destroyed
   - Try accessing protected pages after logout

**Automated Testing:**

```bash
# Check session configuration
php -r "echo 'Session settings:\n'; 
echo 'session.cookie_httponly: ' . ini_get('session.cookie_httponly') . '\n';
echo 'session.cookie_secure: ' . ini_get('session.cookie_secure') . '\n';
echo 'session.use_strict_mode: ' . ini_get('session.use_strict_mode') . '\n';
echo 'session.cookie_samesite: ' . ini_get('session.cookie_samesite') . '\n';"
```

#### 🧰 Recommended Tools

1. **Burp Suite**
   - Intercept login requests
   - Check session tokens
   - Test session fixation

2. **OWASP ZAP**
   - Automated session testing
   - Spider + Active Scan

3. **Cookie Editor Browser Extension**
   - Inspect session cookies
   - Test cookie flags

#### ⚠️ Common Pitfalls

1. **Revealing User Existence:** Error: "Password incorrect" vs "User not found"
2. **Insecure Session Cookies:** Missing HttpOnly, Secure flags
3. **Session Fixation:** Not regenerating session ID after login
4. **Predictable Session IDs:** Using sequential or weak session tokens

#### 🩻 Current Implementation Analysis

**Your Login.php - GOOD Features:**

```php
// Line 15-22: Secure session configuration
configure_secure_session();

// Line 132: Session ID regeneration after login
regenerate_session_id();

// Line 85-89: Prepared statements prevent SQL injection
$stmt = mysqli_prepare($Con, "SELECT ... FROM `student_master` WHERE `sadmission`=?");
```

**From security_helpers.php - Session Configuration:**

```php
// Lines 103-117: Excellent session security
function configure_secure_session() {
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 3600, // 1 hour
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        session_start();
    }
}
```

✅ **Strong Points:**
- HttpOnly flag prevents XSS access to cookies
- SameSite=Strict prevents CSRF
- Secure flag when HTTPS is available
- 1-hour session timeout

⚠️ **Improvements Needed:**

**1. Add Session Validation:**

```php
<?php
// session_validator.php - Include in all protected pages

function validate_session() {
    // Check if session exists
    if (empty($_SESSION['userid'])) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity'])) {
        $inactive_time = time() - $_SESSION['last_activity'];
        if ($inactive_time > 3600) { // 1 hour
            session_destroy();
            return false;
        }
    }
    $_SESSION['last_activity'] = time();
    
    // Check IP address (optional, can cause issues with mobile networks)
    // if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
    //     return false;
    // }
    
    // Check user agent
    if (isset($_SESSION['user_agent'])) {
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            log_security_event('SESSION_HIJACK_ATTEMPT', [
                'user' => $_SESSION['userid'],
                'stored_ua' => $_SESSION['user_agent'],
                'current_ua' => $_SERVER['HTTP_USER_AGENT']
            ], 'CRITICAL');
            session_destroy();
            return false;
        }
    }
    
    return true;
}

// Call at the top of protected pages
if (!validate_session()) {
    header('Location: Login.php');
    exit;
}
?>
```

**2. Store Session Metadata on Login:**

Update Login.php after successful authentication:

```php
// After line 124, add:
$_SESSION['userid'] = $suser;
$_SESSION['StudentName'] = $StudentName;
$_SESSION['StudentClass'] = $StudentClass;
$_SESSION['StudentRollNo'] = $StudentRollNo;
$_SESSION['StudentFatherName'] = $StudentFatherName;
$_SESSION['erp_status'] = $erp_status;

// ADD THESE:
$_SESSION['login_time'] = time();
$_SESSION['last_activity'] = time();
$_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

// Log successful login
if (function_exists('log_security_event')) {
    log_security_event('LOGIN_SUCCESS', [
        'user' => $suser,
        'ip' => $_SERVER['REMOTE_ADDR']
    ], 'INFO');
}
```

**3. Create Logout Script:**

```php
<?php
// logout.php
session_start();

// Log logout event
if (!empty($_SESSION['userid'])) {
    require_once 'includes/security_logger.php';
    log_security_event('LOGOUT', [
        'user' => $_SESSION['userid']
    ], 'INFO');
}

// Destroy session
$_SESSION = array();

// Delete session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy session
session_destroy();

// Redirect to login
header('Location: Login.php');
exit;
?>
```

---

### 1.2 Authorization and Access Control

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Vertical Privilege Escalation:**
   - Login as student
   - Try accessing admin pages directly via URL
   - Example: Try accessing `/Admin/StudentManagement/`

2. **Test Horizontal Privilege Escalation:**
   - Login as Student A
   - Try viewing Student B's data by changing URL parameters
   - Example: Change `student_id=TEST001` to `student_id=TEST002`

3. **Test Direct Object References:**
   - Access a document: `StudentDocuments/123.pdf`
   - Try changing ID: `StudentDocuments/124.pdf`
   - Verify you can only access your own documents

**Automated Testing:**

```bash
# Test access control with curl
# Login and save session cookie
curl -c cookies.txt -d "txtUserId=TEST001&txtPassword=test123&isSubmit=yes" \
  http://localhost/cursorai/Testing/studentportal/Users/Login.php

# Try accessing admin page with student session
curl -b cookies.txt \
  http://localhost/cursorai/Testing/studentportal/Admin/index.php

# Should be denied - check response
```

#### 🧰 Recommended Tools

1. **Burp Suite - Autorize Extension**
   - Automatically tests authorization
   - Detects horizontal/vertical privilege escalation

2. **OWASP ZAP - Access Control Testing**
   - Configure users with different roles
   - Scan for access control issues

#### ⚠️ Common Pitfalls

1. **Client-Side Access Control:** Hiding elements in UI but not checking server-side
2. **Predictable IDs:** Sequential document IDs anyone can guess
3. **Missing Authorization Checks:** Checking authentication but not authorization

#### 🩻 Implementation Guide

**1. Create Authorization Helper:**

```php
<?php
// Users/includes/authorization.php

/**
 * Check if user has access to resource
 * @param string $resource Resource identifier
 * @param string $action Action being performed (view, edit, delete)
 * @return bool
 */
function check_access($resource, $action = 'view') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check authentication first
    if (empty($_SESSION['userid'])) {
        return false;
    }
    
    $user_id = $_SESSION['userid'];
    $user_role = $_SESSION['role'] ?? 'student';
    
    // Define access control rules
    $acl = [
        'student' => [
            'own_profile' => ['view', 'edit'],
            'own_documents' => ['view', 'download'],
            'own_fees' => ['view'],
            'notices' => ['view'],
            'assignments' => ['view', 'submit']
        ],
        'admin' => [
            '*' => ['*'] // Admin has all permissions
        ]
    ];
    
    // Check if role has access
    if (isset($acl[$user_role])) {
        // Check wildcard permission
        if (isset($acl[$user_role]['*']) && in_array('*', $acl[$user_role]['*'])) {
            return true;
        }
        
        // Check specific resource
        if (isset($acl[$user_role][$resource])) {
            if (in_array('*', $acl[$user_role][$resource]) || 
                in_array($action, $acl[$user_role][$resource])) {
                return true;
            }
        }
    }
    
    // Log unauthorized access attempt
    log_security_event('UNAUTHORIZED_ACCESS', [
        'user' => $user_id,
        'role' => $user_role,
        'resource' => $resource,
        'action' => $action
    ], 'WARNING');
    
    return false;
}

/**
 * Check if user owns resource
 * @param string $resource_owner Resource owner ID
 * @return bool
 */
function check_ownership($resource_owner) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['userid']) && $_SESSION['userid'] === $resource_owner;
}

/**
 * Require access or die
 * @param string $resource
 * @param string $action
 */
function require_access($resource, $action = 'view') {
    if (!check_access($resource, $action)) {
        http_response_code(403);
        die('<!DOCTYPE html><html><head><title>Access Denied</title></head><body>
            <h1>403 - Access Denied</h1>
            <p>You do not have permission to access this resource.</p>
            <a href="landing.php">Return to Dashboard</a>
            </body></html>');
    }
}
?>
```

**2. Protect Document Downloads:**

```php
<?php
// download_document.php
require_once '../connection.php';
require_once 'includes/authorization.php';
require_once 'includes/security_helpers.php';

configure_secure_session();

// Check authentication
if (empty($_SESSION['userid'])) {
    http_response_code(401);
    die('Unauthorized');
}

$user_id = $_SESSION['userid'];
$document_id = validate_input($_GET['id'] ?? '', 'int');

if (!$document_id) {
    http_response_code(400);
    die('Invalid document ID');
}

// Get document info
$stmt = mysqli_prepare($Con, "
    SELECT file_path, file_name, student_id, file_type 
    FROM student_documents 
    WHERE id = ?
");
mysqli_stmt_bind_param($stmt, "i", $document_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    // Check if user owns this document
    if ($row['student_id'] !== $user_id) {
        // Not the owner - check if admin
        if (!check_access('all_documents', 'view')) {
            log_security_event('UNAUTHORIZED_DOCUMENT_ACCESS', [
                'user' => $user_id,
                'document_id' => $document_id,
                'owner' => $row['student_id']
            ], 'WARNING');
            
            http_response_code(403);
            die('Access denied');
        }
    }
    
    // User has access - serve file
    $file_path = __DIR__ . '/' . $row['file_path'];
    
    if (!file_exists($file_path)) {
        http_response_code(404);
        die('File not found');
    }
    
    // Log download
    log_security_event('DOCUMENT_DOWNLOAD', [
        'user' => $user_id,
        'document_id' => $document_id,
        'file' => $row['file_name']
    ], 'INFO');
    
    // Serve file securely
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($row['file_name']) . '"');
    header('Content-Length: ' . filesize($file_path));
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    readfile($file_path);
    exit;
    
} else {
    http_response_code(404);
    die('Document not found');
}
?>
```

**3. Protect Pages with Authorization:**

```php
<?php
// At the top of protected pages (e.g., studentprofile.php)
require_once 'includes/security_helpers.php';
require_once 'includes/authorization.php';

configure_secure_session();

// Check authentication
if (!validate_session()) {
    header('Location: Login.php');
    exit;
}

// Check authorization
require_access('own_profile', 'view');

// Rest of page code...
?>
```

**4. Protect Data Endpoints:**

```php
<?php
// fetch_student_data.php
require_once '../connection.php';
require_once 'includes/security_helpers.php';
require_once 'includes/authorization.php';

configure_secure_session();
header('Content-Type: application/json');

// Check authentication
if (!validate_session()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['userid'];
$requested_student_id = validate_input($_GET['student_id'] ?? '', 'string', 50);

// Check if user is requesting their own data
if ($requested_student_id !== $user_id) {
    // Not own data - check if admin
    if (!check_access('all_students', 'view')) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied']);
        exit;
    }
}

// User has access - fetch data
$stmt = mysqli_prepare($Con, "
    SELECT sname, sclass, srollno, sfathername 
    FROM student_master 
    WHERE sadmission = ?
");
mysqli_stmt_bind_param($stmt, "s", $requested_student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode($row);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Student not found']);
}
?>
```

---

## 2. Login Screen Security

### 2.1 HTTPS Enforcement

#### ✅ Verification Steps

**Manual Testing:**
1. **Check HTTPS Availability:**
   - Access via HTTPS: `https://yoursite.com`
   - Verify SSL certificate is valid

2. **Check HTTP to HTTPS Redirect:**
   - Access via HTTP: `http://yoursite.com`
   - Should redirect to HTTPS

**Automated Testing:**

```bash
# Check SSL certificate
openssl s_client -connect localhost:443 -servername localhost

# Check if HTTP redirects to HTTPS
curl -I http://localhost/cursorai/Testing/studentportal/Users/Login.php
# Look for Location: https:// in response headers
```

#### 🧰 Recommended Tools

1. **SSL Labs SSL Test**
   - URL: https://www.ssllabs.com/ssltest/
   - Comprehensive SSL/TLS testing
   - Only works for publicly accessible sites

2. **testssl.sh**
   ```bash
   # Clone repository
   git clone https://github.com/drwetter/testssl.sh.git
   
   # Test your site
   cd testssl.sh
   ./testssl.sh https://yoursite.com
   ```

3. **Let's Encrypt (Free SSL)**
   - URL: https://letsencrypt.org/
   - Free SSL certificates

#### ⚠️ Common Pitfalls

1. **Mixed Content:** Loading HTTP resources on HTTPS page
2. **Weak Ciphers:** Using outdated encryption algorithms
3. **HTTP Accessible:** Login page accessible via HTTP

#### 🩻 Implementation Guide

**1. For Production - Add to .htaccess:**

```apache
# .htaccess - Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Additional security headers
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains" env=HTTPS
Header always set X-Frame-Options "DENY"
Header always set X-Content-Type-Options "nosniff"
Header always set X-XSS-Protection "1; mode=block"
```

**2. For XAMPP Local Development (SSL Setup):**

```bash
# 1. Open XAMPP Control Panel
# 2. Click Apache Config → httpd-ssl.conf
# 3. Find your VirtualHost configuration and update:

<VirtualHost _default_:443>
    DocumentRoot "C:/xampp/htdocs/cursorai/Testing/studentportal"
    ServerName localhost
    SSLEngine on
    SSLCertificateFile "conf/ssl.crt/server.crt"
    SSLCertificateKeyFile "conf/ssl.key/server.key"
</VirtualHost>

# 4. Restart Apache
# 5. Access via https://localhost/cursorai/Testing/studentportal/
```

**3. PHP HTTPS Enforcement:**

Add to top of Login.php:

```php
<?php
// Force HTTPS in production
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    $is_https = true;
} else {
    $is_https = false;
}

// Get environment (production should use HTTPS)
$is_production = $_ENV['APP_ENVIRONMENT'] ?? getenv('APP_ENVIRONMENT') ?? 'development';

if ($is_production === 'production' && !$is_https) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url", true, 301);
    exit;
}
?>
```

---

### 2.2 CSRF Protection

#### ✅ Verification Steps

**Manual Testing:**
1. **Check CSRF Token Presence:**
   - View page source of login form
   - Look for hidden CSRF token field

2. **Test CSRF Protection:**
   - Submit form without token
   - Should be rejected

3. **Test Token Validation:**
   - Modify token value
   - Submit form - should be rejected

**Automated Testing:**

```bash
# Try submitting without CSRF token
curl -X POST http://localhost/cursorai/Testing/studentportal/Users/Login.php \
  -d "txtUserId=TEST001&txtPassword=test&isSubmit=yes"
# Should fail

# Try with invalid token
curl -X POST http://localhost/cursorai/Testing/studentportal/Users/Login.php \
  -d "txtUserId=TEST001&txtPassword=test&isSubmit=yes&csrf_token=invalid"
# Should fail
```

#### 🧰 Recommended Tools

1. **Burp Suite - CSRF PoC Generator**
2. **OWASP ZAP - CSRF Token Scanner**

#### ⚠️ Common Pitfalls

1. **Missing CSRF Tokens:** Forms without tokens
2. **Not Validating Tokens:** Token present but not checked
3. **Weak Token Generation:** Predictable tokens

#### 🩻 Current Implementation Analysis

**Your Login.php - GOOD:**

```php
// Line 59-80: CSRF validation
if (!empty($_REQUEST["isSubmit"]) && $_REQUEST["isSubmit"] == "yes") {
    if (!validate_csrf_token($_REQUEST['csrf_token'] ?? '')) {
        echo '<script>toastr.error("Invalid security token. Please try again.", "Security Error");</script>';
        $suser = ''; // Prevent login attempt
    }
}

// Line 344: CSRF token in form
<input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
```

✅ **Excellent Implementation!**

**Extend to ALL Forms:**

```php
<?php
// Example: Forget Password Form (line 394+)
?>
<form action="submit_forget_password_users.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    
    <!-- Rest of form -->
</form>

<?php
// submit_forget_password_users.php
require_once 'includes/security_helpers.php';

// Validate CSRF token
if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid security token']);
    exit;
}

// Process form...
?>
```

---

### 2.3 Rate Limiting / Brute Force Protection

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Multiple Failed Logins:**
   - Try 10 failed login attempts
   - Check if account is locked or rate limited

2. **Test IP-Based Limiting:**
   - Multiple failures from same IP
   - Should be blocked

**Automated Testing:**

```bash
# Brute force test script
for i in {1..10}; do
    echo "Attempt $i"
    curl -X POST http://localhost/cursorai/Testing/studentportal/Users/Login.php \
      -d "txtUserId=TEST001&txtPassword=wrong$i&isSubmit=yes"
    sleep 1
done
```

#### 🧰 Recommended Tools

1. **Hydra - Password Brute Forcing**
   ```bash
   hydra -l TEST001 -P passwords.txt localhost http-post-form \
     "/cursorai/Testing/studentportal/Users/Login.php:txtUserId=^USER^&txtPassword=^PASS^:Password does not match"
   ```

2. **fail2ban - Server-Level Protection**
   - Monitors log files
   - Bans IPs with too many failures

#### ⚠️ Common Pitfalls

1. **No Rate Limiting:** Unlimited login attempts
2. **User-Based Only:** Not checking IP address
3. **Easy Bypass:** Can bypass by changing IP

#### 🩻 Implementation (Already Provided Earlier)

See the rate limiting implementation in Section A07 of SECURITY_AUDIT_GUIDE.md

**Key Functions:**
- `check_rate_limit()` - Check if user/IP is rate limited
- `record_failed_attempt()` - Log failed attempts
- `clear_login_attempts()` - Clear on successful login

**Create the login_attempts table:**

```sql
CREATE TABLE IF NOT EXISTS `login_attempts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR(50) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` VARCHAR(255),
    `attempt_time` INT NOT NULL,
    INDEX `user_id` (`user_id`),
    INDEX `ip_address` (`ip_address`),
    INDEX `attempt_time` (`attempt_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### 2.4 Input Validation

#### ✅ Verification Steps

**Manual Testing:**
1. **Test SQL Injection:**
   - Username: `admin' OR '1'='1`
   - Username: `' UNION SELECT NULL--`

2. **Test XSS:**
   - Username: `<script>alert('XSS')</script>`
   - Username: `"><img src=x onerror=alert(1)>`

3. **Test Special Characters:**
   - Username: `test@#$%^&*()`

**Automated Testing:**

```bash
# SQL Injection test
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  --batch --risk=3 --level=5
```

#### 🧰 Recommended Tools

1. **sqlmap** - SQL injection testing
2. **XSSer** - XSS testing
3. **Burp Suite Intruder** - Payload fuzzing

#### ⚠️ Common Pitfalls

1. **Client-Side Only Validation:** JavaScript validation can be bypassed
2. **Insufficient Sanitization:** Not removing dangerous characters
3. **Incorrect Escaping:** Using wrong escaping for context

#### 🩻 Current Implementation Analysis

**Your Login.php - GOOD:**

```php
// Line 56-57: Input validation
$suser = validate_input($_REQUEST["txtUserId"] ?? '', 'string', 50);
$spassword = $_REQUEST["txtPassword"] ?? '';

// Line 85-89: Prepared statements
$stmt = mysqli_prepare($Con, "SELECT ... FROM `student_master` WHERE `sadmission`=?");
mysqli_stmt_bind_param($stmt, "s", $suser);
```

✅ **Excellent!** Uses both validation and prepared statements.

**Enhancement - Add More Validation:**

```php
<?php
// Enhance validate_input in security_helpers.php

function validate_login_input($value, $type = 'username') {
    if (empty($value)) {
        return ['valid' => false, 'error' => ucfirst($type) . ' is required'];
    }
    
    $value = trim($value);
    
    switch ($type) {
        case 'username':
            // Username: alphanumeric, dash, underscore only
            if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
                return ['valid' => false, 'error' => 'Username contains invalid characters'];
            }
            if (strlen($value) < 3 || strlen($value) > 50) {
                return ['valid' => false, 'error' => 'Username must be 3-50 characters'];
            }
            break;
            
        case 'password':
            if (strlen($value) < 6) {
                return ['valid' => false, 'error' => 'Password too short'];
            }
            if (strlen($value) > 128) {
                return ['valid' => false, 'error' => 'Password too long'];
            }
            // Check for dangerous patterns
            if (preg_match('/<script|javascript:|onerror=/i', $value)) {
                return ['valid' => false, 'error' => 'Invalid password format'];
            }
            break;
    }
    
    return ['valid' => true, 'value' => $value];
}

// Usage in Login.php:
$username_check = validate_login_input($_REQUEST["txtUserId"] ?? '', 'username');
if (!$username_check['valid']) {
    echo '<script>toastr.error("' . $username_check['error'] . '", "Validation Error");</script>';
    exit;
}

$password_check = validate_login_input($_REQUEST["txtPassword"] ?? '', 'password');
if (!$password_check['valid']) {
    echo '<script>toastr.error("' . $password_check['error'] . '", "Validation Error");</script>';
    exit;
}

$suser = $username_check['value'];
$spassword = $password_check['value'];
?>
```

---

### 2.5 Secure Password Policies

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Weak Passwords:**
   - Try setting password: "123456"
   - Try setting password: "password"
   - Should be rejected

2. **Test Password Complexity:**
   - Try password without numbers
   - Try password without special characters

**Automated Testing:**

Check password storage in database:

```sql
-- Check if passwords are hashed
SELECT 
    sadmission,
    LEFT(spassword, 10) as pwd_sample,
    LENGTH(spassword) as pwd_length
FROM student_master 
LIMIT 5;

-- Hashed passwords should be 60+ characters and start with $2y$
```

#### 🧰 Recommended Tools

1. **Have I Been Pwned API**
   - Check if password appears in data breaches
   - API: https://haveibeenpwned.com/API/v3

2. **zxcvbn Password Strength Estimator**
   - JavaScript library for password strength

#### ⚠️ Common Pitfalls

1. **Plain Text Storage:** Passwords not hashed
2. **Weak Hashing:** Using MD5 or SHA1
3. **No Complexity Requirements:** Allowing "123456"
4. **Password Hints:** Storing security questions with weak answers

#### 🩻 Implementation Guide

**1. Client-Side Password Validation:**

```javascript
// password_validator.js
function validatePassword(password) {
    const errors = [];
    
    // Minimum length
    if (password.length < 8) {
        errors.push('Password must be at least 8 characters');
    }
    
    // Maximum length
    if (password.length > 128) {
        errors.push('Password must not exceed 128 characters');
    }
    
    // Require uppercase
    if (!/[A-Z]/.test(password)) {
        errors.push('Password must contain at least one uppercase letter');
    }
    
    // Require lowercase
    if (!/[a-z]/.test(password)) {
        errors.push('Password must contain at least one lowercase letter');
    }
    
    // Require number
    if (!/[0-9]/.test(password)) {
        errors.push('Password must contain at least one number');
    }
    
    // Require special character
    if (!/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
        errors.push('Password must contain at least one special character');
    }
    
    // Check for common passwords
    const commonPasswords = [
        'password', '12345678', 'qwerty', 'abc123', 'password123',
        'admin', 'letmein', 'welcome', 'monkey', '1234567890'
    ];
    if (commonPasswords.includes(password.toLowerCase())) {
        errors.push('Password is too common');
    }
    
    return {
        valid: errors.length === 0,
        errors: errors
    };
}

// Add to password change form
document.getElementById('newPassword').addEventListener('blur', function() {
    const result = validatePassword(this.value);
    const errorDiv = document.getElementById('passwordErrors');
    
    if (!result.valid) {
        errorDiv.innerHTML = result.errors.map(e => `<div class="error">${e}</div>`).join('');
        this.classList.add('is-invalid');
    } else {
        errorDiv.innerHTML = '<div class="success">Password is strong</div>';
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    }
});
```

**2. Server-Side Password Validation:**

```php
<?php
// password_policy.php

function validate_password_policy($password) {
    $errors = [];
    
    // Length requirements
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters';
    }
    
    if (strlen($password) > 128) {
        $errors[] = 'Password must not exceed 128 characters';
    }
    
    // Complexity requirements
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        $errors[] = 'Password must contain at least one special character';
    }
    
    // Check common passwords
    $common_passwords = [
        'password', '12345678', 'qwerty', 'abc123', 'password123',
        'admin', 'letmein', 'welcome', 'monkey', '1234567890'
    ];
    
    if (in_array(strtolower($password), $common_passwords)) {
        $errors[] = 'Password is too common. Please choose a stronger password';
    }
    
    // Check for sequential characters
    if (preg_match('/(.)\1{2,}/', $password)) {
        $errors[] = 'Password cannot contain repeated characters';
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

// Usage:
$password = $_POST['new_password'];
$validation = validate_password_policy($password);

if (!$validation['valid']) {
    echo json_encode([
        'success' => false,
        'errors' => $validation['errors']
    ]);
    exit;
}

// Password is valid - hash and store
$hashed = hash_password($password);
// Store $hashed in database
?>
```

**3. Password Strength Indicator UI:**

```html
<!-- Add to password change form -->
<div class="form-group">
    <label for="newPassword">New Password</label>
    <input type="password" id="newPassword" name="new_password" class="form-control">
    
    <!-- Password strength indicator -->
    <div class="password-strength">
        <div class="strength-bar">
            <div id="strengthBar" class="strength-fill"></div>
        </div>
        <div id="strengthText" class="strength-text"></div>
    </div>
    
    <!-- Password requirements -->
    <ul class="password-requirements">
        <li id="req-length">✗ At least 8 characters</li>
        <li id="req-uppercase">✗ One uppercase letter</li>
        <li id="req-lowercase">✗ One lowercase letter</li>
        <li id="req-number">✗ One number</li>
        <li id="req-special">✗ One special character</li>
    </ul>
    
    <div id="passwordErrors" class="invalid-feedback"></div>
</div>

<style>
.password-strength {
    margin-top: 10px;
}

.strength-bar {
    height: 5px;
    background: #ddd;
    border-radius: 3px;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s;
}

.strength-fill.weak {
    width: 33%;
    background: #dc3545;
}

.strength-fill.medium {
    width: 66%;
    background: #ffc107;
}

.strength-fill.strong {
    width: 100%;
    background: #28a745;
}

.password-requirements {
    margin-top: 10px;
    font-size: 0.9em;
}

.password-requirements li {
    color: #dc3545;
}

.password-requirements li.valid {
    color: #28a745;
}

.password-requirements li.valid::before {
    content: '✓ ';
}

.password-requirements li::before {
    content: '✗ ';
}
</style>

<script>
document.getElementById('newPassword').addEventListener('input', function() {
    const password = this.value;
    let strength = 0;
    
    // Check each requirement
    const hasLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    // Update requirement checkmarks
    document.getElementById('req-length').classList.toggle('valid', hasLength);
    document.getElementById('req-uppercase').classList.toggle('valid', hasUpper);
    document.getElementById('req-lowercase').classList.toggle('valid', hasLower);
    document.getElementById('req-number').classList.toggle('valid', hasNumber);
    document.getElementById('req-special').classList.toggle('valid', hasSpecial);
    
    // Calculate strength
    if (hasLength) strength++;
    if (hasUpper) strength++;
    if (hasLower) strength++;
    if (hasNumber) strength++;
    if (hasSpecial) strength++;
    
    // Update strength bar
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    strengthBar.className = 'strength-fill';
    
    if (strength <= 2) {
        strengthBar.classList.add('weak');
        strengthText.textContent = 'Weak';
        strengthText.style.color = '#dc3545';
    } else if (strength <= 4) {
        strengthBar.classList.add('medium');
        strengthText.textContent = 'Medium';
        strengthText.style.color = '#ffc107';
    } else {
        strengthBar.classList.add('strong');
        strengthText.textContent = 'Strong';
        strengthText.style.color = '#28a745';
    }
});
</script>
```

---

### 2.6 Error Message Handling

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Login Errors:**
   - Invalid username - check error message
   - Invalid password - check error message
   - Should NOT reveal which is wrong

2. **Generic vs Specific:**
   - Error should be: "Invalid username or password"
   - NOT: "Username doesn't exist" or "Wrong password"

#### ⚠️ Common Pitfalls

1. **User Enumeration:** Revealing if username exists
2. **Detailed Errors:** Exposing system internals
3. **Stack Traces:** Showing debug information in production

#### 🩻 Current Implementation Analysis

**Your Login.php:**

```php
// Line 156 - User doesn't exist
echo '<script>toastr.error("User Does Not Exist ! Please Try Again", "Error");</script>';

// Line 138 - Password wrong
echo '<script>toastr.error("Password does not match ! Please Try Again", "Error");</script>';
```

⚠️ **SECURITY ISSUE:** This allows user enumeration!

**Fix - Use Generic Messages:**

```php
// BEFORE:
if ($num_rows > 0) {
    // ... password check
    if ($password_valid) {
        // Login successful
    } else {
        echo '<script>toastr.error("Password does not match ! Please Try Again", "Error");</script>';
    }
} else {
    echo '<script>toastr.error("User Does Not Exist ! Please Try Again", "Error");</script>';
}

// AFTER (SECURE):
if ($num_rows > 0) {
    // ... password check
    if ($password_valid) {
        // Login successful
    } else {
        // Generic error message
        echo '<script>toastr.error("Invalid username or password", "Login Failed");</script>';
        record_failed_attempt($suser, $Con);
    }
} else {
    // Same generic error message
    echo '<script>toastr.error("Invalid username or password", "Login Failed");</script>';
    record_failed_attempt($suser, $Con);
}
```

---

## 3. Role-Based Access Control (RBAC)

### Implementation Steps

**1. Add Roles to Database:**

```sql
-- Add role column to student_master
ALTER TABLE student_master 
ADD COLUMN role VARCHAR(20) DEFAULT 'student' AFTER erp_status;

-- Create roles table for more complex RBAC
CREATE TABLE IF NOT EXISTS `user_roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255),
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default roles
INSERT INTO user_roles (role_name, description) VALUES
('student', 'Regular student user'),
('teacher', 'Teacher/faculty member'),
('admin', 'System administrator'),
('parent', 'Parent/guardian');

-- Create permissions table
CREATE TABLE IF NOT EXISTS `role_permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `role_name` VARCHAR(50) NOT NULL,
    `resource` VARCHAR(100) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    FOREIGN KEY (role_name) REFERENCES user_roles(role_name),
    UNIQUE KEY `role_resource_action` (`role_name`, `resource`, `action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert permissions for students
INSERT INTO role_permissions (role_name, resource, action) VALUES
('student', 'own_profile', 'view'),
('student', 'own_profile', 'edit'),
('student', 'own_documents', 'view'),
('student', 'own_documents', 'download'),
('student', 'assignments', 'view'),
('student', 'assignments', 'submit'),
('student', 'notices', 'view'),
('student', 'fees', 'view');

-- Insert permissions for admin
INSERT INTO role_permissions (role_name, resource, action) VALUES
('admin', '*', '*');  -- Admin has all permissions
```

**2. Update Login to Set Role:**

```php
// In Login.php, after line 85
$stmt = mysqli_prepare($Con, "SELECT `suser`,`spassword`,`sname`,`sclass`,`srollno`,`sfathername`,`erp_status`,`role` FROM `student_master` WHERE `sadmission`=?");

// After password validation (around line 124)
$_SESSION['role'] = $row[7] ?? 'student';  // Get role from query result
```

---

## 4. Session Management Best Practices

### Complete Session Security Checklist

```php
<?php
// complete_session_config.php

// 1. Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Only if HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_only_cookies', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.gc_maxlifetime', 3600); // 1 hour

// 2. Start session with custom parameters
session_set_cookie_params([
    'lifetime' => 3600,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

// 3. Session validation
function validate_session() {
    // Check if session exists
    if (!isset($_SESSION['userid'])) {
        return false;
    }
    
    // Validate session data
    if (isset($_SESSION['user_agent'])) {
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_destroy();
            return false;
        }
    }
    
    // Check timeout
    if (isset($_SESSION['last_activity'])) {
        if ((time() - $_SESSION['last_activity']) > 3600) {
            session_destroy();
            return false;
        }
    }
    $_SESSION['last_activity'] = time();
    
    // Regenerate session ID periodically (every 30 minutes)
    if (!isset($_SESSION['last_regeneration'])) {
        $_SESSION['last_regeneration'] = time();
    } else if ((time() - $_SESSION['last_regeneration']) > 1800) {
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
    
    return true;
}
?>
```

---

## 7. Automated Security Testing

### Complete Test Suite

```bash
#!/bin/bash
# security_test_suite.sh

echo "=== Security Test Suite ==="
echo ""

echo "1. Testing for SQL Injection..."
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  --batch --level=5 --risk=3

echo ""
echo "2. Testing with OWASP ZAP..."
# Start ZAP in daemon mode
zap.sh -daemon -port 8090 &
sleep 10

# Spider the application
curl "http://localhost:8090/JSON/spider/action/scan/?url=http://localhost/cursorai/Testing/studentportal/"

# Active scan
curl "http://localhost:8090/JSON/ascan/action/scan/?url=http://localhost/cursorai/Testing/studentportal/"

# Get results
curl "http://localhost:8090/JSON/core/view/alerts/" > zap_results.json

echo ""
echo "3. SSL/TLS Testing..."
testssl.sh https://yoursite.com

echo ""
echo "4. Security Headers Check..."
curl -I https://yoursite.com | grep -E "X-Frame-Options|X-Content-Type-Options|Strict-Transport-Security"

echo ""
echo "=== Tests Complete ==="
echo "Check zap_results.json for detailed findings"
```

---

## Summary: Priority Security Fixes

### Critical (Fix Immediately):

1. ✅ **CSRF Protection** - Already implemented in Login.php
2. ✅ **SQL Injection Prevention** - Prepared statements in Login.php
3. ⚠️ **Rate Limiting** - Add login attempt tracking
4. ⚠️ **Generic Error Messages** - Fix user enumeration
5. ⚠️ **Password Reset** - Replace SMS with token-based system

### High Priority:

1. ⚠️ **Session Validation** - Add session hijacking protection
2. ⚠️ **Authorization Checks** - Implement on all protected pages
3. ⚠️ **HTTPS Enforcement** - Force SSL in production
4. ⚠️ **Security Logging** - Log all security events

### Medium Priority:

1. ⚠️ **Password Policy** - Enforce strong passwords
2. ⚠️ **2FA Implementation** - Add two-factor authentication
3. ⚠️ **Input Validation** - Enhance for all forms
4. ⚠️ **Security Headers** - Add comprehensive headers

### Done Well (Keep Maintaining):

1. ✅ Secure session configuration
2. ✅ CSRF token generation and validation
3. ✅ Prepared statements for database queries
4. ✅ Password hashing support
5. ✅ Security helper functions


