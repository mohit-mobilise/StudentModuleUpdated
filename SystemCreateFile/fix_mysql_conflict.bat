@echo off
REM Fix XAMPP MySQL Conflict - Stop Windows MySQL Services
REM Run this script as Administrator

echo ========================================
echo XAMPP MySQL Conflict Fix
echo ========================================
echo.
echo This script will stop Windows MySQL services that conflict with XAMPP
echo.
pause

echo Stopping MySQL services...
net stop MySQL 2>nul
net stop MySQL80 2>nul
net stop MySQL57 2>nul
net stop MySQL5.7 2>nul
net stop MySQL56 2>nul

echo.
echo ========================================
echo Done!
echo ========================================
echo.
echo Please do the following:
echo 1. Open XAMPP Control Panel
echo 2. Click "Stop" on MySQL (if running)
echo 3. Wait 5 seconds
echo 4. Click "Start" on MySQL
echo.
echo The MySQL service has been stopped. XAMPP MySQL should now work.
echo.
pause

