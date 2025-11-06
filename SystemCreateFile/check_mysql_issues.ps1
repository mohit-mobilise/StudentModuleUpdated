# XAMPP MySQL Diagnostic PowerShell Script
# Run this script as Administrator to diagnose MySQL issues

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "XAMPP MySQL Diagnostic Tool" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
if (-not $isAdmin) {
    Write-Host "WARNING: Not running as Administrator. Some checks may not work." -ForegroundColor Yellow
    Write-Host ""
}

# 1. Check Port Status
Write-Host "1. Checking MySQL Ports (3306, 3308)..." -ForegroundColor Green
$ports = @(3306, 3308)
foreach ($port in $ports) {
    $connection = netstat -ano | findstr ":$port"
    if ($connection) {
        Write-Host "   [OK] Port $port is in use (MySQL may be running)" -ForegroundColor Green
    } else {
        Write-Host "   [X] Port $port is not in use (MySQL may be stopped)" -ForegroundColor Red
    }
}
Write-Host ""

# 2. Check for Port Conflicts
Write-Host "2. Checking for Port Conflicts..." -ForegroundColor Green
foreach ($port in $ports) {
    $connections = netstat -ano | findstr ":$port"
    if ($connections) {
        Write-Host "   ⚠ Port $port is in use by:" -ForegroundColor Yellow
        $connections | ForEach-Object {
            Write-Host "     $_" -ForegroundColor Yellow
        }
        # Extract PID and show process
        $pids = ($connections | Select-String -Pattern '\s+(\d+)$').Matches | ForEach-Object { $_.Groups[1].Value } | Select-Object -Unique
        foreach ($pid in $pids) {
            try {
                $process = Get-Process -Id $pid -ErrorAction SilentlyContinue
                if ($process) {
                    Write-Host "     Process: $($process.ProcessName) (PID: $pid)" -ForegroundColor Yellow
                }
            } catch {
                Write-Host "     Process ID: $pid (could not retrieve name)" -ForegroundColor Yellow
            }
        }
    } else {
        Write-Host "   [OK] Port $port is available" -ForegroundColor Green
    }
}
Write-Host ""

# 3. Check MySQL Windows Services
Write-Host "3. Checking MySQL Windows Services..." -ForegroundColor Green
$mysqlServices = @("MySQL", "MySQL80", "MySQL57", "MySQL5.7", "MySQL56")
$foundServices = $false
foreach ($serviceName in $mysqlServices) {
    $service = Get-Service -Name $serviceName -ErrorAction SilentlyContinue
    if ($service) {
        $foundServices = $true
        $status = if ($service.Status -eq "Running") { "RUNNING" } else { $service.Status }
        $color = if ($service.Status -eq "Running") { "Yellow" } else { "Green" }
        Write-Host "   [WARNING] Found service: $serviceName - Status: $status" -ForegroundColor $color
        Write-Host "            This may conflict with XAMPP MySQL!" -ForegroundColor Yellow
    }
}
if (-not $foundServices) {
    Write-Host "   [OK] No conflicting MySQL services found" -ForegroundColor Green
}
Write-Host ""

# 4. Check XAMPP MySQL Path
Write-Host "4. Checking XAMPP MySQL Installation..." -ForegroundColor Green
$xamppPath = "C:\xampp"
$mysqlPath = Join-Path $xamppPath "mysql"
$dataPath = Join-Path $mysqlPath "data"
$errorLogPath = Join-Path $dataPath "mysql_error.log"
$myIniPath = Join-Path $mysqlPath "my.ini"

if ($mysqlPath -and (Test-Path $mysqlPath)) {
    Write-Host "   [OK] MySQL directory found: $mysqlPath" -ForegroundColor Green
} else {
    Write-Host "   [X] MySQL directory not found: $mysqlPath" -ForegroundColor Red
    $mysqlPath = $null
}

if ($mysqlPath) {
    if (Test-Path $dataPath) {
        Write-Host "   [OK] Data directory found: $dataPath" -ForegroundColor Green
    } else {
        Write-Host "   [X] Data directory not found: $dataPath" -ForegroundColor Red
    }

    if (Test-Path $myIniPath) {
        Write-Host "   [OK] Configuration file found: $myIniPath" -ForegroundColor Green
    } else {
        Write-Host "   [X] Configuration file not found: $myIniPath" -ForegroundColor Red
    }

    if (Test-Path $errorLogPath) {
        Write-Host "   [OK] Error log found: $errorLogPath" -ForegroundColor Green
        Write-Host ""
        Write-Host "   Last 10 lines from error log:" -ForegroundColor Cyan
        Get-Content $errorLogPath -Tail 10 | ForEach-Object {
            Write-Host "   $_" -ForegroundColor Gray
        }
    } else {
        Write-Host "   [WARNING] Error log not found: $errorLogPath" -ForegroundColor Yellow
    }
}
Write-Host ""

# 5. Check Disk Space
Write-Host "5. Checking Disk Space..." -ForegroundColor Green
$drive = (Get-Item $xamppPath).PSDrive.Name
$driveInfo = Get-PSDrive $drive
$freeSpaceGB = [math]::Round($driveInfo.Free / 1GB, 2)
$usedSpaceGB = [math]::Round(($driveInfo.Used / 1GB), 2)
Write-Host "   Drive $drive`: Free: $freeSpaceGB GB, Used: $usedSpaceGB GB" -ForegroundColor Cyan
if ($freeSpaceGB -lt 1) {
    Write-Host "   [WARNING] Low disk space! MySQL may fail to start." -ForegroundColor Yellow
} else {
    Write-Host "   [OK] Sufficient disk space available" -ForegroundColor Green
}
Write-Host ""

# 6. Recommendations
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "RECOMMENDATIONS" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$recommendations = @()

# Check if we found issues
if ($foundServices) {
    $recommendations += "1. Stop conflicting MySQL services:"
    $recommendations += "   net stop MySQL"
    $recommendations += "   net stop MySQL80"
    $recommendations += "   (Then restart XAMPP MySQL)"
    $recommendations += ""
}

$recommendations += "2. Check XAMPP Control Panel:"
$recommendations += "   - Ensure MySQL shows as 'Running' (green)"
$recommendations += "   - Click 'Logs' button to see MySQL logs"
$recommendations += ""

$recommendations += "3. If MySQL keeps shutting down:"
$recommendations += "   - Check the error log shown above"
$recommendations += "   - Common fixes:"
$recommendations += "     * Stop conflicting services (see above)"
$recommendations += "     * Check port conflicts"
$recommendations += "     * Restart MySQL from XAMPP Control Panel"
$recommendations += "     * Check antivirus/firewall settings"
$recommendations += ""

$recommendations += "4. For more detailed help, see:"
$recommendations += "   - MYSQL_TROUBLESHOOTING.md"
$recommendations += "   - Run: mysql_diagnostic.php in browser"

$recommendations | ForEach-Object {
    Write-Host $_ -ForegroundColor White
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Diagnostic Complete!" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan

