# Digitazio Panel - Developer Guide

## Project Overview
Digitazio Panel is a PHP-based employee task management system built with modern web technologies including Tailwind CSS, MySQL, and responsive design principles.

## Technology Stack

### Backend
- **PHP 8.2+**: Server-side scripting
- **MySQL 8.0+**: Database management
- **Composer**: Dependency management
- **PHPWord**: Document generation

### Frontend
- **HTML5**: Markup structure
- **Tailwind CSS**: Utility-first CSS framework
- **JavaScript (ES6+)**: Client-side functionality
- **Font Awesome**: Icon library
- **Google Fonts**: Typography (Poppins, Raleway)

### Development Tools
- **XAMPP**: Local development environment
- **Git**: Version control
- **Composer**: PHP package manager

## System Requirements

### Minimum Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 5.7 or higher
- **Apache**: 2.4 or higher
- **Memory**: 512MB RAM minimum
- **Storage**: 1GB free space

### Recommended Requirements
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Memory**: 2GB RAM
- **Storage**: 5GB free space

## Installation & Setup

### 1. Environment Setup

#### Install XAMPP
```bash
# Download XAMPP from https://www.apachefriends.org/
# Install and start Apache + MySQL services
```

#### Clone Repository
```bash
git clone <repository-url>
cd digitazpannel
```

### 2. Database Setup

#### Create Database
```sql
-- Access phpMyAdmin at http://localhost/phpmyadmin/
CREATE DATABASE digitazio_panel;
USE digitazio_panel;
```

#### Import Database Schema
```bash
# Import the main database structure
mysql -u root -p digitazio_panel < DB.sql

# Import additional tables
mysql -u root -p digitazio_panel < task_management_db.sql
mysql -u root -p digitazio_panel < accounting_db.sql
mysql -u root -p digitazio_panel < inventory_db.sql
mysql -u root -p digitazio_panel < clients_table.sql
mysql -u root -p digitazio_panel < requests_db.sql
mysql -u root -p digitazio_panel < reports_db.sql
```

### 3. PHP Dependencies

#### Install Composer
```bash
# Download and install Composer from https://getcomposer.org/
# Verify installation
composer --version
```

#### Install Dependencies
```bash
# Navigate to project directory
cd c:\xampp\htdocs\digitazpannel

# Install PHP dependencies
composer install

# Update dependencies (if needed)
composer update
```

### 4. Configuration

#### Database Connection
```php
// Edit DB_connection.php
<?php
$servername = "localhost";
$username = "root";
$password = ""; // Set your MySQL password
$dbname = "digitazio_panel";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

#### Tailwind CSS Setup
```bash
# Install Node.js and npm
npm install

# Build Tailwind CSS
npx tailwindcss -i ./css/style.css -o ./css/output.css --watch
```

### 5. Development Server

#### Using XAMPP
```bash
# Start XAMPP Control Panel
# Start Apache and MySQL services
# Access: http://localhost/digitazpannel/
```

#### Using PHP Built-in Server
```bash
# Navigate to project directory
cd c:\xampp\htdocs\digitazpannel

# Start PHP development server
php -S localhost:8080

# Access: http://localhost:8080
```

## Project Structure

```
digitazpannel/
├── admin/                  # Admin-specific functionality
├── app/                    # Application logic
│   ├── Model/             # Data models
│   │   ├── User.php
│   │   ├── Task.php
│   │   ├── Inventory.php
│   │   ├── Accounting.php
│   │   └── Client.php
│   ├── add-*.php          # Add record scripts
│   ├── update-*.php       # Update record scripts
│   └── delete-*.php       # Delete record scripts
├── css/                   # Stylesheets
│   └── style.css         # Main Tailwind CSS file
├── employee/             # Employee-specific functionality
├── img/                  # Image assets
├── inc/                  # Include files
│   ├── header.php        # Page header
│   └── nav.php           # Navigation sidebar
├── js/                   # JavaScript files
│   └── sidebar.js        # Sidebar functionality
├── manager/              # Manager-specific functionality
├── reports/              # Report generation
├── vendor/               # Composer dependencies
├── *.php                 # Main application pages
├── *.sql                 # Database schema files
├── composer.json         # PHP dependencies
├── package.json          # Node.js dependencies
└── tailwind.config.js    # Tailwind configuration
```

## Development Guidelines

### Coding Standards

#### PHP Standards
```php
// Use PSR-12 coding standards
// Always use strict types
<?php declare(strict_types=1);

// Use meaningful variable names
$userTaskCount = count_user_tasks($userId);

// Always sanitize inputs
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);

// Use prepared statements
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
```

#### CSS/Tailwind Standards
```css
/* Use Tailwind utility classes */
.btn-primary {
    @apply bg-atlantis hover:bg-atlantis-dark text-white px-4 py-2 rounded;
}

