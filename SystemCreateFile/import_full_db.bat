@echo off
REM Full Database Import Script
REM This will import the complete 702 MB SQL file

echo ========================================
echo    FULL DATABASE IMPORT
echo ========================================
echo.
echo Database: schoolerpbeta
echo File: schoolerpbeta_MY03112025.sql
echo Size: 702 MB
echo.
echo This may take 15-20 minutes...
echo DO NOT CLOSE THIS WINDOW!
echo.
echo ========================================
echo.

REM Check if MySQL is accessible on port 3308
cd C:\xampp\mysql\bin

echo [1/4] Checking MySQL connection on port 3308...
mysql.exe -u root -P 3308 -e "SELECT 1" 2>nul
if %errorlevel% equ 0 (
    echo [OK] Connected to MySQL on port 3308
    set MYSQL_PORT=3308
    goto :import
)

echo [WARN] Port 3308 not available, trying port 3306...
mysql.exe -u root -e "SELECT 1" 2>nul
if %errorlevel% equ 0 (
    echo [OK] Connected to MySQL on port 3306
    set MYSQL_PORT=3306
    goto :import
)

echo [ERROR] Cannot connect to MySQL!
echo Please make sure MySQL is running in XAMPP Control Panel.
pause
exit /b 1

:import
echo.
echo [2/4] Creating database if not exists...
if %MYSQL_PORT% equ 3308 (
    mysql.exe -u root -P 3308 -e "CREATE DATABASE IF NOT EXISTS schoolerpbeta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
) else (
    mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS schoolerpbeta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
)

echo.
echo [3/4] Starting full database import...
echo This will take approximately 15-20 minutes for 702 MB file.
echo Please wait...
echo.

set SQL_FILE="C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql"

if not exist %SQL_FILE% (
    echo [ERROR] SQL file not found at:
    echo %SQL_FILE%
    echo.
    echo Please verify the file path is correct.
    pause
    exit /b 1
)

if %MYSQL_PORT% equ 3308 (
    mysql.exe -u root -P 3308 schoolerpbeta < %SQL_FILE%
) else (
    mysql.exe -u root schoolerpbeta < %SQL_FILE%
)

if %errorlevel% equ 0 (
    echo.
    echo [4/4] Verifying import...
    echo.
    if %MYSQL_PORT% equ 3308 (
        mysql.exe -u root -P 3308 -e "USE schoolerpbeta; SELECT COUNT(*) as 'Total Tables' FROM information_schema.TABLES WHERE table_schema = 'schoolerpbeta';"
    ) else (
        mysql.exe -u root -e "USE schoolerpbeta; SELECT COUNT(*) as 'Total Tables' FROM information_schema.TABLES WHERE table_schema = 'schoolerpbeta';"
    )
    
    echo.
    echo ========================================
    echo    IMPORT COMPLETED SUCCESSFULLY!
    echo ========================================
    echo.
    echo The database has been imported.
    echo You can now verify it in phpMyAdmin or run check_database.php
    echo.
) else (
    echo.
    echo [ERROR] Import failed!
    echo Please check the error messages above.
    echo.
    echo You can try:
    echo 1. Open phpMyAdmin and import manually
    echo 2. Run full_database_import.php in browser
    echo 3. Check if file path is correct
    echo.
)

pause

