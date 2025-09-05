# Digitazio Panel - Live Server Deployment Guide

## Overview
This guide covers deploying the Digitazio Panel to a live hosting server with proper security and performance configurations.

## Hosting Requirements

### Minimum Server Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher  
- **Apache/Nginx**: Latest stable version
- **Storage**: 2GB minimum
- **RAM**: 1GB minimum
- **SSL Certificate**: Required for production

### Recommended Hosting Providers
- **Shared Hosting**: Hostinger, Bluehost, SiteGround
- **VPS Hosting**: DigitalOcean, Linode, Vultr
- **Cloud Hosting**: AWS, Google Cloud, Azure

## Pre-Deployment Checklist

### 1. Code Preparation
```bash
# Remove development files
rm -rf .git/
rm -rf node_modules/
rm composer.lock
rm package-lock.json

# Clean up temporary files
find . -name "*.tmp" -delete
find . -name "*.log" -delete
```

### 2. Security Configuration
```php
// Create config/production.php
<?php
// Production database configuration
define('DB_HOST', 'your-production-host');
define('DB_NAME', 'your-production-database');
define('DB_USER', 'your-production-user');
define('DB_PASS', 'your-secure-password');

// Security settings
define('DEBUG_MODE', false);
define('DISPLAY_ERRORS', false);
define('SESSION_SECURE', true);
define('SESSION_HTTPONLY', true);

// Error logging
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
?>
```

## Deployment Methods

### Method 1: cPanel/Shared Hosting

#### Step 1: Upload Files
```bash
# Create ZIP archive of your project
zip -r digitazio-panel.zip digitazpannel/ -x "*.git*" "node_modules/*"

# Upload via cPanel File Manager or FTP
# Extract to public_html/ or subdirectory
```

#### Step 2: Database Setup
```sql
-- In cPanel phpMyAdmin, create database
CREATE DATABASE your_account_digitazio;

-- Import database files in order:
-- 1. DB.sql (main structure)
-- 2. task_management_db.sql
-- 3. accounting_db.sql
-- 4. inventory_db.sql
-- 5. clients_table.sql
-- 6. requests_db.sql
-- 7. reports_db.sql
```

#### Step 3: Configuration
```php
// Update DB_connection.php
<?php
$servername = "localhost"; // Usually localhost for shared hosting
$username = "your_cpanel_username_dbuser";
$password = "your_database_password";
$dbname = "your_cpanel_username_digitazio";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Database connection failed");
}
?>
```

### Method 2: VPS/Cloud Server

#### Step 1: Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install LAMP stack
sudo apt install apache2 mysql-server php8.2 php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
```

#### Step 2: Upload and Configure
```bash
# Clone or upload project
git clone <your-repo> /var/www/html/digitazpannel
# OR upload via SCP/SFTP

