# 🚀 Deployment Guide - Sistem Pengaduan Warga

Panduan lengkap untuk deploy aplikasi Sistem Pengaduan Warga ke berbagai platform hosting.

## 📋 Persiapan Sebelum Deploy

### **1. Checklist File**
- ✅ Semua file aplikasi
- ✅ Database export (.sql)
- ✅ .htaccess configured
- ✅ Config file updated
- ✅ Upload directory permissions

### **2. Database Export**
```bash
# Export database menggunakan script
http://localhost/pengaduan_warga/database/export-database.php

# Atau manual via phpMyAdmin/command line
mysqldump -u username -p pengaduan_warga > pengaduan_warga.sql
```

## 🌐 Deploy ke Shared Hosting (cPanel)

### **Langkah 1: Upload Files**
1. Compress semua file ke ZIP
2. Login ke cPanel
3. Buka File Manager
4. Upload ZIP ke public_html
5. Extract files

### **Langkah 2: Setup Database**
1. Buka MySQL Databases di cPanel
2. Buat database baru: `username_pengaduan`
3. Buat user database dengan password
4. Assign user ke database (ALL PRIVILEGES)
5. Import file .sql via phpMyAdmin

### **Langkah 3: Update Configuration**
Edit `app/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'username_pengaduan');
define('DB_PASS', 'your_password');
define('DB_NAME', 'username_pengaduan');
define('BASE_URL', 'https://yourdomain.com/');
```

### **Langkah 4: Set Permissions**
```bash
chmod 755 public/uploads
chmod 644 public/uploads/.htaccess
chmod 644 .htaccess
```

## 🔧 Deploy ke VPS/Cloud Server

### **Ubuntu/Debian Server**

#### **1. Install Dependencies**
```bash
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-gd php-mbstring
sudo systemctl enable apache2
sudo systemctl enable mysql
```

#### **2. Clone Repository**
```bash
cd /var/www/html
sudo git clone https://github.com/username/pengaduan-warga.git
sudo chown -R www-data:www-data pengaduan-warga
sudo chmod -R 755 pengaduan-warga
sudo chmod -R 777 pengaduan-warga/public/uploads
```

#### **3. Setup Database**
```bash
sudo mysql -u root -p
CREATE DATABASE pengaduan_warga;
CREATE USER 'pengaduan_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON pengaduan_warga.* TO 'pengaduan_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import database
mysql -u pengaduan_user -p pengaduan_warga < database/pengaduan_warga.sql
```

#### **4. Configure Apache**
```bash
sudo nano /etc/apache2/sites-available/pengaduan-warga.conf
```

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/html/pengaduan-warga
    
    <Directory /var/www/html/pengaduan-warga>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/pengaduan_error.log
    CustomLog ${APACHE_LOG_DIR}/pengaduan_access.log combined
</VirtualHost>
```

```bash
sudo a2ensite pengaduan-warga.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🐳 Deploy dengan Docker

### **Dockerfile**
```dockerfile
FROM php:7.4-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql gd

# Enable mod_rewrite
RUN a2enmod rewrite

# Copy application
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html
RUN chmod -R 777 /var/www/html/public/uploads

EXPOSE 80
```

### **docker-compose.yml**
```yaml
version: '3.8'

services:
  web:
    build: .
    ports:
      - "80:80"
    volumes:
      - ./public/uploads:/var/www/html/public/uploads
    depends_on:
      - db
    environment:
      - DB_HOST=db
      - DB_USER=pengaduan_user
      - DB_PASS=pengaduan_pass
      - DB_NAME=pengaduan_warga

  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: pengaduan_warga
      MYSQL_USER: pengaduan_user
      MYSQL_PASSWORD: pengaduan_pass
    volumes:
      - db_data:/var/lib/mysql
      - ./database/pengaduan_warga.sql:/docker-entrypoint-initdb.d/init.sql

volumes:
  db_data:
```

## ☁️ Deploy ke Cloud Platform

### **Heroku**
1. Install Heroku CLI
2. Create Procfile:
   ```
   web: vendor/bin/heroku-php-apache2 public/
   ```
3. Add ClearDB MySQL addon
4. Configure environment variables
5. Deploy:
   ```bash
   heroku create your-app-name
   git push heroku main
   ```

### **DigitalOcean App Platform**
1. Connect GitHub repository
2. Configure build settings
3. Add MySQL database
4. Set environment variables
5. Deploy automatically

### **AWS EC2**
1. Launch EC2 instance (Ubuntu)
2. Install LAMP stack
3. Clone repository
4. Setup RDS MySQL
5. Configure security groups

## 🔒 Security Checklist

### **Production Security**
- ✅ Change default admin credentials
- ✅ Use strong database passwords
- ✅ Enable HTTPS/SSL
- ✅ Update PHP to latest version
- ✅ Disable PHP error display
- ✅ Set proper file permissions
- ✅ Enable firewall
- ✅ Regular security updates

### **File Permissions**
```bash
# Application files
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

# Upload directory
chmod 777 public/uploads

# Config files
chmod 600 app/config/config.php
```

## 🔧 Post-Deployment Configuration

### **1. Update Base URL**
```php
// app/config/config.php
define('BASE_URL', 'https://yourdomain.com/');
```

### **2. Create Admin Account**
```sql
INSERT INTO users (name, email, password, role) VALUES 
('Administrator', 'admin@yourdomain.com', '$2y$10$hash...', 'admin');
```

### **3. Test Functionality**
- ✅ User registration
- ✅ Admin login
- ✅ File upload
- ✅ Database operations
- ✅ Email notifications (if configured)

## 📊 Monitoring & Maintenance

### **Log Files**
- Apache: `/var/log/apache2/`
- PHP: `/var/log/php/`
- Application: `logs/`

### **Backup Strategy**
```bash
# Database backup
mysqldump -u user -p pengaduan_warga > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz public/uploads/
```

### **Performance Optimization**
- Enable PHP OPcache
- Use CDN for static files
- Optimize database queries
- Implement caching
- Compress images

## 🆘 Troubleshooting

### **Common Issues**

#### **Database Connection Error**
```php
// Check config.php values
// Verify database credentials
// Test connection manually
```

#### **File Upload Not Working**
```bash
# Check permissions
chmod 777 public/uploads

# Check PHP settings
upload_max_filesize = 10M
post_max_size = 10M
```

#### **404 Errors**
```apache
# Enable mod_rewrite
sudo a2enmod rewrite

# Check .htaccess
RewriteEngine On
```

## 📞 Support

Jika mengalami masalah deployment:
1. Check error logs
2. Verify configuration
3. Test database connection
4. Check file permissions
5. Contact support

---

**Happy Deploying! 🚀**
