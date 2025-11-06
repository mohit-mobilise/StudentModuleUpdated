# Security & UI Compliance Audit - Quick Reference Checklist
## Student Portal Application

**Last Updated:** November 6, 2025  
**Application:** PHP-based Student Portal (XAMPP)

---

## 📋 Complete Audit Checklist

### Phase 1: Initial Assessment (2-3 hours)

#### [ ] 1.1 Environment Setup
- [ ] Install required testing tools (see AUTOMATED_SECURITY_TESTING_GUIDE.md)
- [ ] Configure test environment
- [ ] Create backup of database and files
- [ ] Document current application state

#### [ ] 1.2 Quick Security Scan
```powershell
# Run quick security test
.\quick_security_test.ps1

# Run database security check
# Browse to: http://localhost/cursorai/Testing/studentportal/db_security_check.php
```

**Expected Results:**
- Application accessible: ✅
- Security headers present: ✅
- CSRF protection active: ✅
- No SQL injection vulnerabilities: ✅

---

### Phase 2: OWASP Top 10 Compliance (4-6 hours)

#### [ ] 2.1 A01:2021 – Broken Access Control

**Current Status:**
- ✅ Session validation in landing.php
- ⚠️ Need to verify ALL protected pages have session checks

**Action Items:**
- [ ] Run access control test script
  ```powershell
  # Test authentication on all pages
  Get-ChildItem Users\*.php | ForEach-Object {
      Write-Host "Testing $($_.Name)..."
      $response = Invoke-WebRequest -Uri "http://localhost/cursorai/Testing/studentportal/Users/$($_.Name)"
      if ($response.Content -notmatch "login|session") {
          Write-Host "⚠ $($_.Name) may not require authentication" -ForegroundColor Red
      }
  }
  ```
