<?php
// Konfigurasi aplikasi
define('BASE_URL', 'http://localhost/Pengaduan_warga/public/');
define('APP_NAME', 'Sistem Pengaduan Warga');
define('DEBUG', true); // Set to false in production

// Konfigurasi database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pengaduan_warga');

// Konfigurasi upload
define('UPLOAD_PATH', 'public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Konfigurasi session
define('SESSION_NAME', 'pengaduan_session');

// Admin PIN (hardcoded for security)
define('ADMIN_PIN', '2024');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// PHP 8.2 Compatibility - Hide deprecation warnings in development
if (DEBUG) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    error_reporting(0);
}
?>
