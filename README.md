# Sistem Pengaduan Warga

Sistem Pengaduan Warga adalah aplikasi web berbasis PHP dengan pola MVC (Model-View-Controller) yang memungkinkan masyarakat untuk menyampaikan keluhan dan aspirasi kepada pemerintah secara online.

https://haruu01.github.io/pengaduan-warga/

## Fitur Utama

### Untuk Warga:
- ✅ Registrasi dan login akun
- ✅ Membuat pengaduan dengan kategori
- ✅ Upload foto pendukung
- ✅ Tracking status pengaduan real-time
- ✅ Dashboard personal
- ✅ Edit profil

### Untuk Admin:
- ✅ Dashboard admin dengan statistik
- ✅ Kelola semua pengaduan
- ✅ Update status dan berikan tanggapan
- ✅ Kelola kategori pengaduan
- ✅ Generate laporan dengan filter
- ✅ Export laporan ke Excel
- ✅ Print laporan

## Teknologi yang Digunakan

- **Backend**: PHP 7.4+ (Native, tanpa framework)
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5.1.3
- **Icons**: Font Awesome 6.0
- **Server**: Apache (XAMPP)

## Struktur Folder

```
Pengaduan_warga/
├── app/
│   ├── config/
│   │   ├── config.php
│   │   └── database.php
│   ├── controllers/
│   │   ├── Admin.php
│   │   ├── Auth.php
│   │   ├── Complaints.php
│   │   ├── Dashboard.php
│   │   ├── Export.php
│   │   └── Home.php
│   ├── core/
│   │   ├── App.php
│   │   └── Controller.php
│   ├── models/
│   │   ├── Category.php
│   │   ├── Complaint.php
│   │   └── User.php
│   ├── views/
│   │   ├── admin/
│   │   ├── auth/
│   │   ├── complaints/
│   │   ├── dashboard/
│   │   ├── home/
│   │   └── inc/
│   └── init.php
├── database/
│   └── pengaduan_warga.sql
├── public/
│   ├── css/
│   ├── js/
│   ├── uploads/
│   ├── .htaccess
│   └── index.php
└── README.md
```

## Instalasi

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache Web Server
- XAMPP (recommended)

### Langkah Instalasi

1. **Clone atau Download Project**
   ```bash
   git clone [repository-url]
   # atau download dan extract ke folder xampp/htdocs/
   ```

2. **Setup Database**
   - Buka phpMyAdmin
   - Import file `database/pengaduan_warga.sql`
   - Database akan otomatis terbuat dengan nama `pengaduan_warga`

3. **Konfigurasi Database**
   - Edit file `app/config/config.php`
   - Sesuaikan pengaturan database jika diperlukan:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'pengaduan_warga');
   ```

4. **Setup Permissions**
   - Pastikan folder `public/uploads/` memiliki permission write
   ```bash
   chmod 755 public/uploads/
   ```

5. **Jalankan Setup Script**
   ```bash
   php setup.php
   ```

6. **Akses Aplikasi**
   - Buka browser dan akses: `http://localhost/Pengaduan_warga/public/`

## Akun Default

### Akun Default
- **Email**: user@demo.com
- **Password**: password123

- - **Email**: admin@demo.com
- **Password**: password123


## Penggunaan

### Untuk Warga:
1. Daftar akun baru atau login
2. Akses dashboard untuk melihat statistik pengaduan
3. Klik "Buat Pengaduan" untuk membuat pengaduan baru
4. Isi form dengan lengkap dan upload foto jika ada
5. Track status pengaduan di dashboard

### Untuk Admin:
1. Login dengan akun admin
2. Akses dashboard admin untuk melihat statistik
3. Kelola pengaduan di menu "Kelola Pengaduan"
4. Update status dan berikan tanggapan
5. Generate laporan di menu "Laporan"
6. Export laporan ke Excel atau print

## Fitur Export Laporan

Sistem menyediakan fitur export laporan yang dapat:
- Filter berdasarkan status, kategori, dan tanggal
- Export ke format Excel (.xls)
- Print laporan langsung dari browser
- Menampilkan statistik lengkap

## Keamanan

- Password di-hash menggunakan PHP `password_hash()`
- Input validation dan sanitization
- Session management
- File upload validation
- SQL injection protection dengan PDO prepared statements



## Scripts

### Setup Script
```bash
php setup.php
```
Menjalankan setup otomatis database dan konfigurasi awal.

### Test Script
```bash
php test.php
```
Menjalankan test dasar untuk memastikan semua komponen berfungsi.

### Deploy Script
```bash
php deploy.php
```
Mempersiapkan sistem untuk deployment production.

## Deployment ke Production

1. **Persiapan Server**
   - PHP 7.4+ dengan ekstensi yang diperlukan
   - MySQL 5.7+
   - Apache/Nginx dengan mod_rewrite
   - SSL certificate (recommended)

2. **Upload Files**
   - Upload semua file ke server
   - Set permission yang tepat (755 untuk folder, 644 untuk file)

3. **Konfigurasi**
   - Copy `app/config/config.production.php` ke `app/config/config.php`
   - Edit konfigurasi sesuai environment production
   - Set `DEBUG = false`

4. **Database Setup**
   - Buat database MySQL
   - Import `database/pengaduan_warga.sql`
   - Atau jalankan `php setup.php`

5. **Security**
   - Pastikan folder `logs/` dan `public/uploads/` tidak dapat diakses langsung
   - Set up firewall dan security headers
   - Ubah password admin default
