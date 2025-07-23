# ⚡ Quick Deploy Guide

Panduan cepat untuk upload ke GitHub dan deploy ke website.

## 🚀 Step 1: Upload ke GitHub

### **Windows:**
```bash
# Double-click file ini:
setup-git.bat
```

### **Linux/Mac:**
```bash
chmod +x setup-git.sh
./setup-git.sh
```

### **Manual Git Commands:**
```bash
git init
git add .
git commit -m "Initial commit: Sistem Pengaduan Warga"
git remote add origin https://github.com/username/pengaduan-warga.git
git branch -M main
git push -u origin main
```

## 🌐 Step 2: Deploy ke Website

### **Shared Hosting (Recommended for beginners)**

#### **A. cPanel Hosting:**
1. **Download dari GitHub:**
   - Go to your GitHub repo
   - Click "Code" → "Download ZIP"
   - Extract ZIP file

2. **Upload via cPanel:**
   - Login to cPanel
   - Open File Manager
   - Go to public_html
   - Upload ZIP file
   - Extract files

3. **Setup Database:**
   - cPanel → MySQL Databases
   - Create database: `username_pengaduan`
   - Create user with password
   - Import `database/pengaduan_warga.sql`

4. **Update Config:**
   ```php
   // app/config/config.php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'username_pengaduan');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'username_pengaduan');
   define('BASE_URL', 'https://yourdomain.com/');
   ```

5. **Set Permissions:**
   - File Manager → public/uploads
   - Right-click → Permissions → 777

#### **B. Free Hosting (000webhost, InfinityFree, etc.):**
1. Same steps as cPanel
2. Use their file manager
3. Import database via phpMyAdmin
4. Update config accordingly

### **VPS/Cloud Server**

#### **Ubuntu Server:**
```bash
# Install LAMP
sudo apt update
sudo apt install apache2 mysql-server php php-mysql php-gd

# Clone repository
cd /var/www/html
sudo git clone https://github.com/username/pengaduan-warga.git
sudo chown -R www-data:www-data pengaduan-warga
sudo chmod -R 755 pengaduan-warga
sudo chmod 777 pengaduan-warga/public/uploads

# Setup database
sudo mysql -u root -p
CREATE DATABASE pengaduan_warga;
CREATE USER 'pengaduan_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON pengaduan_warga.* TO 'pengaduan_user'@'localhost';
EXIT;

mysql -u pengaduan_user -p pengaduan_warga < pengaduan-warga/database/pengaduan_warga.sql

# Enable mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🔧 Step 3: Configuration

### **Update Base URL:**
```php
// app/config/config.php
define('BASE_URL', 'https://yourdomain.com/');
```

### **Test Login:**
- **Admin PIN:** 2024
- **Admin Email:** admin@pengaduan.com
- **Admin Password:** admin123

## ✅ Step 4: Verification

### **Test These Features:**
- [ ] Homepage loads
- [ ] User registration works
- [ ] Admin login works
- [ ] File upload works
- [ ] Photo display in admin works
- [ ] Database operations work

### **Check These URLs:**
- `https://yourdomain.com/` - Homepage
- `https://yourdomain.com/index.php?url=adminauth/pin` - Admin login
- `https://yourdomain.com/index.php?url=user/register` - User registration

## 🛡️ Step 5: Security (Important!)

### **Change Default Credentials:**
```sql
-- Update admin password
UPDATE users SET password = '$2y$10$newhashedpassword' WHERE email = 'admin@pengaduan.com';

-- Or create new admin and delete default
INSERT INTO users (name, email, password, role) VALUES ('Your Name', 'your@email.com', '$2y$10$hash', 'admin');
DELETE FROM users WHERE email = 'admin@pengaduan.com';
```

### **File Permissions:**
```bash
# Application files: 644
# Directories: 755
# Upload directory: 777
# Config files: 600 (if possible)
```

## 🆘 Common Issues

### **Database Connection Error:**
- Check database credentials in config.php
- Verify database exists
- Test connection manually

### **File Upload Not Working:**
- Check upload directory permissions (777)
- Verify PHP upload settings
- Check .htaccess in uploads folder

### **404 Errors:**
- Enable mod_rewrite
- Check .htaccess file exists
- Verify file permissions

### **Photos Not Displaying:**
- Check upload directory exists
- Verify file permissions
- Check BASE_URL in config

## 📞 Quick Support

### **Test Database Connection:**
```php
// Create test-db.php
<?php
require_once 'app/config/config.php';
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    echo "Database connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

### **Check PHP Info:**
```php
// Create info.php (delete after checking)
<?php phpinfo(); ?>
```

## 🎉 Success!

If everything works:
1. Delete test files (test-db.php, info.php)
2. Change default admin password
3. Test all features thoroughly
4. Set up regular backups
5. Monitor error logs

---

**Your Sistem Pengaduan Warga is now live! 🚀**

Repository: `https://github.com/username/pengaduan-warga`
Website: `https://yourdomain.com`