- [ ] Implement `validate_session()` in all protected pages
- [ ] Add authorization checks for sensitive operations
- [ ] Test horizontal privilege escalation (accessing other students' data)

**Files to Fix:**
```php
// Add to EVERY protected page:
<?php
require_once 'includes/security_helpers.php';
require_once 'includes/authorization.php';

configure_secure_session();
if (!validate_session()) {
    header('Location: Login.php');
    exit;
}
?>
```

---

#### [ ] 2.2 A02:2021 – Cryptographic Failures

**Current Status:**
- ✅ Password hashing functions exist in security_helpers.php
- ⚠️ Some passwords may still be plain text

**Action Items:**
- [ ] Check password storage in database
  ```sql
  SELECT sadmission, LENGTH(spassword) as len, LEFT(spassword, 4) as prefix 
  FROM student_master LIMIT 20;
  -- Hashed passwords: len >= 60, prefix = '$2y$'
  ```
- [ ] Run password migration script if needed
- [ ] Configure HTTPS for production
- [ ] Replace password-via-SMS with token-based reset

**Priority Fix - Password Reset:**
```php
// Create: secure_password_reset.php (see ACCESS_CONTROL_LOGIN_SECURITY_GUIDE.md)
// Create: password_reset_tokens table
```

---

#### [ ] 2.3 A03:2021 – Injection

**Current Status:**
- ✅ Login.php uses prepared statements
- ⚠️ landing.php has vulnerable query (line 32)

**Action Items:**
- [ ] Run SQL injection scanner
  ```bash
  sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
    --data="txtUserId=test&txtPassword=test&isSubmit=yes" --batch
  ```
- [ ] Find all vulnerable queries:
  ```powershell
  Get-ChildItem Users\*.php -Recurse | Select-String "mysqli_query.*\$_" | 
    Where-Object { $_.Line -notmatch "mysqli_prepare|mysqli_real_escape_string" }
  ```
- [ ] Fix landing.php line 32:
  ```php
  // BEFORE:
  $classQuery = "SELECT `sclass` FROM `student_master` WHERE `sadmission` = '$StudentId'";
  
  // AFTER:
  $stmt = mysqli_prepare($Con, "SELECT `sclass` FROM `student_master` WHERE `sadmission` = ?");
  mysqli_stmt_bind_param($stmt, "s", $StudentId);
  mysqli_stmt_execute($stmt);
  $classResult = mysqli_stmt_get_result($stmt);
  ```
- [ ] Review all user input handling
- [ ] Implement input validation on all forms

---

#### [ ] 2.4 A04:2021 – Insecure Design

**Action Items:**
- [ ] Implement rate limiting on login
  - Create `login_attempts` table
  - Add rate limit check before authentication
- [ ] Add security logging
  - Create security_logger.php
  - Log all security events
- [ ] Review business logic for flaws

---

#### [ ] 2.5 A05:2021 – Security Misconfiguration

**Action Items:**
- [ ] Create/update .htaccess file
  ```apache
  Options -Indexes
  <FilesMatch "\.(env|log|ini|config)$">
      Order allow,deny
      Deny from all
  </FilesMatch>
  ```
- [ ] Disable error display in production
  ```php
  // In production .env:
  DISPLAY_ERRORS=false
  ERROR_REPORTING=0
  ```
- [ ] Remove phpinfo.php and test files
- [ ] Configure PHP settings securely

---

#### [ ] 2.6 A06:2021 – Vulnerable Components

**Action Items:**
- [ ] Check library versions
  ```bash
  retire --path=assets/
  ```
- [ ] Update jQuery if version < 3.5.0
- [ ] Review all third-party libraries
- [ ] Document all dependencies

**Current Libraries (from Login.php):**
- jQuery 3.5.1 ✅ (Good)
- Bootstrap 4.5.2 ✅ (Good, consider upgrading to 5.x)

---

#### [ ] 2.7 A07:2021 – Authentication Failures

**Current Status:**
- ✅ Session regeneration on login
- ✅ CSRF protection
- ⚠️ No brute force protection
- ⚠️ Error messages reveal user existence

**Action Items:**
- [ ] Implement rate limiting (see ACCESS_CONTROL_LOGIN_SECURITY_GUIDE.md)
- [ ] Fix error messages to be generic
  ```php
  // Change both "User Does Not Exist" and "Password does not match" to:
  echo '<script>toastr.error("Invalid username or password", "Login Failed");</script>';
  ```
- [ ] Add session validation with user agent check
- [ ] Implement password strength requirements
- [ ] Consider adding 2FA

---

#### [ ] 2.8 A08:2021 – Software/Data Integrity

**Action Items:**
- [ ] Search for unsafe deserialization
  ```powershell
  Get-ChildItem Users\*.php -Recurse | Select-String "unserialize|eval"
  ```
- [ ] Add SRI hashes to CDN resources
  ```html
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"
    integrity="sha384-ZvpUoO+..." crossorigin="anonymous"></script>
  ```

---

#### [ ] 2.9 A09:2021 – Logging/Monitoring Failures

**Action Items:**
- [ ] Create security_logger.php
- [ ] Add logging to:
  - [ ] Login attempts (success & failure)
  - [ ] Password changes
  - [ ] Profile updates
  - [ ] Document downloads
  - [ ] Unauthorized access attempts
- [ ] Review logs weekly
- [ ] Set up alerts for critical events

---

#### [ ] 2.10 A10:2021 – Server-Side Request Forgery

**Action Items:**
- [ ] Review any URL fetching code
  ```powershell
  Get-ChildItem Users\*.php -Recurse | Select-String "file_get_contents.*http|curl_exec"
  ```
- [ ] Implement URL validation if needed
- [ ] Whitelist allowed domains

---

### Phase 3: W3C/WCAG 2.1 Compliance (4-6 hours)

#### [ ] 3.1 Perceivable Content

**Images and Alt Text:**
- [ ] Run image accessibility checker
  ```bash
  pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php
  ```
- [ ] Fix Login.php images:
  ```html
  <!-- BEFORE -->
  <img src="assets/image/logo_new.svg" alt="school logo">
  
  <!-- AFTER -->
  <img src="assets/image/logo_new.svg" alt="Delhi Public School RK Puram - Student Portal">
  ```
- [ ] Add alt text to all social media icons
- [ ] Ensure decorative images have alt=""

**Color Contrast:**
- [ ] Run contrast checker
  ```bash
  lighthouse http://localhost/cursorai/Testing/studentportal/Users/Login.php --only-categories=accessibility
  ```
- [ ] Fix any contrast issues (minimum 4.5:1 for normal text)
- [ ] Test with browser DevTools contrast checker

**Responsive Design:**
- [ ] Test on multiple viewports:
  - [ ] Desktop (1920x1080)
  - [ ] Laptop (1366x768)
  - [ ] Tablet (768x1024)
  - [ ] Mobile (375x667)
- [ ] Ensure text scales properly (test at 200% zoom)
- [ ] Fix any horizontal scrolling issues

---

#### [ ] 3.2 Operable Interface

**Keyboard Accessibility:**
- [ ] Test full navigation with keyboard only (unplug mouse!)
- [ ] Check all pages can be navigated with Tab
- [ ] Verify form submission works with Enter
- [ ] Add skip links:
  ```html
  <a href="#main-content" class="skip-link">Skip to main content</a>
  ```
- [ ] Ensure focus indicators are visible
- [ ] Test modal keyboard trap (focus stays in modal)

**Timing:**
- [ ] Implement session timeout warning (5 min before expiry)
- [ ] Add session extension option
- [ ] Test auto-logout after 1 hour

**Seizure Prevention:**
- [ ] Check for flashing content (nothing > 3 flashes/second)
- [ ] Add prefers-reduced-motion support:
  ```css
  @media (prefers-reduced-motion: reduce) {
      * {
          animation-duration: 0.01ms !important;
          transition-duration: 0.01ms !important;
      }
  }
  ```

---

#### [ ] 3.3 Understandable Information

**Language:**
- [ ] Verify `<html lang="en">` is present (✅ already in Login.php)
- [ ] Add lang attributes for non-English content

**Form Labels:**
- [ ] Verify all inputs have labels (✅ Login.php is good)
- [ ] Add ARIA attributes for better accessibility:
  ```html
  <input type="text" id="txtUserId" aria-required="true" aria-invalid="false">
  ```
- [ ] Improve error messages to be specific
- [ ] Associate errors with fields using aria-describedby

**Predictable:**
- [ ] Ensure consistent navigation across pages
- [ ] No unexpected context changes
- [ ] Form submissions are predictable

---

#### [ ] 3.4 Robust Content

**Valid HTML:**
- [ ] Run HTML validator
  ```bash
  html-validator --file=Users/Login.php --format=text
  ```
- [ ] Fix typo in Login.php line 376:
  ```html
  <!-- BEFORE -->
  <stront>© 2024...</stront>
  
  <!-- AFTER -->
  <strong>© 2024...</strong>
  ```
- [ ] Fix image attributes (remove 'px' from width/height)
  ```html
  <!-- BEFORE -->
  <img src="..." width="50px" height="40px">
  
  <!-- AFTER -->
  <img src="..." width="50" height="40">
  ```

**ARIA Usage:**
- [ ] Add appropriate ARIA roles
  ```html
  <nav role="navigation" aria-label="Main navigation">
  <main role="main" aria-label="Main content">
  <form role="form" aria-label="Student login form">
  ```
- [ ] Add aria-live for dynamic content
- [ ] Test with screen reader (NVDA on Windows)

---

### Phase 4: Login Screen Security (2-3 hours)

#### [ ] 4.1 HTTPS Enforcement

**Production:**
- [ ] Obtain SSL certificate (Let's Encrypt or commercial)
- [ ] Configure Apache/Nginx for HTTPS
- [ ] Add HTTPS redirect in .htaccess
- [ ] Set HSTS header

**Current (Localhost):**
- ✅ Not applicable for local development

---

#### [ ] 4.2 CSRF Protection

**Current Status:**
- ✅ Login.php has CSRF token
- ✅ Token validation implemented

**Action Items:**
- [ ] Verify token is validated server-side
- [ ] Add CSRF to all forms (including forget password modal)
- [ ] Test CSRF protection:
  ```powershell
  # Submit without token - should fail
  Invoke-WebRequest -Uri "http://localhost/.../Login.php" `
    -Method POST -Body @{txtUserId="test"; txtPassword="test"; isSubmit="yes"}
  ```

---

#### [ ] 4.3 Rate Limiting

**Current Status:**
- ⚠️ NOT IMPLEMENTED

**Action Items:**
- [ ] Create login_attempts table
  ```sql
  CREATE TABLE login_attempts (
      id INT AUTO_INCREMENT PRIMARY KEY,
      user_id VARCHAR(50) NOT NULL,
      ip_address VARCHAR(45) NOT NULL,
      user_agent VARCHAR(255),
      attempt_time INT NOT NULL,
      INDEX (user_id), INDEX (ip_address)
  );
  ```
- [ ] Implement rate limiting functions (see ACCESS_CONTROL_LOGIN_SECURITY_GUIDE.md)
- [ ] Test brute force protection (10 failed attempts should lock)

---

#### [ ] 4.4 Input Validation

**Current Status:**
- ✅ validate_input() exists
- ✅ Prepared statements used

**Action Items:**
- [ ] Add enhanced validation:
  ```php
  // Username: alphanumeric only
  if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
      // reject
  }
  ```
- [ ] Validate password length (6-128 characters)
- [ ] Test with malicious inputs

---

#### [ ] 4.5 Error Messages

**Current Status:**
- ⚠️ Error messages reveal user existence

**Action Items:**
- [ ] Fix Login.php error messages:
  ```php
  // Use same message for all login failures:
  "Invalid username or password"
  
  // Don't use:
  "User Does Not Exist"
  "Password does not match"
  ```
- [ ] Test that errors don't reveal sensitive info

---

#### [ ] 4.6 Password Security

**Action Items:**
- [ ] Implement password strength requirements
- [ ] Add password strength indicator UI
- [ ] Enforce minimum 8 characters, complexity rules
- [ ] Check against common passwords list
- [ ] Add password change functionality with old password verification

---

### Phase 5: Automated Testing (1-2 hours)

#### [ ] 5.1 Setup Testing Tools

```powershell
# Install all tools
npm install -g pa11y lighthouse html-validator-cli retire
pip install sqlmap

# Download and install:
# - OWASP ZAP
# - Burp Suite Community
```

---

#### [ ] 5.2 Run Automated Tests

```powershell
# Security scan
.\comprehensive_scanner.ps1

# Accessibility scan
pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php

# Lighthouse audit
lighthouse http://localhost/cursorai/Testing/studentportal/Users/Login.php --view

# SQL injection test
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" --data="txtUserId=test&txtPassword=test&isSubmit=yes" --batch

# Vulnerable libraries
retire --path=assets/
```

---

#### [ ] 5.3 Review and Fix Issues

- [ ] Prioritize findings by severity:
  1. Critical (SQL injection, XSS, authentication bypass)
  2. High (CSRF, weak passwords, session issues)
  3. Medium (missing headers, info disclosure)
  4. Low (best practices, warnings)
- [ ] Fix critical issues first
- [ ] Re-test after each fix
- [ ] Document all changes

---

### Phase 6: Remediation (8-12 hours)

#### [ ] 6.1 Critical Fixes

**Priority 1 (Fix Immediately):**
- [ ] Fix SQL injection in landing.php (line 32)
- [ ] Implement generic error messages in Login.php
- [ ] Add rate limiting to login
- [ ] Fix password reset to use tokens instead of SMS

**Priority 2 (Fix This Week):**
- [ ] Add session validation to all pages
- [ ] Implement authorization checks
- [ ] Hash all plain text passwords
- [ ] Add security logging
- [ ] Fix HTML validation errors
- [ ] Add ARIA attributes

**Priority 3 (Fix This Month):**
- [ ] Implement password strength requirements
- [ ] Add 2FA support
- [ ] Improve accessibility (contrast, keyboard nav)
- [ ] Add comprehensive security headers
- [ ] Update old libraries

---

#### [ ] 6.2 Create Secure Files

Files to create (see detailed guides):
- [ ] `Users/includes/session_validator.php`
- [ ] `Users/includes/authorization.php`
- [ ] `Users/includes/security_logger.php`
- [ ] `Users/secure_password_reset.php`
- [ ] `Users/logout.php`
- [ ] `.htaccess` (root directory)
- [ ] `db_security_check.php`

Database tables to create:
- [ ] `login_attempts`
- [ ] `password_reset_tokens`
- [ ] `security_logs` (optional)

---

### Phase 7: Verification (2-3 hours)

#### [ ] 7.1 Re-run All Tests

```powershell
# Full security scan
.\comprehensive_scanner.ps1

# Accessibility
pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php

# SQL injection
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" --batch

# Database security
# Browse to: db_security_check.php
```

---

#### [ ] 7.2 Manual Testing

- [ ] Test login with correct credentials
- [ ] Test login with wrong credentials (should see generic error)
- [ ] Test login with SQL injection payload (should fail)
- [ ] Test login with XSS payload (should be escaped)
- [ ] Test 10 failed logins (should be rate limited)
- [ ] Test accessing pages without login (should redirect)
- [ ] Test accessing another student's data (should be denied)
- [ ] Test keyboard navigation on all pages
- [ ] Test with screen reader
- [ ] Test on mobile devices
- [ ] Test session timeout
- [ ] Test password reset

---

#### [ ] 7.3 Penetration Testing

**Recommended:**
- [ ] Hire professional penetration testers
- [ ] Or use OWASP ZAP for comprehensive automated testing
- [ ] Review findings and remediate

---

### Phase 8: Documentation (1-2 hours)

#### [ ] 8.1 Create Documentation

- [ ] Security architecture document
- [ ] List of implemented security controls
- [ ] Password policy documentation
- [ ] Incident response plan
- [ ] User security guidelines
- [ ] Admin security procedures

---

#### [ ] 8.2 Training

- [ ] Train developers on secure coding
- [ ] Train users on security features (password strength, 2FA)
- [ ] Train admins on monitoring and incident response

---

## 📊 Success Metrics

### Security Goals

- [ ] ✅ No critical vulnerabilities
- [ ] ✅ No SQL injection vulnerabilities
- [ ] ✅ No XSS vulnerabilities
- [ ] ✅ CSRF protection on all forms
- [ ] ✅ All passwords hashed with bcrypt
- [ ] ✅ Rate limiting on login
- [ ] ✅ Secure session management
- [ ] ✅ HTTPS in production
- [ ] ✅ Security headers present
- [ ] ✅ Security logging implemented

### Accessibility Goals

- [ ] ✅ Lighthouse accessibility score > 90
- [ ] ✅ All images have alt text
- [ ] ✅ Color contrast meets WCAG 2.1 AA (4.5:1)
- [ ] ✅ Keyboard navigation works
- [ ] ✅ Screen reader compatible
- [ ] ✅ Valid HTML
- [ ] ✅ Responsive design (mobile/tablet/desktop)
- [ ] ✅ ARIA attributes used correctly

---

## 🔧 Quick Reference - Common Fixes

### Fix SQL Injection

```php
// BEFORE
$query = "SELECT * FROM table WHERE id = '$id'";
$result = mysqli_query($Con, $query);

// AFTER
$stmt = mysqli_prepare($Con, "SELECT * FROM table WHERE id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
```

### Fix XSS

```php
// BEFORE
echo $user_input;

// AFTER
echo htmlspecialchars($user_input, ENT_QUOTES, 'UTF-8');
// Or use:
echo safe_output($user_input);
```

### Add Session Validation

```php
// Add to top of every protected page
require_once 'includes/security_helpers.php';
configure_secure_session();
if (empty($_SESSION['userid'])) {
    header('Location: Login.php');
    exit;
}
```

### Add CSRF Protection

```html
<!-- In form -->
<input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
```

```php
// In form handler
if (!validate_csrf_token($_POST['csrf_token'] ?? '')) {
    die('Invalid security token');
}
```

---

## 📅 Recommended Timeline

**Week 1:**
- Complete Phases 1-2 (Initial Assessment + OWASP Top 10)
- Fix all critical security issues

**Week 2:**
- Complete Phase 3 (W3C/WCAG Compliance)
- Fix accessibility issues

**Week 3:**
- Complete Phases 4-5 (Login Security + Automated Testing)
- Implement all security controls

**Week 4:**
- Complete Phases 6-8 (Remediation + Verification + Documentation)
- Final testing and sign-off

---

## 🎯 Priority Matrix

### Critical (Do First)
1. SQL Injection fixes
2. Rate limiting
3. Generic error messages
4. Password hashing
5. CSRF on all forms

### High (Do This Week)
1. Session validation everywhere
2. Authorization checks
3. Security logging
4. Password reset with tokens
5. Input validation

### Medium (Do This Month)
1. Accessibility improvements
2. HTML validation
3. Security headers
4. Update libraries
5. Password strength requirements

### Low (Do When Possible)
1. 2FA implementation
2. Advanced monitoring
3. Performance optimization
4. UI/UX enhancements
5. Additional features

---

## 📞 Resources and Support

### Documentation
- **OWASP Top 10:** https://owasp.org/Top10/
- **WCAG 2.1:** https://www.w3.org/WAI/WCAG21/quickref/
- **PHP Security Guide:** https://www.php.net/manual/en/security.php
- **MySQL Security:** https://dev.mysql.com/doc/refman/8.0/en/security.html

### Tools
- **OWASP ZAP:** https://www.zaproxy.org/
- **Burp Suite:** https://portswigger.net/burp
- **Lighthouse:** Built into Chrome DevTools
- **WAVE:** https://wave.webaim.org/
- **axe DevTools:** https://www.deque.com/axe/devtools/

### Testing
- **sqlmap:** http://sqlmap.org/
- **pa11y:** https://pa11y.org/
- **retire.js:** https://retirejs.github.io/retire.js/

---

## ✅ Final Sign-Off

After completing all checklist items:

- [ ] All critical vulnerabilities fixed
- [ ] All high-priority issues addressed
- [ ] Accessibility score > 90
- [ ] No SQL injection vulnerabilities
- [ ] No XSS vulnerabilities
- [ ] CSRF protection verified
- [ ] Rate limiting working
- [ ] Session security verified
- [ ] Documentation complete
- [ ] Team trained
- [ ] Monitoring in place

**Ready for Production:** [ ] YES [ ] NO

**Sign-Off:**
- Developer: _________________ Date: _______
- Security Lead: ______________ Date: _______
- Project Manager: ____________ Date: _______

---

**Remember:**
- Security is an ongoing process, not a one-time task
- Re-test after any significant changes
- Keep all software and libraries up to date
- Review security logs regularly
- Conduct annual security audits
- Stay informed about new vulnerabilities


