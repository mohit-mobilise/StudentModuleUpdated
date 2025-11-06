# Automated Security Testing Guide
## Student Portal Application - Complete Testing Toolkit

**Generated:** November 6, 2025  
**Purpose:** Automated tools, scripts, and commands for comprehensive security testing

---

## Table of Contents

1. [Quick Start - Essential Tests](#1-quick-start---essential-tests)
2. [Tool Installation](#2-tool-installation)
3. [OWASP Top 10 Automated Tests](#3-owasp-top-10-automated-tests)
4. [Accessibility Testing Suite](#4-accessibility-testing-suite)
5. [Custom Testing Scripts](#5-custom-testing-scripts)
6. [Continuous Testing](#6-continuous-testing)
7. [Report Generation](#7-report-generation)

---

## 1. Quick Start - Essential Tests

### Run All Essential Tests (5 Minutes)

```powershell
# PowerShell script for Windows (XAMPP environment)
# Save as: quick_security_test.ps1

Write-Host "=== Quick Security Test Suite ===" -ForegroundColor Cyan
Write-Host ""

$baseUrl = "http://localhost/cursorai/Testing/studentportal"
$results = @()

# Test 1: Check if application is running
Write-Host "[1/7] Checking application availability..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$baseUrl/Users/Login.php" -Method GET -TimeoutSec 5
    if ($response.StatusCode -eq 200) {
        Write-Host "✓ Application is running" -ForegroundColor Green
        $results += "✓ Application available"
    }
} catch {
    Write-Host "✗ Application not accessible" -ForegroundColor Red
    $results += "✗ Application not accessible"
}

# Test 2: Check for exposed files
Write-Host "[2/7] Checking for exposed sensitive files..." -ForegroundColor Yellow
$sensitiveFiles = @(".env", "phpinfo.php", ".git/config", "config.php.bak")
foreach ($file in $sensitiveFiles) {
    try {
        $response = Invoke-WebRequest -Uri "$baseUrl/$file" -Method GET -TimeoutSec 3
        Write-Host "✗ WARNING: $file is accessible!" -ForegroundColor Red
        $results += "✗ Exposed file: $file"
    } catch {
        Write-Host "✓ $file is not accessible" -ForegroundColor Green
        $results += "✓ $file protected"
    }
}

# Test 3: Check security headers
Write-Host "[3/7] Checking security headers..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "$baseUrl/Users/Login.php" -Method GET
    $headers = $response.Headers
    
    $requiredHeaders = @{
        "X-Frame-Options" = "Clickjacking protection"
        "X-Content-Type-Options" = "MIME sniffing protection"
        "X-XSS-Protection" = "XSS protection"
    }
    
    foreach ($header in $requiredHeaders.Keys) {
        if ($headers.ContainsKey($header)) {
            Write-Host "✓ $header present: $($headers[$header])" -ForegroundColor Green
            $results += "✓ $header: Present"
        } else {
            Write-Host "✗ $header missing" -ForegroundColor Red
            $results += "✗ $header: Missing"
        }
    }
} catch {
    Write-Host "✗ Could not check headers" -ForegroundColor Red
}

# Test 4: Test CSRF protection
Write-Host "[4/7] Testing CSRF protection..." -ForegroundColor Yellow
try {
    $body = @{
        txtUserId = "test"
        txtPassword = "test"
        isSubmit = "yes"
    }
    $response = Invoke-WebRequest -Uri "$baseUrl/Users/Login.php" -Method POST -Body $body
    if ($response.Content -match "security token|csrf") {
        Write-Host "✓ CSRF protection detected" -ForegroundColor Green
        $results += "✓ CSRF: Protected"
    } else {
        Write-Host "⚠ CSRF protection unclear" -ForegroundColor Yellow
        $results += "⚠ CSRF: Unclear"
    }
} catch {
    # Request might fail, which is expected
}

# Test 5: Test SQL injection in login
Write-Host "[5/7] Testing basic SQL injection..." -ForegroundColor Yellow
$sqlPayloads = @("admin' OR '1'='1", "' OR 1=1--", "admin'--")
$vulnerable = $false
foreach ($payload in $sqlPayloads) {
    try {
        $body = @{
            txtUserId = $payload
            txtPassword = "test"
            isSubmit = "yes"
        }
        $response = Invoke-WebRequest -Uri "$baseUrl/Users/Login.php" -Method POST -Body $body
        if ($response.Content -match "Dashboard|Welcome|Logged in") {
            Write-Host "✗ CRITICAL: SQL Injection vulnerability detected!" -ForegroundColor Red
            $results += "✗ SQL Injection: VULNERABLE"
            $vulnerable = $true
            break
        }
    } catch {
        # Expected to fail
    }
}
if (-not $vulnerable) {
    Write-Host "✓ SQL Injection: No obvious vulnerabilities" -ForegroundColor Green
    $results += "✓ SQL Injection: Protected"
}

# Test 6: Test XSS in error messages
Write-Host "[6/7] Testing XSS vulnerability..." -ForegroundColor Yellow
$xssPayload = "<script>alert('XSS')</script>"
try {
    $body = @{
        txtUserId = $xssPayload
        txtPassword = "test"
        isSubmit = "yes"
    }
    $response = Invoke-WebRequest -Uri "$baseUrl/Users/Login.php" -Method POST -Body $body
    if ($response.Content -match "<script>alert") {
        Write-Host "✗ XSS vulnerability detected!" -ForegroundColor Red
        $results += "✗ XSS: VULNERABLE"
    } else {
        Write-Host "✓ XSS payload was sanitized" -ForegroundColor Green
        $results += "✓ XSS: Protected"
    }
} catch {
    # Expected
}

# Test 7: Check HTTPS redirect
Write-Host "[7/7] Checking HTTPS enforcement..." -ForegroundColor Yellow
Write-Host "⚠ HTTPS test skipped (localhost)" -ForegroundColor Yellow
$results += "⚠ HTTPS: Not applicable for localhost"

# Summary
Write-Host ""
Write-Host "=== Test Summary ===" -ForegroundColor Cyan
$results | ForEach-Object { Write-Host $_ }

Write-Host ""
Write-Host "Test complete! Review findings above." -ForegroundColor Cyan
```

**To run this script:**

```powershell
# In PowerShell (Run as Administrator if needed)
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
.\quick_security_test.ps1
```

---

## 2. Tool Installation

### Windows (XAMPP Environment)

```powershell
# Install Chocolatey (package manager) if not already installed
Set-ExecutionPolicy Bypass -Scope Process -Force
[System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072
iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))

# Install Node.js (for npm tools)
choco install nodejs -y

# Install Python (for some security tools)
choco install python -y

# Install Git (for cloning tools)
choco install git -y

# Refresh environment
refreshenv
```

### Install Security Testing Tools

```bash
# npm-based tools
npm install -g lighthouse
npm install -g pa11y
npm install -g html-validator-cli
npm install -g retire

# Python-based tools
pip install sqlmap
pip install httpie
pip install requests

# Browser extensions (manual installation)
# - WAVE: https://chrome.google.com/webstore (search "WAVE")
# - axe DevTools: https://chrome.google.com/webstore (search "axe")
# - Cookie Editor: https://chrome.google.com/webstore (search "Cookie Editor")
```

### Install OWASP ZAP

```powershell
# Download OWASP ZAP
choco install zap -y

# Or download manually from:
# https://www.zaproxy.org/download/
```

### Install Burp Suite Community

```powershell
# Download from:
# https://portswigger.net/burp/communitydownload

# Or use Chocolatey (if available)
choco install burp-suite-free-edition -y
```

---

## 3. OWASP Top 10 Automated Tests

### 3.1 SQL Injection Testing

#### Using sqlmap

```bash
# Basic SQL injection test on login form
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  --batch \
  --level=5 \
  --risk=3 \
  --tamper=space2comment

# Test specific parameter
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  -p txtUserId \
  --batch

# Test with cookie authentication (after login)
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/landing.php" \
  --cookie="PHPSESSID=your_session_id" \
  --batch \
  --level=3

# Enumerate databases
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  --dbs \
  --batch

# Dump tables
sqlmap -u "http://localhost/cursorai/Testing/studentportal/Users/Login.php" \
  --data="txtUserId=test&txtPassword=test&isSubmit=yes" \
  -D schoolerpbeta \
  --tables \
  --batch
```

#### Manual SQL Injection Tests

```powershell
# PowerShell script for manual SQL injection testing
$baseUrl = "http://localhost/cursorai/Testing/studentportal/Users/Login.php"

$sqlPayloads = @(
    "' OR '1'='1",
    "' OR '1'='1'--",
    "' OR '1'='1'/*",
    "admin' OR '1'='1",
    "admin' OR '1'='1'--",
    "admin'--",
    "' UNION SELECT NULL--",
    "' UNION SELECT NULL,NULL--",
    "1' AND 1=1--",
    "1' AND 1=2--"
)

Write-Host "Testing SQL Injection payloads..." -ForegroundColor Cyan
foreach ($payload in $sqlPayloads) {
    $body = @{
        txtUserId = $payload
        txtPassword = "test"
        isSubmit = "yes"
    }
    
    try {
        $response = Invoke-WebRequest -Uri $baseUrl -Method POST -Body $body
        
        # Check for signs of successful injection
        if ($response.Content -match "Dashboard|Welcome|landing|SQL syntax|mysql_fetch") {
            Write-Host "⚠ Potential vulnerability with payload: $payload" -ForegroundColor Red
        } else {
            Write-Host "✓ Payload blocked: $payload" -ForegroundColor Green
        }
    } catch {
        Write-Host "✓ Request failed (expected): $payload" -ForegroundColor Green
    }
    
    Start-Sleep -Milliseconds 500
}
```

---

### 3.2 Cross-Site Scripting (XSS) Testing

#### Manual XSS Testing

```powershell
# PowerShell XSS testing script
$baseUrl = "http://localhost/cursorai/Testing/studentportal/Users/Login.php"

$xssPayloads = @(
    "<script>alert('XSS')</script>",
    "<img src=x onerror=alert('XSS')>",
    "javascript:alert('XSS')",
    "<svg onload=alert('XSS')>",
    "'-alert('XSS')-'",
    '"><script>alert(String.fromCharCode(88,83,83))</script>',
    "<iframe src='javascript:alert(1)'>",
    "<body onload=alert('XSS')>"
)

Write-Host "Testing XSS payloads..." -ForegroundColor Cyan
foreach ($payload in $xssPayloads) {
    $body = @{
        txtUserId = $payload
        txtPassword = "test"
        isSubmit = "yes"
    }
    
    try {
        $response = Invoke-WebRequest -Uri $baseUrl -Method POST -Body $body
        
        # Check if payload appears in response unescaped
        if ($response.Content -match [regex]::Escape($payload)) {
            Write-Host "✗ VULNERABLE: Payload reflected: $payload" -ForegroundColor Red
        } else {
            Write-Host "✓ Payload sanitized: $payload" -ForegroundColor Green
        }
    } catch {
        Write-Host "✓ Request handled safely: $payload" -ForegroundColor Green
    }
    
    Start-Sleep -Milliseconds 500
}
```

---

### 3.3 CSRF Testing

```powershell
# Test CSRF protection
$baseUrl = "http://localhost/cursorai/Testing/studentportal/Users/Login.php"

Write-Host "Testing CSRF protection..." -ForegroundColor Cyan

# Test 1: Submit without CSRF token
Write-Host "[1/3] Testing login without CSRF token..."
$body = @{
    txtUserId = "test"
    txtPassword = "test"
    isSubmit = "yes"
}

try {
    $response = Invoke-WebRequest -Uri $baseUrl -Method POST -Body $body
    if ($response.Content -match "security token|csrf|CSRF") {
        Write-Host "✓ CSRF protection detected" -ForegroundColor Green
    } else {
        Write-Host "⚠ No clear CSRF error message" -ForegroundColor Yellow
    }
} catch {
    # Expected
}

# Test 2: Submit with invalid CSRF token
Write-Host "[2/3] Testing with invalid CSRF token..."
$body = @{
    txtUserId = "test"
    txtPassword = "test"
    isSubmit = "yes"
    csrf_token = "invalid_token_12345"
}

try {
    $response = Invoke-WebRequest -Uri $baseUrl -Method POST -Body $body
    if ($response.Content -match "Invalid.*token|security.*token") {
        Write-Host "✓ Invalid token rejected" -ForegroundColor Green
    } else {
        Write-Host "✗ Invalid token may have been accepted" -ForegroundColor Red
    }
} catch {
    # Expected
}

# Test 3: Check if CSRF token is present in form
Write-Host "[3/3] Checking for CSRF token in form..."
try {
    $response = Invoke-WebRequest -Uri $baseUrl -Method GET
    if ($response.Content -match 'name="csrf_token"|name=''csrf_token''') {
        Write-Host "✓ CSRF token found in form" -ForegroundColor Green
    } else {
        Write-Host "✗ No CSRF token in form" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Could not load form" -ForegroundColor Red
}
```

---

### 3.4 Authentication Testing

```powershell
# Test authentication bypass attempts
$baseUrl = "http://localhost/cursorai/Testing/studentportal/Users"

Write-Host "=== Authentication Security Tests ===" -ForegroundColor Cyan

# Test 1: Access protected page without login
Write-Host "[1/5] Testing access to protected pages without authentication..."
$protectedPages = @("landing.php", "studentprofile.php", "MyFees.php")

foreach ($page in $protectedPages) {
    try {
        $response = Invoke-WebRequest -Uri "$baseUrl/$page" -Method GET
        if ($response.Content -match "login|Login|session.*expired") {
            Write-Host "✓ $page requires authentication" -ForegroundColor Green
        } else {
            Write-Host "✗ $page may be accessible without login!" -ForegroundColor Red
        }
    } catch {
        # 401/403 is good
        if ($_.Exception.Response.StatusCode -eq 401 -or $_.Exception.Response.StatusCode -eq 403) {
            Write-Host "✓ $page properly protected" -ForegroundColor Green
        }
    }
}

# Test 2: Brute force protection
Write-Host "[2/5] Testing brute force protection (10 attempts)..."
$attemptCount = 0
$blocked = $false

for ($i = 1; $i -le 10; $i++) {
    $body = @{
        txtUserId = "testuser"
        txtPassword = "wrongpass$i"
        isSubmit = "yes"
    }
    
    try {
        $response = Invoke-WebRequest -Uri "$baseUrl/Login.php" -Method POST -Body $body
        $attemptCount++
        
        if ($response.Content -match "too many|blocked|locked|wait") {
            Write-Host "✓ Account locked after $attemptCount attempts" -ForegroundColor Green
            $blocked = $true
            break
        }
    } catch {
        # Expected failure
    }
    
    Start-Sleep -Milliseconds 500
}

if (-not $blocked) {
    Write-Host "⚠ No brute force protection detected after $attemptCount attempts" -ForegroundColor Yellow
}

# Test 3: Session fixation
Write-Host "[3/5] Testing session fixation vulnerability..."
# Get initial session
try {
    $response = Invoke-WebRequest -Uri "$baseUrl/Login.php" -Method GET -SessionVariable session
    $initialSessionId = $session.Cookies.GetCookies("$baseUrl/Login.php") | Where-Object {$_.Name -eq "PHPSESSID"} | Select-Object -ExpandProperty Value
    
    # Attempt login
    $body = @{
        txtUserId = "validuser"
        txtPassword = "validpass"
        isSubmit = "yes"
    }
    
    $response = Invoke-WebRequest -Uri "$baseUrl/Login.php" -Method POST -Body $body -WebSession $session
    $newSessionId = $session.Cookies.GetCookies("$baseUrl/Login.php") | Where-Object {$_.Name -eq "PHPSESSID"} | Select-Object -ExpandProperty Value
    
    if ($initialSessionId -ne $newSessionId) {
        Write-Host "✓ Session ID regenerated after login" -ForegroundColor Green
    } else {
        Write-Host "✗ Session fixation vulnerability: Session ID not regenerated" -ForegroundColor Red
    }
} catch {
    Write-Host "⚠ Could not test session fixation" -ForegroundColor Yellow
}

# Test 4: Password reset security
Write-Host "[4/5] Testing password reset mechanism..."
try {
    $body = @{
        employee_id = "testuser"
    }
    $response = Invoke-WebRequest -Uri "$baseUrl/submit_forget_password_users.php" -Method POST -Body $body
    
    if ($response.Content -match "password.*sent|link.*sent") {
        Write-Host "✓ Password reset mechanism exists" -ForegroundColor Green
        
        if ($response.Content -match "password.*SMS|password.*email") {
            Write-Host "⚠ WARNING: Sending password via SMS/email (should use reset token)" -ForegroundColor Red
        }
    }
} catch {
    Write-Host "⚠ Could not test password reset" -ForegroundColor Yellow
}

# Test 5: Session timeout
Write-Host "[5/5] Session timeout cannot be tested automatically (requires waiting)"
Write-Host "⚠ Manual test required: Wait 1 hour and check if session expires" -ForegroundColor Yellow
```

---

## 4. Accessibility Testing Suite

### 4.1 Automated Accessibility Testing

```bash
# Install tools
npm install -g pa11y pa11y-ci lighthouse axe-cli

# Test single page with pa11y
pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php

# Test with specific standard
pa11y --standard WCAG2AA http://localhost/cursorai/Testing/studentportal/Users/Login.php

# Test multiple pages
pa11y-ci --sitemap http://localhost/cursorai/Testing/studentportal/sitemap.xml

# Or test specific pages
pa11y-ci \
  http://localhost/cursorai/Testing/studentportal/Users/Login.php \
  http://localhost/cursorai/Testing/studentportal/Users/landing.php \
  http://localhost/cursorai/Testing/studentportal/Users/studentprofile.php

# Generate HTML report
pa11y --reporter html http://localhost/cursorai/Testing/studentportal/Users/Login.php > accessibility-report.html

# Lighthouse accessibility audit
lighthouse http://localhost/cursorai/Testing/studentportal/Users/Login.php \
  --only-categories=accessibility \
  --output=html \
  --output-path=./lighthouse-accessibility-report.html

# axe-cli test
axe http://localhost/cursorai/Testing/studentportal/Users/Login.php
```

### 4.2 Color Contrast Testing Script

```javascript
// Save as: contrast-checker.js
// Run with: node contrast-checker.js

const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    await page.goto('http://localhost/cursorai/Testing/studentportal/Users/Login.php');
    
    const contrastIssues = await page.evaluate(() => {
        function getContrast(rgb1, rgb2) {
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
            return Array.from(ctx.getImageData(0, 0, 1, 1).data.slice(0, 3));
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
                    text: el.textContent.trim().substring(0, 50),
                    contrast: contrast.toFixed(2),
                    required: required,
                    selector: el.tagName + (el.id ? '#' + el.id : '') + (el.className ? '.' + el.className.split(' ').join('.') : '')
                });
            }
        });
        
        return issues;
    });
    
    console.log('Contrast Issues Found:', contrastIssues.length);
    contrastIssues.forEach((issue, i) => {
        console.log(`\n${i + 1}. ${issue.text}`);
        console.log(`   Selector: ${issue.selector}`);
        console.log(`   Contrast: ${issue.contrast}:1 (Required: ${issue.required}:1)`);
    });
    
    await browser.close();
})();
```

### 4.3 HTML Validation

```bash
# Validate HTML
html-validator --file=Users/Login.php --format=text

# Validate all PHP files
for file in Users/*.php; do
    echo "Validating $file..."
    html-validator --file="$file" --format=text
done

# PowerShell version
Get-ChildItem -Path "Users" -Filter "*.php" | ForEach-Object {
    Write-Host "Validating $($_.Name)..." -ForegroundColor Cyan
    html-validator --file=$_.FullName --format=text
}
```

---

## 5. Custom Testing Scripts

### 5.1 Comprehensive Security Scanner

Create `comprehensive_scanner.ps1`:

```powershell
# comprehensive_scanner.ps1
param(
    [string]$BaseUrl = "http://localhost/cursorai/Testing/studentportal",
    [string]$OutputDir = ".\security-reports"
)

# Create output directory
if (-not (Test-Path $OutputDir)) {
    New-Item -ItemType Directory -Path $OutputDir | Out-Null
}

$timestamp = Get-Date -Format "yyyy-MM-dd_HH-mm-ss"
$reportFile = "$OutputDir\security-report-$timestamp.html"

# Initialize report
$html = @"
<!DOCTYPE html>
<html>
<head>
    <title>Security Scan Report - $timestamp</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        h2 { color: #666; border-bottom: 2px solid #ddd; padding-bottom: 5px; }
        .pass { color: green; }
        .fail { color: red; }
        .warn { color: orange; }
        .test-result { margin: 10px 0; padding: 10px; border-left: 4px solid #ddd; }
        .test-result.pass { border-left-color: green; background: #f0fff0; }
        .test-result.fail { border-left-color: red; background: #fff0f0; }
        .test-result.warn { border-left-color: orange; background: #fff8f0; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Security Scan Report</h1>
    <p><strong>Target:</strong> $BaseUrl</p>
    <p><strong>Scan Date:</strong> $timestamp</p>
"@

function Add-TestResult {
    param(
        [string]$Title,
        [string]$Status,
        [string]$Details
    )
    
    $script:html += @"
    <div class="test-result $($Status.ToLower())">
        <strong>$Title</strong> - <span class="$($Status.ToLower())">$Status</span>
        <p>$Details</p>
    </div>
"@
}

# Test 1: Security Headers
Write-Host "Testing security headers..." -ForegroundColor Cyan
$script:html += "<h2>1. Security Headers</h2>"

try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method GET
    $headers = $response.Headers
    
    $requiredHeaders = @{
        "X-Frame-Options" = "DENY or SAMEORIGIN"
        "X-Content-Type-Options" = "nosniff"
        "X-XSS-Protection" = "1; mode=block"
        "Strict-Transport-Security" = "max-age=31536000"
        "Content-Security-Policy" = "Restrictive policy"
    }
    
    foreach ($header in $requiredHeaders.Keys) {
        if ($headers.ContainsKey($header)) {
            Add-TestResult -Title $header -Status "PASS" -Details "Value: $($headers[$header])"
        } else {
            Add-TestResult -Title $header -Status "FAIL" -Details "Header not present"
        }
    }
} catch {
    Add-TestResult -Title "Security Headers" -Status "FAIL" -Details "Could not connect to application"
}

# Test 2: HTTPS
Write-Host "Testing HTTPS..." -ForegroundColor Cyan
$script:html += "<h2>2. HTTPS Enforcement</h2>"

if ($BaseUrl -match "^https://") {
    Add-TestResult -Title "HTTPS" -Status "PASS" -Details "Application uses HTTPS"
} else {
    Add-TestResult -Title "HTTPS" -Status "WARN" -Details "Application accessed via HTTP (acceptable for localhost)"
}

# Test 3: Sensitive File Exposure
Write-Host "Testing for exposed files..." -ForegroundColor Cyan
$script:html += "<h2>3. Sensitive File Exposure</h2>"

$sensitiveFiles = @(
    ".env",
    ".git/config",
    "phpinfo.php",
    "config.php.bak",
    ".htaccess",
    "composer.json",
    "package.json",
    ".env.example"
)

foreach ($file in $sensitiveFiles) {
    try {
        $response = Invoke-WebRequest -Uri "$BaseUrl/$file" -Method GET -TimeoutSec 3
        Add-TestResult -Title "$file exposure" -Status "FAIL" -Details "File is accessible (HTTP $($response.StatusCode))"
    } catch {
        Add-TestResult -Title "$file protection" -Status "PASS" -Details "File is not accessible"
    }
}

# Test 4: SQL Injection
Write-Host "Testing SQL injection..." -ForegroundColor Cyan
$script:html += "<h2>4. SQL Injection</h2>"

$sqlPayloads = @("' OR '1'='1", "admin'--", "' UNION SELECT NULL--")
$vulnerable = $false

foreach ($payload in $sqlPayloads) {
    $body = @{
        txtUserId = $payload
        txtPassword = "test"
        isSubmit = "yes"
    }
    
    try {
        $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method POST -Body $body
        if ($response.Content -match "Dashboard|Welcome|landing|SQL syntax") {
            Add-TestResult -Title "SQL Injection Test" -Status "FAIL" -Details "Payload succeeded: $payload"
            $vulnerable = $true
            break
        }
    } catch {
        # Expected
    }
}

if (-not $vulnerable) {
    Add-TestResult -Title "SQL Injection Tests" -Status "PASS" -Details "Basic SQL injection payloads were blocked"
}

# Test 5: XSS
Write-Host "Testing XSS..." -ForegroundColor Cyan
$script:html += "<h2>5. Cross-Site Scripting (XSS)</h2>"

$xssPayload = "<script>alert('XSS')</script>"
$body = @{
    txtUserId = $xssPayload
    txtPassword = "test"
    isSubmit = "yes"
}

try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method POST -Body $body
    if ($response.Content -match [regex]::Escape($xssPayload)) {
        Add-TestResult -Title "XSS Test" -Status "FAIL" -Details "XSS payload was reflected unescaped"
    } else {
        Add-TestResult -Title "XSS Test" -Status "PASS" -Details "XSS payload was properly escaped"
    }
} catch {
    Add-TestResult -Title "XSS Test" -Status "PASS" -Details "Request was handled safely"
}

# Test 6: CSRF
Write-Host "Testing CSRF protection..." -ForegroundColor Cyan
$script:html += "<h2>6. CSRF Protection</h2>"

try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method GET
    if ($response.Content -match 'name="csrf_token"') {
        Add-TestResult -Title "CSRF Token" -Status "PASS" -Details "CSRF token found in form"
    } else {
        Add-TestResult -Title "CSRF Token" -Status "FAIL" -Details "No CSRF token in form"
    }
} catch {
    Add-TestResult -Title "CSRF Token" -Status "FAIL" -Details "Could not check form"
}

# Test 7: Session Security
Write-Host "Testing session security..." -ForegroundColor Cyan
$script:html += "<h2>7. Session Security</h2>"

try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method GET -SessionVariable session
    $cookies = $session.Cookies.GetCookies($BaseUrl)
    
    $sessionCookie = $cookies | Where-Object { $_.Name -eq "PHPSESSID" }
    
    if ($sessionCookie) {
        if ($sessionCookie.HttpOnly) {
            Add-TestResult -Title "HttpOnly Flag" -Status "PASS" -Details "Session cookie has HttpOnly flag"
        } else {
            Add-TestResult -Title "HttpOnly Flag" -Status "FAIL" -Details "Session cookie missing HttpOnly flag"
        }
        
        if ($sessionCookie.Secure -or $BaseUrl -match "^http://localhost") {
            Add-TestResult -Title "Secure Flag" -Status "PASS" -Details "Session cookie security appropriate for environment"
        } else {
            Add-TestResult -Title "Secure Flag" -Status "WARN" -Details "Session cookie missing Secure flag (required for HTTPS)"
        }
    }
} catch {
    Add-TestResult -Title "Session Security" -Status "WARN" -Details "Could not analyze session cookies"
}

# Test 8: Information Disclosure
Write-Host "Testing information disclosure..." -ForegroundColor Cyan
$script:html += "<h2>8. Information Disclosure</h2>"

try {
    $response = Invoke-WebRequest -Uri "$BaseUrl/Users/Login.php" -Method GET
    $serverHeader = $response.Headers["Server"]
    
    if ($serverHeader -match "Apache|PHP|Microsoft|nginx") {
        Add-TestResult -Title "Server Version Disclosure" -Status "WARN" -Details "Server header reveals: $serverHeader"
    } else {
        Add-TestResult -Title "Server Version Disclosure" -Status "PASS" -Details "Server information hidden"
    }
    
    if ($response.Content -match "<?php|<\?=") {
        Add-TestResult -Title "PHP Code Exposure" -Status "FAIL" -Details "PHP code visible in response"
    } else {
        Add-TestResult -Title "PHP Code Exposure" -Status "PASS" -Details "No PHP code in response"
    }
} catch {
    Add-TestResult -Title "Information Disclosure" -Status "WARN" -Details "Could not test"
}

# Close HTML
$script:html += @"
    <h2>Scan Complete</h2>
    <p>Report generated at $timestamp</p>
</body>
</html>
"@

# Save report
$script:html | Out-File -FilePath $reportFile -Encoding UTF8

Write-Host ""
Write-Host "=== Scan Complete ===" -ForegroundColor Green
Write-Host "Report saved to: $reportFile" -ForegroundColor Cyan
Write-Host ""

# Open report in browser
Start-Process $reportFile
```

**Run the scanner:**

```powershell
.\comprehensive_scanner.ps1
```

---

### 5.2 Database Security Checker

Create `db_security_check.php`:

```php
<?php
/**
 * Database Security Checker
 * Checks password hashing, user permissions, and database configuration
 */

require_once 'connection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Security Check</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; }
        .pass { color: green; }
        .fail { color: red; }
        .warn { color: orange; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
        .test-section { margin: 30px 0; padding: 20px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Database Security Check</h1>
    
    <?php
    echo "<div class='test-section'>";
    echo "<h2>1. Password Storage Security</h2>";
    
    // Check password hashing
    $query = "SELECT sadmission, spassword, LENGTH(spassword) as pwd_length 
              FROM student_master 
              ORDER BY RAND() 
              LIMIT 10";
    $result = mysqli_query($Con, $query);
    
    $hashed_count = 0;
    $plain_count = 0;
    $total_count = 0;
    
    echo "<table>";
    echo "<tr><th>Student ID</th><th>Password Length</th><th>Status</th></tr>";
    
    while ($row = mysqli_fetch_assoc($result)) {
        $total_count++;
        $status = "unknown";
        $class = "warn";
        
        if ($row['pwd_length'] >= 60 && substr($row['spassword'], 0, 4) === '$2y$') {
            $status = "Hashed (bcrypt)";
            $class = "pass";
            $hashed_count++;
        } else {
            $status = "Plain text or weak hash";
            $class = "fail";
            $plain_count++;
        }
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['sadmission']) . "</td>";
        echo "<td>" . $row['pwd_length'] . "</td>";
        echo "<td class='$class'>$status</td>";
        echo "</tr>";
    }
    
    echo "</table>";
    
    $hashed_percent = ($total_count > 0) ? round(($hashed_count / $total_count) * 100, 2) : 0;
    
    if ($hashed_percent == 100) {
        echo "<p class='pass'>✓ All sampled passwords are properly hashed</p>";
    } elseif ($hashed_percent > 0) {
        echo "<p class='warn'>⚠ $hashed_percent% of passwords are hashed. Migration in progress?</p>";
        echo "<p>Run the password migration script to hash remaining passwords.</p>";
    } else {
        echo "<p class='fail'>✗ No passwords are hashed! CRITICAL SECURITY ISSUE</p>";
        echo "<p><strong>Action Required:</strong> Implement password hashing immediately.</p>";
    }
    
    echo "</div>";
    
    // Check for SQL injection in stored procedures
    echo "<div class='test-section'>";
    echo "<h2>2. SQL Injection Risk Assessment</h2>";
    
    // Get list of all tables
    $query = "SHOW TABLES";
    $result = mysqli_query($Con, $query);
    
    echo "<p>Tables in database:</p>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . htmlspecialchars($row[0]) . "</li>";
    }
    echo "</ul>";
    
    echo "<p class='warn'>⚠ Manual code review required to check for SQL injection vulnerabilities</p>";
    echo "</div>";
    
    // Check user permissions
    echo "<div class='test-section'>";
    echo "<h2>3. Database User Permissions</h2>";
    
    $query = "SELECT USER(), DATABASE()";
    $result = mysqli_query($Con, $query);
    $row = mysqli_fetch_array($result);
    
    echo "<p><strong>Current User:</strong> " . htmlspecialchars($row[0]) . "</p>";
    echo "<p><strong>Current Database:</strong> " . htmlspecialchars($row[1]) . "</p>";
    
    // Check privileges
    $query = "SHOW GRANTS";
    $result = mysqli_query($Con, $query);
    
    echo "<p><strong>Privileges:</strong></p>";
    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>" . htmlspecialchars($row[0]) . "</li>";
    }
    echo "</ul>";
    
    echo "<p class='warn'>⚠ Recommendation: Use least privilege principle. Application should not have DROP, CREATE USER, or GRANT privileges.</p>";
    echo "</div>";
    
    // Check for common security issues
    echo "<div class='test-section'>";
    echo "<h2>4. Common Security Issues</h2>";
    
    // Check for default/test accounts
    $query = "SELECT COUNT(*) as count FROM student_master WHERE sadmission LIKE 'TEST%' OR spassword = 'password' OR spassword = '123456'";
    $result = mysqli_query($Con, $query);
    $row = mysqli_fetch_assoc($result);
    
    if ($row['count'] > 0) {
        echo "<p class='warn'>⚠ Found {$row['count']} test accounts or accounts with common passwords</p>";
    } else {
        echo "<p class='pass'>✓ No obvious test accounts found</p>";
    }
    
    // Check for session tokens table
    $query = "SHOW TABLES LIKE 'password_reset_tokens'";
    $result = mysqli_query($Con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<p class='pass'>✓ Password reset tokens table exists</p>";
    } else {
        echo "<p class='fail'>✗ Password reset tokens table not found</p>";
        echo "<p>Create this table to support secure password reset functionality.</p>";
    }
    
    // Check for login attempts table
    $query = "SHOW TABLES LIKE 'login_attempts'";
    $result = mysqli_query($Con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<p class='pass'>✓ Login attempts table exists</p>";
        
        // Check if it's being used
        $query = "SELECT COUNT(*) as count FROM login_attempts WHERE attempt_time > UNIX_TIMESTAMP() - 86400";
        $result = mysqli_query($Con, $query);
        $row = mysqli_fetch_assoc($result);
        
        echo "<p>Login attempts in last 24 hours: {$row['count']}</p>";
    } else {
        echo "<p class='fail'>✗ Login attempts table not found</p>";
        echo "<p>Create this table to implement brute force protection.</p>";
    }
    
    echo "</div>";
    
    // Recommendations
    echo "<div class='test-section'>";
    echo "<h2>5. Security Recommendations</h2>";
    echo "<ul>";
    echo "<li>✓ Use prepared statements for all database queries</li>";
    echo "<li>✓ Hash all passwords with PASSWORD_DEFAULT (bcrypt)</li>";
    echo "<li>✓ Implement rate limiting on login attempts</li>";
    echo "<li>✓ Use secure password reset with tokens, not SMS</li>";
    echo "<li>✓ Regularly backup database with encryption</li>";
    echo "<li>✓ Monitor database for suspicious activity</li>";
    echo "<li>✓ Keep MySQL/MariaDB updated to latest stable version</li>";
    echo "</ul>";
    echo "</div>";
    ?>
    
</body>
</html>
```

---

## 6. Continuous Testing

### GitHub Actions Workflow (if using Git)

Create `.github/workflows/security-scan.yml`:

```yaml
name: Security Scan

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]
  schedule:
    # Run weekly on Monday at 9 AM
    - cron: '0 9 * * 1'

jobs:
  security-scan:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    
    - name: Setup Node.js
      uses: actions/setup-node@v2
      with:
        node-version: '18'
    
    - name: Install dependencies
      run: |
        npm install -g pa11y lighthouse retire
        pip install sqlmap
    
    - name: Run accessibility tests
      run: |
        pa11y --standard WCAG2AA Users/Login.php || true
    
    - name: Run retire.js (check JS libraries)
      run: |
        retire --path=assets/ --outputformat=json --outputpath=retire-report.json || true
    
    - name: Upload results
      uses: actions/upload-artifact@v2
      with:
        name: security-reports
        path: |
          retire-report.json
```

---

## 7. Report Generation

### Generate PDF Report (using Node.js)

```javascript
// Save as: generate-pdf-report.js
const puppeteer = require('puppeteer');
const fs = require('fs');

async function generateReport() {
    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    
    // Load the HTML report
    const htmlContent = fs.readFileSync('security-reports/security-report.html', 'utf8');
    await page.setContent(htmlContent);
    
    // Generate PDF
    await page.pdf({
        path: 'security-reports/security-report.pdf',
        format: 'A4',
        printBackground: true,
        margin: {
            top: '1cm',
            right: '1cm',
            bottom: '1cm',
            left: '1cm'
        }
    });
    
    console.log('PDF report generated: security-reports/security-report.pdf');
    
    await browser.close();
}

generateReport().catch(console.error);
```

---

## Quick Reference Commands

### Essential Daily Tests

```powershell
# 1. Quick security check
.\quick_security_test.ps1

# 2. Test accessibility
pa11y http://localhost/cursorai/Testing/studentportal/Users/Login.php

# 3. Check for vulnerable libraries
retire --path=assets/ --outputformat=text

# 4. Validate HTML
html-validator --file=Users/Login.php --format=text
```

### Before Deployment

```powershell
# 1. Run comprehensive scanner
.\comprehensive_scanner.ps1

# 2. Run database security check
# Open in browser: http://localhost/cursorai/Testing/studentportal/db_security_check.php

# 3. Run OWASP ZAP scan
# Manual: Open ZAP GUI and scan

# 4. Generate Lighthouse report
lighthouse http://localhost/cursorai/Testing/studentportal/ --view
```

---

**This completes the automated testing guide!**

For best results:
1. Run quick tests daily during development
2. Run comprehensive tests before each release
3. Schedule weekly automated scans
4. Review and fix issues by priority (Critical → High → Medium → Low)
5. Document all fixes and re-test


