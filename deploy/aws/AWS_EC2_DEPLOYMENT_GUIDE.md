# AWS EC2 Deployment Guide — Housing365 India

This step-by-step guide will walk you through hosting **Housing365 India** on an **Amazon Web Services (AWS) EC2** instance running **Ubuntu 24.04 LTS** with **Nginx, PHP 8.3-FPM, MySQL 8.x, Queue Workers, and SSL**.

---

## 1. AWS EC2 Instance Provisioning

### Step 1.1: Launch Instance in AWS Console
1. Log in to the [AWS Management Console](https://console.aws.amazon.com/ec2).
2. Choose your preferred AWS Region (e.g., `ap-south-1` **Asia Pacific (Mumbai)** for lowest latency in India).
3. Click **Launch instances**.
4. Configure instance parameters:
   - **Name:** `housing365-production`
   - **Application and OS Images (AMI):** Select **Ubuntu**, then **Ubuntu Server 24.04 LTS (HVM), SSD Volume Type (64-bit x86)**.
   - **Instance type:**
     - **Recommended:** `t3.medium` (2 vCPUs, 4 GiB RAM) for production workload (Filament + Vite build + Queue workers).
     - **Cost-effective minimum:** `t3.small` (2 vCPUs, 2 GiB RAM) — our setup script will add a 2GB swap file to protect against memory spikes during builds.
   - **Key pair (login):** Create a new key pair (e.g. `housing365-key.pem`) and download it to your local machine.

### Step 1.2: Network & Security Group Settings
Create a Security Group with the following inbound rules:
| Type | Port | Source | Description |
|---|---|---|---|
| **SSH** | `22` | `My IP` (or restricted CIDR) | Secure terminal access |
| **HTTP** | `80` | `0.0.0.0/0` (Anywhere IPv4) | Web traffic & SSL challenge |
| **HTTPS** | `443` | `0.0.0.0/0` (Anywhere IPv4) | Encrypted secure traffic |

### Step 1.3: Storage (EBS)
- Set volume size to at least **20 GiB to 30 GiB** (Volume Type: `gp3`).
- Click **Launch instance**.

### Step 1.4: Allocate an Elastic IP (Static Public IP)
1. In the EC2 sidebar, go to **Network & Security** > **Elastic IPs**.
2. Click **Allocate Elastic IP address**.
3. Select the allocated IP, click **Actions** > **Associate Elastic IP address**, and attach it to your `housing365-production` instance.

---

## 2. Connect to the EC2 Server & Run Auto-Provisioning

### Step 2.1: SSH into the Server
Open your local terminal (PowerShell, Command Prompt, or Git Bash) and run:
```bash
chmod 400 path/to/housing365-key.pem   # (Linux/Mac/Git Bash)
ssh -i "path/to/housing365-key.pem" ubuntu@<YOUR_ELASTIC_IP>
```

### Step 2.2: Run the Automated Server Setup
Once inside the EC2 server, download and run the provisioning script:
```bash
# Clone the repository (or copy ec2-setup.sh to the server)
git clone https://github.com/ajaypoonia3012/housing365india.git /tmp/housing365-repo
sudo bash /tmp/housing365-repo/deploy/aws/ec2-setup.sh
```
*This script automatically updates the system, creates a 2GB swap file, installs Nginx, PHP 8.3 with all required extensions, Composer, Node.js 20, MySQL 8, Supervisor, and Certbot.*

---

## 3. Database Configuration (MySQL 8.x)

You can use the local MySQL on the EC2 instance, or an AWS RDS MySQL 8 instance.

### Option A: Local MySQL on EC2
Connect to MySQL as root:
```bash
sudo mysql
```
Run the following SQL commands to create the database and user:
```sql
CREATE DATABASE housing365_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER 'housing365_user'@'localhost' IDENTIFIED BY 'StrongRandomPassword123#';

GRANT ALL PRIVILEGES ON housing365_prod.* TO 'housing365_user'@'localhost';

FLUSH PRIVILEGES;
EXIT;
```

---

## 4. Deploying the Application

### Step 4.1: Clone the Codebase into `/var/www/housing365`
```bash
sudo git clone https://github.com/ajaypoonia3012/housing365india.git /var/www/housing365
sudo chown -R ubuntu:www-data /var/www/housing365
sudo chmod -R 775 /var/www/housing365/storage /var/www/housing365/bootstrap/cache
cd /var/www/housing365
```

### Step 4.2: Configure `.env`
```bash
cp .env.production.example .env
nano .env
```
Update these key parameters in `.env`:
```ini
APP_NAME="Housing 365 India"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://housing365.in   # Or http://<YOUR_ELASTIC_IP> before domain setup

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=housing365_prod
DB_USERNAME=housing365_user
DB_PASSWORD=StrongRandomPassword123#

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```
Save and close `nano` (`Ctrl+O`, `Enter`, `Ctrl+X`).

### Step 4.3: Initial Build & Migrations
```bash
# Generate application encryption key
php artisan key:generate --force

# Install composer production dependencies
composer install --no-dev --optimize-autoloader

# Install npm dependencies and compile Vite production assets
npm ci
npm run build

# Run database migrations
php artisan migrate --force

# Link storage
php artisan storage:link

# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 5. Web Server (Nginx) & Queue Worker Configuration

### Step 5.1: Configure Nginx
```bash
# Copy preconfigured Nginx configuration
sudo cp deploy/aws/nginx-housing365.conf /etc/nginx/sites-available/housing365

# Edit server_name with your domain or Elastic IP
sudo nano /etc/nginx/sites-available/housing365

# Enable the site and test configuration
sudo ln -s /etc/nginx/sites-available/housing365 /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### Step 5.2: Configure Supervisor (Queue Worker)
```bash
# Copy supervisor queue configuration
sudo cp deploy/aws/supervisor-housing365.conf /etc/supervisor/conf.d/housing365-worker.conf

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start all
sudo supervisorctl status
```

### Step 5.3: Configure Laravel Scheduler (Cron)
```bash
# Open crontab for www-data user
sudo crontab -u www-data -e
```
Add this single line at the bottom:
```bash
* * * * * cd /var/www/housing365 && php artisan schedule:run >> /dev/null 2>&1
```

---

## 6. Point Domain & Enable Free SSL (HTTPS)

### Step 6.1: Update DNS Records
At your domain registrar (or AWS Route 53):
- **A Record:** `@` points to `<YOUR_ELASTIC_IP>`
- **CNAME Record:** `www` points to `housing365.in`

### Step 6.2: Issue Free Let's Encrypt SSL
Once DNS has propagated (verify with `ping yourdomain.com`):
```bash
sudo certbot --nginx -d housing365.in -d www.housing365.in
```
Follow the interactive prompts to enter your email and agree to terms. Certbot will automatically configure SSL in Nginx and set up automatic renewal.

---

## 7. Future Deployments (One-Click)

Whenever you push new code to Git, deploy updates with one command:
```bash
cd /var/www/housing365
bash deploy/aws/deploy.sh
```
This automated script handles:
- Zero-downtime maintenance mode
- Git pull
- Composer & NPM build
- Safe additive database migrations
- Cache warming
- Queue worker and PHP-FPM reloads
