<#
.SYNOPSIS
    Sauvegarde la base de donnees et les fichiers televerses d'AYRU.

.DESCRIPTION
    Le theme et le plugin sont versionnes dans Git, mais toute la configuration
    vit dans la base de donnees a l'interieur du volume Docker : pages, produits,
    categories, reglages WooCommerce, cles Stripe, liens sociaux, identifiants de
    pixels. Si le volume est perdu ou recree, ce travail disparait.

    Ce script produit une archive complete, indispensable a deux moments :
    avant toute manipulation risquee, et le jour de la migration vers un
    hebergement en ligne.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File .\scripts\backup.ps1
#>

$ErrorActionPreference = 'Stop'

$racine = Split-Path -Parent $PSScriptRoot
$dossier = Join-Path $racine 'backups'
$horodatage = Get-Date -Format 'yyyy-MM-dd_HHmm'

if (-not (Test-Path $dossier)) {
    New-Item -ItemType Directory -Path $dossier | Out-Null
}

Set-Location $racine

Write-Host "Sauvegarde AYRU du $horodatage" -ForegroundColor Cyan

# --- Base de donnees ------------------------------------------------------
$sql = "ayru-db-$horodatage.sql"
Write-Host "  base de donnees..." -NoNewline
docker compose run --rm wpcli db export "wp-content/$sql" --user=1 | Out-Null
if ($?) {
    # wp-cli ecrit dans le conteneur ; on rapatrie via le volume monte du theme
    $source = Join-Path $racine "wordpress\wp-content\$sql"
    if (Test-Path $source) {
        Move-Item $source (Join-Path $dossier $sql) -Force
        $taille = [math]::Round((Get-Item (Join-Path $dossier $sql)).Length / 1MB, 2)
        Write-Host " ok ($taille Mo)" -ForegroundColor Green
    } else {
        Write-Host " export cree dans le conteneur, a rapatrier manuellement" -ForegroundColor Yellow
    }
}

# --- Fichiers televerses --------------------------------------------------
$tar = "ayru-uploads-$horodatage.tar"
Write-Host "  mediatheque..." -NoNewline
docker compose run --rm --entrypoint tar wpcli -cf "wp-content/$tar" -C /var/www/html/wp-content uploads 2>$null | Out-Null
$sourceTar = Join-Path $racine "wordpress\wp-content\$tar"
if (Test-Path $sourceTar) {
    Move-Item $sourceTar (Join-Path $dossier $tar) -Force
    $taille = [math]::Round((Get-Item (Join-Path $dossier $tar)).Length / 1MB, 2)
    Write-Host " ok ($taille Mo)" -ForegroundColor Green
} else {
    Write-Host " aucun fichier televerse" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Archives dans $dossier" -ForegroundColor Cyan
Write-Host "Ces fichiers contiennent des donnees et des cles : ils ne sont pas versionnes." -ForegroundColor DarkGray
Get-ChildItem $dossier | Sort-Object LastWriteTime -Descending | Select-Object -First 6 |
    Format-Table Name, @{N='Taille (Mo)';E={[math]::Round($_.Length/1MB,2)}}, LastWriteTime -AutoSize
