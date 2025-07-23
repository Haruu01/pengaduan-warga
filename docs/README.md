# 📢 Sistem Pengaduan Warga - GitHub Pages

Selamat datang di halaman GitHub Pages untuk **Sistem Pengaduan Warga**!

## 🌐 Live Demo

**Website Demo**: [https://Haruu01.github.io/pengaduan-warga](https://Haruu01.github.io/pengaduan-warga)

## 📋 Tentang Aplikasi

Sistem Pengaduan Warga adalah aplikasi web berbasis PHP yang memungkinkan masyarakat untuk menyampaikan keluhan dan aspirasi kepada pemerintah secara online dengan fitur upload foto.

### ✨ Fitur Utama

#### 👥 **Untuk Warga:**
- 📝 Submit pengaduan dengan foto
- 📱 Interface responsive dan user-friendly
- 🔍 Tracking status pengaduan real-time
- 📊 Dashboard personal

#### 👨‍💼 **Untuk Admin:**
- 🔐 Login dengan PIN dan kredensial
- 📋 Dashboard kelola pengaduan
- 🖼️ **View foto pengaduan dengan modal preview**
- 📈 Statistik dan laporan
- ⚙️ Manajemen kategori

## 🛠️ Teknologi

- **Backend**: PHP 7.4+ dengan MVC Pattern
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5, HTML5, CSS3, JavaScript
- **Upload**: Support JPEG, PNG, GIF, WebP
- **Security**: Password hashing, SQL injection protection

## 🚀 Quick Start

### 1. Download Source Code
```bash
git clone https://github.com/Haruu01/pengaduan-warga.git
cd pengaduan-warga
```

### 2. Setup Database
```sql
CREATE DATABASE pengaduan_warga;
```

### 3. Import Database
```bash
mysql -u username -p pengaduan_warga < database/pengaduan_warga.sql
```

### 4. Configure
Edit `app/config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'pengaduan_warga');
define('BASE_URL', 'http://yourdomain.com/');
```

### 5. Deploy
Upload ke hosting dan set permissions:
```bash
chmod 755 public/uploads
```

## 🔑 Default Login

### Admin
- **PIN**: `2024`
- **Email**: `admin@pengaduan.com`
- **Password**: `admin123`

## 📁 Struktur Project

```
pengaduan-warga/
├── app/
│   ├── controllers/     # Controller files
│   ├── models/         # Model files
│   ├── views/          # View templates
│   └── config/         # Configuration
├── public/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript
│   ├── images/        # Static images
│   └── uploads/       # User uploads
├── database/          # Database files
└── docs/             # GitHub Pages
```

## 🖼️ Screenshot Features

### Dashboard Admin dengan Photo Display
![Admin Dashboard](https://via.placeholder.com/800x400/28a745/ffffff?text=Admin+Dashboard+with+Photo+Display)

### Modal Preview Foto
![Photo Modal](https://via.placeholder.com/600x400/dc3545/ffffff?text=Photo+Modal+Preview)

### User Dashboard
![User Dashboard](https://via.placeholder.com/800x400/007bff/ffffff?text=User+Dashboard)

## 📖 Documentation

- 📚 [README Lengkap](../README.md)
- 🚀 [Deployment Guide](../DEPLOYMENT.md)
- ⚡ [Quick Deploy](../QUICK-DEPLOY.md)
- 📤 [GitHub Upload Guide](../GITHUB-UPLOAD.md)

## 🔗 Links

- **GitHub Repository**: [https://github.com/Haruu01/pengaduan-warga](https://github.com/Haruu01/pengaduan-warga)
- **Download ZIP**: [https://github.com/Haruu01/pengaduan-warga/archive/main.zip](https://github.com/Haruu01/pengaduan-warga/archive/main.zip)
- **Issues**: [https://github.com/Haruu01/pengaduan-warga/issues](https://github.com/Haruu01/pengaduan-warga/issues)

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

- **GitHub Issues**: [Report bugs or request features](https://github.com/Haruu01/pengaduan-warga/issues)
- **Email**: your.email@example.com

---

⭐ **Jika project ini membantu, berikan star di GitHub!**

**Made with ❤️ for Indonesian communities**
