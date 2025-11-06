@echo off
REM Database Import Batch File
REM This will import the SQL file using MySQL command line

echo ========================================
echo Database Import Script
echo ========================================
echo.
echo Database: schoolerpbeta
echo File: schoolerpbeta_MY03112025.sql
echo.

cd /d "C:\xampp\mysql\bin"

echo Checking if database exists...
mysql.exe -u root -P 3308 -e "USE schoolerpbeta;" 2>nul
if errorlevel 1 (
    echo Database schoolerpbeta does not exist!
    echo Creating database...
    mysql.exe -u root -P 3308 -e "CREATE DATABASE schoolerpbeta CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
)

echo.
echo Starting import...
echo This may take 10-20 minutes for a 702 MB file...
echo Please be patient and do NOT close this window!
echo.

mysql.exe -u root -P 3308 schoolerpbeta < "C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql" 2>&1

if errorlevel 1 (
    echo.
    echo ========================================
    echo ERROR: Import failed!
    echo Trying with default port...
    echo ========================================
    mysql.exe -u root schoolerpbeta < "C:\Users\mohit.yadav\Documents\Mohit Yadav\DB Backup\schoolerpbeta_MY03112025.sql" 2>&1
)

if errorlevel 1 (
    echo.
    echo ========================================
    echo IMPORT FAILED
    echo Please check the error messages above
    echo ========================================
) else (
    echo.
    echo ========================================
    echo IMPORT COMPLETED SUCCESSFULLY!
    echo ========================================
    echo.
    echo You can now check phpMyAdmin to verify the tables were imported.
)

echo.
pause


