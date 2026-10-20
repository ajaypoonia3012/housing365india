#!/usr/bin/env bash
# ==============================================================================
# Housing365 India — Production Deployment Script for AWS EC2
# Run from /var/www/housing365 as ubuntu/deploy user: bash deploy/aws/deploy.sh
# ==============================================================================

set -euo pipefail

APP_DIR="/var/www/housing365"
cd "${APP_DIR}"

echo "==> [1/9] Activating Maintenance Mode..."
php artisan down --secret="housing365-deploy-access-token" --render="errors::500" || true

echo "==> [2/9] Fetching latest changes from Git..."
git fetch origin main
git checkout main
git pull origin main

echo "==> [3/9] Installing PHP Production Dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "==> [4/9] Compiling Production Frontend Assets (Vite)..."
npm ci --prefer-offline --no-audit
npm run build

echo "==> [5/9] Executing Safe Database Migrations (MySQL 8.x)..."
php artisan migrate --force

echo "==> [6/9] Verifying Storage Symlink..."
php artisan storage:link || true

echo "==> [7/9] Rebuilding Production Application Caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo "==> [8/9] Restarting Queue Workers & PHP-FPM..."
php artisan queue:restart
sudo systemctl reload php8.3-fpm || sudo systemctl restart php8.3-fpm
sudo supervisorctl restart all || true

echo "==> [9/9] Disabling Maintenance Mode (Site Live)..."
php artisan up

echo "=============================================================================="
echo " Housing365 Deployment Successfully Completed! "
echo " Environment: $(php artisan env)"
echo " Timestamp:   $(date)"
echo "=============================================================================="
