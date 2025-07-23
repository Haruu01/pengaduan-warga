<?php
// Production Configuration for Pengaduan Warga
// Copy this file to config.php and modify for production use

// Application Configuration
define('BASE_URL', 'https://yourdomain.com/');
define('APP_NAME', 'Sistem Pengaduan Warga');
define('DEBUG', false); // IMPORTANT: Set to false in production

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_user');
define('DB_PASS', 'your_strong_password');
define('DB_NAME', 'pengaduan_warga');

// Upload Configuration
define('UPLOAD_PATH', 'public/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Session Configuration
define('SESSION_NAME', 'pengaduan_session');

// Security Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Email Configuration (for future use)
define('SMTP_HOST', 'your_smtp_host');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your_email@domain.com');
define('SMTP_PASSWORD', 'your_email_password');
define('FROM_EMAIL', 'noreply@yourdomain.com');
define('FROM_NAME', 'Sistem Pengaduan Warga');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (disabled in production)
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '../logs/php_errors.log');

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// HTTPS Enforcement (uncomment if using HTTPS)
// if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
//     $redirectURL = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//     header("Location: $redirectURL");
//     exit();
// }
?>
