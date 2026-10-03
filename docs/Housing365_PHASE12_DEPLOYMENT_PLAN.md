# Housing365 India — Phase 12 Production Deployment Plan

**Project:** Housing365 India  
**Phase:** Phase 12 — Production Deployment, Staging, UAT & Launch Readiness  
**Target Environment:** Linux / Ubuntu LTS (PHP 8.2+, Nginx, MySQL 8.x + InnoDB)  
**Database Runtime Constraint:** MySQL 8.x + InnoDB Required (SQLite Prohibited)  
**Status:** Staging Verified & Production Deployment Checklist Established  

---

## 1. Executive Deployment Architecture

Housing365 India is an enterprise-grade unified property platform in India. This deployment plan establishes the rigid, immutable procedures required to safely deploy the application to staging and production environments without data loss, service interruption, or security regressions.

```
                           [ Cloudflare / DNS / SSL Termination ]
                                            │
                                            ▼
                           [ Nginx Reverse Proxy / Web Server ]
                                            │
                   ┌────────────────────────┴────────────────────────┐
                   ▼                                                 ▼
        [ PHP-FPM / Laravel Application ]                 [ Compiled Vite Assets ]
        - APP_ENV=production                              - public/build/assets/
        - APP_DEBUG=false                                 - CSS (142 kB) / JS (52 kB)
        - Session: database / redis                       - Robots.txt / XML Sitemaps
                   │
       ┌───────────┼─────────────────────────┐
       ▼           ▼                         ▼
 [ MySQL 8.x ] [ Queue Worker (Supervisor) ] [ Cron Scheduler ]
 - InnoDB      - php artisan queue:work      - * * * * * schedule:run
 - Port 3306   - Notifications, Care, Payouts
```

---

## 2. Environment Configuration Matrix

The following environment variables must be populated in the production `.env` file. **Never commit secrets to source control.**

| Variable | Staging / Testing | Production Specification | Notes |
|---|---|---|---|
| `APP_NAME` | `Housing 365 India` | `Housing 365 India` | Unified brand name |
| `APP_ENV` | `local` / `staging` | `production` | Enables production security locks |
| `APP_DEBUG` | `false` | `false` | **MANDATORY**: Stack traces hidden |
| `APP_KEY` | *(generated)* | `base64:...` | Run `php artisan key:generate` |
| `APP_URL` | `http://127.0.0.1:8000` | `https://housing365.in` | Canonical HTTPS URL |
| `DB_CONNECTION` | `mysql` | `mysql` | **SQLite strictly prohibited** |
| `DB_HOST` | `127.0.0.1` | `127.0.0.1` or Cloud SQL / RDS host | Secure internal network |
| `DB_PORT` | `3306` | `3306` | Standard MySQL port |
| `DB_DATABASE` | `housing365` | `housing365_prod` | Production database |
| `DB_USERNAME` | *(dev user)* | *(dedicated restricted user)* | Minimal required privileges |
| `DB_PASSWORD` | *(dev pass)* | *(strong vault secret)* | High entropy password |
| `SESSION_DRIVER` | `database` | `database` or `redis` | Stateful database sessions |
| `SESSION_SECURE_COOKIE` | `false` | `true` | Enforces HTTPS-only cookies |
| `QUEUE_CONNECTION` | `database` | `database` or `redis` | Background job processing |
| `CACHE_STORE` | `database` | `database` or `redis` | Configuration & query caching |
| `FILESYSTEM_DISK` | `local` | `local` (or private S3) | Private docs kept non-public |
| `MAIL_MAILER` | `log` | `smtp` / `ses` / `postmark` | Live transactional mailer |
| `PAYMENT_GATEWAY_KEY` | *(sandbox)* | *(live merchant key)* | Requires external configuration |
| `PAYMENT_WEBHOOK_SECRET`| *(sandbox)* | *(live merchant secret)* | HMAC verification key |

---

## 3. Database Engine & MySQL 8.x Verification

### Production Database Requirements
1. **Engine:** MySQL 8.x (`MySQL 8.0` or `8.4 LTS`).
2. **Storage Engine:** `InnoDB` for all tables (row-level locking, ACID compliance, `FOR UPDATE` concurrency support).
3. **Collation:** `utf8mb4_unicode_ci` or `utf8mb4_0900_ai_ci`.
4. **SQLite Prohibition:** No SQLite drivers, no `database.sqlite` files, no `:memory:` test runtimes.

### Pre-Deployment Database Verification Query
Before initiating migrations on production, run:
```sql
SELECT VERSION() AS server_version, @@version_comment AS distribution, @@default_storage_engine AS engine;
```
Expected output:
- Server: `MySQL 8.x`
- Engine: `InnoDB`

---

## 4. Database Backup & Disaster Recovery Runbook

### Absolute Invariant: Data Preservation
> [!CAUTION]
> **NEVER** run `php artisan migrate:fresh`, `db:wipe`, `TRUNCATE`, or `DROP DATABASE` on staging or production. All schema changes must be strictly additive and applied via versioned Laravel migrations.

### Automated Backup Procedure (Daily & Pre-Deployment)
Prior to running any deployment or migration command, execute a complete logical backup:

