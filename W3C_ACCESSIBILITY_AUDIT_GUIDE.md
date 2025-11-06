# W3C Guidelines - UI/UX Accessibility & Design Audit
## Student Portal Application

**Standard:** WCAG 2.1 Level AA Compliance  
**Generated:** November 6, 2025

---

## Table of Contents

1. [Overview](#overview)
2. [Perceivable Content](#1-perceivable-content)
3. [Operable Interface](#2-operable-interface)
4. [Understandable Information](#3-understandable-information)
5. [Robust Content](#4-robust-content)
6. [Responsive Design](#5-responsive-design)
7. [Automated Testing Tools](#automated-testing-tools)

---

## Overview

The W3C Web Content Accessibility Guidelines (WCAG) 2.1 are organized around four principles:
- **Perceivable:** Information and UI components must be presentable to users
- **Operable:** UI components and navigation must be operable
- **Understandable:** Information and UI operation must be understandable
- **Robust:** Content must be robust enough to work with assistive technologies

---

## 1. Perceivable Content

### 1.1 Text Alternatives (WCAG 1.1.1 - Level A)

#### ✅ Verification Steps

**Manual Testing:**
1. **Check Images for Alt Text:**
   - Right-click on images and inspect
   - Verify all `<img>` tags have meaningful `alt` attributes
   - Decorative images should have `alt=""`

2. **Review Icons and Graphics:**
   - Ensure icons have text labels or ARIA labels
   - Check SVG images have `<title>` elements

**Automated Testing:**

```bash
# PowerShell script to find images without alt text
Get-ChildItem -Path "Users\" -Filter "*.php" -Recurse | Select-String -Pattern "<img[^>]*>" | ForEach-Object {
    if ($_.Line -notmatch 'alt=') {
        Write-Host "Missing alt: $($_.Filename):$($_.LineNumber)" -ForegroundColor Red
    }
}
```

Or using grep:

```bash
# Find img tags without alt attribute
grep -rn "<img" Users/ --include="*.php" | grep -v "alt="
```

#### 🧰 Recommended Tools

1. **WAVE Browser Extension**
   - Chrome: https://chrome.google.com/webstore (search "WAVE")
   - Firefox: https://addons.mozilla.org/en-US/firefox/addon/wave-accessibility-tool/
   - Tests: Missing alt text, empty links, contrast issues

2. **axe DevTools**
   - Chrome/Firefox extension
   - More detailed accessibility testing

3. **Lighthouse (Built into Chrome)**
   - Open DevTools → Lighthouse → Accessibility audit

#### ⚠️ Common Pitfalls

1. **Generic Alt Text:** `alt="image"`, `alt="picture"`
2. **Missing Alt on Important Images:** Logo without alt text
3. **Redundant Alt Text:** `alt="image of logo"` instead of `alt="School Logo"`

#### 🩻 Example Code Check

**Check your current Login.php:**

```php
// From Login.php (line 334)
<img src="assets/image/logo_new.svg" alt="school logo" style="max-width: 300px;">
```

✅ **Good:** Has alt attribute  
⚠️ **Improvement:** Make it more specific: `alt="Delhi Public School RK Puram Logo"`

**Fix Issues in Your Code:**

```html
<!-- BEFORE (Generic) -->
<img src="assets/image/logo_new.svg" alt="school logo">

<!-- AFTER (Descriptive) -->
<img src="assets/image/logo_new.svg" alt="Delhi Public School RK Puram - Student Portal Login">

<!-- Social Media Icons (from line 365-368) -->
<!-- BEFORE -->
<a href="https://apps.apple.com/us/app/..." target="blank">
    <img src="assets/image/apple.svg" width="50px" height="40px">
</a>

<!-- AFTER -->
<a href="https://apps.apple.com/us/app/..." target="_blank" aria-label="Download on Apple App Store">
    <img src="assets/image/apple.svg" width="50" height="40" alt="Apple App Store">
</a>

<!-- Decorative images (if any) -->
<img src="decorative-pattern.png" alt="" role="presentation">
```

**Scan Script:**

```php
<?php
// accessibility_image_checker.php
$directory = 'Users/';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
$issues = [];

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $lines = explode("\n", $content);
        
        foreach ($lines as $num => $line) {
            // Check for img tags without alt
            if (preg_match('/<img[^>]*>/', $line)) {
                if (!preg_match('/alt=/', $line)) {
                    $issues[] = [
                        'file' => $file->getPathname(),
                        'line' => $num + 1,
                        'issue' => 'Missing alt attribute',
                        'code' => trim($line)
                    ];
                } elseif (preg_match('/alt=""/', $line) && !preg_match('/role="presentation"/', $line)) {
                    $issues[] = [
                        'file' => $file->getPathname(),
                        'line' => $num + 1,
                        'issue' => 'Empty alt without role="presentation"',
                        'code' => trim($line)
                    ];
                }
            }
        }
    }
}

echo "=== Image Accessibility Issues ===\n\n";
foreach ($issues as $issue) {
    echo "⚠️ {$issue['file']}:{$issue['line']}\n";
    echo "   Issue: {$issue['issue']}\n";
    echo "   Code: {$issue['code']}\n\n";
}
echo "Total issues found: " . count($issues) . "\n";
?>
```

---

### 1.2 Color Contrast (WCAG 1.4.3 - Level AA)

#### ✅ Verification Steps

**Manual Testing:**
1. **Check Text Contrast:**
   - Normal text needs 4.5:1 contrast ratio
   - Large text (18pt+/14pt+ bold) needs 3:1 contrast ratio
   - Use browser DevTools to inspect colors

2. **Test with Tools:**
   - Use WebAIM Contrast Checker
   - Test all text/background combinations

**Automated Testing:**

Use Lighthouse in Chrome DevTools:
1. Open DevTools (F12)
2. Go to Lighthouse tab
3. Select "Accessibility"
4. Click "Generate report"

#### 🧰 Recommended Tools

1. **WebAIM Contrast Checker**
   - URL: https://webaim.org/resources/contrastchecker/
   - Enter foreground/background colors

2. **Colour Contrast Analyser (CCA)**
   - Download: https://www.tpgi.com/color-contrast-checker/
   - Desktop app for Windows/Mac

3. **Chrome DevTools**
   - Inspect element → Styles → Color picker shows contrast ratio

#### ⚠️ Common Pitfalls

1. **Light Gray on White:** Common but often fails contrast
2. **Low Contrast Links:** Link color too similar to text
3. **Placeholder Text:** Often has insufficient contrast

#### 🩻 Example Code Check

**Check Your CSS Files:**

```css
/* Example from dps-users-style.css */
/* Check if these have sufficient contrast */

/* Login button */
.btn-primary {
    background-color: #007bff; /* Check against white text */
    color: #ffffff;
}

/* Check contrast ratio: */
/* #007bff vs #ffffff = 4.5:1 (PASS for normal text) */

/* Error text */
.text-danger {
    color: #dc3545; /* Check against background */
}

/* Warning text */
.text-warning {
    color: #ffc107; /* Often FAILS on white background */
}
```

**Test Script:**

```javascript
// Run in browser console on your pages
// This checks all text elements for contrast
(function() {
    function getContrast(rgb1, rgb2) {
        // Calculate relative luminance
        function getLuminance(rgb) {
            const [r, g, b] = rgb.map(v => {
                v /= 255;
                return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
            });
            return 0.2126 * r + 0.7152 * g + 0.0722 * b;
        }
        
        const lum1 = getLuminance(rgb1);
        const lum2 = getLuminance(rgb2);
        const brightest = Math.max(lum1, lum2);
        const darkest = Math.min(lum1, lum2);
        return (brightest + 0.05) / (darkest + 0.05);
    }
    
    function parseColor(color) {
        const canvas = document.createElement('canvas');
        canvas.width = canvas.height = 1;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, 1, 1);
        return ctx.getImageData(0, 0, 1, 1).data.slice(0, 3);
    }
    
    const elements = document.querySelectorAll('p, h1, h2, h3, h4, h5, h6, a, button, span, div, label');
    const issues = [];
    
    elements.forEach(el => {
        const style = window.getComputedStyle(el);
        const color = parseColor(style.color);
        const bgColor = parseColor(style.backgroundColor);
        const fontSize = parseFloat(style.fontSize);
        const fontWeight = parseInt(style.fontWeight);
        
        const contrast = getContrast(color, bgColor);
        const isLargeText = fontSize >= 18 || (fontSize >= 14 && fontWeight >= 700);
        const required = isLargeText ? 3 : 4.5;
        
        if (contrast < required && el.textContent.trim() !== '') {
            issues.push({
                element: el,
                contrast: contrast.toFixed(2),
                required: required,
                text: el.textContent.trim().substring(0, 50)
            });
        }
    });
    
    console.log(`%c=== Contrast Issues Found: ${issues.length} ===`, 'color: red; font-size: 16px; font-weight: bold');
    issues.forEach((issue, i) => {
        console.log(`\n${i + 1}. "${issue.text}"`);
        console.log(`   Contrast: ${issue.contrast}:1 (Required: ${issue.required}:1)`);
        console.log('   Element:', issue.element);
    });
})();
```

**Fixes for Common Issues:**

```css
/* If warning text fails contrast */
/* BEFORE */
.text-warning {
    color: #ffc107; /* 2.5:1 - FAILS */
}

/* AFTER */
.text-warning {
    color: #856404; /* Dark warning color - 4.6:1 PASSES */
    font-weight: 500; /* Improve readability */
}

/* Placeholder text */
/* BEFORE */
::placeholder {
    color: #999; /* Often fails */
}

/* AFTER */
::placeholder {
    color: #6c757d; /* 4.5:1 contrast */
    opacity: 1;
}
```

---

### 1.3 Responsive Images and Text (WCAG 1.4.4 - Level AA)

#### ✅ Verification Steps

**Manual Testing:**
1. **Test Text Resize:**
   - Zoom browser to 200%
   - Verify text is readable and doesn't overlap
   - Check if layout breaks

2. **Test Different Viewports:**
   - Desktop (1920x1080)
   - Tablet (768x1024)
   - Mobile (375x667)

**Automated Testing:**

```javascript
// Run in browser console
// Test responsive breakpoints
const viewports = [
    { width: 1920, height: 1080, name: 'Desktop' },
    { width: 1366, height: 768, name: 'Laptop' },
    { width: 768, height: 1024, name: 'Tablet' },
    { width: 375, height: 667, name: 'Mobile' }
];

console.log('Open DevTools > Device Toolbar (Ctrl+Shift+M)');
console.log('Test these viewports:');
viewports.forEach(v => {
    console.log(`${v.name}: ${v.width}x${v.height}`);
});
```

#### 🧰 Recommended Tools

1. **Chrome DevTools - Device Mode**
   - Press F12 → Ctrl+Shift+M (Windows) or Cmd+Shift+M (Mac)
   - Test multiple device sizes

2. **BrowserStack**
   - URL: https://www.browserstack.com/
   - Test on real devices

3. **Responsive Design Checker**
   - URL: https://responsivedesignchecker.com/

#### ⚠️ Common Pitfalls

1. **Fixed Width Elements:** Elements with `width: 500px` don't scale
2. **Small Touch Targets:** Buttons too small on mobile
3. **Horizontal Scrolling:** Content wider than viewport

#### 🩻 Example Code Check

**Check Your Login.php:**

```html
<!-- From Login.php (line 303) -->
<meta name="viewport" content="width=device-width, initial-scale=1">
```

✅ **Good:** Has viewport meta tag

**Check Responsive Layout:**

```html
<!-- From Login.php (lines 326-331) -->
<div class="col-md-6 col-lg-6 left-container...">
```

✅ **Good:** Uses Bootstrap responsive grid

**Improvements Needed:**

```css
/* Ensure images are responsive */
img {
    max-width: 100%;
    height: auto;
}

/* Ensure buttons are touch-friendly (minimum 44x44px) */
.btn {
    min-height: 44px;
    min-width: 44px;
    padding: 12px 24px;
}

/* Prevent horizontal scroll */
body {
    overflow-x: hidden;
}

.container-fluid {
    max-width: 100%;
    overflow-x: hidden;
}

/* Text should scale with viewport */
body {
    font-size: 16px; /* Base size */
}

@media (max-width: 768px) {
    body {
        font-size: 14px;
    }
}
```

---

## 2. Operable Interface

### 2.1 Keyboard Accessibility (WCAG 2.1.1 - Level A)

#### ✅ Verification Steps

**Manual Testing:**
1. **Tab Navigation:**
   - Unplug your mouse
   - Press Tab to navigate through page
   - Verify all interactive elements are reachable
   - Check tab order is logical

2. **Form Submission:**
   - Navigate to form fields with Tab
   - Submit form with Enter key
   - Verify dropdowns work with arrow keys

3. **Visual Focus Indicator:**
   - Check if focused element is highlighted
   - Verify focus indicator has good contrast

**Automated Testing:**

```javascript
// Run in browser console
// Test keyboard navigation
(function() {
    console.log('%cKeyboard Accessibility Test', 'font-size: 16px; font-weight: bold');
    
    // Get all focusable elements
    const focusable = document.querySelectorAll(
        'a[href], button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
    );
    
    console.log(`\nTotal focusable elements: ${focusable.length}`);
    
    // Check for elements with negative tab index
    const negative = document.querySelectorAll('[tabindex="-1"]');
    console.log(`Elements with tabindex="-1": ${negative.length}`);
    if (negative.length > 0) {
        console.warn('These elements cannot be focused with keyboard:', negative);
    }
    
    // Check for focus indicators
    const style = document.createElement('style');
    style.textContent = `
        :focus {
            outline: 3px solid red !important;
            outline-offset: 2px !important;
        }
    `;
    document.head.appendChild(style);
    console.log('\n✅ Focus indicators highlighted in red');
    console.log('Press Tab to test keyboard navigation');
})();
```

#### 🧰 Recommended Tools

1. **Manual Testing:** Use keyboard only
2. **WAVE Extension:** Shows tab order
3. **axe DevTools:** Detects keyboard traps

#### ⚠️ Common Pitfalls

1. **Keyboard Trap:** Focus gets stuck in modal/dropdown
2. **No Focus Indicator:** Can't see which element is focused
3. **Skip Links Missing:** No way to skip navigation
4. **Custom Widgets:** Dropdowns/modals not keyboard accessible

#### 🩻 Example Code Check

**Check Focus Indicators in Your CSS:**

```css
/* Add to your CSS file */

/* Ensure all interactive elements have visible focus */
a:focus,
button:focus,
input:focus,
select:focus,
textarea:focus {
    outline: 2px solid #005fcc;
    outline-offset: 2px;
    box-shadow: 0 0 0 3px rgba(0, 95, 204, 0.2);
}

/* Don't remove outline! */
/* NEVER DO THIS: */
/* *:focus { outline: none; } */

/* Skip to main content link */
.skip-link {
    position: absolute;
    top: -40px;
    left: 0;
    background: #000;
    color: #fff;
    padding: 8px;
    text-decoration: none;
    z-index: 100;
}

.skip-link:focus {
    top: 0;
}
```

**Add Skip Link to Login.php:**

```html
<!-- Add after <body> tag (line 320) -->
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <div class="container-fluid" id="main-content">
        <!-- existing content -->
    </div>
</body>
```

**Ensure Modal is Keyboard Accessible:**

```javascript
// For the forget password modal (line 386+)
document.addEventListener('DOMContentLoaded', function() {
    // Trap focus in modal when open
    const modal = document.getElementById('edit_forget_modal');
    
    $(modal).on('shown.bs.modal', function() {
        // Focus first input
        const firstInput = modal.querySelector('input:not([type="hidden"])');
        if (firstInput) firstInput.focus();
        
        // Trap focus within modal
        const focusable = modal.querySelectorAll(
            'button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        const firstFocusable = focusable[0];
        const lastFocusable = focusable[focusable.length - 1];
        
        modal.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                if (e.shiftKey && document.activeElement === firstFocusable) {
                    e.preventDefault();
                    lastFocusable.focus();
                } else if (!e.shiftKey && document.activeElement === lastFocusable) {
                    e.preventDefault();
                    firstFocusable.focus();
                }
            }
            
            // Close on Escape
            if (e.key === 'Escape') {
                $(modal).modal('hide');
            }
        });
    });
});
```

---

### 2.2 Enough Time (WCAG 2.2.1 - Level A)

#### ✅ Verification Steps

1. **Check Session Timeout:**
   - Time how long until session expires
   - Verify warning is shown before expiration
   - Check if user can extend session

2. **Auto-Refresh:**
   - Check if pages auto-refresh
   - Verify user can stop auto-refresh

#### 🧰 Recommended Tools

Manual testing

#### ⚠️ Common Pitfalls

1. **Short Session Timeout:** < 20 minutes without warning
2. **No Warning:** Session expires without notice
3. **Cannot Extend:** No option to extend session

#### 🩻 Example Implementation

**Add Session Timeout Warning:**

```javascript
// session_timeout_warning.js
// Add to all authenticated pages

(function() {
    const SESSION_TIMEOUT = 3600; // 1 hour in seconds (from security_helpers.php)
    const WARNING_TIME = 300; // Show warning 5 minutes before expiry
    
    let lastActivity = Date.now();
    let warningShown = false;
    
    // Reset timer on user activity
    ['click', 'keypress', 'scroll', 'mousemove'].forEach(event => {
        document.addEventListener(event, () => {
            lastActivity = Date.now();
            warningShown = false;
        }, { passive: true });
    });
    
    // Check session timeout
    setInterval(() => {
        const elapsed = (Date.now() - lastActivity) / 1000;
        const remaining = SESSION_TIMEOUT - elapsed;
        
        if (remaining <= 0) {
            // Session expired
            alert('Your session has expired. Please login again.');
            window.location.href = 'Login.php';
        } else if (remaining <= WARNING_TIME && !warningShown) {
            // Show warning
            warningShown = true;
            const minutes = Math.floor(remaining / 60);
            
            if (confirm(`Your session will expire in ${minutes} minutes. Would you like to stay logged in?`)) {
                // Refresh session
                fetch('refresh_session.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            lastActivity = Date.now();
                            warningShown = false;
                        }
                    });
            }
        }
    }, 30000); // Check every 30 seconds
})();
```

**Create refresh_session.php:**

```php
<?php
// refresh_session.php
session_start();

// Check if user is logged in
if (!empty($_SESSION['userid'])) {
    // Refresh session
    session_regenerate_id(true);
    $_SESSION['last_activity'] = time();
    
    echo json_encode(['success' => true, 'message' => 'Session refreshed']);
} else {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
}
?>
```

---

### 2.3 Seizures and Physical Reactions (WCAG 2.3.1 - Level A)

#### ✅ Verification Steps

1. **Check for Flashing Content:**
   - Look for animations, videos, GIFs
   - Verify nothing flashes more than 3 times per second

#### ⚠️ Common Pitfalls

1. **Auto-Playing Videos:** Videos with flashing content
2. **Animation Speed:** Fast carousel transitions

#### 🩻 Example Code Check

```css
/* Respect user's motion preferences */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

---

## 3. Understandable Information

### 3.1 Page Language (WCAG 3.1.1 - Level A)

#### ✅ Verification Steps

**Check HTML Lang Attribute:**

```bash
# Check if lang attribute exists
grep -n "<html" Users/*.php
```

#### 🩻 Example Code Check

**Your Login.php:**

```html
<!-- Line 299 -->
<html lang="en">
```

✅ **Good:** Has lang attribute

**If Missing, Add:**

```html
<!DOCTYPE html>
<html lang="en">
```

**For Multi-Language Support:**

```html
<!-- English content -->
<html lang="en">

<!-- Hindi content -->
<html lang="hi">

<!-- Mixed content -->
<html lang="en">
<body>
    <p>Welcome to the portal</p>
    <p lang="hi">पोर्टल में आपका स्वागत है</p>
</body>
</html>
```

---

### 3.2 Form Labels and Instructions (WCAG 3.3.2 - Level A)

#### ✅ Verification Steps

**Manual Testing:**
1. **Check All Form Fields:**
   - Verify every input has a visible label
   - Check if label is associated with input

2. **Error Messages:**
   - Submit form with errors
   - Check if error messages are clear and specific

**Automated Testing:**

```bash
# Find inputs without labels
grep -rn "<input" Users/ --include="*.php" | grep -v "label\|aria-label"
```

#### 🧰 Recommended Tools

1. **WAVE Extension:** Shows form labels
2. **axe DevTools:** Detects missing labels

#### ⚠️ Common Pitfalls

1. **Placeholder Instead of Label:** Placeholder disappears when typing
2. **No Error Messages:** Form just doesn't submit
3. **Generic Errors:** "Error occurred" instead of specific message

#### 🩻 Example Code Check

**Your Login.php (Good Example):**

```html
<!-- Lines 346-354 - GOOD -->
<div class="form-group">
    <label for="txtUserId">User ID / Admission Number</label>
    <input type="text" class="form-control" id="txtUserId" name="txtUserId" placeholder="Enter User ID" required>
</div>

<div class="form-group">
    <label for="txtPassword">Password</label>
    <input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Enter Password" required>
</div>
```

✅ **Good Features:**
- Has `<label>` element
- Label has `for` attribute matching input `id`
- Input has descriptive `placeholder`
- Has `required` attribute

**Improvements:**

```html
<!-- Add ARIA attributes for better accessibility -->
<div class="form-group">
    <label for="txtUserId">
        User ID / Admission Number
        <span aria-label="required" class="required">*</span>
    </label>
    <input 
        type="text" 
        class="form-control" 
        id="txtUserId" 
        name="txtUserId" 
        placeholder="Enter User ID" 
        required
        aria-required="true"
        aria-describedby="userIdHelp">
    <small id="userIdHelp" class="form-text text-muted">
        Enter your admission number (e.g., TEST001)
    </small>
</div>

<!-- Error message should be associated with input -->
<div class="form-group">
    <label for="txtPassword">Password</label>
    <input 
        type="password" 
        id="txtPassword" 
        name="txtPassword"
        aria-required="true"
        aria-invalid="false"
        aria-describedby="passwordError">
    <div id="passwordError" class="error-message" role="alert" style="display:none;">
        <!-- Error message will be inserted here -->
    </div>
</div>
```

**Enhanced Error Handling:**

```javascript
// Replace the Validate() function in Login.php
function Validate() {
    const userIdField = document.getElementById("txtUserId");
    const passwordField = document.getElementById("txtPassword");
    const userId = userIdField.value.trim();
    const password = passwordField.value.trim();
    
    // Clear previous errors
    clearErrors();
    
    let hasError = false;
    
    if (userId === "") {
        showError(userIdField, "Please enter your User ID or Admission Number");
        hasError = true;
    }
    
    if (password === "") {
        showError(passwordField, "Please enter your password");
        hasError = true;
    }
    
    return !hasError;
}

function showError(field, message) {
    // Set ARIA invalid
    field.setAttribute('aria-invalid', 'true');
    field.classList.add('is-invalid');
    
    // Create/update error message
    let errorId = field.id + 'Error';
    let errorDiv = document.getElementById(errorId);
    
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.id = errorId;
        errorDiv.className = 'invalid-feedback';
        errorDiv.setAttribute('role', 'alert');
        field.parentNode.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
    
    // Update aria-describedby
    field.setAttribute('aria-describedby', errorId);
    
    // Show toast (your existing method)
    toastr.warning(message, "Validation Error");
}

function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
        field.setAttribute('aria-invalid', 'false');
    });
    
    document.querySelectorAll('.invalid-feedback').forEach(error => {
        error.style.display = 'none';
    });
}
```

---

## 4. Robust Content

### 4.1 Valid HTML (WCAG 4.1.1 - Level A)

#### ✅ Verification Steps

**Automated Testing:**

1. **W3C Markup Validation Service**
   - URL: https://validator.w3.org/
   - Upload your HTML file or provide URL

2. **Command Line Validation:**

```bash
# Install HTML5 validator
npm install -g html-validator-cli

# Validate file
html-validator --file=Users/Login.php --format=text
```

#### 🧰 Recommended Tools

1. **W3C Validator**
   - URL: https://validator.w3.org/
   - Checks HTML syntax

2. **Browser DevTools**
   - Check console for errors

#### ⚠️ Common Pitfalls

1. **Unclosed Tags:** `<div>` without `</div>`
2. **Duplicate IDs:** Same `id` used multiple times
3. **Invalid Nesting:** `<p><div></div></p>`

#### 🩻 Example Code Check

**Issues Found in Login.php:**

```html
<!-- Line 376 - Typo in closing tag -->
<stront>© 2024 All rights reserved . Powered by</stront>
<!-- Should be: -->
<strong>© 2024 All rights reserved . Powered by</strong>

<!-- Line 367 - Missing closing tag -->
<a href="..."><img src="...">
<!-- Should be: -->
<a href="..."><img src="..."></a>
```

**Validation Script:**

```php
<?php
// html_validator.php
function validateHTML($file) {
    $html = file_get_contents($file);
    $errors = [];
    
    // Check for common issues
    
    // 1. Unclosed tags
    preg_match_all('/<(\w+)[^>]*>/', $html, $openTags);
    preg_match_all('/<\/(\w+)>/', $html, $closeTags);
    
    $selfClosing = ['img', 'input', 'br', 'hr', 'meta', 'link'];
    $opening = array_diff($openTags[1], $selfClosing);
    $closing = $closeTags[1];
    
    if (count($opening) != count($closing)) {
        $errors[] = "Potential unclosed tags";
    }
    
    // 2. Duplicate IDs
    preg_match_all('/id="([^"]+)"/', $html, $ids);
    $duplicates = array_diff_assoc($ids[1], array_unique($ids[1]));
    if (!empty($duplicates)) {
        $errors[] = "Duplicate IDs found: " . implode(', ', array_unique($duplicates));
    }
    
    // 3. Invalid attributes on img
    if (preg_match('/width="?\d+px"?/', $html)) {
        $errors[] = "Invalid width attribute (remove 'px' unit)";
    }
    
    return $errors;
}

$files = glob('Users/*.php');
foreach ($files as $file) {
    $errors = validateHTML($file);
    if (!empty($errors)) {
        echo "⚠️ $file:\n";
        foreach ($errors as $error) {
            echo "   - $error\n";
        }
        echo "\n";
    }
}
?>
```

---

### 4.2 ARIA Roles and Attributes (WCAG 4.1.2 - Level A)

#### ✅ Verification Steps

**Manual Testing:**
1. **Check ARIA Usage:**
   - Verify proper use of ARIA roles
   - Check that ARIA attributes are valid

2. **Screen Reader Testing:**
   - Test with NVDA (Windows) or VoiceOver (Mac)

**Automated Testing:**

```javascript
// Run in console
// Check ARIA usage
(function() {
    const elements = document.querySelectorAll('[role], [aria-label], [aria-labelledby], [aria-describedby]');
    console.log(`Elements with ARIA attributes: ${elements.length}`);
    
    elements.forEach(el => {
        console.log(el.tagName, {
            role: el.getAttribute('role'),
            label: el.getAttribute('aria-label'),
            labelledby: el.getAttribute('aria-labelledby'),
            describedby: el.getAttribute('aria-describedby')
        });
    });
})();
```

#### 🧰 Recommended Tools

1. **NVDA Screen Reader** (Windows)
   - Download: https://www.nvaccess.org/download/

2. **JAWS Screen Reader** (Windows, Commercial)

3. **VoiceOver** (Mac/iOS, Built-in)
   - Press Cmd+F5 to enable

#### 🩻 Example Implementation

**Add ARIA to Your Components:**

```html
<!-- Navigation -->
<nav role="navigation" aria-label="Main navigation">
    <ul>
        <li><a href="home.php">Home</a></li>
    </ul>
</nav>

<!-- Main content -->
<main role="main" aria-label="Main content">
    <!-- content -->
</main>

<!-- Alert messages -->
<div role="alert" aria-live="polite" class="alert">
    Your session will expire soon
</div>

<!-- Form -->
<form role="form" aria-label="Student login form">
    <!-- form fields -->
</form>

<!-- Button with icon only -->
<button aria-label="Close modal">
    <span aria-hidden="true">&times;</span>
</button>

<!-- Status message -->
<div role="status" aria-live="polite" id="status-message"></div>
```

---

## 5. Responsive Design

### 5.1 Mobile-First Approach

#### ✅ Verification Steps

**Manual Testing:**
1. Test on actual mobile devices
2. Test landscape/portrait orientations
3. Test different screen sizes

#### 🩻 Example CSS

```css
/* Mobile-first responsive design */

/* Base styles (mobile) */
.container {
    padding: 15px;
}

.login-form {
    width: 100%;
}

/* Tablet and up */
@media (min-width: 768px) {
    .container {
        padding: 30px;
    }
    
    .login-form {
        max-width: 500px;
        margin: 0 auto;
    }
}

/* Desktop and up */
@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
}

/* Touch-friendly buttons */
button, .btn, a.btn {
    min-height: 44px;
    min-width: 44px;
    padding: 12px 24px;
}

/* Responsive tables */
@media (max-width: 768px) {
    table {
        display: block;
        overflow-x: auto;
    }
}
```

---

## Automated Testing Tools

### Complete Accessibility Testing Suite

```bash
# 1. Install Node.js tools
npm install -g pa11y lighthouse html-validator-cli

# 2. Run pa11y accessibility test
pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php

# 3. Run Lighthouse audit
lighthouse http://localhost/cursorai/Testing/studentportal/Users/Login.php --view

# 4. Validate HTML
html-validator --file=Users/Login.php --format=text
```

### Browser Extension Checklist

1. **Install These Extensions:**
   - WAVE Evaluation Tool
   - axe DevTools
   - Lighthouse (built into Chrome)

2. **Run Tests on Each Page:**
   - Login.php
   - landing.php
   - All major user-facing pages

3. **Fix Issues in Priority Order:**
   - Critical (WCAG Level A failures)
   - Serious (WCAG Level AA failures)
   - Moderate (best practices)

---

## Summary Checklist

### Before Going Live:

- [ ] All images have meaningful alt text
- [ ] Color contrast meets 4.5:1 ratio
- [ ] All forms have proper labels
- [ ] Keyboard navigation works throughout
- [ ] HTML validates without errors
- [ ] Responsive on mobile/tablet/desktop
- [ ] Session timeout has warning
- [ ] ARIA attributes used correctly
- [ ] Focus indicators visible
- [ ] Screen reader tested
- [ ] Lighthouse score > 90 for Accessibility

---

**Next Steps:**
1. Run automated tests
2. Fix critical issues first
3. Test with real users (including those with disabilities)
4. Re-test after fixes
5. Document accessibility features


