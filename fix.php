<?php
/**
 * Quick Fix Script for Common Issues
 */

echo "=== PENGADUAN WARGA QUICK FIX ===\n\n";

// Fix 1: Check and create missing directories
echo "1. Checking directories...\n";
$directories = [
    'public/uploads',
    'logs',
    'app/views/errors'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "   ✓ Created: $dir\n";
        } else {
            echo "   ✗ Failed to create: $dir\n";
        }
    } else {
        echo "   ✓ Exists: $dir\n";
    }
}

// Fix 2: Check .htaccess files
echo "\n2. Checking .htaccess files...\n";

// public/.htaccess
if (!file_exists('public/.htaccess')) {
    $htaccess_content = "Options -Indexes

RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]";
    
    if (file_put_contents('public/.htaccess', $htaccess_content)) {
        echo "   ✓ Created: public/.htaccess\n";
    } else {
        echo "   ✗ Failed to create: public/.htaccess\n";
    }
} else {
    echo "   ✓ Exists: public/.htaccess\n";
}

// public/uploads/.htaccess
if (!file_exists('public/uploads/.htaccess')) {
    $uploads_htaccess = "# Prevent direct access to uploaded files
Options -Indexes

# Allow only image files
<FilesMatch \"\\.(jpg|jpeg|png|gif)$\">
    Order Allow,Deny
    Allow from all
</FilesMatch>

# Deny access to other file types
<FilesMatch \"\\.\">";
    
    if (file_put_contents('public/uploads/.htaccess', $uploads_htaccess)) {
        echo "   ✓ Created: public/uploads/.htaccess\n";
    } else {
        echo "   ✗ Failed to create: public/uploads/.htaccess\n";
    }
} else {
    echo "   ✓ Exists: public/uploads/.htaccess\n";
}

// Fix 3: Check configuration file
echo "\n3. Checking configuration...\n";
if (!file_exists('app/config/config.php')) {
    echo "   ✗ Configuration file missing!\n";
    echo "   Creating basic configuration...\n";
    
    $config_content = "<?php
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

// Timezone
date_default_timezone_set('Asia/Jakarta');
?>";
    
    if (file_put_contents('app/config/config.php', $config_content)) {
        echo "   ✓ Created: app/config/config.php\n";
    } else {
        echo "   ✗ Failed to create: app/config/config.php\n";
    }
} else {
    echo "   ✓ Exists: app/config/config.php\n";
}

// Fix 4: Test database connection
echo "\n4. Testing database connection...\n";
try {
    require_once 'app/config/config.php';
    require_once 'app/config/database.php';
    
    $db = new Database();
    echo "   ✓ Database connection successful\n";
} catch (Exception $e) {
    echo "   ✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "   Please check your database configuration or run setup.php\n";
}

// Fix 5: Check file permissions
echo "\n5. Checking file permissions...\n";
$writable_dirs = ['public/uploads', 'logs'];

foreach ($writable_dirs as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "   ✓ Writable: $dir\n";
        } else {
            if (chmod($dir, 0755)) {
                echo "   ✓ Fixed permissions: $dir\n";
            } else {
                echo "   ✗ Cannot fix permissions: $dir\n";
            }
        }
    }
}

// Fix 6: Create error pages if missing
echo "\n6. Checking error pages...\n";
if (!file_exists('app/views/errors/404.php')) {
    echo "   ⚠ 404 error page missing - creating basic one...\n";
    $error_404 = '<!DOCTYPE html>
<html>
<head><title>404 - Page Not Found</title></head>
<body>
<h1>404 - Page Not Found</h1>
<p>The page you are looking for could not be found.</p>
<a href="' . (defined('BASE_URL') ? BASE_URL : '/') . '">Go Home</a>
</body>
</html>';
    file_put_contents('app/views/errors/404.php', $error_404);
}

if (!file_exists('app/views/errors/500.php')) {
    echo "   ⚠ 500 error page missing - creating basic one...\n";
    $error_500 = '<!DOCTYPE html>
<html>
<head><title>500 - Server Error</title></head>
<body>
<h1>500 - Server Error</h1>
<p>Something went wrong on our end.</p>
<a href="' . (defined('BASE_URL') ? BASE_URL : '/') . '">Go Home</a>
</body>
</html>';
    file_put_contents('app/views/errors/500.php', $error_500);
}

echo "\n=== QUICK FIX COMPLETE ===\n";
echo "Try accessing your application now at: " . (defined('BASE_URL') ? BASE_URL : 'http://localhost/Pengaduan_warga/public/') . "\n";
echo "\nIf you still have issues:\n";
echo "1. Run setup.php to initialize the database\n";
echo "2. Check that Apache mod_rewrite is enabled\n";
echo "3. Make sure you're accessing through the correct URL\n";
echo "4. Check Apache error logs for more details\n";
?>
