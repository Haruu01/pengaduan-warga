# 📤 Upload ke GitHub - Manual Commands

Panduan manual untuk upload Sistem Pengaduan Warga ke GitHub.

## 🔧 Step 1: Setup Git Config (Sekali saja)

```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## 📁 Step 2: Initialize Repository

```bash
# Masuk ke folder project
cd C:\xampp\htdocs\Pengaduan_warga

# Initialize git repository
git init

# Add all files
git add .

# Create initial commit
git commit -m "Initial commit: Sistem Pengaduan Warga with photo display feature"
```

## 🌐 Step 3: Create GitHub Repository

1. **Buka GitHub**: https://github.com/new
2. **Repository name**: `pengaduan-warga`
3. **Description**: `Sistem Pengaduan Warga - Web Application for Citizen Complaints with Photo Display`
4. **Visibility**: Public atau Private (pilih sesuai kebutuhan)
5. **Initialize**: ❌ JANGAN centang "Add a README file" (kita sudah punya)
6. **Click**: "Create repository"

## 🔗 Step 4: Connect to GitHub

```bash
# Add remote origin (ganti username dengan username GitHub Anda)
git remote add origin https://github.com/username/pengaduan-warga.git

# Set main branch
git branch -M main

# Push to GitHub
git push -u origin main
```

## ✅ Step 5: Verify Upload

Setelah upload berhasil, cek di GitHub:
- ✅ Semua file terupload
- ✅ README.md tampil dengan baik
- ✅ Folder structure benar
- ✅ .gitignore berfungsi (file test tidak terupload)

## 🚀 Step 6: Deploy ke Website

### **Option A: Shared Hosting (Recommended)**

#### **1. Download dari GitHub:**
```bash
# Di GitHub repository, klik "Code" → "Download ZIP"
# Extract file ZIP
```

#### **2. Upload via cPanel/File Manager:**
- Login ke hosting control panel
- Buka File Manager
- Upload ZIP ke public_html
- Extract files

#### **3. Setup Database:**
- Buat database baru
- Import file `database/pengaduan_warga.sql`
- Update `app/config/config.php`

### **Option B: VPS/Cloud Server**

#### **1. Clone Repository:**
```bash
# SSH ke server
ssh user@your-server.com

# Clone repository
cd /var/www/html
git clone https://github.com/username/pengaduan-warga.git
```

#### **2. Setup Permissions:**
```bash
sudo chown -R www-data:www-data pengaduan-warga
sudo chmod -R 755 pengaduan-warga
sudo chmod 777 pengaduan-warga/public/uploads
```

#### **3. Setup Database:**
```bash
mysql -u root -p
CREATE DATABASE pengaduan_warga;
CREATE USER 'pengaduan_user'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON pengaduan_warga.* TO 'pengaduan_user'@'localhost';
EXIT;

mysql -u pengaduan_user -p pengaduan_warga < pengaduan-warga/database/pengaduan_warga.sql
```

## ⚙️ Step 7: Configuration

### **Update Database Config:**
```php
// app/config/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'your_db_name');
define('BASE_URL', 'https://yourdomain.com/');
```

### **Test Login:**
- **Admin PIN**: 2024
- **Admin Email**: admin@pengaduan.com
- **Admin Password**: admin123

## 🔄 Step 8: Future Updates

Untuk update code di masa depan:

```bash
# Make changes to your code
# Add changes
git add .

# Commit changes
git commit -m "Update: description of changes"

# Push to GitHub
git push origin main

# Deploy to website
# Option A: Download ZIP dari GitHub dan upload ulang
# Option B: Pull changes di server
git pull origin main
```

## 🛡️ Step 9: Security (PENTING!)

### **Change Default Credentials:**
```sql
-- Login ke database dan jalankan:
UPDATE users SET 
    email = 'your@email.com',
    password = '$2y$10$your_new_hashed_password'
WHERE email = 'admin@pengaduan.com';
```

### **File Permissions:**
```bash
# Set proper permissions
chmod 644 app/config/config.php
chmod 777 public/uploads
chmod 755 public
```

## 📊 Step 10: Test Everything

### **Test Checklist:**
- [ ] Homepage loads: `https://yourdomain.com/`
- [ ] User registration works
- [ ] Admin login works: `https://yourdomain.com/index.php?url=adminauth/pin`
- [ ] File upload works
- [ ] Photo display in admin works
- [ ] Database operations work

## 🆘 Troubleshooting

### **Git Issues:**
```bash
# If you get authentication errors
git config --global credential.helper store

# If you need to change remote URL
git remote set-url origin https://github.com/username/pengaduan-warga.git
```

### **Database Issues:**
```bash
# Test database connection
php -r "
try {
    \$pdo = new PDO('mysql:host=localhost;dbname=pengaduan_warga', 'user', 'pass');
    echo 'Connected successfully';
} catch (PDOException \$e) {
    echo 'Connection failed: ' . \$e->getMessage();
}
"
```

### **File Upload Issues:**
```bash
# Check upload directory
ls -la public/uploads/
chmod 777 public/uploads/

# Check PHP settings
php -i | grep upload
```

## 📞 Support Commands

### **Check Git Status:**
```bash
git status
git log --oneline
git remote -v
```

### **Check File Permissions:**
```bash
ls -la public/uploads/
ls -la app/config/
```

### **Check PHP Info:**
```php
<?php phpinfo(); ?>
```

## 🎉 Success!

Jika semua langkah berhasil:
- ✅ Code tersimpan di GitHub
- ✅ Website berjalan di hosting
- ✅ Database terkoneksi
- ✅ Fitur foto berfungsi
- ✅ Admin dashboard accessible

**Repository GitHub**: `https://github.com/username/pengaduan-warga`
**Live Website**: `https://yourdomain.com`

---

**Selamat! Sistem Pengaduan Warga sudah online! 🚀**