# Set permissions
sudo chown -R www-data:www-data /var/www/html/digitazpannel
sudo chmod -R 755 /var/www/html/digitazpannel
sudo chmod -R 644 /var/www/html/digitazpannel/*.php

# Install dependencies
cd /var/www/html/digitazpannel
composer install --no-dev --optimize-autoloader
```

#### Step 3: Database Setup
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE digitazio_panel;
CREATE USER 'digitazio_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON digitazio_panel.* TO 'digitazio_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database
mysql -u digitazio_user -p digitazio_panel < DB.sql
mysql -u digitazio_user -p digitazio_panel < task_management_db.sql
# ... import other SQL files
```

#### Step 4: Apache Virtual Host
```apache
# Create /etc/apache2/sites-available/digitazio.conf
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/html/digitazpannel
    
    <Directory /var/www/html/digitazpannel>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/digitazio_error.log
    CustomLog ${APACHE_LOG_DIR}/digitazio_access.log combined
</VirtualHost>

# Enable site
sudo a2ensite digitazio.conf
sudo systemctl reload apache2
```

## SSL Certificate Setup

### Using Let's Encrypt (Free)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Auto-renewal (add to crontab)
0 12 * * * /usr/bin/certbot renew --quiet
```

### Manual SSL Certificate
```apache
# Update virtual host for HTTPS
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/digitazpannel
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    SSLCertificateChainFile /path/to/chain.crt
    
    # Security headers
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</VirtualHost>
```

## Production Optimizations

### 1. PHP Configuration
```ini
# Update php.ini for production
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
memory_limit = 256M
max_execution_time = 300
upload_max_filesize = 10M
post_max_size = 10M
session.cookie_secure = 1
session.cookie_httponly = 1
```

### 2. Database Optimization
```sql
-- Add indexes for better performance
CREATE INDEX idx_user_tasks ON tasks(user_id);
CREATE INDEX idx_task_status ON tasks(status);
CREATE INDEX idx_created_at ON tasks(created_at);
CREATE INDEX idx_user_requests ON requests(user_id);
CREATE INDEX idx_request_status ON requests(status);
```

### 3. File Permissions
```bash
# Secure file permissions
find /var/www/html/digitazpannel -type f -exec chmod 644 {} \;
find /var/www/html/digitazpannel -type d -exec chmod 755 {} \;

# Protect sensitive files
chmod 600 /var/www/html/digitazpannel/DB_connection.php
chmod 600 /var/www/html/digitazpannel/config/*
```

### 4. .htaccess Security
```apache
# Create .htaccess in root directory
RewriteEngine On

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
</IfModule>

# Protect sensitive files
<Files "*.sql">
    Order allow,deny
    Deny from all
</Files>

<Files "composer.*">
    Order allow,deny
    Deny from all
</Files>

<Files "*.md">
    Order allow,deny
    Deny from all
</Files>

# Block access to vendor directory
RedirectMatch 403 ^/vendor/.*$
```

## Environment-Specific Configuration

### Production Environment Variables
```php
// Create .env file (not in version control)
DB_HOST=localhost
DB_NAME=digitazio_panel
DB_USER=digitazio_user
DB_PASS=secure_password
DEBUG_MODE=false
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=mail_password
```

### Load Environment Variables
```php
// Add to DB_connection.php
if (file_exists('.env')) {
    $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$servername = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USER'] ?? 'root';
$password = $_ENV['DB_PASS'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'digitazio_panel';
```

## Backup Strategy

### Automated Database Backup
```bash
#!/bin/bash
# Create backup script: /home/backup_digitazio.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/backups"
DB_NAME="digitazio_panel"
DB_USER="digitazio_user"
DB_PASS="your_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/digitazio_db_$DATE.sql

# Files backup
tar -czf $BACKUP_DIR/digitazio_files_$DATE.tar.gz /var/www/html/digitazpannel

# Remove backups older than 30 days
find $BACKUP_DIR -name "digitazio_*" -mtime +30 -delete

# Add to crontab for daily backup at 2 AM
# 0 2 * * * /home/backup_digitazio.sh
```

## Monitoring and Maintenance

### Log Monitoring
```bash
# Monitor error logs
tail -f /var/log/apache2/digitazio_error.log
tail -f /var/log/php_errors.log

# Monitor access logs
tail -f /var/log/apache2/digitazio_access.log
```

### Performance Monitoring
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs -y

# Monitor system resources
htop
iotop
nethogs
```

## Troubleshooting Common Issues

### Database Connection Issues
```bash
# Test database connection
mysql -u digitazio_user -p digitazio_panel -e "SELECT 1"

# Check MySQL service
sudo systemctl status mysql
sudo systemctl restart mysql
```

### File Permission Issues
```bash
# Fix ownership
sudo chown -R www-data:www-data /var/www/html/digitazpannel

# Fix permissions
sudo find /var/www/html/digitazpannel -type f -exec chmod 644 {} \;
sudo find /var/www/html/digitazpannel -type d -exec chmod 755 {} \;
```

### Apache Issues
```bash
# Check Apache configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2

# Check Apache status
sudo systemctl status apache2
```

## Security Checklist

- [ ] SSL certificate installed and configured
- [ ] Database user has minimal required privileges
- [ ] File permissions properly set (644 for files, 755 for directories)
- [ ] Sensitive files protected with .htaccess
- [ ] Debug mode disabled in production
- [ ] Error display disabled, logging enabled
- [ ] Regular security updates applied
- [ ] Strong passwords used for all accounts
- [ ] Backup system configured and tested
- [ ] Monitoring and alerting set up

## Post-Deployment Testing

### Functionality Tests
1. **Login System**: Test admin and employee login
2. **Task Management**: Create, update, delete tasks
3. **User Management**: Add/edit users (admin only)
4. **Database Operations**: All CRUD operations
5. **File Uploads**: Test any file upload functionality
6. **Email Notifications**: Test email sending if implemented

### Performance Tests
```bash
# Test page load times
curl -w "@curl-format.txt" -o /dev/null -s "https://yourdomain.com"

# Test database performance
mysql -u digitazio_user -p digitazio_panel -e "SHOW PROCESSLIST;"
```

### Security Tests
- Test for SQL injection vulnerabilities
- Verify HTTPS redirects work
- Check file access restrictions
- Test session security
- Verify error handling doesn't expose sensitive information

---

**Important**: Always test the deployment process on a staging server before deploying to production. Keep regular backups and have a rollback plan ready.

**Version**: 1.0  
**Last Updated**: August 2025
