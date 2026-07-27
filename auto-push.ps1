# Auto Push Script - Cluckory
# Script ini memantau perubahan file dan otomatis push ke GitHub

$projectPath = "c:\laragon\www\cluckory"
$debounceSeconds = 5  # Tunggu berapa detik setelah perubahan terakhir sebelum push

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Auto Push Watcher - Cluckory" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Memantau perubahan di: $projectPath" -ForegroundColor Yellow
Write-Host "Tekan Ctrl+C untuk berhenti." -ForegroundColor Yellow
Write-Host ""

# Buat FileSystemWatcher
$watcher = New-Object System.IO.FileSystemWatcher
$watcher.Path = $projectPath
$watcher.IncludeSubdirectories = $true
$watcher.EnableRaisingEvents = $true

# Filter file/folder yang diabaikan
$ignoredPatterns = @(
    '\.git',
    'node_modules',
    'vendor',
    'storage\\framework',
    'storage\\logs',
    'bootstrap\\cache',
    '\.env$',
    '\.log$'
)

$lastChangeTime = [DateTime]::MinValue
$timer = $null

$action = {
    $path = $Event.SourceEventArgs.FullPath
    $changeType = $Event.SourceEventArgs.ChangeType

    # Cek apakah file yang berubah termasuk yang diabaikan
    foreach ($pattern in $using:ignoredPatterns) {
        if ($path -match $pattern) { return }
    }

    $script:lastChangeTime = [DateTime]::Now
    Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Perubahan terdeteksi: $changeType - $path" -ForegroundColor DarkGray
}

# Daftarkan event handlers
$handlers = @(
    Register-ObjectEvent $watcher "Changed" -Action $action
    Register-ObjectEvent $watcher "Created" -Action $action
    Register-ObjectEvent $watcher "Deleted" -Action $action
    Register-ObjectEvent $watcher "Renamed" -Action $action
)

try {
    while ($true) {
        Start-Sleep -Seconds 1

        if ($script:lastChangeTime -ne [DateTime]::MinValue) {
            $secondsSinceChange = ([DateTime]::Now - $script:lastChangeTime).TotalSeconds

            if ($secondsSinceChange -ge $debounceSeconds) {
                $script:lastChangeTime = [DateTime]::MinValue

                Write-Host ""
                Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Memulai auto-push..." -ForegroundColor Green

                Set-Location $projectPath

                # Cek apakah ada perubahan
                $status = git status --porcelain
                if ($status) {
                    $commitMsg = "auto: update $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
                    git add .
                    git commit -m $commitMsg
                    git push origin main

                    if ($LASTEXITCODE -eq 0) {
                        Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Berhasil di-push ke GitHub!" -ForegroundColor Green
                    } else {
                        Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Gagal push. Cek koneksi/autentikasi GitHub." -ForegroundColor Red
                    }
                } else {
                    Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Tidak ada perubahan untuk di-push." -ForegroundColor DarkGray
                }
                Write-Host ""
            }
        }
    }
} finally {
    # Cleanup saat script dihentikan
    $handlers | ForEach-Object { Unregister-Event -SourceIdentifier $_.Name }
    $watcher.Dispose()
    Write-Host "Auto Push Watcher dihentikan." -ForegroundColor Yellow
}
