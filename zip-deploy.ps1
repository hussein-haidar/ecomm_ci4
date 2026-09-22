<# 
.SYNOPSIS
    Script otomatis zip project CodeIgniter 4 untuk hosting cPanel
    Membuat 2 zip: public_html.zip dan ci4app.zip

.DESCRIPTION
    Struktur hasil:
    - public_html.zip  -> isi folder public/ (document root)
    - ci4app.zip       -> semua file lain (app, system, vendor, writable, .env, dll)

.NOTES
    Jalankan di folder root project (C:\xampp\htdocs\ecomm_ci4)
    PowerShell: .\zip-deploy.ps1
#>

param(
    [string]$ProjectPath = "C:\xampp\htdocs\ecomm_ci4",
    [string]$OutputPath = "C:\xampp\htdocs\deploy_output",
    [switch]$IncludeVendor = $true,
    [switch]$CleanOutput = $true
)

# ===========================================
# KONFIGURASI FILE/FOLDER YANG DIEXCLUDE
# ===========================================
$ExcludePatterns = @(
    # Version control
    ".git*",
    ".github*",
    ".gitignore",
    
    # Testing
    "tests*",
    "phpunit.xml*",
    "phpcs.xml*",
    
    # IDE/Editor
    ".vscode*",
    ".idea*",
    "*.swp",
    "*.swo",
    "*~",
    
    # OS
    "Thumbs.db",
    "Desktop.ini",
    ".DS_Store",
    
    # Logs & Cache (local)
    "writable/logs/*",
    "writable/cache/*",
    "writable/session/*",
    "writable/backup/*",
    
    # Uploads (hapus file user, keep folder structure)
    "writable/uploads/*",
    "!writable/uploads/.htaccess",
    "!writable/uploads/index.html",
    
    # Documentation
    "README*",
    "LICENSE*",
    "CHANGELOG*",
    "CONTRIBUTING*",
    
    # Package managers (node)
    "package*.json",
    "node_modules*",
    "yarn.lock",
    "webpack.mix.js",
    
    # Deployment scripts
    "zip-deploy.ps1",
    "deploy.sh",
    ".env.production.txt",
    
    # SQL dumps
    "sql/*.sql",
    "database/*.sql",
    
    # Env files (akan diganti di server)
    ".env",
    ".env.*",
    "!env"
)

# ===========================================
# FOLDER YANG HARUS ADA (BUAT KALAU KOSONG)
# ===========================================
$RequiredWritableFolders = @(
    "writable/cache",
    "writable/logs", 
    "writable/session",
    "writable/uploads",
    "writable/debugbar"
)

# ===========================================
# MAIN SCRIPT
# ===========================================
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "  CI4 DEPLOY ZIP GENERATOR" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""

# Validasi path
if (-not (Test-Path $ProjectPath)) {
    Write-Error "Project path tidak ditemukan: $ProjectPath"
    exit 1
}

Set-Location $ProjectPath

# Cek composer vendor
if ($IncludeVendor -and -not (Test-Path "vendor/autoload.php")) {
    Write-Warning "vendor/autoload.php tidak ditemukan!"
    Write-Host "Menjalankan composer install --no-dev --optimize-autoloader..." -ForegroundColor Yellow
    & composer install --no-dev --optimize-autoloader
    if ($LASTEXITCODE -ne 0) {
        Write-Error "Composer install gagal!"
        exit 1
    }
}

# Buat folder output
if ($CleanOutput -and (Test-Path $OutputPath)) {
    Write-Host "Membersihkan folder output lama..." -ForegroundColor Yellow
    Remove-Item -Path $OutputPath -Recurse -Force -ErrorAction SilentlyContinue
}
New-Item -Path $OutputPath -ItemType Directory -Force | Out-Null

# Pastikan writable folders exist
foreach ($folder in $RequiredWritableFolders) {
    if (-not (Test-Path $folder)) {
        New-Item -Path $folder -ItemType Directory -Force | Out-Null
        Write-Host "Created: $folder" -ForegroundColor Green
    }
    # Pastikan .htaccess & index.html di uploads
    if ($folder -eq "writable/uploads") {
        if (-not (Test-Path "$folder/.htaccess")) {
            @"<IfModule authz_core_module>
    Require all denied
</IfModule>
<IfModule !authz_core_module>
    Deny from all
</IfModule>" | Set-Content -Path "$folder/.htaccess" -Encoding UTF8
        }
        if (-not (Test-Path "$folder/index.html")) {
            "<html><body><h1>403 Forbidden</h1></body></html>" | Set-Content -Path "$folder/index.html" -Encoding UTF8
        }
    }
}

# ===========================================
# ZIP 1: public_html.zip (isi folder public/)
# ===========================================
Write-Host ""
Write-Host "===========================================" -ForegroundColor Green
Write-Host "  MEMBUAT public_html.zip" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Green