/* Custom components in @layer components */
@layer components {
    .card {
        @apply bg-white rounded-lg shadow-digitazio p-6;
    }
}
```

#### JavaScript Standards
```javascript
// Use ES6+ features
const toggleSidebar = () => {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
};

// Use event delegation
document.addEventListener('click', (e) => {
    if (e.target.matches('.toggle-btn')) {
        toggleSidebar();
    }
});
```

### Database Design

#### Naming Conventions
- **Tables**: snake_case (e.g., `user_tasks`, `inventory_items`)
- **Columns**: snake_case (e.g., `created_at`, `user_id`)
- **Primary Keys**: `id` (auto-increment)
- **Foreign Keys**: `{table}_id` (e.g., `user_id`, `task_id`)

#### Migration Scripts
```sql
-- Always include migration timestamps
-- migrations/2025_08_29_create_tasks_table.sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    user_id INT NOT NULL,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Testing

### Manual Testing
```bash
# Test database connections
php -r "include 'DB_connection.php'; echo 'Database connected successfully';"

# Test PHP syntax
php -l index.php

# Check for PHP errors
tail -f /path/to/php/error.log
```

### Browser Testing
- **Chrome**: Latest version
- **Firefox**: Latest version
- **Safari**: Latest version
- **Edge**: Latest version

## Deployment

### Production Setup

#### Environment Configuration
```php
// config/production.php
<?php
define('DB_HOST', 'production-host');
define('DB_NAME', 'production-db');
define('DB_USER', 'production-user');
define('DB_PASS', 'secure-password');
define('DEBUG_MODE', false);
?>
```

#### Security Checklist
- [ ] Change default passwords
- [ ] Enable HTTPS
- [ ] Set proper file permissions
- [ ] Configure error reporting
- [ ] Enable security headers
- [ ] Regular security updates

### Build Process
```bash
# Build production CSS
npx tailwindcss -i ./css/style.css -o ./css/style.min.css --minify

# Optimize images
# Minify JavaScript files
# Run security scans
```

## API Documentation

### Authentication Endpoints
```php
// POST /login.php
// Body: { "username": "string", "password": "string" }
// Response: { "success": boolean, "redirect": "string" }

// POST /logout.php
// Response: { "success": boolean }
```

### Task Management Endpoints
```php
// GET /app/get-tasks.php?user_id=1
// Response: Array of task objects

// POST /app/add-task.php
// Body: { "title": "string", "description": "string", "user_id": int }

// PUT /app/update-task.php
// Body: { "id": int, "status": "string" }

// DELETE /app/delete-task.php?id=1
```

## Common Development Tasks

### Adding New Features
1. **Create Database Migration**: Add new tables/columns
2. **Create Model**: Add PHP model class
3. **Create Views**: Add HTML/PHP templates
4. **Add Routes**: Create new PHP pages
5. **Update Navigation**: Modify nav.php if needed
6. **Add Styling**: Update CSS/Tailwind classes
7. **Test Functionality**: Manual and automated testing

### Debugging
```bash
# Enable PHP error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

# Check PHP logs
tail -f /var/log/php_errors.log

# Database debugging
# Enable MySQL query logging
```

## Performance Optimization

### Database Optimization
```sql
-- Add indexes for frequently queried columns
CREATE INDEX idx_user_tasks ON tasks(user_id);
CREATE INDEX idx_task_status ON tasks(status);
CREATE INDEX idx_created_at ON tasks(created_at);
```

### Frontend Optimization
```bash
# Minify CSS
npx tailwindcss -o style.min.css --minify

# Optimize images
# Use WebP format where possible
# Implement lazy loading
```

## Troubleshooting

### Common Issues

#### Database Connection Errors
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection
mysql -u root -p -e "SELECT 1"
```

#### PHP Errors
```bash
# Check PHP version
php --version

# Verify extensions
php -m | grep pdo_mysql
```

#### Permission Issues
```bash
# Set proper permissions
chmod 755 /path/to/digitazpannel
chmod 644 /path/to/digitazpannel/*.php
```

## Contributing

### Development Workflow
1. **Fork Repository**: Create personal fork
2. **Create Branch**: `git checkout -b feature/new-feature`
3. **Make Changes**: Implement feature/fix
4. **Test Changes**: Run all tests
5. **Commit Changes**: `git commit -m "Add new feature"`
6. **Push Branch**: `git push origin feature/new-feature`
7. **Create PR**: Submit pull request

### Code Review Checklist
- [ ] Code follows project standards
- [ ] All tests pass
- [ ] Documentation updated
- [ ] Security considerations addressed
- [ ] Performance impact assessed

---

**Version**: 1.0  
**Last Updated**: August 2025  
**Maintainer**: Development Team
