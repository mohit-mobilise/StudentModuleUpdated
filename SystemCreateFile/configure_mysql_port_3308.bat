@echo off
REM Configure XAMPP MySQL to use port 3308
REM Run this script as Administrator

echo ========================================
echo XAMPP MySQL Port Configuration Tool
echo Configuring MySQL to use port 3308
echo ========================================
echo.

REM Check if running as Administrator
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo ERROR: This script must be run as Administrator!
    echo Right-click this file and select "Run as Administrator"
    pause
    exit /b 1
)

echo Step 1: Stopping conflicting MySQL processes...
taskkill /F /IM mysqld.exe 2>nul
net stop MySQL80 2>nul
net stop MySQL57 2>nul
net stop MySQL 2>nul
timeout /t 2 /nobreak >nul

echo Step 2: Backing up current my.ini...
if exist "C:\xampp\mysql\bin\my.ini" (
    copy "C:\xampp\mysql\bin\my.ini" "C:\xampp\mysql\bin\my.ini.backup" >nul
    echo Backup created: C:\xampp\mysql\bin\my.ini.backup
)

echo Step 3: Configuring MySQL to use port 3308...
echo.

REM Create a PowerShell script to modify my.ini
powershell -Command "$content = Get-Content 'C:\xampp\mysql\bin\my.ini' -Raw; $content = $content -replace '(?m)^port\s*=\s*\d+', 'port = 3308'; if ($content -notmatch 'port\s*=\s*3308') { $content = $content -replace '(\[mysqld\])', '$1`nport = 3308' }; Set-Content 'C:\xampp\mysql\bin\my.ini' -Value $content -NoNewline"

if %errorLevel% equ 0 (
    echo [SUCCESS] MySQL configured to use port 3308
    echo.
    echo Configuration file: C:\xampp\mysql\bin\my.ini
    echo Port changed to: 3308
) else (
    echo [ERROR] Failed to configure port. Please check file permissions.
    pause
    exit /b 1
)

echo.
echo ========================================
echo Configuration Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Open XAMPP Control Panel
echo 2. Click "Start" on MySQL
echo 3. MySQL should now start on port 3308
echo.
echo To verify, check the port in XAMPP Control Panel
echo (should show port 3308 instead of 3306)
echo.
pause