$PublicHtmlZip = Join-Path $OutputPath "public_html.zip"
$PublicSource = "public"

if (Test-Path $PublicSource) {
    # Exclude patterns untuk public
    $PublicExclude = @(
        "*.map",
        "*.ts",
        "*.scss",
        "*.less",
        "node_modules*",
        "package*.json",
        "yarn.lock",
        "webpack*",
        "starter.html",
        "README*"
    )
    
    $zipArgs = @(
        "a", "-tzip", "-r",
        "-x@(($PublicExclude -join ' ' -replace ' ', ' -x '))",
        $PublicHtmlZip,
        "$PublicSource\*"
    )
    
    Write-Host "Mengkompresi $PublicSource -> $PublicHtmlZip" -ForegroundColor Yellow
    & 7z $zipArgs
    
    if ($LASTEXITCODE -eq 0) {
        $size = [math]::Round((Get-Item $PublicHtmlZip).Length / 1MB, 2)
        Write-Host "✓ public_html.zip dibuat ($size MB)" -ForegroundColor Green
    } else {
        Write-Error "Gagal membuat public_html.zip"
        exit 1
    }
} else {
    Write-Error "Folder public/ tidak ditemukan!"
    exit 1
}

# ===========================================
# ZIP 2: ci4app.zip (semua file kecuali public/)
# ===========================================
Write-Host ""
Write-Host "===========================================" -ForegroundColor Green
Write-Host "  MEMBUAT ci4app.zip" -ForegroundColor Green
Write-Host "===========================================" -ForegroundColor Green

$Ci4AppZip = Join-Path $OutputPath "ci4app.zip"

# Build exclude argument untuk 7zip
$ExcludeArgs = $ExcludePatterns | ForEach-Object { "-x!$_" }

$zipArgs2 = @(
    "a", "-tzip", "-r",
    $ExcludeArgs,
    $Ci4AppZip,
    "*"
)

Write-Host "Mengkompresi project root (kecuali public/) -> $Ci4AppZip" -ForegroundColor Yellow
Write-Host "Exclude patterns: $($ExcludePatterns.Count) items" -ForegroundColor Gray

& 7z $zipArgs2

if ($LASTEXITCODE -eq 0) {
    $size = [math]::Round((Get-Item $Ci4AppZip).Length / 1MB, 2)
    Write-Host "✓ ci4app.zip dibuat ($size MB)" -ForegroundColor Green
} else {
    Write-Error "Gagal membuat ci4app.zip"
    exit 1
}

# ===========================================
# VERIFIKASI ISI ZIP
# ===========================================
Write-Host ""
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "  VERIFIKASI ISI ZIP" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan

Write-Host "`n--- public_html.zip ---" -ForegroundColor Yellow
& 7z l $PublicHtmlZip | Select-String "^\d{4}-\d{2}-\d{2}" | Select-Object -First 20

Write-Host "`n--- ci4app.zip ---" -ForegroundColor Yellow
& 7z l $Ci4AppZip | Select-String "^\d{4}-\d{2}-\d{2}" | Select-Object -First 30

# ===========================================
# SUMMARY
# ===========================================
Write-Host ""
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host "  SELESAI!" -ForegroundColor Cyan
Write-Host "===========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Output folder: $OutputPath" -ForegroundColor White
Write-Host ""
Write-Host "File yang dihasilkan:" -ForegroundColor White
Write-Host "  1. public_html.zip ($([math]::Round((Get-Item $PublicHtmlZip).Length / 1MB, 2)) MB)" -ForegroundColor White
WriteHost "     -> Upload ke cPanel: public_html/ (extract di sini)" -ForegroundColor Gray
Write-Host "  2. ci4app.zip ($([math]::Round((Get-Item $Ci4AppZip).Length / 1MB, 2)) MB)" -ForegroundColor White
Write-Host "     -> Upload ke cPanel: /home/username/ci4app/ (extract di sini)" -ForegroundColor Gray
Write-Host ""
Write-Host "Langkah selanjutnya di cPanel:" -ForegroundColor Yellow
Write-Host "  1. Extract public_html.zip ke public_html/" -ForegroundColor Gray
Write-Host "  2. Extract ci4app.zip ke /home/username/ci4app/" -ForegroundColor Gray
Write-Host "  3. Edit public_html/index.php -> path ke ../ci4app/app/Config/Paths.php" -ForegroundColor Gray
Write-Host "  4. Edit ci4app/.env dengan kredensial database cPanel" -ForegroundColor Gray
Write-Host "  5. Set permission writable/ = 755" -ForegroundColor Gray
Write-Host "  6. Import database stok_toko.sql" -ForegroundColor Gray