```bash
#!/bin/bash
# Pre-deployment backup script
BACKUP_DIR="/var/backups/housing365"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_FILE="${BACKUP_DIR}/housing365_backup_${TIMESTAMP}.sql.gz"

mkdir -p ${BACKUP_DIR}

echo "Starting database dump to ${BACKUP_FILE}..."
mysqldump -h ${DB_HOST} -P ${DB_PORT} -u ${DB_USERNAME} -p${DB_PASSWORD} \
  --single-transaction \
  --quick \
  --routines \
  --triggers \
  ${DB_DATABASE} | gzip > ${BACKUP_FILE}

echo "Verifying backup file integrity..."
if [ -s "${BACKUP_FILE}" ]; then
    echo "Backup completed successfully: $(ls -lh ${BACKUP_FILE})"
else
    echo "ERROR: Backup file is empty! Aborting deployment."
    exit 1
fi
```

### Backup Policy
- **Frequency:** Full backup every 6 hours + immediate pre-deployment snapshot.
- **Retention:** 30 days rolling on secondary secure cloud bucket (GCS/S3).
- **Encryption:** AES-256 encrypted before transmission to offsite storage.
- **Verification:** Weekly automated restore test on a sandboxed staging instance to verify dump integrity.

### Restore Procedure
In the event of a catastrophic rollback requirement:
```bash
gunzip < /var/backups/housing365/housing365_backup_YYYYMMDD_HHMMSS.sql.gz | mysql -h ${DB_HOST} -P ${DB_PORT} -u ${DB_USERNAME} -p ${DB_DATABASE}
```

---

## 5. Step-by-Step Production Deployment Procedure

Follow these commands sequentially during a scheduled maintenance window or rolling deployment:

### Step 1: Enable Maintenance Mode
```bash
php artisan down --secret="housing365-deploy-access-token" --render="errors::500"
```

### Step 2: Update Codebase
```bash
git fetch origin main
git checkout main
git pull origin main
```

### Step 3: Install Production PHP Dependencies
```bash
composer install --no-dev --optimize-autoloader --no-interaction
```

### Step 4: Build Production Frontend Assets
```bash
npm ci
npm run build
```

### Step 5: Execute Database Migrations Safely
```bash
# Check current migration status first
php artisan migrate:status

# Execute pending migrations with force flag
php artisan migrate --force

# Verify all migrations show [Ran]
php artisan migrate:status
```

### Step 6: Optimize Application Caches
```bash
# Clear any stale development caches
php artisan optimize:clear

# Recompile optimized production caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Step 7: Restart Queue Workers & Daemons
```bash
php artisan queue:restart
sudo supervisorctl restart housing365-worker:*
```

### Step 8: Verify Public Health & Disable Maintenance Mode
```bash
# Smoke test endpoints locally
curl -I http://127.0.0.1:8000/
curl -I http://127.0.0.1:8000/sitemap.xml
curl -I http://127.0.0.1:8000/robots.txt

# Disable maintenance mode
php artisan up
```

---

## 6. Queue Workers Configuration (Supervisor)

All asynchronous tasks (notifications, Care work order dispatch, rent receipt generation, referral payouts, and lead routing) run through the queued worker pool.

### `/etc/supervisor/conf.d/housing365-worker.conf`
```ini
[program:housing365-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/housing365/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --memory=128
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/housing365/worker.log
stopwaitsecs=3600
```

---

## 7. Cron Scheduler Configuration

The Laravel scheduler runs daily sitemap updates, SLA escalation alerts for Housing365 Care, lead expiry pruning, and rent ledger reconciliation.

Add the following entry to the web server user crontab (`crontab -e -u www-data`):
```cron
* * * * * cd /var/www/housing365 && php artisan schedule:run >> /dev/null 2>&1
```

---

## 8. Web Server Configuration (Nginx + SSL)

```nginx
server {
    listen 80;
    server_name housing365.in www.housing365.in;
    return 301 https://housing365.in$request_uri;
}

server {
    listen 443 ssl http2;
    server_name housing365.in;
    root /var/www/housing365/public;

    ssl_certificate /etc/letsencrypt/live/housing365.in/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/housing365.in/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval';" always;

    index index.php;
    charset utf-8;

    # Gzip Compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;

    # Static Assets with High Cache Expiry
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 365d;
        add_header Cache-Control "public, no-transform";
    }

    # Deny direct access to private storage
    location /storage/private {
        deny all;
        return 404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 9. Rollback Plan

If an unexpected runtime regression occurs post-deployment:
1. Put site in maintenance mode: `php artisan down`.
2. Rollback git checkout: `git checkout HEAD@{1}`.
3. If migrations need rollback: `php artisan migrate:rollback --step=1 --force` (only after checking safety).
4. Reinstall previous dependencies: `composer install --no-dev --optimize-autoloader`.
5. Rebuild frontend assets: `npm run build`.
6. Rebuild caches: `php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache`.
7. Bring application up: `php artisan up`.
8. If database was corrupted, execute the database restore procedure from the pre-deployment snapshot.
