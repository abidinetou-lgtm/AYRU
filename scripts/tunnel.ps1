<#
.SYNOPSIS
    Publie le site AYRU local sur une adresse HTTPS publique, via Cloudflare Tunnel.

.DESCRIPTION
    Cloudflare Workers ne peut pas heberger AYRU : Workers execute du JavaScript,
    WordPress a besoin de PHP et de MySQL. Cloudflare Tunnel resout autrement le
    meme besoin : le site continue de tourner en local, et le tunnel lui donne une
    adresse publique en HTTPS que n'importe qui peut ouvrir.

    Utile pour une demonstration, un entretien, ou faire tester le site a quelqu'un.
    Ce n'est pas un hebergement : le lien ne fonctionne que tant que cette machine
    est allumee, Docker demarre et ce script en cours d'execution.

    Le mu-plugin scripts/tunnel-mu-plugin.php doit etre installe dans le conteneur
    pour que les liens et les images pointent vers l'adresse du tunnel et non vers
    localhost. Ce script l'installe automatiquement.

.EXAMPLE
    powershell -ExecutionPolicy Bypass -File .\scripts\tunnel.ps1
#>

$ErrorActionPreference = 'Stop'
$racine = Split-Path -Parent $PSScriptRoot
Set-Location $racine

# --- Verifications prealables ---------------------------------------------
Write-Host "Verification de l'environnement..." -ForegroundColor Cyan

docker ps 2>&1 | Out-Null
if (-not $?) {
    Write-Host "Docker n'est pas demarre. Lance Docker Desktop puis relance ce script." -ForegroundColor Red
    exit 1
}

$conteneur = 'ayruproject-wordpress-1'
docker exec $conteneur true 2>&1 | Out-Null
if (-not $?) {
    Write-Host "  conteneurs arretes, demarrage..." -ForegroundColor Yellow
    docker compose up -d | Out-Null
    Start-Sleep -Seconds 8
}

$cf = Get-Command cloudflared -ErrorAction SilentlyContinue
if (-not $cf) {
    $cf = Get-Item "C:\Program Files (x86)\cloudflared\cloudflared.exe" -ErrorAction SilentlyContinue
}
if (-not $cf) {
    Write-Host "cloudflared est absent. Installe-le avec :" -ForegroundColor Red
    Write-Host "  winget install --id Cloudflare.cloudflared" -ForegroundColor Yellow
    exit 1
}

# --- Adaptation de WordPress au tunnel ------------------------------------
Write-Host "  installation du mu-plugin d'adaptation..." -NoNewline
docker exec $conteneur mkdir -p /var/www/html/wp-content/mu-plugins | Out-Null
docker cp "$racine\scripts\tunnel-mu-plugin.php" "${conteneur}:/var/www/html/wp-content/mu-plugins/ayru-tunnel.php" | Out-Null
Write-Host " ok" -ForegroundColor Green

# --- Ouverture du tunnel ---------------------------------------------------
Write-Host ""
Write-Host "Ouverture du tunnel. Laisse cette fenetre ouverte." -ForegroundColor Cyan
Write-Host "L'adresse publique s'affiche dans quelques secondes, encadree." -ForegroundColor DarkGray
Write-Host "Ctrl+C pour fermer le tunnel." -ForegroundColor DarkGray
Write-Host ""

& $cf.Source tunnel --url http://localhost:8080 --no-autoupdate
