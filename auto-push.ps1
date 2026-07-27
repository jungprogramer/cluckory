# Auto Push Script - Cluckory
# Script ini memantau perubahan via git status dan otomatis push ke GitHub

$projectPath = "c:\laragon\www\cluckory"
$pollIntervalSeconds = 3  # Cek setiap 3 detik

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  Auto Push Watcher - Cluckory" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Memantau: $projectPath" -ForegroundColor Yellow
Write-Host "Tekan Ctrl+C untuk berhenti." -ForegroundColor Yellow
Write-Host ""

Set-Location $projectPath

$lastHash = ""

try {
    while ($true) {
        Start-Sleep -Seconds $pollIntervalSeconds

        # Ambil status git sekarang
        $currentStatus = git status --porcelain 2>$null
        $currentHash = ($currentStatus | Out-String).Trim()

        # Jika ada perubahan dan berbeda dari sebelumnya
        if ($currentHash -ne "" -and $currentHash -ne $lastHash) {
            $lastHash = $currentHash

            Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Perubahan terdeteksi, menunggu selesai..." -ForegroundColor Yellow
            Start-Sleep -Seconds 2  # Tunggu agar file selesai ditulis

            # Re-check apakah masih ada perubahan
            $finalStatus = (git status --porcelain 2>$null | Out-String).Trim()
            if ($finalStatus -ne "") {
                Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Memulai auto-push..." -ForegroundColor Green

                git add .
                $commitMsg = "auto: update $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')"
                git commit -m $commitMsg

                if ($LASTEXITCODE -eq 0) {
                    git push origin main
                    if ($LASTEXITCODE -eq 0) {
                        Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Berhasil di-push ke GitHub!" -ForegroundColor Green
                        $lastHash = ""  # Reset agar siap deteksi berikutnya
                    } else {
                        Write-Host "[$(Get-Date -Format 'HH:mm:ss')] Gagal push. Cek koneksi/auth GitHub." -ForegroundColor Red
                    }
                }
                Write-Host ""
            }
        }
    }
} finally {
    Write-Host "Auto Push Watcher dihentikan." -ForegroundColor Yellow
}
