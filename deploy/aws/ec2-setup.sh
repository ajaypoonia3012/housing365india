#!/usr/bin/env bash
# ==============================================================================
# Housing365 India — Ubuntu 24.04 LTS Server Provisioning for AWS EC2
# Run this script with sudo: sudo bash ec2-setup.sh
# ==============================================================================

set -euo pipefail

echo "==> [1/8] Updating package repositories..."
export DEBIAN_FRONTEND=noninteractive
apt-get update -y && apt-get upgrade -y

echo "==> [2/8] Creating 2GB Swap file (prevents OOM on Composer/Vite builds)..."
if [ ! -f /swapfile ]; then
    fallocate -l 2G /swapfile
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile
    echo '/swapfile none swap sw 0 0' >> /etc/fstab
    echo "Swap file created successfully."
fi

echo "==> [3/8] Installing common tools & dependencies..."
apt-get install -y software-properties-common curl git unzip zip ufw htop ca-certificates gnupg supervisor

echo "==> [4/8] Installing Nginx..."
apt-get install -y nginx

echo "==> [5/8] Installing PHP 8.3 and extensions..."
add-apt-repository -y ppa:ondrej/php
apt-get update -y
apt-get install -y \
    php8.3 \
    php8.3-cli \
    php8.3-fpm \
    php8.3-mysql \
    php8.3-mbstring \
    php8.3-xml \
    php8.3-bcmath \
    php8.3-curl \
    php8.3-zip \
    php8.3-intl \
    php8.3-gd \
    php8.3-sqlite3 \
    php8.3-redis

echo "==> Configuring PHP-FPM limits for production..."
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 32M/' /etc/php/8.3/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 32M/' /etc/php/8.3/fpm/php.ini
sed -i 's/memory_limit = .*/memory_limit = 512M/' /etc/php/8.3/fpm/php.ini
systemctl restart php8.3-fpm

echo "==> [6/8] Installing Composer..."
if ! command -v composer &> /dev/null; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

echo "==> [7/8] Installing Node.js (v20 LTS) & npm..."
if ! command -v node &> /dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi

echo "==> [8/8] Installing MySQL Server (or connect to external RDS)..."
apt-get install -y mysql-server
systemctl enable mysql
systemctl start mysql

echo "==> Installing Certbot for SSL certificates..."
apt-get install -y certbot python3-certbot-nginx

echo "==> Preparing web directory..."
mkdir -p /var/www/housing365
chown -R www-data:ubuntu /var/www/housing365
chmod -R 775 /var/www/housing365

echo "=============================================================================="
echo " AWS EC2 Provisioning Complete! "
echo " PHP Version:    $(php -v | head -n 1)"
echo " Composer:       $(composer --version | head -n 1)"
echo " Node Version:   $(node -v)"
echo " MySQL Version:  $(mysql --version)"
echo "=============================================================================="
