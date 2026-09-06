param(
    [string]$SiteUrl = "http://localhost:8080",
    [string]$AdminUser = "ayru_admin",
    [string]$AdminPassword = "ayru_local_only_change_me",
    [string]$AdminEmail = "admin@example.test"
)

$ErrorActionPreference = "Stop"
$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location -LiteralPath $projectRoot

docker compose up -d database wordpress

$attempt = 0
do {
    $attempt++
    Start-Sleep -Seconds 2
    docker compose run --rm wpcli core version 2>$null
    $coreReady = $LASTEXITCODE -eq 0
} while (-not $coreReady -and $attempt -lt 20)

if (-not $coreReady) { throw "Les fichiers WordPress ne sont pas prêts." }

docker compose run --rm wpcli core is-installed 2>$null
if ($LASTEXITCODE -ne 0) {
    docker compose run --rm wpcli core install --url=$SiteUrl --title="AYRU" --admin_user=$AdminUser --admin_password=$AdminPassword --admin_email=$AdminEmail --skip-email
}

docker compose run --rm wpcli plugin is-installed woocommerce 2>$null
if ($LASTEXITCODE -ne 0) { docker compose run --rm wpcli plugin install woocommerce --activate } else { docker compose run --rm wpcli plugin activate woocommerce }
docker compose run --rm wpcli language core install fr_FR --activate
docker compose run --rm wpcli language plugin install woocommerce fr_FR
docker compose run --rm wpcli plugin activate ayru-core
docker compose run --rm wpcli theme activate ayru
docker compose run --rm wpcli eval-file /var/www/html/wp-content/themes/ayru/dev-config.php
docker compose run --rm wpcli option update blogdescription "Plus qu’une boutique, un monde pour grandir."
docker compose run --rm wpcli rewrite structure '/%postname%/' --hard
docker compose run --rm wpcli eval-file /var/www/html/wp-content/themes/ayru/dev-seed.php

Write-Host "AYRU est prêt sur $SiteUrl"
